

<?php

// Sample Product Data (Replace with your database integration)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to handle the purchase process
function handlePurchase($productId, $quantity) {
    // Validate input
    if (!is_numeric($productId) || $productId <= 0) {
        return "Invalid product ID.  Please enter a positive integer.";
    }
    if (!is_numeric($quantity) || $quantity <= 0) {
        return "Invalid quantity. Please enter a positive integer.";
    }

    // Find the product
    $product = $products[$productId];

    if (!$product) {
        return "Product with ID " . $productId . " not found.";
    }

    // Calculate the total price
    $totalPrice = $product['price'] * $quantity;

    // Display the purchase details
    return "Purchase confirmed:
" .
           "Product: " . $product['name'] . "
" .
           "Quantity: " . $quantity . "
" .
           "Price per item: $" . $product['price'] . "
" .
           "Total Price: $" . $totalPrice;
}

// Example Usage (Handle user input from a form - see the form example below)
//  You'll need to implement this based on how you're getting the data.

//  For demonstration purposes, let's simulate input.
$productId = 1;
$quantity = 2;

$result = handlePurchase($productId, $quantity);
echo $result . "
";

?>
