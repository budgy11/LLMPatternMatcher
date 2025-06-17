<?php endif; ?>

</body>
</html>


<?php

// Database connection (SQLite)
$db_file = 'purchase.db';

// Ensure the database file exists.  Create it if not.
if (!file_exists($db_file)) {
    $sql = "CREATE TABLE purchases (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_name TEXT NOT NULL,
        quantity INTEGER NOT NULL,
        price REAL NOT NULL,
        purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $conn = new SQLite3($db_file);
    $conn->exec($sql);
    $conn->close();
}

// Connect to the database
$conn = new SQLite3($db_file);


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the product name
    $product_name = $_POST["product_name"];
    if (empty($product_name)) {
        $error_message = "Product name cannot be empty.";
    }

    // Validate the quantity
    $quantity = $_POST["quantity"];
    if (!is_numeric($quantity) || $quantity <= 0) {
        $error_message = "Quantity must be a positive number.";
    }

    //If no errors, insert the data
    if (empty($error_message)) {
        //Simulate a price (in a real app, you'd fetch it from a product table)
        $price = 10.00; // Example price

        $stmt = $conn->prepare("INSERT INTO purchases (product_name, quantity, price) VALUES (:product_name, :quantity, :price)");
        $stmt->bindValue(':product_name', $product_name);
        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':price', $price);
        $stmt->execute();

        $last_insert_id = $conn->lastInsertRowID();

        $error_message = "Purchase created successfully! (ID: $last_insert_id)";

    }

} else {
    $error_message = ""; // Reset error message if the form wasn't submitted
}

// Close the database connection
$conn->close();
?>
