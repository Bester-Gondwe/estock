<div class="products__topbar sub-header flex justify-between items-center mb-4">
    <h4 class="text-xl font-semibold">Products</h4>
    <button class="btn bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" id="addProductBtn">Add Product</button>
</div>

<div class="product-cards grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="product-cards">
    <!-- Dynamic product cards will be inserted here -->
</div>

<!-- Modal -->
<div id="productModal" class="modal fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="modal-dialog bg-white rounded-lg shadow-lg max-w-lg w-full">
        <div class="modal__content">
            <div class="modal__header flex justify-between items-center p-4 border-b">
                <span id="close-btn" class="close text-xl cursor-pointer">&times;</span>
            </div>
            <div class="modal__body p-4">
                <div class="product-details">
                    <form class="product-form" id="product-form" method="POST" enctype="multipart/form-data">
                        <div class="product-details-content grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="hidden" name="productId" id="productId">

                            <!-- Left column -->
                            <div class="product-details-left space-y-4">
                                <div class="input-box">
                                    <label class="input-box__label block text-sm font-medium text-gray-700" for="productName">Product Name</label>
                                    <input class="input-box__field p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" name="productName" id="productName">
                                </div>

                                <div class="input-box">
                                    <label class="input-box__label block text-sm font-medium text-gray-700" for="productDescription">Product Description</label>
                                    <textarea class="input-box__field p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" name="productDescription" id="productDescription"></textarea>
                                </div>

                                <div class="input-box">
                                    <label class="input-box__label block text-sm font-medium text-gray-700" for="categoryName">Category</label>
                                    <select class="input-box__field p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="categoryName" id="categoryName">
                                        <!-- Dynamic categories will be inserted here -->
                                    </select>
                                </div>

                                <div class="flex space-x-4">
                                    <div class="input-box w-1/2">
                                        <label class="input-box__label block text-sm font-medium text-gray-700" for="stockQuantity">Stock Quantity</label>
                                        <input class="input-box__field p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" type="number" name="stockQuantity" id="stockQuantity">
                                    </div>

                                    <div class="input-box w-1/2">
                                        <label class="input-box__label block text-sm font-medium text-gray-700" for="productPrice">Price</label>
                                        <input class="input-box__field p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" type="number" name="productPrice" id="productPrice">
                                    </div>
                                </div>
                            </div>

                            <!-- Right column (Product gallery) -->
                            <div class="product-details-right space-y-4">
                                <div class="primary__img-wrapper mb-4">
                                    <img class="primary__img w-full rounded-md border border-gray-300" id="primaryImg" src="../images/landscape-placeholder-svgrepo-com.svg" alt="Primary Image">
                                </div>

                                <button type="button" class="product-form__file-input bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 flex justify-center items-center" id="productFormFileInput">
                                    <p>Browse File to Upload</p>
                                </button>
                                <input type="file" id="imageInput" name="productImages[]" accept="image/*" hidden multiple>
                                <ul class="preview-list mt-4" id="preview-list">
                                    <!-- Image previews will be inserted here -->
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer p-4 border-t">
                <div class="product-form__ctrls flex justify-between">
                    <button type="button" class="product-form__btn bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" id="delete-btn">DELETE</button>
                    <button type="submit" form="product-form" class="product-form__btn bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600" id="updateBtn">UPDATE</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/products.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const addProductBtn = document.getElementById("addProductBtn");
        const productModal = document.getElementById("productModal");
        const closeBtn = document.getElementById("close-btn");
        const updateBtn = document.getElementById("updateBtn");
        const productId = document.getElementById("productId");

        // Show modal
        addProductBtn.addEventListener("click", () => {
            productModal.classList.remove("hidden");
            productId.value = ''; // Reset form for new product
            updateBtn.textContent = "SAVE";
        });

        // Close modal
        closeBtn.addEventListener("click", () => {
            productModal.classList.add("hidden");
        });

        // Optional: close when clicking outside the modal
        window.addEventListener("click", (e) => {
            if (e.target === productModal) {
                productModal.classList.add("hidden");
            }
        });

        // Initialize button text on page load
        if (productId.value) {
            updateBtn.textContent = "SAVE";
        } else {
            updateBtn.textContent = "UPDATE";
        }

        // Trigger hidden file input
        const fileButton = document.getElementById("productFormFileInput");
        const imageInput = document.getElementById("imageInput");
        fileButton.addEventListener("click", () => {
            imageInput.click();
        });
    });
</script>
