

<?php
session_start();

// Cart data - This could be coming from a database, a file, or user input
$cart = [
    ['id' => 1, 'name' => 'T-Shirt', 'price' => 20, 'quantity' => 2]
    ,['id' => 2, 'name' => 'Jeans', 'price' => 50, 'quantity' => 1]
];

// Function to add an item to the cart
function add_to_cart($item_id) {
    // Check if the item already exists in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Increment quantity if already in cart
            $item['quantity']++;
            // Break out of the loop - we found the item
            break;
        }
    }
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
    foreach ($cart as $key => $item) {
        if ($item['id'] == $item_id) {
            unset($cart[$key]); // Remove the item from the array
            break;
        }
    }
}

// Function to get the cart contents
function get_cart_contents() {
    return $cart;
}

// Function to calculate the cart total
function calculate_total() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// Example Usage -  Simulating user interaction
// Let's say the user adds a T-Shirt to the cart
add_to_cart(1);

// Let's say the user removes the Jeans
remove_from_cart(2);

// Get the current cart contents
$cart_contents = get_cart_contents();
$total = calculate_total();

// Print the cart contents and total
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
foreach ($cart_contents as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
}
echo "</ul>";

echo "<p><strong>Total: $" . $total . "</strong></p>";

?>
