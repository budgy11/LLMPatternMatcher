
</body>
</html>


<?php
session_start();

// Product Data (Simulated Database)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Cart (Session-Based)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Helper functions
function addItemToCart($productId, $quantity = 1) {
    $_SESSION['cart'][] = $productId;
}

function getCartItems() {
    return $_SESSION['cart'];
}

function removeItemFromCart($productId) {
    $cart = getCartItems();
    foreach ($cart as $key => $item) {
        if ($item == $productId) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
    return false;
}

function calculateTotal() {
    $total = 0;
    $cart = getCartItems();
    foreach ($cart as $item) {
        $total += $products[$item]['price'];
    }
    return $total;
}

// Handle Adding Items to Cart
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Validate product ID
    if (isset($products[$productId])) {
        addItemToCart($productId);
    }
}

// Handle Removing Items from Cart
if (isset($_POST['remove_item'])) {
    $productId = (int)$_POST['product_id'];
    removeItemFromCart($productId);
}

// Display the Cart
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Online Store - Cart</title>";
echo "</head>";
echo "<body>";
echo "<h1>Shopping Cart</h1>";

$cartItems = getCartItems();

if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $item) {
        echo "<li>" . $products[$item]['name'] . " - $" . $products[$item]['price'] . "</li>";
    }
    echo "</ul>";
}

echo "<br>";
echo "<a href='checkout.php'>Proceed to Checkout</a>"; // Link to checkout page

echo "<br>";
echo "<a href='index.php'>Back to Products</a>"; // Link back to product listing

echo "</body>";
echo "</html>";
?>
