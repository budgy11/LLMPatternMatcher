

<?php
session_start();

// Initialize the cart if it doesn't exist
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
        // If the product is in the cart, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Otherwise, add the product to the cart with its quantity
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Example usage:
// Adding a product to the cart
addToCart(1, "Awesome T-Shirt", 20.00, 2);
addToCart(2, "Cool Mug", 10.00, 1);

// Displaying the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Removing an item from the cart
// removeCartItem(1); // Uncomment this line to remove the Awesome T-Shirt

// Updating the quantity of an item in the cart
// updateCartQuantity(2, 3); // Uncomment this line to change the quantity of the Cool Mug to 3
?>
