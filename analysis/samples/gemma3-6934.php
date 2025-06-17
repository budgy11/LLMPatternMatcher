

<?php
session_start(); // Start the session

// Database connection (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// --- Purchase Functionality ---

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

        if ($product_id && $quantity > 0) {
            // Check if the product exists
            $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                // Update the cart
                if (isset($_SESSION['cart']) === false) {
                    $_SESSION['cart'] = array();
                }

                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = $quantity;
                }

                echo json_encode(['success' => true, 'message' => 'Product added to cart']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
        }
    }
}


// 2. View Cart
if (isset($_GET['view_cart'])) {
    $cart_items = $_SESSION['cart'];
    $total_amount = 0;

    if (empty($cart_items)) {
        echo "<h2>Your cart is empty.</h2>";
    } else {
        echo "<h2>Shopping Cart</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

        foreach ($cart_items as $product_id => $quantity) {
            // Retrieve product details
            $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $total = $product['price'] * $quantity;
                $total_amount += $total;

                echo "<tr><td>" . $product['name'] . "</td><td>$" . $product['price'] . "</td><td>" . $quantity . "</td><td>$" . $total . "</td></tr>";
            }
        }

        echo "</table>";
        echo "<p><strong>Total Amount: $" . $total_amount . "</strong></p>";
    }
}


// 3. Remove Item from Cart
if (isset($_GET['remove_item'])) {
    $product_id = filter_input(INPUT_GET, 'remove_item', FILTER_VALIDATE_INT);

    if ($product_id && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
    }
}


// 4. Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $new_quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if ($product_id && $new_quantity > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = $new_quantity;
            echo json_encode(['success' => true, 'message' => 'Quantity updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
    }
}


?>
