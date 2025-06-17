

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// --- Functions ---

/**
 * Connects to the database.
 *
 * @return mysqli|false Connection object or false on failure.
 */
function connect_to_db() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

/**
 * Adds a product to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return bool True on success, false on failure.
 */
function add_to_cart(int $product_id, int $quantity) {
    $conn = connect_to_db();

    if (!$conn) {
        return false;
    }

    // Check if the product exists (basic check)
    $result = $conn->query("SELECT id, name, price FROM products WHERE id = $product_id");
    if (!$result || $result->num_rows == 0) {
        $conn->close();
        return false; // Product doesn't exist
    }

    $product = $result->fetch_assoc();

    // Construct the query
    $sql = "INSERT INTO cart (product_id, quantity, total_price) VALUES ($product_id, $quantity, " . $product['price'] * $quantity . ")";

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}


/**
 * Retrieves the contents of the shopping cart.
 *
 * @return array An associative array representing the cart contents (product_id => quantity).
 */
function get_cart_contents() {
    $conn = connect_to_db();

    if (!$conn) {
        return []; // Return empty array if connection fails.
    }

    $cart_contents = [];
    $result = $conn->query("SELECT product_id, quantity FROM cart");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart_contents[$row['product_id']] = $row['quantity'];
        }
    }

    $conn->close();
    return $cart_contents;
}


/**
 * Calculates the total cart value
 *
 * @return float The total cart value
 */
function calculate_cart_total() {
    $conn = connect_to_db();

    if (!$conn) {
        return 0;
    }

    $total = 0;
    $result = $conn->query("SELECT product_id, quantity, price FROM cart");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total = $total + ($row['price'] * $row['quantity']);
        }
    }

    $conn->close();
    return $total;
}


/**
 * Removes a product from the shopping cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True on success, false on failure.
 */
function remove_from_cart(int $product_id) {
    $conn = connect_to_db();

    if (!$conn) {
        return false;
    }

    // First, delete all existing quantities of the product
    $conn->query("DELETE FROM cart WHERE product_id = $product_id");

    if (!$conn->affected_rows > 0) {
        $conn->close();
        return false;
    }

    $conn->close();
    return true;
}



/**
 * Example usage (demonstrates the purchase functionality)
 */

// --- Database Setup (Simplified for demonstration) ---
// You would typically create these tables using SQL.
// Example SQL (not executed here, just for reference):
// CREATE TABLE products (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   name VARCHAR(255) NOT NULL,
//   price DECIMAL(10, 2) NOT NULL
// );
//
// CREATE TABLE cart (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   product_id INT NOT NULL,
//   quantity INT NOT NULL,
//   total_price DECIMAL(10, 2) NOT NULL
// );


// Example Products (replace with your actual product data)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Hat', 'price' => 15.00]
];

// --- Purchase Flow ---
if (isset($_POST['add_to_cart']) && $_POST['product_id'] > 0) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']); // Get quantity from the form

    if (add_to_cart($product_id, $quantity)) {
        echo "<p>Product added to cart!</p>";
    } else {
        echo "<p>Error adding product to cart.</p>";
    }
}

if (isset($_POST['remove_from_cart']) && $_POST['product_id'] > 0) {
    $product_id = intval($_POST['product_id']);
    if (remove_from_cart($product_id)) {
        echo "<p>Product removed from cart!</p>";
    } else {
        echo "<p>Error removing product from cart.</p>";
    }
}


// Display cart contents:
$cart_contents = get_cart_contents();

echo "<h2>Shopping Cart</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $quantity) {
        $product_name = $products[$product_id]['name'];
        echo "<li>Product: " . $product_name . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Cart Value: $" . calculate_cart_total() . "</p>";
}
?>
