

<?php
session_start();

// Example products (replace with your actual product data)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20, 'quantity' => 1],
    2 => ['name' => 'Jeans', 'price' => 50, 'quantity' => 2],
    3 => ['name' => 'Hat', 'price' => 15, 'quantity' => 1],
];

// Function to add item to cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'][$productId])) {
        // Item already in cart, increase quantity
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

// Function to get cart items
function getCartItems()
{
    return $_SESSION['cart'];
}

// Function to update cart item quantity
function updateCartItemQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to remove item from cart
function removeCartItem($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Example usage:

// Add products to the cart
addToCart(1); // Add 1 T-Shirt
addToCart(2, 3); // Add 3 Jeans
addToCart(3); // Add 1 Hat

// Display the cart items
$cartItems = getCartItems();

echo "<h2>Your Cart</h2>";
if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $productId => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Example: Update quantity of item 2 to 5
    updateCartItemQuantity(2, 5);

    // Display the updated cart
    echo "<h2>Updated Cart</h2>";
    $cartItems = getCartItems();
    echo "<ul>";
    foreach ($cartItems as $productId => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Example: Remove item 1 from the cart
    removeCartItem(1);

    // Display the cart after removing item 1
    echo "<h2>Cart After Removing Item 1</h2>";
    $cartItems = getCartItems();
    echo "<ul>";
    foreach ($cartItems as $productId => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}
?>
