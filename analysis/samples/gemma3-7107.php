

<?php

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the form data
    $item = $_POST["item"];
    $quantity = $_POST["quantity"];

    // Validate the input (very basic example)
    if (empty($item) || empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
        $error = "Invalid input. Please enter a valid item name and quantity.";
    } else {
        // Calculate the total price (example: $10 per item)
        $total = $quantity * 10;

        // Display the order details
        echo "<h2>Order Confirmation</h2>";
        echo "<p>Item: " . htmlspecialchars($item) . "</p>";  //Use htmlspecialchars to prevent XSS
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Total Price: $" . $total . "</p>";

        // You would typically save this order information to a database here
    }
} else {
    // If the form wasn't submitted, redirect to the form
    header("Location: purchase_form.php");
    exit();
}
?>
