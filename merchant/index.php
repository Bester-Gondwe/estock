<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    }
    header("Location: ../login.php");
    exit;
}
// Determine the current page based on the 'p' query parameter
$page = isset($_GET['p']) ? basename($_GET['p']) : 'home';
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../header.php' ?>

<body>
    <div class="container d-gridx2">
        <div class="sidebar">
            <div class="sidebar__header">
                <a class="sidebar__header-brand" href="">
                    <p class="sidebar__brand-text">eStock</p>
                    <img class="sidebar__brand-img" src="../images/shop-bag-with-handle-svgrepo-com.svg"
                        alt="eStock Logo">
                </a>
            </div>
            <nav class="sidebar-nav">
                <ul class="sidebar-nav__items">
                    <li class="sidebar-nav__item <?= $page === 'home' ? 'active' : '' ?>">
                        <a href="./index.php?p=home" class="sidebar-nav__link">
                            <img class="sidebar-nav__icon" src="../images/dashboard-svgrepo-com.svg" alt="Dashboard Icon">
                            <p class="sidebar-nav__link-text">Dashboard</p>
                        </a>
                    </li>

                    <li class="sidebar-nav__item <?= $page === 'products' ? 'active' : '' ?>">
                        <a href="./index.php?p=products" class="sidebar-nav__link">
                            <img class="sidebar-nav__icon" src="../images/album-collection-svgrepo-com.svg" alt="Products Icon">
                            <p class="sidebar-nav__link-text">Products</p>
                        </a>
                    </li>

                    <li class="sidebar-nav__item <?= $page === 'orders' ? 'active' : '' ?>">
                        <a href="./index.php?p=orders" class="sidebar-nav__link">
                            <img class="sidebar-nav__icon" src="../images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
                            <p class="sidebar-nav__link-text">Orders</p>
                        </a>
                    </li>

                    <div class="categories-menu" id="categoriesMenu">
                        <p>Categories</p>
                        <img src="../images/ic_down-arrow.svg" alt="">
                    </div>
                    <div id="categoriesWrapper" class="categories-wrapper">
                        <ul class="categories_list" id="categoriesList"></ul>
                        <p class='new-category-btn' id='newCategoryBtn'>new category</p>
                    </div>

                </ul>
            </nav>
        </div>
        <div class="main-content">
            <?php include_once "../topbar.php"; ?>
            <div class="content">
                <?php
                if (!file_exists($page . ".php")) {
                    include '../404.html';
                } else {
                    include $page . '.php';
                }

                ?>
            </div>
        </div>

    </div>
    <div id="categoryModal" class="modal">
        <div class="modal-dialog category-dialog">
            <div class="modal__content">
                <div class="modal__header">
                    <span id="close-btn" class="close">&times;</span>
                </div>
                <div class="modal__body">
                    <form class="category-form" id="categoryForm" method="POST">
                        <input type="hidden" name="categoryId" id="categoryId">
                        <div class="input-box">
                            <label class="input-box__label" for="">Category Name</label>
                            <input class="input-box__field" type="text" name="categoryName" id="categoryName">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="product-form__ctrls">
                        <button class="product-form__btn" id="categoryDeleteBtn">DELETE</button>
                        <button class="product-form__btn" id="categoryUpdateBtn">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div id="msgBox" class="msg-box-wrapper error">
        <p>product saved sucessfully</p>
    </div> -->
    <script src="js/main.js"></script>
</body>

</html>