    <label for="product1_qty">T-Shirt (Quantity):</label>
    <input type="number" id="product1_qty" name="product1_qty" value="1">
    <br><br>

    <label for="product2_qty">Jeans (Quantity):</label>
    <input type="number" id="product2_qty" name="product2_qty" value="1">
    <br><br>

    <label for="product3_qty">Hat (Quantity):</label>
    <input type="number" id="product3_qty" name="product3_qty" value="1">
    <br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_username';
$dbPassword = 'your_password';

//  --  Product Data (Simulated for demonstration) --
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

//  --  Cart data (Simulated) --
$cart = [];
$cart_id = session_id();  // Use session ID as cart ID for demo
session_start();
$_SESSION['cart'] = $cart;


// Function to add to cart
function addToCart($productId, $quantity = 1)
{
    global $cart, $cart_id, $_SESSION;

    if (!isset($products[$productId])) {
        return "Product not found.";
    }

    if (!isset($cart[$productId])) {
        $cart[$productId] = ['quantity' => $quantity];
    } else {
        $cart[$productId]['quantity'] += $quantity;
    }
    session_start();
    $_SESSION['cart'] = $cart;
    return "Product added to cart.";
}


// Function to display the cart
function displayCart()
{
    session_start();
    $cart = $_SESSION['cart'];

    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $productId => $item) {
        $product = $products[$productId];
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $item['quantity'] . " = $" . ($product['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate total price
    $total = 0;
    foreach ($cart as $productId => $item) {
        $product = $products[$productId];
        $total += $product['price'] * $item['quantity'];
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";
}


//  --  Purchase Functionality (Simulated) --
function processPurchase($productId, $quantity = 1)
{
    global $products, $cart;

    if (!isset($products[$productId])) {
        return "Product not found.";
    }

    if ($quantity <= 0) {
        return "Invalid quantity. Please enter a positive number.";
    }

    if ($quantity > $products[$productId]['quantity'] ) {
        return "Insufficient stock for this product.";
    }
    
    $order_id = uniqid(); // Unique order ID
    
    //  Simulate order processing
    echo "<h2>Order Summary</h2>";
    echo "<p>Order ID: " . $order_id . "</p>";
    echo "<p>Product: " . $products[$productId]['name'] . "</p>";
    echo "<p>Quantity: " . $quantity . "</p>";
    echo "<p>Total Price: $" . ($products[$productId]['price'] * $quantity) . "</p>";
    
    //  Remove from cart
    $cart_id = session_id();
    session_start();
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    return "Order placed successfully!  Order ID: " . $order_id;
}


//  --  Example Usage (for demonstration and testing) --
// Add some products to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Validate input
    $quantity = (int)$_POST['quantity']; // Validate input
    $result = addToCart($productId, $quantity);
    echo "<p>" . $result . "</p>";
}

// Display the cart
displayCart();

// Simulate a purchase
if (isset($_POST['place_order'])) {
  $productId = (int)$_POST['product_id'];  // Validate input
  $quantity = (int)$_POST['quantity']; // Validate input
  $result = processPurchase($productId, $quantity);
  echo "<p>" . $result . "</p>";
}


?>
