<?php
session_start();
require_once "models/Product.php";

$output = '';

if (isset($_SESSION['cart']) && count($_SESSION['cart']) != 0) {
    $total = 0;
    $product = new Product();
    foreach ($_SESSION['cart'] as $index => $item) {
        $cartProduct = $product->getProductById($item['productID']);
        $subtotal = $cartProduct['product_price'] * $item['quantity'];
        $total += $subtotal;

        $output .=  "<tr>
        <td><button type='button' onclick='deleteFromCart(" . $cartProduct['product_id'] . ")' class='cart_delete'>X</button></td>  
        <td>" . $cartProduct['product_name'] . "</td>
        <td>&#36; " . number_format($cartProduct['product_price'], 2) . "</td>
        <td>
           <input data-id='" . $cartProduct['product_id'] . "' id='quantityField' class='input-box__field' type='number' value='" . $item['quantity'] . "' min='0'/>
        </td>
        <td>&#36; " . number_format($subtotal, 2) . "</td>
    </tr>";
    }

    // Adding total to footer row
    $output .= "<tfoot>
                <tr>
                    <td colspan='4' align='right'><b>Total</b></td>
                    <td><b>&#36; " . number_format($total, 2) . "</b></td>
                </tr>
                </tfoot>";
} else {
    $output .= "<tr><td colspan='5' align='center'>Shopping cart empty</td></tr>";
}

echo  $output;
?>
