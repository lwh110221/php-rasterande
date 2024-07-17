<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:/admin/index.php");
    exit;
}

require_once 'DBconn/database.php';

// 获取所有菜品信息
$dishes_query = "SELECT * FROM Dishes";
$dishes_result = mysqli_query($conn, $dishes_query);

// 处理AJAX请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'add') {
            $reservation_id = $_POST['reservation_id'];
            $order_time = $_POST['order_time'];
            $total_price = 0;
            $add_order_query = "INSERT INTO Orders (ReservationID, OrderTime, TotalPrice) VALUES ($reservation_id, '$order_time', $total_price)";
            mysqli_query($conn, $add_order_query);
            $order_id = mysqli_insert_id($conn);

            foreach ($_POST['dishes'] as $dish_id => $quantity) {
                if ($quantity > 0) {
                    $dish_query = "SELECT Price FROM Dishes WHERE DishID = $dish_id";
                    $dish_result = mysqli_query($conn, $dish_query);
                    $dish = mysqli_fetch_assoc($dish_result);
                    $price = $dish['Price'] * $quantity;
                    $total_price += $price;
                    $add_order_detail_query = "INSERT INTO OrderDetails (OrderID, DishID, Quantity) VALUES ($order_id, $dish_id, $quantity)";
                    mysqli_query($conn, $add_order_detail_query);
                }
            }

            $update_order_query = "UPDATE Orders SET TotalPrice = $total_price WHERE OrderID = $order_id";
            mysqli_query($conn, $update_order_query);

            echo '添加成功';
        } elseif ($action == 'delete') {
            $order_id = $_POST['order_id'];
            $delete_order_detail_query = "DELETE FROM OrderDetails WHERE OrderID = $order_id";
            mysqli_query($conn, $delete_order_detail_query);
            $delete_query = "DELETE FROM Orders WHERE OrderID = $order_id";
            mysqli_query($conn, $delete_query);
            echo '删除成功';
        } elseif ($action == 'edit') {
            $order_id = $_POST['order_id'];
            $order_time = $_POST['order_time'];
            $total_price = 0;

            $delete_order_detail_query = "DELETE FROM OrderDetails WHERE OrderID = $order_id";
            mysqli_query($conn, $delete_order_detail_query);

            foreach ($_POST['dishes'] as $dish_id => $quantity) {
                if ($quantity > 0) {
                    $dish_query = "SELECT Price FROM Dishes WHERE DishID = $dish_id";
                    $dish_result = mysqli_query($conn, $dish_query);
                    $dish = mysqli_fetch_assoc($dish_result);
                    $price = $dish['Price'] * $quantity;
                    $total_price += $price;
                    $add_order_detail_query = "INSERT INTO OrderDetails (OrderID, DishID, Quantity) VALUES ($order_id, $dish_id, $quantity)";
                    mysqli_query($conn, $add_order_detail_query);
                }
            }

            $update_order_query = "UPDATE Orders SET OrderTime = '$order_time', TotalPrice = $total_price WHERE OrderID = $order_id";
            mysqli_query($conn, $update_order_query);

            echo '编辑成功';
        } elseif ($action == 'get_order_details') {
            $order_id = $_POST['order_id'];
            $order_details_query = "SELECT d.DishID, d.Name, od.Quantity, d.Price FROM OrderDetails od JOIN Dishes d ON od.DishID = d.DishID WHERE od.OrderID = $order_id";
            $order_details_result = mysqli_query($conn, $order_details_query);
            $order_details = [];
            while ($order_detail = mysqli_fetch_assoc($order_details_result)) {
                $order_details[] = $order_detail;
            }
            echo json_encode($order_details);
            exit;
        } elseif ($action == 'get_order_info') {
            $order_id = $_POST['order_id'];
            $order_query = "SELECT o.*, r.ReservationTime, c.FullName AS CustomerName FROM Orders o 
                            JOIN Reservations r ON o.ReservationID = r.ReservationID 
                            JOIN Customers c ON r.CustomerID = c.CustomerID 
                            WHERE o.OrderID = $order_id";
            $order_result = mysqli_query($conn, $order_query);
            $order_info = mysqli_fetch_assoc($order_result);
            echo json_encode($order_info);
            exit;
        }
        exit;
    }

    // 处理分页请求
    if (isset($_POST['page'])) {
        $items_per_page = 10;
        $page = intval($_POST['page']);
        $offset = ($page - 1) * $items_per_page;

        $query = "SELECT o.*, r.ReservationTime, c.FullName AS CustomerName FROM Orders o 
                  JOIN Reservations r ON o.ReservationID = r.ReservationID 
                  JOIN Customers c ON r.CustomerID = c.CustomerID 
                  LIMIT $items_per_page OFFSET $offset";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr data-order-id="' . $row['OrderID'] . '">';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['OrderID'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['CustomerName'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['ReservationTime'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['OrderTime'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">' . $row['TotalPrice'] . '</td>';
            echo '<td class="border px-4 py-2 text-center border-2 border-blue-700">';
            echo '<button class="text-blue-500 edit-order font-bold">编辑</button>';
            echo '&nbsp&nbsp&nbsp';
            echo '<button class="text-red-500 delete-order font-bold">删除</button>';
            echo '&nbsp&nbsp&nbsp';
            echo '<button class="text-green-500 view-order font-bold">查看详情</button>';
            echo '</td>';
            echo '</tr>';
        }
        exit;
    }
}

// 获取初始的订单数据和分页信息
$items_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

$query = "SELECT o.*, r.ReservationTime, c.FullName AS CustomerName FROM Orders o 
          JOIN Reservations r ON o.ReservationID = r.ReservationID 
          JOIN Customers c ON r.CustomerID = c.CustomerID 
          LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) as total FROM Orders";
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
    <title>订单管理</title>
</head>
<body class="bg-slate-100">
    <?php include 'component/topbar.php'; ?>
    <div class="flex">
        <?php include 'component/sidebar.php'; ?>
        <div class="flex-1 ml-20 p-4 min-h-screen">
            <h1 class="text-xl font-bold mb-4">订单管理</h1>
            <button id="add-order-button" class="bg-blue-500 text-white px-4 py-2 mb-4 font-bold rounded-lg">添加订单(根据预约)</button>
            <table class="table-auto w-full mb-4 border-dashed border-2 border-indigo-600" id="orders-table">
                <thead>
                    <tr>
                        <th class="px-4 py-2">订单ID</th>
                        <th class="px-4 py-2">顾客</th>
                        <th class="px-4 py-2">预定时间</th>
                        <th class="px-4 py-2">订单时间</th>
                        <th class="px-4 py-2">总价</th>
                        <th class="px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr data-order-id="<?php echo $row['OrderID']; ?>">
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['OrderID']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['CustomerName']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['ReservationTime']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['OrderTime']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700"><?php echo $row['TotalPrice']; ?></td>
                            <td class="border px-4 py-2 text-center border-2 border-blue-700">
                                <button class="text-blue-500 edit-order font-bold">编辑</button>
                                &nbsp&nbsp&nbsp
                                <button class="text-red-500 delete-order font-bold">删除</button>
                                &nbsp&nbsp&nbsp
                                <button class="text-green-500 view-order font-bold">查看详情</button>
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

    <!-- 查看详情的弹窗 -->
    <div id="viewModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-auto">
        <div class="bg-white p-4 rounded w-2/3 max-h-full">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">订单详情</h2>
            <div id="order-info" class="mb-4">
                <!-- 订单信息将通过AJAX加载 -->
            </div>
            <table class="table-auto w-full mb-4 border-dashed border-2 border-indigo-600">
                <thead>
                    <tr>
                        <th class="px-4 py-2">菜品</th>
                        <th class="px-4 py-2">数量</th>
                        <th class="px-4 py-2">价格</th>
                    </tr>
                </thead>
                <tbody id="order-details">
                    <!-- 我这里显示菜品详情是通过AJAX加载的 ！！！！！！！！！！-->
                </tbody>
            </table>
            <div class="flex justify-end">
                <div class="text-right">
                    <p>合计数量：<span id="total-quantity">0</span></p>
                    <p>总价：<span id="total-price">0</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- 编辑弹窗 -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-auto">
        <div class="bg-white p-4 rounded w-2/3 max-h-full">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">编辑订单</h2>
            <form id="edit-order-form">
                <input type="hidden" name="order_id" id="edit-order-id">
                <label class="block mb-2">订单时间:</label>
                <input type="datetime-local" name="order_time" id="edit-order-time" class="border p-2 mb-2 w-full" required>
                <div id="edit-dishes" class="grid grid-cols-2 gap-4 overflow-auto max-h-96">
                    <label class="block mb-2 col-span-2">菜品:</label>
                    <?php 
                    // 重新执行查询以获取结果集
                    $dishes_result = mysqli_query($conn, $dishes_query); 
                    while ($dish = mysqli_fetch_assoc($dishes_result)) { ?>
                        <div class="flex items-center mb-2">
                            <input type="number" name="dishes[<?php echo $dish['DishID']; ?>]" class="border p-2 w-20 mr-2 quantity-input" placeholder="数量" min="0">
                            <span class="dish-name"><?php echo $dish['Name']; ?> (￥<?php echo $dish['Price']; ?>)</span>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit" class="bg-blue-500 text-white p-2">保存</button>
            </form>
        </div>
    </div>

    <!-- 添加弹窗 -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-auto">
        <div class="bg-white p-4 rounded w-2/3 max-h-full">
            <span class="close float-right text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">添加订单</h2>
            <form id="add-order-form">
                <label class="block mb-2">预定:</label>
                <select name="reservation_id" class="border p-2 mb-2 w-full" required>
                    <?php 
                    $reservations_query = "SELECT r.ReservationID, c.FullName, r.ReservationTime FROM Reservations r JOIN Customers c ON r.CustomerID = c.CustomerID";
                    $reservations_result = mysqli_query($conn, $reservations_query);
                    while ($reservation = mysqli_fetch_assoc($reservations_result)) { ?>
                        <option value="<?php echo $reservation['ReservationID']; ?>"><?php echo $reservation['FullName'] . ' - ' . $reservation['ReservationTime']; ?></option>
                    <?php } ?>
                </select>
                <label class="block mb-2">订单时间:</label>
                <input type="datetime-local" name="order_time" class="border p-2 mb-2 w-full" required>
                <div id="add-dishes" class="grid grid-cols-2 gap-4 overflow-auto max-h-96">
                    <label class="block mb-2 col-span-2">菜品:</label>
                    <?php 
                    $dishes_result = mysqli_query($conn, $dishes_query); //重新执行查询以获取结果集
                    while ($dish = mysqli_fetch_assoc($dishes_result)) { ?>
                        <div class="flex items-center mb-2">
                            <input type="number" name="dishes[<?php echo $dish['DishID']; ?>]" class="border p-2 w-20 mr-2" placeholder="数量" min="0">
                            <span><?php echo $dish['Name']; ?> (￥<?php echo $dish['Price']; ?>)</span>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit" class="bg-blue-500 text-white p-2">添加</button>
            </form>
        </div>
    </div>

    <div id="notification" class="fixed bottom-10 right-10 bg-green-500 text-white p-4 rounded hidden"></div>


    <script>
        $(document).ready(function() {
            function loadPage(page) {
                $.post('orders.php', { page: page }, function(data) {
                    $('#orders-table tbody').html(data);
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

            $('#add-order-button').on('click', function() {
                $('#addModal').removeClass('hidden');
            });

            $('#add-order-form').on('submit', function(e) {
                e.preventDefault();
                $.post('orders.php', $(this).serialize() + '&action=add', function(response) {
                    showNotification(response);
                    $('#add-order-form')[0].reset(); 
                    $('#addModal').addClass('hidden');
                    loadPage(1);
                });
            });

            $(document).on('click', '.delete-order', function() {
                var orderId = $(this).closest('tr').data('order-id');
                if (confirm('确认删除？')) {
                    $.post('orders.php', { order_id: orderId, action: 'delete' }, function(response) {
                        showNotification(response);
                        loadPage(1);
                    });
                }
            });

            $(document).on('click', '.edit-order', function() {
                var orderId = $(this).closest('tr').data('order-id');
                var row = $(this).closest('tr');
                $('#edit-order-id').val(orderId);
                $('#edit-order-time').val(row.find('td:nth-child(4)').text());

                $('#edit-dishes input').each(function() {
                    $(this).val(0).removeClass('text-green-500');
                });

                $.post('orders.php', { order_id: orderId, action: 'get_order_details' }, function(data) {
                    var orderDetails = JSON.parse(data);
                    orderDetails.forEach(function(detail) {
                        var input = $('#edit-dishes input[name="dishes[' + detail.DishID + ']"]');
                        input.val(detail.Quantity);
                        if (detail.Quantity > 0) {
                            input.addClass('text-green-500');
                        }
                    });
                });

                $('#editModal').removeClass('hidden');
            });

            $(document).on('click', '.view-order', function() {
                var orderId = $(this).closest('tr').data('order-id');

                // 获取订单信息
                $.post('orders.php', { order_id: orderId, action: 'get_order_info' }, function(data) {
                    var orderInfo = JSON.parse(data);
                    $('#order-info').html('<p>顾客: ' + orderInfo.CustomerName + '</p><p>预定时间: ' + orderInfo.ReservationTime + '</p><p>订单时间: ' + orderInfo.OrderTime + '</p>');
                });

                // 获取订单详情
                $.post('orders.php', { order_id: orderId, action: 'get_order_details' }, function(data) {
                    var orderDetails = JSON.parse(data);
                    var totalQuantity = 0;
                    var totalPrice = 0;
                    $('#order-details').empty();
                    orderDetails.forEach(function(detail) {
                        var quantity = parseInt(detail.Quantity);
                        var price = parseFloat(detail.Price) * quantity;
                        totalQuantity += quantity;
                        totalPrice += price;
                        $('#order-details').append(
                            '<tr><td class="border px-4 py-2 text-center">' + detail.Name + 
                            '</td><td class="border px-4 py-2 text-center">' + quantity + 
                            '</td><td class="border px-4 py-2 text-center">￥' + price.toFixed(2) + 
                            '</td></tr>'
                        );
                    });
                    $('#total-quantity').text(totalQuantity);
                    $('#total-price').text('￥' + totalPrice.toFixed(2));
                });

                $('#viewModal').removeClass('hidden');
            });

            $('.close').on('click', function() {
                $(this).closest('.fixed').addClass('hidden');
            });

            $('#edit-order-form').on('submit', function(e) {
                e.preventDefault();
                $.post('orders.php', $(this).serialize() + '&action=edit', function(response) {
                    $('#editModal').addClass('hidden');
                    showNotification(response);
                    loadPage(1);
                });
            });

            $(document).on('input', '#edit-dishes input', function() {
                if ($(this).val() > 0) {
                    $(this).addClass('text-green-500');
                } else {
                    $(this).removeClass('text-green-500');
                }
            });
        });
    </script>
</body>
</html>
