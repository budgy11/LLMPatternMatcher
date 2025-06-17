

<?php
session_start();

// --- Example Product Information ---
// This is just for demonstration.  In a real application,
// you would retrieve product data from a database or other source.

$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// --- Add to Cart Function ---
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0;  // Get existing quantity or default to 0
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity; // Initialize if cart doesn't exist
    }
}

// --- Remove from Cart Function ---
function removeCartItem($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// --- Update Cart Item Quantity Function ---
function updateCartItemQuantity($productId, $quantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// --- View Cart Function ---
function viewCart()
{
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products[$productId];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";
}

// --- Handle Add to Cart Request (Example - from a button click) ---
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id'];  // Cast to integer for safety
    $quantity = (int)$_POST['quantity'] ?? 1;  //Get quantity or default to 1

    addToCart($productId, $quantity);
}

// --- Handle Remove from Cart Request ---
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];
    removeCartItem($productId);
}

// --- Handle Update Cart Item Quantity Request ---
if (isset($_POST['update_quantity'])) {
    $productId = (int)$_POST['product_id'];
    $newQuantity = (int)$_POST['quantity'];
    updateCartItemQuantity($productId, $newQuantity);
}


?>
