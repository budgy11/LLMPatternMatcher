

<?php
session_start();

// Example Product Data (replace with your actual product information)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0; // Initialize if not already present
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] + $quantity);
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to get the cart items
function getCartItems() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return [];
    }
}

// Function to remove an item from the cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Also remove from the session for consistency. Important for future operations.
        unset($_SESSION['cart'][$productId]);
    }
}


// --- Example Usage (Demonstration - can be replaced with your UI logic) ---

// Example: Adding an item to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Convert to integer for safety
    $quantity = (int)$_POST['quantity'];

    if (isset($products[$productId])) {
        addToCart($productId, $quantity);
    }
}

// Example: Removing an item from the cart
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];
    removeFromCart($productId);
}


// --- Displaying the Cart ---

// Get the cart items
$cartItems = getCartItems();

// Calculate the total price
$totalPrice = 0;
foreach ($cartItems as $productId => $quantity) {
    if (isset($products[$productId])) {
        $totalPrice += $products[$productId]['price'] * $quantity;
    }
}

echo "<h2>Your Cart</h2>";

if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $productId => $quantity) {
        if (isset($products[$productId])) {
            echo "<li>" . $products[$productId]['name'] . " - Quantity: " . $quantity . " - Price: $" . $products[$productId]['price'] . "</li>";
        }
    }
    echo "</ul>";
    echo "<p>Total: $" . number_format($totalPrice, 2) . "</p>";
}

?>
