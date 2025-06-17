

<?php

session_start();

// **Initialize the Cart**
// This is where you would normally load cart data from a database or file.
// For this example, we'll start with an empty cart.  In a real application,
// you'd load this from a database.

$_SESSION['cart'] = []; // Initialize an empty array to store items in the cart.

// **Helper Functions** (for demonstration - you'd likely have these in a separate file)

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment quantity if product exists
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product not in cart, add it
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// **Example Usage (Simulating a Shopping Cart Interaction)**

// 1. Add an item to the cart
addToCart(101, 'T-Shirt', 20.00, 2); // Product ID 101, T-Shirt, price $20, quantity 2

// 2. Add another item
addToCart(102, 'Jeans', 50.00, 1);

// 3.  Update quantity of product 101 to 3
updateQuantity(101, 3);

// 4. Remove a product
removeFromCart(102);

// **Displaying the Cart Content**

// This section shows how you'd display the contents of the cart.
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

echo "<p><strong>Total Cart Value:</strong> $" . (array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart']))) . "</p>";



?>
