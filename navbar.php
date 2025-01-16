<header class="main-header">
    <div class="container" style="display: flex;align-items: center;justify-content: space-between;">
        <a class="main-header__brand" href="">eStore</a>
        <nav class="nav">
            <ul class="nav__list">
                <li class="nav__item"><a class="nav__link" href="./">Home</a></li>
               
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle nav__link">Category <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        require_once "models/Category.php";
                        $category = new Category();
                        foreach ($category->getAllCategories() as $catg) {
                            echo "<li><a href='category.php?category=" . $catg['category_name'] . "'>" . $catg['category_name'] . "</a></li>";
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="custom-menu">
            <ul class="nav nav__list">
                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img class="custom-menu-cart" src="images/cart.png" alt="Cart">
                        <span id="cartCount" class="cart_count"><?php if (empty($_SESSION['cart'])) echo 0;
                                                                else {
                                                                    echo count($_SESSION['cart']);
                                                                } ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <ul class="menu" id="cart_menu">
                        </ul>
                        <li class="footer"><a href="cart.php">Go to Cart</a></li>
                    </ul>
                </li>

                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li class="dropdown">
                        <span class="dropdown-toggle user-profile" ><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?></span>
                        <ul class="dropdown-menu">
                            <li class="user-footer">
                                <a href="logout.php">Sign out</a>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li><a class="nav__link" href='login.php'>Login</a></li>
                    <li> <a class="nav__link" href='register.php'>Sign up</a></li>
                <?php }  ?>
            </ul>
        </div>
    </div>
</header>