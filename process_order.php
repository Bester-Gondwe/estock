<?php
session_start();
require_once "models/Order.php";
require_once "models/Product.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['user_id']) {
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $total = 0;
            $order = new Order();
            $product = new Product();

            // Create an order in the database
            $orderId = $order->createOrder($_SESSION['user_id']);
            if ($orderId > 0) {
                // Loop through the cart items and add them to the order
                foreach ($_SESSION['cart'] as $index => $item) {
                    if ($item['quantity'] > 0) {
                        $cartProduct = $product->getProductById($item['productID']);
                        $total += $cartProduct['product_price'] * $item['quantity'];
                        $order->addProductToOrder($orderId, $item['productID'], $item['quantity']);
                    }
                }

                // Clear the cart after placing the order
                $_SESSION['cart'] = [];
                $order->updateOrderAmount($orderId, $total);

                // Order success message
                echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Order Confirmation</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100'>
    <div class='max-w-lg mx-auto p-6 bg-white shadow-md rounded-lg mt-12'>
        <h1 class='text-2xl font-semibold text-center text-green-600'>Thank you for your order!</h1>
        <h3 class='text-center text-lg mt-2'>Your order has been placed successfully.</h3>
        <div class='mt-6'>
            <p class='text-sm text-gray-600 text-center'>Total Order Amount: <span class='font-semibold'><?php echo $total; ?></span></p>
        </div>
        <div class='mt-6 text-center'>
            <a href='index.php' class='inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Continue Shopping</a>
        </div>
    </div>
</body>
</html>";

            } else {
                echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Order Error</title>
                    <script src='https://cdn.tailwindcss.com'></script>
                </head>
                <body class='bg-gray-100'>
                    <div class='max-w-lg mx-auto p-6 bg-white shadow-md rounded-lg mt-12'>
                        <h1 class='text-2xl font-semibold text-center text-red-600'>Something went wrong!</h1>
                        <h3 class='text-center text-lg mt-2'>Please try again later.</h3>
                        <div class='mt-6 text-center'>
                            <a href='cart.php' class='inline-block px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600'>Go back to Cart</a>
                        </div>
                    </div>
                </body>
                </html>";
            }
        } else {
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Empty Cart</title>
                <script src='https://cdn.tailwindcss.com'></script>
            </head>
            <body class='bg-gray-100'>
                <div class='max-w-lg mx-auto p-6 bg-white shadow-md rounded-lg mt-12'>
                    <h1 class='text-2xl font-semibold text-center text-red-600'>Your cart is empty!</h1>
                    <h3 class='text-center text-lg mt-2'>Add products to your cart before placing an order.</h3>
                    <div class='mt-6 text-center'>
                        <a href='cart.php' class='inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Go to Cart</a>
                    </div>
                </div>
            </body>
            </html>";
        }
    } else {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Not Logged In</title>
            <script src='https://cdn.tailwindcss.com'></script>
        </head>
        <body class='bg-gray-100'>
            <div class='max-w-lg mx-auto p-6 bg-white shadow-md rounded-lg mt-12'>
                <h1 class='text-2xl font-semibold text-center text-red-600'>You are not logged in!</h1>
                <h3 class='text-center text-lg mt-2'>Please log in to place an order.</h3>
                <div class='mt-6 text-center'>
                    <a href='login.php' class='inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Log in</a>
                </div>
            </div>
        </body>
        </html>";
    }
}
?>
