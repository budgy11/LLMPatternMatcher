

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --------------------- Functions for Cart Management ---------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param string $product_name The name of the product.
 * @param int $price The price of the product.
 * @param int $quantity The quantity of the product being added.
 */
function add_to_cart($product_id, $product_name, $price, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        // Item already in the cart, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}


/**
 * Removes an item from the cart by its product ID.
 *
 * @param int $product_id The ID of the product to remove.
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 */
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Gets the cart contents.
 *
 * @return array The cart contents.
 */
function get_cart_contents() {
    return $_SESSION['cart'];
}

// --------------------- Example Usage ---------------------

// Add some items to the cart
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);
add_to_cart(1, "Laptop", 1200, 1); // Add another laptop to increase quantity


// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example of removing an item
//remove_from_cart(2);
//echo "<p>Item removed from cart.</p>";


//Example of updating quantity
//update_quantity(1, 3);
//echo "<p>Quantity of Laptop updated to 3</p>";



?>
