

<?php

// Initialize the session if it doesn't exist
session_start();

// Sample product data (replace with your actual product data)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to add an item to the cart
function addItemToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0; // Initialize to 0 if not found
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    }
}


// Handle adding items to the cart (example: from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = (int)$_POST['product_id']; // Convert to integer for safety
        $quantity = (int)$_POST['quantity'];

        if (isset($products[$productId])) {
            addItemToCart($productId, $quantity);
        } else {
            echo "Product ID " . $productId . " not found.";
        }
    } elseif (isset($_POST['remove_from_cart'])) {
        $productId = (int)$_POST['product_id'];
        removeItemFromCart($productId);
    } elseif (isset($_POST['update_quantity'])) {
       $productId = (int)$_POST['product_id'];
       $quantity = (int)$_POST['quantity'];
       updateQuantity($productId, $quantity);
    }
}


// Display the cart contents
echo "<h2>Your Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products[$productId];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

?>
