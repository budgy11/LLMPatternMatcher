

<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// Sample product data (replace with your actual product data)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        // Item already in cart, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it
        $_SESSION['cart'][$productId] = [
            'quantity' => $quantity,
            'price' => $products[$productId]['price']
        ];
    }
}

// Function to get the cart contents
function getCart()
{
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return [];
    }
}

// Function to remove an item from the cart
function removeItemFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// ---  Example usage based on a hypothetical user interaction  ---

// 1. User adds a Laptop
addToCart(1);

// 2. User adds 2 Mice
addToCart(2, 2);

// 3. User updates the quantity of the Laptop to 3
updateQuantity(1, 3);

// 4. User removes the Mouse
removeItemFromCart(2);

// Get the cart contents
$cart = getCart();

// Display the cart contents (for demonstration)
echo "<h2>Your Cart:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $productId => $item) {
        echo "<li>" . $products[$productId]['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}


// ---  How to implement in a real application  ---

// In a real web application, the user interaction would come from:
// - A button click (e.g., "Add to Cart" button)
// - Form submission
// - AJAX request

// Example for a button click (simplified):
//  - When the user clicks the "Add to Cart" button for a product:
//    - Get the product ID.
//    - Call addToCart(productId, quantity);
//    - Possibly update the cart summary on the page.

?>
