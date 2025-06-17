

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
    if (!is_numeric($productId) || $productId <= 0) {
        return 'Invalid product ID.';
    }
    if (!is_numeric($quantity) || $quantity <= 0) {
        return 'Invalid quantity.';
    }

    // Find the product
    $product = $products[$productId];

    if (!$product) {
        return 'Product not found.';
    }

    // Calculate the total price
    $totalPrice = $product['price'] * $quantity;

    //  Store the order (This is a simplified example.  You'd normally use a database)
    $order = [
        'product_id' => $productId,
        'quantity' => $quantity,
        'total_price' => $totalPrice,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Log the order (Optional - useful for debugging and tracking)
    // You'd typically write this to a log file or database
    error_log("Order placed: " . json_encode($order));


    return 'Order placed successfully! Total: $' . number_format($totalPrice, 2);
}

// Handle the purchase based on user input (example)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $result = handlePurchase($productId, $quantity);
    echo "<p>" . $result . "</p>";
}
?>
