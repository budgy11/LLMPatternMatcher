
</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (Important for security)
    $name = trim($_POST["name"]);
    $quantity = (int)($_POST["quantity"]);
    $price = (float)($_POST["price"]);

    // Sanitize input (Important for security)
    $name = htmlspecialchars($name);
    $quantity = (int)$quantity;
    $price = (float)$price;

    // Check if validation passes (Example - you can add more validation)
    if (empty($name) || $quantity <= 0 || $price <= 0) {
        $error = "Please fill in all fields and ensure quantity and price are positive values.";
    } else {
        // Add the item to the cart
        $cart_id = session_id(); // Use session ID as cart ID
        $sql = "INSERT INTO cart (cart_id, product_name, quantity, price) VALUES ('$cart_id', '$name', '$quantity', '$price')";

        // Execute the query
        $result = mysqli_query($db_connection, $sql);

        if ($result) {
            $success = "Item added to cart successfully!";
        } else {
            $error = "Error adding item to cart. " . mysqli_error($db_connection);
        }
    }
} else {
    $success = "";
    $error = "";
}

// Connect to the database
$db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$db_connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display the cart contents (Simplified)
function displayCartContents($db_connection) {
    //  In a real application, you'd retrieve cart items based on the session ID.
    //  This is a simplified example to demonstrate a basic cart display.
    $cart_items = array(
        array("id" => 1, "name" => "T-Shirt", "quantity" => 2, "price" => 20.00),
        array("id" => 2, "name" => "Jeans", "quantity" => 1, "price" => 50.00),
    );

    echo "<h2>Cart Contents:</h2>";
    if (empty($cart_items)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart_items as $item) {
            echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
        }
        echo "</ul>";
    }
}

?>
