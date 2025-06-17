

<?php

session_start();

// Initialize the cart as an array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --------------------------------------------------
// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If the product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// --------------------------------------------------
// Example Usage (Demonstration)
//  You would normally get these product details from a database or another source.

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 2); // Adding more of the same product

// --------------------------------------------------
// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// --------------------------------------------------
// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// --------------------------------------------------
// Function to display the cart contents
function displayCart() {
    echo "<h2>Your Shopping Cart</h2>";

    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total per item:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";

    // Calculate the total cart value
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        $total += $product_details['price'] * $product_details['quantity'];
    }
    echo "<p><strong>Total Cart Value:</strong> $" . $total . "</p>";
}

// --------------------------------------------------
// Example Usage (Displaying the cart)

displayCart();

// --------------------------------------------------
// Example Usage (Removing an item)
//removeItemFromCart(2); // Remove the mouse
//displayCart();

// --------------------------------------------------
// Example Usage (Updating quantity)
//updateQuantity(1, 3); // Change quantity of Laptop to 3
//displayCart();

?>
