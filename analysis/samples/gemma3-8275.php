

  <!-- Add a 'Checkout' button here -->
  <a href="checkout.php">Checkout</a>


</body>
</html>


<?php
session_start();

// --- Cart Data ---
$cart = [];

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
    if (!isset($cart[$productId])) {
        $cart[$productId] = [
            'name' => $productName,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity
        ];
    } else {
        $cart[$productId]['quantity'] += $quantity;
        $cart[$productId]['total'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $newQuantity;
        $cart[$productId]['total'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
    }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
    if (isset($cart[$productId])) {
        unset($cart[$productId]);
    }
}

// Function to calculate the total cart value
function calculateCartTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total = $total + $item['total'];
    }
    return $total;
}

// --- Product Data (Simulated Database - Replace with your actual database) ---
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
    4 => ['name' => 'Monitor', 'price' => 300]
];


// --- Handle Cart Actions ---
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addItemToCart($productId, $products[$productId]['name'], $products[$productId]['price'], $quantity);
} elseif (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];
    updateQuantity($productId, $newQuantity);
} elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $productId = $_POST['product_id'];
    removeItemFromCart($productId);
}
// --- Display Cart ---

$cartTotal = calculateCartTotal();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['total'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $cartTotal . "</strong></p>";
}

?>
