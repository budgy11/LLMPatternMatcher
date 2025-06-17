

if (isset($_POST['add_to_cart'])) {
    $product_id = 1; // Or get the product ID from where the button was clicked
    $quantity = 1; // Or get the quantity from the user's input
    add_to_cart($product_id, $quantity);
}


<?php
session_start();

// --- Cart Operations ---

// 1. Add item to cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][$product_id] = [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    ];
}

// 2. Update item quantity in cart
function update_cart_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// 3. Remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// 4. Get cart items
function get_cart_items() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return [];
    }
}

// 5. Calculate total cart value
function calculate_cart_total() {
    $total = 0;
    $cart_items = get_cart_items();

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// --- Example Usage (Simulated Product Data) ---

// Sample product data (replace with your actual database)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];

// ---  Session Management and User Interaction (Simulated) ---
// For demonstration purposes, we'll simulate a user adding an item.
// In a real application, you'd get this from a form or other user input.

// 1. Add a Laptop to the cart (Product ID 1)
add_to_cart(1, $products[1]['name'], $products[1]['price'], 1);

// 2.  Update the quantity of the Laptop to 2
update_cart_quantity(1, 2);

// 3. Get the cart contents
$cart_items = get_cart_items();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// 4. Calculate and display the total
$total = calculate_cart_total();
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";

// 5. Remove the Mouse from the cart
remove_from_cart(2);

// Display updated cart
echo "<p><strong>Updated Cart Items:</strong></p>";
$cart_items = get_cart_items();

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}
?>
