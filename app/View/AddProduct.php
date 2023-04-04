
<div style="margin-bottom: 3em;">
    <button type="button" id="backToProductList" class="btn btn-danger">Cancel</button>
</div>
<h1>Add Product</h1>
<form id="addProductSubmit" >
    <div class="container">
        <div class="form-group">
            <label>SkuCode</label>
            <input type="text" id="skuCode" value="" class="form-control" required>
            <!--    <div class="invalid-feedback"></div>-->
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" id="name" value="" class="form-control" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="form-group">
            <label>$ Price</label>
            <input type="number" id="price" value="" class="form-control">
            <div class="invalid-feedback"></div>
        </div>
        <div class="select-product-type">
            <label>Product Type</label>
            <select class="form-select" id="productType" aria-label="Default select example">
                <option value="">Choose..</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
                <option value="DvdDisk">DvdDisk</option>
            </select>
        </div>
        <div class="product-type-section" id="productTypeSection">
        </div>
        <button  style="margin-top: 3em" type="submit" id="addProductButton" class="btn btn-success">Add Product</button>
    </div>
</form>


