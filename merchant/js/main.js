document.addEventListener('DOMContentLoaded', () => {
    const categoryModal = document.getElementById('categoryModal');
    const categoryForm = document.getElementById('categoryForm');
    const categoriesList = document.querySelector('#categoriesList');
    const categoryDeleteBtn = document.querySelector('#categoryDeleteBtn');
    const categoryUpdateBtn = document.querySelector('#categoryUpdateBtn');
    const newCategoryBtn = document.querySelector('#newCategoryBtn');
    const closeBtn = document.querySelector('#categoryCloseBtn');
    const categoriesMenu = document.querySelector('#categoriesMenu');
    const categoriesWrapper = document.querySelector('#categoriesWrapper');
    const categoriesArrow = document.querySelector('#categoriesArrow');
    const formError = document.querySelector('#categoryFormError');
    const modalTitle = document.querySelector('#categoryModalTitle');

    if (!categoryModal || !categoryForm) {
        return;
    }

    function showFormError(message) {
        if (!formError) return;
        if (!message) {
            formError.classList.add('hidden');
            formError.textContent = '';
            return;
        }
        formError.textContent = message;
        formError.classList.remove('hidden');
    }

    function openModal(id = '', name = '') {
        categoryForm.categoryId.value = id || '';
        categoryForm.categoryName.value = name || '';
        showFormError('');
        if (modalTitle) {
            modalTitle.textContent = id ? 'Edit category' : 'New category';
        }
        if (categoryDeleteBtn) {
            categoryDeleteBtn.style.visibility = id ? 'visible' : 'hidden';
        }
        categoryModal.classList.remove('hidden');
        categoryModal.classList.add('flex');
        setTimeout(() => categoryForm.categoryName.focus(), 50);
    }

    function closeModal() {
        categoryModal.classList.add('hidden');
        categoryModal.classList.remove('flex');
        categoryForm.reset();
        categoryForm.categoryId.value = '';
        showFormError('');
    }

    function bindSidebarItems() {
        document.querySelectorAll('.categories-list__item').forEach(item => {
            item.addEventListener('click', function () {
                openModal(
                    item.dataset.id,
                    item.querySelector('.categories-list__item-text').textContent.trim()
                );
            });
        });
    }

    function loadCategories() {
        if (!categoriesList) return;

        fetch('category_handler.php')
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data)) {
                    categoriesList.innerHTML = '<li class="px-3 py-2 text-xs text-red-500">Failed to load</li>';
                    return;
                }
                if (data.length === 0) {
                    categoriesList.innerHTML = '<li class="px-3 py-2 text-xs text-slate-400">No categories yet</li>';
                    return;
                }
                categoriesList.innerHTML = data.map(renderCategoryItem).join('');
                bindSidebarItems();
            })
            .catch(() => {
                categoriesList.innerHTML = '<li class="px-3 py-2 text-xs text-red-500">Failed to load</li>';
            });
    }

    loadCategories();

    if (categoryDeleteBtn) {
        categoryDeleteBtn.addEventListener('click', () => {
            const categoryID = categoryForm.categoryId.value;
            if (!categoryID) return;
            if (!confirm('Delete this category?')) return;

            fetch(`category_handler.php?categoryID=${encodeURIComponent(categoryID)}`, {
                method: 'DELETE',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showFormError(data.error);
                        return;
                    }
                    closeModal();
                    location.reload();
                })
                .catch(() => showFormError('Delete failed. Please try again.'));
        });
    }

    if (newCategoryBtn) {
        newCategoryBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            openModal();
        });
    }

    const pageNewBtn = document.getElementById('pageNewCategoryBtn');
    if (pageNewBtn) {
        pageNewBtn.addEventListener('click', () => openModal());
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    window.addEventListener('click', (e) => {
        if (e.target === categoryModal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !categoryModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    if (categoriesMenu && categoriesWrapper) {
        categoriesMenu.addEventListener('click', () => {
            categoriesWrapper.classList.toggle('hidden');
            if (categoriesArrow) {
                categoriesArrow.classList.toggle('rotate-180');
            }
        });
    }

    if (categoryUpdateBtn) {
        categoryUpdateBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const name = categoryForm.categoryName.value.trim();
            if (!name) {
                showFormError('Category name is required');
                return;
            }

            const formData = new FormData();
            formData.append('categoryId', categoryForm.categoryId.value);
            formData.append('categoryName', name);

            categoryUpdateBtn.disabled = true;
            categoryUpdateBtn.textContent = 'Saving...';

            fetch('category_handler.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showFormError(data.error);
                        return;
                    }
                    closeModal();
                    location.reload();
                })
                .catch(() => showFormError('Save failed. Please try again.'))
                .finally(() => {
                    categoryUpdateBtn.disabled = false;
                    categoryUpdateBtn.textContent = 'Save';
                });
        });
    }

    // Edit buttons on categories page
    document.querySelectorAll('.edit-category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            if (!row) return;
            openModal(row.dataset.id, row.querySelector('.category-name').textContent.trim());
        });
    });

    function renderCategoryItem(category) {
        const count = category.numberOfProducts ?? 0;
        return `
            <li data-id="${category.category_id}" class="categories-list__item cursor-pointer px-3 py-1.5 hover:bg-slate-100 rounded-lg flex justify-between gap-2 items-center">
                <p class="categories-list__item-text truncate">${escapeHtml(category.category_name)}</p>
                <span class="text-xs text-slate-400 shrink-0">${count}</span>
            </li>`;
    }

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
});
