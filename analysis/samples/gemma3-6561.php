

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it with initial quantity
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name'     => $product_name,
      'price'    => $price
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
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

// Function to calculate the cart total
function calculateCartTotal() {
  $total = 0;
  if (empty($_SESSION['cart'])) {
    return 0;
  }

  foreach ($_SESSION['cart'] as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}

// Example Usage (Simulated product data)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200],
  2 => ['name' => 'Mouse', 'price' => 25],
  3 => ['name' => 'Keyboard', 'price' => 75],
];

// 1. Add an item to the cart
addToCart(1, 1, $products[1]['name'], $products[1]['price']);

// 2. Add another item to the cart
addToCart(2, 2, $products[2]['name'], $products[2]['price']);

// 3. Update the quantity of an item
updateCartItemQuantity(2, 5);


// 4. Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
foreach ($cart as $product_id => $item) {
  echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}
echo "</ul>";

// 5. Calculate and display the cart total
$total = calculateCartTotal();
echo "<p><strong>Cart Total: $" . $total . "</strong></p>";

// 6. Remove an item from the cart
removeFromCart(2);

// 7. Get and display updated cart contents
$cart = getCartContents();
echo "<p><strong>Cart Contents (After Removal):</strong></p>";
echo "<ul>";
foreach ($cart as $product_id => $item) {
  echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}
echo "</ul>";
?>
