</body>
</html>


<?php

// Define the cart file
$cartFile = 'cart.txt';

// Initialize the cart as an empty array if the file doesn't exist
$cart = file_exists($cartFile) ?  file($cartFile, FILE_IGNORE_NEW_LINES) : [];

// Function to add an item to the cart
function addToCart($cart, $item, $quantity = 1) {
    if (isset($cart[$item]) ) {
        $cart[$item] += $quantity;
    } else {
        $cart[$item] = $quantity;
    }
    file_put_contents($cartFile, json_encode($cart)); // Save the updated cart
}


// Function to remove an item from the cart
function removeFromCart($cart, $item) {
    unset($cart[$item]);
    file_put_contents($cartFile, json_encode($cart));
}

// Function to update the quantity of an item in the cart
function updateQuantity($cart, $item, $quantity) {
    $cart[$item] = $quantity;
    file_put_contents($cartFile, json_encode($cart));
}


// Get the requested action (add, remove, update, view)
$action = $_GET['action'];

// Handle actions
switch ($action) {
    case 'add':
        $item = $_POST['item'];
        $quantity = $_POST['quantity'] ?? 1; // Default quantity is 1
        addToCart($cart, $item, $quantity);
        break;

    case 'remove':
        $item = $_POST['item'];
        removeFromCart($cart, $item);
        break;

    case 'update':
        $item = $_POST['item'];
        $quantity = $_POST['quantity'];
        updateQuantity($cart, $item, $quantity);
        break;

    case 'view':
        // Display the cart contents
        echo "<h2>Your Shopping Cart</h2>";
        if (empty($cart)) {
            echo "<p>Your cart is empty.</p>";
        } else {
            echo "<ul>";
            foreach ($cart as $item => $quantity) {
                echo "<li>$item - Quantity: $quantity<br>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='item' value='$item'>";
                echo "<input type='number' name='quantity' value='$quantity' min='1' style='width:50px;'>";
                echo "<button type='submit' name='action' value='update'>Update</button> | <a href='?action=remove&item=$item'>Remove</a>";
                echo "</form>";
                echo "</li>";
            }
            echo "</ul>";
        }
        break;

    default:
        // Handle unknown actions (e.g., display an error)
        echo "<p>Invalid action.</p>";
}
?>
