const profileBtn = document.querySelector('#profile-btn');
const popupMenu = document.querySelector('#popup-menu');
const categoryModal = document.getElementById('categoryModal');
const categoryForm = document.getElementById('categoryForm');

fetch("category_handler.php")
    .then(response => response.json())
    .then(data => {
        if (data) {
            document.querySelector("#categoriesList").innerHTML =  data.map(renderCategoryItem).join('');
            document.querySelectorAll(".categories-list__item").forEach(item => {
                item.addEventListener('click', function () {
                    categoryForm.categoryId.value = item.dataset.id;
                    categoryForm.categoryName.value = item.querySelector(".categories-list__item-text").innerHTML;
                    categoryModal.classList.add('open');
                })
            })
        }
    })

document.querySelector("#categoryDeleteBtn").addEventListener('click', function () {
    const categoryID = categoryForm.categoryId.value;
    if (categoryID != null)
        deleteCategory(categoryID);
})
document.querySelector("#newCategoryBtn").addEventListener('click', function () {
    categoryModal.classList.add("open");
})


document.querySelector("#close-btn").addEventListener('click', function () {
    closeModal();
})

profileBtn.addEventListener('click', function (e) {
    popupMenu.classList.toggle("show");

});

// Close categoryModal when clicking outside the categoryModal content
window.addEventListener('click', (e) => {
    if (e.target === categoryModal) {
        closeModal();
    }
});

function closeModal() {
    categoryModal.classList.remove('open');
    categoryForm.categoryId.value = null;
    categoryForm.categoryName.value = null;
}

function deleteCategory(categoryID) {

    if (confirm("Are you sure deleting this category ?")) {
        fetch(`category_handler.php?categoryID=${categoryID}`, {
            method: "DELETE"
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                closeModal();
            })
            .catch(error => {
                console.log("error");
            });
    }
}

// Handle form submission
document.querySelector("#categoryUpdateBtn").addEventListener('click', function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("categoryId", categoryForm.categoryId.value);
    formData.append("categoryName", categoryForm.categoryName.value);

    if (categoryForm.categoryId.value) {
        formData.append("action", "update");
    }

    fetch("category_handler.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.log(error);
        });
});

document.querySelector("#categoriesMenu").addEventListener('click', function () {
    document.querySelector("#categoriesWrapper").classList.toggle('show');
})

function renderCategoryItem(category) {
    return `<li data-id='${category.category_id}' class='categories-list__item'>
    <p class='categories-list__item-text'>${category.category_name}</p>
    <div class='categories-list__products-count-wrapper'>
        <p class='categories-list__products-count'>${category.numberOfProducts}</p>
        </div>
    </li>`;
}