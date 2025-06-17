
</body>
</html>


<?php

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $product_name = $_POST["product_name"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];

    // Validate the data (VERY IMPORTANT!)
    if (empty($product_name) || empty($quantity) || empty($price)) {
        echo "Error: Please fill in all fields.";
    } elseif (!is_numeric($quantity) || !is_numeric($price)) {
        echo "Error: Quantity and Price must be numbers.";
    } else {
        // Calculate the total amount
        $total_amount = $quantity * $price;

        // Store the purchase data (in this example, an array)
        $purchases = array(
            "product_name" => $product_name,
            "quantity" => $quantity,
            "price" => $price,
            "total_amount" => $total_amount,
            "timestamp" => date("Y-m-d H:i:s")
        );

        // You would normally save this data to a database here.
        // For this example, we'll just print it.

        echo "<h2>Purchase Confirmation</h2>";
        echo "<p>Product: " . $product_name . "</p>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price per Unit: " . $price . "</p>";
        echo "<p>Total Amount: $" . $total_amount . "</p>";
        echo "<p>Order Placed at: " . $timestamp . "</p>";

        // Add more logging or confirmation actions here.
        // Example:  Send an email confirmation to the customer.
    }
} else {
    echo "Error: Form not submitted correctly.";
}
?>
