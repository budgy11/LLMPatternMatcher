    </select>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1" value="1">

    <button type="submit">Purchase</button>
</form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// --- Product Data (Simulated for demonstration) ---
$products = [
  1 => ["id" => 1, "name" => "Laptop", "price" => 1200.00],
  2 => ["id" => 2, "name" => "Mouse", "price" => 25.00],
  3 => ["id" => 3, "name" => "Keyboard", "price" => 75.00],
];

// --- Purchase Functionality ---

function createPurchase($productId, $quantity, $customerName, $customerEmail) {
  global $conn, $products;

  // Validate inputs
  if (!$productId || !$quantity || !$customerName || !$customerEmail) {
    return "Error: All fields are required.";
  }

  if (!is_numeric($quantity) || $quantity <= 0) {
    return "Error: Invalid quantity. Quantity must be a positive number.";
  }

  // Validate Product ID
  if (!isset($products[$productId])) {
    return "Error: Product ID not found.";
  }

  $product = $products[$productId];

  // Calculate total price
  $totalPrice = $product["price"] * $quantity;

  // Prepare the SQL query
  $sql = "INSERT INTO purchases (product_id, customer_name, customer_email, quantity, total_price) 
          VALUES (:product_id, :customer_name, :customer_email, :quantity, :total_price)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bindParam(':product_id', $productId);
  $stmt->bindParam(':customer_name', $customerName);
  $stmt->bindParam(':customer_email', $customerEmail);
  $stmt->bindParam(':quantity', $quantity);
  $stmt->bindParam(':total_price', $totalPrice);

  // Execute the query
  if ($stmt->execute()) {
    return "Purchase created successfully!  Order ID: " . $conn->lastInsertId();
  } else {
    return "Error creating purchase.  " . print_r($stmt->errorInfo(), true);
  }
}

// --- Example Usage (Form Handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $productId = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  $customerName = $_POST["customer_name"];
  $customerEmail = $_POST["customer_email"];

  // Create the purchase
  $result = createPurchase($productId, $quantity, $customerName, $customerEmail);

  // Display the result
  echo $result;
}

?>
