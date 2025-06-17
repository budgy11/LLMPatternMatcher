    <label for="name">Item Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per Item:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Purchase">
  </form>

</body>
</html>


<?php

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the input (Important!)
    $product_name = trim($_POST["product_name"]); // Remove whitespace
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);
    $customer_name = trim($_POST["customer_name"]);

    // Basic validation
    if (empty($product_name) || empty($quantity) || empty($price) || empty($customer_name)) {
        echo "Error: All fields are required.";
    } elseif ($quantity <= 0 || $price <= 0) {
        echo "Error: Quantity and Price must be greater than zero.";
    } else {
        // Calculate the total amount
        $total_amount = $quantity * $price;

        // Display the purchase information
        echo "<h2>Purchase Order Details</h2>";
        echo "<p>Product Name: " . htmlspecialchars($product_name) . "</p>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price per Item: " . $price . "</p>";
        echo "<p>Total Amount: $" . $total_amount . "</p>";
        echo "<p>Customer Name: " . htmlspecialchars($customer_name) . "</p>";

        // In a real application, you would save this data to a database here.
        // For example:
        // $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        // $stmt = $db->prepare("INSERT INTO purchases (product_name, quantity, price, customer_name, total_amount) VALUES (:product_name, :quantity, :price, :customer_name, :total_amount)");
        // $stmt->bindParam(':product_name', $product_name);
        // $stmt->bindParam(':quantity', $quantity);
        // $stmt->bindParam(':price', $price);
        // $stmt->bindParam(':customer_name', $customer_name);
        // $stmt->bindParam(':total_amount', $total_amount);
        // $stmt->execute();
    }
} else {
    // If the form wasn't submitted, show the form again
    echo "<h2>Purchase Order</h2>";
    echo "<form action='process_purchase.php' method='post'>";
    echo "<label for='product_name'>Product Name:</label>";
    echo "<input type='text' id='product_name' name='product_name' required><br><br>";

    echo "<label for='quantity'>Quantity:</label>";
    echo "<input type='number' id='quantity' name='quantity' required><br><br>";

    echo "<label for='price'>Price per Item:</label>";
    echo "<input type='number' id='price' name='price' required><br><br>";

    echo "<label for='customer_name'>Customer Name:</label>";
    echo "<input type='text' id='customer_name' name='customer_name' required><br><br>";

    echo "<input type='submit' value='Place Order'>";
    echo "</form>";
}
?>
