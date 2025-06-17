

<?php
session_start();

// Configuration
$items = []; // Array to store items in the cart
$cart_file = 'cart.json'; // File to store cart data (JSON format)

// Function to load cart data from the file
function loadCart() {
    if (file_exists($cart_file)) {
        $data = file_get_contents($cart_file);
        if ($data) {
            $cart = json_decode($data, true);
            return $cart;
        } else {
            return [];
        }
    }
    return [];
}

// Function to save cart data to the file
function saveCart($cart) {
    file_put_contents($cart_file, json_encode($cart, JSON_PRETTY_PRINT));
}


// ------------------ Cart Operations ------------------

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    $cart = loadCart();

    if (empty($cart)) {
        $cart[$product_id] = $quantity;
    } else {
        if (isset($cart[$product_id])) {
            $cart[$product_id] += $quantity;
        } else {
            $cart[$product_id] = $quantity;
        }
    }

    saveCart($cart);
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    $cart = loadCart();
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
    saveCart($cart);
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    $cart = loadCart();
    if (isset($cart[$product_id])) {
        $cart[$product_id] = $quantity;
    }
    saveCart($cart);
}


// Get the cart contents
function get_cart_contents() {
    $cart = loadCart();
    return $cart;
}

// Calculate the total price
function calculate_total() {
    $cart = get_cart_contents();
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        // Assume we have a product price map (replace with your actual data source)
        $product_prices = [
            1 => 10,  // Product ID 1: $10
            2 => 20,  // Product ID 2: $20
            3 => 30   // Product ID 3: $30
        ];
        if (isset($product_prices[$product_id])) {
            $total_item_price = $product_prices[$product_id] * $quantity;
            $total += $total_item_price;
        }
    }
    return $total;
}



// ------------------  Example Usage (for demonstration) ------------------

// Example: Add an item to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    add_to_cart($product_id, $quantity);
    $cart_contents = get_cart_contents();
    echo "Item added to cart. Cart contents: " . json_encode($cart_contents) . "<br>";
}

// Example: Remove an item from the cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    remove_from_cart($product_id);
    $cart_contents = get_cart_contents();
    echo "Item removed from cart. Cart contents: " . json_encode($cart_contents) . "<br>";
}

// Example: Update quantity
if (isset($_GET['update_quantity'])) {
    $product_id = $_GET['update_quantity'];
    $new_quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
    update_quantity($product_id, $new_quantity);
    $cart_contents = get_cart_contents();
    echo "Quantity updated. Cart contents: " . json_encode($cart_contents) . "<br>";
}

// Show the cart contents
$cart_contents = get_cart_contents();
$total = calculate_total();

echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
}

echo "<h2>Total: $" . $total . "</h2>";

?>
