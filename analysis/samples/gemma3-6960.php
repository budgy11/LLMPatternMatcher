

<?php

// Configuration (adjust as needed)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

$cart = []; // Array to store items and quantities

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    global $cart;

    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $product_name = $product['name'];

        if (isset($cart[$product_name])) {
            $cart[$product_name]['quantity'] += $quantity;
        } else {
            $cart[$product_name] = ['name' => $product_name, 'quantity' => $quantity, 'price' => $product['price']];
        }

        echo "<p>Added '$product_name' to cart. Quantity: " . $cart[$product_name]['quantity'] . "</p>";
    } else {
        echo "<p>Product ID '$product_id' not found.</p>";
    }
}

// Function to view the cart
function viewCart()
{
    global $cart;

    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h2>Your Cart</h2>";
        echo "<ul>";
        foreach ($cart as $item) {
            $name = $item['name'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total = $price * $quantity;
            echo "<li>$name - Quantity: $quantity - Price: $price - Total: $total</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total Cart Value:</strong> " . calculateTotal($cart) . "</p>";
    }
}

// Function to calculate the total cart value
function calculateTotal($cart)
{
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to handle form submission (adding to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = (int)$_POST['product_id']; // Cast to integer for safety
        $quantity = (int)$_POST['quantity'];

        addToCart($product_id, $quantity);
    }
}

?>
