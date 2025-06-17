
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <a href="checkout.php">Checkout</a>  <!-- Example link to checkout page -->

  <form action="cart.php" method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1">
    <button type="submit">Add to Cart</button>
  </form>

</body>
</html>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// Example Usage -  Simulated Product Data (Replace with your actual data)
$products = [
  1 => ['name' => 'Shirt', 'price' => 20],
  2 => ['name' => 'Pants', 'price' => 30],
  3 => ['name' => 'Shoes', 'price' => 50],
];


//  Simulate user interactions -  Add some items to the cart

// Add a shirt
addToCart(1, $products[1]['name'], $products[1]['price'], 2);

// Add a pair of pants
addToCart(2, $products[2]['name'], $products[2]['price'], 1);

//Update the quantity of a shirt
updateQuantity(1, 3);


// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


//Calculate the total
$total = calculateTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";
?>
