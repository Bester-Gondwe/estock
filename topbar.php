<div class="dashboard-header">
    <img src="../images/ic_search.svg" alt="Search Icon" width="24" height="24">
    <div id="profile-btn" class="profile-btn">
        <p class="profile-btn__text"><?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
        <img src="../images/ic_down-arrow.svg" alt="" width="10" height="6">

    </div>
    <div id="popup-menu" class="popup-menu">
        <?php echo "<a class='popup-menu__link' href='../logout.php'>Logout</a>"; ?>
    </div>
</div>