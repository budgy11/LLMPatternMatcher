

<?php
session_start();

// This is a simplified example and focuses on the core concepts.
// In a real application, you'd likely use a database to store your cart data.

// Sample product data (replace with your actual product information)
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

// Function to get the cart contents
function getCartContents()
{
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return []; // Return an empty array if the cart is empty
    }
}

// --- Example Usage ---

// 1. Add a T-Shirt to the cart (quantity 2)
addToCart(1, 2);

// 2. Add Jeans to the cart (quantity 1)
addToCart(2);

// 3.  Display the cart contents
$cartContents = getCartContents();
echo "<h2>Your Shopping Cart</h2>";

if (empty($cartContents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartContents as $productId => $quantity) {
        $product = $products[$productId];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";

    // Calculate the total cost
    $totalCost = 0;
    foreach ($cartContents as $productId => $quantity) {
        $product = $products[$productId];
        $totalCost += $product['price'] * $quantity;
    }

    echo "<p><strong>Total Cost: $" . number_format($totalCost, 2) . "</strong></p>";

}


// You would typically add code here to handle checkout,
// payment processing, etc., which is beyond the scope of this simple example.
?>
