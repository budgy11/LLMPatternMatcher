

<?php
session_start();

// Database connection details (Replace with your actual details)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Function to connect to the database
function connectDB($host, $user, $pass, $name) {
    $conn = new mysqli($host, $user, $pass, $name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to create a new cart
function createCart($conn) {
  // Check if cart exists for the user. If not, create it.
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Get user ID from session
  if ($user_id) {
    // Check if cart exists for this user.  If not, create it.
    $sql = "SELECT id FROM carts WHERE user_id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Cart exists, do nothing (it's already there)
    } else {
        $sql = "INSERT INTO carts (user_id) VALUES ('$user_id')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['cart_id'] = $conn->insert_id; // Get the newly created cart ID
            echo "<p>New cart created for you!</p>";
        } else {
            echo "<p>Error creating cart: " . $conn->error . "</p>";
        }
    }
} else {
    // No user logged in, so create a default cart
    $sql = "INSERT INTO carts (user_id) VALUES (NULL)"; // No user ID, create default cart
    if ($conn->query($sql) === TRUE) {
        $_SESSION['cart_id'] = $conn->insert_id; // Get the newly created cart ID
        echo "<p>Default cart created for you!</p>";
    } else {
        echo "<p>Error creating default cart: " . $conn->error . "</p>";
    }
}
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;

    if (!$user_id || !$cart_id) {
        echo "<p>Please log in or create a cart before adding items.</p>";
        return false;
    }

    $sql = "INSERT INTO cart_items (cart_id, product_id, quantity)
            VALUES ('$cart_id', '$product_id', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "<p>Error adding item to cart: " . $conn->error . "</p>";
        return false;
    }
}

// Function to get cart items
function getCartItems($conn) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;
    $cart_items = [];

    if (!$user_id || !$cart_id) {
        return $cart_items; // Return empty array if no cart or user
    }

    $sql = "SELECT ci.product_id, p.name, p.price, ci.quantity
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = '$cart_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }
    return $cart_items;
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id, $cart_id) {
    $sql = "DELETE FROM cart_items WHERE product_id = '$product_id' AND cart_id = '$cart_id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "<p>Error removing item from cart: " . $conn->error . "</p>";
        return false;
    }
}

// Function to update quantity of an item in the cart
function updateQuantity($conn, $product_id, $cart_id, $quantity) {
    $sql = "UPDATE cart_items
            SET quantity = '$quantity'
            WHERE product_id = '$product_id' AND cart_id = '$cart_id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "<p>Error updating quantity in cart: " . $conn->error . "</p>";
        return false;
    }
}


// --- Example Usage (Simulated for demonstration) ---

// Connect to the database
$conn = connectDB($dbHost, $dbUser, $dbPass, $dbName);

// Initialize Cart (Run this once per session)
createCart($conn);

// Get cart items
$cart_items = getCartItems($conn);

// Display cart items
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// --- Example Cart Operations (Simulated - Replace with your actual form handling) ---

// Assuming a form to update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($conn, $product_id, $_SESSION['cart_id'], $new_quantity);
    // Optionally redirect to the cart page after updating.
}

// Example: Remove Item
if (isset($_GET['remove_item'])) {
    $product_id = $_GET['remove_item'];
    removeFromCart($conn, $product_id, $_SESSION['cart_id']);
    // Redirect to the cart page to refresh.
}

?>
