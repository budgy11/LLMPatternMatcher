

<?php

// Database connection details (Replace with your actual details)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database_name";

//  Helper function to connect to the database
function connectToDatabase() {
  try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    // Handle database connection error.  Log or display error.
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed.");
  }
}

// Function to create a new purchase
function createPurchase($product_id, $quantity, $customer_name, $customer_email) {
    $pdo = connectToDatabase();

    try {
        $stmt = $pdo->prepare("INSERT INTO purchases (product_id, quantity, customer_name, customer_email, purchase_date) VALUES ( :product_id, :quantity, :customer_name, :customer_email, NOW())");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':customer_email', $customer_email);

        $stmt->execute();
        return $pdo->lastInsertId(); // Returns the ID of the newly created purchase
    } catch (PDOException $e) {
        // Handle any database errors
        error_log("Purchase Creation Error: " . $e->getMessage());
        return false; // Indicate failure
    }
}

// Function to get purchase details by ID
function getPurchaseDetails($purchase_id) {
    $pdo = connectToDatabase();

    try {
        $stmt = $pdo->prepare("SELECT * FROM purchases WHERE id = :purchase_id");
        $stmt->bindParam(':purchase_id', $purchase_id);
        $stmt->execute();
        $purchase = $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch as an associative array
        return $purchase;
    } catch (PDOException $e) {
        // Handle database errors
        error_log("Purchase Retrieval Error: " . $e->getMessage());
        return false;
    }
}


//  Example Usage (You'd typically handle this through a form submission, but this shows how it's used)

// --- Sample Data (For Testing) ---
$product_id = 1;
$quantity = 2;
$customer_name = "John Doe";
$customer_email = "john.doe@example.com";

// --- Create the Purchase ---
$newPurchaseId = createPurchase($product_id, $quantity, $customer_name, $customer_email);

if ($newPurchaseId) {
  echo "Purchase created successfully!  New Purchase ID: " . $newPurchaseId . "<br>";

  //  Example: Retrieve details of the purchase
  $purchaseDetails = getPurchaseDetails($newPurchaseId);
  if ($purchaseDetails) {
    echo "Purchase Details:<br>";
    echo "Product ID: " . $purchaseDetails['product_id'] . "<br>";
    echo "Quantity: " . $purchaseDetails['quantity'] . "<br>";
    echo "Customer Name: " . $purchaseDetails['customer_name'] . "<br>";
    echo "Customer Email: " . $purchaseDetails['customer_email'] . "<br>";
    echo "Purchase Date: " . $purchaseDetails['purchase_date'] . "<br>";
  } else {
    echo "Error retrieving purchase details.<br>";
  }
} else {
  echo "Purchase creation failed.<br>";
}


// --- Database Table Creation (Run this once to set up the database) ---
// This is just a sample, adjust data types and constraints as needed
/*
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/
?>
