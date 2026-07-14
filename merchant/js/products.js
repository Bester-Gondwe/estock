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
    modal.classList.remove('open', 'show', 'flex');
    modal.classList.add('hidden');
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
    if (productForm.sku) formData.append("sku", productForm.sku.value);
    if (productForm.lowStockThreshold) formData.append("lowStockThreshold", productForm.lowStockThreshold.value);
    formData.append("primaryImg", primaryImage);

    newImages.forEach((file) => {
        formData.append('productImages[]', file);
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
    modal.classList.remove("hidden");
    modal.classList.add("flex");
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
    const image = product.primary_image
        ? `../uploads/${product.primary_image}`
        : "../images/landscape-placeholder-svgrepo-com.svg";
    const stockClass = Number(product.quantity) <= Number(product.low_stock_threshold || 5)
        ? 'text-amber-600'
        : 'text-slate-500';
    return `
        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
            <img src="${image}" alt="" class="w-full h-40 object-cover bg-slate-100" onerror="this.src='../assets/default-image.svg'">
            <div class="p-4 space-y-2">
                <p class="text-xs text-slate-400">${product.category_name || ''}</p>
                <p class="font-semibold truncate">${product.product_name}</p>
                <p class="text-emerald-700 font-medium">MWK ${Number(product.product_price).toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                <p class="text-xs ${stockClass}">Stock: ${product.quantity ?? 0}${product.sku ? ' · SKU ' + product.sku : ''}</p>
                <p class="text-sm text-slate-500 line-clamp-2">${product.product_description || ''}</p>
                <div class="flex gap-2 pt-2">
                    <button type="button" class="flex-1 bg-emerald-600 text-white text-sm py-1.5 rounded-lg hover:bg-emerald-700" onclick="editProduct(${product.product_id})">Edit</button>
                    <button type="button" class="flex-1 bg-red-500 text-white text-sm py-1.5 rounded-lg hover:bg-red-600" onclick="deleteProduct(${product.product_id})">Delete</button>
                </div>
            </div>
        </div>`;
}

// Edit product
function editProduct(productId) {
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    fetch(`product_handler.php?id=${productId}`)
        .then(res => res.json())
        .then(data => {
            productForm.productId.value = data.product_id;
            productForm.productName.value = data.product_name;
            productForm.productDescription.value = data.product_description;
            productForm.productPrice.value = data.product_price;
            productForm.stockQuantity.value = data.quantity;
            productForm.categoryName.value = data.category_name;
            if (productForm.sku) productForm.sku.value = data.sku || '';
            if (productForm.lowStockThreshold) productForm.lowStockThreshold.value = data.low_stock_threshold || 5;

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
