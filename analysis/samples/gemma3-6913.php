

<?php

// Database connection details (replace with your actual details)
$dbHost = "localhost";
$dbName = "shopping_cart";
$dbUser = "root";
$dbPass = "";

// Initialize the database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase.
 *
 * @param array $cart_items The items to purchase (e.g., ['product_id' => 1, 'quantity' => 2]).
 * @param string $shipping_address The shipping address.
 * @return string A message indicating the success or failure of the purchase.
 */
function processPurchase(array $cart_items, string $shipping_address) {
    // 1. Validate Input (Basic - add more robust validation as needed)
    if (empty($cart_items) || empty($shipping_address)) {
        return "Error: Please fill in all required fields.";
    }

    // 2.  Update Stock (Simulated - Replace with actual database update)
    // In a real system, you'd update the inventory in your database.
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        // Simulate stock decrease (replace with database query)
        // Example: $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
        echo "Simulating Stock Update: Reduced stock for product ID: " . $product_id . " by " . $quantity . "<br>";
    }


    // 3.  Create Order (Simulated - Replace with actual database insertion)
    $order_id = bin2hex(uniqid()); // Generate a unique order ID
    $order_date = date("Y-m-d H:i:s");

    // Example Order Data (replace with actual data from your database)
    $order_data = [
        'order_id' => $order_id,
        'user_id' => 1, // Replace with the user's ID
        'order_date' => $order_date,
        'shipping_address' => $shipping_address,
        'total_amount' => 0 // Calculate this based on cart items
    ];

    // Save order to database (replace with actual insert query)
    // Example: $conn->query("INSERT INTO orders (order_id, user_id, order_date, shipping_address, total_amount) VALUES ('$order_id', $user_id, '$order_date', '$shipping_address', $total_amount)");
    echo "Simulating Order Creation: Order ID: " . $order_id . "<br>";


    // 4.  Confirmation Message
    return "Purchase successful! Order ID: " . $order_id;
}


// --- Example Usage (Simulated Form Handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get cart items from the form (assuming they are in a JSON array)
    $cart_items_json = $_POST['cart_items'];
    $cart_items = json_decode($cart_items_json, true); // Convert JSON to associative array

    // Get shipping address from the form
    $shipping_address = $_POST['shipping_address'];

    // Process the purchase
    $purchase_result = processPurchase($cart_items, $shipping_address);

    // Display the result
    echo $purchase_result;
}

?>
