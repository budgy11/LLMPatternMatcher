    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>

    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required>

    <br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" placeholder="Enter customer name" required>

    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Sample Data (Replace with your actual database or data source)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

$cart = [];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $cart) {
    if (array_key_exists($productId, $products)) {
        $product = $products[$productId];
        $cart[] = ['id' => $productId, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
        return true;
    } else {
        return false;
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        $productId = $item['id'];
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        echo "<li>" . $name . " - $" . number_format($price, 2) . " (Quantity: " . $quantity . ")</li>";
    }
    echo "</ul>";
}

// Function to calculate the total cart value
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle Purchase Request (Simulated)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = (int)$_POST['product_id']; // Ensure it's an integer
        $quantity = (int)$_POST['quantity'];

        if (addToCart($productId, $quantity, $cart)) {
            echo "<p>Item added to cart successfully!</p>";
            displayCart($cart);
        } else {
            echo "<p>Error adding item to cart.</p>";
        }
    }
}
?>
