
  <form method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <button type="submit">Add to Cart</button>
  </form>

</body>
</html>


<?php

// Configuration - Change these to suit your needs
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

$cart = []; // Array to store the items in the user's cart
$total = 0.00;


// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $cart = $cart) {
    if (isset($products[$productId]) && $products[$productId]['quantity'] > 0) {
        $cart[] = ['product_id' => $productId, 'quantity' => $quantity];
        $total += $products[$productId]['price'] * $quantity;
        // Update product quantity (optional - for more complex inventory management)
        $products[$productId]['quantity']--;
        echo "<p>Added {$quantity} x {$products[$productId]['name']} to your cart.  Total: {$total}</p>";
    } else {
        echo "<p>Sorry, {$products[$productId]['name']} is out of stock or not found.</p>";
    }
}

// Function to display the cart
function displayCart() {
    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $item) {
            $product = $products[$item['product_id']];
            echo "<li>" . $product['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $product['price'] . " - Total: $" . ($product['price'] * $item['quantity']) . "</li>";
        }
        echo "</ul>";
        echo "<p><b>Total: $" . $total . "</p>";
    }
}

// Handling form submission (adding items to the cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        addToCart($productId, $quantity);
    }
}

// ---  Example of a simple checkout process (VERY BASIC) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    echo "<h2>Checkout</h2>";
    echo "<p>Thank you for your order!  Processing...</p>";
    //  In a real application, you'd integrate with a payment gateway here.
    //  For this example, we just show a message.
}

?>
