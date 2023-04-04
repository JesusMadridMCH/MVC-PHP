<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <h1>Product List</h1>
            </ul>

            <div class="input-group-prepend">
                <label class="input-group-text" for="labelMassiveDelete">Options</label>
            </div>
            <select class="custom-select" id="selectMassiveDelete">
                <option value="0">Choose...</option>
                <option value="1">Massive Delete</option>
            </select>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button type="button" class="btn btn-dark ml-10" id="massiveDelete">Apply</button>
                </li>
            </ul>
        </div>
        <div class="add-product">
            <button type="button" class="btn btn-success" id="addProduct">Add Product</button>
        </div>
    </div>

</nav>
<div id="containerType"></div>