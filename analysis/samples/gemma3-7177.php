
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Purchase Product</h1>

  <form method="post" action="">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database connection (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Adds a new purchase to the database.
 *
 * @param int $product_id The ID of the product being purchased.
 * @param int $quantity The quantity of the product being purchased.
 * @param string $customer_name The customer's name.
 * @param string $customer_email The customer's email address.
 *
 * @return int|false The ID of the newly created purchase, or false on failure.
 */
function addPurchase(int $product_id, int $quantity, string $customer_name, string $customer_email) {
    global $conn; // Access the database connection

    // Check if the product exists (Basic validation - improve with product details)
    $product_query = "SELECT id FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Product exists, proceed with adding the purchase
    } else {
        // Product does not exist, handle the error appropriately
        error_log("Invalid product ID: " . $product_id);  // Log the error
        return false;
    }

    // Prepare the SQL query
    $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email, purchase_date)
           VALUES (?, ?, ?, ?, NOW())";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $product_id, $quantity, $customer_name, $customer_email);

    if ($stmt->execute()) {
        // Get the ID of the newly created purchase
        $purchase_id = $conn->insert_id;
        return $purchase_id;
    } else {
        // Handle errors
        error_log("Error adding purchase: " . $stmt->error);
        return false;
    }

    // Close the statement
    $stmt->close();
}

// --- Example Usage ---
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID and quantity from the form
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];

    // Add the purchase
    $purchase_id = addPurchase($product_id, $quantity, $customer_name, $customer_email);

    if ($purchase_id) {
        echo "Purchase successful!  Purchase ID: " . $purchase_id;
    } else {
        echo "Purchase failed.  Please try again.";
    }
}
?>
