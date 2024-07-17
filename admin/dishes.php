<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:/admin/index.php");
    exit;
}

require_once 'DBconn/database.php';

// 处理AJAX请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'add') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $add_query = "INSERT INTO Dishes (Name, Description, Price) VALUES ('$name', '$description', $price)";
            mysqli_query($conn, $add_query);
            echo '添加成功';
        } elseif ($action == 'delete') {
            $dish_id = $_POST['dish_id'];
            $delete_query = "DELETE FROM Dishes WHERE DishID = $dish_id";
            mysqli_query($conn, $delete_query);
            echo '删除成功';
        } elseif ($action == 'edit') {
            $dish_id = $_POST['dish_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $update_query = "UPDATE Dishes SET Name = '$name', Description = '$description', Price = $price WHERE DishID = $dish_id";
            mysqli_query($conn, $update_query);
            echo '编辑成功';
        }
        exit;
    }

    // 处理分页请求
    if (isset($_POST['page'])) {
        $items_per_page = 10;
        $page = intval($_POST['page']);
        $offset = ($page - 1) * $items_per_page;

        $query = "SELECT * FROM Dishes LIMIT $items_per_page OFFSET $offset";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr data-dish-id="' . $row['DishID'] . '">';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['DishID'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['Name'] . '</td>';
            echo '<td class="border px-4 py-2 border-2 border-blue-700">' . $row['Description'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['Price'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">';
            echo '<button class="text-blue-500 edit-dish font-bold">编辑</button>';
            echo '&nbsp&nbsp&nbsp';
            echo '<button class="text-red-500 delete-dish font-bold">删除</button>';
            echo '</td>';
            echo '</tr>';
        }
        exit;
    }
}

// 获取初始的菜品数据和分页信息
$items_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

$query = "SELECT * FROM Dishes LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) as total FROM Dishes";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/biulded_all.css">
    <!-- 图标！！！用的fontawesome cdn -->
    <link rel="stylesheet" href="/static/css/fontawesome.min.css">
    <script src="/static/js/all.min.js"></script>
    <script src="/static/js/jquery-3.7.1.min.js"></script>
    <title>菜品管理</title>
</head>
<body class="bg-slate-100">
    <?php include 'component/topbar.php'; ?>
    <div class="flex">
        <?php include 'component/sidebar.php'; ?>
        <div class="flex-1 ml-20 p-4 min-h-screen">
            <h1 class="text-xl font-bold mb-4">菜品管理</h1>
            <button id="add-dish-button" class="bg-blue-500 text-white px-4 py-2 mb-4 font-bold">添加菜品</button>
            <table class="table-auto w-full mb-4 border-dashed border-2 border-indigo-600" id="dishes-table">
                <thead>
                    <tr>
                        <th class="px-4 py-2">菜品ID</th>
                        <th class="px-4 py-2">名称</th>
                        <th class="px-4 py-2">描述</th>
                        <th class="px-4 py-2">价格</th>
                        <th class="px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr data-dish-id="<?php echo $row['DishID']; ?>">
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['DishID']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['Name']; ?></td>
                            <td class="border px-4 py-2 border-2 border-blue-700"><?php echo $row['Description']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['Price']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700">
                                <button class="text-blue-500 edit-dish font-bold">编辑</button>
                                &nbsp&nbsp&nbsp
                                <button class="text-red-500 delete-dish font-bold">删除</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="pagination flex justify-center">
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <a href="#" class="page-link px-3 py-1 <?php echo $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-200'; ?>" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include 'component/footer.php'; ?>

    <!-- 编辑那个弹窗 -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded w-1/3">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">编辑菜品</h2>
            <form id="edit-dish-form">
                <input type="hidden" name="dish_id" id="edit-dish-id">
                <label class="block mb-2">名称:</label>
                <input type="text" name="name" id="edit-name" class="border p-2 mb-2 w-full" required>
                <label class="block mb-2">描述:</label>
                <textarea name="description" id="edit-description" class="border p-2 mb-2 w-full" required></textarea>
                <label class="block mb-2">价格:</label>
                <input type="number" name="price" id="edit-price" step="0.01" class="border p-2 mb-2 w-full" required>
                <button type="submit" class="bg-blue-500 text-white p-2">保存</button>
            </form>
        </div>
    </div>

    <!-- 添加弹窗 -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded w-1/3">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">添加菜品</h2>
            <form id="add-dish-form">
                <label class="block mb-2">名称:</label>
                <input type="text" name="name" class="border p-2 mb-2 w-full" required>
                <label class="block mb-2">描述:</label>
                <textarea name="description" class="border p-2 mb-2 w-full h-10" required></textarea>
                <label class="block mb-2">价格:</label>
                <input type="number" name="price" step="0.01" class="border p-2 mb-2 w-full" required>
                <button type="submit" class="bg-blue-500 text-white p-2">添加</button>
            </form>
        </div>
    </div>

    <div id="notification" class="fixed bottom-10 right-10 bg-green-500 text-white p-4 rounded hidden"></div>

    <script>
        $(document).ready(function() {
            function loadPage(page) {
                $.post('dishes.php', { page: page }, function(data) {
                    $('#dishes-table tbody').html(data);
                    $('.page-link').removeClass('bg-blue-500 text-white').addClass('bg-gray-200');
                    $('.page-link[data-page="' + page + '"]').addClass('bg-blue-500 text-white').removeClass('bg-gray-200');
                });
            }

            function showNotification(message, isError = false) {
                $('#notification').text(message).toggleClass('bg-red-500', isError).toggleClass('bg-green-500', !isError).fadeIn();
                setTimeout(function() {
                    $('#notification').fadeOut();
                }, 3000);
            }

            $('.page-link').on('click', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                loadPage(page);
            });

            $('#add-dish-button').on('click', function() {
                $('#addModal').removeClass('hidden');
            });

            $('#add-dish-form').on('submit', function(e) {
                e.preventDefault();
                $.post('dishes.php', $(this).serialize() + '&action=add', function(response) {
                    showNotification(response);
                    $('#add-dish-form')[0].reset(); 
                    $('#addModal').addClass('hidden');
                    loadPage(1);
                });
            });

            $(document).on('click', '.delete-dish', function() {
                var dishId = $(this).closest('tr').data('dish-id');
                if (confirm('确认删除？')) {
                    $.post('dishes.php', { dish_id: dishId, action: 'delete' }, function(response) {
                        showNotification(response);
                        loadPage(1);
                    });
                }
            });

            $(document).on('click', '.edit-dish', function() {
                var dishId = $(this).closest('tr').data('dish-id');
                var row = $(this).closest('tr');
                $('#edit-dish-id').val(dishId);
                $('#edit-name').val(row.find('td:nth-child(2)').text());
                $('#edit-description').val(row.find('td:nth-child(3)').text());
                $('#edit-price').val(row.find('td:nth-child(4)').text());
                $('#editModal').removeClass('hidden');
            });

            $('.close').on('click', function() {
                $(this).closest('.fixed').addClass('hidden');
            });

            $('#edit-dish-form').on('submit', function(e) {
                e.preventDefault();
                $.post('dishes.php', $(this).serialize() + '&action=edit', function(response) {
                    $('#editModal').addClass('hidden');
                    showNotification(response);
                    loadPage(1);
                });
            });
        });
    </script>
</body>
</html>
