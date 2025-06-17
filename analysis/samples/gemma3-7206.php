
</body>
</html>


<?php

// Configuration (Replace with your actual data)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

$cart = []; // Initialize an empty cart

// Function to add items to the cart
function addToCart($productId, $cart) {
    if (isset($products[$productId])) {
        $product = $products[$productId];
        $cart[] = $product; // Add the product details to the cart
        return true;
    } else {
        return false; // Product not found
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . " - Quantity: <input type='number' value='" . $item['quantity'] . "' min='1' max='" . $item['quantity'] . "' onchange='updateCart()' ></li>";
        }
        echo "</ul>";
    }
}

// Function to update cart quantity
function updateCart() {
    // Get the input value (updated quantity)
    $input_value = $_POST['quantity_input'];

    // Loop through the cart and update the quantity
    foreach ($cart as $key => $item) {
        if ($key == (int)$input_value) {
            $cart[$key]['quantity'] = $input_value;
            break; // Stop iterating once the item is updated
        }
    }
}


// Handle adding to cart (e.g., from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        if (addToCart($productId, $cart)) {
            echo "<p>Item added to cart.</p>";
        } else {
            echo "<p>Product not found.</p>";
        }
    }
}

// Display the cart
displayCart($cart);

?>
