
    <br><br>

    <button type="submit">Add to Cart</button>
  </form>

  <br><br>

  <h2>Cart (Example - You'll need to implement a proper cart system)</h2>
  <p>This is a placeholder for your cart functionality.</p>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Session handling (for user management)
session_start();

// --- Database Functions ---

/**
 * Connects to the database.
 * @return mysqli|false Connection object or false on error.
 */
function connect_to_db() {
    global $db_host, $db_name, $db_user, $db_password;
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

/**
 * Adds a product to the cart.
 * @param int $product_id
 * @param int $quantity
 * @return bool True on success, false on failure.
 */
function add_to_cart(int $product_id, int $quantity) {
    $conn = connect_to_db();

    if (!$conn) {
        return false;
    }

    // Check if the product exists (basic check)
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // Product doesn't exist
    }
    $product = $result->fetch_assoc();
    $stmt->close();

    // Get the cart ID
    $cart_id = $_SESSION['cart_id'];

    // Prepare the update query
    $update_query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iii", $cart_id, $product_id, $quantity);

    if ($stmt->execute()) {
        return true;
    } else {
        // Handle the error (e.g., log it)
        echo "Error adding to cart: " . $stmt->error;
        return false;
    }
    $stmt->close();
}


/**
 * Retrieves the contents of the cart.
 * @return array An associative array of cart items.
 */
function get_cart_contents() {
    $cart_id = $_SESSION['cart_id'];

    $conn = connect_to_db();

    if (!$conn) {
        return []; // Return empty array if connection fails
    }

    $query = "SELECT p.id, p.name, p.price, ci.quantity FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[$row['id']] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'total' => $row['price'] * $row['quantity']
        ];
    }

    $stmt->close();
    return $cart_items;
}

/**
 * Updates the quantity of a product in the cart.
 * @param int $product_id
 * @param int $new_quantity
 * @return bool True on success, false on failure.
 */
function update_cart_quantity(int $product_id, int $new_quantity) {
    $conn = connect_to_db();

    if (!$conn) {
        return false;
    }

    // Check if the product exists in the cart
    $query = "SELECT id FROM cart_items WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $product_id, $_SESSION['cart_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return false; // Product not found in cart
    }

    // Prepare the update query
    $update_query = "UPDATE cart_items SET quantity = ? WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iii", $new_quantity, $product_id, $_SESSION['cart_id']);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error updating cart: " . $stmt->error;
        return false;
    }
    $stmt->close();
}

/**
 * Removes a product from the cart.
 * @param int $product_id
 * @return bool True on success, false on failure.
 */
function remove_from_cart(int $product_id) {
    $conn = connect_to_db();

    if (!$conn) {
        return false;
    }

    // Prepare the delete query
    $delete_query = "DELETE FROM cart_items WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("is", $product_id, $_SESSION['cart_id']);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error removing from cart: " . $stmt->error;
        return false;
    }
    $stmt->close();
}

/**
 * Initializes the shopping cart session.
 */
function init_shopping_cart() {
    // Create the cart table if it doesn't exist
    $conn = connect_to_db();
    if(!$conn){
        return;
    }
    $create_table_query = "CREATE TABLE IF NOT EXISTS cart_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cart_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL
    )";
    if(!$conn->query($create_table_query)){
        echo "Error creating table: " . $conn->error;
    }

    // Create the cart table if it doesn't exist
    $create_table_query = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL
    )";
    if(!$conn->query($create_table_query)){
        echo "Error creating table: " . $conn->error;
    }


    // Create a unique cart ID (using a timestamp)
    $cart_id = md5(time()); //Use md5 to generate the id.
    $_SESSION['cart_id'] = $cart_id;
}



// --- Example Usage (for demonstration purposes) ---

// Initialize the shopping cart
init_shopping_cart();

// Add a product to the cart (example)
if (add_to_cart(1, 2)) {
    echo "Product added to cart.";
} else {
    echo "Failed to add product to cart.";
}

// Get the cart contents
$cart = get_cart_contents();
echo "<pre>";
print_r($cart);
echo "</pre>";

// Update quantity
if (update_cart_quantity(1, 3)) {
    echo "Quantity updated.";
} else {
    echo "Failed to update quantity.";
}

// Remove a product
if (remove_from_cart(1)) {
    echo "Product removed from cart.";
} else {
    echo "Failed to remove product from cart.";
}

// Get the updated cart contents
$cart = get_cart_contents();
echo "<pre>";
print_r($cart);
echo "</pre>";
?>
