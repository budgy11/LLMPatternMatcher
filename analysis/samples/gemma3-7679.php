    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database details)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle adding a purchase to the database
function addPurchase($userId, $productId, $quantity, $totalAmount) {
    $conn = connectToDatabase();

    // Prepare the SQL statement
    $sql = "INSERT INTO purchases (userId, productId, quantity, totalAmount, purchaseDate)
            VALUES (
                '$userId',
                '$productId',
                '$quantity',
                '$totalAmount',
                NOW()
            )";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to retrieve a user's purchases
function getPurchasesByUserId($userId) {
    $conn = connectToDatabase();

    $sql = "SELECT * FROM purchases WHERE userId = '$userId'";
    $result = $conn->query($sql);

    $purchases = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $purchases[] = $row;
        }
    }

    return $purchases;
}



// --- Example Usage (Simulated form submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userId = $_POST["userId"];
    $productId = $_POST["productId"];
    $quantity = $_POST["quantity"];
    $totalAmount = $_POST["totalAmount"];

    // Validate input (Basic example - enhance this!)
    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "<p>Invalid quantity. Please enter a positive number.</p>";
        exit;
    }

    // Add the purchase to the database
    if (addPurchase($userId, $productId, $quantity, $totalAmount)) {
        echo "<p>Purchase added successfully!</p>";
    } else {
        echo "<p>Error adding purchase.</p>";
    }
}



// --- Displaying Purchases (Example) ---

// Get the user's purchases (replace with your user ID)
$userPurchases = getPurchasesByUserId("123"); // Replace "123" with the actual user ID

// Display the purchases
echo "<h2>Your Purchases:</h2>";
if (count($userPurchases) > 0) {
    echo "<ul>";
    foreach ($userPurchases as $purchase) {
        echo "<li>Product ID: " . $purchase['productId'] . ", Quantity: " . $purchase['quantity'] . ", Total Amount: " . $purchase['totalAmount'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>You have no purchases yet.</p>";
}

?>
