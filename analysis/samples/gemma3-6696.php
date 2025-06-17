

<?php

session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the contents of the cart
function getCart() {
    return $_SESSION['cart'];
}

// Example Usage (Demonstration)

// Add some items to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 3); //Adding more of the same item

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = getCart();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }
}
echo "</ul>";

// Example of updating a quantity
updateQuantity(2, 5);

// Display the updated cart contents
echo "<br><h2>Cart Contents (After Update):</h2>";
$cart_items = getCart();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }
}
echo "</ul>";


// Example of removing an item from the cart
removeCartItem(2);
echo "<br><h2>Cart Contents (After Removal):</h2>";
$cart_items = getCart();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }
}
echo "</ul>";


?>
