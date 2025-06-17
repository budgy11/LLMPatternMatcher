    </ul>

    <hr>

    <a href='?action=view'>View Cart</a>

</body>
</html>


<?php
session_start(); // Start the session to track user data and shopping cart

// --- Database Configuration (Replace with your actual details) ---
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';
// --- End Database Configuration ---


// --- Helper Functions ---

/**
 * Connect to the database.
 *
 * @return mysqli|false Database connection object or false on error.
 */
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

/**
 * Add a product to the shopping cart.
 *
 * @param int $product_id
 * @param int $quantity
 * @return bool True on success, false on failure.
 */
function addToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    if (!$conn) {
        return false;
    }

    $product_id = (int)$product_id; // Ensure product_id is an integer
    $quantity = (int)$quantity;      // Ensure quantity is an integer

    // Check if the product exists
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product_name = $row['name'];
        $product_price = $row['price'];

        // Check if the product is already in the cart
        $cart_key = 'cart_' . session_id();
        $cart = unserialize($_SESSION[$cart_key]);

        if ($cart) {
            // Product already in cart - update the quantity
            foreach ($cart as &$item) { //Use &$item to modify the existing cart item
                if ($item['product_id'] == $product_id) {
                    $item['quantity'] += $quantity;
                    $item['total_price'] = $item['quantity'] * $product_price; // Recalculate the total price
                    break;
                }
            }
        } else {
            // Product not in cart - create a new entry
            $cart[] = [
                'product_id' => $product_id,
                'name' => $product_name,
                'quantity' => $quantity,
                'price' => $product_price,
                'total_price' => $quantity * $product_price
            ];
        }

        // Serialize the cart
        $_SESSION[$cart_key] = serialize($cart);
        return true;
    } else {
        return false; // Product not found
    }
    $stmt->close();
}


/**
 * Get the current shopping cart.
 *
 * @return array|false The shopping cart array, or false on error.
 */
function getCart() {
    $cart_key = 'cart_' . session_id();
    $cart = unserialize($_SESSION[$cart_key]);

    if ($cart) {
        return $cart;
    } else {
        return []; // Return an empty array if the cart is empty
    }
}

/**
 * Calculate the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotalCartValue() {
    $cart = getCart();
    $total = 0.00;

    foreach ($cart as $item) {
        $total += $item['total_price'];
    }

    return $total;
}

/**
 * Clear the shopping cart.
 */
function clearCart() {
    $cart_key = 'cart_' . session_id();
    unset($_SESSION[$cart_key]);
}
// --- End Helper Functions ---



// --- Purchase Processing ---

if (isset($_POST['purchase_submit'])) {
    // Validate input (very basic - enhance as needed)
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['address'])) {
        echo "<p style='color:red;'>Please fill in all the required fields.</p>";
        exit;
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $total = calculateTotalCartValue();

    // Sanitize input (important for security - especially if displaying in emails/databases)
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Safe email sanitization
    $address = htmlspecialchars($address);


    //  Example:  Log the purchase details (replace with your actual logging mechanism)
    echo "<p>Purchase Details:</p>";
    echo "<p>Name: " . $name . "</p>";
    echo "<p>Email: " . $email . "</p>";
    echo "<p>Address: " . $address . "</p>";
    echo "<p>Total: $" . $total . "</p>";



    // 1.  Empty the cart
    clearCart();

    // 2.  Simulate order processing (e.g., send confirmation email, update database)
    echo "<p>Order placed successfully!  Thank you for your purchase.</p>";
}
?>
