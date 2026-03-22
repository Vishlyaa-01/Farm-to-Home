<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $order_id = $_POST['order_id'];

    $sql = "UPDATE orders SET order_status = 'delivered' WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        header("Location: farmer.php?msg=Order+Delivered");
    } else {
        echo "Error updating status!";
    }
}
?>
