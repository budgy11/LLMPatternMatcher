
</body>
</html>


<?php
session_start();

// Define some product information (for demonstration)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20, 'quantity' => 1],
    2 => ['name' => 'Jeans', 'price' => 50, 'quantity' => 2],
    3 => ['name' => 'Hat', 'price' => 15, 'quantity' => 1],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'][$productId])) {
        // Item already in cart, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it
        $_SESSION['cart'][$productId] = [
            'name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to get the cart total
function calculateCartTotal()
{
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Example Usage (Simulating User Interactions) ---

// 1. User adds a T-Shirt
addToCart(1);

// 2. User adds 2 Jeans
addToCart(2, 2);

// 3. User removes the Hat (product ID 3)
removeFromCart(3);

// 4. Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $itemId => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

echo "<p><strong>Total:</strong> $" . calculateCartTotal() . "</p>";

?>
