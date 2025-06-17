    </select>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1">

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --------------------  Products Data (Simulated) --------------------
$products = array(
    array("id" => 1, "name" => "T-Shirt", "price" => 20.00),
    array("id" => 2, "name" => "Jeans", "price" => 50.00),
    array("id" => 3, "name" => "Hat", "price" => 15.00),
);

// --------------------  Purchase Functionality --------------------

function createPurchase($cart, $conn) {
    // Validate cart data
    if (empty($cart)) {
        return false; // Empty cart
    }

    // Calculate total price
    $total = 0;
    foreach ($cart as $item_id => $quantity) {
        $product = getProductById($item_id, $conn);
        if ($product) {
            $total += $product['price'] * $quantity;
        } else {
            return false; // Product not found
        }
    }

    // Generate order ID (Simple example - improve for production)
    $order_id = md5(time());

    // Insert order details into the database (Replace 'orders' with your table name)
    $sql = "INSERT INTO orders (order_id, customer_id, total_amount) VALUES ('$order_id', 1, $total)"; // Assuming customer_id 1 for now
    if ($conn->query($sql) === TRUE) {
        // Insert order items into the order_items table
        foreach ($cart as $item_id => $quantity) {
            $sql_item = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$order_id', $item_id, $quantity)";
            if ($conn->query($sql_item) === TRUE) {
                echo "Order created successfully! Order ID: " . $order_id . "<br>";
            } else {
                echo "Error inserting order item: " . $conn->error . "<br>";
                return false;
            }
        }

        return true;
    } else {
        echo "Error creating order: " . $conn->error . "<br>";
        return false;
    }
}

// --------------------  Helper Functions --------------------

// Get product by ID
function getProductById($id, $conn) {
    global $products; // Access the global $products array

    foreach ($products as $product) {
        if ($product['id'] == $id) {
            return $product;
        }
    }
    return null;
}


// --------------------  Example Usage (Simulated Form Handling) --------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart = array();
    // Process cart data (This is where you'd get data from a form or AJAX request)
    if (isset($_POST['product1_qty']) && isset($_POST['product2_qty']) && isset($_POST['product3_qty'])) {
        $cart['1'] = $_POST['product1_qty']; // Product ID 1
        $cart['2'] = $_POST['product2_qty']; // Product ID 2
        $cart['3'] = $_POST['product3_qty']; // Product ID 3
    }

    if (createPurchase($cart, $conn)) {
        echo "Purchase completed successfully!";
    } else {
        echo "Purchase failed.";
    }
}
?>
