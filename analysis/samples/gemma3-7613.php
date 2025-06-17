

<?php
session_start();

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'item_count' => 1
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['item_count'] = count($_SESSION['cart'][$product_id]);
    }
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        $_SESSION['cart'][$product_id]['item_count'] = count($_SESSION['cart'][$product_id]);
    }
}


// Handle adding to cart (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    $name = "Product " . $product_id; // You'd likely get this from a product ID
    $price = 10.00; // Example price
    add_to_cart($product_id, $name, $price);
}

// Handle removing an item
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    remove_from_cart($product_id);
}

// Handle updating quantity
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['update_quantity'])) {
    $product_id = $_GET['update_quantity'];
    $new_quantity = intval($_GET['quantity']); // Ensure it's an integer
    update_quantity($product_id, $new_quantity);
}


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";

$total = 0;

foreach ($_SESSION['cart'] as $item_id => $product_info) {
    echo "<li>";
    echo "<strong>" . $product_info['name'] . "</strong> - $" . $product_info['price'] . " ";
    echo "Quantity: " . $product_info['quantity'];
    echo "<form method='get' action='cart.php'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='" . $product_info['quantity'] . "' min='1'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
    $total += $product_info['price'] * $product_info['quantity'];
}

echo "</ul>";
echo "<p><strong>Total: $" . $total . "</strong></p>";

// Empty cart
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
}
?>
