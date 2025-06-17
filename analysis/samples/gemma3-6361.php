

<?php
session_start();

// --- Cart Functions ---

// Add item to cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][$product_id] = [
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Update quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // Product doesn't exist in cart, create a new item
        add_to_cart($product_id, $product_name, $price, $quantity); // Use the updated quantity
    }
}

// Remove item from cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Get cart items
function get_cart_items() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return [];
  }
}

// Calculate cart total
function calculate_cart_total() {
    $total = 0;
    $cart_items = get_cart_items();

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// --- Example Usage (In a Web Page - e.g., index.php) ---

// Example product data (simulated)
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80]
];

// --- Handle Add to Cart Request (e.g., from a form submission) ---

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart_id']) && isset($_POST['quantity'])) {
        $product_id = (int)$_POST['add_to_cart_id']; // Cast to integer
        $quantity = (int)$_POST['quantity'];
        add_to_cart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        update_cart_quantity($product_id, $quantity);
    } elseif (isset($_POST['remove_from_cart_id'])) {
        $product_id = (int)$_POST['remove_from_cart_id'];
        remove_from_cart($product_id);
    }
}

// --- Display Cart Contents ---

$cart_items = get_cart_items();
$cart_total = calculate_cart_total();

echo "<h1>Your Shopping Cart</h1>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
}

?>
