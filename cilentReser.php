<?php
require_once 'admin/DBconn/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_customer'])) {
        $phone = $_POST['phone'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        // 检查顾客是否存在
        $check_query = "SELECT * FROM Customers WHERE Phone = '$phone'";
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $customer = mysqli_fetch_assoc($check_result);
            $customer_id = $customer['CustomerID'];
            echo json_encode(['exists' => true, 'customer_id' => $customer_id]);
        } else {
            $insert_query = "INSERT INTO Customers (FullName, Phone, Email) VALUES ('$name', '$phone', '$email')";
            mysqli_query($conn, $insert_query);
            $customer_id = mysqli_insert_id($conn);
            echo json_encode(['exists' => false, 'customer_id' => $customer_id]);
        }
        exit;
    }

    if (isset($_POST['make_reservation'])) {
        $customer_id = $_POST['customer_id'];
        $table_id = $_POST['table_id'];
        $reservation_time = $_POST['reservation_time'];
        //      调试报错信息
        error_log("Customer ID: $customer_id, Table ID: $table_id, Reservation Time: $reservation_time");
        $reservation_query = "INSERT INTO Reservations (CustomerID, TableID, ReservationTime) VALUES ($customer_id, $table_id, '$reservation_time')";
        if (mysqli_query($conn, $reservation_query)) {
            echo '预定成功';
        } else {
            echo '预定失败: ' . mysqli_error($conn);
        }
        exit;
    }
}

// 获取顾客和餐桌数据
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
?>