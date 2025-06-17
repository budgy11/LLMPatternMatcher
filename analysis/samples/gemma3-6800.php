    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $quantity = $_POST["quantity"];
    $product = $_POST["product"];

    // Validate the data (basic example - you should enhance this)
    if (empty($name) || empty($email) || empty($quantity) || empty($product)) {
        $error = "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Process the purchase (e.g., save to a database)
        $purchase_data = array(
            "name" => $name,
            "email" => $email,
            "quantity" => $quantity,
            "product" => $product,
            "timestamp" => date("Y-m-d H:i:s")  // Add a timestamp
        );

        // Save to a file (for demonstration)
        $file = "purchases.txt";
        $data_string = json_encode($purchase_data);
        file_put_contents($file, $data_string . PHP_EOL, FILE_APPEND);  // Append to the file

        // Display a success message
        echo "<p>Your order has been placed successfully!</p>";
        echo "<p>Order Details: " . $data_string . "</p>";
    }
} else {
    // If the form wasn't submitted via POST, redirect to the form
    header("Location: purchase_form.html");
    exit();
}
?>
