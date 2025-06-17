

<?php

// --- Configuration ---
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

$cart = []; // Array to store the cart items
$order_id = 1; // Simple order ID generator (incrementing)

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 */
function addToCart(int $product_id, int $quantity = 1) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        if ($quantity <= $product['quantity']) {
            $cart[] = ['product_id' => $product_id, 'quantity' => $quantity, 'price' => $product['price']];
            echo "<p>Added $quantity x $product['name'] to your cart.</p>";
        } else {
            echo "<p>Not enough stock for $product['name']</p>";
        }
    } else {
        echo "<p>Product ID $product_id not found.</p>";
    }
}

/**
 * Calculates the total order amount.
 */
function calculateTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

/**
 * Displays the cart contents.
 */
function displayCart() {
    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['quantity'] . " x " . $products[$item['product_id']]['name'] . " - $" . number_format($item['quantity'] * $item['price'], 2) . "</li>";
    }
    echo "</ul>";

    echo "<h3>Total: $" . number_format(calculateTotal(), 2) . "</h3>";
}

/**
 * Simulates an order submission.  In a real application, this would
 * process the payment, update inventory, etc.
 */
function submitOrder() {
    global $cart, $products, $order_id;

    if (empty($cart)) {
        echo "<p>Your cart is empty.  Please add items before submitting an order.</p>";
        return;
    }

    // Reset the cart after order submission (or update inventory, etc.)
    $cart = [];

    echo "<h2>Order Confirmation</h2>";
    echo "<p>Your order (ID: $order_id) has been placed!</p>";
    echo "<p>Thank you for your purchase.</p>";

    // Increment the order ID
    $order_id++;
}

// --- Handling User Input (Example) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'] ?? 1; // Use ?? to provide a default value of 1
    addToCart($product_id, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'submit_order') {
    submitOrder();
}

// --- Displaying the Shopping Page ---
?>
