

<?php

session_start();

// --- Cart Functionality ---

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function get_cart_contents() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculate_total() {
    $total = 0;
    if (empty($_SESSION['cart'])) {
        return $total = 0;
    }

    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Example Product Data (Replace with your database or data source) ---
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15],
];

// --- Example Usage (For demonstration purposes) ---

// 1. Add a T-Shirt to the cart
add_to_cart(1, $products[1]['name'], $products[1]['price']);

// 2. Add two pairs of Jeans to the cart
add_to_cart(2, $products[2]['name'], $products[2]['price'], 2);

// 3. Update the quantity of the T-Shirt to 3
update_cart_quantity(1, 3);

// 4. Remove the Hat from the cart
remove_from_cart(3);

// 5. Get the contents of the cart
$cart_contents = get_cart_contents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// 6. Calculate and display the total
$total = calculate_total();
echo "<p><strong>Total: $" . $total . "</strong></p>";

?>
