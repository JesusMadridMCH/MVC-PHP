
<div style="margin-bottom: 3em;">
    <button type="button" id="backToProductList" class="btn btn-danger btn-lg">Cancel</button>
</div>
<div class="title-section"><h1>Add Product</h1></div>
<form id="addProductSubmit" >
    <div class="container">
        <div class="form-group">
            <label class="h6">SkuCode</label><label class="already-exists-error" id="alreadyExistsError"></label>
            <input type="text" id="skuCode" value="" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="h6">Name</label>
            <input type="text" id="name" value="" class="form-control" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="form-group">
            <label class="h6">$ Price</label>
            <input type="number" id="price" value="" class="form-control">
            <div class="invalid-feedback"></div>
        </div>
        <div class="select-product-type">
            <label class="h6">Product Type</label>
            <select class="form-select" id="productType" aria-label="Default select example">
                <option value="">Choose..</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
                <option value="DvdDisk">DvdDisk</option>
            </select>
        </div>
        <div class="product-type-section" id="productTypeSection"></div>
        <div style="color:darkred" class="mt-3" id="submitErrorContainer"></div>
        <button  style="margin-top: 3em" type="submit" id="addProductButton" class="btn btn-success btn-lg">Add Product</button>
    </div>
</form>


