

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// ------------------------------------------------------------------------
//  Functions to handle adding items to the cart
// ------------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

// ------------------------------------------------------------------------
//  Example usage - Handle adding items from a form (simulated)
// ------------------------------------------------------------------------

//  If a form was submitted (simulated here for demonstration)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default quantity is 1
    add_to_cart($product_id, $quantity);
}


// ------------------------------------------------------------------------
//  Functions to handle cart manipulation (e.g., removing items)
// ------------------------------------------------------------------------

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
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
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}



// ------------------------------------------------------------------------
//  Functions to calculate cart totals
// ------------------------------------------------------------------------

/**
 * Calculates the total number of items in the cart.
 *
 * @return int The total number of items.
 */
function get_cart_total() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $total_for_product = $quantity; // Assuming you'll fetch product price later
            $total += $total_for_product;
        }
    }
    return $total;
}

/**
 * Calculates the cart total (simplified - assuming product price is known)
 *  **Important:** This is a basic example.  In a real application, you'd fetch
 *  the price of each product from a database or other source.
 *
 * @return float The total cart value.
 */
function get_cart_total_value() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Simulate fetching product price (replace with your database lookup)
            $product_price = 25; // Example price
            $total_for_product = $product_price * $quantity;
            $total_for_product = $total_for_product;
            $total += $total_for_product;
        }
    }
    return $total;
}


// ------------------------------------------------------------------------
//  Display the cart contents
// ------------------------------------------------------------------------

// Display the cart contents
if (isset($_SESSION['cart'])) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        //  Replace this with your actual product details retrieval from the database
        $product_name = "Product " . $product_id;

        echo "<li>" . $product_name . " - Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Cart Value: $" . get_cart_total_value() . "</strong></p>";
} else {
    echo "<p>Your cart is empty.</p>";
}

?>
