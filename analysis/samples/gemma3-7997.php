

<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    $conn = connectToDatabase();

    // Validate input (add more robust validation as needed)
    if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($payment_method)) {
        return "Error: All fields are required.";
    }

    // Check if the cart exists
    $result = $conn->query("SELECT * FROM carts WHERE id = '$cart_id'");
    if ($result->num_rows > 0) {
        $cart = $result->fetch_assoc();

        // Insert order details into the orders table
        $order_id = $conn->insert("orders", [
            "cart_id" => $cart_id,
            "customer_name" => $customer_name,
            "customer_email" => $customer_email,
            "payment_method" => $payment_method,
            "order_date" => date("Y-m-d H:i:s")
        ]);

        // Update the cart (e.g., mark as sold or delete)
        $conn->query("UPDATE carts SET sold = 1 WHERE id = '$cart_id'");

        // Optionally, you can send an email confirmation here

        return "Purchase successful! Order ID: " . $conn->insert_id;
    } else {
        return "Error: Cart not found.";
    }
}

// --- Example usage (assuming you have a form to submit data) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart_id = $_POST["cart_id"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $payment_method = $_POST["payment_method"];

    $result = handlePurchase($cart_id, $customer_name, $customer_email, $payment_method);

    echo $result;
} else {
    // Display form (for initial setup)
    echo "<form method='post'>";
    echo "Cart ID: <input type='text' name='cart_id' value='" . $_POST["cart_id"] . "'><br>";
    echo "Customer Name: <input type='text' name='customer_name' value='" . $_POST["customer_name"] . "'><br>";
    echo "Customer Email: <input type='email' name='customer_email' value='" . $_POST["customer_email"] . "'><br>";
    echo "Payment Method: <input type='text' name='payment_method' value='" . $_POST["payment_method"] . "'><br>";
    echo "<input type='submit' value='Place Order'>";
    echo "</form>";
}

?>
