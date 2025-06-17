        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// Database Connection (Replace with your database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Product Data (Simulated for this example) ---
$products = [
    [
        'id' => 1,
        'name' => 'T-Shirt',
        'price' => 20.00,
        'stock' => 10
    ],
    [
        'id' => 2,
        'name' => 'Jeans',
        'price' => 50.00,
        'stock' => 5
    ],
    [
        'id' => 3,
        'name' => 'Sneakers',
        'price' => 80.00,
        'stock' => 3
    ]
];

// --- Shopping Cart Data (In-memory for demonstration) ---
$cart = [];

// --- Form Handling (Simulated - Replace with your form handling logic) ---

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $product = null;
    foreach ($products as $p) {
        if ($p['id'] == $product_id) {
            $product = $p;
            break;
        }
    }

    if ($product) {
        if ($quantity > 0) {
            // Add to cart
            $cart[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];

            echo "<p style='color:green;'>Added " . $quantity . " " . $product['name'] . " to your cart.</p>";
        } else {
            echo "<p style='color:red;'>Please enter a valid quantity.</p>";
        }
    } else {
        echo "<p style='color:red;'>Product not found.</p>";
    }
}


// 2. Display Cart (If the cart is not empty)
if (!empty($cart)) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate total price
    $total_price = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart));
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}
else {
    echo "<p>Your cart is empty.</p>";
}



// --- Form for Adding to Cart (Simulated) ---
?>
