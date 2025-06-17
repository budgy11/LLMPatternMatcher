

function addPurchase($userId, $productId, $quantity, $totalAmount) {
    $conn = connectToDatabase();

    // Prepare the SQL statement
    $sql = "INSERT INTO purchases (userId, productId, quantity, totalAmount, purchaseDate)
            VALUES (?, ?, ?, ?, NOW())";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("sii", $userId, $productId, $quantity); // 's' for string, 'i' for integer

    // Execute the statement
    if ($stmt->execute() === false) {
        die("Error executing statement: " . $stmt->error);
    }

    $stmt->close();
    return true;
}


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize input
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];

  if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
    $error = "Invalid input.  Please enter valid product ID and quantity.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO cart (product_id, quantity) VALUES (?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ii", $product_id, $quantity);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Product added to cart!";
    } else {
      $error = "Error adding product to cart. " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}

// Function to display the cart (simplified for demonstration)
function displayCart() {
  // Replace this with your actual cart retrieval logic
  $cart_items = getCartItems();

  if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($cart_items as $item) {
      echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
  }
}


//  Example product data (replace with your product database)
$products = [
    1 => ["name" => "Laptop", "price" => 1200],
    2 => ["name" => "Mouse", "price" => 25],
    3 => ["name" => "Keyboard", "price" => 75],
];

//  Database connection  (using PDO for better security)
try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); // Stop execution if the database connection fails
}

//  Add this to the end of your page to show the purchase form and/or cart.
?>
