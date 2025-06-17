</body>
</html>


<?php

// Database connection details
$db_host = "localhost";
$db_name = "shop";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate input data
  $product_id = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);

  if ($product_id && $quantity) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO orders (product_id, quantity) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind parameters
      $stmt->bind_param("ii", $product_id, $quantity);

      // Execute the query
      if ($stmt->execute()) {
        echo "Order placed successfully!";
      } else {
        echo "Error placing order: " . $stmt->error;
      }

      // Close the statement and connection
      $stmt->close();
      $conn->close();
    } else {
      echo "Error preparing statement.";
    }
  } else {
    echo "Invalid product ID or quantity.";
  }
}

?>
