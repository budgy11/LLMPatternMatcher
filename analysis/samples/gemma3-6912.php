

<?php

// ... (Database connection function - connectToDatabase()) ...

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_amount) {
    $conn = connectToDatabase();

    // Input validation (same as before)

    $sql_insert_order = "INSERT INTO orders (cart_id, customer_name, customer_email, order_date, payment_amount)
                          VALUES (?, ?, ?, NOW(), ?)";

    $stmt = $conn->prepare($sql_insert_order);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("isss", $cart_id, $customer_name, $customer_email);

    if ($stmt->execute() === false) {
        die("Error executing statement: " . $stmt->error);
    }

    // ... (Rest of the code remains largely the same, but with prepared statements for other queries as needed)
    $stmt->close(); // Important to close the statement after use
}


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Configuration
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];

// 1. Validate Input (Important for security)
if (!isset($product_id) || empty($product_id)) {
    echo "Error: Product ID is required.";
    exit;
}

if (!is_numeric($product_id)) {
    echo "Error: Product ID must be a number.";
    exit;
}

if (!isset($quantity) || empty($quantity)) {
    echo "Error: Quantity is required.";
    exit;
}

if (!is_numeric($quantity)) {
    echo "Error: Quantity must be a number.";
    exit;
}

if (!isset($customer_name) || empty($customer_name)) {
    echo "Error: Customer Name is required.";
    exit;
}

if (!isset($customer_email) || empty($customer_email)) {
    echo "Error: Customer Email is required.";
    exit;
}

if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid Email Address.";
    exit;
}

// 2. Retrieve Product Information
// Replace this with your database query
$product = getProductFromDatabase($product_id);  // Implement this function to fetch product details

if (!$product) {
    echo "Product not found.";
    exit;
}

// 3. Calculate Total Price
$total_price = $product['price'] * $quantity;

// 4.  Create Order Data
$order_id = rand(1000, 9999); // Generate a unique order ID (for demonstration)
$order_date = date('Y-m-d H:i:s');

// 5. Save Order to Database
// Replace this with your actual database insertion query
saveOrderToDatabase($order_id, $product_id, $quantity, $customer_name, $customer_email, $total_price, $order_date);

// 6. Display Confirmation Message
echo "<h2>Order Confirmation</h2>";
echo "<p>Order ID: " . $order_id . "</p>";
echo "<p>Product: " . $product['name'] . "</p>";
echo "<p>Quantity: " . $quantity . "</p>";
echo "<p>Total Price: $" . $total_price . "</p>";
echo "<p>Customer Name: " . $customer_name . "</p>";
echo "<p>Customer Email: " . $customer_email . "</p>";
echo "<p>Order Date: " . $order_date . "</p>";

// 7.  Log the Order (optional)
//  This is important for tracking and debugging
//  Implement logging to a file or database.
//  Example:  logOrderToDatabase($order_id, $customer_name, $total_price);


function getProductFromDatabase($product_id) {
    // Dummy database function (replace with your actual query)
    // This is just for demonstration.  In a real application,
    // you would fetch data from a database.
    $products = [
        1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
        2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
        3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00]
    ];

    if (isset($products[$product_id])) {
        return $products[$product_id];
    } else {
        return null;
    }
}

function saveOrderToDatabase($order_id, $product_id, $quantity, $customer_name, $customer_email, $total_price, $order_date) {
    // Replace with your database insertion query
    // This is just a placeholder.
    //  Example:
    //  $query = "INSERT INTO orders (order_id, product_id, quantity, customer_name, customer_email, total_price, order_date)
    //            VALUES ('$order_id', '$product_id', '$quantity', '$customer_name', '$customer_email', '$total_price', '$order_date')";
    //  $conn->query($query);
    echo "Order saved to database (placeholder)";
}

?>
