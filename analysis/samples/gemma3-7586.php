    </select>

    <br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1">

    <br>

    <button type="submit">Add to Cart</button>
</form>

<br>
<a href="cart.php">View Cart</a> (To be implemented in a real application)

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connect_to_db() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to add a product to the cart
function add_to_cart($conn, $product_id, $quantity) {
    // Check if the product already exists in the cart
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);  // "i" for integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Check if the product is already in the cart
        $cart_query = "SELECT * FROM cart WHERE product_id = ? AND quantity > 0";
        $cart_stmt = $conn->prepare($cart_query);
        $cart_stmt->bind_param("i", $product_id);
        $cart_stmt->execute();
        $cart_result = $cart_stmt->get_result();

        if ($cart_result->num_rows > 0) {
            // Product is in the cart, update the quantity
            $query = "UPDATE cart SET quantity = quantity + ? WHERE product_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $quantity, $product_id);
            $stmt->execute();
        } else {
            // Product not in cart, add it
            $query = "INSERT INTO cart (product_id, quantity) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $product_id, $quantity);
            $stmt->execute();
            echo "Product added to cart.";
        }
    } else {
        echo "Product not found.";
    }
}

// Function to view the cart
function view_cart($conn) {
    $cart_query = "SELECT p.name AS product_name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id";
    $stmt = $conn->prepare($cart_query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Shopping Cart</h2>";
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $total = $row['price'] * $row['quantity'];
            echo "<tr>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>$" . $row['price'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>$" . $total . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p><strong>Total: $" . calculate_total($conn) . "</strong></p>";
    } else {
        echo "Your cart is empty.";
    }
}


// Function to calculate the total price of the cart
function calculate_total($conn) {
    $query = "SELECT SUM(price * quantity) AS total";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// Function to remove a product from the cart
function remove_from_cart($conn, $product_id) {
    $query = "DELETE FROM cart WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Product removed from cart.";
    } else {
        echo "Product not found in cart.";
    }
}

// Example Usage (Illustrative - This is the front-end code)
// You would typically process this through a form on your webpage.

// If you want to add a product to the cart, call this:
// add_to_cart($conn, $product_id, $quantity);

// To view the cart:
// view_cart($conn);

// To remove a product:
// remove_from_cart($conn, $product_id);

// The $conn object would be passed from your PHP script to the HTML page to handle the form submission and display the cart.

// --- Database setup (simplified - you'll likely want to use a database migration tool) ---
// CREATE TABLE products (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(255) NOT NULL,
//     price DECIMAL(10, 2) NOT NULL
// );
//
// CREATE TABLE cart (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     product_id INT NOT NULL,
//     quantity INT NOT NULL,
//     FOREIGN KEY (product_id) REFERENCES products(id)
// );
?>
