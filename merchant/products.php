<div class="products__topbar sub-header">
    <h4>Products
    </h4>
    <button class="btn" id="addProductBtn">Add Product</button>
</div>

<div class="product-cards" id="product-cards">

</div>
<!-- Modal -->
<div id="productModal" class="modal">
    <div class="modal-dialog">
        <div class="modal__content">
            <div class="modal__header">
                <span id="close-btn" class="close">&times;</span>
                <!-- <h2>Product Form</h2> -->
            </div>
            <div class="modal__body">
                <div class="product-details">
                    <form class="product-form" id="product-form" method="POST" enctype="multipart/form-data">
                        <div class="product-details-content">
                            <input type="hidden" name="productId" id="productId">
                            <div class="product-details-left">
                                <div class="input-box">
                                    <label class="input-box__label" for="">Product Name</label>
                                    <input class="input-box__field" type="text" name="productName" id="productName">
                                </div>
                                <div class="input-box">
                                    <label class="input-box__label" for="">Product Descriprion</label>
                                    <textarea class="input-box__field" rows="2" name="productDescription" id="productDescription"></textarea>
                                </div>
                                <div class="input-box">
                                    <label class="input-box__label" for="">Category</label>
                                    <select class="input-box__field" name="categoryName" id="categoryName">
                                    </select>
                                </div>
                                <div class="d-flex">
                                    <div class="input-box">
                                        <label class="input-box__label" for="">Stock Quantity</label>
                                        <input class="input-box__field" type="number" name="stockQuantity" id="stockQuantity">
                                    </div>
                                    <div class="input-box">
                                        <label class="input-box__label" for="">Price</label>
                                        <input class="input-box__field" type="number" name="productPrice" id="productPrice">
                                    </div>
                                </div>
                            </div>

                            <!-- product gallary -->
                            <div class="product-details-right">
                                <div class="primary__img-wrapper">
                                    <img class="primary__img" id="primaryImg" src="../images/landscape-placeholder-svgrepo-com.svg" alt="Primary Image">
                                </div>

                                <button class="product-form__file-input" id="productFormFileInput">
                                    <!-- <img src="../images/upload-svgrepo-com.svg" width="24" height="24" alt=""> -->
                                    <p>Browse File to Upload</p>
                                </button>
                                <input type="file" id="imageInput" name="productImages[]" accept="image/*" hidden multiple>
                                <ul class="preview-list" id="preview-list">
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div class="product-form__ctrls">
                    <button class="product-form__btn" id="delete-btn">DELETE</button>
                    <button class="product-form__btn" id="updateBtn">UPDATE</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="js/products.js"></script>
<script>
    if (document.querySelector("#productId").value) {
        document.querySelector("#updateBtn").textContent = "SAVE";
    } else {
        document.querySelector("#updateBtn").textContent = "UPDATE";
    }
</script>