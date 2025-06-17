    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required>

    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" value="1">

    <br><br>

    <label for="item_name">Item Name:</label>
    <input type="text" id="item_name" name="item_name" placeholder="Item Name">

    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "shop_db";
$db_user = "your_username";
$db_password = "your_password";

//  Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Crucial for security!)
  $name = htmlspecialchars($_POST["name"]); // Sanitize input to prevent XSS attacks
  $email = htmlspecialchars($_POST["email"]);
  $quantity = intval($_POST["quantity"]); // Ensure quantity is an integer
  $price = floatval($_POST["price"]); // Ensure price is a float

  //  Basic input validation (Add more robust validation as needed)
  if (empty($name) || empty($email) || $quantity <= 0 || $price <= 0) {
    $error = "Please fill in all fields and ensure quantities and prices are positive.";
  } else {
    //  Simulate adding to cart
    $cart_item = array(
      "name" => $name,
      "email" => $email,
      "quantity" => $quantity,
      "price" => $price
    );

    //  Store cart item (e.g., in a session, database, or file)
    //  For simplicity, we'll use a session here.
    if (!isset($_SESSION["cart"])) {
      $_SESSION["cart"] = array();
    }
    $_SESSION["cart"][] = $cart_item;

    //  Display success message
    $success = "Item added to cart!";
  }
}
?>
