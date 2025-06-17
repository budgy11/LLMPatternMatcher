

<?php

// Database Connection (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// --- Purchase Functionality ---

/**
 * Processes a purchase.
 *
 * @param array $orderData Array of product IDs and quantities.  Example: ['product_id' => 1, 'quantity' => 2]
 * @return string  Success or error message.
 */
function processPurchase(array $orderData) {
    // 1. Validate Order Data
    if (empty($orderData)) {
        return "Error: No products were selected for purchase.";
    }

    // 2.  Fetch Product Information
    $products = [];
    foreach ($orderData as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return "Error: Product with ID $product_id not found.";
        }

        $products[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }

    // 3. Calculate Total Price
    $totalPrice = 0;
    foreach ($products as $product) {
        $totalPrice += $product['price'] * $product['quantity'];
    }

    // 4.  Create Order Record
    $order_id = date("YmdHis"); // Generate a unique order ID (YearMonthDayHourMinuteSecond)
    $stmt = $pdo->prepare("INSERT INTO orders (order_id, customer_name, total_price) VALUES (?, ?, ?)");
    $stmt->execute([$order_id, "Guest", $totalPrice]);  //  Replace "Guest" with user's name if logged in
    $order_id = $pdo->lastInsertId(); //Get the newly created ID

    // 5.  Create Order Items
    foreach ($products as $product) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $product['id'], $product['quantity']]);
    }

    return "Purchase completed successfully! Order ID: " . $order_id;
}


// --- Example Usage (Simulate a form submission) ---

// Example order data (replace with data from a form)
$order_data = [
    1 => 2, // Product ID 1, quantity 2
    2 => 1, // Product ID 2, quantity 1
];

// Process the purchase
$result = processPurchase($order_data);

echo "<p>" . $result . "</p>";


// ---  Dummy Data (Products Table -  create this in your database) ---
/*
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

INSERT INTO products (name, price) VALUES
('Laptop', 1200.00),
('Mouse', 25.00),
('Keyboard', 75.00);
*/
?>
