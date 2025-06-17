

<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the items from the hidden input field
  $items = json_decode(htmlspecialchars($_POST["items"]), true);

  // Process the purchase (basic example)
  echo "<h2>Checkout</h2>";
  echo "<p>You selected the following items:</p>";
  echo "<ul>";
  foreach ($items as $item) {
    echo "<li>" . $item . "</li>";
  }
  echo "</ul>";

  //  In a real application, you'd handle payment, order processing, etc.
  echo "<p>Order submitted successfully (simulated).</p>";
} else {
  // Handle errors or invalid requests (optional)
  echo "<p>Invalid request.</p>";
}
?>
