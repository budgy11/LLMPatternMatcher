    <input type="submit" name="submit_purchase" value="Complete Purchase">
  </form>

</body>
</html>


<?php

// Database connection details
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Session handling
session_start();

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart, $customerName, $customerEmail, $paymentAmount) {
    $conn = connectToDatabase();

    // Insert order details into the database
    $sql = "INSERT INTO orders (customer_name, customer_email, order_total, order_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $customerName, $customerEmail, $paymentAmount);
    $stmt->execute();
    $orderId = $conn->insert_id; // Get the ID of the newly inserted order

    // Insert order items into the order_items table
    foreach ($cart as $item) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $orderId, $item['product_id'], $item['quantity']);
        $stmt->execute();
    }

    // Clear the cart (optional, depending on your requirements)
    $_SESSION['cart'] = [];  // You can store this in a file instead if needed

    return $orderId;
}


// Check if the purchase form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get cart data from the session
    $cart = $_SESSION['cart'];

    // Get customer details from the form
    $customerName = $_POST["customer_name"];
    $customerEmail = $_POST["customer_email"];
    $paymentAmount = $_POST["payment_amount"]; // Price of the order

    // Handle the purchase
    $orderId = handlePurchase($cart, $customerName, $customerEmail, $paymentAmount);

    // Display a success message
    echo "<p>Order placed successfully! Order ID: " . $orderId . "</p>";
}

// ---  Example Form (HTML)  ---
?>
