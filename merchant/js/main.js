document.addEventListener("DOMContentLoaded", () => {
    const profileBtn = document.querySelector('#profile-btn');
    const popupMenu = document.querySelector('#popup-menu');
    const categoryModal = document.getElementById('categoryModal');
    const categoryForm = document.getElementById('categoryForm');
    const categoriesList = document.querySelector("#categoriesList");
    const categoryDeleteBtn = document.querySelector("#categoryDeleteBtn");
    const categoryUpdateBtn = document.querySelector("#categoryUpdateBtn");
    const newCategoryBtn = document.querySelector("#newCategoryBtn");
    const closeBtn = document.querySelector("#close-btn");
    const categoriesMenu = document.querySelector("#categoriesMenu");
    const categoriesWrapper = document.querySelector("#categoriesWrapper");

    // Load categories
    fetch("category_handler.php")
        .then(response => response.json())
        .then(data => {
            if (data && Array.isArray(data)) {
                categoriesList.innerHTML = data.map(renderCategoryItem).join('');
                document.querySelectorAll(".categories-list__item").forEach(item => {
                    item.addEventListener('click', function () {
                        categoryForm.categoryId.value = item.dataset.id;
                        categoryForm.categoryName.value = item.querySelector(".categories-list__item-text").textContent;
                        categoryModal.classList.add('open');
                    });
                });
            }
        })
        .catch(error => console.error("Failed to load categories:", error));

    // Delete category
    if (categoryDeleteBtn) {
        categoryDeleteBtn.addEventListener('click', function () {
            const categoryID = categoryForm.categoryId.value;
            if (categoryID) deleteCategory(categoryID);
        });
    }

    // Add new category
    if (newCategoryBtn) {
        newCategoryBtn.addEventListener('click', function () {
            categoryModal.classList.add("open");
        });
    }

    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            closeModal();
        });
    }

    // Toggle profile menu
    if (profileBtn) {
        profileBtn.addEventListener('click', function () {
            popupMenu.classList.toggle("show");
        });
    }

    // Click outside modal closes it
    window.addEventListener('click', (e) => {
        if (e.target === categoryModal) {
            closeModal();
        }
    });

    // Toggle categories section
    if (categoriesMenu) {
        categoriesMenu.addEventListener('click', function () {
            categoriesWrapper.classList.toggle('show');
        });
    }

    // Submit form (update or create)
    if (categoryUpdateBtn) {
        categoryUpdateBtn.addEventListener('click', function (e) {
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
                    alert(data);
                    closeModal();
                    location.reload(); // reload to show updated list
                })
                .catch(error => {
                    alert("An error occurred: " + error);
                });
        });
    }

    function closeModal() {
        categoryModal.classList.remove('open');
        categoryForm.reset();
    }

    function deleteCategory(categoryID) {
        if (confirm("Are you sure you want to delete this category?")) {
            fetch(`category_handler.php?categoryID=${categoryID}`, {
                method: "DELETE"
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    closeModal();
                    location.reload(); // reload to show updated list
                })
                .catch(error => {
                    alert("An error occurred: " + error);
                });
        }
    }

    function renderCategoryItem(category) {
        return `
            <li data-id='${category.category_id}' class='categories-list__item cursor-pointer p-2 hover:bg-gray-100'>
                <p class='categories-list__item-text'>${category.category_name}</p>
                <div class='categories-list__products-count-wrapper'>
                    <p class='categories-list__products-count text-sm text-gray-500'>${category.numberOfProducts}</p>
                </div>
            </li>`;
    }
});
