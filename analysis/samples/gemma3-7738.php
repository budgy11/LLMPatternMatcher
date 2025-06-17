    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="product_price">Product Price:</label>
    <input type="number" id="product_price" name="product_price" step="0.01" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" required><br><br>

    <button type="submit">Purchase</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
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

// --- Functions ---

/**
 * Add a new order to the database.
 *
 * @param array $orderData An associative array containing order details.
 * @return int|false The ID of the new order if successful, or false on failure.
 */
function addOrder(array $orderData) {
    global $conn;

    $order_id = $conn->insert_id; // Get the auto-incremented ID

    $sql = "INSERT INTO orders (customer_name, product_name, quantity, price, order_date)
           VALUES ('" . $conn->real_escape_string($orderData['customer_name']) . "',
                  '" . $conn->real_escape_string($orderData['product_name']) . "',
                  " . $conn->real_escape_string($orderData['quantity']) . ",
                  " . $conn->real_escape_string($orderData['price']) . ",
                  CURDATE())";

    if ($conn->query($sql) === TRUE) {
        return $conn->insert_id;
    } else {
        error_log("Error adding order: " . $conn->error);
        return false;
    }
}


/**
 * Display a simple purchase form.
 */
function displayPurchaseForm() {
    echo '<form action="process_purchase.php" method="POST">';
    echo 'Customer Name: <input type="text" name="customer_name" required>';
    echo '<br>';
    echo 'Product Name: <input type="text" name="product_name" required>';
    echo '<br>';
    echo 'Quantity: <input type="number" name="quantity" required>';
    echo '<br>';
    echo 'Price: <input type="number" name="price" required>';
    echo '<br>';
    echo '<input type="submit" value="Place Order">';
    echo '</form>';
}


/**
 * Display the order details (for confirmation).
 *
 * @param int $orderId The ID of the order to display.
 */
function displayOrderDetails(int $orderId) {
    echo '<br><h2>Order Details:</h2>';
    echo '<p>Order ID: ' . $orderId . '</p>';

    //This is a placeholder - replace with your database query to retrieve order details.
    // In a real application, you would fetch the details from the database.
    // Example:
    // $sql = "SELECT * FROM orders WHERE id = " . $orderId;
    // $result = $conn->query($sql);

    // if ($result->num_rows > 0) {
    //    $order = $result->fetch_assoc();
    //    echo '<p>Customer Name: ' . $order['customer_name'] . '</p>';
    //    echo '<p>Product Name: ' . $order['product_name'] . '</p>';
    //    echo '<p>Quantity: ' . $order['quantity'] . '</p>';
    //    echo '<p>Price: ' . $order['price'] . '</p>';
    // } else {
    //    echo '<p>Order not found.</p>';
    // }
}

// --- Main Execution ---

//Display the purchase form
displayPurchaseForm();

// If a form submission occurred (check the $_POST array)
if (isset($_POST['submit_order'])) { // Check if the submit_order is set, meaning the form was submitted
    $orderData = [
        'customer_name' => $_POST['customer_name'],
        'product_name' => $_POST['product_name'],
        'quantity' => $_POST['quantity'],
        'price' => $_POST['price']
    ];

    // Add the order to the database
    $orderId = addOrder($orderData);

    if ($orderId) {
        echo '<br><h2>Order Placed Successfully!</h2>';
        displayOrderDetails($orderId);
    } else {
        echo '<br><h2>Error Placing Order. Please try again.</h2>';
    }
}

?>
