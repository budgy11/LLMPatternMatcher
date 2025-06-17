
</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addCartItem($conn, $user_id, $product_id, $quantity) {
    // Check if the user has a cart yet
    $query = "SELECT id FROM carts WHERE user_id = '$user_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User has a cart, so update the cart
        $query = "SELECT id FROM carts WHERE user_id = '$user_id'";
        $result = $conn->query($query);
        $cart_id = $result->fetch_assoc()['id'];

        $query = "SELECT id FROM cart_items WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Item already in cart, update the quantity
            $query = "UPDATE cart_items SET quantity = quantity + '$quantity' WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
        } else {
            // Item not in cart, add it
            $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', '$quantity')";
        }
    } else {
        // User doesn't have a cart, create one
        $query = "INSERT INTO carts (user_id) VALUES ('$user_id')";
        $conn->query($query);
        $query = "SELECT id FROM carts WHERE user_id = '$user_id'";
        $result = $conn->query($query);
        $cart_id = $result->fetch_assoc()['id'];
        $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', '$quantity')";
        $conn->query($query);
    }

    // Output for debugging
    echo "<p>Item '$product_id' added to cart.  Cart total: " . calculateCartTotal($conn, $user_id) . "</p>";
}

// Function to calculate the total cart value
function calculateCartTotal($conn, $user_id) {
    $query = "SELECT ci.product_id, p.price FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = (SELECT id FROM carts WHERE user_id = '$user_id')" ;
    $result = $conn->query($query);

    $total = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total += $row['price'];
        }
    }
    return $total;
}

// Function to remove an item from the cart
function removeCartItem($conn, $user_id, $product_id) {
    $query = "SELECT id FROM carts WHERE user_id = '$user_id'";
    $result = $conn->query($query);
    $cart_id = $result->fetch_assoc()['id'];

    $query = "DELETE FROM cart_items WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
    $conn->query($query);

    echo "<p>Item '$product_id' removed from cart.  Cart total: " . calculateCartTotal($conn, $user_id) . "</p>";
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($conn, $user_id, $product_id, $quantity) {
    $query = "SELECT id FROM carts WHERE user_id = '$user_id'";
    $result = $conn->query($query);
    $cart_id = $result->fetch_assoc()['id'];

    $query = "UPDATE cart_items SET quantity = '$quantity' WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
    $conn->query($query);

    echo "<p>Quantity of item '$product_id' updated.  Cart total: " . calculateCartTotal($conn, $user_id) . "</p>";
}

// --- Example Usage (Simulated User Interaction) ---

// Connect to the database
$conn = connectToDatabase();

// Simulate adding a product to the cart
// Assume $user_id is determined (e.g., from user login)
$user_id = 1;
$product_id = 'product123';
$quantity = 2;
addCartItem($conn, $user_id, $product_id, $quantity);

// Remove an item
removeCartItem($conn, $user_id, $product_id);

// Update the quantity
updateCartItemQuantity($conn, $user_id, $product_id, 5);

// Close the database connection
$conn->close();
?>
