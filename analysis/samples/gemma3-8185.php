

<?php
session_start();

// Configuration
$product_data = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'stock' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'stock' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'stock' => 20],
];

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart total
function getCartTotal()
{
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            if (isset($product_data[$product_id])) {
                $total_price = $product_data[$product_id]['price'] * $quantity;
                $total += $total_price;
            }
        }
    }
    return $total;
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Cast to integer
    $quantity = (int)$_POST['quantity']; // Cast to integer

    if (isset($product_data[$product_id]) && $quantity > 0) {
        addToCart($product_id, $quantity);
    }
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
}

// Display the cart
?>
