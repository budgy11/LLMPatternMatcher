  </ul>

</body>
</html>


<?php

// Database connection (Replace with your actual database details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---  Purchase Functionality  ---

// Function to create a new purchase
function createPurchase($userId, $productId, $quantity) {
    global $conn;

    // Validate inputs - IMPORTANT!
    if (!$userId || !$productId || !$quantity) {
        return false; // Or throw an exception, depending on your error handling
    }

    $userId = (int)$userId; // Ensure it's an integer
    $productId = (int)$productId; // Ensure it's an integer
    $quantity = (int)$quantity;

    if ($quantity <= 0) {
        return false; // Invalid quantity
    }


    // Check if the product exists
    $productQuery = "SELECT id, name, price FROM products WHERE id = $productId";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows == 0) {
        return false; // Product doesn't exist
    }

    $product = $productResult->fetch_assoc();
    $productName = $product['name'];
    $productPrice = $product['price'];

    // Calculate total cost
    $totalCost = $productPrice * $quantity;

    // Insert into the purchases table
    $sql = "INSERT INTO purchases (userId, productId, quantity, totalCost, purchaseDate)
           VALUES ($userId, $productId, $quantity, $totalCost, NOW())";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        error_log("Error creating purchase: " . $conn->error); // Log the error
        return false;
    }
}


// --- Example Usage (Simulated Form Handling) ---

// Simulating form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST["userId"];
    $productId = $_POST["productId"];
    $quantity = $_POST["quantity"];

    //  Make the purchase
    $purchaseSuccessful = createPurchase($userId, $productId, $quantity);

    if ($purchaseSuccessful) {
        echo "<p>Purchase successful!  User ID: " . $userId . ", Product ID: " . $productId . ", Quantity: " . $quantity . "</p>";
    } else {
        echo "<p>Purchase failed.  Please try again.</p>";
    }
}


?>
