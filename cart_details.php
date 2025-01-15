<?php
session_start();
require_once "models/Product.php";

$output = '';

if (count($_SESSION['cart']) != 0 || isset($_SESSION['cart'])) {
    $total = 0;
    $product = new Product();
    foreach ($_SESSION['cart'] as $index => $item) {
        $cartProduct = $product->getProductById($item['product_id']);
        $subtotal = $cartProduct['product_price'];
        $total += $subtotal;

        $output .=  "<tr>
        <td><button type='button' onclick='deleteFromCart(" . $cartProduct['product_id'] . ")' class='cart_delete'>X</button></td> 
         <td><img src='uploads/" . $cartProduct['primary_image'] . "' width='80px' height='80px'></td>   
        <td>" . $cartProduct['product_name'] . "</td>
        <td>&#36; " . number_format($cartProduct['product_price'], 2) . "</td>
        <td>
           <input id='quantityField' class='input-box__field' type='number' value='" . $item['quantity'] . "' min='0'/>
        </td>
        </td><td>&#36; " . number_format($subtotal, 2) . "</td>
    </tr>";
    }

    $output .= "<tr><td colspan='5' align='right'><b>Total</b></td><td><b>&#36; " . number_format($total, 2) . "</b></td><tr>";
} else {
    $output .= "<tr><td colspan='6' align='center'>Shopping cart empty</td><tr>";
}

echo  $output;
