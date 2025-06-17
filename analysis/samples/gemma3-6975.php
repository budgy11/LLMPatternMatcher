

<?php

// 1. Form Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["product_name"];
    $quantity = (int)$_POST["quantity"]; // Cast to integer
    $price = (float)$_POST["price"]; // Cast to float

    // Basic validation (improve this!)
    if (empty($productName) || $quantity <= 0 || $price <= 0) {
        $error = "Please fill in all fields and enter valid quantities and prices.";
    } else {
        $total = $quantity * $price;
        $message = "Your order for $quantity of $productName at $price each has been placed. Total: $total";
    }
}
?>
