

<?php
session_start();

// --- Database Connection (Replace with your actual credentials) ---
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";
// --- End Database Connection ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Crucial for security!)
  $name = $_POST["name"];
  $email = $_POST["email"];
  $quantity = $_POST["quantity"];
  $product_id = $_POST["product_id"]; // Assuming you have a product ID
  // Add more validation here, such as:
  // - Check if name, email, and quantity are not empty
  // - Check if quantity is a valid number
  // - Sanitize inputs to prevent SQL injection

  // **IMPORTANT: SQL Injection Prevention**
  //  Instead of directly inserting user input into the query, 
  //  use prepared statements with PDO or MySQLi. 
  //  This is essential for security!  

  // Example using PDO (Recommended):
  try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the statement
    $stmt = $pdo->prepare("INSERT INTO orders (name, email, quantity, product_id) VALUES (?, ?, ?, ?)");

    // Bind parameters
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $quantity);
    $stmt->bindParam(4, $product_id);

    // Execute the statement
    $stmt->execute();

    // Success message
    echo "Order placed successfully! Thank you, " . $name . "!";

  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>
