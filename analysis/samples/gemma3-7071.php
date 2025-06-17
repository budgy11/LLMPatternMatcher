

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Basic validation - improve this for production)
  $name = $_POST["name"];
  $email = $_POST["email"];
  $quantity = $_POST["quantity"];
  $price = $_POST["price"];

  // Sanitize the data to prevent XSS vulnerabilities
  $name = htmlspecialchars($name);
  $email = htmlspecialchars($email);
  $quantity = intval($quantity); // Ensure quantity is an integer
  $price = floatval($price);    // Ensure price is a float

  // Input validation (more robust validation is crucial in real applications)
  if (empty($name) || empty($email) || $quantity <= 0 || $price <= 0) {
    $error_message = "Please fill in all fields correctly.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } else {
    // Process the order
    // 1.  Create a database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // 2. Prepare the SQL query
    $sql = "INSERT INTO orders (name, email, quantity, price) VALUES (?, ?, ?, ?)";

    // 3. Prepare the statement
    $stmt = $conn->prepare($sql);

    // 4. Bind the parameters
    $stmt->bind_param("ssdi", $name, $email, $quantity, $price);

    // 5. Execute the query
    if ($stmt->execute()) {
      $success_message = "Order placed successfully! Thank you for your purchase.";
    } else {
      $error_message = "Order placement failed: " . $stmt->error;
    }

    // 6. Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
