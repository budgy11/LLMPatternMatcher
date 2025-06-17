

<?php

// Sample Product Data (Replace with your actual database or data source)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to handle the purchase process
function handlePurchase($productId, $quantity) {
    // Validate input
    if (!isset($products[$productId])) {
        return "Product ID not found.";
    }

    if ($quantity <= 0) {
        return "Quantity must be greater than zero.";
    }

    // Calculate total price
    $totalPrice = $products[$productId]['price'] * $quantity;

    // Display purchase confirmation
    return "Purchase Confirmation:
" .
           "Product: " . $products[$productId]['name'] . "
" .
           "Quantity: " . $quantity . "
" .
           "Total Price: $" . $totalPrice;
}

// Check if the purchase form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID and quantity from the form
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate input (Important - prevent SQL injection, bad data)
    if (!isset($products[$productId])) {
        echo "<p style='color:red;'>Product ID not found.</p>";
    } else if ($quantity <= 0) {
        echo "<p style='color:red;'>Quantity must be greater than zero.</p>";
    } else {
        // Process the purchase
        $confirmationMessage = handlePurchase($productId, $quantity);
        echo $confirmationMessage;
    }
}
?>
