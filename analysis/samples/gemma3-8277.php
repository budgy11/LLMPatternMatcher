

<?php
session_start();

// Configuration
$items = []; // Array to store the items in the cart
$cart_file = 'cart.json'; // File to store the cart data

// Helper functions to read and write to the JSON file
function readCart() {
    if (file_exists($cart_file)) {
        $data = file_get_contents($cart_file);
        return json_decode($data, true); // Decode to associative array
    }
    return [];
}

function writeCart($cart) {
    file_put_contents($cart_file, json_encode($cart, JSON_PRETTY_PRINT));
}


// Cart functions

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    $cart = readCart();

    // Check if the product is already in the cart
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] += $quantity;
    } else {
        $cart[$product_id] = ['quantity' => $quantity];
    }

    writeCart($cart);
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    $cart = readCart();
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }

    writeCart($cart);
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    $cart = readCart();
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
    writeCart($cart);
}


// Display the cart
function display_cart() {
    $cart = readCart();

    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        $product_name = get_product_name($product_id); //Get product name from a database
        echo "<li>";
        echo "<strong>$product_name</strong> - Quantity: $item['quantity'] - Price: $item['price'] (Assuming you have a database for product prices)";
        echo "<form method='post'>";
        echo "<label for='$product_id'>Quantity:</label>";
        echo "<input type='number' id='$product_id' name='$product_id' value='$item['quantity']' min='1'>";
        echo "<input type='submit' name='$product_id' value='Update'>";
        echo "<a href='?remove=$product_id'>Remove</a>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";

    // Calculate the total
    $total = 0;
    foreach ($cart as $product_id => $item) {
        $total += $item['quantity'] * $item['price']; // Assuming you have a database for product prices
    }

    echo "<p><strong>Total: $total</strong></p>";
}

// Get product name from database (example - modify to fit your setup)
function get_product_name($product_id) {
    //Replace this with your actual database query
    $products = [
        1 => ['name' => 'Laptop'],
        2 => ['name' => 'Mouse'],
        3 => ['name' => 'Keyboard']
    ];
    if (isset($products[$product_id])) {
        return $products[$product_id]['name'];
    } else {
        return "Unknown Product";
    }
}



// Handle form submission (Update Quantity)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = htmlspecialchars($_POST['product_id']);  // Sanitize input
    if (isset($product_id)) {
        update_quantity($product_id, $_POST['quantity']);
    }
}

// Handle Remove Item
if (isset($_GET['remove'])) {
    remove_from_cart($_GET['remove']);
}

// Initialize the cart (if not already initialized)
if (!readCart()) {
    $cart = [];
    writeCart($cart);
}


//Display the cart
display_cart();
?>
