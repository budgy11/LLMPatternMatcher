

<?php
// Example of updating the cart quantity

if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];

    // Find the item in the cart
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;

        // Optionally, you could also update the price if needed.
        // For simplicity, we assume the price remains the same.

        echo "Cart updated successfully.";
    } else {
        echo "Item not found in the cart.";
    }
}
?>
