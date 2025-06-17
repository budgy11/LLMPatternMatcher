

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli, PDO)
// For simplicity, we'll use a placeholder for the database connection:
$db = new mysqli("localhost", "username", "password", "database_name");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Function to handle product purchase
function purchaseProduct($product_id, $quantity, $user_id) {
    // **1. Input Validation & Security**
    //  - Validate product_id, quantity, and user_id to prevent SQL injection and other vulnerabilities.
    //  - Sanitize inputs before using them in a query.
    $product_id = (int)$product_id; // Convert to integer to prevent type-based SQL injection
    $quantity = (int)$quantity;
    $user_id = (int)$user_id;

    if ($quantity <= 0) {
        return "Invalid quantity.  Quantity must be greater than 0.";
    }

    // **2. Check Product Availability**
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "Product not found.";
    }

    $product = $result->fetch_assoc();

    if ($product['stock'] < $quantity) {
        $stmt->close();
        return "Not enough stock available.";
    }

    // **3. Update Stock**
    $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("is", $quantity, $product_id);
    $stmt->execute();

    // **4. Record Purchase (Add to Order Table)**
    $sql = "INSERT INTO orders (user_id, product_id, quantity, purchase_date) VALUES (?, ?, ?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();

    // **5.  Clean up**
    $stmt->close();

    return "Purchase successful! Order ID: " . $db->insert_id;
}


// **Example Usage (Handling Form Submission - Simulate a Request)**
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $user_id = $_SESSION["user_id"]; // Assuming you have a user_id stored in session

    $purchase_result = purchaseProduct($product_id, $quantity, $user_id);
    echo "<p>" . $purchase_result . "</p>";
}
?>
