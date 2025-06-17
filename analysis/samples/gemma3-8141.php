
</body>
</html>


<?php
session_start();

// Define cart items as an array
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        // Item already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it with quantity 1
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the total cart value
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to display the cart contents
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $item['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        echo "<form method='post'>";
        echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
        echo "<input type='number' id='quantity_" . $product_id . "' value='" . $item['quantity'] . "' min='1' max='10'>"; //Example max quantity limit
        echo "<input type='submit' value='Update'>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total:</strong> $" . number_format(calculateTotal(), 2) . "</p>";
}

// --- Example Product Data (For Demonstration) ---
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];


// --- Handle Form Submission (Add to Cart) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    $quantity = (int)$_POST['quantity'];

    if (isset($products[$product_id])) {
        addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    } else {
        echo "<p style='color:red;'>Invalid Product ID.</p>";
    }
}

// --- Display the Cart ---
displayCart();

?>
