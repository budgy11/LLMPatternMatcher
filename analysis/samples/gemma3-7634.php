

<?php

// Data Storage (Replace with a real database in a production environment)
$cart = [];
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
    4 => ['name' => 'Monitor', 'price' => 300],
];

// Function to add to cart
function addToCart($product_id, $quantity = 1)
{
    if (isset($products[$product_id])) {
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['name' => $products[$product_id]['name'], 'price' => $products[$product_id]['price'], 'quantity' => $quantity];
        }
    } else {
        echo "Product ID $product_id not found.";
    }
}

// Function to display the cart
function displayCart()
{
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        $product_name = $item['name'];
        $product_price = $item['price'];
        $quantity = $item['quantity'];
        $total_price = $product_price * $quantity;

        echo "<li>" . $product_name . " - $" . $product_price . " x " . $quantity . " = $" . $total_price . "</li>";
    }
    echo "</ul>";

    // Calculate total amount
    $totalAmount = 0;
    foreach ($cart as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    echo "<p><b>Total Amount: $" . number_format($totalAmount, 2) . "</p>";
}

// Function to handle the purchase (Simulated)
function processPurchase()
{
    if (empty($cart)) {
        echo "<p>Your cart is empty.  Nothing to purchase.</p>";
        return;
    }

    echo "<h2>Order Confirmation</h2>";
    echo "<p>Thank you for your purchase!</p>";

    // Simulate order processing
    echo "<p>Your order details:</p>";
    foreach ($cart as $product_id => $item) {
        $product_name = $item['name'];
        $product_price = $item['price'];
        $quantity = $item['quantity'];
        $total_price = $product_price * $quantity;

        echo "<p>" . $product_name . " x " . $quantity . " = $" . $total_price . "</p>";
    }

    // Clear the cart after purchase
    $cart = [];
    echo "<p>Your cart has been emptied.</p>";
}

// ---------------------  Handling User Input ---------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for "add_to_cart" button
    if (isset($_POST["add_to_cart"])) {
        $product_id = $_POST["product_id"];
        $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 1;
        addToCart($product_id, $quantity);
    }

    // Check for "purchase" button
    if (isset($_POST["purchase"])) {
        processPurchase();
    }
}

// ---------------------  Display the Form ---------------------

?>
