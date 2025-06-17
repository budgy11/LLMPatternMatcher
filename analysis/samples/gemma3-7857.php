

<?php

// --- Database Configuration (Replace with your actual values) ---
$db_host = "localhost";
$db_name = "e_commerce";
$db_user = "your_username";
$db_password = "your_password";
// --- End Database Configuration ---

//  --- Product Data (Example - Replace with your actual database query) ---
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200, 'stock' => 5],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25, 'stock' => 50],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75, 'stock' => 20],
];

// --- Shopping Cart (Simulated for this example) ---
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    global $cart;

    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id]['quantity'] += $quantity;
    } else {
        $cart[$product_id] = ['quantity' => $quantity];
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    global $cart;

    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id]['quantity'] = $quantity;
    } else {
        // Handle the case where the item isn't in the cart - perhaps log an error or redirect.
        // For this example, we'll just silently do nothing.
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    global $cart;

    if (array_key_exists($product_id, $cart)) {
        unset($cart[$product_id]);
    }
}

// Function to calculate the total cart value
function calculateTotal() {
    global $cart;
    $total = 0;
    foreach ($cart as $item_id => $details) {
        $product = getProductById($item_id);  // Use getProductById function
        if ($product) {
            $total_item_price = $product['price'] * $details['quantity'];
            $total += $total_item_price;
        }
    }
    return $total;
}

//  Helper function to get product details by ID (simulated database query)
function getProductById($product_id) {
    global $products;
    if (array_key_exists($product_id, $products)) {
        return $products[$product_id];
    }
    return null;
}

// --- Purchase Functionality ---

function processPurchase() {
    global $cart;
    $total = calculateTotal();

    if (empty($cart)) {
        return false; // Cart is empty
    }

    // **Important:** In a real application, you would:
    // 1. Authenticate the user.
    // 2. Process the payment (using a payment gateway).
    // 3. Update the database with the order details (product IDs, quantities, user ID, order date, etc.).
    // 4. Empty the cart.

    // For this simulated example, we'll just print a success message.
    echo "<h2>Order Summary</h2>";
    echo "<p>Total: $" . number_format($total, 2) . "</p>";
    echo "<p>Thank you for your order! (Simulated)</p>";
    // Empty the cart (simulated)
    $cart = [];

    return true; // Purchase completed (simulated)
}

// ---  Example Usage/UI (for demonstration)  ---

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    addToCart($product_id, $quantity);
    echo "<p>Item added to cart.</p>";
}

// 2. Update Cart Quantity
if (isset($_POST['update_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateCartQuantity($product_id, $quantity);
    echo "<p>Cart updated.</p>";
}

// 3. Remove from Cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// 4. Process Purchase
if (isset($_POST['purchase'])) {
    processPurchase();
}


// --- Display Cart Items (for demonstration) ---
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item_id => $details) {
        $product = getProductById($item_id);
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . " x " . $details['quantity'] . " = $" . number_format($product['price'] * $details['quantity'], 2) . "</li>";
    }
    echo "</ul>";
}

?>
