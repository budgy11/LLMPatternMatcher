
</body>
</html>


<?php

// Define product data (in a real application, this would come from a database)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

// Function to handle the purchase process
function handlePurchase() {
    // 1. Get user input
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate input
    if (!isset($products[$productId])) {
        echo "<p>Invalid product ID.</p>";
        return;
    }

    if ($quantity <= 0 || !is_numeric($quantity)) {
        echo "<p>Invalid quantity. Please enter a positive number.</p>";
        return;
    }

    $quantity = (int)$quantity; // Ensure quantity is an integer

    // 2. Calculate the total price
    $totalPrice = $products[$productId]['price'] * $quantity;

    // 3.  Update inventory (simulated)
    $products[$productId]['quantity'] -= $quantity;

    // 4. Display confirmation
    echo "<p><strong>Order Confirmation:</strong></p>";
    echo "<p>Product: " . $products[$productId]['name'] . "</p>";
    echo "<p>Quantity: " . $quantity . "</p>";
    echo "<p>Total Price: $" . number_format($totalPrice, 2) . "</p>";
    echo "<p>Thank you for your purchase!</p>";
}


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    handlePurchase();
}

?>
