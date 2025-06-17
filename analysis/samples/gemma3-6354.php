

<?php
session_start();

// --- Session Variables ---
$cart = array(); // Array to store cart items.  Keys will be product IDs.
$total_items = 0;

// --- Helper Functions ---

// Add an item to the cart
function addToCart($product_id, $quantity = 1, $product_name = null) {
    global $cart, $total_items;

    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] += $quantity;
    } else {
        $cart[$product_id] = array(
            'quantity' => $quantity,
            'name' => $product_name ?? $product_id, // Use product_name if provided, otherwise use product_id as name
        );
    }
    $total_items += $quantity;
}

// Remove an item from the cart
function removeFromCart($product_id) {
    global $cart, $total_items;

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        $total_items -= $cart[$product_id]['quantity'];
    }
}

// Get the cart contents
function getCart() {
    return $cart;
}

// Get total cart value
function getTotalCartValue() {
    global $cart;
    $total = 0;
    foreach ($cart as $item) {
        // Assuming you have a way to get the product price.  Replace with your actual price retrieval.
        $price = getProductPrice($item['name']);  // Replace with your product price retrieval function
        $total_item_value = $price * $item['quantity'];
        $total_item_value += $total_item_value;
        $total = $total + $total_item_value;
    }
    return $total;
}


// --- Example Usage (Illustrative) ---

// Add some items to the cart
addToCart(101, 2);  // Product ID 101, quantity 2
addToCart(102, 1, "Blue Shirt"); // Product ID 102, quantity 1, product name "Blue Shirt"
addToCart(103, 3);


// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . getProductPrice($item['name']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . getTotalCartValue() . "</strong></p>";
}

// ---  Demonstration of session management ---

// To persist the cart across multiple page views, you'll likely use this in your
// page structure.  Here's how it might look:

// 1.  On the product page:
//     - When the user adds an item to the cart, call addToCart().
//     -  *After* the product page loads, `session_start();` will automatically
//        start the session, and the $cart variable will be available.

// 2.  On the cart page:
//     - `session_start();`
//     - Display the contents of $cart, calculated using getCart() and getTotalCartValue().

?>
