
</body>
</html>


<?php

// Database connection (Replace with your database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "root";
$db_pass = "";

// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    // Check if the product exists in the database
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Check if the item is already in the cart (using a simple key, could be improved with a hash)
        $cart_item_key = "product_" . $product_id;

        // Prepare the update query
        $update_sql = "UPDATE cart SET quantity = quantity + ? WHERE product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $quantity, $product_id);

        if ($update_stmt->execute()) {
            // Optionally, you could log the action here
            // echo "Product '$product_name' added to cart (quantity: " . $quantity . ").  Cart ID: " . $product_id . "<br>";
        } else {
            echo "Error updating cart: " . $conn->error . "<br>";
        }
    } else {
        echo "Product with ID " . $product_id . " not found in the database.<br>";
    }

    $stmt->close();
}

// Function to view the cart
function viewCart($conn) {
    $sql = "SELECT p.id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Your Shopping Cart</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Product ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $product_name = $row['name'];
            $product_price = $row['price'];
            $quantity = $row['quantity'];
            $total = $product_price * $quantity;

            echo "<tr>";
            echo "<td>" . $product_id . "</td>";
            echo "<td>" . $product_name . "</td>";
            echo "<td>$" . number_format($product_price, 2) . "</td>";
            echo "<td>" . $quantity . "</td>";
            echo "<td>$" . number_format($total, 2) . "</td>";
            echo "<td><a href='update_cart.php?product_id=" . $product_id . "&action=update'>Update</a> | <a href='update_cart.php?product_id=" . $product_id . "&action=remove'>Remove</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}


// Handle form submissions (update_cart.php will use these)
if (isset($_GET['product_id']) && isset($_GET['action'])) {
    $product_id = $_GET['product_id'];
    $action = $_GET['action'];

    if ($action == "update") {
        $quantity = $_POST['quantity']; // Assumes quantity is sent via a POST form

        if (is_numeric($quantity) && $quantity > 0) {
            addToCart($conn, $product_id, $quantity);
        } else {
            echo "Invalid quantity.  Please enter a number greater than 0.";
        }

    } elseif ($action == "remove") {
        //  Implement remove logic here.  A simple example:
        removeFromCart($conn, $product_id);
    }
}

?>
