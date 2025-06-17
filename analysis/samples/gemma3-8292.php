

<?php
// checkout.php

require_once 'cart.php';

if (empty($cart)) {
    echo '<p>Your cart is empty.  Nothing to checkout.</p>';
} else {
    echo '<h1>Checkout</h1>';
    echo '<p>Thank you for your order!</p>';
    echo '<p><strong>Total: $' . array_sum(array_map(function($qty) { return $products[$key]['price'] * $qty; }, $cart)) . '</strong></p>';
}
?>
