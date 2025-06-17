

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Product Data (For demonstration purposes) ---
// In a real application, you'd retrieve this from a database or an API.
$products = array(
    1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
    2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
    3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75),
);


// --- Cart Functions ---

// Add an item to the cart
function addToCart($product_id, $quantity = 1) {
    global $products;

    if (isset($products[$product_id])) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = array('id' => $product_id, 'name' => $products[$product_id]['name'], 'price' => $products[$product_id]['price'], 'quantity' => $quantity);
        }
    } else {
        // Product not found, handle error appropriately (e.g., display an error message)
        //echo "Product with ID " . $product_id . " not found.";
    }
}

// Remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Get the contents of the cart
function getCartContents() {
    return $_SESSION['cart'];
}

// Calculate the total cart value
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// --- Example Usage (Handle user interactions) ---

// 1. Add items to the cart (e.g., from a form submission)
// Example:  If the user clicks a "Add to Cart" button for product ID 1
// addToCart(1); // Adds one laptop to the cart

// 2. Update quantity (e.g., if user changes quantity on the page)
// updateCartItemQuantity(1, 2); // Changes the quantity of product 1 to 2

// 3. Remove an item
// removeCartItem(2); // Remove the mouse from the cart

// 4. Display the cart contents
$cart_contents = getCartContents();
$total_value = calculateTotal();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $total_value . "</p>";
}

?>
