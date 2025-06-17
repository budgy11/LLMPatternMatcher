

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If so, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Otherwise, add the product to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Example usage (simulate user interaction)
// Add some products to the cart
add_to_cart('product1', 'Laptop', 1200, 1);
add_to_cart('product2', 'Mouse', 25, 2);
add_to_cart('product3', 'Keyboard', 75, 1);


// Display the contents of the cart
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
        echo "<form action='' method='post'>"; //  Leave action empty for simplicity
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='submit' value='Remove from Cart'> ";
        echo "</form>";
    }
    echo "</ul>";
}


// Example of updating quantity (Simulate user changing quantity)
// update_quantity('product2', 3); // Change quantity of mouse to 3

// Example of removing an item from the cart
// remove_from_cart('product1');
?>
