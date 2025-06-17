        </select>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" max="10" style="width:50px;">
        <br><br>

        <button type="submit">Purchase</button>
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

//  Simulate product data (replace with your database query)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
    4 => ['id' => 4, 'name' => 'Monitor', 'price' => 300]
];

// Session management (to store the cart)
session_start();

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($products[$product_id])) {
        if (isset($_SESSION['cart'])) {
            // Check if the product already exists in the cart
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                // Product not in cart, add it
                $_SESSION['cart'][$product_id] = ['quantity' => $quantity, 'price' => $products[$product_id]['price']];
            }
        } else {
            // Cart is empty, create a new array
            $_SESSION['cart'][$product_id] = ['quantity' => $quantity, 'price' => $products[$product_id]['price']];
        }
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Calculate the total price
$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}

?>
