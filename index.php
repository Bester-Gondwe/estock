<?php
require_once "models/Category.php";
$category = new Category();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eStock</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dropdown.less">
</head>

<body>
  <header class="main-header">
    <div class="container" style="display: flex;align-items: center;justify-content: space-between;">
      <a class="main-header__brand" href="">eStore</a>
      <nav class="nav">
        <ul class="nav__list">
          <li class="nav__item"><a class="nav__link" href="">Home</a></li>
          <li class="nav__item"><a class="nav__link" href="">About Us</a></li>
          <li class="nav__item"><a class="nav__link" href="">Contact Us</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle nav__link">Category <span
                class="caret"></span></a>
            <ul class="dropdown-menu">
              <?php
              foreach ($category->getAllCategories() as $catg) {
                echo "
                   <li><a href='category.php?category=" . $catg['category_name'] . "'>" . $catg['category_name'] . "</a></li>
                 ";
              }
              ?>
            </ul>
          </li>

          <!-- <form method="POST" class="navbar-form navbar-left" action="search.php">
                <div class="input-group">
                    <input type="text" class="form-control" id="navbar-search-input" name="keyword" placeholder="Search for Product" required>
                    <span class="input-group-btn" id="searchBtn" style="display:none;">
                        <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i> </button>
                    </span>
                </div>
            </form> -->
        </ul>


        </ul>
      </nav>
      <?php
      if (isset($_SESSION['user_id'])) {
        $image = (!empty($user['photo'])) ? 'images/' . $user['photo'] : 'images/profile.jpg';
        echo '
                <li class="dropdown user user-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="' . $image . '" class="user-image" alt="User Image">
                    <span class="hidden-xs">' . $user['firstname'] . ' ' . $user['lastname'] . '</span>
                  </a>
                  <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                      <img src="' . $image . '" class="img-circle" alt="User Image">

                      <p>
                        ' . $user['firstname'] . ' ' . $user['lastname'] . '
                        <small>Member since ' . date('M. Y', strtotime($user['created_on'])) . '</small>
                      </p>
                    </li>
                    <li class="user-footer">
                      <div class="pull-left">
                        <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                      </div>
                      <div class="pull-right">
                        <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                      </div>
                    </li>
                  </ul>
                </li>
              ';
      } else { ?>
        <div class="header__auth-btns">
          <a class="btn nav__link" href='login.php'>Login</a>
          <a class="btn nav__link" href='register.php'>Sign up</a>
        </div>
      <?php } ?>
    </div>
  </header>
  <script>
    // Close all dropdowns on page shows
    window.addEventListener('pageshow', function() {
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.remove('show');
      });
    });

    // Toggle dropdown on click
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
      item.addEventListener('click', function(e) {
        e.preventDefault();

        // Close other open dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
          if (menu !== this.nextElementSibling) {
            menu.classList.remove('show');
          }
        });

        // Toggle current dropdown
        const menu = this.nextElementSibling;
        menu.classList.toggle('show');
      });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        if (!menu.contains(event.target) && !menu.previousElementSibling.contains(event.target)) {
          menu.classList.remove('show');
        }
      });
    });
  </script>
</body>

</html>