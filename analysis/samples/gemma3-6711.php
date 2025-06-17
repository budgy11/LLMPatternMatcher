

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Example Usage (Simulating a product)
$product1_id = 1;
$product1_name = "T-Shirt";
$product1_price = 20.00;

$product2_id = 2;
$product2_name = "Jeans";
$product2_price = 50.00;

// --- Cart Operations ---

// 1. Add an item to the cart
addToCart($product1_id, $product1_name, $product1_price);
addToCart($product2_id, $product2_name, $product2_price, 2); // Add 2 pairs of Jeans

// 2. Display the cart contents (for demonstration)
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
    }
    echo "</ul>";
}



// 3. Remove an item
removeFromCart($product1_id);

// 4. Update quantity
updateQuantity($product2_id, 3);

// Display updated cart
echo "<h2>Shopping Cart (Updated)</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
    }
    echo "</ul>";
}

?>
