

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "your_username";
$db_password = "your_password";

// Session management (for user login) - Simplified for this example
session_start();

// Check if the user is logged in (optional, for security)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}


// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false; // Or handle the error differently
    }

    //  Implement authentication/authorization here if needed, for example, check user cart
    //  If the user is not logged in, redirect them to login.php

    //Get user's ID from session
    $user_id = $_SESSION['user_id'];

    // Build the SQL query
    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Function to get the cart items for a user
function getCartItems($conn, $user_id) {
    $query = "SELECT p.product_name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = '$user_id'";
    $result = $conn->query($query);

    $cart_items = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }
    return $cart_items;
}


// --- Handling the Purchase Request ---

// Check if the purchase form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the cart data (IMPORTANT for security) - VERY BASIC EXAMPLE
    if (empty($_POST['cart_items'])) {
        echo "Error: Cart is empty.  Please add items to your cart.";
        exit;
    }

    // Get the cart items
    $cart_items = json_decode($_POST['cart_items'], true); // Decode JSON data

    if ($cart_items) {
        // Process the purchase
        $conn = connectToDatabase();

        foreach ($cart_items as $item) {
            // Add the item to the cart (this assumes the item is already in the cart)
            //  This is where you would handle adding it if it's not.

            addToCart($conn, $item['product_id'], $item['quantity']);
        }

        $conn->close();

        // Display success message
        echo "<p>Order placed successfully! Thank you for your purchase.</p>";
        //You would also update order statuses in your order table here.
    } else {
        echo "Error: Invalid cart data.";
    }
}


// --- Displaying the Cart (for demonstration) ---
$conn = connectToDatabase();
$cart_items = getCartItems($conn, $_SESSION['user_id']); // Get cart items for the logged-in user

$conn->close();

?>
