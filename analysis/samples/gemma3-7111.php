  <label for="product_name">Product Name:</label>
  <input type="text" id="product_name" name="product_name" required><br><br>

  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity" required><br><br>

  <label for="price">Price:</label>
  <input type="number" step="0.01" id="price" name="price" required><br><br>

  <button type="submit">Add to Cart</button>
</form>


<?php

// Database Configuration (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPassword = "your_password";
$dbName = "your_database_name";

//  Ensure the database connection is established
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (Crucial for security)
    $productName = $_POST["product_name"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];  // Get the price
    $customerName = $_POST["customer_name"];

    // Input Validation -  Add more validation as needed
    if (empty($productName) || empty($quantity) || !is_numeric($quantity) || empty($price) || !is_numeric($price) || empty($customerName)) {
        $error = "Please fill in all fields correctly.";
    } elseif (intval($quantity) <= 0 || intval($price) <= 0) {
        $error = "Quantity and Price must be greater than zero.";
    }
    else {
        // Prepare the SQL query (Use prepared statements for security)
        $sql = "INSERT INTO orders (product_name, quantity, price, customer_name) VALUES (?, ?, ?, ?)";

        // Use prepared statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssid", $productName, $quantity, $price);

            // Execute the query
            if ($stmt->execute()) {
                $success = "Order placed successfully!";
            } else {
                $error = "Query execution failed: " . $stmt->error;
            }
        } else {
            $error = "Error preparing statement.";
        }
    }
}

// Display the form
?>
