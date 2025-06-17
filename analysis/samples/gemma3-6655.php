

<?php
session_start();

// --- Product Information (For demonstration purposes) ---
$products = [
    1 => ["name" => "Laptop", "price" => 1200],
    2 => ["name" => "Mouse", "price" => 25],
    3 => ["name" => "Keyboard", "price" => 75],
];

// --- Cart Functions ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add. Defaults to 1.
 */
function addToCart(int $product_id, int $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
        ];

        // Optionally:  You might want to update the quantity if the item already exists in the cart
        // and the quantity is greater than 1.
        // if (isset($_SESSION['cart'][$product_id]['quantity'])) {
        //     $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        // }
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
    $total = 0.00;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total_item = $item['price'] * $item['quantity'];
            $total += $total_item;
        }
    }
    return $total;
}

/**
 * Clears the cart.
 */
function clearCart() {
    unset($_SESSION['cart']);
}


// ---  Handling Add to Cart Request (if this is part of a web form) ---
if (isset($_POST['add_to_cart'])) {
    //Get the product ID from the form (assuming a hidden field named 'product_id')
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0; // Sanitize input

    if($product_id > 0){
       addToCart($product_id);
    }
}

// --- Displaying the Cart ---

echo "<h1>Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $cart_item) {
        echo "<li>" . $cart_item['name'] . " - $" . number_format($cart_item['price'], 2) . " x " . $cart_item['quantity'] . " = $" . number_format($cart_item['price'] * $cart_item['quantity'], 2) . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . number_format(calculateTotal(), 2) . "</strong></p>";

    echo "<button onclick='clearCart()''>Clear Cart</button>";
}

?>
