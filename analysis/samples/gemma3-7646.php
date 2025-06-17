    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
        <option value="stripe">Stripe</option>
    </select><br><br>


    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php
session_start();

// Configuration
$items = [
    ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Cart functions
function addItemToCart($productId, $cart) {
    if (array_key_exists($productId, $cart)) {
        $cart[$productId]['quantity'] = $cart[$productId]['quantity'] + 1;
    } else {
        $cart[$productId] = ['quantity' => 1];
    }
    
    // Save the cart to session
    $_SESSION['cart'] = $cart;
}

function removeItemFromCart($productId, &$cart) {
    if (array_key_exists($productId, $cart)) {
        unset($cart[$productId]);
    }
}

function updateQuantity($productId, $quantity, &$cart) {
    if (array_key_exists($productId, $cart)) {
        $cart[$productId]['quantity'] = $quantity;
    }
}

function getCart() {
    return $_SESSION['cart'] ?? []; // Return empty array if cart is not initialized
}

function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle Add to Cart Request
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Cast to integer for safety
    addItemToCart($productId, getCart());
}

// Handle Remove from Cart Request
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];
    removeItemFromCart($productId, getCart());
}

// Handle Update Quantity Request
if (isset($_POST['update_quantity'])) {
    $productId = (int)$_POST['product_id'];
    $newQuantity = (int)$_POST['quantity'];
    updateQuantity($productId, $newQuantity, getCart());
}


// Display the Cart
$cart = getCart();
$total = calculateCartTotal($cart);
?>
