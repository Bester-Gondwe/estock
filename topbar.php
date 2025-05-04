<div class="dashboard-header flex justify-between items-center p-4 bg-gray-800 text-white">
    <div class="flex items-center">
        <img src="../images/ic_search.svg" alt="Search Icon" class="w-6 h-6 mr-4">
    </div>
    
    <div id="profile-btn" class="relative flex items-center cursor-pointer group">
        <p class="profile-btn__text text-lg"><?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
        <img src="../images/ic_down-arrow.svg" alt="Down Arrow" class="w-2.5 h-1.5 ml-2">
        
        <!-- Popup menu -->
        <div id="popup-menu" class="popup-menu absolute right-0 mt-2 hidden bg-white text-black shadow-md rounded-lg w-40 group-hover:block">
            <?php echo "<a class='popup-menu__link block px-4 py-2 hover:bg-gray-200' href='../logout.php'>Logout</a>"; ?>
        </div>
    </div>
</div>
