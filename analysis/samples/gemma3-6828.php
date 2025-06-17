

<?php
session_start();

// Configuration
$items = []; // Array to store items in the cart
$cart_file = 'cart.json'; // File to store cart data

// Function to load cart from JSON file
function loadCart() {
    if (file_exists($cart_file)) {
        $data = file_get_contents($cart_file);
        if ($data) {
            $cart = json_decode($data, true);
            return $cart;
        } else {
            return []; // Return empty array if file is corrupt
        }
    }
    return [];
}

// Function to save cart to JSON file
function saveCart($cart) {
    file_put_contents($cart_file, json_encode($cart, JSON_PRETTY_PRINT));
}


// --------------------- Cart Functions ---------------------

// Add item to cart
function add_to_cart($product_id, $quantity = 1) {
    $cart = loadCart();

    // Check if product already in cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            saveCart($cart);
            return;
        }
    }

    // If not in cart, add new item
    $cart[] = ['product_id' => $product_id, 'quantity' => $quantity];
    saveCart($cart);
}


// Update quantity of item in cart
function update_quantity($product_id, $quantity) {
    $cart = loadCart();

    // Find the product in the cart and update the quantity
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            saveCart($cart);
            return;
        }
    }

    // Product not found - could be handled differently (e.g., throw an error)
    echo "<p>Product ID '$product_id' not found in cart.</p>";
}


// Remove item from cart
function remove_from_cart($product_id) {
    $cart = loadCart();

    // Filter out the item to be removed
    $cart = array_filter($cart, function ($item) use ($product_id) {
        return $item['product_id'] !== $product_id;
    });

    saveCart($cart);
}


// Get cart contents
function get_cart_contents() {
    return loadCart();
}


// Calculate total price
function calculate_total_price() {
    $cart = get_cart_contents();
    $total = 0;
    foreach ($cart as $item) {
        // Assuming you have a product database or data source
        //  and you can retrieve the price.  Replace this with your actual logic.
        $product_price = get_product_price($item['product_id']); // Implement this function
        $total_item_price = $product_price * $item['quantity'];
        $total += $total_item_price;
    }
    return $total;
}

// --------------------- Product Data (Dummy for Example) ---------------------
// Replace this with your actual product data source
function get_product_price($product_id) {
    // This is a dummy function. Replace it with your database query or data retrieval.
    switch ($product_id) {
        case 1: return 10.00;
        case 2: return 25.50;
        case 3: return 5.75;
        default: return 0; // Price not found
    }
}

// --------------------- Example Usage (In a View/Page) ---------------------

// Add to cart (example)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;  // Default to 1
    add_to_cart($product_id, $quantity);
    echo "<p>Item added to cart.</p>";
}

// Update quantity (example)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default to 1
    update_quantity($product_id, $quantity);
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// Get cart contents to display
$cart_contents = get_cart_contents();

// Calculate total price
$total_price = calculate_total_price();

// Display cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $item) {
        echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . ", Price: $" . get_product_price($item['product_id']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}
?>
