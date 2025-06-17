    </select>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

</body>
</html>


<?php
session_start();

// Configuration
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 1],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 1],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 2],
];

$cart = []; // Initialize an empty cart
$cart_count = 0; // Initialize cart count

// Handle Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure it's an integer

    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer

        if (isset($cart[$product_id])) {
            // Product already in cart, increase quantity
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            // Product not in cart, add it
            $cart[$product_id] = ['name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
        }
        $cart_count = count($cart);
    }
}

// Handle Remove from Cart functionality
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure it's an integer

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        $cart_count = count($cart);
    }
}


// Display Cart
echo "<h2>Shopping Cart</h2>";

if (count($cart) > 0) {
    echo "<ul>";
    foreach ($cart as $product_id => $product) {
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "  (Quantity: " . $product['quantity'] . ")</li>";
    }
    echo "</ul>";

    echo "<p>Total: $" . number_format(array_sum(array_map(function($product) { return $product['price'] * $product['quantity']; }, $cart)), 2) . "</p>";

} else {
    echo "<p>Your cart is empty.</p>";
}

// Display Products for Purchase
echo "<h2>Available Products</h2>";
echo "<ul>";
foreach ($products as $id => $product) {
    echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "<br>";
    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
    echo "<label for='product_" . $id . "'>Quantity:</label>";
    echo "<input type='number' id='product_" . $id . "' min='1' value='" . (isset($_POST['product_' . $id]) ? $_POST['product_' . $id] : 1) . "'>";
    echo "<input type='hidden' name='product_id' value='" . $id . "'>";
    echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
    echo "</form></li>";
}
echo "</ul>";
?>
