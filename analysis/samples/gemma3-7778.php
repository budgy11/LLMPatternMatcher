

<?php
session_start(); // Start the session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($productId, $quantity = 1) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'quantity' => $quantity,
            'product_id' => $productId // Store product ID for updates
        ];
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($productId, $quantity) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    } else {
        // Handle the case where the product isn't in the cart
        // You might want to log an error or display a message
        echo "Product ID " . $productId . " not found in cart.";
    }
}


// Function to remove an item from the cart
function removeCartItem($productId) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    } else {
        echo "Product ID " . $productId . " not found in cart.";
    }
}

// Function to get the cart total
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product = getProductDetails($item['product_id']); // Get product details
        if ($product) {
            $totalItemPrice = $product['price'] * $item['quantity'];
            $total += $totalItemPrice;
        }
    }
    return $total;
}

// Function to display the cart contents
function displayCart() {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        $product = getProductDetails($item['product_id']);
        if ($product) {
            $totalItemPrice = $product['price'] * $item['quantity'];
            $totalItemPrice = $totalItemPrice;
            echo "<tr>";
            echo "<td>" . $product['name'] . "</td>";
            echo "<td>$" . number_format($product['price'], 2) . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . number_format($totalItemPrice, 2) . "</td>";
            echo "<td><a href='cart.php?action=update&productId=" . $item['product_id'] . "&quantity=1'>Update</a> | <a href='cart.php?action=remove&productId=" . $item['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "<br>";
    echo "<strong>Total: $" . number_format(getCartTotal(), 2) . "</strong>";
}
?>
