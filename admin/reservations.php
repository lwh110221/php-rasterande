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
            $customer_id = $_POST['customer_id'];
            $table_id = $_POST['table_id'];
            $reservation_time = $_POST['reservation_time'];
            $add_query = "INSERT INTO Reservations (CustomerID, TableID, ReservationTime) VALUES ($customer_id, $table_id, '$reservation_time')";
            mysqli_query($conn, $add_query);
            $reservation_id = mysqli_insert_id($conn);
            $add_order_query = "INSERT INTO Orders (ReservationID, OrderTime, TotalPrice) VALUES ($reservation_id, '$reservation_time', 0)";
            mysqli_query($conn, $add_order_query);
            echo '添加成功';
        } elseif ($action == 'delete') {
            $reservation_id = $_POST['reservation_id'];
            $delete_order_query = "DELETE FROM Orders WHERE ReservationID = $reservation_id";
            mysqli_query($conn, $delete_order_query);
            $delete_query = "DELETE FROM Reservations WHERE ReservationID = $reservation_id";
            mysqli_query($conn, $delete_query);
            echo '删除成功';
        } elseif ($action == 'edit') {
            $reservation_id = $_POST['reservation_id'];
            $customer_id = $_POST['customer_id'];
            $table_id = $_POST['table_id'];
            $reservation_time = $_POST['reservation_time'];
            $update_query = "UPDATE Reservations SET CustomerID = $customer_id, TableID = $table_id, ReservationTime = '$reservation_time' WHERE ReservationID = $reservation_id";
            mysqli_query($conn, $update_query);
            echo '编辑成功';
        } elseif ($action == 'get_customers_tables') {
            $customers_query = "SELECT * FROM Customers";
            $customers_result = mysqli_query($conn, $customers_query);
            $customers = [];
            while ($customer = mysqli_fetch_assoc($customers_result)) {
                $customers[] = $customer;
            }

            $tables_query = "SELECT * FROM Tables";
            $tables_result = mysqli_query($conn, $tables_query);
            $tables = [];
            while ($table = mysqli_fetch_assoc($tables_result)) {
                $tables[] = $table;
            }

            echo json_encode(['customers' => $customers, 'tables' => $tables]);
            exit;
        }
        exit;
    }

    // 处理分页请求
    if (isset($_POST['page'])) {
        $items_per_page = 10;
        $page = intval($_POST['page']);
        $offset = ($page - 1) * $items_per_page;

        $query = "SELECT r.*, c.FullName AS CustomerName, t.Capacity AS TableCapacity FROM Reservations r 
                  JOIN Customers c ON r.CustomerID = c.CustomerID 
                  JOIN Tables t ON r.TableID = t.TableID 
                  LIMIT $items_per_page OFFSET $offset";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr data-reservation-id="' . $row['ReservationID'] . '">';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['ReservationID'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['CustomerName'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['TableID'] . '号桌 - ' . $row['TableCapacity'] . '人</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['ReservationTime'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">';
            echo '<button class="text-blue-500 edit-reservation font-bold">编辑</button>';
            echo '&nbsp&nbsp&nbsp';
            echo '<button class="text-red-500 delete-reservation font-bold">删除</button>';
            echo '</td>';
            echo '</tr>';
        }
        exit;
    }
}

// 获取初始的预定数据和分页信息
$items_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

$query = "SELECT r.*, c.FullName AS CustomerName, t.Capacity AS TableCapacity FROM Reservations r 
          JOIN Customers c ON r.CustomerID = c.CustomerID 
          JOIN Tables t ON r.TableID = t.TableID 
          LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) as total FROM Reservations";
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
    <title>预定管理</title>
</head>
<body class="bg-slate-100">
    <?php include 'component/topbar.php'; ?>
    <div class="flex">
        <?php include 'component/sidebar.php'; ?>
        <div class="flex-1 ml-20 p-4 min-h-screen">
            <h1 class="text-xl font-bold mb-4">预定管理</h1>
            <button id="add-reservation-button" class="bg-blue-500 text-white px-4 py-2 mb-4 font-bold">添加预定</button>
            <table class="table-auto w-full mb-4 border-dashed border-2 border-indigo-600" id="reservations-table">
                <thead>
                    <tr>
                        <th class="px-4 py-2">预定ID</th>
                        <th class="px-4 py-2">顾客</th>
                        <th class="px-4 py-2">餐桌</th>
                        <th class="px-4 py-2">预定时间</th>
                        <th class="px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr data-reservation-id="<?php echo $row['ReservationID']; ?>">
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['ReservationID']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['CustomerName']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['TableID']; ?>号桌 - <?php echo $row['TableCapacity']; ?>人</td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['ReservationTime']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700">
                                <button class="text-blue-500 edit-reservation font-bold">编辑</button>
                                &nbsp&nbsp&nbsp
                                <button class="text-red-500 delete-reservation font-bold">删除</button>
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
            <h2 class="text-xl font-bold mb-4">编辑预定</h2>
            <form id="edit-reservation-form">
                <input type="hidden" name="reservation_id" id="edit-reservation-id">
                <label class="block mb-2">顾客:</label>
                <select name="customer_id" id="edit-customer-id" class="border p-2 mb-2 w-full" required></select>
                <label class="block mb-2">餐桌:</label>
                <select name="table_id" id="edit-table-id" class="border p-2 mb-2 w-full" required></select>
                <label class="block mb-2">预定时间:</label>
                <input type="datetime-local" name="reservation_time" id="edit-reservation-time" class="border p-2 mb-2 w-full" required>
                <button type="submit" class="bg-blue-500 text-white p-2">保存</button>
            </form>
        </div>
    </div>

    <!-- 添加弹窗 -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded w-1/3">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">添加预定</h2>
            <form id="add-reservation-form">
                <label class="block mb-2">顾客:</label>
                <select name="customer_id" id="add-customer-id" class="border p-2 mb-2 w-full" required></select>
                <label class="block mb-2">餐桌:</label>
                <select name="table_id" id="add-table-id" class="border p-2 mb-2 w-full" required></select>
                <label class="block mb-2">预定时间:</label>
                <input type="datetime-local" name="reservation_time" class="border p-2 mb-2 w-full" required>
                <button type="submit" class="bg-blue-500 text-white p-2">添加</button>
            </form>
        </div>
    </div>

    <div id="notification" class="fixed bottom-10 right-10 bg-green-500 text-white p-4 rounded hidden"></div>

    <?php include 'component/footer.php'; ?>

    <script>
        $(document).ready(function() {
            function loadPage(page) {
                $.post('reservations.php', { page: page }, function(data) {
                    $('#reservations-table tbody').html(data);
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

            function loadCustomersAndTables() {
                $.post('reservations.php', { action: 'get_customers_tables' }, function(response) {
                    var data = JSON.parse(response);
                    $('#add-customer-id, #edit-customer-id').empty();
                    $('#add-table-id, #edit-table-id').empty();
                    data.customers.forEach(function(customer) {
                        $('#add-customer-id, #edit-customer-id').append('<option value="' + customer.CustomerID + '">' + customer.FullName + '</option>');
                    });
                    data.tables.forEach(function(table) {
                        $('#add-table-id, #edit-table-id').append('<option value="' + table.TableID + '">' + table.TableID + '号桌 - ' + table.Capacity + '人</option>');
                    });
                });
            }

            $('.page-link').on('click', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                loadPage(page);
            });

            $('#add-reservation-button').on('click', function() {
                loadCustomersAndTables();
                $('#addModal').removeClass('hidden');
            });

            $('#add-reservation-form').on('submit', function(e) {
                e.preventDefault();
                $.post('reservations.php', $(this).serialize() + '&action=add', function(response) {
                    showNotification(response);
                    $('#add-reservation-form')[0].reset(); 
                    $('#addModal').addClass('hidden');
                    loadPage(1);
                });
            });

            $(document).on('click', '.delete-reservation', function() {
                var reservationId = $(this).closest('tr').data('reservation-id');
                if (confirm('确认删除？')) {
                    $.post('reservations.php', { reservation_id: reservationId, action: 'delete' }, function(response) {
                        showNotification(response);
                        loadPage(1);
                    });
                }
            });

            $(document).on('click', '.edit-reservation', function() {
                var reservationId = $(this).closest('tr').data('reservation-id');
                var row = $(this).closest('tr');
                $('#edit-reservation-id').val(reservationId);
                $('#edit-reservation-time').val(row.find('td:nth-child(4)').text());

                loadCustomersAndTables();

                setTimeout(function() {
                    $('#edit-customer-id').val(row.find('td:nth-child(2)').data('customer-id'));
                    $('#edit-table-id').val(row.find('td:nth-child(3)').data('table-id'));
                    $('#editModal').removeClass('hidden');
                }, 500);
            });

            $('.close').on('click', function() {
                $(this).closest('.fixed').addClass('hidden');
            });

            $('#edit-reservation-form').on('submit', function(e) {
                e.preventDefault();
                $.post('reservations.php', $(this).serialize() + '&action=edit', function(response) {
                    $('#editModal').addClass('hidden');
                    showNotification(response);
                    loadPage(1);
                });
            });
        });
    </script>
</body>
</html>
