
</body>
</html>


<?php
session_start();

// Assuming you have a file (e.g., products.php) that retrieves product data
// For demonstration, I'll use some hardcoded data.

$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => $quantity,
        ];
    }
}

// Function to get the cart contents
function getCartContents()
{
    if (empty($_SESSION['cart'])) {
        return [];
    }
    return $_SESSION['cart'];
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// ---------------------------------------
// Example Usage (Simulating User Actions)
// ---------------------------------------

// 1. User adds a T-Shirt
addToCart(1);

// 2. User adds two Jeans
addToCart(2, 2);

// 3. User adds a Hat
addToCart(3);

// 4. User removes a Jeans
removeFromCart(2);

// ---------------------------------------
// Displaying the Cart Contents
// ---------------------------------------

$cart = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $itemId => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

?>
