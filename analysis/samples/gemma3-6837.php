    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// --- Function to connect to the database ---
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// --- Function to add an item to the cart ---
function add_to_cart($conn, $product_id, $quantity) {
    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product_name = $row["name"];
        $product_price = $row["price"];

        // Update the cart quantity or add a new item
        $sql = "UPDATE carts SET quantity = quantity + ? WHERE product_id = ?";
        if ($conn->query($sql, $quantity, $product_id) === TRUE) {
            // Optionally, you can add a success message to the user
            // echo "Item '$product_name' added to cart.";
        } else {
            // Handle error
            echo "Error updating cart: " . $conn->error;
        }
    } else {
        echo "Product with ID '$product_id' not found.";
    }

    $stmt->close();
}


// --- Function to display the cart ---
function display_cart($conn) {
    $sql = "SELECT c.product_id, p.name, p.price, c.quantity FROM carts c JOIN products p ON c.product_id = p.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Shopping Cart</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Remove</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $product_name = $row["name"];
            $product_price = $row["price"];
            $quantity = $row["quantity"];
            $total = $product_price * $quantity;

            echo "<tr>";
            echo "<td>" . $product_name . "</td>";
            echo "<td>$" . $product_price . "</td>";
            echo "<td>" . $quantity . "</td>";
            echo "<td>$" . $total . "</td>";
            echo "<td><a href='remove_from_cart.php?product_id=" . $row["product_id"] . "'>Remove</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}


// --- Function to remove an item from the cart ---
function remove_from_cart($conn, $product_id) {
    $sql = "DELETE FROM carts WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p>Item removed from cart.</p>";
    } else {
        echo "<p>Item not found in cart.</p>";
    }
    $stmt->close();
}

// --- Example Usage (This is the main part of the script - it's called when the user accesses the cart page)---

// 1. Establish database connection
$conn = connectToDatabase();

// 2. Display the cart contents
display_cart($conn);

// Close the database connection
$conn->close();

?>
