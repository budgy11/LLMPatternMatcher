

<?php

// --- Configuration ---
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200, 'quantity' => 10],
    2 => ['name' => 'Mouse', 'price' => 25, 'quantity' => 50],
    3 => ['name' => 'Keyboard', 'price' => 75, 'quantity' => 30],
    4 => ['name' => 'Monitor', 'price' => 300, 'quantity' => 15]
];

$cart = []; // Array to store items in the cart
$total_amount = 0;
$order_id = rand(1000, 9999); // Generate a random order ID

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 */
function addToCart(int $product_id, int $quantity) {
    global $cart, $total_amount; // Access the global variables

    if (isset($products[$product_id])) {
        $product = $products[$product_id];

        if ($product['quantity'] >= $quantity) {
            $cart[] = $product;
            $total_amount += $product['price'] * $quantity;
            echo "Added " . $quantity . " " . $product['name'] . " to your cart.<br>";
        } else {
            echo "Not enough " . $product['name'] . " in stock.<br>";
        }
    } else {
        echo "Product ID " . $product_id . " not found.<br>";
    }
}

/**
 * Displays the contents of the cart.
 */
function displayCart() {
    global $cart;

    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . "  (Quantity: " . $item['quantity'] . ")</li>";
        }
        echo "</ul>";
        echo "<p><b>Total: $" . $total_amount . "</p>";
    }
}

/**
 * Processes the order (simulated).  In a real application, this would
 * integrate with a database or payment gateway.
 */
function processOrder() {
  global $cart, $total_amount;

  if (empty($cart)) {
    echo "<p>Your cart is empty.  Cannot process order.</p>";
    return;
  }

  echo "<h2>Order Confirmation</h2>";
  echo "<p>Order ID: " . $order_id . "</p>";
  echo "<p>Items: </p>";

  foreach ($cart as $item) {
    echo $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . "<br>";
  }

  echo "<p>Total Amount: $" . $total_amount . "</p>";
  echo "<p>Thank you for your order!</p>";

  // Clear the cart after processing (optional)
  $cart = [];
  $total_amount = 0;
}

// --- Example Usage (Handle the Purchase) ---

if (isset($_GET['add_to_cart'])) {
    $product_id = (int)$_GET['add_to_cart']; // Get product ID from GET request
    $quantity = (int)$_GET['quantity'] ?? 1; //Get quantity, default to 1 if not provided

    addToCart($product_id, $quantity);
}

if (isset($_GET['process_order'])) {
    processOrder();
}

// Display the Cart and a Button to Process the Order
displayCart();
echo "<br><br>";
echo "<a href='?process_order=true'>Process Order</a>";
?>
