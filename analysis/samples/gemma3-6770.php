
</body>
</html>


<?php

// Sample product data (replace with a database connection)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Session handling (for cart persistence)
session_start();

// Initialize cart if not already present
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $cart) {
    if (!is_numeric($productId) || $productId <= 0) {
        return false; // Invalid product ID
    }

    if (!isset($cart[$productId])) {
        return false; // Product not found
    }

    if (!is_numeric($quantity) || $quantity <= 0) {
        return false; // Invalid quantity
    }

    $cart[$productId] = $cart[$productId] + $quantity;
    return true;
}

// Function to remove an item from the cart
function removeFromCart($productId, $cart) {
    if (!is_numeric($productId) || $productId <= 0) {
        return false; // Invalid product ID
    }

    if (!isset($cart[$productId]) || $cart[$productId] <= 0) {
        return false; // Product not found or already empty
    }

    $cart[$productId] -= $quantity = $cart[$productId];
    if ($cart[$productId] == 0) {
        unset($cart[$productId]);
    }
    return true;
}


// Function to calculate the cart total
function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $productId => $quantity) {
        if (isset($products[$productId])) {
            $total += $products[$productId]['price'] * $quantity;
        }
    }
    return $total;
}


// Handle Add to Cart request (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id'];  // Cast to integer for safety
    $quantity = (int)$_POST['quantity']; // Cast to integer for safety

    if (addToCart($productId, $quantity, $_SESSION['cart'])) {
        echo "<p style='color: green;'>Item added to cart.</p>";
    } else {
        echo "<p style='color: red;'>Failed to add item to cart.</p>";
    }
}


// Handle Remove from Cart request (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];

    if (removeFromCart($productId, $_SESSION['cart'])) {
        echo "<p style='color: green;'>Item removed from cart.</p>";
    } else {
        echo "<p style='color: red;'>Failed to remove item from cart.</p>";
    }
}

// Display the Cart
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        echo "<li>";
        echo "Product: " . $products[$productId]['name'] . " - Quantity: " . $quantity . " - Price: $" . $products[$productId]['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . calculateCartTotal($_SESSION['cart']) . "</strong></p>";
}

?>
