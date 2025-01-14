const productForm = document.querySelector("#product-form");
const productFormFileInput = document.querySelector("#productFormFileInput");
const fileInput = document.querySelector("#imageInput");
const previewList = document.querySelector("#preview-list");
const modal = document.getElementById('productModal');
const primaryImageView = document.querySelector("#primaryImg");


let existingImages = [];
let newImages = []; // Array to keep track of selected files
let removedImageIDs = [];
let productImagesCount = 0;
let primaryImage = null;
let deletedExistingImages = [];

function loadProducts() {
    fetch(`category_handler.php`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.querySelector("#categoryName").innerHTML = "<option value selected>- select -</option>" + data.map((category) => `<option value='${category.category_name}'>${category.category_name}</option>`).join("");
            }
        });

    fetch(`product_handler.php`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.querySelector("#product-cards").innerHTML = data.map(renderProductCard).join("");
            }
        });




}

loadProducts();

document.querySelector('.close').addEventListener('click', closeModal);

// Close modal when clicking outside the modal content
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});

function closeModal() {
    modal.classList.remove('open');

    //reset
    newImages = [];
    removedImageIDs = [];
    productImagesCount = 0;
    previewList.innerHTML = "";

    clearProductForm();

    loadProducts();
}


productForm.addEventListener('submit', (e) => {
    e.preventDefault();
});


function deleteProduct(productId) {

    if (confirm("Are you sure deleting this product ?")) {
        fetch(`product_handler.php?productId=${productId}`, {
            method: "DELETE"
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.log("error");
            });
        loadProducts();
    }
}


document.querySelector("#addProductBtn").addEventListener('click', function () {
    modal.classList.add("open");
})

// Handle form submission
document.querySelector("#updateBtn").addEventListener('click', function (e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append("productId", productForm.productId.value);
    formData.append("productName", productForm.productName.value);
    formData.append("productDescription", productForm.productDescription.value);
    formData.append("productPrice", productForm.productPrice.value);
    formData.append("stockQuantity", productForm.stockQuantity.value);
    formData.append("categoryName", productForm.categoryName.value);

    // Append selected files
    newImages.forEach((file, index) => {
        formData.append(`productImages[${index}]`, file);
    });

    formData.append("primaryImg", primaryImage);


    let removedImageIDs = [];
    existingImages.filter((image, index) => deletedExistingImages.includes(image.image_id)).forEach((img) => removedImageIDs.push(img.image_id));

    if (productForm.productId.value) {
        formData.append("action", "update");
        if (removedImageIDs.length > 0)
            formData.append("removedImgs", JSON.stringify(removedImageIDs));
    }

    fetch("product_handler.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.log("error");
        });

    existingImages = [];
    newImages = []; // Array to keep track of selected files
    removedImageIDs = [];
    productImagesCount = 0;
    primaryImage = null;
    deletedExistingImages = [];
});

//Trigger the file input when the button is clicked
productFormFileInput.addEventListener("click", (e) => {
    e.preventDefault();
    fileInput.click();
});

// Handle new file selection
fileInput.addEventListener("change", function (e) {
    e.preventDefault(); // Prevent accidental form submission

    const files = Array.from(e.target.files);
    const remainingImages = 4 - productImagesCount;
    if (remainingImages > 0) {
        for (let i = 0; i < remainingImages; i++) {
            newImages.push(files[i]);
            productImagesCount++;
        }
        console.log(productImagesCount)
        if (productImagesCount == 4)
            productFormFileInput.disabled = true;
        renderPreviews();
    }

    // reset file input
    fileInput.value = "";
});


function createPreviewItem(src, isExistingImg, index) {

    const previewItem = document.createElement("li");
    previewItem.classList.add("preview-item")

    const previewItemImg = document.createElement("img");

    previewItemImg.classList.add("preview-item__img");
    previewItemImg.src = src;
    previewItemImg.alt = "Preview";

    const previewItemImgWrapper = document.createElement("div");

    previewItemImgWrapper.classList.add("preview-item__img-wrapper");

    const previewItemDeleteBtn = document.createElement("img");
    previewItemDeleteBtn.src = "../images/close-svgrepo-com.svg";

    previewItem.addEventListener('mouseover', () => {
        previewItemDeleteBtn.classList.add('active');
    });

    previewItem.addEventListener('mouseout', () => {
        previewItemDeleteBtn.classList.remove('active');
    });


    previewItemDeleteBtn.classList.add("preview-item__delete-btn");

    // Add event listener to remove the image
    previewItemDeleteBtn.addEventListener("click", (e) => {
        e.preventDefault();

        if (isExistingImg) {
            deletedExistingImages.push(index);
        } else {
            newImages.splice(index, 1);
        }

        if (primaryImage == index || primaryImage == `new-${index}`) {
            primaryImageView.src = "../images/landscape-placeholder-svgrepo-com.svg";
            primaryImage = null;
        }

        renderPreviews();
        previewItem.remove(); // Remove the preview
        productImagesCount--;
        productFormFileInput.disabled = false;
    });



    previewItem.addEventListener('click', function (e) {
        if (e.target == previewItemImg) {
            primaryImageView.src = src;

            if (!isExistingImg)
                primaryImage = `new-${index}`;
            else
                primaryImage = index
        }
    })

    // previewItem.appendChild(previewItemImg);
    previewItem.appendChild(previewItemImgWrapper);
    previewItemImgWrapper.appendChild(previewItemImg);
    previewItemImgWrapper.appendChild(previewItemDeleteBtn);
    previewList.appendChild(previewItem);
}


function renderProductCard(product) {
    return `
             <div class="product-card">
                <div class="product-card__container">
                <div class="product-image">
                    <img src="${product.primary_image === null ? '../images/landscape-placeholder-svgrepo-com.svg' : '../uploads/' + product.primary_image}" alt="Product Image">
                </div>
                <div class="product-info">
                    <p class="product-name">${product.category_name}</p>
                    <p class="product-name">${product.product_name}</p>
                    <p class="product-price">$${product.product_price}</p>
                </div>
            </div>
            <p>${product.product_description}</p>
            <div class="product-actions">
                <button id="btn-edit" class="btn edit-btn" onclick="editProduct(${product.product_id})" >Edit</button>
                <button class="btn delete-btn"  onclick="deleteProduct(${product.product_id})">Delete</button>
            </div>
        </div>`;
}



function editProduct(productId) {
    // open modal
    modal.classList.add('open');
    fetch(`product_handler.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.querySelector("#productId").value = data.product_id;
                document.querySelector("#productName").value = data.product_name;
                document.querySelector("#productDescription").value = data.product_description;
                document.querySelector("#productPrice").value = data.product_price;
                document.querySelector("#stockQuantity").value = data.quantity;
                document.querySelector("#categoryName").value = data.category_name;
                existingImages = data.images;
                productImagesCount = existingImages.length
                if (productImagesCount == 4) {
                    productFormFileInput.disabled = true
                }
                renderPreviews();
            }
        });
}


function renderPreviews() {
    const container = document.getElementById('preview-list');
    container.innerHTML = ''; // Clear existing previews

    // Render existing images
    existingImages.forEach((image, index) => {

        if (image.is_primary == 1 && !deletedExistingImages.includes(image.image_id)) {
            primaryImageView.src = image.file_name;
            primaryImage = image.image_id;
        }

        if (!deletedExistingImages.includes(image.image_id))
            createPreviewItem(image.file_name, true, image.image_id);
    });

    // Render new images
    newImages.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = () => {
            createPreviewItem(reader.result, false, index);
        };
        reader.readAsDataURL(file);
    });
}

function clearProductForm() {
    document.querySelector("#productId").value = null;
    document.querySelector("#productName").value = null;
    document.querySelector("#productDescription").value = null;
    document.querySelector("#productPrice").value = null;
    document.querySelector("#stockQuantity").value = null;
    document.querySelector("#categoryName").value = null;

    primaryImageView.src = "../images/landscape-placeholder-svgrepo-com.svg";
    previewList.innerHTML = "";

    existingImages = [];
}

