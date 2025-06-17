
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h2>Add a Purchase</h2>

<form method="post" action="">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <button type="submit">Add Purchase</button>
</form>

</body>
</html>


<?php

// Data Storage (Replace with a database connection in a real application)
$cart = []; // Array to store items in the cart
$products = [
    1 => ["name" => "T-Shirt", "price" => 20.00],
    2 => ["name" => "Jeans", "price" => 50.00],
    3 => ["name" => "Hat", "price" => 15.00],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1) {
    if (isset($products[$productId])) {
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => $quantity
            ];
        }
    } else {
        echo "Product ID {$productId} not found.";
    }
}

// Function to display the cart
function displayCart() {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $itemId => $item) {
        echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";

    // Calculate total cost
    $totalCost = 0;
    foreach ($cart as $itemId => $item) {
        $totalCost += $item['price'] * $item['quantity'];
    }
    echo "<p><b>Total Cost: $" . number_format($totalCost, 2) . "</b></p>";
}

// Function to handle the purchase (simplified - no payment processing)
function processPurchase() {
    if (empty($cart)) {
        echo "<p>Your cart is empty.  Nothing to purchase.</p>";
        return;
    }

    echo "<h2>Order Confirmation</h2>";
    echo "<p>Thank you for your order!</p>";
    echo "<p>Order Summary:</p>";
    displayCart(); // Re-display the cart as confirmation
    // In a real application, you would:
    // 1. Process payment (integrate with a payment gateway)
    // 2. Update inventory (reduce product stock)
    // 3. Send confirmation email
    echo "<p>Your order has been placed.  (Simulated - no actual payment/shipping)</p>";
}

// --- Example Usage (Handle form submissions and calls to functions) ---

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = (int)$_POST['product_id']; // Convert to integer
        $quantity = (int)$_POST['quantity'];

        addToCart($productId, $quantity);
    }

    if (isset($_POST['purchase'])) {
        processPurchase();
    }
}

?>
