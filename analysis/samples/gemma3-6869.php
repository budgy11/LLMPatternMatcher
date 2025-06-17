    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>

    <input type="submit" value="Add to Cart">
</form>

</body>
</html>


<?php

// --- Database Configuration (Replace with your actual credentials) ---
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// --- Helper Functions ---

/**
 * Connects to the database.
 *
 * @return mysqli Connection object or null on failure.
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
 *
 * @param mysqli $conn Database connection.
 * @param int $product_id Product ID.
 * @param int $quantity Quantity to add.
 * @return bool True if successful, false otherwise.
 */
function add_to_cart(mysqli $conn, $product_id, $quantity) {
    $product_id = mysqli_real_escape_string($conn, $product_id); // Sanitize input
    $quantity = mysqli_real_escape_string($conn, $quantity); // Sanitize input

    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false; // Product not found
    }

    $product = mysqli_fetch_assoc($result);

    // Check if the product is already in the cart
    $cart_query = "SELECT * FROM cart WHERE product_id = '$product_id'";
    $cart_result = mysqli_query($conn, $cart_query);

    if (mysqli_num_rows($cart_result) > 0) {
        // Product already in cart, update the quantity
        $update_query = "UPDATE cart SET quantity = quantity + '$quantity' WHERE product_id = '$product_id'";
        if (mysqli_query($conn, $update_query)) {
            return true;
        } else {
            return false;
        }
    } else {
        // Product not in cart, add it
        $insert_query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', '$quantity')";

        if (mysqli_query($conn, $insert_query)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Retrieves the cart contents.
 *
 * @param mysqli $conn Database connection.
 * @return array Array of cart items, or an empty array if the cart is empty.
 */
function get_cart_contents(mysqli $conn) {
    $query = "SELECT * FROM cart";
    $result = mysqli_query($conn, $query);

    $cart_items = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cart_items[] = $row;
        }
    }

    return $cart_items;
}


/**
 * Removes a product from the cart.
 *
 * @param mysqli $conn Database connection.
 * @param int $product_id Product ID.
 * @return bool True if successful, false otherwise.
 */
function remove_from_cart(mysqli $conn, $product_id) {
    $product_id = mysqli_real_escape_string($conn, $product_id); // Sanitize input

    $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $delete_query = "DELETE FROM cart WHERE product_id = '$product_id'";
        if (mysqli_query($conn, $delete_query)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // Product not found in cart
    }
}


// --- Example Usage (Replace this with your actual request handling) ---

// Assuming this code runs on a form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Example: Add to cart
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if (add_to_cart(connect_to_db(), $product_id, $quantity)) {
            echo "Product added to cart.";
        } else {
            echo "Failed to add product to cart.";
        }
    }

    // Example: Remove from cart
    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        if (remove_from_cart(connect_to_db(), $product_id)) {
            echo "Product removed from cart.";
        } else {
            echo "Failed to remove product from cart.";
        }
    }

    // Get the cart contents for display
    $cart_items = get_cart_contents(connect_to_db());
    echo "<br>";
    echo "<h2>Cart Contents:</h2>";
    if (empty($cart_items)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart_items as $item) {
            echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
        }
        echo "</ul>";
    }
}
?>
