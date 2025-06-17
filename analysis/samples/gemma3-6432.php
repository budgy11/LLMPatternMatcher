

<?php
session_start();

// This is a basic example, and you'll likely want to expand it
// with more robust error handling, validation, and database integration.

// Example Product Data (Replace with your actual product data)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'] = [
            $product_id => $quantity,
        ];
    }
}

// Function to get the cart items
function getCartItems()
{
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return []; // Return an empty array if the cart is empty
    }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity)
{
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // Product doesn't exist in the cart.  You might want to handle this differently
        // (e.g., add it to the cart with the given quantity)
        echo "Product ID " . $product_id . " not found in cart.";
    }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


// --- Example Usage (Demonstration) ---

// 1. Add a T-Shirt to the cart
addToCart(1);

// 2. Add 2 Jeans to the cart
addToCart(2, 2);

// 3. Update the quantity of the T-Shirt to 3
updateCartItemQuantity(1, 3);

// 4. Get the cart items
$cart_items = getCartItems();

// Display the cart items
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $id => $quantity) {
        $product = $products[$id];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";
}

// Example: Remove the T-Shirt
// removeItemFromCart(1);

?>
