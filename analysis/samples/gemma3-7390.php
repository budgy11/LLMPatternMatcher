    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" required><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Configuration
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Hat', 'price' => 15.00],
];

$cart = []; // Initialize empty cart

// Function to add item to cart
function addToCart($productId, $quantity = 1, $cart) {
    if (array_key_exists($productId, $products)) {
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = ['name' => $products[$productId]['name'], 'price' => $products[$productId]['price'], 'quantity' => $quantity];
        }
    } else {
        echo "Product ID $productId not found.";
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $productId => $item) {
        echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " (Quantity: " . $item['quantity'] . ") </li>";
    }
    echo "</ul>";

    // Calculate total price
    $total = 0;
    foreach ($cart as $productId => $item) {
        $total += $item['price'] * $item['quantity'];
    }

    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}


// Handle form submission (add to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = (int)$_POST['product_id']; // Cast to integer
        $quantity = (int)$_POST['quantity'];  // Cast to integer

        addToCart($productId, $quantity, $cart); // Pass cart to addToCart
        displayCart($cart); // Display updated cart
    }
}

?>
