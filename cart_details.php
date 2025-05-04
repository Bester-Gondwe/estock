<?php
session_start();
require_once "models/Product.php";

$output = '';

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;
    $product = new Product();

    foreach ($_SESSION['cart'] as $item) {
        $cartProduct = $product->getProductById($item['productID']);
        $subtotal = $cartProduct['product_price'] * $item['quantity'];
        $total += $subtotal;

        $output .= "<tr>
            <td class='p-3'>
                <button onclick='deleteFromCart({$cartProduct['product_id']})' class='text-red-600 hover:text-red-800 font-bold'>×</button>
            </td>
            <td class='p-3 font-medium'>{$cartProduct['product_name']}</td>
            <td class='p-3'>\$" . number_format($cartProduct['product_price'], 2) . "</td>
            <td class='p-3'>
                <input data-id='{$cartProduct['product_id']}' id='quantityField' class='w-16 px-2 py-1 border border-gray-300 rounded' type='number' value='{$item['quantity']}' min='1'>
            </td>
            <td class='p-3'>\$" . number_format($subtotal, 2) . "</td>
        </tr>";
    }

    $output .= "<tr class='bg-gray-100 font-semibold'>
        <td colspan='4' class='text-right p-3'>Total</td>
        <td class='p-3'>\$" . number_format($total, 2) . "</td>
    </tr>";
} else {
    $output .= "<tr><td colspan='5' class='text-center p-4'>🛒 Your shopping cart is empty.</td></tr>";
}

echo $output;
