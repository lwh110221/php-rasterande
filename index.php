<?php include 'cilentReser.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="static/css/biulded_all.css">
    <script src="static/js/jquery-3.7.1.min.js"></script>
    <title>小Song餐厅</title>
    <style>
        .sticky-nav {
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .introduction-section {
            background-size: cover;
            background-position: center;
            height: 100vh;
            transition: background-image 1s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-900 text-white sticky-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="#" class="text-2xl font-semibold">小Song餐厅</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="#introduction" class="nav-link border-transparent hover:border-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold">介绍</a>
                        <a href="#features" class="nav-link border-transparent hover:border-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold">特色</a>
                        <a href="#reservation" class="nav-link border-transparent hover:border-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold">预定</a>
                        <a href="#about" class="nav-link border-transparent hover:border-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold">关于</a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <a href="/admin/index.php" class="text-lg font-bold text-gray-300 hover:text-white">后台管理</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 介绍部分 -->
    <section id="introduction" class="introduction-section flex items-center justify-center">
        <div class="bg-black bg-opacity-50 text-white p-10 rounded text-center">
            <h1 class="text-4xl font-bold mb-4">欢迎来到我们的餐厅😅</h1>
            <p class="text-lg">享受我们精心准备的美食，让您的味蕾得到满足。</p>
            <a href="#reservation" class="mt-6 inline-block bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-600">立即预定</a>
        </div>
    </section>

    <!-- 特色部分 -->
    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">我们的特色</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-100 p-6 rounded shadow">
                    <img src="static/img/ts1.jpg" alt="特色1" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold mb-2">地道风味，传承经典</h3>
                    <p class="text-gray-700">我们强调对地道美食传统的坚持与传承。我们的大厨们精心挑选食材，遵循古老食谱，用匠心独运的手法复刻出那些令人怀念的经典味道。每一道菜都是一段味蕾上的旅行，让顾客在每一口之间感受到历史的沉淀与文化的韵味。</p>
                </div>
                <div class="bg-gray-100 p-6 rounded shadow">
                    <img src="static/img/ts2.jpg" alt="特色2" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold mb-2">创意融合，味觉新体验</h3>
                    <p class="text-gray-700">我们不仅仅停留于传统，更致力于美食的创新与融合。在这里，东西方食材巧妙结合，传统与现代烹饪技术相碰撞，创造出前所未有的味觉体验。每一季，我们的菜单都会更新，融入时下流行的元素，旨在为食客带来惊喜连连的餐桌艺术。</p>
                </div>
                <div class="bg-gray-100 p-6 rounded shadow">
                    <img src="static/img/ts3.jpg" alt="特色3" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold mb-2">绿色健康，食材至上</h3>
                    <p class="text-gray-700">健康饮食是我们坚持的原则之一。我们精选当地农场直供的有机蔬菜、无激素肉类以及可持续海产品，确保每一份食材都是新鲜、纯净、健康的。在享受美味的同时，我们也关心顾客的健康，致力于提供营养均衡、低负担的餐点选择，让美味与健康同行。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 预定部分 -->
    <section id="reservation" class="py-16 bg-orange-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">在线预定</h2>
            <form id="reservation-form" class="max-w-lg mx-auto bg-white p-8 rounded shadow">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">姓名</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">邮箱</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">电话</label>
                    <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                </div>
                <button type="button" id="check-reservation" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">预定</button>
            </form>
        </div>
    </section>

    <section id="about" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mt-12 mb-12">PHP课程实验设计</h1》>
            <h2 class="text-3xl font-bold text-center mb-12">网站介绍</h2>
            <p class="text-lg text-gray-700">本网站为Php课程设计项目的主页，用于展示餐厅的介绍、特色和预定功能。Tailwind CSS构建页面样式，JQuery处理前端交互，PHP处理后端逻辑。</p>
            <p class="text-lg text-gray-700">本页的预定功能可以实现实际的预定操作，预定成功后会将预定信息存储到数据库中。</p>
            <p class="text-lg text-gray-700">前往后台信息管理页面请点击右上角导航栏登录后台信息管理。</p>
        </div>
    </section>

    <!-- 提交预约的模态框 -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded w-1/3">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">提交预约</h2>
            <form id="add-reservation-form">
                <input type="hidden" name="customer_id" id="customer_id">
                <div class="mb-4">
                    <label for="table_id" class="block text-gray-700 font-bold mb-2">餐桌</label>
                    <select name="table_id" id="table_id" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                        <?php foreach ($tables as $table): ?>
                            <option value="<?= $table['TableID']; ?>"><?= $table['TableID']; ?>号桌 - <?= $table['Capacity']; ?>人</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="reservation_time" class="block text-gray-700 font-bold mb-2">预定时间</label>
                    <input type="datetime-local" name="reservation_time" id="reservation_time" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">提交预定</button>
            </form>
        </div>
    </div>
    <footer class="bg-gray-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <span>&copy; 2024 Song`s. All rights reserved. Powered by 
                   <a href="https://www.tailwindcss.cn/" class="text-blue-500 hover:text-blue-300">Tailwind CSS</a>
                </span>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.nav-link').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        const images = [
            'static/img/food1.jpg',
            'static/img/food2.jpg',
            'static/img/food3.jpg'
        ];
        let currentIndex = 0;
        const introductionSection = document.getElementById('introduction');

        function changeBackgroundImage() {
            introductionSection.style.backgroundImage = `url(${images[currentIndex]})`;
            currentIndex = (currentIndex + 1) % images.length;
        }

        changeBackgroundImage();
        // 每5秒切换一次背景图片
        setInterval(changeBackgroundImage, 5000);

        document.getElementById('check-reservation').addEventListener('click', function () {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;

            $.post('index.php', { check_customer: true, phone, name, email }, function (response) {
                const data = JSON.parse(response);
                document.getElementById('customer_id').value = data.customer_id;
                document.getElementById('addModal').classList.remove('hidden');
            });
        });

        document.getElementById('add-reservation-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.post('index.php', formData + '&make_reservation=true', function (response) {
                alert(response);
                document.getElementById('addModal').classList.add('hidden');
                document.getElementById('reservation-form').reset();
            });
        });

        document.querySelector('.close').addEventListener('click', function () {
            document.getElementById('addModal').classList.add('hidden');
        });


        // 注册 Service Worker！！！缓存用
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('Service Worker registered with scope:', registration.scope);
            })
            .catch((error) => {
                console.log('Service Worker registration failed:', error);
            });
        }
    </script>
</body>
</html>
