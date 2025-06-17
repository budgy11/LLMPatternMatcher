

<?php
session_start(); // Start the session to maintain user data

// Sample Product Data (replace with your database or data source)
$products = [
    1 => ["name" => "T-Shirt", "price" => 20.00],
    2 => ["name" => "Mug", "price" => 10.00],
    3 => ["name" => "Hat", "price" => 15.00],
];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form has been submitted

    $cart = isset($_POST["cart"]) ? $_POST["cart"] : [];
    $quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : [];

    // Validate input (very basic - enhance in a real application)
    $valid_cart = true;
    $valid_quantity = true;
    foreach($cart as $product_id => $qty) {
        if(!is_numeric($product_id) || !is_numeric($qty) || $qty <= 0) {
            $valid_cart = false;
        }
    }

    if (!$valid_cart) {
        $error_message = "Invalid product ID or quantity.";
    }

    if ($valid_cart) {
        $total = 0;
        foreach ($cart as $product_id => $qty) {
            if (isset($products[$product_id])) {
                $total += $products[$product_id]["price"] * $qty;
            } else {
                $error_message = "Product ID not found.";
                break; // Stop processing if product ID is invalid
            }
        }

        if ($error_message == "") {
            // Display Order Summary
            echo "<h2>Order Summary</h2>";
            echo "<p>Total Amount: $" . number_format($total, 2) . "</p>";
            echo "<p>Thank you for your order!</p>";
        }
    }
}
?>
