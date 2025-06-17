

<?php
session_start(); // Start the session

// Check if item_id is set in the session.  This is critical.
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $quantity = $_GET['quantity'];

    // Check if the item already exists in the cart
    $query = "SELECT * FROM cart WHERE item_id = '$item_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Item exists, update quantity
        $query = "UPDATE cart SET quantity = quantity + $quantity WHERE item_id = '$item_id'";
        if ($conn->query($query) === TRUE) {
            echo "Item quantity updated successfully.";
        } else {
            echo "Error updating item quantity: " . $conn->error;
        }
    } else {
        // Item doesn't exist, add it to the cart
        $query = "INSERT INTO cart (item_id, quantity) VALUES ('$item_id', $quantity)";
        if ($conn->query($query) === TRUE) {
            echo "Item added to cart successfully.";
        } else {
            echo "Error adding item to cart: " . $conn->error;
        }
    }
}
?>
