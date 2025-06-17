

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize an empty cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Handle the case where the product isn't in the cart (e.g., remove from cart)
    // You could also add it with a quantity of 1 if desired
    // For simplicity, we'll just do nothing here.  A better solution would be to return
    // an error message or use an appropriate flag.
    echo "Product " . $product_id . " not found in cart."; // or handle differently
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];
    $total_item = $price * $quantity;
    $total += $total_item;

    echo "<tr>";
    echo "<td>" . $name . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total_item, 2) . "</td>";
    echo "<td><a href='cart.php?remove=" . $product_id . "'>Remove</a></td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<p><strong>Total: $" . number_format($total, 2) . "</p>";
}

// Example Usage (simulated product data)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200],
  2 => ['name' => 'Mouse', 'price' => 25],
  3 => ['name' => 'Keyboard', 'price' => 75]
];

// Simulate adding items to the cart (you would typically get this from a form submission)
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id'];  // Important: Cast to integer
  $quantity = (int)$_POST['quantity']; // Cast to integer
  addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
}

if (isset($_POST['update_quantity'])) {
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];
  updateQuantity($product_id, $quantity);
}

// Display the cart contents
displayCart();

?>
