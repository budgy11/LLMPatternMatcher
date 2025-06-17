    <br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required>
    <br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required>
    <br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
      <option value="paypal">PayPal</option>
      <option value="stripe">Stripe</option>
    </select>
    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase
function processPurchase($cart, $customer_name, $customer_email, $payment_method) {
    global $conn;

    // Sanitize input (VERY IMPORTANT - prevent SQL injection)
    $customer_name = $conn->real_escape_string($customer_name);
    $customer_email = $conn->real_escape_string($customer_email);
    $payment_method = $conn->real_escape_string($payment_method);


    // Insert order into the database
    $sql_order = "INSERT INTO orders (customer_name, customer_email, payment_method) VALUES ('" . $customer_name . "', '" . $customer_email . "', '" . $payment_method . "')";

    if ($conn->query($sql_order) === TRUE) {
        $order_id = $conn->insert_id; // Get the ID of the newly inserted order

        // Insert order items into the database
        foreach ($cart as $item_id => $quantity) {
            $sql_item = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('" . $order_id . "', '" . $item_id . "', '" . $quantity . "')";

            if ($conn->query($sql_item) === TRUE) {
                echo "Order item added successfully.";
            } else {
                echo "Error adding order item: " . $conn->error;
            }
        }

        echo "<br>Order placed successfully! Order ID: " . $order_id;
    } else {
        echo "Error inserting order: " . $conn->error;
    }
}

// --------------------  Example Usage  --------------------
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get cart data (this would typically come from a session or a form)
    $cart = array(
        "product1" => 2, // Product ID 1, Quantity 2
        "product2" => 1  // Product ID 2, Quantity 1
    );

    // Get customer details from the form
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $payment_method = $_POST["payment_method"];


    // Call the processPurchase function
    processPurchase($cart, $customer_name, $customer_email, $payment_method);
}
?>
