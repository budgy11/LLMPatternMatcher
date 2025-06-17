
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Products</h1>

<form method="post" action="">
    <label for="laptop">Laptop:</label>
    <input type="number" id="laptop" name="laptop" value="1" min="1">
    <br>

    <input type="hidden" name="add_product_1" value="1">
    <br>

    <label for="mouse">Mouse:</label>
    <input type="number" id="mouse" name="mouse" value="1" min="1">
    <br>
    <input type="hidden" name="add_product_2" value="1">
    <br>

    <label for="keyboard">Keyboard:</label>
    <input type="number" id="keyboard" name="keyboard" value="1" min="1">
    <br>
    <input type="hidden" name="add_product_3" value="1">
    <br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'ecommerce';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connectDB($host, $name, $user, $password) {
    $conn = new mysqli($host, $user, $password, $name);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}

// Function to add a purchase to the database
function addPurchase($conn, $userId, $productId, $quantity) {
    $stmt = $conn->prepare("INSERT INTO purchases (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $productId, $quantity);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $stmt->close();
}

// Function to update the product quantity in the database
function updateProductQuantity($conn, $productId, $quantity) {
    $stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
    $stmt->bind_param("ii", $quantity, $productId);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $stmt->close();
}

// --- Example Usage (Simulated Request Handling) ---

// 1. Receive data from the form (e.g., via POST)
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null; // Validate and cast to integer
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;


// 2. Validate the inputs (VERY IMPORTANT!)
if (!$userId || !$productId || !$quantity) {
    echo "Error: Missing required fields.";
    exit; // Stop execution if required fields are missing
}

if ($quantity <= 0) {
    echo "Error: Quantity must be greater than 0.";
    exit;
}

// 3. Connect to the database
$conn = connectDB($db_host, $db_name, $db_user, $db_password);

// 4. Add the purchase to the database
$purchaseSuccessful = addPurchase($conn, $userId, $productId, $quantity);

// 5. Update the product quantity
if ($purchaseSuccessful) {
    $updateSuccessful = updateProductQuantity($conn, $productId, $quantity);
    if ($updateSuccessful) {
        echo "Purchase successful! Product quantity updated.";
    } else {
        echo "Purchase successful, but failed to update product quantity.";
    }
} else {
    echo "Purchase failed.";
}


// 6. Close the database connection
$conn->close();

?>
