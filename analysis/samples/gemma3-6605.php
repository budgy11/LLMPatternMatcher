

<?php
session_start(); // Start the session

// Check if the 'cart' session variable exists
if (!isset($_SESSION['cart'])) {
    // Initialize the cart array if it doesn't exist
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it's already in the cart, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Otherwise, add the product to the cart
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
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Example usage -  Illustrative, you'll likely get product data from a database/API

// Add some items to the cart
addToCart('product1', 'Awesome T-Shirt', 20, 2);
addToCart('product2', 'Cool Mug', 10, 3);
addToCart('product1', 'Awesome T-Shirt', 20, 1); // Add more of the existing item


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
        echo "<button onclick='removeFromCart(" . $product_id . ")'>Remove</button>";  //Example of how you might display the remove button.  JavaScript would handle the call to removeFromCart.
    }
    echo "</ul>";
}



// Example of removing an item
// removeFromCart('product2');

// Example of updating quantity
// updateQuantity('product1', 5);

?>
