    <!-- Cart items -  Simulated example.  Replace with your actual product data. -->
    <label for="product1">Product 1 (ID: 1):</label>
    <input type="number" id="product1" name="cart[1]" value="2"> <br>

    <label for="product2">Product 2 (ID: 2):</label>
    <input type="number" id="product2" name="cart[2]" value="1"> <br>

    <input type="hidden" name="cart" value='["1", "2"]'> <!-- Simulate cart data -->
    <input type="submit" value="Purchase">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//  --- Product Data (For demonstration purposes - replace with a database table) ---
$products = [
    1 => ["id" => 1, "name" => "Laptop", "price" => 1200, "quantity" => 10],
    2 => ["id" => 2, "name" => "Mouse", "price" => 25, "quantity" => 50],
    3 => ["id" => 3, "name" => "Keyboard", "price" => 75, "quantity" => 30],
];


// Function to handle adding to cart
function addToCart($productId, $conn, $cart) {
    // Check if the product exists
    if (!isset($products[$productId])) {
        return false;
    }

    // Check if the product is already in the cart
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += 1;
    } else {
        $cart[$productId] = ["quantity" => 1, "product_id" => $productId];
    }
    return true;
}



// Function to display the shopping cart
function displayCart($conn, $cart) {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    $total = 0;

    foreach ($cart as $productId => $item) {
        $product = $products[$productId];
        echo "<li>";
        echo "<strong>" . $product['name'] . "</strong> - $" . $product['price'] . " (Quantity: " . $item['quantity'] . ")";
        $itemTotal = $product['price'] * $item['quantity'];
        $total += $itemTotal;
        echo "</li>";
    }

    echo "</ul>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// --- Handling Form Submission (Adding to Cart) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = (int)$_POST['product_id'];  //Ensure product_id is an integer.
        addToCart($productId, $conn, $cart);
    }
}

// --- Initialize Cart (Session-based - recommended for persistence) ---
session_start();

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// --- Display the cart ---
displayCart($conn, $_SESSION["cart"]);

// --- Display Product List (For Reference) ---
echo "<h2>Available Products</h2>";
echo "<ul>";
foreach ($products as $id => $product) {
    echo "<li>";
    echo "<strong>" . $product['name'] . "</strong> - $" . $product['price'] . " (Quantity: " . $product['quantity'] . ")";
    echo "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'><input type='hidden' name='product_id' value='" . $id . "'>";
    echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
    echo "</form>";
    echo "</li>";
}
echo "</ul>";

?>
