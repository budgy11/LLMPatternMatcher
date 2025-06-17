

</body>
</html>


<?php

// Database Connection (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// Function to handle the purchase process
function processPurchase($orderData) {
    // Validate data (Add more validation as needed)
    $errors = [];

    if (empty($orderData['name']) || empty($orderData['email']) || empty($orderData['quantity']) || empty($orderData['product_id'])) {
        $errors[] = "Please fill in all required fields.";
    }

    if (!filter_var($orderData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!is_numeric($orderData['quantity']) || $orderData['quantity'] <= 0) {
        $errors[] = "Quantity must be a positive number.";
    }

    if (!is_numeric($orderData['product_id']) || !is_numeric($orderData['quantity'])) {
        $errors[] = "Invalid product_id or quantity.";
    }


    if (empty($errors)) {
        // Prepare data for insertion
        $name = sanitizeInput($orderData['name']);
        $email = sanitizeInput($orderData['email']);
        $quantity = (int)$orderData['quantity'];  // Convert to integer
        $product_id = (int)$orderData['product_id'];

        // Prepare SQL statement
        $sql = "INSERT INTO orders (name, email, quantity, product_id) VALUES (:name, :email, :quantity, :product_id)";

        // Prepare statement
        $stmt = $pdo->prepare($sql);

        // Execute statement
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':quantity' => $quantity,
            ':product_id' => $product_id
        ]);

        return true; // Purchase successful
    } else {
        return false; // Purchase failed due to validation errors
    }
}


// ---  Example Usage (Form Submission Handling) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data (Sanitize and validate as needed)
    $orderData = [
        'name' => sanitizeInput($_POST["name"]),
        'email' => sanitizeInput($_POST["email"]),
        'quantity' => (int)sanitizeInput($_POST["quantity"]),
        'product_id' => (int)sanitizeInput($_POST["product_id"])
    ];


    // Process the purchase
    if (processPurchase($orderData)) {
        echo "<h2>Purchase successful! Thank you, " . $orderData['name'] . "</h2>";
        echo "<p>Your order has been placed.  You will receive a confirmation email shortly.</p>";
    } else {
        echo "<h2>Error:</h2>";
        echo "<p>Please check your form for errors.</p>";
        print_r($errors); // Display validation errors (for debugging)
    }
}

?>
