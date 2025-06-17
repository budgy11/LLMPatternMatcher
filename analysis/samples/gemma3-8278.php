

<?php
session_start();

// Define product data (replace with your actual data source)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add item to cart
function addToCart($productId, $quantity = 1) {
    if (isset($products[$productId])) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $productId,
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => $quantity
            ];
        }
    }
}

// Function to update quantity
function updateQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to remove item from cart
function removeCartItem($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to get cart total
function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle adding to cart (from a form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    addToCart($productId, $quantity);
}

// Handle updating quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    updateQuantity($productId, $quantity);
}

// Handle removing item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    removeCartItem($productId);
}

// Get cart contents for display
$cart_items = $_SESSION['cart'];

// Calculate cart total
$total = calculateCartTotal();

?>
