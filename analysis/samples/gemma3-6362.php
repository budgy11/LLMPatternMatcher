

<?php
session_start();

// Sample Product Data (for demonstration)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0;
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Example Usage (simulating user actions)

// User adds a T-Shirt to the cart
addToCart(1);

// User adds 2 Jeans to the cart
addToCart(2, 2);

// User removes the Hat from the cart
removeFromCart(3);

// User updates the quantity of Jeans to 3
updateQuantity(2, 3);


// Display the Cart Contents
echo "<h2>Shopping Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products[$productId];
        echo "<li>";
        echo "<strong>" . $product['name'] . "</strong> - $" . $product['price'] . " x " . $quantity . " = $" . ($product['price'] * $quantity) . "</li>";
    }
    echo "</ul>";

    // Calculate the total price
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products[$productId];
        $totalPrice += $product['price'] * $quantity;
    }
    echo "<p><strong>Total: $" . $totalPrice . "</strong></p>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
