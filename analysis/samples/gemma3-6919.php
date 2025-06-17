    <label for="shipping_address">Shipping Address:</label><br>
    <textarea id="shipping_address" name="shipping_address" rows="4" cols="50"></textarea><br><br>

    <label for="payment_method">Payment Method:</label><br>
    <select id="payment_method" name="payment_method">
      <option value="credit_card">Credit Card</option>
      <option value="paypal">PayPal</option>
    </select><br><br>

    <input type="submit" name="submit" value="Place Order">
  </form>

  <script>
    // Example JavaScript to update quantity (you can enhance this)
    // This is just a basic illustration
    document.querySelectorAll('input[type="number"]').forEach(input => {
      input.addEventListener('change', function() {
        // Get the product ID and new quantity from the input
        const productId = this.id.split('_')[1]; // Extract the product ID
        const newQuantity = parseInt(this.value);

        // Update the cart (replace with your database interaction)
        updateCartQuantity(productId, newQuantity);

        // Refresh the cart display (you might use AJAX to avoid a page reload)
        location.reload();
      });
    });
  </script>

</body>
</html>


<?php

// Database connection (Replace with your actual database details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to handle adding a purchase
function addPurchase($product_id, $quantity, $customer_name, $customer_email) {
  global $conn;  // Access the database connection

  // Sanitize inputs to prevent SQL injection
  $product_id = mysqli_real_escape_string($conn, $product_id);
  $quantity = mysqli_real_escape_string($conn, $quantity);
  $customer_name = mysqli_real_escape_string($conn, $customer_name);
  $customer_email = mysqli_real_escape_string($conn, $customer_email);

  // SQL query to insert the purchase
  $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email)
          VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Purchase added successfully
  } else {
    return false; // Error adding purchase
  }
}

// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];

  if (addPurchase($product_id, $quantity, $customer_name, $customer_email)) {
    echo "Purchase added successfully! Product ID: " . $product_id;
  } else {
    echo "Error adding purchase.";
  }
}

//  --- HTML Form for Purchase ---
?>
