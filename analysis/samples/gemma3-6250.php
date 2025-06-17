

<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Example Usage:  (Simulated data for demonstration)

// Add a product to the cart
addToCart(1, "Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);

// Update the quantity of a product
updateQuantity(1, 5);  // Increase shirt quantity to 5

// Get the cart contents
$cartContents = getCartContents();
print_r($cartContents);

// Remove an item from the cart
removeCartItem(2);

// Get the cart contents again
$cartContents = getCartContents();
print_r($cartContents);
?>
