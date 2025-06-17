

<?php

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $product = $_POST["product"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];

    // Validate the input
    if (empty($product) || empty($quantity) || empty($price)) {
        echo "<p>Please fill in all fields.</p>";
    } elseif (!is_numeric($quantity) || !is_numeric($price)) {
        echo "<p>Quantity and Price must be numeric values.</p>";
    } else {
        // Calculate the total amount
        $totalAmount = $quantity * $price;

        // Store the purchase data (in an array - for demonstration only)
        $purchaseData = [
            "product" => $product,
            "quantity" => $quantity,
            "price" => $price,
            "totalAmount" => $totalAmount,
            "timestamp" => date("Y-m-d H:i:s")
        ];

        // Display a confirmation message
        echo "<p><strong>Purchase Confirmation:</strong></p>";
        echo "<p>Product: " . $product . "</p>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price per Item: $" . $price . "</p>";
        echo "<p>Total Amount: $" . $totalAmount . "</p>";
        echo "<p>Timestamp: " . $timestamp . "</p>";

        // You would typically save this $purchaseData to a database here.
        // For example:
        // $db = new DatabaseConnection(); // Assuming you have a database class
        // $db->insertPurchase($purchaseData);
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
