    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="item_id">Item ID:</label>
    <input type="number" id="item_id" name="item_id" min="1" required><br><br>

    <button type="submit">Place Order</button>
  </form>

  <a href="index.php">Back to Home</a> <!-- Link back to the home page (or wherever you want to redirect) -->

</body>
</html>


<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

//  Product Data (Simulated for demonstration)
$products = [
    1 => ["id" => 1, "name" => "T-Shirt", "price" => 20],
    2 => ["id" => 2, "name" => "Jeans", "price" => 50],
    3 => ["id" => 3, "name" => "Hat", "price" => 15],
];


// Function to handle adding to cart
function addToCart($product_id, $cart) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $cart[] = $product;
        return $cart;
    } else {
        return $cart;  // Product not found, return cart unchanged
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
        }
        echo "</ul>";
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'];
        }
        echo "<p><strong>Total: $" . $total . "</strong></p>";
    }
}

// Handle form submission (add to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["product_id"]) && isset($_POST["quantity"])) {
        $product_id = (int)$_POST["product_id"]; // Cast to integer
        $quantity = (int)$_POST["quantity"];  // Cast to integer

        // Validate the quantity (optional but recommended)
        if ($quantity <= 0) {
            echo "<p style='color:red;'>Invalid quantity. Please enter a positive number.</p>";
        } else {
            // Add to cart
            $cart = $_SESSION['cart'] ?? [];  // Use session for persistence
            $cart = addToCart($product_id, $cart);
            $_SESSION['cart'] = $cart; // Store in session

            echo "<p style='color:green;'>Product added to cart!</p>";
        }

    }
}


// Display Cart (if cart exists in session)
if (isset($_SESSION['cart'])) {
    displayCart($_SESSION['cart']);
}
?>
