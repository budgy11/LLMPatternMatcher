

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array if it doesn't exist
  }

  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    echo "<li>";
    echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . "<br>";
    echo "<form action=\"update_cart.php\" method=\"post\">"; // Link to update_cart.php
    echo "<input type=\"hidden\" name=\"product_id\" value=\"" . $item_id . "\">";
    echo "<input type=\"number\" name=\"quantity\" value=\"" . $item_data['quantity'] . "\">";
    echo "<input type=\"submit\" value=\"Update\">";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    $total += $item_data['price'] * $item_data['quantity'];
  }

  echo "<p><strong>Total: $" . $total . "</strong></p>";

  //  Link to checkout
  echo "<form action=\"checkout.php\" method=\"post\">";
  echo "<input type=\"hidden\" name=\"cart_items\" value=\"" . json_encode($_SESSION['cart']) . "\">";
  echo "<input type=\"submit\" value=\"Proceed to Checkout\">";
  echo "</form>";

}


// Example Usage (Simulated Product Data -  Replace with your actual database)

$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80]
];


// Simulate adding items to the cart
addToCart(1, $products[1]['name'], $products[1]['price'], 2); // Add 2 T-shirts
addToCart(2, $products[2]['name'], $products[2]['price']); // Add 1 pair of Jeans
addToCart(3, $products[3]['name'], $products[3]['price'], 1); // Add 1 pair of Shoes

// Display the cart contents
displayCart();

// To remove an item
// removeCartItem(2);

// To update quantity
// updateCartQuantity(1, 3);
?>
