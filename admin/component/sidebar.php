<nav class="sidebar-container fixed w-16 bg-sky-700 hover:w-44 transition-all duration-500 flex flex-col justify-between">
    <div class="menu-top flex flex-col">
        <a href="home.php" class="menu-item flex items-center p-4 text-white cursor-pointer overflow-hidden transition-all duration-300 hover:bg-blue-700 hover:text-yellow-300">
            <i class="fas fa-home menu-icon text-2xl mr-4 transition-all duration-500 "></i>
            <span class="whitespace-nowrap opacity-0 transition-all duration-500 font-bold">主页简介</span>
        </a>
        <a href="dishes.php" class="menu-item flex items-center p-4 text-white cursor-pointer overflow-hidden transition-all duration-300 hover:bg-blue-700 hover:text-yellow-300">
            <i class="fas fa-utensils menu-icon text-2xl mr-4 transition-all duration-500"></i>
            <span class="whitespace-nowrap opacity-0 transition-all duration-500 font-bold">菜品管理</span>
        </a>
        <a href="customers.php" class="menu-item flex items-center p-4 text-white cursor-pointer overflow-hidden transition-all duration-300 hover:bg-blue-700 hover:text-yellow-300">
            <i class="fas fa-users menu-icon text-2xl mr-4 transition-all duration-500"></i>
            <span class="whitespace-nowrap opacity-0 transition-all duration-500 font-bold">顾客管理</span>
        </a>
        <a href="tables.php" class="menu-item flex items-center p-4 text-white cursor-pointer overflow-hidden transition-all duration-300 hover:bg-blue-700 hover:text-yellow-300">
            <i class="fas fa-chair menu-icon text-2xl mr-4 transition-all duration-500"></i>
            <span class="whitespace-nowrap opacity-0 transition-all duration-500 font-bold">餐桌管理</span>
        </a>
        <a href="reservations.php" class="menu-item flex items-center p-4 text-white cursor-pointer overflow-hidden transition-all duration-300 hover:bg-blue-700 hover:text-yellow-300">
            <i class="fas fa-calendar-alt menu-icon text-2xl mr-4 transition-all duration-500"></i>
            <span class="whitespace-nowrap opacity-0 transition-all duration-500 font-bold">预约管理</span>
        </a>
        <a href="orders.php" class="menu-item flex items-center p-4 text-white cursor-pointer overflow-hidden transition-all duration-300 hover:bg-blue-700 hover:text-yellow-300">
            <i class="fas fa-clipboard-list menu-icon text-2xl mr-4 transition-all duration-500"></i>
            <span class="whitespace-nowrap opacity-0 transition-all duration-500 font-bold">订单管理</span>
        </a>
    </div>
</nav>

<style>
.sidebar-container {
    top: 4rem;
    height: calc(100vh - 4rem);
}

.sidebar-container:hover .menu-item span {
    opacity: 1;
}

.menu-item.active {
    background-color: #ffd299; 
    color: #FF7F50;
}
</style>

<script>
(function() {
    var sidebar = document.querySelector('.sidebar-container');
    var menuItems = document.querySelectorAll('.menu-item');

    // 设置激活状态
    var currentPath = window.location.pathname.split('/').pop();
    menuItems.forEach(function(item) {
        var link = item.getAttribute('href');
        if (link === currentPath) {
            item.classList.add('active');
        }
    });

    // 滚动时调整侧边栏位置
    window.addEventListener('scroll', function() {
        if (window.scrollY > 0) {
            sidebar.style.top = '0';
            sidebar.style.height = '100vh';
        } else {
            sidebar.style.top = '4rem';
            sidebar.style.height = 'calc(100vh - 4rem)';
        }
    });

    // 添加菜单项的激活状态点击事件
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            menuItems.forEach(function(item) {
                item.classList.remove('active');
            });
            item.classList.add('active');
        });
    });
})();
</script>
