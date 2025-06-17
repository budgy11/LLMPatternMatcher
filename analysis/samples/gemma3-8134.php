

<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'root';
$db_password = '';

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    $conn = connectDB();

    if (!$conn) {
        return false;
    }

    // Check if the product exists
    $product_query = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product_name = $row['name'];
        $product_price = $row['price'];

        // Get the cart or create a new one
        $cart_key = 'cart';
        if (!isset($_SESSION[$cart_key])) {
            $_SESSION[$cart_key] = [];
        }

        // Check if the product is already in the cart
        if (isset($_SESSION[$cart_key][$product_id])) {
            // Increment quantity
            $_SESSION[$cart_key][$product_id]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $_SESSION[$cart_key][$product_id] = [
                'quantity' => $quantity,
                'name' => $product_name,
                'price' => $product_price
            ];
        }
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false; // Product not found
    }
}


// Function to get the cart contents
function getCartContents() {
    $cart_key = 'cart';
    if (isset($_SESSION[$cart_key])) {
        return $_SESSION[$cart_key];
    } else {
        return [];
    }
}


// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    $cart_key = 'cart';

    if (isset($_SESSION[$cart_key][$product_id])) {
        $_SESSION[$cart_key][$product_id]['quantity'] = $quantity;
        return true;
    } else {
        return false; // Product not in cart
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    $cart_key = 'cart';

    if (isset($_SESSION[$cart_key][$product_id])) {
        unset($_SESSION[$cart_key][$product_id]);
        return true;
    } else {
        return false; // Product not in cart
    }
}

// Function to clear the entire cart
function clearCart() {
    unset($_SESSION['cart']);
}

// Example Usage (Handle Add to Cart Button)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (addToCart($product_id, $quantity)) {
        echo "<p>Item added to cart!</p>";
    } else {
        echo "<p>Failed to add item to cart.</p>";
    }
}


// Example Usage (Handle Remove from Cart Button)
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    if (removeFromCart($product_id)) {
        echo "<p>Item removed from cart!</p>";
    } else {
        echo "<p>Failed to remove item from cart.</p>";
    }
}

// Example Usage (Handle Update Quantity Button)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    if (updateCartItemQuantity($product_id, $new_quantity)) {
        echo "<p>Quantity updated!</p>";
    } else {
        echo "<p>Failed to update quantity.</p>";
    }
}

// Example Usage (Handle Clear Cart Button)
if (isset($_POST['clear_cart'])) {
    clearCart();
    echo "<p>Cart cleared!</p>";
}


// Display the Cart Contents
$cart_contents = getCartContents();

// HTML for the Cart Display
?>
