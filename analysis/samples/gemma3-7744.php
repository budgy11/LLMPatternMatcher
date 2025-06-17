

<?php

// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase order.
 *
 * @param array $order_data Array of order details:
 *    - product_id: ID of the product being purchased.
 *    - quantity: Quantity of the product being purchased.
 *    - customer_name: Customer's name.
 *    - customer_email: Customer's email.
 *
 * @return string Response message (success or error).
 */
function process_purchase(array $order_data) {

    // Validate input (important for security)
    $product_id = isset($order_data['product_id']) ? $order_data['product_id'] : null;
    $quantity = isset($order_data['quantity']) ? intval($order_data['quantity']) : 0;
    $customer_name = isset($order_data['customer_name']) ? htmlspecialchars($order_data['customer_name']) : '';
    $customer_email = isset($order_data['customer_email']) ? htmlspecialchars($order_data['customer_email']) : '';


    // Check if required fields are present
    if ($product_id === null || $quantity <= 0 || $customer_name === '' || $customer_email === '') {
        return "Error: Missing required fields. Please fill in all the details.";
    }

    // Fetch product details
    $product_query = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);  // i = integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "Error: Product with ID '$product_id' not found.";
    }

    $product = $result->fetch_assoc();
    $stmt->close();

    // Calculate total price
    $total_price = $product['price'] * $quantity;

    // Insert order into database
    $order_query = "INSERT INTO orders (customer_name, customer_email, product_id, quantity, total_price, order_date) 
                     VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("isd", $customer_name, $product_id, $quantity, $total_price);
    $stmt->execute();
    $stmt->close();


    // Update product stock (optional - depends on your stock management)
    $update_stock_query = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $stmt = $conn->prepare($update_stock_query);
    $stmt->bind_param("id", $quantity, $product_id);
    $stmt->execute();
    $stmt->close();

    return "Order placed successfully! Order ID: " . $conn->insert_id;
}


// --- Example Usage (Form Submission Handling) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_data = [
        'product_id' => isset($_POST['product_id']) ? intval($_POST['product_id']) : null,
        'quantity' => isset($_POST['quantity']) ? intval($_POST['quantity']) : 0,
        'customer_name' => isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : '',
        'customer_email' => isset($_POST['customer_email']) ? htmlspecialchars($_POST['customer_email']) : ''
    ];

    $response = process_purchase($order_data);
    echo $response;
}
?>
