

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
    // Add item to the cart array
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );

    // You might want to update the cart quantity if the item already exists
    updateCartItemQuantity($product_id, $quantity);
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Example usage:  Simulating a product being added to the cart.
//  Replace these with your actual product data and handling.

// Add an item to the cart
addToCart(1, "Awesome T-Shirt", 20.00, 2);
addToCart(2, "Cool Mug", 10.00, 1);


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate the total cost of the cart
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// Optional:  Function to remove an item from the cart
function removeItemFromCart($product_id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            // Optionally, re-index the cart array if necessary
            // reset($_SESSION['cart']);
            return;
        }
    }
}


// Example: Remove a product from cart
// removeItemFromCart(2);
?>
