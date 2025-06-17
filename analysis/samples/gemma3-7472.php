

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Define the database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase.
 *
 * @param array $order_data Array containing order details (item_id, quantity, customer_name, etc.)
 * @return bool True on success, false on failure.
 */
function processPurchase(array $order_data) {
    // Validate input (Important for security - add more validation as needed)
    if (empty($order_data['item_id']) || empty($order_data['quantity']) || empty($order_data['customer_name'])) {
        error_log("Invalid purchase data received."); // Log the error
        return false;
    }

    // Sanitize input (Crucial to prevent SQL injection)
    $item_id = $conn->real_escape_string($order_data['item_id']);
    $quantity = $conn->real_escape_string($order_data['quantity']);
    $customer_name = $conn->real_escape_string($order_data['customer_name']);
    // Add more sanitation for other fields (e.g., address, payment details)

    // 1. Get Item Details from Database
    $sql = "SELECT id, name, price FROM items WHERE id = '$item_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $item_name = $item['name'];
        $item_price = $item['price'];
    } else {
        error_log("Item with ID '$item_id' not found.");
        return false; // Item not found
    }

    // 2. Calculate Total Price
    $total_price = $item_price * $quantity;

    // 3. Insert Order into Database
    $sql = "INSERT INTO orders (customer_name, item_id, quantity, total_price, order_date)
            VALUES ('$customer_name', '$item_id', '$quantity', '$total_price', NOW())";

    if ($conn->query($sql) === TRUE) {
        // 4. Update Inventory (Example)
        $sql_inventory = "UPDATE items SET stock = stock - '$quantity' WHERE id = '$item_id'";
        if ($conn->query($sql_inventory) === TRUE) {
            error_log("Purchase successful for item: $item_name");
            return true;
        } else {
            error_log("Failed to update inventory after purchase.");
            $conn->rollback(); // Rollback the order if inventory update fails
            return false;
        }
    } else {
        error_log("Failed to insert order into database: " . $conn->error);
        $conn->rollback(); // Rollback the order if insertion fails
        return false;
    }
}


// --- Example Usage (Simulated Form Submission) ---

// Assuming the form data is submitted in $_POST

// Simulate form data (Replace with actual form data)
$order_data = [
    'item_id' => '1',  // Example item ID
    'quantity' => '2',
    'customer_name' => 'John Doe'
];

// Process the purchase
if (processPurchase($order_data)) {
    echo "Purchase successful! Order ID: [Order ID generated here]";  // Replace with actual order ID retrieval
} else {
    echo "Purchase failed. Please try again.";
}

// ---  Important Notes and Further Development ---

// 1.  Error Handling:
//     - Comprehensive logging: Use error_log() or a proper logging system for debugging.
//     - Detailed error messages:  Provide informative error messages to the user or to the logging system.
//     -  Rollbacks: Crucial in case of database errors.

// 2.  Security:
//     - Prepared Statements:  **Crucially important** for preventing SQL injection.  The example provided uses `real_escape_string()`, but this is generally considered less secure than prepared statements.  Research and use prepared statements.
//     - Input Validation: Thoroughly validate all input data (type, format, range) before processing.
//     - Authentication/Authorization:  Implement user authentication and authorization to restrict access to the purchase functionality.
//     - CSRF Protection: Protect against Cross-Site Request Forgery attacks.

// 3.  Database Design:
//     - Normalize your database tables for efficiency and data integrity.  Consider tables for items, orders, order_items (linking orders and items), and potentially customers.

// 4.  Payment Integration:
//     - Integrate with a payment gateway (Stripe, PayPal, etc.) to handle payments securely.

// 5.  Inventory Management:
//     -  More sophisticated inventory management. Consider using triggers, scheduled jobs, or external inventory management systems.

// 6.  User Interface:
//     -  Develop a user-friendly interface for customers to place orders.

// 7.  Testing:
//     -  Thoroughly test your purchase functionality with different scenarios (valid data, invalid data, edge cases).


<?php
session_start();

// Database connection details (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_user";
$dbPass = "your_password";
$dbName = "your_database";

//  Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to check if a product is in the cart
function isInCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        return true;
    }
    return false;
}

// Function to update the cart quantity
function updateCartQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to add a product to the cart
function addProductToCart($productId, $quantity = 1) {
    if (isInCart($productId)) {
        updateCartQuantity($productId, $quantity);
    } else {
        // Product not in cart, add it
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = array('quantity' => $quantity, 'price' => 0); //Initialize price
        } else {
            // Product already in cart, update the quantity
            updateCartQuantity($productId, $quantity);
        }
    }
}



// Cart Functions - These are the core functions

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity is 1
    addProductToCart($productId, $quantity);
    echo "<p>Product added to cart.</p>";
}


// 2. Update Quantity
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity is 1
    updateCartQuantity($productId, $quantity);
    echo "<p>Quantity updated in cart.</p>";
}



// 3. Remove Product from Cart
if (isset($_GET['remove_from_cart'])) {
    $productId = $_GET['remove_from_cart'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    echo "<p>Product removed from cart.</p>";
}


// 4. View Cart
if (isset($_GET['view_cart'])) {
    // Display Cart Contents
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $productId => $item) {
            $productName = getProductName($productId); // Implement this function (see example below)
            echo "<li>";
            echo "<strong>$productName</strong> - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'];
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='product_id' value='$productId'>";
            echo "<input type='hidden' name='product_id' value='$productId'>";  // Double quotes are needed here for correct string concatenation
            echo "<input type='text' name='quantity' value='$item['quantity']' size='3'>";
            echo "<input type='submit' value='Update'>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ul>";

        // Calculate total price
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $productId => $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        echo "<p><strong>Total: $" . $totalPrice . "</strong></p>";

    }
}



// Helper function to get product name from database (replace with your database query)
function getProductName($productId) {
    // Example:  Assuming you have a products table with a 'id' and 'name' column
    $query = "SELECT name FROM products WHERE id = $productId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['name'];
    } else {
        return "Unknown Product";
    }
}

// Example product data (for demonstration purposes - replace with your actual data)
$products = array(
    1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
    2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
    3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75)
);

// Start the session
session_start();

?>
