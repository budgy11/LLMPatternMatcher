
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Products</h1>

<!-- Example products (replace with your actual product list) -->
<ul>
    <li>
        <form method="post" action="">
            <input type="hidden" name="product_id" value="1">
            <label for="product_name">Product 1</label>
            <input type="text" name="product_name" value="Product 1" readonly>
            <label for="product_price">Price: $10.00</label>
            <input type="number" name="quantity" value="1" min="1" max="10">
            <input type="submit" name="add_to_cart" value="Add to Cart">
        </form>
    </li>
    <li>
        <form method="post" action="">
            <input type="hidden" name="product_id" value="2">
            <label for="product_name">Product 2</label>
            <input type="text" name="product_name" value="Product 2" readonly>
            <label for="product_price">Price: $20.00</label>
            <input type="number" name="quantity" value="1" min="1" max="10">
            <input type="submit" name="add_to_cart" value="Add to Cart">
        </form>
    </li>
</ul>

<br>

<a href="?action=view_cart">View Cart</a>
</body>
</html>


<?php
session_start();

// Database connection (Replace with your actual database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['quantity' => $quantity];
        }
    } else {
        $cart[$product_id] = ['quantity' => $quantity];
    }

    $_SESSION['cart'] = json_encode($cart);
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);
        }
        $_SESSION['cart'] = json_encode($cart);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] = $quantity;
        }
        $_SESSION['cart'] = json_encode($cart);
    }
}

// Function to display the cart contents
function displayCart($conn) {
    if (!isset($_SESSION['cart'])) {
        echo "<h2>Your cart is empty.</h2>";
        return;
    }

    $cart = json_decode($_SESSION['cart'], true);
    $total_price = 0;

    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";

    foreach ($cart as $product_id => $item) {
        // Assuming you have a 'products' table with product_id and price
        $product_query = "SELECT product_name, price FROM products WHERE product_id = " . $product_id;
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product_name = $product_result->fetch_assoc()['product_name'];
            $price = $product_result->fetch_assoc()['price'];
            $item_total = $price * $item['quantity'];
            $total_price += $item_total;

            echo "<tr>";
            echo "<td>" . $product_name . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . number_format($price, 2) . "</td>";
            echo "<td>$" . number_format($item_total, 2) . "</td>";
            echo "<td><a href='update_cart.php?product_id=" . $product_id . "&quantity=" . $item['quantity'] . "'>Update</a> | <a href='remove_from_cart.php?product_id=" . $product_id . "'>Remove</a></td>";
            echo "</tr>";
        } else {
            echo "<tr><td>Error: Product not found</td><td></td><td></td><td></td><td></td></tr>";
        }
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format($total_price, 2) . "</strong></p>";
}

// ------  Handling Cart Actions  ------

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($conn, $product_id, $quantity);
    header("Location: cart.php"); // Redirect to cart page
    exit();
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateQuantity($conn, $product_id, $quantity);
    header("Location: cart.php");
    exit();
}

// Remove from Cart
if (isset($_GET['product_id'])) {
    removeFromCart($conn, $_GET['product_id']);
    header("Location: cart.php");
    exit();
}


// ------  Display Cart  ------
displayCart($conn);

// ------  Close Connection  ------
$conn->close();
?>
