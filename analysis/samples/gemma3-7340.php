

<?php

// --- Database Configuration (Replace with your actual details) ---
$db_host = "localhost";
$db_name = "e_commerce";
$db_user = "your_username";
$db_password = "your_password";

// --- Helper Functions ---

/**
 * Connects to the database.
 *
 * @return mysqli|false Connection object or false on failure.
 */
function connect_to_db() {
  global $db_host, $db_name, $db_user, $db_password;
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

/**
 * Executes a SQL query and returns the result.
 *
 * @param mysqli $conn Connection object.
 * @param string $sql SQL query.
 * @return array|false Result set or false on failure.
 */
function execute_query(mysqli $conn, $sql) {
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
    return $data;
  }
  return false;
}

/**
 * Sanitizes input to prevent SQL injection.
 *
 * @param string $input Input string.
 * @return string Sanitized string.
 */
function sanitize_input($input) {
    $input = trim($input);
    return $input; // Simple escaping -  Consider using mysqli_real_escape_string for robust escaping
}

// --- Purchase Processing Logic ---

// 1. Handle Form Submission (if applicable)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // --- Validate Inputs ---
  $product_id = sanitize_input($_POST["product_id"]);
  $quantity = sanitize_input($_POST["quantity"]);
  $customer_name = sanitize_input($_POST["customer_name"]);
  $customer_email = sanitize_input($_POST["customer_email"]);


  // --- Basic Validation (Expand this for more robust validation) ---
  if (!is_numeric($product_id)) {
    $error_message = "Invalid Product ID";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error_message = "Invalid Quantity";
  } elseif (empty($customer_name) || empty($customer_email)) {
    $error_message = "Customer Information Required";
  }


  // --- If no errors, process the purchase ---
  if (empty($error_message)) {
    // --- Get Product Details (Replace with your actual database query) ---
    $product_query = "SELECT product_id, product_name, price FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id); // "i" for integer
    $stmt->execute();
    $product_result = $stmt->get_result();
    $product = $product_result->fetch_assoc();

    if ($product) {
      $total_amount = $product["price"] * $quantity;

      // --- Store Purchase Information (Replace with your database logic) ---
      $purchase_data = [
        "product_id" => $product_id,
        "customer_name" => $customer_name,
        "customer_email" => $customer_email,
        "quantity" => $quantity,
        "total_amount" => $total_amount,
        "purchase_date" => date("Y-m-d H:i:s")
      ];

      // --- Save the purchase to the database ---
      //  (Example -  You'll need to adapt this to your database schema)
      //  $sql = "INSERT INTO purchases (product_id, customer_name, customer_email, quantity, total_amount, purchase_date)
      //          VALUES (?, ?, ?, ?, ?, ?)";
      //  $stmt = $conn->prepare($sql);
      //  $stmt->bind_param("isisi", $product_id, $customer_name, $customer_email, $quantity, $total_amount);
      //  $stmt->execute();

      //  Instead of saving directly, let's just print for demo:
      echo "<h3>Purchase Successful!</h3>";
      echo "<p>Product: " . $product["product_name"] . "</p>";
      echo "<p>Quantity: " . $quantity . "</p>";
      echo "<p>Total Amount: $" . number_format($total_amount, 2) . "</p>";  // Format as currency
      echo "<p>Customer: " . $customer_name . "</p>";

    } else {
      echo "<p style='color:red;'>Product not found.</p>";
    }
  } else {
    // Display error messages
    echo "<div style='color:red;'>";
    if (!empty($error_message)) {
      echo $error_message . "<br>";
    }
    // Add more error handling here as needed.
    echo "</div>";
  }
}

// --- Database Connection ---
$conn = connect_to_db();

?>
