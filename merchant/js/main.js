document.addEventListener('DOMContentLoaded', () => {
    const profileBtn = document.querySelector('#profile-btn');
    const popupMenu = document.querySelector('#popup-menu');
    const categoryModal = document.getElementById('categoryModal');
    const categoryForm = document.getElementById('categoryForm');
    const categoriesList = document.querySelector('#categoriesList');
    const categoryDeleteBtn = document.querySelector('#categoryDeleteBtn');
    const categoryUpdateBtn = document.querySelector('#categoryUpdateBtn');
    const newCategoryBtn = document.querySelector('#newCategoryBtn');
    const closeBtn = document.querySelector('#close-btn');
    const categoriesMenu = document.querySelector('#categoriesMenu');
    const categoriesWrapper = document.querySelector('#categoriesWrapper');

    function openModal() {
        categoryModal.classList.remove('hidden');
        categoryModal.classList.add('flex');
    }

    function closeModal() {
        categoryModal.classList.add('hidden');
        categoryModal.classList.remove('flex');
        categoryForm.reset();
        categoryForm.categoryId.value = '';
    }

    fetch('category_handler.php')
        .then(response => response.json())
        .then(data => {
            if (data && Array.isArray(data)) {
                categoriesList.innerHTML = data.map(renderCategoryItem).join('');
                document.querySelectorAll('.categories-list__item').forEach(item => {
                    item.addEventListener('click', function () {
                        categoryForm.categoryId.value = item.dataset.id;
                        categoryForm.categoryName.value = item.querySelector('.categories-list__item-text').textContent.trim();
                        openModal();
                    });
                });
            }
        })
        .catch(error => console.error('Failed to load categories:', error));

    if (categoryDeleteBtn) {
        categoryDeleteBtn.addEventListener('click', () => {
            const categoryID = categoryForm.categoryId.value;
            if (categoryID) deleteCategory(categoryID);
        });
    }

    if (newCategoryBtn) {
        newCategoryBtn.addEventListener('click', () => {
            categoryForm.reset();
            categoryForm.categoryId.value = '';
            openModal();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (profileBtn && popupMenu) {
        profileBtn.addEventListener('click', () => popupMenu.classList.toggle('hidden'));
    }

    window.addEventListener('click', (e) => {
        if (e.target === categoryModal) closeModal();
    });

    if (categoriesMenu && categoriesWrapper) {
        categoriesMenu.addEventListener('click', () => {
            categoriesWrapper.classList.toggle('hidden');
        });
    }

    if (categoryUpdateBtn) {
        categoryUpdateBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('categoryId', categoryForm.categoryId.value);
            formData.append('categoryName', categoryForm.categoryName.value);

            fetch('category_handler.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || data.error || 'Done');
                    closeModal();
                    location.reload();
                })
                .catch(error => alert('An error occurred: ' + error));
        });
    }

    function deleteCategory(categoryID) {
        if (!confirm('Are you sure you want to delete this category?')) return;
        fetch(`category_handler.php?categoryID=${categoryID}`, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => {
                alert(data.message || data.error || 'Done');
                closeModal();
                location.reload();
            })
            .catch(error => alert('An error occurred: ' + error));
    }

    function renderCategoryItem(category) {
        return `
            <li data-id="${category.category_id}" class="categories-list__item cursor-pointer px-2 py-1.5 hover:bg-slate-100 rounded flex justify-between gap-2">
                <p class="categories-list__item-text truncate">${category.category_name}</p>
                <p class="categories-list__products-count text-xs text-slate-400">${category.numberOfProducts}</p>
            </li>`;
    }
});
