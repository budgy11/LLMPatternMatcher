    <button type="submit" name="remove_from_cart">Remove Product</button>
  </form>

  <br>

  <form method="post" action="">
    <button type="submit" name="purchase">Purchase</button>
  </form>


</body>
</html>


<?php

// Database Connection (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_user';
$db_password = 'your_password';
$db_name = 'your_database';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to handle the purchase process
function processPurchase($cart, $customer_name, $customer_email, $payment_method) {
    // 1. Data Validation (Basic - expand as needed)
    if (empty($cart) || empty($customer_name) || empty($customer_email) || empty($payment_method)) {
        return false; // Indicate failure
    }

    // 2. Create Order Record in the Database
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, payment_method, order_date) VALUES (:customer_name, :customer_email, :payment_method, NOW())");
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':customer_email', $customer_email);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->execute();
        $order_id = $pdo->lastInsertId(); // Get the ID of the newly created order

        // 3. Add Order Items to the Database
        foreach ($cart as $item_id => $quantity) {
            try {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $item_id); // Product ID is the item key
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
            } catch(PDOException $e) {
                // Handle any errors during item insertion (e.g., product not found)
                error_log("Error adding item to order: " . $e->getMessage());
                return false; // Indicate failure
            }
        }

        return true; // Indicate success

    } catch(PDOException $e) {
        //Handle any errors during order creation or item insertion
        error_log("Database error during purchase: " . $e->getMessage());
        return false;
    }
}

// --- Example Usage (for demonstration - integrate into a form) ---

// 1. Get cart data (Assuming this is coming from a session or form)
$cart = [
    'product_1' => 2, // Item ID: 2 of product 1
    'product_3' => 1, // Item ID: 3 of product 3
];


// 2. Get customer information (From a form)
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$payment_method = $_POST['payment_method'];

// 3. Process the purchase
if (processPurchase($cart, $customer_name, $customer_email, $payment_method)) {
    echo "Purchase successful! Order ID: " . $order_id = $pdo->lastInsertId(); // Display the order ID
    // Add success message to the user
} else {
    echo "Purchase failed. Please try again.";
    // Add error message to the user
}

?>
