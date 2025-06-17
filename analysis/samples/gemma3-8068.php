

<?php

// Database Connection (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// --- Purchase Functionality ---

/**
 * Creates a new purchase record in the database.
 *
 * @param array $orderData An associative array containing order details:
 *                         - product_id => int  (ID of the product)
 *                         - quantity => int   (Quantity of the product)
 *                         - customer_id => int (ID of the customer - optional, if not provided, defaults to 1)
 *
 * @return int|false  The ID of the newly created purchase record on success, 
 *                   false if the purchase creation failed.
 */
function createPurchase(array $orderData) {
    // Validate input (add more validation as needed)
    if (!isset($orderData['product_id']) || !is_numeric($orderData['product_id']) || $orderData['product_id'] <= 0) {
        return false;
    }
    if (!isset($orderData['quantity']) || !is_numeric($orderData['quantity']) || $orderData['quantity'] <= 0) {
        return false;
    }

    // Get product details
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id = :product_id");
    $stmt->bindParam(':product_id', $orderData['product_id']);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        return false; // Product not found
    }

    // Calculate total price
    $totalPrice = $product['price'] * $orderData['quantity'];

    // Insert into the purchases table
    $stmt = $pdo->prepare("INSERT INTO purchases (product_id, quantity, customer_id, total_price) 
                             VALUES (:product_id, :quantity, :customer_id, :total_price)");
    $stmt->bindParam(':product_id', $orderData['product_id']);
    $stmt->bindParam(':quantity', $orderData['quantity']);
    $stmt->bindParam(':customer_id', $orderData['customer_id'] ?? 1); // Use 1 as default customer ID
    $stmt->bindParam(':total_price', $totalPrice);
    $result = $stmt->execute();

    if ($result) {
        return $pdo->lastInsertId(); // Return the ID of the new purchase
    } else {
        return false;
    }
}


// --- Example Usage (This would typically be part of a web form submission) ---

// Assuming a form has submitted and $orderData contains the form data.
// Example:
// $orderData = [
//     'product_id' => 1,
//     'quantity' => 2,
//     'customer_id' => 5  // (Optional)
// ];


// Create the purchase
// $purchaseId = createPurchase($orderData);

// if ($purchaseId) {
//     echo "Purchase created successfully!  Purchase ID: " . $purchaseId;
// } else {
//     echo "Purchase creation failed.";
// }

?>
