
<!DOCTYPE html>
<html>
<head>
    <title>Online Shop - Purchase</title>
</head>
<body>

    <h1>Purchase Your Items</h1>

    <form method="post" action="purchase.php">
        <label for="cart_id">Cart ID:</label>
        <input type="number" id="cart_id" name="cart_id" required>
        <br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>
        <br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required>
        <br><br>

        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method">
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
        </select>
        <br><br>

        <button type="submit">Place Order</button>
    </form>

</body>
</html>


<?php

// In-Memory Product Data (Replace with Database Connection)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

$cart = []; // Array to store items in the cart

// Function to add an item to the cart
function addToCart($productId, $cart) {
    if (isset($products[$productId])) {
        $item = $products[$productId];
        $cart[] = $item;
        return true;
    }
    return false;
}

// Function to calculate the total
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'];
    }
    return $total;
}

// Handle form submission (adding to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = (int)$_POST['product_id']; // Ensure it's an integer
        addToCart($productId, $cart);
    }
}

$total = calculateTotal($cart);
?>
