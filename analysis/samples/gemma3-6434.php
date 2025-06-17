

<?php
session_start(); // Start the session

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        // Cart is empty, create an empty array
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // Increment the quantity
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Add the new item to the cart
        $_SESSION['cart'][$item_id] = [
            'name' => $item_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


// Example Usage (Demonstration - You'd typically get this data from a form or database)
//  Let's assume these products exist:
//  - Item ID: 1, Name: "T-Shirt", Price: 20
//  - Item ID: 2, Name: "Jeans", Price: 50
//  - Item ID: 3, Name: "Hat", Price: 15

// Add a T-Shirt to the cart
add_to_cart(1, "T-Shirt", 20, 2);

// Add some Jeans to the cart
add_to_cart(2, "Jeans", 50);

//Update quantity of a T-Shirt
update_cart_quantity(1, 5); // Change quantity of item with ID 1 to 5

// Remove the Hat from the cart
remove_from_cart(3);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
    }
    echo "</ul>";
}

?>
