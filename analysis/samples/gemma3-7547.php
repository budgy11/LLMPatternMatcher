

<?php

// Configuration
$items = [
    "T-Shirt" => 19.99,
    "Jeans" => 49.99,
    "Hat" => 12.50,
    "Shoes" => 79.99,
];

$cart = []; // Array to store items and their quantities

// Function to display the shopping cart
function displayCart() {
    echo "<table border='1'>";
    echo "<tr><th>Item</th><th>Quantity</th><th>Price</th></tr>";
    foreach ($cart as $item => $quantity) {
        $price = $items[$item];
        echo "<tr><td>$item</td><td>$quantity</td><td>$price</td></tr>";
    }
    echo "</table>";
}

// Function to add an item to the cart
function addItemToCart($item, $quantity) {
    if (isset($cart[$item])) {
        $cart[$item] += $quantity;
    } else {
        $cart[$item] = $quantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($item, $quantity) {
    if (isset($cart[$item])) {
        if ($cart[$item] > $quantity) {
            $cart[$item] -= $quantity;
            if ($cart[$item] == 0) {
                unset($cart[$item]); // Remove if quantity becomes 0
            }
        } else {
            $cart[$item] = 0;
            unset($cart[$item]);
        }
    } else {
        echo "<p>Item not in cart.</p>";
    }
}


// Handle form submission (for adding items)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["item"]) && isset($_POST["quantity"])) {
        $item = htmlspecialchars($_POST["item"]);  //Sanitize input
        $quantity = intval($_POST["quantity"]); //Convert to integer

        addItemToCart($item, $quantity);
        displayCart();
    }
}

// Handle form submission (for removing items - optional)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_item"])) {
    $item = htmlspecialchars($_POST["remove_item"]);
    $quantity = intval($_POST["remove_quantity"]);
    removeItemFromCart($item, $quantity);
    displayCart();
}



// Display the shopping cart
displayCart();

?>
