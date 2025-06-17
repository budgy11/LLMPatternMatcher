
<!-- JavaScript for removing items (simplified example) -->
<script>
    function removeFromCart(product_id) {
        // This is a placeholder.  In a real application, this function would
        // send a request to the server (e.g., using AJAX) to remove the item
        // from the session.

        // For demonstration purposes, we'll simply display a confirmation message.
        alert("Item " + product_id + " removed (simulated).");
    }
</script>


<?php
session_start();

// Function to add item to cart
function addToCart($product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // Initialize the cart array
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to remove item from cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $newQuantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
    }
}

// Example Usage - This is where you'd handle form submissions, etc.

// Simulate a product ID
$productId = 123;
$quantityToAdd = 2;

// Add item to cart
addToCart($productId, $quantityToAdd);

// Display the cart contents
$cartContents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example of removing an item
// removeFromCart($productId);


// Example of updating quantity
// updateQuantity($productId, 5);
?>
