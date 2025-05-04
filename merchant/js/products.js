const productForm = document.querySelector("#product-form");
const productFormFileInput = document.querySelector("#productFormFileInput");
const fileInput = document.querySelector("#imageInput");
const previewList = document.querySelector("#preview-list");
const modal = document.getElementById("productModal");
const primaryImageView = document.querySelector("#primaryImg");

let existingImages = [];
let newImages = [];
let deletedExistingImages = [];
let productImagesCount = 0;
let primaryImage = null;

// Load all data on startup
loadProducts();

function loadProducts() {
    fetch("category_handler.php")
        .then(res => res.json())
        .then(data => {
            const options = data?.map(c => `<option value="${c.category_name}">${c.category_name}</option>`) || [];
            document.querySelector("#categoryName").innerHTML = `<option value selected>- select -</option>${options.join("")}`;
        });

    fetch("product_handler.php")
        .then(res => res.json())
        .then(data => {
            document.querySelector("#product-cards").innerHTML = data.map(renderProductCard).join("");
        });
}

function closeModal() {
    modal.classList.remove("open");
    resetForm();
    loadProducts();
}

// Close modal on click outside or close button
window.addEventListener("click", e => e.target === modal && closeModal());
document.querySelector(".close").addEventListener("click", closeModal);

// Trigger file dialog
productFormFileInput.addEventListener("click", e => {
    e.preventDefault();
    fileInput.click();
});

// Handle image selection
fileInput.addEventListener("change", e => {
    e.preventDefault();

    const files = Array.from(e.target.files);
    const allowed = 4 - productImagesCount;

    files.slice(0, allowed).forEach(file => {
        newImages.push(file);
        productImagesCount++;
    });

    renderPreviews();
    fileInput.value = "";
});

// Handle form submission
document.querySelector("#updateBtn").addEventListener("click", e => {
    e.preventDefault();
    const formData = new FormData();

    formData.append("productId", productForm.productId.value);
    formData.append("productName", productForm.productName.value);
    formData.append("productDescription", productForm.productDescription.value);
    formData.append("productPrice", productForm.productPrice.value);
    formData.append("stockQuantity", productForm.stockQuantity.value);
    formData.append("categoryName", productForm.categoryName.value);
    formData.append("primaryImg", primaryImage);

    newImages.forEach((file, index) => {
        formData.append(`productImages[${index}]`, file);
    });

    const removedImageIDs = existingImages
        .filter(img => deletedExistingImages.includes(img.image_id))
        .map(img => img.image_id);

    if (productForm.productId.value) {
        formData.append("action", "update");
        if (removedImageIDs.length) {
            formData.append("removedImgs", JSON.stringify(removedImageIDs));
        }
    }

    fetch("product_handler.php", {
        method: "POST",
        body: formData,
    })
        .then(res => res.text())
        .then(alert)
        .catch(error => alert(error));

    resetForm();
});

// Add Product button
document.querySelector("#addProductBtn").addEventListener("click", () => {
    modal.classList.add("open");
});

// Delete product
function deleteProduct(productId) {
    if (!confirm("Are you sure deleting this product?")) return;

    fetch(`product_handler.php?productId=${productId}`, { method: "DELETE" })
        .then(res => res.text())
        .then(console.log)
        .catch(console.error)
        .finally(loadProducts);
}

// Render product card
function renderProductCard(product) {
    const image = product.primary_image ? `../uploads/${product.primary_image}` : "../images/landscape-placeholder-svgrepo-com.svg";
    return `
        <div class="product-card">
            <div class="product-card__container">
                <div class="product-image">
                    <img src="${image}" alt="Product Image">
                </div>
                <div class="product-info">
                    <p class="product-name">${product.category_name}</p>
                    <p class="product-name">${product.product_name}</p>
                    <p class="product-price">$${product.product_price}</p>
                </div>
            </div>
            <p>${product.product_description}</p>
            <div class="product-actions">
                <button class="btn edit-btn" onclick="editProduct(${product.product_id})">Edit</button>
                <button class="btn delete-btn" onclick="deleteProduct(${product.product_id})">Delete</button>
            </div>
        </div>`;
}

// Edit product
function editProduct(productId) {
    modal.classList.add("open");
    fetch(`product_handler.php?id=${productId}`)
        .then(res => res.json())
        .then(data => {
            productForm.productId.value = data.product_id;
            productForm.productName.value = data.product_name;
            productForm.productDescription.value = data.product_description;
            productForm.productPrice.value = data.product_price;
            productForm.stockQuantity.value = data.quantity;
            productForm.categoryName.value = data.category_name;

            existingImages = data.images || [];
            productImagesCount = existingImages.length;

            productFormFileInput.disabled = productImagesCount >= 4;
            renderPreviews();
        });
}

// Preview rendering
function renderPreviews() {
    previewList.innerHTML = "";

    existingImages.forEach(img => {
        if (!deletedExistingImages.includes(img.image_id)) {
            if (img.is_primary === 1) {
                primaryImageView.src = img.file_name;
                primaryImage = img.image_id;
            }
            createPreviewItem(img.file_name, true, img.image_id);
        }
    });

    newImages.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = () => createPreviewItem(reader.result, false, index);
        reader.readAsDataURL(file);
    });
}

// Create preview item
function createPreviewItem(src, isExisting, index) {
    const item = document.createElement("li");
    item.classList.add("preview-item");

    const imgWrapper = document.createElement("div");
    imgWrapper.classList.add("preview-item__img-wrapper");

    const img = document.createElement("img");
    img.classList.add("preview-item__img");
    img.src = src;
    img.alt = "Preview";

    const deleteBtn = document.createElement("img");
    deleteBtn.src = "../images/close-svgrepo-com.svg";
    deleteBtn.classList.add("preview-item__delete-btn");

    item.addEventListener("mouseover", () => deleteBtn.classList.add("active"));
    item.addEventListener("mouseout", () => deleteBtn.classList.remove("active"));

    deleteBtn.addEventListener("click", e => {
        e.preventDefault();

        if (isExisting) {
            deletedExistingImages.push(index);
        } else {
            newImages.splice(index, 1);
        }

        if (primaryImage === index || primaryImage === `new-${index}`) {
            primaryImage = null;
            primaryImageView.src = "../images/landscape-placeholder-svgrepo-com.svg";
        }

        productImagesCount--;
        productFormFileInput.disabled = false;
        renderPreviews();
    });

    item.addEventListener("click", e => {
        if (e.target === img) {
            primaryImageView.src = src;
            primaryImage = isExisting ? index : `new-${index}`;
        }
    });

    imgWrapper.append(img, deleteBtn);
    item.appendChild(imgWrapper);
    previewList.appendChild(item);
}

// Reset everything
function resetForm() {
    productForm.reset();
    previewList.innerHTML = "";
    primaryImageView.src = "../images/landscape-placeholder-svgrepo-com.svg";

    existingImages = [];
    newImages = [];
    deletedExistingImages = [];
    productImagesCount = 0;
    primaryImage = null;
}
