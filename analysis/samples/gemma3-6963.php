
</body>
</html>


<?php
session_start();

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Product Data (Simulated for demonstration) ---
$products = [
    1 => ["id" => 1, "name" => "Laptop", "price" => 1200],
    2 => ["id" => 2, "name" => "Mouse", "price" => 25],
    3 => ["id" => 3, "name" => "Keyboard", "price" => 75],
];


// --- Function to handle the purchase process ---
function handlePurchase($cart) {
    global $conn, $products;

    // Calculate total price
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            $product = $products[$product_id];
            $total += $product['price'] * $quantity;
        } else {
            // Handle invalid product ID
            return "Invalid product ID: " . $product_id;
        }
    }

    // Insert order into the database
    $order_date = date("Y-m-d H:i:s");
    $customer_name = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : "Guest";
    $order_query = "INSERT INTO orders (customer_name, order_date, total_amount) VALUES ('" . mysqli_real_escape_string($conn, $customer_name) . "', '" . $order_date . "', '" . $total . "')";

    if ($conn->query($order_query) === TRUE) {
        $order_id = $conn->insert_id;

        // Insert order items into the database
        foreach ($cart as $product_id => $quantity) {
            $item_query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (" . $order_id . ", " . $product_id . ", " . $quantity . ")";
            if ($conn->query($item_query) === TRUE) {
                //echo "Inserted item: " . $product_id . " - " . $quantity . " into order " . $order_id . "<br>";
            } else {
                echo "Error inserting order item: " . $conn->error . "<br>";
            }
        }

        return "Order placed successfully! Order ID: " . $order_id;

    } else {
        echo "Error placing order: " . $conn->error . "<br>";
        return "Order placement failed.";
    }
}


// --- Handle Form Submission (for the shopping cart) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_cart'])) {
        $cart = [];
        foreach ($_POST['product_ids'] as $product_id => $quantity) {
            if (isset($products[$product_id])) {
                $cart[$product_id] = $quantity;
            }
        }

        $result = handlePurchase($cart);
        echo $result;
    }
}



// --- Display Shopping Cart Form ---
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Shopping Cart</title>";
echo "</head>";
echo "<body>";

echo "<h1>Shopping Cart</h1>";

echo "<form method='post' action=''>";
echo "<h2>Select Products</h2>";

foreach ($products as $product_id => $product) {
    echo "<label for='" . $product['id'] . "'>" . $product['name'] . " - $" . $product['price'] . "</label><br>";
    echo "<input type='number' id='" . $product['id'] . "' name='product_ids[" . $product['id'] . "]' value='1' min='1'><br>";
}

echo "<br>";
echo "<input type='submit' name='submit_cart' value='Place Order'>";
echo "</form>";

echo "</body>";
echo "</html>";


?>
