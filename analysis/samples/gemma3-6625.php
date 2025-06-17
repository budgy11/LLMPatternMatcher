

<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart array
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // Optionally, you can update the quantity if the item already exists
  update_cart_quantity($product_id, $quantity);
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) { // Use & to modify the original array
            if ($item['product_id'] == $product_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}



// Example usage (Simulating a product selection)
$product1_id = 1;
$product1_name = "Laptop";
$product1_price = 1200;

$product2_id = 2;
$product2_name = "Mouse";
$product2_price = 25;

// Add the first item to the cart (1 quantity)
add_to_cart($product1_id, $product1_name, $product1_price);

// Add the second item to the cart (2 quantities)
add_to_cart($product2_id, $product2_name, $product2_price, 2);


// Display the cart contents
if (isset($_SESSION['cart'])) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total_price = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
  }

  echo "<p><strong>Total Price: $" . $total_price . "</strong></p>";

  // Clear the cart (Optional - for example, after purchase)
  // unset($_SESSION['cart']);
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
