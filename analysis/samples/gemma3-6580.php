

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
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
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'] ?? []; // Return an empty array if cart is not initialized
}

// Example usage:  (Simulated product information)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];

// ---  User Actions  ---

// Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer

    if (isset($products[$product_id])) {
        addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
        echo "<p>Product added to cart!</p>";
    } else {
        echo "<p>Product not found.</p>";
    }
}

// Remove a product from the cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    removeFromCart($product_id);
    echo "<p>Product removed from cart!</p>";
}

// Update the quantity of a product in the cart
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $new_quantity = (int)$_POST['quantity'];

    if (isset($products[$product_id])) {
        updateQuantity($product_id, $new_quantity);
        echo "<p>Quantity updated!</p>";
    } else {
        echo "<p>Product not found.</p>";
    }
}


// --- Display Cart Contents ---

$cart_contents = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";

    // Calculate total
    $total = 0;
    foreach ($cart_contents as $id => $item) {
        $total += $item['price'] * $item['quantity'];
    }

    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}

?>
