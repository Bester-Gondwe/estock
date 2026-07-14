<?php
require_once __DIR__ . '/../models/Category.php';

$categoryModel = new Category();
$categories = $categoryModel->countMerchantCategories($_SESSION['user_id']);
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <p class="text-lg font-semibold">Categories</p>
        <p class="text-sm text-slate-500">
            <a href="./index.php?p=home" class="text-emerald-600 hover:underline">Home</a> &gt; Categories
        </p>
    </div>
    <button type="button" id="pageNewCategoryBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + New category
    </button>
</div>

<div class="overflow-x-auto border border-slate-200 rounded-xl">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Your products</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-slate-400">No categories yet. Create one to organize products.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <tr class="border-t border-slate-100" data-id="<?= (int) $cat['category_id'] ?>">
                        <td class="px-4 py-3 font-medium category-name"><?= htmlspecialchars($cat['category_name']) ?></td>
                        <td class="px-4 py-3 text-slate-500"><?= (int) $cat['numberOfProducts'] ?></td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button type="button" class="edit-category-btn text-emerald-600 hover:underline">Edit</button>
                            <button type="button" class="delete-category-btn text-red-600 hover:underline"
                                    data-id="<?= (int) $cat['category_id'] ?>"
                                    data-name="<?= htmlspecialchars($cat['category_name'], ENT_QUOTES) ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('.delete-category-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        if (!confirm(`Delete category "${name}"?`)) return;
        fetch(`category_handler.php?categoryID=${encodeURIComponent(id)}`, { method: 'DELETE' })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                location.reload();
            })
            .catch(() => alert('Failed to delete category'));
    });
});
</script>
