$(document).ready(function(){
    // addProduct();
    getProducts();
    // showContainerType(3);

    $("#addProductButton").on('click', (e) => {
        e.preventDefault();
        const skuCode=$("#skuCode").val();
        const name=$("#name").val();
        const price=$("#price").val();
        const typeName=$("#productType").val();
        let checkGenerateFields;
        let productType='';
        let productProperty='';
        let singleMeasurement = [];

        $("#skuCode").css("border", `solid 1px ${checkColorInput("skuCode")}`);
        $("#name").css("border", `solid 1px ${checkColorInput("name")}`);
        $("#price").css("border", `solid 1px ${checkColorInput("price")}`);
        $("#productType").css("border", `solid 1px ${checkColorSelect("productType")}`);

        if($.trim(typeName)!==""){
            checkGenerateFields = generateComponents()[typeName];
            console.log(checkGenerateFields)
            productType=checkGenerateFields.type;

            const fields = Object.keys(checkGenerateFields.components)
            fields.forEach((field) => {
                let fieldName = field.toLowerCase();
                $(`${fieldName}`).css("border", `solid 1px ${checkColorInput(`${fieldName}`)}`);
                console.log(fieldName)
                singleMeasurement.push($(`#${fieldName}`).val());
            })
            singleMeasurement=singleMeasurement.join("X")
        }
        if(checkFields()){
           e.preventDefault();
        }
        else {
            const product = {};
            product["productType"]=typeName;
            product["skuCode"]=skuCode;
            product["name"]=name;
            product["type"]=productType;
            product[checkGenerateFields.property]=singleMeasurement;

            console.log(product)
            addProduct(product)
            // debugger;
        }
    })

    $("#massiveDelete").on('click', () => {
        const optionSelected = $("#selectMassiveDelete").find(":selected").val()
        const productsToBeDeleted = []
        if(optionSelected==1){
            $('#containerType input:checked').each(function() {
                let joinedId = $(this).attr('id').split("product_");
                let realId=joinedId[1];
                productsToBeDeleted.push(realId)
            });
            bodyRequestDeleteProducts=productsToBeDeleted.join("/")
            console.log(bodyRequestDeleteProducts)
            deleteProducts(bodyRequestDeleteProducts)
        }
    })
    $("#addProduct").on('click', () => {
        document.location.href="/addProduct";
    })

    $("#backToProductList").on('click', () => {
        document.location.href="/";
    })

    $("#productType").on('change', () => {
        if($.trim($(this).find(":selected").val())!==""){
            showContainerType($(this).find(":selected").val());
        } else {
            cleanContainerTypeSection();
        }
    })
});
const checkFields = () => {
    let borderColorsFields = [];

    $('#addProductSubmit :input').toArray().forEach((el) => borderColorsFields.push($(el).css("border-color")) )
    return $.inArray("rgb(255, 0, 0)" , borderColorsFields) !== -1
}
const showContainerType = (type) => {
    const container=generateComponents();
    generateFields(container[type])
}
const generateComponents = () => {
    return {
        DvdDisk: {
            components: {"Size": "number"},
            complement: "(in MB)",
            property: "size",
            type: 3
        },
        Furniture: {
            components: {"Height": "number", "Width": "number", "Length": "number"},
            property: "dimensions",
            type: 1
        },
        Book: {
            components: {"Weight": "number"},
            complement: "(in Kg)",
            property: "weight",
            type: 2
        }
    }
}
const productTypes = () =>
{
    return {
        "1": "Furniture",
        "2": "Book",
        "3": "DvdDisk",
    }
}


const generateFields = (product) => {
    let components=product.components
    let fields = Object.keys(components)
    let element = ''
    let complementText = "complement" in product? product["complement"] : "";
    fields.forEach((field) => {
        let title = field+" "+complementText
        element+=`
        <div class="form-group">
            <label>${title}</label>
            <input type="${components[field]}" id="${field.toLowerCase()}" value="" class="form-control" required>
        </div>`;
    })
    /* Clean Content Before Insert*/
    cleanContainerTypeSection();
    return $(element).appendTo("#productTypeSection")
}

const cleanContainerTypeSection = () =>   $("#productTypeSection").html("");
const editContainer = (element) => {
    const properties = Object.keys(element);
    const propertyShow=properties[1];
    const titleProperty=properties[1][0].toUpperCase()+properties[1].slice(1);
    const property = productTypes()[element.type]
    const complement = generateComponents()[property].complement!==undefined?generateComponents()[property].complement : ""

    $(`<div class="square-img-container">
        <div class="form-check float-left">
          <input class="form-check-input" type="checkbox" value="" id="product_${element.skuCode}">
        </div>
        <div class="infoContent">
            <h1>${element.skuCode}</h1>
            <p>${element.name}</p>
            <p>${element.price} </p>
            <h5>${titleProperty}: ${element[propertyShow]} ${complement}</h5>
        </div>
        
</div>`).appendTo("#containerType");
}

const checkColorInput = (field) => $.trim($(`#${field}`).val())=="" || $.trim($(`#${field}`).val())=="0.00"? "red" : "#cccccc";
const checkColorSelect = (field) => $.trim($(`#${field}`).find(":selected").val())==""? "red" : "#cccccc";

const addProduct = (product) => {
    $.post("http://localhost/addproduct",
        product ,
        (data, status) => {
          // console.log(data)
            if(Object.keys(data).length) {
                let response= JSON.parse(data)
                if(!response.success)
                    console.log(response.message)
                else
                    setTimeout(() => window.location ="/", 3000)
            }
        })
}
const getProducts = () => {
    $.ajax({
        Type:'GET',
        url:'http://localhost/products',
        contentType:'application/json',
        success: (data) => {
            // console.log(data)
            if(Object.keys(data).length){
                let response= JSON.parse(data)
                response.forEach(element => {
                    editContainer(element)
                })
            } else {
                console.log("no data found");
            }
        }, error:(error)=>  console.log(error)
    })
}
const deleteProducts = (bodyRequestDeleteProducts) => {
    $.ajax({
        Type:'GET',
        url:'http://localhost/deleteproduct/'+bodyRequestDeleteProducts,
        contentType:'application/json',
        success: (data) => {
            console.log(data)
            if(Object.keys(data).length){
                if(Object.keys(data).length){
                    let response= JSON.parse(data)
                    if(response.success)
                        setTimeout(() => location.reload(), 3000)
                } else {
                    console.log("no data found");
                }
            } else {
                console.log("no data found");
            }
        }, error:(error)=>  console.log(error)
    })
}

