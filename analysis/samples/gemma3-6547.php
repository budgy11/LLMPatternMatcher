

<?php

session_start();

// Sample products (replace with your actual product data)
$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1)
{
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize an empty cart array
  }

  // Check if the product exists
  if (isset($products[$product_id])) {
    $product = $products[$product_id];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      // Add the product to the cart
      $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product_id, // Using ID as name for simplicity
        'price' => $product_id,  // Using ID as price for simplicity
        'quantity' => $quantity,
      ];
    }
  }
}

// Example usage:
// Add one T-Shirt to the cart
add_to_cart(1);

// Add two Jeans to the cart
add_to_cart(2, 2);

// Add one pair of Shoes to the cart
add_to_cart(3);

// Display the cart contents
echo "<h2>Cart Contents:</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_details) {
    echo "<li>" . $item_details['name'] . " - $" . $item_details['price'] . " (Quantity: " . $item_details['quantity'] . ")</li>";
  }
  echo "</ul>";
}


// Example: Remove an item from the cart (optional)
// Function to remove an item from the cart
function remove_from_cart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example: Remove the T-Shirt
//remove_from_cart(1);

// Display the cart contents after removal (optional)
// echo "<h2>Cart Contents After Removal:</h2>";
// if (empty($_SESSION['cart'])) {
//     echo "<p>Your cart is empty.</p>";
// } else {
//     echo "<ul>";
//     foreach ($_SESSION['cart'] as $item_id => $item_details) {
//         echo "<li>" . $item_details['name'] . " - $" . $item_details['price'] . " (Quantity: " . $item_details['quantity'] . ")</li>";
//     }
//     echo "</ul>";
// }


?>
