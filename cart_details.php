<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Product.php';

$output = '';

if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $total = 0;
    $product = new Product();

    foreach ($_SESSION['cart'] as $item) {
        $cartProduct = $product->getProductById($item['productID']);
        if (!$cartProduct) {
            continue;
        }
        $qty = (int) $item['quantity'];
        $max = (int) $cartProduct['quantity'];
        $subtotal = (float) $cartProduct['product_price'] * $qty;
        $total += $subtotal;

        $output .= "<tr>
            <td class='p-3'>
                <button type='button' onclick='deleteFromCart(" . (int) $cartProduct['product_id'] . ")' class='text-red-600 hover:text-red-800 font-bold text-lg' aria-label='Remove'>&times;</button>
            </td>
            <td class='p-3 font-medium'>" . htmlspecialchars($cartProduct['product_name']) . "
                <div class='text-xs text-slate-400'>Max stock: {$max}</div>
            </td>
            <td class='p-3'>" . htmlspecialchars(format_money($cartProduct['product_price'])) . "</td>
            <td class='p-3'>
                <input data-id='" . (int) $cartProduct['product_id'] . "' id='quantityField' class='w-16 px-2 py-1 border border-slate-300 rounded' type='number' value='{$qty}' min='1' max='{$max}'>
            </td>
            <td class='p-3'>" . htmlspecialchars(format_money($subtotal)) . "</td>
        </tr>";
    }

    $output .= "<tr class='bg-slate-50 font-semibold'>
        <td colspan='4' class='text-right p-3'>Total</td>
        <td class='p-3'>" . htmlspecialchars(format_money($total)) . "</td>
    </tr>";
} else {
    $output .= "<tr><td colspan='5' class='text-center p-8 text-slate-500'>Your shopping cart is empty.</td></tr>";
}

echo $output;
