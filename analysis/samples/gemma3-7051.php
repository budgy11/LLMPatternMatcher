    </form>

</body>
</html>


<?php
session_start(); // Start the session for user management

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function processPurchase($cart, $customer_name, $customer_email, $payment_method) {
    $conn = connectToDatabase();

    // 1.  Validate Cart Data (Important Security Step) - Example
    //  (In a real application, you'd likely have more robust validation)
    foreach ($cart as $product_id => $quantity) {
        // Check if the product ID exists in your database
        $sql = "SELECT id, product_name, price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id); // "i" for integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Product exists, continue
        } else {
            // Product does not exist - Handle this error appropriately
            die("Invalid product ID in cart.");
        }
    }

    // 2.  Create Order Record
    $order_date = date('Y-m-d H:i:s');
    $total_amount = 0;

    $sql = "INSERT INTO orders (customer_name, customer_email, order_date, total_amount) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssid", $customer_name, $customer_email, $order_date, $total_amount);
    $stmt->execute();
    $order_id = $conn->insert_id;  // Get the ID of the newly inserted order

    // 3.  Create Order Items (One record per product in the order)
    foreach ($cart as $product_id => $quantity) {
        $product_name = "";
        $product_price = "";

        // Retrieve product details
        $sql = "SELECT product_name, price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $product_name = $row['product_name'];
            $product_price = $row['price'];
        }

        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isd", $order_id, $product_id, $quantity, $product_price);
        $stmt->execute();
    }

    // 4.  Update Order Total (Optional, but good practice)
    $sql = "UPDATE orders SET total_amount = (SELECT SUM(price * quantity) FROM order_items WHERE order_id = ?) WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $order_id, $order_id);
    $stmt->execute();

    // 5.  Clear Cart (Reset the shopping cart)
    $_SESSION['cart'] = [];

    // 6.  Success Message
    echo "<p style='color: green;'>Purchase successful! Order ID: " . $order_id . "</p>";
}


// --- Example Usage (Handles form submission) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get cart data from the session
    $cart = $_SESSION['cart'];

    // Get customer information from the form
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $payment_method = $_POST['payment_method'];

    // Process the purchase
    processPurchase($cart, $customer_name, $customer_email, $payment_method);
}
?>
