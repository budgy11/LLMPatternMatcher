

<?php

// Assuming you have a database connection established (e.g., using mysqli or PDO)
// For this example, we'll use a simplified database connection:

$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add a purchase
function addPurchase($product_id, $quantity, $customer_name, $customer_email) {
  global $conn; // Access the database connection

  $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email, purchase_date) VALUES (
          '" . $product_id . "',
          " . $quantity . ",
          '".$customer_name."',
          '".$customer_email."',
          NOW()
        )";

  if ($conn->query($sql) === TRUE) {
    return true; // Purchase added successfully
  } else {
    return false; // Error adding purchase
  }
}

// Function to display a product (example - for a shopping cart)
function displayProduct($product_id, $product_name, $price) {
  echo "<form method='post' action=''>";
  echo "<label for='quantity'>" . $product_name . " - $" . $price . "</label><br>";
  echo "<input type='number' id='quantity' name='quantity' value='1' min='1' required><br>";
  echo "<input type='submit' value='Add to Cart'>";
  echo "</form>";
}



// --- Example Usage (Simulating a shopping cart interaction) ---

//  Simulate a database query to get product information (replace with your actual database query)
//  This is just for demonstration; in a real application, you'd fetch from your database.
$products = [
    1 => ["product_id" => 1, "product_name" => "Laptop", "price" => 1200],
    2 => ["product_id" => 2, "product_name" => "Mouse", "price" => 25],
    3 => ["product_id" => 3, "product_name" => "Keyboard", "price" => 75]
];

echo "<h2>Shopping Cart</h2>";

foreach ($products as $product_id => $product_data) {
    displayProduct($product_id, $product_data["product_name"], $product_data["price"]);
}


// Simulate a form submission to add items to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    //  Validate the quantity (add more robust validation in a real application)
    if (is_numeric($quantity) && $quantity >= 1) {
        if (addPurchase($product_id, $quantity, "John Doe", "john.doe@example.com")) {
            echo "<p>Item added to cart successfully!</p>";
        } else {
            echo "<p>Error adding item to cart.</p>";
        }
    } else {
        echo "<p>Invalid quantity.</p>";
    }
}

// Example of displaying the cart contents (simplified)
echo "<br><br><h2>Cart Contents (Simplified)</h2>";
// In a real application, you'd query the database for the cart items.
// For this example, we just show a few items:
echo "<p>Product 1: Quantity 1</p>";
echo "<p>Product 2: Quantity 2</p>";


// --- End of Example Usage ---

// Important notes:
// 1.  Database Connection:  Replace the placeholder values in the $db_host, $db_user, $db_password, and $db_name variables with your actual database credentials.
// 2.  Database Schema: This code assumes you have a database table named "purchases" with the following columns:
//     - product_id (INT)
//     - quantity (INT)
//     - customer_name (VARCHAR)
//     - customer_email (VARCHAR)
//     - purchase_date (DATETIME)
// 3.  Security:  This is a simplified example for demonstration purposes. In a production environment, you *must* implement proper security measures, including:
//     - Input validation and sanitization (to prevent SQL injection)
//     - Proper error handling and logging
//     - Password hashing and authentication for user accounts
//     - Protection against Cross-Site Scripting (XSS) and Cross-Site Request Forgery (CSRF) attacks.
// 4.  Database Querying:  Replace the example product data with actual database queries to retrieve product information.
// 5.  Error Handling:  Expand the error handling to provide more informative messages to the user.
// 6.  User Interface:  This code generates a basic HTML form.  You'll need to style it and integrate it into your website's design.

?>
