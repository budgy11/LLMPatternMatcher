    <?php endif; ?>

    <hr>

    <h2>Add to Cart</h2>
    <ul>
        <li><form method="post" action="">
            <input type="hidden" name="action" value="addToCart">
            <input type="hidden" name="productId" value="1">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit">Add Product 1 to Cart</button>
        </form></li>

        <li><form method="post" action="">
            <input type="hidden" name="action" value="addToCart">
            <input type="hidden" name="productId" value="2">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit">Add Product 2 to Cart</button>
        </form></li>
    </ul>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Database connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Cart Data
$cart = array();


// Function to add an item to the cart
function add_to_cart($conn, $product_id, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            // Update the quantity
            $cart[$key]['quantity'] += $quantity;
            return;
        }
    }

    // If not in the cart, add it
    $cart[] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($conn, $product_id) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }
}


// Function to update the quantity of an item in the cart
function update_cart_quantity($conn, $product_id, $quantity) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $cart[$key]['quantity'] = $quantity;
            return;
        }
    }
}


// ---  Handling Requests (GET/POST) ---

// 1. Add to Cart (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    add_to_cart($conn, $product_id, $quantity);
}


// 2. Remove from Cart (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($conn, $product_id);
}


// 3. Update Quantity (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_cart_quantity($conn, $product_id, $new_quantity);
}


// --- Display Cart Contents ---
echo "<h2>Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $key => $item) {
        $product_id = $item['product_id'];

        // Fetch product details from the database (replace with your query)
        $product_query = "SELECT * FROM products WHERE id = $product_id";
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            echo "<li>";
            echo "Product: " . $product['name'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "Price: $" . $product['price'] . "<br>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
            echo "<input type='hidden' name='quantity' value='" . $item['quantity'] . "'>";
            echo "<input type='submit' name='update_quantity' value='Update Quantity'>&nbsp; ";
            echo "<input type='submit' name='remove_from_cart' value='Remove from Cart'> ";
            echo "</form>";
            echo "</li>";
        } else {
            echo "<li>Product ID: " . $product_id . " not found.</li>";
        }
    }
    echo "</ul>";
}


// Close the database connection
$conn->close();
?>
