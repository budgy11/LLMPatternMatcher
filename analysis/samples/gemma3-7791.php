
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Form</h1>

<form method="post" action="">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <button type="submit">Add Purchase</button>
</form>

</body>
</html>


<?php

// ... (Database connection code - same as before) ...

// Function to add a new purchase using prepared statements
function addPurchase($product_id, $quantity, $customer_name, $customer_email) {
  global $conn;

  // Sanitize inputs (important even with prepared statements, but less critical)
  $product_id = $conn->real_escape_string($product_id);
  $quantity = $conn->real_escape_string($quantity);
  $customer_name = $conn->real_escape_string($customer_name);
  $customer_email = $conn->real_escape_string($customer_email);


  // SQL query with a placeholder
  $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email)
          VALUES (?, ?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("isss", $product_id, $quantity, $customer_name, $customer_email);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    return true;
  } else {
    error_log("Error adding purchase: " . $conn->error); // Log the error
    return false;
  }

  // Close the statement
  $stmt->close();
}
?>
