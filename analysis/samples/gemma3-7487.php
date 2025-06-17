    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" step="0.01" min="0.01" value="10.00"><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
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

// Function to add an item to the cart
function addToCart($conn, $item_id, $quantity) {
    // Assuming you have a "cart" table with columns: user_id, item_id, quantity
    $user_id = $_SESSION['user_id'];  // Assuming user ID is stored in session
    $sql = "INSERT INTO cart (user_id, item_id, quantity) VALUES ('$user_id', '$item_id', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to update quantity in cart
function updateCartQuantity($conn, $item_id, $quantity) {
    // Assuming you have a "cart" table with columns: user_id, item_id, quantity
    $user_id = $_SESSION['user_id'];

    // Check if item already exists in cart
    $sql_check = "SELECT quantity FROM cart WHERE user_id = '$user_id' AND item_id = '$item_id'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Update the quantity
        $sql_update = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND item_id = '$item_id'";
        if ($conn->query($sql_update) === TRUE) {
            return true;
        } else {
            return false;
        }
    } else {
        // Item doesn't exist, so insert a new row
        $sql_insert = "INSERT INTO cart (user_id, item_id, quantity) VALUES ('$user_id', '$item_id', '$quantity')";
        if ($conn->query($sql_insert) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}

// Function to remove an item from the cart
function removeFromCart($conn, $item_id) {
    // Assuming you have a "cart" table with columns: user_id, item_id, quantity
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM cart WHERE user_id = '$user_id' AND item_id = '$item_id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to handle the purchase (e.g., place the order)
function placeOrder($conn) {
    // In a real application, you'd likely save the cart data to an 'orders' table
    // This is a simplified example.
    // You'd also need to clear the cart after placing the order.

    // Example: Save cart data to orders table
    // $sql_save_order = "INSERT INTO orders (user_id, order_date) VALUES ('$user_id', NOW())";
    // if ($conn->query($sql_save_order) === TRUE) {
    //     // Clear the cart
    //     // $sql_clear_cart = "DELETE FROM cart WHERE user_id = '$user_id'";
    //     // $conn->query($sql_clear_cart);
    //     return true;
    // } else {
    //     return false;
    // }

    // Simplified example: just returning true to indicate order placement
    return true;
}


// Main execution (Example -  replace with your actual logic)

// 1. Connect to the database
$conn = connectToDatabase();

// 2.  Simulate user login (replace with your actual authentication)
// Assume user is logged in and their ID is stored in session
$_SESSION['user_id'] = 'user123'; // Example user ID

// 3.  Handle Purchase Request
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    if (addToCart($conn, $item_id, $quantity)) {
        echo "Item added to cart!";
    } else {
        echo "Failed to add item to cart.";
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'update_cart') {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    if (updateCartQuantity($conn, $item_id, $quantity)) {
        echo "Cart quantity updated successfully!";
    } else {
        echo "Failed to update cart quantity.";
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $item_id = $_POST['item_id'];
    if (removeFromCart($conn, $item_id)) {
        echo "Item removed from cart!";
    } else {
        echo "Failed to remove item from cart.";
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'place_order') {
    placeOrder($conn);
    echo "Order placed successfully!";
}


// Close the database connection (important!)
$conn->close();

?>
