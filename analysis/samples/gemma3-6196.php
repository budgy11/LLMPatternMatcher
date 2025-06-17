

<?php
session_start();

// update_cart.php
if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = $_GET['product_id'];
    $quantity = (int)$_GET['quantity'];  // Ensure quantity is an integer

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    } else {
        // Product doesn't exist in the cart, do nothing.  Or, add it with the new quantity
    }
}
// remove_cart.php
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}
?>
