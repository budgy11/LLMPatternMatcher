    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="items">Items (comma-separated):</label>
    <input type="text" id="items" name="items" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "e_commerce";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data (Basic example - enhance this for production)
    $product_name = trim($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $total_amount = floatval($_POST["total_amount"]);

    // Basic input validation
    if (empty($product_name) || $quantity <= 0 || $total_amount <= 0) {
        $error_message = "Please fill in all fields correctly.";
    } else {
        // Process the order (e.g., save to database)
        // In a real application, you would likely use prepared statements to prevent SQL injection.
        // This is a simplified example for demonstration.
        
        //  Simulate saving to database (replace with your database logic)
        $order_id = generate_order_id(); // Example function to generate a unique order ID
        $order_data = [
            'order_id' => $order_id,
            'product_name' => $product_name,
            'quantity' => $quantity,
            'total_amount' => $total_amount,
            'order_date' => date("Y-m-d H:i:s")
        ];
        
        // Save the order data (e.g., insert into a database table)
        // save_order_to_database($order_data); // Placeholder function

        // Display success message
        $success_message = "Order placed successfully! Order ID: " . $order_id;
    }
}
?>
