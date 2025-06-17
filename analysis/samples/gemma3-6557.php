

<?php
session_start();

if (isset($_GET['product_id']) && $_GET['action'] == 'remove') {
    $product_id = $_GET['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); // Remove the product from the cart
    }
}
header("Location: cart.php"); // Redirect back to the cart page
exit();
?>
