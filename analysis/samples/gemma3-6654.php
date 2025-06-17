

<?php
// Example of how you might add an item to the cart from a form:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, "Product Name", 50.00, $quantity);  // Replace "Product Name" with the actual product name
  }
}
?>
