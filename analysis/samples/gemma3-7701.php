

<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Initialize session for user information
session_start();

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @return bool True on success, false on failure.
 */
function addToCart(int $product_id) {
    global $db; // Access the database connection

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false; // User not logged in, cannot add to cart.
    }

    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Check if the product is already in the cart
        $queryCart = "SELECT * FROM cart WHERE product_id = ? AND user_id = ?";
        $stmtCart = $db->prepare($queryCart);
        $stmtCart->bind_param("is", $product_id, $_SESSION['user_id']);
        $stmtCart->execute();
        $resultCart = $stmtCart->get_result();

        if ($resultCart->num_rows > 0) {
            // Product already in cart, update quantity
            $queryUpdate = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = ? AND user_id = ?";
            $stmtUpdate = $db->prepare($queryUpdate);
            $stmtUpdate->bind_param("is", $product_id, $_SESSION['user_id']);
            $stmtUpdate->execute();
        } else {
            // Product not in cart, add it
            $queryInsert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
            $stmtInsert = $db->prepare($queryInsert);
            $stmtInsert->bind_param("is", $_SESSION['user_id'], $product_id);
            $stmtInsert->execute();
        }
        return true;
    } else {
        return false; // Product not found
    }
}

/**
 * Retrieves the shopping cart contents for a specific user.
 *
 * @param int $user_id The ID of the user.
 * @return array An array of product details from the cart, or an empty array if none.
 */
function getCartContents(int $user_id) {
    global $db;

    $query = "SELECT p.id AS product_id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ? ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = array();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    return $cart_items;
}

/**
 * Removes a product from the shopping cart.
 * @param int $product_id
 * @return bool
 */
function removeFromCart(int $product_id) {
    global $db;

    $query = "SELECT * FROM cart WHERE product_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $product_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $queryDelete = "DELETE FROM cart WHERE product_id = ? AND user_id = ?";
        $stmtDelete = $db->prepare($queryDelete);
        $stmtDelete->bind_param("is", $product_id, $_SESSION['user_id']);
        $stmtDelete->execute();
        return true;
    } else {
        return false;
    }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return bool True on success, false on failure.
 */
function updateCartQuantity(int $product_id, int $quantity) {
    global $db;

    if ($quantity <= 0) {
        return false; // Invalid quantity
    }

    $query = "SELECT * FROM cart WHERE product_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $product_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $queryUpdate = "UPDATE cart SET quantity = ? WHERE product_id = ? AND user_id = ?";
        $stmtUpdate = $db->prepare($queryUpdate);
        $stmtUpdate->bind_param("is", $quantity, $product_id, $_SESSION['user_id']);
        $stmtUpdate->execute();
        return true;
    } else {
        return false; // Product not in cart
    }
}

// Example usage (for testing)
// Initialize the database connection
$db = connectToDatabase();

// Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    if (addToCart($product_id)) {
        echo "Product added to cart.<br>";
    } else {
        echo "Failed to add product to cart.<br>";
    }
}

// Retrieve the cart contents
$cart_contents = getCartContents($_SESSION['user_id']);

// Output the cart contents (for demonstration purposes)
echo "<h2>Shopping Cart Contents:</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
}

//  Example of updating the quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $new_quantity = (int)$_POST['quantity'];

    if (updateCartQuantity($product_id, $new_quantity)) {
        echo "Quantity updated successfully.<br>";
    } else {
        echo "Failed to update quantity.<br>";
    }
}

// Example of removing a product
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    if (removeFromCart($product_id)) {
        echo "Product removed from cart.<br>";
    } else {
        echo "Failed to remove product from cart.<br>";
    }
}

?>
