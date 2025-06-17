

<?php
session_start();

// Example Product Data (replace with your actual product data)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0; // Use ?? to handle new items
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to get the cart total
function getCartTotal()
{
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $products[$productId];
            $totalForProduct = $product['price'] * $quantity;
            $totalForProduct += $totalForProduct;
        }
    }
    return $totalForProduct;
}

// Example usage:  Add items to the cart
addToCart(1, 2); // Add 2 T-Shirts to the cart
addToCart(2, 1); // Add 1 Jeans to the cart
addToCart(3, 3); // Add 3 Hats to the cart

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (isset($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products[$productId];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . " - Total: $" . ($product['price'] * $quantity) . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Cart Total: $" . getCartTotal() . "</strong></p>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
