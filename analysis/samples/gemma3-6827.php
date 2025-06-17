

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to place an order.</p>";
    exit; // Stop execution
}

// Validate form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate input
    if (!isset($products[$product_id])) {
        echo "<p>Invalid product ID.</p>";
        exit;
    }
    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "<p>Invalid quantity.</p>";
        exit;
    }

    // Process the order
    $order_total = $products[$product_id]['price'] * $quantity;

    // Store the order (in this example, just to the session)
    $order_data = [
        'user_id' => $_SESSION['user_id'],
        'product_id' => $product_id,
        'quantity' => $quantity,
        'total' => $order_total,
        'timestamp' => time()
    ];
    $_SESSION['orders'][] = $order_data;

    echo "<p>Order placed successfully! Order ID: " . end($_SESSION['orders'])['order_id'] . "</p>";
    //  In a real app, you'd redirect the user or display an order confirmation page.
}
?>
