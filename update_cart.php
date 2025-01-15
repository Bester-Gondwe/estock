<?php
session_start();



$output = array('error' => false);

$id = $_POST['id'];
$qty = $_POST['qty'];


foreach ($_SESSION['cart'] as $key => $row) {
    if ($row['productid'] == $id) {
        $_SESSION['cart'][$key]['quantity'] = $qty;
        $output['message'] = 'Updated';
    }
}

echo json_encode($output);
