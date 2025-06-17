

<?php
session_start();

// Initialize the cart (an array to hold items)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Item exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to display the cart contents
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $item_id => $item_details) {
            echo "<li>";
            echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " x " . $item_details['quantity'];
            echo "<br>";
            echo "<form method='post'>";
            echo "<label for='quantity_" . $item_id . "'>Quantity:</label>";
            echo "<input type='number' id='quantity_" . $item_id . "' value='" . $item_details['quantity'] . "' min='1' max='" . $item_details['quantity'] . "' name='quantity_" . $item_id . "' >";
            echo "<button type='submit' name='update_quantity_" . $item_id . "'>Update</button>";
            echo "</form>";

            echo "<br>";
            echo "<form method='post'>";
            echo "<button type='submit' name='remove_" . $item_id . "'>Remove</button>";
            echo "</form>";
            echo "<br>";
        }
        echo "</ul>";

        // Calculate the total price
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item_id => $item_details) {
            $total_price += $item_details['price'] * $item_details['quantity'];
        }
        echo "<p><strong>Total: $" . $total_price . "</strong></p>";
    }
}

// Handle form submissions
if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['update_quantity'];
    $new_quantity = intval($_POST['quantity_' . $item_id]); // Make sure it's an integer
    updateQuantity($item_id, $new_quantity);
}

if (isset($_POST['remove'])) {
    $item_id = $_POST['remove'];
    removeFromCart($item_id);
}

// Display the cart
displayCart();

?>
