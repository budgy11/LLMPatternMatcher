

<?php
session_start();

// --- Cart Operations ---

// 1. Add an Item to the Cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart.  If so, update the quantity.
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Otherwise, add a new entry with quantity 1.
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// 2. Remove an Item from the Cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// 3. Update Item Quantity
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// 4. Get Cart Items
function get_cart_items() {
    return $_SESSION['cart'];
}

// 5. Calculate Cart Total
function calculate_cart_total() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}


// --- Example Usage (Demonstration) ---

// **Simulating Product Information (Replace with actual database retrieval)**
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];

// ---------------------  Cart Session Handling Examples ---------------------

// **1. Add a Product to the Cart**
// $product_id = 1;
// $quantity = 2;
// add_to_cart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);

// **2. Remove a Product**
// remove_from_cart(2);

// **3. Update Quantity**
// update_cart_quantity(1, 3);

// **4.  Display Cart Contents (for demonstration)**
$cart_items = get_cart_items();
$cart_total = calculate_cart_total();

echo "<h2>Your Cart</h2>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
}


?>
