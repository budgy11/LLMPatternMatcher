    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required>

    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" max="10" required>

    <br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" min="1" max="10" required>


    <br><br>

    <button type="submit">Place Order</button>
  </form>

  <br><br>
  <a href="index.php">Back to Home</a>  <!-- Link to a home page, replace "index.php" with your actual home page -->

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shop";
$db_user = "your_username";
$db_password = "your_password";

// --- Product Data (For demonstration - Replace with database retrieval) ---
$products = [
    1 => ["id" => 1, "name" => "T-Shirt", "price" => 20.00],
    2 => ["id" => 2, "name" => "Jeans", "price" => 50.00],
    3 => ["id" => 3, "name" => "Sneakers", "price" => 75.00],
];

// --- Session Management ---
session_start();

// --- Cart Data (Stored in session) ---
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Function to add to cart ---
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

// --- Function to update quantity in cart ---
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // Handle case where product is not in the cart.  You might want to add it here
        // or simply ignore it based on your needs.
        // For this example, we'll ignore it.
    }
}

// --- Function to remove product from cart ---
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// --- Function to get cart contents ---
function getCartContents() {
    return $_SESSION['cart'];
}

// --- Function to calculate total cart value ---
function calculateTotal() {
    $total = 0;
    foreach (getCartContents() as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            $totalPrice = $products[$product_id]['price'] * $quantity;
            $total += $totalPrice;
        }
    }
    return $total;
}

// --- Handle form submission (adding to cart) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = (int)$_POST['product_id']; // Sanitize input
        addToCart($product_id, (int)$_POST['quantity']); // Sanitize input
    }
}

// --- Display Cart Contents ---
echo "<h2>Shopping Cart</h2>";

if (count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach (getCartContents() as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            echo "<li>" . $products[$product_id]['name'] . " - $" . $products[$product_id]['price'] . " x " . $quantity . " = $" . $products[$product_id]['price'] * $quantity . "</li>";
        }
    }
    echo "</ul>";
    echo "<p>Total: $" . calculateTotal() . "</p>";

    echo "<form method='post'>";
    echo "<button type='submit' name='remove_from_cart' value='" . implode("','", array_keys($_SESSION['cart'])) . "'>";
    echo "Remove Items";
    echo "</button>";
    echo "</form>";

} else {
    echo "<p>Your cart is empty.</p>";
}

?>
