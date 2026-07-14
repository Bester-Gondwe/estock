<div class="dashboard-header flex justify-between items-center px-1 py-2 mb-2">
    <div>
        <p class="text-sm text-slate-500">Merchant dashboard</p>
    </div>
    <div id="profile-btn" class="relative flex items-center cursor-pointer group">
        <p class="text-sm font-medium text-slate-700"><?= htmlspecialchars($_SESSION['first_name'] ?? '') ?></p>
        <img src="../images/ic_down-arrow.svg" alt="" class="w-2.5 h-1.5 ml-2">
        <div id="popup-menu" class="absolute right-0 top-full mt-2 hidden group-hover:block bg-white text-slate-800 shadow-md rounded-lg w-40 border border-slate-100 z-20">
            <a class="block px-4 py-2 hover:bg-slate-50 text-sm" href="../logout.php">Logout</a>
        </div>
    </div>
</div>
