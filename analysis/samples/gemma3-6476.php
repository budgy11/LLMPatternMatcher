

<?php
session_start();

// Array to store cart items
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $cart = [
      $product_id => [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      ]
    ];
  } else {
    // Check if the item is already in the cart
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] += $quantity;
    } else {
      $cart[$product_id] = [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      ];
    }
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart contents
function get_cart_contents() {
  return $cart;
}


// Example Usage (Illustrative - This would be in a form submission handler)

//Simulate a form submission (replace with actual form handling)
if (isset($_POST['action'])) {
  if ($_POST['action'] == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    add_to_cart($product_id, $product_name, $price, $quantity);
    // You'd likely redirect the user here or display a success message
  } elseif ($_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
  } elseif ($_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($product_id, $new_quantity);
  }
}


// Output the cart contents (for demonstration)
if (!empty($_SESSION['cart'])) {
  echo "<h2>Your Cart</h2>";
  foreach ($cart as $product_id => $item) {
    echo "<h3>" . $item['name'] . "</h3>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
  }
} else {
  echo "<p>Your cart is empty.</p>";
}
?>
