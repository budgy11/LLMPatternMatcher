

<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Example Functions for Cart Operations ---

// Add item to cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Remove item from cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update item quantity in cart
function updateCartItem($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Calculate cart total
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// Display the cart contents
function displayCart() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "<h2>Your Cart is Empty</h2>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total_item = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . number_format($total_item, 2) . "</td>";
        echo "<td><a href='cart.php?remove=$product_id'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br>";
    echo "<strong>Total: $" . number_format(calculateCartTotal(), 2) . "</strong>";
}



// --- Example Usage (Handling Add to Cart Request) ---

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1

    addToCart($product_id, $product_name, $price, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}


// --- Cart Page (cart.php) ---

// Display the cart contents (as defined in the function above)
displayCart();
?>
