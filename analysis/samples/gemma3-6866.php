
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Example: $conn = mysqli_connect("localhost", "username", "password", "database_name");

// Configuration
$items = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
    4 => ['name' => 'Monitor', 'price' => 300],
];

$cart = [];

// Function to add an item to the cart
function addToCart($item_id, $quantity = 1)
{
    global $cart;

    if (isset($items[$item_id])) {
        $item = $items[$item_id];
        $cart[] = ['item_id' => $item_id, 'name' => $item['name'], 'price' => $item['price'], 'quantity' => $quantity];
    } else {
        echo "Error: Item with ID " . $item_id . " not found.";
    }
}

// Function to display the cart
function displayCart()
{
    echo "<h2>Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Total:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)) . "</p>";
}

// Function to calculate the total price
function calculateTotalPrice()
{
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Example Usage and Processing (Simulated Purchase)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
        $item_id = (int)$_POST['item_id']; // Validate and convert to integer
        $quantity = (int)$_POST['quantity'];

        if ($quantity > 0) {
            addToCart($item_id, $quantity);
            displayCart();
        } else {
            echo "<p>Invalid quantity. Please enter a positive number.</p>";
        }
    }
}

?>
