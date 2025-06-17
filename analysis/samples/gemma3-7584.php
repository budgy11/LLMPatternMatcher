
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Functionality</h1>

<form method="post" action="">
    <label for="cart_id">Cart ID:</label>
    <input type="text" id="cart_id" name="cart_id" required><br><br>

    <label for="user_id">User ID:</label>
    <input type="text" id="user_id" name="user_id" required><br><br>

    <input type="submit" value="Complete Purchase">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Product Data (Simulated for demonstration)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20, 'stock' => 10],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50, 'stock' => 5],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15, 'stock' => 20],
];

// Session for user data (Simple - for demonstration)
session_start();

// Function to handle the purchase process
function processPurchase($product_id, $quantity) {
    // Validate inputs (Very basic for this example)
    if (!is_numeric($product_id) || $product_id <= 0 || !is_numeric($quantity) || $quantity <= 0) {
        return "Invalid product ID or quantity.";
    }

    $product_id = (int)$product_id; // Convert to integer for safety

    $product = $products[$product_id];

    if ($product === false) {
        return "Product not found.";
    }

    if ($product['stock'] < $quantity) {
        return "Not enough stock.";
    }

    // Add to cart (In a real application, you'd store this in a session or database)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
    ];

    // Update stock (Simulated - in reality, you'd update the database)
    $product['stock'] -= $quantity;

    // Return success message
    return "Purchase added to cart!";
}

// Handle form submission (if applicable)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $result = processPurchase($product_id, $quantity);
    echo "<br>" . $result;

    // Display cart summary (Example)
    if (isset($_SESSION['cart'])) {
        echo "<br><h2>Cart Summary:</h2>";
        echo "<ul>";
        foreach ($_SESSION['cart'] as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
        }
        echo "</ul>";
    }
}
?>
