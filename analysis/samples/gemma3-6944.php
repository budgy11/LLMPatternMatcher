    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual database details)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to check if an item is in the cart
function isInCart($userId, $itemId) {
    $conn = connectToDatabase();
    $sql = "SELECT * FROM cart WHERE user_id = '$userId' AND item_id = '$itemId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to update the cart
function updateCart($userId, $itemId, $quantity) {
    $conn = connectToDatabase();
    $sql = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '$userId' AND item_id = '$itemId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to add an item to the cart
function addToCart($userId, $itemId, $quantity) {
    if (isInCart($userId, $itemId)) {
        // Item already in cart, update quantity
        return updateCart($userId, $itemId, $quantity);
    } else {
        // Item not in cart, add it
        $sql = "INSERT INTO cart (user_id, item_id, quantity) VALUES ('$userId', '$itemId', '$quantity')";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}

// Function to remove an item from the cart
function removeFromCart($userId, $itemId) {
    $conn = connectToDatabase();
    $sql = "DELETE FROM cart WHERE user_id = '$userId' AND item_id = '$itemId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to get the cart items for a user
function getCartItems($userId) {
    $conn = connectToDatabase();
    $sql = "SELECT * FROM cart WHERE user_id = '$userId'";
    $result = $conn->query($sql);

    $items = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

// --- Purchase Functionality ---

// Check if the purchase form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user ID from session
    $userId = $_SESSION['userId'];

    // Check for purchase details (e.g., address, payment information)
    // ... Add your validation and processing logic here ...

    //  Simulate processing (replace with actual payment gateway integration)
    $cartItems = getCartItems($userId);

    if (empty($cartItems)) {
        echo "<p>Your cart is empty.  Please add items before purchasing.</p>";
    } else {
        echo "<p>Successfully placed your order!</p>";
        //  Clear the cart after purchase (optional)
        //  removeFromCart($userId,  $itemId) for all items in cart.
        //  Consider a session variable for the user to be logged in.
    }
}

// --- Display Cart Contents ---

// Get cart items for the current user
$cartItems = getCartItems($_SESSION['userId']);

?>
