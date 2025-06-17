

<?php
// index.php

require_once 'cart.php'; // Include the cart.php file

$cart = readCart();  // Read the cart data from the file

// Product data (for example purposes)
$products = array(
    'product1' => array('name' => 'T-Shirt', 'price' => 20),
    'product2' => array('name' => 'Jeans', 'price' => 50),
    'product3' => array('name' => 'Hat', 'price' => 15)
);

echo '<h1>Shopping Cart</h1>';

if (empty($cart)) {
    echo '<p>Your cart is empty.</p>';
} else {
    echo '<ul>';
    foreach ($cart as $product_id => $quantity) {
        $product_name = $products[$product_id]['name'];
        $product_price = $products[$product_id]['price'];
        $total_price = $product_price * $quantity;
        echo '<li>' . $product_name . ' - $' . $product_price . ' x ' . $quantity . ' = $' . $total_price . '</li>';
    }
    echo '</ul>';
    echo '<p><strong>Total: $' . array_sum(array_map(function($qty) { return $products[$key]['price'] * $qty; }, $cart)) . '</strong></p>';
}

echo '<br><a href="add_to_cart.php">Add to Cart</a> | <a href="remove_from_cart.php">Remove from Cart</a> | <a href="checkout.php">Checkout</a>';
?>
