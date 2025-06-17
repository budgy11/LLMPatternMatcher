

<?php

session_start();

// Cart array to store items
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $cart[$product_id]['quantity'] += $quantity;
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    unset($cart[$product_id]);
}

// Function to get the cart contents
function get_cart_contents() {
    return $cart;
}

// Example Usage - Simulated Products
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Shoes', 'price' => 80],
];

// ---  Example User Interaction (Simulated) ---

// 1. User adds a T-Shirt to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
echo "T-Shirt added to cart (quantity: 2) <br>";

// 2. User updates the quantity of Jeans to 3
update_cart_quantity(2, 3);
echo "Jeans quantity updated to 3 <br>";

// 3. User removes Shoes from the cart
remove_from_cart(3);
echo "Shoes removed from cart <br>";

// 4. Display the cart contents
$cart_contents = get_cart_contents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// -----------------------  Session Management  -----------------------
// Add Cart Data to Session
$_SESSION['cart'] = $cart;

// Display Cart Contents from Session (for demonstration)
echo "<h2>Cart Contents from Session:</h2>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}


?>
