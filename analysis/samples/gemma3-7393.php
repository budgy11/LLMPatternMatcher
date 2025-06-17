    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1"><br><br>

    <button type="submit">Add to Cart</button>
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "root";
$db_password = "";

// --- Functions ---

/**
 * Connects to the database.
 *
 * @return mysqli Connection object or null if connection fails.
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
 * Adds a product to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return bool True if added successfully, false otherwise.
 */
function add_to_cart(int $product_id, int $quantity) {
    global $conn;

    // Check if the product already exists in the cart
    $query = "SELECT * FROM cart WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product exists, update the quantity
        $query = "UPDATE cart SET quantity = quantity + ? WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $quantity, $product_id);
        $result = $stmt->execute();

        if ($result) {
            return true;
        } else {
            // Handle errors
            error_log("Error updating cart: " . $conn->error);
            return false;
        }
    } else {
        // Product doesn't exist, add a new entry
        $query = "INSERT INTO cart (product_id, quantity) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $product_id, $quantity);
        $result = $stmt->execute();

        if ($result) {
            return true;
        } else {
            // Handle errors
            error_log("Error inserting into cart: " . $conn->error);
            return false;
        }
    }

    $stmt->close(); // Close the statement
    return false;
}

/**
 * Retrieves the shopping cart contents.
 *
 * @return array An array of product details from the cart, or an empty array if the cart is empty.
 */
function get_cart_contents() {
    global $conn;

    $query = "SELECT p.product_name, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $cart_contents = array();
        while ($row = $result->fetch_assoc()) {
            $cart_contents[] = $row;
        }
        return $cart_contents;
    } else {
        return array(); // Empty cart
    }
}

/**
 * Clears the entire shopping cart.
 *
 * @return bool True if successful, false otherwise.
 */
function clear_cart() {
    global $conn;
    $query = "DELETE FROM cart";
    $result = $conn->query($query);

    if ($result) {
        return true;
    } else {
        // Handle errors
        error_log("Error clearing cart: " . $conn->error);
        return false;
    }
}


// --- Main Purchase Logic (Example) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Add items to the cart
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if (add_to_cart($product_id, $quantity)) {
            echo "<p>Item added to cart.</p>";
        } else {
            echo "<p>Error adding item to cart.</p>";
        }
    }

    // 2.  Clear the cart (e.g., after a successful purchase)
    if (isset($_POST['clear_cart'])) {
        if (clear_cart()) {
            echo "<p>Cart cleared successfully.</p>";
        } else {
            echo "<p>Error clearing cart.</p>";
        }
    }

}


// --- Example Product Table (For demonstration) ---
// You should create this in your database.

// CREATE TABLE products (
//     product_id INT PRIMARY KEY AUTO_INCREMENT,
//     product_name VARCHAR(255) NOT NULL,
//     price DECIMAL(10, 2) NOT NULL
// );

// INSERT INTO products (product_name, price) VALUES
// ('Laptop', 1200.00),
// ('Mouse', 25.00),
// ('Keyboard', 75.00);


// --- Example HTML Form (To be integrated into your webpage) ---

// <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
