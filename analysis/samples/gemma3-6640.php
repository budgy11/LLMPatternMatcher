

<?php
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Functions for Cart Management ---

// Add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product exists, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If the product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Get the contents of the cart
function get_cart_contents() {
    return $_SESSION['cart'];
}


// --- Example Usage / Handling Cart Actions ---

// 1. Add a product to the cart (Example)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = floatval($_POST['price']); // Convert to float
    $quantity = intval($_POST['quantity']);  // Convert to integer

    add_to_cart($product_id, $product_name, $price, $quantity);
    $message = "Product '$product_name' added to cart.";
}

// 2. Remove an item (Example)
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    remove_from_cart($product_id);
    $message = "Product removed from cart.";
}

// 3. Update quantity (Example)
if (isset($_GET['update'])) {
    $product_id = $_GET['update'];
    $new_quantity = intval($_GET['quantity']); // Ensure it's an integer
    update_cart_quantity($product_id, $new_quantity);
    $message = "Quantity updated for product.";
}


// --- Displaying the Cart Contents ---

// Get the cart contents
$cart_contents = get_cart_contents();

// Get the total cart value
$total = 0;
if (is_array($cart_contents)) {
    foreach ($cart_contents as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}

?>
