

<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Purchase</h1>

  <form method="post" action="">
    <label for="cart_id">Cart ID:</label>
    <input type="text" id="cart_id" name="cart_id" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="shipping_address">Shipping Address:</label>
    <textarea id="shipping_address" name="shipping_address" rows="4" cols="50" required></textarea><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
      <option value="credit_card">Credit Card</option>
      <option value="paypal">PayPal</option>
    </select><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Configuration (Replace with your actual values)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'stock' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'stock' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'stock' => 20],
];

$cart = []; // This will hold the items the user wants to purchase

// --- Function to display the product catalog ---
function displayProducts() {
    echo "<h2>Available Products</h2>";
    echo "<ul>";
    foreach ($products as $id => $product) {
        echo "<li>";
        echo "<h3>" . $product['name'] . "</h3>";
        echo "Price: $" . number_format($product['price'], 2) . "<br>";
        echo "Stock: " . $product['stock'] . "<br>";
        echo "<a href='add_to_cart.php?product_id=" . $id . "'>Add to Cart</a>";
        echo "</li>";
    }
    echo "</ul>";
}


// --- Function to add a product to the cart ---
function addToCart($product_id) {
    if (isset($products[$product_id])) {
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += 1;
        } else {
            $cart[$product_id] = ['quantity' => 1, 'price' => $products[$product_id]['price']];
        }
    } else {
        echo "<p>Product ID " . $product_id . " not found.</p>";
    }
}


// --- Function to display the cart ---
function displayCart() {
    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $id => $item) {
            echo "<li>";
            echo "<h3>" . $products[$id]['name'] . "</h3>";
            echo "Price: $" . number_format($item['price'], 2) . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "<a href='remove_from_cart.php?product_id=" . $id . "'>Remove</a>";
            echo "</li>";
        }
        echo "</ul>";
    }
}

// ---  Handle requests ---
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['product_id'];
    addToCart($product_id);
}

if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['product_id'];
    remove_from_cart($product_id);
}

// --- Display the catalog and cart ---
displayProducts();
displayCart();
?>
