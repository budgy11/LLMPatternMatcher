

<?php

// Assuming you have the $cart array defined in the same file or a shared one

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]); // Remove the item from the cart
    } else {
        echo "<p>Product ID " . $product_id . " not found in cart.</p>";
    }

    // Optionally, redirect back to the main page or the cart page
    header("Location: index.php"); // Redirect to the main page
    exit();
}
?>
