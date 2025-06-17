
</body>
</html>


<?php

// Sample product data (replace with your database or data source)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Session management for cart
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    // Validate product ID
    if (!array_key_exists($product_id, $products)) {
        return false; // Product not found
    }

    // Check if product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity, 'price' => $products[$product_id]['price']];
    }
    return true;
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    return true;
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        return false; // Product not found in cart
    }

    if ($quantity <= 0) {
        remove_from_cart($product_id);
        return true; // Successfully removed
    }

    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    return true;
}



// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;  // Default quantity is 1
    add_to_cart($product_id, $quantity);
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Update cart quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    update_cart_quantity($product_id, $quantity);
}



// Display the cart
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>";
        echo "<strong>" . $products[$product_id]['name'] . "</strong> - $" . $products[$product_id]['price'] . " x " . $item['quantity'] . " = $" . ($item['quantity'] * $products[$product_id]['price']) . "</li>";
    }
    echo "</ul>";

    // Calculate total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total_price += ($item['quantity'] * $products[$product_id]['price']);
    }
    echo "<p><strong>Total: $" . number_format($total_price, 2) . "</strong></p>";
}


?>
