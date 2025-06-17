

<?php
session_start(); // Start the session

// Cart array to store items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $cart = array();
    }

    $cart[] = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );
    $_SESSION['cart'] = $cart;
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($cart[$key]);
                break; // Stop after removing the item
            }
        }
        $_SESSION['cart'] = $cart;
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $key => $item) {
            if ($item['id'] == $product_id) {
                $cart[$key]['quantity'] = $new_quantity;
                break;
            }
        }
        $_SESSION['cart'] = $cart;
    }
}


// Example Usage (Simulated form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        addToCart($product_id, $product_name, $price, $quantity);
    } elseif (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id_to_remove'];
        removeCartItem($product_id_to_remove);
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id_to_update'];
        $new_quantity = $_POST['new_quantity'];
        updateCartQuantity($product_id_to_update, $new_quantity);
    }
}

// Display the cart contents
if (isset($_SESSION['cart'])) {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
        $total += ($item['price'] * $item['quantity']);
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
} else {
    echo "<p>Your cart is empty.</p>";
}

?>
