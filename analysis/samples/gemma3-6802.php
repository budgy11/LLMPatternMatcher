
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

    <h1>Make a Purchase</h1>

    <form method="post" action="">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>
        <br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required>
        <br><br>

        <button type="submit">Place Order</button>
    </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'root';
$db_password = '';

// Start the session
session_start();

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false; // User not logged in
    }

    // Build the query
    $sql = "INSERT INTO cart (user_id, product_id, quantity) 
            VALUES ('" . $_SESSION['user_id'] . "', '" . $product_id . "', '" . $quantity . "')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
    $conn->close();
}

// Function to get the cart contents
function getCartContents() {
    $conn = connectToDatabase();
    $sql = "SELECT p.product_name, p.price, c.quantity 
            FROM cart c 
            JOIN products p ON c.product_id = p.product_id 
            WHERE c.user_id = '" . $_SESSION['user_id'] . "'";
    $result = $conn->query($sql);

    $cart_items = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }
    $conn->close();
    return $cart_items;
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    $conn = connectToDatabase();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false; // User not logged in
    }

    // Build the query
    $sql = "DELETE FROM cart WHERE user_id = '" . $_SESSION['user_id'] . "' AND product_id = '" . $product_id . "'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
    $conn->close();
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    $conn = connectToDatabase();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false; // User not logged in
    }

    // Build the query
    $sql = "UPDATE cart SET quantity = '" . $quantity . "' WHERE user_id = '" . $_SESSION['user_id'] . "' AND product_id = '" . $product_id . "'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
    $conn->close();
}


// Handle adding to cart (example - can be triggered from a button click)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
}


// Handle removing from cart (example - can be triggered from a button click)
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Handle updating quantity (example - can be triggered from a button click)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateQuantity($product_id, $quantity);
}



// Display the cart contents
$cart_items = getCartContents();

?>
