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
            $capacity = $_POST['capacity'];
            $add_query = "INSERT INTO Tables (Capacity) VALUES ($capacity)";
            mysqli_query($conn, $add_query);
            echo '添加成功';
        } elseif ($action == 'delete') {
            $table_id = $_POST['table_id'];
            $delete_query = "DELETE FROM Tables WHERE TableID = $table_id";
            mysqli_query($conn, $delete_query);
            echo '删除成功';
        } elseif ($action == 'edit') {
            $table_id = $_POST['table_id'];
            $capacity = $_POST['capacity'];
            $update_query = "UPDATE Tables SET Capacity = $capacity WHERE TableID = $table_id";
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

        $query = "SELECT * FROM Tables LIMIT $items_per_page OFFSET $offset";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr data-table-id="' . $row['TableID'] . '">';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['TableID'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['Capacity'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">';
            echo '<button class="text-blue-500 edit-table font-bold">编辑</button>';
            echo '&nbsp&nbsp&nbsp';
            echo '<button class="text-red-500 delete-table font-bold">删除</button>';
            echo '</td>';
            echo '</tr>';
        }
        exit;
    }
}

// 获取初始的餐桌数据和分页信息
$items_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

$query = "SELECT * FROM Tables LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) as total FROM Tables";
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
    <link rel="stylesheet" href="/static/css/fontawesome.min.css">
    <script src="/static/js/all.min.js"></script>
    <script src="/static/js/jquery-3.7.1.min.js"></script>
    <title>餐桌管理</title>
</head>
<body class="bg-slate-100">
    <?php include 'component/topbar.php'; ?>
    <div class="flex">
        <?php include 'component/sidebar.php'; ?>
        <div class="flex-1 ml-20 p-4 min-h-screen">
            <h1 class="text-xl font-bold mb-4">餐桌管理</h1>
            <button id="add-table-button" class="bg-blue-500 text-white px-4 py-2 mb-4 font-bold">添加餐桌</button>
            <table class="table-auto w-full mb-4 border-dashed border-2 border-indigo-600" id="tables-table">
                <thead>
                    <tr>
                        <th class="px-4 py-2">餐桌ID</th>
                        <th class="px-4 py-2">容量</th>
                        <th class="px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr data-table-id="<?php echo $row['TableID']; ?>">
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['TableID']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['Capacity']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700">
                                <button class="text-blue-500 edit-table font-bold">编辑</button>
                                &nbsp&nbsp&nbsp
                                <button class="text-red-500 delete-table font-bold">删除</button>
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

    <!-- 编辑弹窗 -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded w-1/3">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">编辑餐桌</h2>
            <form id="edit-table-form">
                <input type="hidden" name="table_id" id="edit-table-id">
                <label class="block mb-2">容量:</label>
                <input type="number" name="capacity" id="edit-capacity" class="border p-2 mb-2 w-full" required>
                <button type="submit" class="bg-blue-500 text-white p-2">保存</button>
            </form>
        </div>
    </div>

    <!-- 添加弹窗 -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded w-1/3">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">添加餐桌</h2>
            <form id="add-table-form">
                <label class="block mb-2">容量:</label>
                <input type="number" name="capacity" class="border p-2 mb-2 w-full" required>
                <button type="submit" class="bg-blue-500 text-white p-2">添加</button>
            </form>
        </div>
    </div>

    <div id="notification" class="fixed bottom-10 right-10 bg-green-500 text-white p-4 rounded hidden"></div>

    <?php include 'component/footer.php'; ?>

    <script>
        $(document).ready(function() {
            function loadPage(page) {
                $.post('tables.php', { page: page }, function(data) {
                    $('#tables-table tbody').html(data);
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

            $('#add-table-button').on('click', function() {
                $('#addModal').removeClass('hidden');
            });

            $('#add-table-form').on('submit', function(e) {
                e.preventDefault();
                $.post('tables.php', $(this).serialize() + '&action=add', function(response) {
                    showNotification(response);
                    $('#add-table-form')[0].reset(); 
                    $('#addModal').addClass('hidden');
                    loadPage(1);
                });
            });

            $(document).on('click', '.delete-table', function() {
                var tableId = $(this).closest('tr').data('table-id');
                if (confirm('确认删除？')) {
                    $.post('tables.php', { table_id: tableId, action: 'delete' }, function(response) {
                        showNotification(response);
                        loadPage(1);
                    });
                }
            });

            $(document).on('click', '.edit-table', function() {
                var tableId = $(this).closest('tr').data('table-id');
                var row = $(this).closest('tr');
                $('#edit-table-id').val(tableId);
                $('#edit-capacity').val(row.find('td:nth-child(2)').text());
                $('#editModal').removeClass('hidden');
            });

            $('.close').on('click', function() {
                $(this).closest('.fixed').addClass('hidden');
            });

            $('#edit-table-form').on('submit', function(e) {
                e.preventDefault();
                $.post('tables.php', $(this).serialize() + '&action=edit', function(response) {
                    $('#editModal').addClass('hidden');
                    showNotification(response);
                    loadPage(1);
                });
            });
        });
    </script>
</body>
</html>
