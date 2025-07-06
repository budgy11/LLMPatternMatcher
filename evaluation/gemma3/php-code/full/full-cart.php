<?php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "ecommerce";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_product_by_id($conn, $product_id) {
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    return $product;
}

function insert_cart_item($conn, $user_id, $product_id, $quantity) {
    $sql = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
    return $conn->insert_id; // Returns the new cart_id
}

function get_cart_items($conn) {
    $sql = "SELECT p.product_name, p.price, p.image_url, c.quantity FROM carts c JOIN products p ON c.product_id = p.product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    } else {
        return [];
    }
}
?>
<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"] ?? 1; // Default to 1 if no quantity is provided

    if (get_product_by_id($conn, $product_id)) {
        $cart_id = insert_cart_item($conn, 1, $product_id, $quantity); // Using user_id 1 for simplicity.

        echo "<p>Item added to cart.  Cart ID: " . $cart_id . "</p>";
        // Redirect to cart.php
        header("Location: cart.php");
        exit();
    } else {
        echo "<p>Product not found.</p>";
    }
}
?>
<?php
require_once 'database.php';

// Cart ID (Assuming this is stored in a session, or you can use a cookie)
session_start();
if (!isset($_SESSION["cart_id"])) {
    $_SESSION["cart_id"] = null; // Initialize cart if it doesn't exist
}

$cart_items = get_cart_items($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td><img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['product_name']; ?>" width="100"></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>
                        <a href="add_to_cart.php?product_id=<?php echo $item['product_id']; ?>">Update</a> |
                        <a href="remove_from_cart.php?product_id=<?php echo $item['product_id']; ?>" >Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Total: <?php echo $total = 0; foreach ($cart_items as $item) { echo $total + ($item['price'] * $item['quantity']);} ?> </p>

    <a href="checkout.php">Checkout</a>
<?php endif; ?>

</body>
</html>
<?php
require_once 'database.php';
session_start();
$product_id = $_GET["product_id"];

$conn->query("DELETE FROM carts WHERE product_id = '$product_id'");

header("Location: cart.php");
exit();
?>
<?php
session_start();
if (!isset($_SESSION["cart_id"])) {
  echo "<p>Your cart is empty. Please add items first.</p>";
  header("Location: cart.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
<h1>Checkout</h1>

<p>Thank you for your order!  You can view your order details in your account.</p>

<a href="cart.php">Continue Shopping</a>
</body>
</html>
