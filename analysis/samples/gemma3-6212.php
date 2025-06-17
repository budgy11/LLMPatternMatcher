

<?php
session_start();

function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // ... (same addToCart function as above) ...
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = floatval($_POST['price']);  // Ensure price is a float
        $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

        addToCart($product_id, $product_name, $price, $quantity);
    }
}
?>
