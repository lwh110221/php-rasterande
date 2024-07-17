<?php
require_once 'DBconn/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['admin'] = $username;
        header("Location:/admin/home.php");
        exit;
    } else {
        echo "<script>alert('用户名或密码错误')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员登录</title>
    <link rel="stylesheet" href="/static/css/biulded_all.css">
</head>
<style>
    body {
       background-image: url('/static/img/bg.jpg');  
    }
</style>
<body>
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-2xl w-96 shadow-orange-500">
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">管理员登录</h2>
            <form method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">用户名</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">密码</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 rounded-lg font-bold">登录</button>
                <button type="button" onclick="window.location.href='/'" class="w-full bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 rounded-lg mt-4 font-bold">返回主页</button>
            </form>
        </div>
    </div>
    <?php include 'component/footer.php'; ?>
</body>
</html>
