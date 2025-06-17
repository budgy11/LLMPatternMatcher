
</body>
</html>


<?php
session_start();

// Cart data (can be stored in a database for a real-time application)
$cart = [];

// Helper functions
function addItemToCart($productId, $productName, $price, $quantity = 1) {
    if (!isset($cart[$productId])) {
        $cart[$productId] = [
            'name' => $productName,
            'price' => $price,
            'quantity' => $quantity
        ];
    } else {
        $cart[$productId]['quantity'] += $quantity;
    }
}

function removeItemFromCart($productId) {
    if (isset($cart[$productId])) {
        unset($cart[$productId]);
    }
}

function updateQuantity($productId, $quantity) {
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $quantity;
    }
}

function getCartTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// --- Cart Handling Functions (Called by the frontend) ---

// Add to cart (example usage:  'add_to_cart' form submission)
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['product_price'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default quantity is 1

    addItemToCart($productId, $productName, $price, $quantity);
    // Redirect to cart page or display success message
    echo '<script>alert("Product added to cart!");</script>';
}



// Remove from cart (example: 'remove_from_cart' form submission)
if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    removeItemFromCart($productId);
    echo '<script>alert("Product removed from cart!");</script>';
}

// Update quantity (example: 'update_quantity' form submission)
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];
    updateQuantity($productId, $newQuantity);
    echo '<script>alert("Quantity updated!");</script>';
}



// Display Cart (example:  'view_cart' form submission)
if (isset($_GET['action']) && $_GET['action'] === 'view_cart') {
    echo '<h2>Your Shopping Cart</h2>';

    if (empty($cart)) {
        echo '<p>Your cart is empty.</p>';
    } else {
        echo '<ul>';
        foreach ($cart as $item) {
            echo '<li>' . $item['name'] . ' - $' . number_format($item['price'], 2) . ' x ' . $item['quantity'] . ' = $' . number_format($item['price'] * $item['quantity'], 2) . '</li>';
        }
        echo '</ul>';
        echo '<p><strong>Total: $' . number_format(getCartTotal(), 2) . '</strong></p>';
    }
}
?>
