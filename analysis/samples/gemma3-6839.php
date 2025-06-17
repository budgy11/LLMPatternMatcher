

<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_pass = "your_password";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function add_to_cart($conn, $item_id, $quantity) {
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

// Function to display the cart contents
function display_cart($conn) {
    $query = "SELECT * FROM cart";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Your Shopping Cart</h2>";
        while ($row = $result->fetch_assoc()) {
            $item_id = $row['item_id'];
            $quantity = $row['quantity'];
            $item_name = get_item_name($conn, $item_id); // Call a function to retrieve item name

            echo "<tr>";
            echo "<td>" . $item_name . "</td>";
            echo "<td>" . $quantity . "</td>";
            echo "<td><a href='update_cart.php?item_id=$item_id&quantity=$quantity'>Update</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<h2>Your Shopping Cart is Empty</h2>";
    }
}

// Function to get item name by ID
function get_item_name($conn, $item_id) {
    $query = "SELECT item_name FROM items WHERE item_id = '$item_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['item_name'];
    } else {
        return "Unknown Item"; // Handle case where item doesn't exist
    }
}


// ------------------  Purchase Functionality ------------------

// Function to process the purchase
function process_purchase($conn) {
  //  You'd typically handle payment integration here.
  //  This is a placeholder.

  //  In a real application, you'd:
  //  1. Validate the purchase details (quantity, user info).
  //  2.  Integrate with a payment gateway (e.g., PayPal, Stripe).
  //  3.  Update the order status in your database.
  //  4.  Clear the cart after a successful purchase.

  // For this example, we'll just display a confirmation message.
  echo "<h2>Purchase Confirmed!</h2>";
  echo "<p>Your order has been placed.  (Payment processed in background)</p>";

  // Clear the cart after purchase
  clear_cart($conn);
}


// Function to clear the cart
function clear_cart($conn) {
    $query = "TRUNCATE TABLE cart";
    if ($conn->query($query) === TRUE) {
        echo "Cart cleared successfully.";
    } else {
        echo "Error clearing cart: " . $conn->error;
    }
}


// ------------------  Handling Purchase Requests ------------------

// Check if the purchase button was clicked
if (isset($_POST['purchase_button'])) {
    process_purchase($conn);
}

// ------------------  Updating Cart (Update Quantity) ------------------

//  Handle update quantity requests
if (isset($_POST['update_item'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($conn, $item_id, $quantity);
}



// ------------------  Initial Cart Display ------------------

// Display the cart contents
display_cart($conn);

?>
