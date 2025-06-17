
<!DOCTYPE html>
<html>
<head>
    <title>Online Shop</title>
</head>
<body>

<h1>Online Shop</h1>

<h2>Product: [Product Name - You'll need to add this to your database)</h2>
<img src="product_image.jpg" alt="Product Image" width="200">  <!-- Replace with actual image URL -->

<form method="post" action="">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br><br>

    <label for="shipping_address">Shipping Address:</label>
    <input type="text" id="shipping_address" name="shipping_address" required>
    <br><br>

    <button type="submit">Purchase Now!</button>
</form>

</body>
</html>


<?php

// Sample Product Data (Replace with your database)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the quantity from the form
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

    // Validate quantity
    if ($quantity <= 0) {
        $error_message = "Please enter a valid quantity (greater than zero).";
    } else {
        // Check if the product exists
        $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : null;

        if ($product_id === null || !isset($products[$product_id])) {
            $error_message = "Invalid product ID.";
        } else {
            // Calculate the total price
            $total_price = $products[$product_id]["price"] * $quantity;

            // Display the order information
            echo "<h2>Order Summary:</h2>";
            echo "<p>Product: " . $products[$product_id]["name"] . "</p>";
            echo "<p>Quantity: " . $quantity . "</p>";
            echo "<p>Total Price: $" . number_format($total_price, 2) . "</p>"; // Format to 2 decimal places
        }
    }
}
?>
