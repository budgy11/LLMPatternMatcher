

<?php
session_start();

// Array to store cart items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
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
  echo "<div class='cart-container'>";
  echo "<h2>Shopping Cart</h2>";

  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
      $product_name = $item['name'];
      $price = $item['price'];
      $quantity = $item['quantity'];
      $total_price = $price * $quantity;

      echo "<li>";
      echo "<strong>$product_name</strong> - $price x $quantity = $total_price";
      echo "<form method='post' action='"
          . $_SERVER['PHP_SELF'] . "'>";
      echo "<label for='quantity_$product_id'>Quantity:</label>";
      echo "<input type='number' id='quantity_$product_id' value='$quantity' name='quantity_$product_id' min='1'>";
      echo "<input type='submit' value='Update'>";
      echo "</form>";
      echo "</li>";
    }
    echo "</ul>";

    echo "<form method='post' action='"
        . $_SERVER['PHP_SELF'] . "'>";
    echo "<input type='submit' value='Remove All Items'>";
    echo "</form>";
  }

  echo "</div>";
}

// Handle form submission to update quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  foreach ($_POST as $key => $value) {
    if (substr($key, 1) == '$') { // Crude check to filter out the '$'
       $key = str_replace('$','', $key);
       if (isset($_SESSION['cart'][$key])) {
          updateCartQuantity($key, $value);
       }
    }
  }
}

// Handle remove all items
if ($_POST['action'] == 'Remove All Items') {
    unset($_SESSION['cart']);
}


// Display the cart
displayCart();
?>
