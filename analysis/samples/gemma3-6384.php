

<?php

session_start();

// Check if the cart exists, if not, initialize it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ------------------------------------------------------------------
// Example Functions (You'll need to implement these)
// ------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product being added.
 * @param string $product_name The name of the product.  (Optional, for display)
 * @param int $quantity The quantity of the product to add. Defaults to 1.
 * @return void
 */
function add_to_cart(string $product_id, string $product_name = '', int $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['name' => $product_name, 'quantity' => $quantity, 'price' => 0]; // Initialize with price
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product being updated.
 * @param int $new_quantity The new quantity for the product.
 * @return void
 */
function update_cart_quantity(string $product_id, int $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total price of items in the cart.
 *
 * @return float The total price.
 */
function calculate_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}


/**
 * Returns the cart contents
 * @return array  The cart array
 */
function get_cart() : array{
    return $_SESSION['cart'];
}


// ------------------------------------------------------------------
// Example Usage (Illustrative - you'll integrate this into your form/logic)
// ------------------------------------------------------------------

// Example: Adding an item to the cart
add_to_cart('product1', 'Awesome T-Shirt', 2);
add_to_cart('product2', 'Cool Mug', 1);

// Example: Updating the quantity
update_cart_quantity('product1', 5);


// Example: Getting and displaying the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculate_cart_total() . "</p>";
}

// Example: Removing an item
//remove_from_cart('product1');


// ------------------------------------------------------------------
// Important Considerations and Next Steps
// ------------------------------------------------------------------

// 1.  Database Integration:  This example uses an in-memory session.  You'll *absolutely* need to integrate this with your database to store cart data persistently.  You'll need a table to store cart items, and code to read/write to that table.

// 2.  Product Information:  You'll want to fetch product details (name, price, etc.) from your database based on the product ID. This is crucial for displaying accurate information in the cart.

// 3.  User Authentication:  In a real application, you'll need user authentication to associate carts with specific users.

// 4.  Error Handling: Add error handling (e.g., checking for invalid product IDs, handling database errors).

// 5.  Quantity Validation:  Validate the quantity being added/updated to prevent negative quantities or extremely large numbers.

// 6.  Security:  Protect your session data from unauthorized access and modification.

// 7.  Testing:  Thoroughly test your cart implementation with various scenarios (adding, removing, updating, calculating total).
?>
