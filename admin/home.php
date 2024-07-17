<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location:/admin/index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/biulded_all.css">
    <link rel="stylesheet" href="/static/css/fontawesome.min.css">
    <script src="/static/js/all.min.js"></script>
    <script src="/static/js/jquery-3.7.1.min.js"></script>
    <title>主页</title>
</head>
<body class="bg-slate-100">
    <?php include 'component/topbar.php'; ?>

    <div class="flex">
        <?php include 'component/sidebar.php'; ?>

        <div class="flex-1 ml-20 p-4 min-h-screen">
            <h1 class="text-2xl font-bold">欢迎回来，管理员<?php echo $_SESSION['admin']; ?></h1>
            <div class="mt-4">
                <p class="text-lg">👻该系统为PHP课程的课程设计项目，是一个简单的餐厅管理系统，可以在左侧导航栏中查看和管理各个功能。</p>
                <p class="text-lg">📌在系统中我将侧边栏顶部栏和底部栏封装成组件放在component文件夹中，通过include在这些管理页面中引用，提高代码复用性。数据库连接文件则放在DBconn文件夹中
                通过include在需要连接数据库的文件中引用，提高代码的可维护性。大部分的管理页面的代码结构都是相似的，在同样的结构下，通过不同的SQL语句实现不同的功能。
                </p>
                <p class="text-lg mt-4">系统功能介绍：</p>
                <ul class="list-disc list-inside text-lg">
                    <li><strong>主页简介：</strong>查看餐厅管理系统的总体概述。</li>
                    <li><strong>菜品管理：</strong>管理餐厅的所有菜品，包括添加、删除和编辑菜品信息。</li>
                    <li><strong>顾客管理：</strong>维护顾客信息，方便查看和管理顾客资料。</li>
                    <li><strong>餐桌管理：</strong>管理餐厅内的所有餐桌，查看每张餐桌容量。</li>
                    <li><strong>订单管理：</strong>查看和管理所有订单，包括订单的详细信息和状态。</li>
                    <li><strong>预约管理：</strong>管理顾客的预约信息，方便餐厅安排和优化座位。</li>
                </ul>
                <p class="text-lg mt-4">使用到的技术：</p>
                <ul class="list-disc list-inside text-lg">
                    <li><strong>PHP：</strong>作为服务器端编程语言，用于处理业务逻辑、数据库操作和生成动态网页内容。</li>
                    <li><strong>MySQL：</strong>关系型数据库管理系统，用于存储和管理餐厅的各种数据，如顾客信息、菜品信息、订单和预约等。</li>
                    <li><strong>HTML：</strong>用于定义网页的结构和内容。</li>
                    <li><strong>CSS：</strong>用于美化网页，包括使用 <a href="https://www.tailwindcss.cn/" class="text-blue-400 hover:text-blue-600">Tailwind CSS</a> 框架提供响应式设计和样式。tailwindcss是一种将原子类作为构建块的工具，样式通过tailwindcss构建后,极大的简化了样式的编写，提高了开发效率。</li>
                    <li><strong>JavaScript：</strong>用于增强网页的交互性，主要使用 <a href="https://jquery.com/" class="text-blue-400 hover:text-blue-600">jQuery</a> 库来简化 DOM 操作和 AJAX 请求。</li>
                    <li><strong>AJAX：</strong>用于在不重新加载整个页面的情况下与服务器进行异步通信，从而实现动态更新页面内容。</li>
                    <li><strong>FontAwesome：</strong>用于提供丰富的图标库，增强用户界面的视觉效果。</li>
                    <li><strong>Perttier：</strong>用于代码格式化和标准化，让代码方便查看修改，更加美观</li>
                </ul>
                <p class="text-lg mt-4">数据库结构：</p>
                <ul class="list-disc list-inside text-lg">
                    <li><strong>admins 表：</strong>存储管理员信息，包括用户名和密码。</li>
                    <li><strong>customers 表：</strong>存储顾客信息，包括顾客的姓名、电话和邮箱。</li>
                    <li><strong>dishes 表：</strong>存储菜品信息，包括菜品名称、描述和价格。</li>
                    <li><strong>orderdetails 表：</strong>存储订单详细信息，包括每个订单中的菜品及其数量。</li>
                    <li><strong>orders 表：</strong>存储订单信息，包括预定ID、订单时间和总价。</li>
                    <li><strong>reservations 表：</strong>存储预约信息，包括顾客ID、餐桌ID和预约时间。</li>
                    <li><strong>tables 表：</strong>存储餐桌信息，包括餐桌的容量。</li>
                </ul>
                <p class="text-lg mt-4">数据库满足3NF,并且使用外键约束来保证数据的完整性和一致性。</p>
            </div>
        </div>
    </div>
    
    <div>
        <?php include 'component/footer.php'; ?>  
    </div>
      
</body>
</html>