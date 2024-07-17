<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<div class="topbar-container flex justify-between items-center bg-cyan-800 p-4 text-white w-full z-10">
    <div class="brand text-2xl font-bold">
        <a href="/admin/home.php">Song`s小餐厅信息管理系统🍚</a>
    </div>
    <div class="user-info flex items-center">
        <i class="fas fa-user user-icon text-2xl mr-2"></i>
        <span class="username text-lg">管理员:<?php echo $_SESSION['admin']; ?></span>
        <a href="?logout" class="ml-4 bg-red-600 px-2 py-1 rounded font-bold">注销</a>
    </div>
</div>

<style>
.topbar-container {
    height: 4rem; 
}
</style>
