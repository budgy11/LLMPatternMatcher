
<!DOCTYPE html>
<html>
<head>
  <title>Online Store</title>
</head>
<body>
  <h1>Welcome to Our Store</h1>

  <!--  Include the cart functionality in the HTML. -->
  <script>
    // Example JavaScript for Cart interaction
    // You would typically add these functions to your main HTML file
    // and link them to buttons.

    // This is a basic example.  For a real-world application,
    // you would need to implement more robust error handling and
    // user interface interactions.

    function addToCart(productId, productName, price) {
        // Call the PHP function (you'll need to make this AJAX call)
        //  In a real app, use AJAX to submit this data to the server.
        //  This example is simplified for demonstration.
        //  You would use a JavaScript library (e.g., Axios, Fetch)
        //  to make the AJAX request.
        console.log("addToCart called with:", productId, productName, price);
    }

    function removeFromCart(productId) {
        // Similar to addToCart, implement AJAX to call the PHP function
        console.log("removeFromCart called with:", productId);
    }

  </script>
</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "your_user";
$db_pass = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $shipping_address) {
    $conn = connectToDatabase();

    // 1. Update the cart's status to "purchased"
    $sql_update_cart = "UPDATE carts SET status = 'purchased', order_date = NOW() WHERE id = $cart_id";
    if ($conn->query($sql_update_cart) === FALSE) {
        echo "Error updating cart: " . $conn->error;
        return false;
    }

    // 2.  Create an order record
    $sql_create_order = "INSERT INTO orders (cart_id, customer_name, customer_email, shipping_address, order_date)
                        VALUES ($cart_id, '$customer_name', '$customer_email', '$shipping_address', NOW())";

    if ($conn->query($sql_create_order) === FALSE) {
        echo "Error creating order: " . $conn->error;
        return false;
    }

    // 3.  Update the items in the cart to reflect they are sold (e.g., decrease quantity or set to 0)
    //    This part is dependent on how you manage your inventory.
    //    This example sets the quantity of each item in the cart to 0.  You should adjust
    //    this based on your specific needs.
    $sql_update_cart_items = "UPDATE cart_items SET quantity = 0 WHERE cart_id = $cart_id";
    if ($conn->query($sql_update_cart_items) === FALSE) {
        echo "Error updating cart items: " . $conn->error;
        return false;
    }

    echo "Purchase successful! Order ID: " . $conn->insert_id; // Get the auto-incremented order ID
    return true;
}

// --------------------  Example Usage (Simulated form handling) --------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart_id = $_POST["cart_id"]; // Assuming cart ID is passed from a form
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $shipping_address = $_POST["shipping_address"];

    // Call the handlePurchase function
    if (handlePurchase($cart_id, $customer_name, $customer_email, $shipping_address)) {
        // Redirect to a success page or display a confirmation message
        echo "<p>Your order has been placed successfully!</p>";
    } else {
        echo "<p>An error occurred during the purchase process.</p>";
    }
}
?>
