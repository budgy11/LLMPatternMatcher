
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Online Store Purchase</h1>

  <form method="post">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <option value="123">Laptop</option>
      <option value="456">Mouse</option>
      <option value="789">Keyboard</option>
    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" value="1" min="1"><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" name="customer_name" value="Guest"><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Purchase Class
class Purchase {

  private $db;

  public function __construct() {
    $this->db = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($this->db->connect_error) {
      die("Connection failed: " . $this->db->connect_error);
    }
  }

  public function createPurchase($product_id, $quantity, $customer_name, $customer_email) {
    // Input Validation (Basic - improve for production)
    if (!is_numeric($product_id) || !is_numeric($quantity)) {
      return "Invalid product ID or quantity.";
    }
    if (empty($customer_name) || empty($customer_email)) {
      return "Customer name and email are required.";
    }

    // SQL Query
    $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email, purchase_date)
            VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email', NOW())";

    if ($this->db->query($sql)) {
      return "Purchase created successfully!";
    } else {
      return "Error creating purchase: " . $this->db->error;
    }
  }

  public function __destruct() {
    $this->db->close();
  }
}

// --- Example Usage (Simulated Form Handling) ---

// 1. Simulate form data
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];


// 2. Create a new Purchase object
$purchase = new Purchase();

// 3.  Handle the purchase creation
$result = $purchase->createPurchase($product_id, $quantity, $customer_name, $customer_email);

// 4. Display the result
echo "<p>Result: " . $result . "</p>";

// You would typically display this result to the user,
// and if successful, redirect them to a confirmation page.

?>
