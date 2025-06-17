

<?php
session_start();

// Session variables for the cart
$cart = array();

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $item_price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$item_id] = array(
      'name' => $item_name,
      'price' => $item_price,
      'quantity' => $quantity
    );
  }
}


// Function to remove an item from the cart
function remove_from_cart($item_id) {
  if (isset($_SESSION['cart'][$item_id])) {
    unset($_SESSION['cart'][$item_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
  if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart items
function get_cart() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculate_total() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


// Example Usage:
// -----------------------------------------------------
// Add an item to the cart (e.g., a product with ID 1, name "T-Shirt", price 20)
add_to_cart(1, "T-Shirt", 20, 2);

// Add another item (e.g., a product with ID 2, name "Jeans", price 50)
add_to_cart(2, "Jeans", 50, 1);

// Get the cart contents
$cart_items = get_cart();
print_r($cart_items);

// Calculate the total
$total = calculate_total();
echo "Total Cart Value: $" . $total . "<br>";


// Remove an item from the cart (e.g., remove the T-Shirt)
remove_from_cart(1);

// Get the updated cart contents
$cart_items = get_cart();
print_r($cart_items);

//Calculate updated total
$total = calculate_total();
echo "Total Cart Value: $" . $total . "<br>";


//Update quantity of Jeans to 3
update_quantity(2, 3);
$cart_items = get_cart();
print_r($cart_items);

$total = calculate_total();
echo "Total Cart Value: $" . $total . "<br>";

?>
