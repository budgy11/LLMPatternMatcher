
  <form method="post" action="cart.php">
    <input type="hidden" name="product_id" value="">
    <input type="hidden" name="action" value="add">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate inputs (Basic example - expand for real-world scenarios)
  $product_name = $_POST["product_name"];
  $quantity = (int)$_POST["quantity"];
  $price = (float)$_POST["price"];

  // Error handling (Check if required fields are present and valid)
  if (empty($product_name) || $quantity <= 0 || $price <= 0) {
    $error = "Please fill in all the fields and enter valid numbers.";
  } else {
    // Perform the purchase (Simulating a purchase - in real application, you'd handle payment gateway integration etc.)
    $total_amount = $quantity * $price;

    // Store the order data (Simulating a database insertion - replace with your actual database logic)
    $order_id = generate_order_id(); // Function to generate a unique order ID
    $order_data = [
      "order_id" => $order_id,
      "product_name" => $product_name,
      "quantity" => $quantity,
      "price" => $price,
      "total_amount" => $total_amount,
      "order_date" => date("Y-m-d H:i:s")
    ];

    //  Simulate saving to the database (Replace with your database insertion query)
    save_order($order_data);

    // Display success message
    $success_message = "Order placed successfully! Your order ID is: " . $order_id;
  }
}
?>
