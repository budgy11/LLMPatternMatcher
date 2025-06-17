

<?php
session_start();

// Database connection details
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a product to the cart
function addToCart($product_id, $quantity, $conn) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $sql = "INSERT INTO cart (user_id, product_id, quantity) 
            VALUES ($user_id, $product_id, $quantity)";

    if ($conn->query($sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}

// Function to get the cart items
function getCartItems($user_id, $conn) {
    $sql = "SELECT p.product_name, p.price, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = $user_id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $cart_items = array();
        while($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
        return $cart_items;
    } else {
        return array();
    }
}

// Function to update the cart item quantity
function updateCartItemQuantity($product_id, $quantity, $conn) {
    $user_id = $_SESSION['user_id'];

    // Check if the item exists in the cart
    $sql = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update the quantity
        $sql = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE; // Item not found in cart
    }
}

// Function to remove a cart item
function removeCartItem($product_id, $conn) {
    $user_id = $_SESSION['user_id'];
    $sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    if ($conn->query($sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}

// --- Purchase Functionality (Simplified for demonstration) ---

// Example Purchase Request (triggered by a form submission)
if (isset($_POST['purchase'])) {
    // 1. Get Cart Items
    $conn = connectToDatabase();
    $cart_items = getCartItems($_SESSION['user_id'], $conn);

    // 2.  Calculate Total Price (Simplified)
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['price'];
    }

    // 3.  Update Cart (Empty it after purchase) - Can be removed if desired
    foreach ($cart_items as $item) {
        // Remove the item from the cart
        removeCartItem($item['product_id'], $conn);
    }


    // 4.  Display Purchase Confirmation (Replace with actual payment processing)
    echo "<p>Purchase completed successfully!  Total: $" . $total_price . "</p>";
    // In a real application, you would integrate with a payment gateway here.
}

// --- Example HTML Form (for adding items to cart) ---

echo "<form method='post' action=''>";
echo "<label for='product_id'>Product ID:</label><br>";
echo "<input type='number' id='product_id' name='product_id' required><br><br>";

echo "<label for='quantity'>Quantity:</label><br>";
echo "<input type='number' id='quantity' name='quantity' value='1' min='1'><br><br>";

echo "<input type='submit' value='Add to Cart'>";
echo "</form>";

?>
