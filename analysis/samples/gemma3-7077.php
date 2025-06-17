    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email"><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" value="10.00"><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_user';
$db_pass = 'your_password';
$db_name = 'your_database';

// Initialize session for user tracking
session_start();

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}


// Function to handle the purchase process
function handlePurchase($cart_id, $user_id) {
    $conn = connectToDatabase();

    // Check if the cart exists
    $sql_cart = "SELECT * FROM carts WHERE id = ?";
    $stmt_cart = $conn->prepare($sql_cart);
    $stmt_cart->bind_param("i", $cart_id);
    $stmt_cart->execute();
    $result_cart = $stmt_cart->get_result();

    if ($result_cart->num_rows == 0) {
        return false; // Cart not found
    }

    $cart_data = $result_cart->fetch_assoc();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false; // User not logged in
    }

    // Get user ID from session
    $user_id_session = $_SESSION['user_id'];

    // Check if the user owns the cart
    $sql_user_cart = "SELECT * FROM carts WHERE id = ? AND user_id = ?";
    $stmt_user_cart = $conn->prepare($sql_user_cart);
    $stmt_user_cart->bind_param("is", $cart_id, $user_id_session);
    $stmt_user_cart->execute();
    $result_user_cart = $stmt_user_cart->get_result();

    if ($result_user_cart->num_rows == 0) {
        return false; // User doesn't own this cart
    }

    // Update cart status to 'completed'
    $sql_update_cart = "UPDATE carts SET status = 'completed', completed_at = NOW() WHERE id = ?";
    $stmt_update_cart = $conn->prepare($sql_update_cart);
    $stmt_update_cart->bind_param("i", $cart_id);
    $stmt_update_cart->execute();

    // Get products from the cart
    $sql_get_products = "SELECT * FROM cart_items WHERE cart_id = ?";
    $stmt_get_products = $conn->prepare($sql_get_products);
    $stmt_get_products->bind_param("i", $cart_id);
    $stmt_get_products->execute();
    $result_products = $stmt_get_products->get_result();

    if ($result_products->num_rows > 0) {
        while ($product_data = $result_products->fetch_assoc()) {
            // Perform order processing here.  This is where you'd
            // integrate with payment gateways, update inventory, etc.

            // Example: Log the order details
            echo "Order details: Product ID = " . $product_data['product_id'] . ", Quantity = " . $product_data['quantity'] . "<br>";

            // You would likely want to add this to a log table.
        }
    }

    return true; // Purchase successful
}

// --------------------  Handle the Purchase Request  --------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the cart ID from the form
    $cart_id = $_POST["cart_id"];

    // Validate cart_id (Important for security - prevent SQL injection)
    if (!is_numeric($cart_id)) {
        $error = "Invalid cart ID.";
    } else {
        // Call the handlePurchase function
        if (handlePurchase($cart_id, $_SESSION['user_id'])) {
            $message = "Purchase successful! Your cart has been completed.";
        } else {
            $message = "Purchase failed.";
        }
    }
}

?>
