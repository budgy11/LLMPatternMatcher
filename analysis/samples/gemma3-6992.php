    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1"><br><br>

    <input type="submit" name="addToCart" value="Add to Cart">
</form>

<form method="post" action="">
  <input type="hidden" name="action" value="checkout">
  <input type="submit" name="checkout" value="Checkout">
</form>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data (Essential for security!)
    $name = $_POST["name"];
    $email = $_POST["email"];
    $quantity = $_POST["quantity"];
    $product_id = $_POST["product_id"]; // Assuming you have a product ID field

    // Input Validation (Basic - improve this for production)
    if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (is_numeric($quantity) && $quantity > 0) {
        // Add more validation here (e.g., quantity limits)
    } else {
        // Product Data (Fetch from database -  Example, adjust to your schema)
        // Assume you have a table called 'products' with columns like 'id', 'name', 'price'
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id); // "i" for integer
        $stmt->execute();
        $product = $stmt->fetch_assoc();

        if (!$product) {
            $error = "Product not found.";
        } else {
            // Calculate the total price
            $total_price = $product["price"] * $quantity;

            //  Store the order information (You'll need to implement order storage)
            //  This is a simplified example; in reality, you'd save this to a database
            $order_details = [
                "name" => $name,
                "email" => $email,
                "product_id" => $product_id,
                "quantity" => $quantity,
                "total_price" => $total_price,
                "order_date" => date("Y-m-d H:i:s") // Add order timestamp
            ];

            // Save the order details (Replace with your actual storage method)
            // Example:  Store in a session, file, or database
            session_start();
            $_SESSION["order_details"] = $order_details;  // Store in session

            // Display a success message
            $success = "Your order has been placed successfully!";

        }
    }
}
?>
