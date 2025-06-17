

<?php
require_once 'database.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate input (Crucial for security)
    if (!is_numeric($product_id) || !is_numeric($quantity)) {
        $error_message = "Invalid product ID or quantity.";
    } elseif ($quantity <= 0) {
        $error_message = "Quantity must be greater than zero.";
    } else {
        // Proceed with the order
        $db = connect_to_db();
        $query = "INSERT INTO orders (product_id, quantity) VALUES ('" . $product_id . "', '" . $quantity . "')";
        execute_query($db, $query);
        $db->close(); // Close the connection

        $order_message = "Order placed successfully!";
    }
}
?>
