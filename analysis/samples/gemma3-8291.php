

<?php
// remove_from_cart.php

require_once 'cart.php';

$product_id = $_GET['product_id'];

if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
}

writeCart($cart); // Save the updated cart data
header("Location: index.php"); // Redirect back to the main page
exit();
?>
