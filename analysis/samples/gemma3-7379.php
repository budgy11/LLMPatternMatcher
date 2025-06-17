

<?php

// Database Connection (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "root";
$db_password = "";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addItemToCart($conn, $userId, $product_id, $quantity) {
    // Check if the user already has an entry for this product
    $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item already exists, update the quantity
        $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        if ($conn->query($sql) === TRUE) {
            // Success
            return true;
        } else {
            // Error
            return false;
        }
    } else {
        // Item doesn't exist, create a new entry
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        if ($conn->query($sql) === TRUE) {
            // Success
            return true;
        } else {
            // Error
            return false;
        }
    }
    $stmt->close();
}

// Function to get the cart contents
function getCartContents($conn, $userId) {
    $sql = "SELECT p.product_name, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cart_items = [];
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
        $stmt->close();
        return $cart_items;
    } else {
        $stmt->close();
        return [];
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($conn, $userId, $product_id, $quantity) {
    $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    if ($conn->query($sql, $quantity, $userId, $product_id) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($conn, $userId, $product_id) {
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    if ($conn->query($sql, $userId, $product_id) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Example usage (This is just an example, you'll need to integrate this with your website's form handling)

// 1. Get user ID (Replace with how you get the user ID, e.g., from a session)
$userId = 1; // Example user ID

// 2. Add an item to the cart
$product_id = 1;  // Example product ID
$quantity = 2;
if (addItemToCart($conn, $userId, $product_id, $quantity)) {
    echo "Item added to cart successfully!";
} else {
    echo "Error adding item to cart.";
}

// 3. Get the cart contents
$cart = getCartContents($conn, $userId);
echo "<br><h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}


// 4. Example:  Updating quantity (replace with your form handling)
// if (updateCartQuantity($conn, $userId, $product_id, $new_quantity)) {
//     echo "<br>Quantity updated successfully!";
// }

// 5. Example: Removing an item
// if (removeItemFromCart($conn, $userId, $product_id)) {
//     echo "<br>Item removed from cart.";
// }

// Close the database connection (Important!)
$conn->close();

?>
