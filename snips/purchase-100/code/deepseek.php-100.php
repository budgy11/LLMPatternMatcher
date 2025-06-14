
<?php
session_start();
// Connect to your database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <?php
    // Query to fetch products from database
    $sql = "SELECT id, name, price FROM products";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h3>".$row['name']."</h3>";
            echo "<p>Price: \$".$row['price']."</p>";
            echo "<button onclick=\"addToCart(".$row['id'].")\">Add to Cart</button>";
            echo "</div>";
        }
    } else {
        echo "No products found";
    }
    ?>
    
    <script>
    function addToCart(productId) {
        // Use AJAX to add item to cart
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // Update cart display
                showCart();
            }
        };
        xmlhttp.open("GET", "add_item.php?id=" + productId, true);
        xmlhttp.send();
    }
    
    function showCart() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("cart").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "get_cart.php", true);
        xmlhttp.send();
    }
    
    // Show cart initially
    showCart();
    </script>
    
    <div id="cart"></div>
</body>
</html>


<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // Increment quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Get product details
        $sql = "SELECT id, name, price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $_SESSION['cart'][$productId] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            );
        }
    }
}
?>


<?php
session_start();
include 'db_connection.php';

echo "<h2>Shopping Cart</h2>";
echo "<table>";
echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<tr>";
        echo "<td>".$item['name']."</td>";
        echo "<td>\$".$item['price']."</td>";
        echo "<td>".$item['quantity']."</td>";
        echo "<td>\$".($item['price'] * $item['quantity'])."</td>";
        echo "<td><button onclick=\"removeItem(".$id.")\">Remove</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
}
echo "</table>";
?>


<?php
session_start();
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}
?>


<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        .cart {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .product {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
    </style>
</head>
<body>

<?php
// Product list with Add to Cart buttons
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'price' => 29.99),
    array('id' => 2, 'name' => 'Product 2', 'price' => 49.99),
    array('id' => 3, 'name' => 'Product 3', 'price' => 19.99),
);

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // Increment quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Add new product to cart
        $product = array_search($productId, array_column($products, 'id'));
        $newItem = array(
            'id' => $products[$product]['id'],
            'name' => $products[$product]['name'],
            'price' => $products[$product]['price'],
            'quantity' => 1
        );
        $_SESSION['cart'][$productId] = $newItem;
    }
}
?>

<h2>Products</h2>
<div class="cart">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <img src="placeholder.jpg" alt="<?php echo $product['name']; ?>">
            <div>
                <h3><?php echo $product['name']; ?></h3>
                <p>Price: <?php echo '$'.$product['price']; ?></p>
                <form action="" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    
    <a href="view_cart.php">View Cart</a>
</div>

<?php
// Calculate and display cart total
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
}
?>

<h3>Total: <?php echo '$'.$total; ?></h3>

</body>
</html>


<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Cart</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <h2>Your Shopping Cart</h2>
    
    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="cart">
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div class="cart-item">
                    <img src="placeholder.jpg" alt="<?php echo $item['name']; ?>" style="width: 100px;">
                    <h3><?php echo $item['name']; ?></h3>
                    <p>Price: <?php echo '$'.$item['price']; ?></p>
                    <div class="quantity">
                        <form action="update_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>" style="width: 50px;">
                            <button type="submit">Update</button>
                        </form>
                    </div>
                    <form action="update_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="remove_item">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        
        <p>Total: <?php echo '$'.number_format($total, 2); ?></p>
        <a href="checkout.php">Checkout</a> | 
        <a href="index.php">Continue Shopping</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="index.php">Back to Products</a>
    <?php endif; ?>

</body>
</html>


<?php
session_start();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    
    if (isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0) {
        // Update quantity
        $_SESSION['cart'][$productId]['quantity'] = $_POST['quantity'];
    } elseif (isset($_POST['remove_item'])) {
        // Remove item from cart
        unset($_SESSION['cart'][$productId]);
    }
    
    header("Location: view_cart.php");
    exit();
}
?>


<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += ($item['price'] * $item['quantity']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>
    
    <div class="cart">
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
                <img src="placeholder.jpg" alt="<?php echo $item['name']; ?>" style="width: 100px;">
                <h3><?php echo $item['name']; ?></h3>
                <p>Price: <?php echo '$'.$item['price']; ?></p>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h3>Total Price: <?php echo '$'.number_format($total, 2); ?></h3>

    <!-- Add your checkout form here -->
    <form action="" method="POST">
        <h3>Shipping Information</h3>
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="address" placeholder="Address" required><br>
        <button type="submit">Place Order</button>
    </form>

    <?php
    // Process order submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Here you would typically connect to a database and store the order details
        // For now, just display a thank you message
        echo "<h2>Thank you for your order!</h2>";
        // Clear cart after purchase
        unset($_SESSION['cart']);
    }
    ?>

</body>
</html>


<?php
session_start();
require_once 'db_connection.php';

// Get all products from database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);

// Add product to cart
if (isset($_GET['add_to_cart'])) {
    $productId = $_GET['add_to_cart'];
    
    if (!isset($_SESSION['cart'][$productId])) {
        // Get product details from database
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        $_SESSION['cart'][$productId] = array(
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        );
    } else {
        // Increment quantity if product already exists in cart
        $_SESSION['cart'][$productId]['quantity']++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Store</title>
</head>
<body>
    <h1>Our Products</h1>
    <?php foreach ($products as $product) { ?>
        <div style="border: 1px solid #ddd; padding: 10px; margin: 5px;">
            <h3><?php echo $product['name']; ?></h3>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <?php if ($product['image_path']) { ?>
                <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>" width="100">
            <?php } ?>
            <a href="?add_to_cart=<?php echo $product['id']; ?>">Add to Cart</a>
        </div>
    <?php } ?>

    <p><a href="cart.php">View Cart</a></p>
</body>
</html>


<?php
session_start();

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (empty($_SESSION['cart'])) { ?>
        <p>Your cart is empty.</p>
    <?php } else { ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($_SESSION['cart'] as $id => $item) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>" style="width: 50px;">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="delete_item.php?id=<?php echo $id; ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
        
        <p>Total: $<?php echo number_format($total, 2); ?></p>
    <?php } ?>

    <p><a href="index.php">Continue Shopping</a></p>
</body>
</html>


<?php
session_start();
require_once 'db_connection.php';

if (isset($_POST['quantity']) && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity']));
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

header('Location: cart.php');
exit();


<?php
session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

header('Location: cart.php');
exit();


<?php
$host = 'localhost';
$username = 'root'; // Change to your database username
$password = '';      // Change to your database password
$dbname = 'shopping_cart';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get the cart
$cart = &$_SESSION['cart'];

// Function to add item to cart
function addToCart($id, $name, $price) {
    global $cart;
    
    // Check if product already in cart
    $productIndex = array_search($id, array_column($cart, 'id'));
    
    if ($productIndex === false) {
        $cart[] = array(
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    } else {
        // Increment quantity
        $cart[$productIndex]['quantity']++;
    }
}

// Function to update item quantity
function updateCart($id, $quantity) {
    global $cart;
    
    foreach ($cart as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}

// Function to remove item from cart
function removeFromCart($id) {
    global $cart;
    
    foreach ($cart as $key => $item) {
        if ($item['id'] == $id) {
            unset($cart[$key]);
            break;
        }
    }
    // Re-index the array after removal
    $cart = array_values($cart);
}

// Calculate total price
function calculateTotal() {
    global $cart;
    
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}
?>


<?php
session_start();

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['price'])) {
    include 'cart.php';
    
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    addToCart($id, $name, $price);
}

// Redirect back to product page
header('Location: product_page.php');
exit();
?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h2>Your Shopping Cart</h2>
    
    <?php if (!empty($cart)): ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($cart as $key => $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="remove_from_cart.php?id=<?php echo $item['id']; ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
            
            <tr>
                <td colspan="4">Total:</td>
                <td>$<?php echo number_format(calculateTotal(), 2); ?></td>
            </tr>
        </table>
        
        <br>
        <a href="checkout.php">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="product_page.php">Continue Shopping</a>
    <?php endif; ?>
</body>
</html>


<?php
session_start();

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    include 'cart.php';
    
    $id = $_POST['id'];
    $quantity = intval($_POST['quantity']);
    
    if ($quantity >= 1) {
        updateCart($id, $quantity);
    }
}

header('Location: view_cart.php');
exit();
?>


<?php
session_start();

if (isset($_GET['id'])) {
    include 'cart.php';
    
    $id = intval($_GET['id']);
    removeFromCart($id);
}

header('Location: view_cart.php');
exit();
?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <?php if (!empty($cart)): ?>
        <h2>Checkout</h2>
        
        <h3>Order Summary</h3>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            
            <tr>
                <td colspan="3">Total:</td>
                <td>$<?php echo number_format(calculateTotal(), 2); ?></td>
            </tr>
        </table>

        <br>
        <form action="purchase.php" method="post">
            <h3>Payment Information</h3>
            <label for="card_number">Card Number:</label><br>
            <input type="text" id="card_number" name="card_number" required><br>
            
            <label for="expiry_date">Expiry Date:</label><br>
            <input type="text" id="expiry_date" name="expiry_date" required><br>
            
            <label for="cvv">CVV:</label><br>
            <input type="text" id="cvv" name="cvv" required><br>
            
            <button type="submit">Purchase</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty. Please add items to your cart first.</p>
        <a href="product_page.php">Continue Shopping</a>
    <?php endif; ?>
</body>
</html>


<?php
session_start();

if (!empty($_SESSION['cart']) && isset($_POST['card_number']) && isset($_POST['expiry_date']) && isset($_POST['cvv'])) {
    // Here you would typically connect to a database and process the payment
    
    // For this example, we'll just display a success message
    echo "<h2>Purchase Successful!</h2>";
    
    // Clear the cart
    $_SESSION['cart'] = array();
} else {
    header('Location: checkout.php');
    exit();
}
?>


<?php
// add_to_cart.php
session_start();

if (isset($_POST['product_id']) && isset($_SESSION['cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product already exists in cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // Update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Add new product to cart
        $products = array(
            1 => array('name' => 'Laptop', 'price' => 999.99),
            2 => array('name' => 'Phone', 'price' => 699.99),
            3 => array('name' => 'Tablet', 'price' => 299.99)
        );

        $_SESSION['cart'][$productId] = array(
            'product_name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => $quantity
        );
    }
}

header("Location: cart.php");
exit();
?>


<?php
// cart.php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Your Shopping Cart</h2>
        
        <?php if (empty($_SESSION['cart'])) { ?>
            <p>Your cart is empty.</p>
        <?php } else { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $productId => $item) { ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; ?>
                        <?php $total += $subtotal; ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <form action="remove_from_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>Total:</strong></td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>

        <a href="products.php" class="btn btn-primary">Continue Shopping</a>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>


<?php
// remove_from_cart.php
session_start();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

header("Location: cart.php");
exit();
?>


<?php
// Session initialization
session_start();

// Database connection (replace with your database credentials)
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get product details
function getProductDetails($productId) {
    global $conn;
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $productId);
    mysqli_execute($stmt);
    $result = mysqli_get_result($stmt);
    return $result->fetch_assoc();
}

// Function to calculate cart total
function cartTotal() {
    global $conn;
    if (isset($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $product = getProductDetails($item['id']);
            $total += ($product['price'] * $item['quantity']);
        }
        return $total;
    } else {
        return 0;
    }
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Check if product exists
    $product = getProductDetails($productId);
    if ($product) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                break;
            }
        }
        if (!isset($_SESSION['cart'][$ productId])) {
            $_SESSION['cart'][] = array(
                'id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            );
        }
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Update item quantity
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .product-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <h1>Your Cart</h1>
            <table width="100%">
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr class="cart-item">
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <button type="submit" name="update_quantity">Update</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $item['id']; ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h3>Total: $<?php echo number_format(cartTotal(), 2); ?></h3>
            <a href="checkout.php">Checkout</a>
        <?php else: ?>
            <p>Your cart is empty!</p>
            <a href="products.php">Continue Shopping</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>


<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId) {
    global $products;
    
    // Check if product exists in products list
    if (isset($products[$productId])) {
        $product = $products[$productId];
        
        // Check if item already exists in cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity']++;
                return;
            }
        }
        
        // Add new item to cart
        $_SESSION['cart'][] = array(
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'description' => $product['description'],
            'quantity' => 1
        );
    }
}

// Function to update cart item quantity
function updateCart($productId, $newQuantity) {
    // Loop through cart items
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = max(0, intval($newQuantity));
            
            // Remove item if quantity is 0 or less
            if ($item['quantity'] <= 0) {
                removeItemFromCart($productId);
            }
            return;
        }
    }
}

// Function to remove item from cart
function removeItemFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
}

// Function to calculate total price
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Sample products list (you can connect this to your database)
$products = array(
    1 => array(
        'id' => 1,
        'name' => 'Product 1',
        'description' => 'Description of product 1',
        'price' => 29.99
    ),
    2 => array(
        'id' => 2,
        'name' => 'Product 2',
        'description' => 'Description of product 2',
        'price' => 49.99
    ),
    // Add more products as needed
);

// Display cart contents function
function displayCart() {
    $cart = $_SESSION['cart'];
    if (empty($cart)) {
        echo '<p>Your cart is empty.</p>';
        return;
    }
    
    echo '<table border="1">';
    echo '<tr><th>Product</th><th>Description</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>';
    
    foreach ($cart as $item) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['name']) . '</td>';
        echo '<td>' . htmlspecialchars($item['description']) . '</td>';
        echo '<td>$' . number_format($item['price'], 2) . '</td>';
        echo '<td><input type="text" name="quantity-' . $item['id'] . '" value="' . $item['quantity'] . '" size="3"></td>';
        echo '<td>$' . number_format(($item['price'] * $item['quantity']), 2) . '</td>';
        echo '<td><button onclick="removeFromCart(' . $item['id'] . ')">Remove</button></td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '<p>Total: $' . number_format(calculateTotal(), 2) . '</p>';
}

// Process form actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            addToCart(intval($_POST['product_id']));
            break;
        case 'update':
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'quantity-') === 0) {
                    $productId = intval(substr($key, 9));
                    updateCart($productId, $value);
                }
            }
            break;
        case 'remove':
            removeItemFromCart(intval($_POST['product_id']));
            break;
    }
}

// Example usage:
echo '<form method="post" action="">';
echo '<input type="hidden" name="action" value="add">';
echo '<select name="product_id">';
foreach ($products as $product) {
    echo '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
}
echo '</select>';
echo '<button type="submit">Add to Cart</button>';
echo '</form>';

// Display the cart
displayCart();

?>


<?php
session_start();
?>


<?php
function get_products() {
    // This is a simplified product list; in real application, you would fetch this from a database
    $products = array(
        array('id' => '1', 'name' => 'Product 1', 'price' => 29.99, 'quantity' => 5),
        array('id' => '2', 'name' => 'Product 2', 'price' => 49.99, 'quantity' => 3),
        array('id' => '3', 'name' => 'Product 3', 'price' => 19.99, 'quantity' => 10)
    );
    return $products;
}

function add_to_cart($product_id, $quantity = 1) {
    global $db;
    
    // Check if the product exists
    $products = get_products();
    $product = array_filter($products, function($p) use ($product_id) {
        return $p['id'] == $product_id;
    });
    $product = reset($product);
    
    if (empty($product)) {
        return false;
    }
    
    // Check if the product is already in the cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 0
        );
    }
    
    // Update the quantity
    $new_quantity = $_SESSION['cart'][$product_id]['quantity'] + $quantity;
    if ($new_quantity > $product['quantity']) {
        return false; // Not enough stock
    }
    
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    return true;
}

function get_cart_items() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
}

function remove_item_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    return false;
}
?>


<?php
include('functions.php');
$products = get_products();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
</head>
<body>
    <?php foreach ($products as $product): ?>
        <div style="border: 1px solid #ddd; padding: 10px; margin: 5px;">
            <h3><?php echo $product['name']; ?></h3>
            <p>Price: <?php echo '$' . number_format($product['price'], 2); ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="text" name="quantity" placeholder="Quantity" value="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
    
    <a href="view_cart.php">View Cart</a>
</body>
</html>


<?php
include('functions.php');
cart_session();

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    add_to_cart($product_id, $quantity);
}

header("Location: index.php");
exit();
?>


<?php
include('functions.php');
cart_session();
$cart_items = get_cart_items();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if (!empty($cart_items)): ?>
        <h2>Your Shopping Cart</h2>
        <?php foreach ($cart_items as $item): ?>
            <div style="border: 1px solid #ddd; padding: 10px; margin: 5px;">
                <h3><?php echo $item['name']; ?></h3>
                <p>Price: <?php echo '$' . number_format($item['price'], 2); ?></p>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
                <a href="remove_item.php?id=<?php echo $item['id']; ?>">Remove</a>
            </div>
        <?php endforeach; ?>
        
        <h3>Total: <?php echo '$' . number_format(calculate_total(), 2); ?></h3>
        <form action="checkout.php" method="post">
            <button type="submit">Checkout</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
    
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
include('functions.php');
cart_session();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    remove_item_from_cart($product_id);
}

header("Location: view_cart.php");
exit();
?>


<?php
include('functions.php');
cart_session();

if (empty(get_cart_items())) {
    header("Location: index.php");
    exit();
}

// Process the order here
// This is a simplified example; in real application, you would:
// - Validate user input
// - Check if cart is not empty
// - Check quantities against stock
// - Calculate total amount
// - Save order details to database
// - Deduct quantities from products
// - Clear the cart

header("Location: thank_you.php");
exit();
?>


<?php
include('functions.php');
cart_session();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
</head>
<body>
    <h2>Thank you for your order!</h2>
    <p>Your order has been successfully processed.</p>
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();
include('header.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .product-item { margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; }
        .cart-link { color: blue; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Product List</h1>
    
    <?php
    // Dummy products for demonstration
    $products = array(
        array('id' => 1, 'name' => 'Laptop', 'price' => 999.99),
        array('id' => 2, 'name' => 'Smartphone', 'price' => 699.99),
        array('id' => 3, 'name' => 'Tablet', 'price' => 499.99)
    );
    
    foreach ($products as $product) {
        echo "<div class='product-item'>";
        echo "<h3>{$product['name']}</h3>";
        echo "<p>Price: \${$product['price']}</p>";
        echo "<a href='add_to_cart.php?id={$product['id']}&name={$product['name']}&price={$product['price']}'>Add to Cart</a>";
        echo "</div>";
    }
    ?>
    
    <p class="cart-link"><a href="cart.php">View Cart</a></p>
</body>
</html>


<?php
session_start();
if (isset($_GET['id']) && isset($_GET['name']) && isset($_GET['price'])) {
    $item = array(
        'id' => $_GET['id'],
        'name' => $_GET['name'],
        'price' => $_GET['price']
    );
    
    if (!isset($_SESSION['cart'][$item['id']])) {
        $_SESSION['cart'][$item['id']] = array('quantity' => 1, 'name' => $item['name'], 'price' => $item['price']);
    } else {
        $_SESSION['cart'][$item['id']]['quantity']++;
    }
}
header("Location: cart.php");
exit();
?>


<?php
session_start();
if (isset($_POST['action']) && isset($_POST['id'])) {
    switch ($_POST['action']) {
        case 'update':
            if (isset($_POST['quantity'])) {
                $quantity = max(1, intval($_POST['quantity']));
                $_SESSION['cart'][$_POST['id']]['quantity'] = $quantity;
            }
            break;
            
        case 'remove':
            unset($_SESSION['cart'][$_POST['id']]);
            break;
    }
}
header("Location: cart.php");
exit();
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .cart-item { margin-bottom: 10px; padding: 5px; border: 1px solid #ddd; }
        input[type='number'] { width: 60px; }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>
    
    <?php
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item) {
            echo "<div class='cart-item'>";
            echo "<strong>{$item['name']}</strong>";
            echo "<br>Price: \${$item['price']}";
            echo "<form action='update_cart.php' method='post'>";
            echo "<input type='hidden' name='id' value='$id'>";
            echo "<input type='number' name='quantity' min='1' value='{$item['quantity']}'>";
            echo "&nbsp;";
            echo "<input type='submit' name='action' value='update'>";
            echo "</form>";
            echo "<br>Subtotal: \${$item['price'] * $item['quantity']}";
            echo "</div>";
            
            $total += $item['price'] * $item['quantity'];
        }
        echo "<h3>Total Amount: \$$total</h3>";
    } else {
        echo "Your cart is empty.";
    }
    
    if (isset($_SESSION['cart'])) {
        echo "<form action='update_cart.php' method='post'>";
        echo "<input type='submit' name='action' value='remove all'>";
        echo "</form>";
    }
    
    echo "<p><a href='index.php'>Continue Shopping</a></p>";
    ?>
</body>
</html>


<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <h1>Available Products</h1>
    
    <?php
    // Sample product data
    $products = array(
        (object)array('id' => 1, 'name' => 'Product 1', 'price' => 10.99),
        (object)array('id' => 2, 'name' => 'Product 2', 'price' => 15.99),
        (object)array('id' => 3, 'name' => 'Product 3', 'price' => 7.99)
    );
    
    foreach ($products as $product) {
        echo "<div>";
        echo "<h3>{$product->name}</h3>";
        echo "<p>Price: \${$product->price}</p>";
        echo "<a href='add_to_cart.php?id={$product->id}'>Add to Cart</a>";
        echo "</div><br>";
    }
    ?>
    
    <a href="cart.php">View Cart</a>
</body>
</html>


<?php
session_start();

// Get product ID from query string
$product_id = intval($_GET['id']);

if (isset($_SESSION['cart'])) {
    // Check if item already exists in cart
    $item_index = -1;
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $product_id) {
            $item_index = $i;
            break;
        }
    }

    if ($item_index != -1) {
        // Increment quantity
        $_SESSION['cart'][$item_index]['quantity']++;
    } else {
        // Add new item to cart
        array_push($_SESSION['cart'], array('id' => $product_id, 'quantity' => 1));
    }
} else {
    // Initialize cart with first item
    $_SESSION['cart'] = array(array('id' => $product_id, 'quantity' => 1));
}

header("Location: products.php");
exit();
?>


<?php
session_start();

require_once 'products.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php
    // Display cart contents
    if (count($_SESSION['cart']) == 0) {
        echo "<p>Your cart is empty.</p>";
    } else {
        $total = 0;
        
        echo "<form action='update_cart.php' method='post'>";
        foreach ($_SESSION['cart'] as $index => $item) {
            // Get product details from products array
            $product = null;
            foreach ($products as $prod) {
                if ($prod->id == $item['id']) {
                    $product = $prod;
                    break;
                }
            }
            
            if ($product != null) {
                echo "<div>";
                echo "<h3>{$product->name}</h3>";
                echo "<p>Price: \${$product->price}</p>";
                echo "<input type='number' name='quantity[$index]' value='{$item['quantity']}' min='1'>";
                echo "</div><br>";
            }
        }
        
        echo "<button type='submit'>Update Cart</button>";
        echo "</form>";
    }
    
    if (count($_SESSION['cart']) > 0) {
        echo "<a href='remove_from_cart.php'>Remove Selected Items</a> | ";
        echo "<a href='products.php'>Continue Shopping</a>";
    }
    
    // Calculate total price
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        foreach ($products as $product) {
            if ($product->id == $item['id']) {
                $total += $product->price * $item['quantity'];
                break;
            }
        }
    }
    
    echo "<h2>Total: \${$total}</h2>";
    ?>
</body>
</html>


<?php
session_start();

// Update quantities in cart
foreach ($_POST['quantity'] as $index => $qty) {
    $_SESSION['cart'][$index]['quantity'] = intval($qty);
}

header("Location: cart.php");
exit();
?>


<?php
session_start();

// Remove items from cart (we'll use GET for simplicity, but POST could be used instead)
foreach ($_GET['remove'] as $index) {
    unset($_SESSION['cart'][$index]);
}

$_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array

header("Location: cart.php");
exit();
?>


<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
</head>
<body>
    <h1>Product List</h1>
    <?php
        $products = array(
            array('id' => 1, 'name' => 'Product 1', 'price' => 20.00),
            array('id' => 2, 'name' => 'Product 2', 'price' => 30.00),
            array('id' => 3, 'name' => 'Product 3', 'price' => 40.00)
        );

        foreach ($products as $product) {
            echo "<div>";
            echo "<h3>".$product['name']."</h3>";
            echo "<p>Price: \$".$product['price']."</p>";
            echo "<a href='add_to_cart.php?id=".$product['id']."&name=".$product['name']."&price=".$product['price']."'>Add to Cart</a>";
            echo "</div><br/>";
        }
    ?>
    <a href="cart.php">View Cart</a>
</body>
</html>


<?php
session_start();
 
if(isset($_GET['id']) && isset($_GET['name']) && isset($_GET['price'])){
    $item = array(
        'id' => $_GET['id'],
        'name' => $_GET['name'],
        'price' => $_GET['price']
    );
     
    if(empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
     
    // Check if item already exists in cart
    $item_exists = false;
    foreach ($_SESSION['cart'] as &$item_session) {
        if($item_session['id'] == $item['id']) {
            $item_exists = true;
            break;
        }
    }
     
    if(!$item_exists) {
        array_push($_SESSION['cart'], $item);
    } else {
        // If item exists, increment quantity
        foreach ($_SESSION['cart'] as &$item_session) {
            if($item_session['id'] == $item['id']) {
                $item_session['quantity'] = isset($item_session['quantity']) ? $item_session['quantity'] + 1 : 2;
                break;
            }
        }
    }
}
 
header("Location: cart.php");
?>


<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php
        if(empty($_SESSION['cart'])) {
            echo "<h2>Your cart is empty!</h2>";
        } else {
            $total = 0;
            echo "<h2>Your Cart:</h2>";
            foreach ($_SESSION['cart'] as $item) {
                echo "<div>";
                echo "<p>".$item['name']."</p>";
                echo "<p>Price: \$".$item['price']."</p>";
                echo "<p>Quantity: <input type='number' min='1' value='".(isset($item['quantity']) ? $item['quantity'] : 1)."' style='width:50px'></p>";
                echo "<a href='remove_from_cart.php?id=".$item['id']."'>Remove</a>";
                echo "</div><br/>";
                // Calculate total
                $total += isset($item['quantity']) ? $item['quantity'] * $item['price'] : $item['price'];
            }
            echo "<h3>Total: \$".$total."</h3>";
        }
    ?>
    <a href="products.php">Continue Shopping</a> | 
    <?php if(!empty($_SESSION['cart'])) { echo "<a href='checkout.php'>Checkout</a>"; } ?>
</body>
</html>


<?php
session_start();
 
if(isset($_GET['id'])){
    foreach ($_SESSION['cart'] as $key => &$item) {
        if($item['id'] == $_GET['id']) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}
 
header("Location: cart.php");
?>


<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <?php if(!empty($_SESSION['cart'])) { ?>
        <h2>Checkout</h2>
        <form action="process_order.php" method="post">
            <div>
                <label for="name">Name:</label><br/>
                <input type="text" name="name" id="name" required /><br/>
                
                <label for="email">Email:</label><br/>
                <input type="email" name="email" id="email" required /><br/>
                
                <label for="address">Address:</label><br/>
                <textarea name="address" id="address" required ></textarea><br/>
                
                <input type="submit" value="Place Order"/>
            </div>
        </form>
    <?php } else { ?>
        <h2>Your cart is empty!</h2>
    <?php } ?>
</body>
</html>


<?php
session_start();
 
if(!empty($_SESSION['cart']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['address'])){
    // Process the order here
    // You would typically connect to a database and store the order details
    // For this example, we'll just print out the order details
    
    echo "<h2>Order Summary</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<p>".$item['name']." x ".(isset($item['quantity']) ? $item['quantity'] : 1)."</p>";
    }
     
    // Clear the cart
    unset($_SESSION['cart']);
}
 
header("Location: products.php");
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cart_db';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create products table if not exists
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10, 2),
    description TEXT,
    image VARCHAR(255)
)";

mysqli_query($conn, $sql);

// Create cart table if not exists
$sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255),
    item_id INT,
    quantity INT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_ordered BOOLEAN DEFAULT FALSE
)";

mysqli_query($conn, $sql);

// Function to add item to cart
function addToCart($item_id) {
    global $conn;
    
    // Check if the product exists
    $product_sql = "SELECT id FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $product_sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 0) {
        return false;
    }
    
    // Get current session id
    $session_id = session_id();
    
    // Check if item is already in cart
    $check_sql = "SELECT id FROM cart WHERE session_id = ? AND item_id = ? AND is_ordered = FALSE";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "si", $session_id, $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Increment quantity
        $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE session_id = ? AND item_id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "si", $session_id, $item_id);
        mysqli_stmt_execute($stmt);
    } else {
        // Add new item
        $insert_sql = "INSERT INTO cart (session_id, item_id, quantity) VALUES (?, ?, 1)";
        $stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt, "si", $session_id, $item_id);
        mysqli_stmt_execute($stmt);
    }
    
    return true;
}

// Function to get cart items
function getCartItems() {
    global $conn;
    
    // Get current session id
    $session_id = session_id();
    
    $sql = "SELECT c.id AS cart_id, p.*, c.quantity 
            FROM products p 
            JOIN cart c ON p.id = c.item_id 
            WHERE c.session_id = ? AND c.is_ordered = FALSE";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return $result;
}

// Function to update cart quantity
function updateCartQuantity($cart_id, $quantity) {
    global $conn;
    
    // Update quantity in cart
    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $cart_id);
    mysqli_stmt_execute($stmt);
    
    return true;
}

// Function to delete item from cart
function deleteCartItem($cart_id) {
    global $conn;
    
    // Delete item from cart
    $sql = "DELETE FROM cart WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $cart_id);
    mysqli_stmt_execute($stmt);
    
    return true;
}

// Function to calculate total price
function calculateTotal() {
    global $conn;
    
    // Get current session id
    $session_id = session_id();
    
    $sql = "SELECT SUM(p.price * c.quantity) AS total 
            FROM products p 
            JOIN cart c ON p.id = c.item_id 
            WHERE c.session_id = ? AND c.is_ordered = FALSE";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return $row['total'];
}

// Initialize session
if (!isset($_SESSION)) {
    session_start();
}

// Check if cart table has items for this session, 
// if not, insert a new session into the cart table
$session_id = session_id();

$sql = "SELECT id FROM cart WHERE session_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Insert new session
    $insert_sql = "INSERT INTO cart (session_id) VALUES (?)";
    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
}
?>


<?php
require_once 'cart_functions.php';

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    addToCart($_POST['product_id']);
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    updateCartQuantity($_POST['cart_id'], $_POST['quantity']);
}

// Delete item from cart
if (isset($_GET['delete_item'])) {
    deleteCartItem($_GET['cart_id']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if ($result = getCartItems()): ?>
        <h2>Your Cart</h2>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                    <td>
                        <a href="?delete_item=<?php echo $row['cart_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <h3>Total: $<?php echo number_format(calculateTotal(), 2); ?></h3>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <!-- Add items to cart -->
    <?php
    // Fetch products from database
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0):
    ?>
        <h2>Products</h2>
        <div class="products">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-item">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>$<?php echo number_format($row['price'], 2); ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</body>
</html>


<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_cart";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


<?php
require_once 'config.php';

// Add product to cart
function add_to_cart($product_id, $quantity = 1) {
    global $conn;
    
    session_start();
    $session_id = session_id();

    try {
        // Check if the item already exists in the cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND session_id = ?");
        $stmt->execute([$product_id, $session_id]);
        
        if ($stmt->rowCount() > 0) {
            // Update quantity
            $currentQuantity = $stmt->fetch()['quantity'];
            $newQuantity = $currentQuantity + $quantity;
            update_cart_item($product_id, $session_id, $newQuantity);
        } else {
            // Insert new item
            $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$session_id, $product_id, $quantity]);
        }
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Update quantity of a product in the cart
function update_cart_item($product_id, $session_id, $new_quantity) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ? AND session_id = ?");
        $stmt->execute([$new_quantity, $product_id, $session_id]);
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Get all items from the cart
function get_cart_items() {
    global $conn;
    
    session_start();
    $session_id = session_id();

    try {
        $stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ?");
        $stmt->execute([$session_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Delete an item from the cart
function delete_cart_item($item_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->execute([$item_id]);
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>


<?php
session_start();
require_once 'functions.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Products</h1>
    
    <?php
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as $product) {
        echo "
            <div class='col-md-4'>
                <div class='thumbnail'>
                    <img src='{$product['image']}' alt='{$product['name']}'>
                    <div class='caption'>
                        <h3>{$product['name']}</h3>
                        <p>{$product['description']}</p>
                        <p>\${$product['price']}</p>
                        <a href='?action=add&id={$product['id']}' class='btn btn-primary'>Add to Cart</a>
                    </div>
                </div>
            </div>";
    }
    ?>

    <br><br>
    <a href="cart.php" class="btn btn-success">View Cart</a>
</div>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    add_to_cart($_GET['id'], 1);
}
?>

</body>
</html>


<?php
session_start();
require_once 'functions.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Your Shopping Cart</h1>
    
    <?php
    $cart_items = get_cart_items();
    
    if (count($cart_items) > 0) {
        echo "
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";
        
        $total = 0;
        foreach ($cart_items as $item) {
            $product_price = $item['price'];
            $total += $product_price * $item['quantity'];
            
            echo "
                <tr>
                    <td>{$item['name']}</td>
                    <td>\${$item['price']}</td>
                    <td>
                        <form method='post' action='update_cart.php'>
                            <input type='hidden' name='id' value='{$item['id']}'>
                            <input type='number' name='quantity' min='1' value='{$item['quantity']}' style='width: 50px;'>
                            <button type='submit' class='btn btn-default'>Update</button>
                        </form>
                    </td>
                    <td>\${$product_price * $item['quantity']}</td>
                    <td>
                        <a href='delete_cart.php?id={$item['id']}' class='btn btn-danger'>Delete</a>
                    </td>
                </tr>";
        }
        
        echo "
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan='4' style='text-align: right;'>Total:</td>
                        <td>\${$total}</td>
                    </tr>
                </tfoot>
            </table>";
    } else {
        echo "<p>Your cart is empty!</p>";
    }
    ?>
    
    <?php
    if (count($cart_items) > 0) {
        echo "<a href='checkout.php' class='btn btn-success'>Checkout</a>";
    }
    ?>
    
    <br><br>
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
</div>

</body>
</html>


<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['quantity'])) {
        $item_id = $_POST['id'];
        $new_quantity = $_POST['quantity'];
        
        update_cart_item($item_id, session_id(), $new_quantity);
    }
    
    header("Location: cart.php");
    exit();
}
?>


<?php
session_start();
require_once 'functions.php';

if (isset($_GET['id'])) {
    delete_cart_item($_GET['id']);
}

header("Location: cart.php");
exit();
?>


<?php
session_start();
require_once 'functions.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Checkout</h1>
    
    <?php
    $cart_items = get_cart_items();
    if (count($cart_items) == 0) {
        header("Location: index.php");
        exit();
    }
    
    echo "
        <div class='col-md-6'>
            <h3>Shipping Information</h3>
            <form action='process_checkout.php' method='post'>
                <div class='form-group'>
                    <label>Name:</label>
                    <input type='text' name='name' class='form-control' required>
                </div>
                <div class='form-group'>
                    <label>Address:</label>
                    <input type='text' name='address' class='form-control' required>
                </div>
                <div class='form-group'>
                    <label>City:</label>
                    <input type='text' name='city' class='form-control' required>
                </div>
                <div class='form-group'>
                    <label>State:</label>
                    <input type='text' name='state' class='form-control' required>
                </div>
                <div class='form-group'>
                    <label>Zip Code:</label>
                    <input type='number' name='zip_code' class='form-control' required>
                </div>
                <button type='submit' class='btn btn-success'>Complete Checkout</button>
            </form>
        </div>
    ";
    ?>
    
    <div class='col-md-6'>
        <h3>Order Summary</h3>
        <?php
        $total = 0;
        foreach ($cart_items as $item) {
            $product_price = $item['price'];
            $total += $product_price * $item['quantity'];
            
            echo "
                <div class='row'>
                    <div class='col-md-8'>{$item['name']}</div>
                    <div class='col-md-4'>\${$product_price * $item['quantity']}</div>
                </div>";
        }
        ?>
        
        <div class='row total'>
            <div class='col-md-8' style='text-align: right;'><strong>Total:</strong></div>
            <div class='col-md-4'><strong>\${$total}</strong></div>
        </div>
    </div>

</div>

</body>
</html>


<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    
    // Here you would typically process the payment and save the order
    // For this example, we'll just empty the cart and display a success message
    
    // Empty the cart
    $session_id = session_id();
    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
        $stmt->execute([$session_id]);
        
        echo "<h1>Thank you for your order!</h1>";
        echo "<p>Your order has been processed successfully.</p>";
        echo "<a href='index.php' class='btn btn-primary'>Continue Shopping</a>";
    } catch(PDOException $e) {
        die("Error processing your order: " . $e->getMessage());
    }
}
?>


<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "shopping_cart";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
session_start();
require_once 'db_connect.php';

// Sample product data (you can fetch this from your database)
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'description' => 'Description for Product 1', 'price' => 29.99),
    array('id' => 2, 'name' => 'Product 2', 'description' => 'Description for Product 2', 'price' => 49.99),
    array('id' => 3, 'name' => 'Product 3', 'description' => 'Description for Product 3', 'price' => 19.99)
);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .product {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Welcome to Our Store</h1>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <form action="view_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="text" name="quantity" placeholder="Enter quantity" value="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>


<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add product to cart
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = array(
                'id' => $product_id,
                'quantity' => $quantity
            );
        } else {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        }
    }
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
}

// Empty cart
if (isset($_POST['empty_cart'])) {
    $_SESSION['cart'] = array();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['cart'] as $product): ?>
            <div class="cart-item">
                <p>Product ID: <?php echo $product['id']; ?></p>
                <p>Quantity: <?php echo $product['quantity']; ?></p>
                <a href="view_cart.php?remove=<?php echo $product['id']; ?>">Remove</a>
            </div>
        <?php endforeach; ?>
        
        <form action="checkout.php" method="post">
            <button type="submit">Checkout</button>
        </form>
    <?php endif; ?>

    <p><a href="index.php">Continue Shopping</a></p>
</body>
</html>


<?php
session_start();
require_once 'db_connect.php';

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Process order here (e.g., save to database, send emails, etc.)
    // For this example, we'll just display a thank you message
    
    $total_amount = 0;
    
    foreach ($_SESSION['cart'] as $product) {
        // Get product details from database
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $product['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $product_details = mysqli_fetch_assoc($result);
        
        $total_amount += $product_details['price'] * $product['quantity'];
    }
    
    // Reset cart
    $_SESSION['cart'] = array();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <?php if (isset($_SESSION['cart']) && empty($_SESSION['cart'])): ?>
        <p>Thank you for your order! Your total was $<?php echo number_format($total_amount, 2); ?>.</p>
    <?php else: ?>
        <p>Your cart is empty. Please add some products first.</p>
    <?php endif; ?>
    
    <p><a href="index.php">Back to Shopping</a></p>
</body>
</html>


<?php
// Start the session
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Product data (you can replace this with database data)
$products = array(
    1 => array('name' => 'Product 1', 'price' => 10.99),
    2 => array('name' => 'Product 2', 'price' => 15.99),
    3 => array('name' => 'Product 3', 'price' => 20.99)
);

// Function to add item to cart
function addToCart($id) {
    global $products;
    
    if (isset($products[$id])) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = array(
                'name' => $products[$id]['name'],
                'price' => $products[$id]['price'],
                'quantity' => 1
            );
        } else {
            $_SESSION['cart'][$id]['quantity']++;
        }
    }
}

// Function to update cart quantity
function updateCart($id, $quantity) {
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] = $quantity;
    }
}

// Function to delete item from cart
function deleteItem($id) {
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .product-list { width: 80%; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .cart-total { font-weight: bold; margin-top: 20px; }
        .button { padding: 8px 16px; background-color: #4CAF50; color: white; text-decoration: none; }
        .button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h2>Product List</h2>
    <div class="product-list">
        <?php foreach ($products as $id => $product): ?>
            <div style="margin-bottom: 10px;">
                <strong><?php echo $product['name']; ?></strong><br>
                Price: $<?php echo number_format($product['price'], 2); ?><br>
                <a href="#" onclick="addToCart(<?php echo $id; ?>)" class="button">Add to Cart</a>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Your Cart</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>"></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="#" onclick="deleteItem(<?php echo $id; ?>)" class="button">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="cart-total">
            Total: $<?php 
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                echo number_format($total, 2);
            ?>
        </div>
    <?php endif; ?>

    <script>
        function addToCart(id) {
            if (confirm("Add this item to cart?")) {
                window.location.href = "add.php?id=" + id;
            }
        }

        function deleteItem(id) {
            if (confirm("Delete this item from cart?")) {
                window.location.href = "delete.php?id=" + id;
            }
        }
    </script>
</body>
</html>


<?php
session_start();

if (isset($_GET['id'])) {
    addToCart($_GET['id']);
}

header("Location: index.php");
exit();
?>


<?php
session_start();

foreach ($_POST as $key => $value) {
    if (strpos($key, 'quantity') !== false) {
        list(, $id) = explode('[', $key);
        $id = str_replace(']', '', $id);
        updateCart($id, $value);
    }
}

header("Location: index.php");
exit();
?>


<?php
session_start();

if (isset($_GET['id'])) {
    deleteItem($_GET['id']);
}

header("Location: index.php");
exit();
?>


<?php
session_start();
include('db_connection.php');

// Get all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product">';
        echo '<h3>' . $row['name'] . '</h3>';
        echo '<p>Price: $' . number_format($row['price'], 2) . '</p>';
        echo '<img src="' . $row['image_path'] . '" alt="' . $row['name'] . '">';
        echo '<form action="cart.php" method="post">';
        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
        echo '<input type="number" name="quantity" min="1" max="99" value="1">';
        echo '<button type="submit">Add to Cart</button>';
        echo '</form>';
        echo '</div>';
    }
} else {
    echo "No products found.";
}

$conn->close();
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

include('db_connection.php');

// Add item to cart
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        if (array_key_exists($product_id, $_SESSION['cart'])) {
            // Update quantity
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add new product
            $sql = "SELECT name, price FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $_SESSION['cart'][$product_id] = array(
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'quantity' => $quantity
                );
            }
        }
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
}

// Display cart contents
echo '<h2>Shopping Cart</h2>';
echo '<a href="index.php">Continue Shopping</a>';

if (!empty($_SESSION['cart'])) {
    echo '<table>';
    echo '<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>';
    
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;

        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>$' . number_format($item['price'], 2) . '</td>';
        echo '<td><form method="post" action="cart.php"><input type="hidden" name="product_id" value="' . $product_id . '"><input type="number" name="quantity" min="1" max="99" value="' . $item['quantity'] . '"><button type="submit">Update</button></form></td>';
        echo '<td>$' . number_format($subtotal, 2) . '</td>';
        echo '<td><a href="cart.php?remove=' . $product_id . '">Remove</a></td>';
        echo '</tr>';
    }
    
    echo '<tr><th colspan="3">Total:</th><td>$' . number_format($total, 2) . '</td><td><a href="checkout.php">Checkout</a></td></tr>';
    echo '</table>';
} else {
    echo "Your cart is empty.";
}

$conn->close();
?>


<?php
session_start();

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

include('db_connection.php');

// Display checkout form
echo '<h2>Checkout</h2>';
echo '<form method="post" action="process_order.php">';
echo '<label for="name">Name:</label><br>';
echo '<input type="text" id="name" name="name" required><br>';
echo '<label for="email">Email:</label><br>';
echo '<input type="email" id="email" name="email" required><br>';
echo '<label for="address">Address:</label><br>';
echo '<input type="text" id="address" name="address" required><br>';
echo '<button type="submit">Place Order</button>';
echo '</form>';

$conn->close();
?>


<?php
session_start();

include('db_connection.php');

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Get user input
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];

// Insert order into database
$sql = "INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
foreach ($_SESSION['cart'] as $product_id => $item) {
    $stmt->bind_param("isi", 1, $product_id, $item['quantity']);
    $stmt->execute();
}

// Clear cart
unset($_SESSION['cart']);

echo "Order placed successfully!";
header("Location: index.php");
exit();

$conn->close();
?>


<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Welcome to Our Store</h1>
    
    <?php
    // Sample product data
    $products = array(
        array('id' => 1, 'name' => 'Product 1', 'description' => 'Description for Product 1', 'price' => 29.99),
        array('id' => 2, 'name' => 'Product 2', 'description' => 'Description for Product 2', 'price' => 49.99),
        array('id' => 3, 'name' => 'Product 3', 'description' => 'Description for Product 3', 'price' => 19.99)
    );
    
    foreach ($products as $product) {
        echo "<div class='product'>";
        echo "<h2>{$product['name']}</h2>";
        echo "<p>{$product['description']}</p>";
        echo "<p>Price: \${$product['price']}</p>";
        echo "<form action='add_to_cart.php' method='post'>";
        echo "<input type='hidden' name='id' value='{$product['id']}'>";
        echo "<input type='hidden' name='name' value='{$product['name']}'>";
        echo "<input type='hidden' name='description' value='{$product['description']}'>";
        echo "<input type='hidden' name='price' value='{$product['price']}'>";
        echo "<button type='submit'>Add to Cart</button>";
        echo "</form>";
        echo "</div>";
    }
    ?>
    
    <a href="view_cart.php">View Cart</a>
</body>
</html>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Add item to cart or update quantity
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity' => 1
        );
    }
    
    header('Location: view_cart.php');
    exit();
}
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$total_price = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <?php
                    $total = $item['price'] * $item['quantity'];
                    $total_price += $total;
                ?>
                
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['description']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                    <td>
                        <form action="remove_from_cart.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            
            <tr>
                <td colspan="4" align="right"><strong>Total:</strong></td>
                <td colspan="2">$<?php echo number_format($total_price, 2); ?></td>
            </tr>
        </table>
        
        <a href="checkout.php">Proceed to Checkout</a> |
        <a href="index.php">Continue Shopping</a>
    <?php endif; ?>
</body>
</html>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    
    header('Location: view_cart.php');
    exit();
}
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])): ?>
        <h1>Checkout</h1>
        
        <form action="process_checkout.php" method="post">
            <table border="1">
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                
                <?php
                $total_price = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total = $item['price'] * $item['quantity'];
                    $total_price += $total;
                    
                    echo "<tr>";
                    echo "<td>{$item['name']}</td>";
                    echo "<td>{$item['description']}</td>";
                    echo "<td>\${$item['price']}</td>";
                    echo "<td>{$item['quantity']}</td>";
                    echo "<td>\${$total}</td>";
                    echo "</tr>";
                }
                ?>
                
                <tr>
                    <td colspan="4" align="right"><strong>Total:</strong></td>
                    <td>\$<?php echo number_format($total_price, 2); ?></td>
                </tr>
            </table>
            
            <h2>Shipping Information</h2>
            <input type="text" name="name" placeholder="Name">
            <br><br>
            <input type="email" name="email" placeholder="Email">
            <br><br>
            <input type="text" name="address" placeholder="Address">
            <br><br>
            <button type="submit">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty. Please add items to your cart first.</p>
        <a href="index.php">Back to Shopping</a>
    <?php endif; ?>
</body>
</html>


<?php
// Initialize session
session_start();

// Function to add item to cart
function addToCart($item_id, $item_name, $price) {
    global $cart;

    // If cart doesn't exist, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if item is already in cart
    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            // Update quantity
            $_SESSION['cart'][$key]['quantity'] += 1;
            $found = true;
            break;
        }
    }

    // If item not found, add to cart
    if (!$found) {
        $_SESSION['cart'][] = array(
            'id' => $item_id,
            'name' => $item_name,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to update item quantity in cart
function updateQuantity($item_id, $new_quantity) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id && $new_quantity > 0) {
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;
        }
    }
}

// Function to delete item from cart
function deleteItem($item_id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            // Re-index array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

// Function to calculate total items in cart
function get_total_items() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'];
    }
    return $total;
}

// Function to calculate total price of items in cart
function get_total_price() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Sample usage:
// Add item to cart
addToCart(1, 'Product 1', 19.99);
addToCart(2, 'Product 2', 29.99);

// Update quantity of item with id 1 to 3
updateQuantity(1, 3);

// Delete item from cart
deleteItem(2);

// Get total items and price
echo "Total Items: " . get_total_items(); // Output: Total Items: 3
echo "Total Price: $" . number_format(get_total_price(), 2); // Output: Total Price: $59.97

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <!-- Display cart contents -->
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <h2>Your Shopping Cart</h2>
        <table border="1">
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><input type="number" value="<?php echo $item['quantity']; ?>" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)"></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><button onclick="deleteItem(<?php echo $item['id']; ?>)">Delete</button></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3>Total: $<?php echo number_format(get_total_price(), 2); ?></h3>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <!-- Add items to cart -->
    <h2>Available Products</h2>
    <a href="#" onclick="addToCart(1, 'Product 1', 19.99)">Add Product 1 ($19.99)</a><br>
    <a href="#" onclick="addToCart(2, 'Product 2', 29.99)">Add Product 2 ($29.99)</a>

    <!-- Include JavaScript for dynamic updates -->
    <script>
        function addToCart(id, name, price) {
            fetch('cart.php?action=add&id=' + id + '&name=' + name + '&price=' + price)
                .then(response => response.text())
                .then(data => {
                    document.location.reload();
                });
        }

        function updateQuantity(id, quantity) {
            if (quantity > 0) {
                fetch('cart.php?action=update&id=' + id + '&quantity=' + quantity)
                    .then(response => response.text())
                    .then(data => {
                        document.location.reload();
                    });
            }
        }

        function deleteItem(id) {
            fetch('cart.php?action=delete&id=' + id)
                .then(response => response.text())
                .then(data => {
                    document.location.reload();
                });
        }
    </script>
</body>
</html>


<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "cart_db";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create products table if not exists
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2),
    stock INT(11)
)";

mysqli_query($conn, $sql);

// Create orders table if not exists
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255),
    product_id INT(11),
    quantity INT(11),
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

mysqli_query($conn, $sql);

// Add sample products to database
$sql = "INSERT INTO products (name, description, price, stock) 
        VALUES ('Product 1', 'Description of Product 1', 29.99, 10),
               ('Product 2', 'Description of Product 2', 49.99, 5),
               ('Product 3', 'Description of Product 3', 19.99, 20)";

mysqli_query($conn, $sql);

// Start session
session_start();

// Add to cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart') {
    $product_id = intval($_GET['product_id']);
    $quantity = intval($_GET['quantity']);

    if ($quantity > 0) {
        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $sql = "SELECT name, price FROM products WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $_SESSION['cart'][$product_id] = array(
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'quantity' => $quantity
                );
            }
        }
    }
}

// View cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'view_cart') {
    if (!empty($_SESSION['cart'])) {
        echo "<h2>Your Cart</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

        foreach ($_SESSION['cart'] as $product_id => $item) {
            $total = $item['price'] * $item['quantity'];
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>$$item[price]</td>";
            echo "<td>$item[quantity]</td>";
            echo "<td>$$total</td>";
            echo "</tr>";
        }

        // Calculate cart total
        $cart_total = 0;
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $cart_total += $item['price'] * $item['quantity'];
        }
        echo "<tr><td colspan='3'>Cart Total:</td><td>$$cart_total</td></tr>";
        echo "</table>";
    } else {
        echo "Your cart is empty!";
    }
}

// Checkout functionality
if (isset($_GET['action']) && $_GET['action'] == 'checkout') {
    if (!empty($_SESSION['cart'])) {
        $user_id = session_id();

        foreach ($_SESSION['cart'] as $product_id => $item) {
            $sql = "INSERT INTO orders (user_id, product_id, quantity)
                    VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "isi", $user_id, $product_id, $item['quantity']);
            mysqli_stmt_execute($stmt);
        }

        // Clear cart after checkout
        unset($_SESSION['cart']);
        echo "Thank you for your purchase!";
    } else {
        echo "Your cart is empty!";
    }
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove_item') {
    $product_id = intval($_GET['product_id']);
    if (!empty($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        echo "Item removed from cart!";
    }
}

// Display products
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

echo "<h1>Products</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Add to Cart</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td>$$row[price]</td>";
    echo "<td>" . $row['stock'] . "</td>";
    echo "<td><a href='?action=add_to_cart&product_id=" . $row['id'] . "&quantity=1'>Add to Cart</a></td>";
    echo "</tr>";
}

echo "</table>";

// Display cart link
echo "<br />";
echo "<a href='?action=view_cart'>View Cart</a> | ";
echo "<a href='?action=checkout'>Checkout</a>";
?>


<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_cart";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
require_once 'db.php';

$products = array(
    array(
        'name' => 'Product 1',
        'price' => 29.99,
        'description' => 'Description for Product 1',
        'image' => 'product1.jpg'
    ),
    array(
        'name' => 'Product 2',
        'price' => 49.99,
        'description' => 'Description for Product 2',
        'image' => 'product2.jpg'
    )
);

foreach ($products as $product) {
    $sql = "INSERT INTO products (name, price, description, image) 
            VALUES ('{$product['name']}', {$product['price']}, '{$product['description']}', '{$product['image']}')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Product added successfully<br>";
    } else {
        echo "Error: " . mysqli_error($conn) . "<br>";
    }
}

mysqli_close($conn);
?>


<?php
session_start();
require_once 'db.php';

// Check if user is logged in, otherwise use session ID as temporary identifier
if (!isset($_SESSION['user_id'])) {
    $_SESSION['cart_identifier'] = session_id();
} else {
    $_SESSION['cart_identifier'] = $_SESSION['user_id'];
}

$cartIdentifier = $_SESSION['cart_identifier'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
        }
        img {
            max-width: 200px;
        }
        .cart-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<h1>Products</h1>
<?php
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        echo "<img src='{$row['image']}' alt='{$row['name']}'>";
        echo "<h3>{$row['name']}</h3>";
        echo "<p>Price: \${$row['price']}</p>";
        echo "<a href='add_to_cart.php?product_id={$row['id']}'>Add to Cart</a>";
        echo "</div>";
    }
} else {
    echo "No products found.";
}

mysqli_close($conn);
?>

<h2>Your Cart</h2>
<div class="cart-container">
    <?php
    $sql = "SELECT c.id AS cart_id, p.*, c.quantity 
            FROM products p 
            JOIN cart c ON p.id = c.product_id 
            WHERE c.user_id = {$cartIdentifier}";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<img src='{$row['image']}' alt='{$row['name']}'>";
            echo "<h3>{$row['name']}</h3>";
            echo "<p>Quantity: {$row['quantity']}</p>";
            echo "<p>Total: \${$row['price'] * $row['quantity']}</p>";
            echo "<a href='remove_from_cart.php?cart_id={$row['cart_id']}'>Remove</a>";
            echo "</div>";
        }
    } else {
        echo "Your cart is empty.";
    }

    mysqli_close($conn);
    ?>
</div>

</body>
</html>


<?php
session_start();
require_once 'db.php';

if (!isset($_GET['product_id'])) {
    header("Location: index.php");
    exit();
}

$productId = $_GET['product_id'];
$userIdentifier = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : session_id();

$sql = "INSERT INTO cart (user_id, product_id, quantity) 
        VALUES ($userIdentifier, $productId, 1)
        ON DUPLICATE KEY UPDATE quantity = quantity + 1";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Product added to cart');</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
header("Location: index.php");
?>


<?php
session_start();
require_once 'db.php';

if (!isset($_GET['cart_id'])) {
    header("Location: index.php");
    exit();
}

$cartId = $_GET['cart_id'];
$sql = "DELETE FROM cart WHERE id = $cartId";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Product removed from cart');</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
header("Location: index.php");
?>


<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <?php
        // Sample products array
        $products = [
            ['id' => 1, 'name' => 'Product A', 'price' => 29.99],
            ['id' => 2, 'name' => 'Product B', 'price' => 49.99],
            // Add more products as needed
        ];
    ?>
    
    <?php foreach ($products as $product): ?>
        <div>
            <h3><?php echo $product['name']; ?></h3>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <a href="add_to_cart.php?id=<?php echo $product['id']; ?>">Add to Cart</a>
        </div>
    <?php endforeach; ?>
    
    <a href="cart.php">View Cart</a>
</body>
</html>


<?php session_start(); ?>

<?php
    // Retrieve product ID from GET request
    $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Sample products array (in a real application, fetch from database)
    $products = [
        ['id' => 1, 'name' => 'Product A', 'price' => 29.99],
        ['id' => 2, 'name' => 'Product B', 'price' => 49.99],
        // Add more products as needed
    ];
    
    // Check if product exists
    $product = array_search($productId, array_column($products, 'id'));
    if ($product !== false) {
        $productData = $products[$product];
        
        // Initialize cart if not set
        $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        
        // Check if item is already in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $productData['id'],
                'name' => $productData['name'],
                'price' => $productData['price'],
                'quantity' => 1
            ];
        }
    }
    
    // Redirect back to products page
    header('Location: index.php');
    exit();
?>


<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])): ?>
        <h2>Your Shopping Cart</h2>
        <form action="update_cart.php" method="post">
            <table border="1">
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1"></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="remove_from_cart.php?id=<?php echo $item['id']; ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
                
            </table>
            
            <?php
                // Calculate cart total
                $cartTotal = array_sum(array_map(function($item) {
                    return $item['price'] * $item['quantity'];
                }, $_SESSION['cart']));
            ?>
            
            <h3>Cart Total: $<?php echo number_format($cartTotal, 2); ?></h3>
            <button type="submit">Update Cart</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
    
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php session_start(); ?>

<?php
    if (!empty($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $productId => $qty) {
            // Ensure quantity is an integer and at least 1
            $qty = max(1, (int)$qty);
            
            $_SESSION['cart'][$productId]['quantity'] = $qty;
        }
    }
    
    // Redirect back to cart page
    header('Location: cart.php');
    exit();
?>


<?php session_start(); ?>

<?php
    if (isset($_GET['id'])) {
        $productId = (int)$_GET['id'];
        
        // Remove item from cart
        unset($_SESSION['cart'][$productId]);
        
        // Re-index the array to maintain consistency
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    
    // Redirect back to cart page
    header('Location: cart.php');
    exit();
?>


<?php
// Start the session
session_start();

// Initialize product array
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'description' => 'Description for Product 1', 'price' => 29.99),
    array('id' => 2, 'name' => 'Product 2', 'description' => 'Description for Product 2', 'price' => 49.99),
    array('id' => 3, 'name' => 'Product 3', 'description' => 'Description for Product 3', 'price' => 19.99),
    array('id' => 4, 'name' => 'Product 4', 'description' => 'Description for Product 4', 'price' => 39.99)
);

// Function to add item to cart
function addToCart($product_id) {
    global $products;
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if product exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        // Add new product to cart
        foreach ($products as $product) {
            if ($product['id'] == $product_id) {
                $_SESSION['cart'][] = array(
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'quantity' => 1
                );
            }
        }
    }
}

// Function to update cart item quantity
function updateQuantity($item_index, $new_quantity) {
    if (isset($_SESSION['cart'][$item_index])) {
        $_SESSION['cart'][$item_index]['quantity'] = max(1, $new_quantity);
    }
}

// Function to delete item from cart
function deleteItem($item_index) {
    if (isset($_SESSION['cart'][$item_index])) {
        unset($_SESSION['cart'][$item_index]);
        // Re-index the array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            if (isset($_GET['id'])) {
                addToCart($_GET['id']);
            }
            break;
        case 'update':
            if (isset($_GET['index']) && isset($_GET['quantity'])) {
                updateQuantity($_GET['index'], $_GET['quantity']);
            }
            break;
        case 'delete':
            if (isset($_GET['index'])) {
                deleteItem($_GET['index']);
            }
            break;
    }
}

// Function to display cart items
function displayCart() {
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        echo '<h2>Your Cart</h2>';
        echo '<table border="1">';
        echo '<tr><th>Product Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>';
        
        foreach ($_SESSION['cart'] as $index => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            echo '<tr>';
            echo '<td>' . $item['name'] . '</td>';
            echo '<td>' . $item['description'] . '</td>';
            echo '<td>$' . number_format($item['price'], 2) . '</td>';
            echo '<td><input type="number" value="' . $item['quantity'] . '" min="1"></td>';
            echo '<td>$' . number_format($subtotal, 2) . '</td>';
            echo '<td><a href="?action=delete&index=' . $index . '">Delete</a></td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '<h3>Total: $' . number_format($total, 2) . '</h3>';
    } else {
        echo '<p>Your cart is empty!</p>';
    }
}

// Function to display products
function displayProducts() {
    global $products;
    
    echo '<h2>Available Products</h2>';
    echo '<div class="product-grid">';
    foreach ($products as $product) {
        echo '<div class="product-item">';
        echo '<img src="placeholder-image.jpg" alt="' . $product['name'] . '">';
        echo '<h3>' . $product['name'] . '</h3>';
        echo '<p>' . $product['description'] . '</p>';
        echo '<p>$' . number_format($product['price'], 2) . '</p>';
        echo '<a href="?action=add&id=' . $product['id'] . '" class="add-to-cart">Add to Cart</a>';
        echo '</div>';
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .product-item {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        .add-to-cart {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php displayProducts(); ?>
    
    <?php if (isset($_SESSION['cart'])) { ?>
        <h2>Shopping Cart</h2>
        <?php displayCart(); ?>
    <?php } ?>
</body>
</html>


<?php
// Initialize the session
session_start();

// Products array (you can connect this to your database later)
$products = [
    1 => [
        'name' => 'Product 1',
        'price' => 29.99,
        'description' => 'This is product 1'
    ],
    2 => [
        'name' => 'Product 2',
        'price' => 49.99,
        'description' => 'This is product 2'
    ],
    3 => [
        'name' => 'Product 3',
        'price' => 19.99,
        'description' => 'This is product 3'
    ]
];

// Function to add item to cart
function addToCart($productId, $quantity = 1) {
    if (isset($_COOKIE['cart'])) {
        $cart = unserialize($_COOKIE['cart']);
    } else {
        $cart = [];
    }

    if (!isset($cart[$productId])) {
        $cart[$productId] = ['quantity' => $quantity];
    } else {
        $cart[$productId]['quantity'] += $quantity;
    }

    setcookie('cart', serialize($cart), time() + 3600 * 24 * 7);
}

// Function to view cart
function viewCart() {
    if (isset($_COOKIE['cart'])) {
        $cart = unserialize($_COOKIE['cart']);
        return $cart;
    } else {
        return [];
    }
}

// Function to update quantity
function updateQuantity($productId, $quantity) {
    $cart = unserialize($_COOKIE['cart']);
    $cart[$productId]['quantity'] = $quantity;
    setcookie('cart', serialize($cart), time() + 3600 * 24 * 7);
}

// Function to remove item from cart
function removeFromCart($productId) {
    $cart = unserialize($_COOKIE['cart']);
    unset($cart[$productId]);
    setcookie('cart', serialize($cart), time() + 3600 * 24 * 7);
}

// Function to calculate total price
function calculateTotal($cart, $products) {
    $total = 0;
    foreach ($cart as $productId => $item) {
        $total += $products[$productId]['price'] * $item['quantity'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .product-card { border: 1px solid #ddd; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <h1>Products</h1>
    
    <div class="product-grid">
        <?php foreach ($products as $id => $product): ?>
            <div class="product-card">
                <h3><?php echo $product['name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Your Cart</h2>
    
    <?php 
    $cart = viewCart();
    if (empty($cart)): 
    ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cart as $productId => $item): ?>
                <tr>
                    <td><?php echo $products[$productId]['name']; ?></td>
                    <td>
                        <form method="post" action="update_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($products[$productId]['price'], 2); ?></td>
                    <td>$<?php echo number_format($products[$productId]['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="remove_from_cart.php?product_id=<?php echo $productId; ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <p>Total: $<?php echo number_format(calculateTotal($cart, $products), 2); ?></p>
        <br>
        <form method="post" action="checkout.php">
            <button type="submit">Checkout</button>
        </form>
    <?php endif; ?>
</body>
</html>



<?php
session_start();
include 'cart_functions.php';

if (isset($_POST['product_id'])) {
    addToCart($_POST['product_id']);
}

header('Location: index.php');
exit();
?>


<?php
session_start();
include 'cart_functions.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    updateQuantity($_POST['product_id'], $_POST['quantity']);
}

header('Location: index.php');
exit();
?>


<?php
session_start();
include 'cart_functions.php';

if (isset($_GET['product_id'])) {
    removeFromCart($_GET['product_id']);
}

header('Location: index.php');
exit();
?>


<?php
session_start();
include 'cart_functions.php';

$cart = viewCart();

// Here you would typically process the payment and save the order to a database

// For this example, we'll just show a thank you message
echo "<h1>Thank You for Your Order!</h1>";
echo "<p>Your order number is: " . mt_rand(10000000, 99999999) . "</p>";
?>


<?php
// This is a simplified example of a shopping cart functionality

session_start();

// Database connection
require_once 'db.php';

// Function to add item to cart
function addToCart($productId, $quantity = 1) {
    global $pdo;

    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        return false;
    }

    if (isset($_SESSION['cart'][$productId])) {
        // If item already in cart, increment quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'description' => $product['description']
        ];
    }

    return true;
}

// Function to update cart item quantity
function updateCartItem($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
        return true;
    }
    return false;
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        return true;
    }
    return false;
}

// Function to get cart items
function getCartItems() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

// Function to calculate total price of cart
function getCartTotal() {
    $total = 0;
    foreach (getCartItems() as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
// Display products and cart functionality

require_once 'db.php';
require_once 'functions.php';

// Get all products from database
$products = getAllProducts();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        addToCart($productId, $quantity);
    }

    if (isset($_POST['update_cart'])) {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'quantity_') === 0) {
                $productId = str_replace('quantity_', '', $key);
                updateCartItem($productId, intval($value));
            }
        }
    }

    if (isset($_POST['remove_item'])) {
        $productId = $_POST['product_id'];
        removeFromCart($productId);
    }

    if (isset($_POST['checkout'])) {
        // Process checkout
        foreach (getCartItems() as $item) {
            $stmt = $pdo->prepare("INSERT INTO orders (product_id, user_id, quantity, order_date)
                                  VALUES (?, ?, ?, NOW())");
            $stmt->execute([$item['id'], isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null, $item['quantity']]);
        }
        // Clear cart after checkout
        unset($_SESSION['cart']);
    }
}
?>

<h2 class="text-center">Shopping Cart</h2>

<div class="container">
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text"><?php echo $product['description']; ?></p>
                        <p class="card-text">Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" name="quantity" min="1" value="1">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3>Shopping Cart Items</h3>
    <?php if (empty(getCartItems())): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (getCartItems() as $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>
                                <input type="number" class="form-control" name="quantity_<?php echo $item['id']; ?>" min="1" value="<?php echo $item['quantity']; ?>">
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <button type="submit" name="remove_item" class="btn btn-danger">
                                    Remove
                                </button>
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-right mb-3">
                <strong>Total: $<?php echo number_format(getCartTotal(), 2); ?></strong>
            </div>
            <button type="submit" name="update_cart" class="btn btn-success">Update Cart</button>
        </form>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <button type="submit" name="checkout" class="btn btn-primary">Checkout</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>


<?php
// Initialize session
session_start();

// Check if cart exists in session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php
    // Adding item to cart logic
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Check if product is already in cart
        if (array_key_exists($productId, $_SESSION['cart'])) {
            // Update quantity
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            // Add new product to cart
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    // Updating item quantity logic
    if (isset($_POST['update_cart'])) {
        foreach ($_POST as $key => $value) {
            if ($key != 'update_cart' && is_numeric($value)) {
                $_SESSION['cart'][$key] = $value;
            }
        }
    }

    // Removing item from cart logic
    if (isset($_GET['remove'])) {
        $productId = $_GET['remove'];
        unset($_SESSION['cart'][$productId]);
    }

    // Display products to add to cart
    echo "<h2>Our Products</h2>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='1'>";
    echo "<input type='number' name='quantity' min='1' value='1'>";
    echo "<input type='submit' name='add_to_cart' value='Add Product 1'>";
    echo "</form>";

    echo "<br>";
    
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='2'>";
    echo "<input type='number' name='quantity' min='1' value='1'>";
    echo "<input type='submit' name='add_to_cart' value='Add Product 2'>";
    echo "</form>";

    // Display cart contents
    if (!empty($_SESSION['cart'])) {
        echo "<h2>Your Cart</h2>";
        echo "<form method='post'>";
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            echo "<div class='cart-item'>";
            echo "Product ID: $productId <br>";
            echo "Quantity: <input type='number' name='$productId' min='1' value='$quantity'><br>";
            echo "<a href='?remove=$productId'>Remove</a>";
            echo "</div>";
        }
        echo "<input type='submit' name='update_cart' value='Update Cart'>";
        echo "</form>";
    } else {
        echo "<h2>Your cart is empty</h2>";
    }

    // Links to navigate between pages
    echo "<br><a href='products.php'>View More Products</a> | <a href='cart.php'>View Cart</a>";
?>
</body>
</html>


class Product {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Get all products
    public function getAllProducts() {
        $query = "SELECT * FROM products";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add new product
    public function addProduct($name, $price, $description, $image_path) {
        $query = "INSERT INTO products (name, price, description, image_path) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$name, $price, $description, $image_path]);
    }

    // Search products
    public function searchProducts($keyword) {
        $query = "SELECT * FROM products 
                WHERE name LIKE ? OR description LIKE ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(["%$keyword%", "%$keyword%"]);
    }
}


class Cart {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    // Add item to cart
    public function addItem($product_id, $quantity = 1) {
        $query = "INSERT INTO cart (user_id, product_id, quantity)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = quantity + ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->user_id, $product_id, $quantity, $quantity]);
    }

    // Get cart items
    public function getCartItems() {
        $query = "SELECT c.*, p.* FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update quantity
    public function updateQuantity($cart_id, $quantity) {
        $query = "UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$quantity, $cart_id]);
    }

    // Remove item from cart
    public function removeItem($cart_id) {
        $query = "DELETE FROM cart WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$cart_id]);
    }

    // Calculate total price
    public function calculateTotal() {
        $query = "SELECT SUM(p.price * c.quantity) as total 
                FROM cart c JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Checkout
    public function checkout() {
        // Here you would typically insert into an orders table
        // and then empty the cart
        $this->emptyCart();
        return true;
    }

    // Empty cart
    private function emptyCart() {
        $query = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->user_id]);
    }
}


class UserAuth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Register user
    public function register($username, $password) {
        $query = "INSERT INTO users (username, password)
                VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$username, md5($password)]);
    }

    // Login user
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username, md5($password)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Logout user
    public function logout() {
        session_unset();
        session_destroy();
    }
}


<?php
require_once 'database.php';
require_once 'Product.php';

$product = new Product($db);

// Get all products
$products = $product->getAllProducts();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - Products</title>
</head>
<body>
    <h1>Available Products</h1>
    
    <?php foreach ($products as $product): ?>
        <div class="product">
            <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <form action="cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>

    <!-- Add your own styling and layout -->
</body>
</html>


<?php
session_start();
require_once 'database.php';
require_once 'Cart.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart = new Cart($db, $_SESSION['user_id']);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $cart->addItem($product_id, $quantity);
    } elseif (isset($_POST['update_quantity'])) {
        foreach ($_POST['quantity'] as $cart_id => $qty) {
            $cart->updateQuantity($cart_id, intval($qty));
        }
    } elseif (isset($_POST['remove_item'])) {
        $cart->removeItem(intval($_POST['item_id']));
    } elseif (isset($_POST['checkout'])) {
        $cart->checkout();
        header('Location: products.php');
        exit;
    }
}

$items = $cart->getCartItems();
$total = $cart->calculateTotal();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (!empty($items)): ?>
        <form action="cart.php" method="post">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['id']; ?>]" 
                                       value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <input type="hidden" name="remove_item_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove_item">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="cart-total">
                <h3>Total: $<?php echo number_format($total, 2); ?></h3>
                <input type="submit" name="update_quantity" value="Update Cart">
                <input type="submit" name="checkout" value="Checkout">
            </div>
        </form>
    <?php else: ?>
        <p>Your cart is empty!</p>
    <?php endif; ?>

    <a href="products.php">Continue Shopping</a>
</body>
</html>


<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>


// login.php
<?php
session_start();
require_once 'database.php';
require_once 'UserAuth.php';

$user_auth = new UserAuth($db);

if ($user_auth->isLoggedIn()) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $user_auth->login($_POST['username'], $_POST['password']);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: products.php');
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } elseif (isset($_POST['register'])) {
        $user_auth->register($_POST['username'], $_POST['password']);
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <h1>Login</h1>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Don't have an account? <a href="?action=register">Register here</a></p>

    <?php if (isset($_GET['action']) && $_GET['action'] == 'register'): ?>
        <h1>Register</h1>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>
    <?php endif; ?>
</body>
</html>


// logout.php
<?php
session_start();
require_once 'UserAuth.php';

$user_auth = new UserAuth($db);
$user_auth->logout();

header('Location: login.php');
exit;
?>


<?php
$host = 'localhost';
$dbname = 'shopping_cart';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


<?php
include 'config.php';

// Get all products from the database
$stmt = $conn->query('SELECT * FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Products</h1>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <p>Price: <?php echo $product['price']; ?> €</p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="quantity" min="1" max="<?php echo $product['stock']; ?>" required>
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>


<?php
session_start();
include 'config.php';

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Check if the product exists and has stock
$stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product && $product['stock'] >= $quantity) {
    // Add to cart (session)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 0
        );
    }
    
    // Update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    
    // Reduce stock (you might want to do this when placing the order instead)
    // $stmt = $conn->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
    // $stmt->execute([$quantity, $product_id]);
    
    header('Location: cart.php');
} else {
    header('Location: products.php');
}
?>


<?php
session_start();
include 'config.php';

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
            <div class="cart-item">
                <p><?php echo $item['name']; ?></p>
                <p>Price: <?php echo $item['price']; ?> €</p>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
                <form action="remove_from_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <button type="submit">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
        
        <h3>Total: <?php echo $total; ?> €</h3>
        <a href="checkout.php">Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="products.php">Continue Shopping</a>
    <?php endif; ?>
    
    <br>
    <a href="products.php">Back to Products</a>
</body>
</html>


<?php
session_start();

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

header('Location: cart.php');
?>


<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the order
    $user_id = 1; // Replace with actual user ID or authentication system
    
    foreach ($_SESSION['cart'] as $id => $item) {
        // Update stock
        $stmt = $conn->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
        $stmt->execute([$item['quantity'], $id]);
        
        // Insert order
        $stmt = $conn->prepare('INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)');
        $stmt->execute([$user_id, $id, $item['quantity'], $item['price'] * $item['quantity']]);
    }
    
    // Clear cart
    unset($_SESSION['cart']);
    
    header('Location: thank_you.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <form action="checkout.php" method="post">
        <div>
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            
            <label for="address">Address:</label><br>
            <textarea id="address" name="address" required></textarea><br>
            
            <label for="phone">Phone:</label><br>
            <input type="tel" id="phone" name="phone" required><br>
        </div>
        
        <h3>Payment Method</h3>
        <div>
            <input type="radio" name="payment_method" value="credit_card" required>Credit Card<br>
            <input type="radio" name="payment_method" value="paypal">PayPal
        </div>
        
        <button type="submit">Place Order</button>
    </form>
</body>
</html>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
</head>
<body>
    <h1>Thank You for Your Order!</h1>
    <p>Your order has been successfully placed.</p>
    <a href="products.php">Continue Shopping</a>
</body>
</html>


<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "cart_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize session
session_start();

// Start creating the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .product-list {
            margin: 20px;
        }
        .cart-item {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>
    
    <!-- Display product categories -->
    <div class="product-list">
        <?php
        // Get all products from database
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='cart-item'>";
                echo "<h3>" . $row['name'] . "</h3>";
                echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
                echo "<p>Category: " . $row['category'] . "</p>";
                echo "<a href='index.php?action=add&id=" . $row['id'] . "'>Add to Cart</a>";
                echo "</div>";
            }
        } else {
            echo "No products found.";
        }
        ?>
    </div>

    <!-- Display cart contents -->
    <h2>Your Cart:</h2>
    <?php
    if (!empty($_SESSION['cart'])) {
        $total = 0;
        echo "<form action='index.php?action=update' method='post'>";
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "SELECT * FROM products WHERE id=" . $product_id;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            
            echo "<div class='cart-item'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
            echo "<input type='number' name='" . $product_id . "' value='" . $quantity . "'>";
            echo "<a href='index.php?action=remove&id=" . $product_id . "'>Remove</a>";
            echo "</div>";
            
            $total += $row['price'] * $quantity;
        }
        echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
        echo "<input type='submit' value='Update Cart'>";
        echo "</form>";
    } else {
        echo "Your cart is empty.";
    }
    ?>

    <!-- Checkout button -->
    <?php if (!empty($_SESSION['cart'])) { ?>
        <a href="checkout.php">Proceed to Checkout</a>
    <?php } ?>

<?php
// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'add':
            $product_id = $_GET['id'];
            if (!empty($product_id)) {
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]++;
                } else {
                    $_SESSION['cart'][$product_id] = 1;
                }
            }
            break;
            
        case 'remove':
            $product_id = $_GET['id'];
            unset($_SESSION['cart'][$product_id]);
            break;
    }
}

// Update quantities
if (isset($_POST)) {
    foreach ($_POST as $key => $value) {
        if ($value < 1) {
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key] = $value;
        }
    }
}
?>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])) { ?>
        <h1>Checkout Page</h1>
        <p>Your order total is: $<?php echo number_format($total, 2); ?></p>
        
        <!-- Add checkout form -->
        <form action="process_order.php" method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name"><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"><br>
            
            <label for="address">Address:</label><br>
            <textarea id="address" name="address"></textarea><br>
            
            <input type="submit" value="Place Order">
        </form>
    <?php } else { ?>
        <p>Your cart is empty. Please add items to your cart first.</p>
    <?php } ?>

    <!-- Go back link -->
    <a href="index.php">Go Back</a>
</body>
</html>


<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


<?php
require_once('config.php');

// Start session
session_start();

// Get or create session ID
$sessionId = session_id();
if (!$sessionId) {
    session_regenerate(true);
    $sessionId = session_id();
}

// Function to add product to cart
function addToCart($productId, $quantity = 1) {
    global $conn, $sessionId;

    // Check if the product is already in the cart
    $stmt = $conn->prepare("SELECT id FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param('si', $sessionId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity
        $row = $result->fetch_assoc();
        $cartId = $row['id'];
        $newQuantity = $quantity + getCartQuantity($productId);
        updateCartQuantity($cartId, $newQuantity);
    } else {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $sessionId, $productId, $quantity);
        $stmt->execute();
    }
}

// Function to get cart items
function getCartItems() {
    global $conn, $sessionId;

    $cartItems = [];
    
    $stmt = $conn->prepare("SELECT c.id AS cart_id, p.*, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ?");
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    return $cartItems;
}

// Function to update quantity
function updateCartQuantity($cartId, $quantity) {
    global $conn;

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param('ii', $quantity, $cartId);
    $stmt->execute();
}

// Function to delete item from cart
function deleteCartItem($cartId) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param('i', $cartId);
    $stmt->execute();
}

// Helper function to get current quantity of an item in the cart
function getCartQuantity($productId) {
    global $conn, $sessionId;

    $quantity = 0;
    
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param('si', $sessionId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quantity = $row['quantity'];
    }

    return $quantity;
}


<?php
require_once('cart_functions.php');

// Sample products array - replace this with actual database query in production
$products = [
    ['id' => 1, 'name' => 'Product 1', 'price' => 29.99, 'description' => 'Description for Product 1', 'image' => 'product1.jpg'],
    ['id' => 2, 'name' => 'Product 2', 'price' => 49.99, 'description' => 'Description for Product 2', 'image' => 'product2.jpg'],
    // Add more products as needed
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <style>
        /* Add your CSS styles here */
        .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
        }
        .cart-link {
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Products</h1>
    
    <?php foreach ($products as $product) { ?>
        <div class="product">
            <?php if (!empty($product['image'])) { ?>
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="max-width: 200px;">
            <?php } ?>
            <h3><?php echo $product['name']; ?></h3>
            <p>Price: <?php echo number_format($product['price'], 2); ?></p>
            <p><?php echo $product['description']; ?></p>
            <div class="cart-link">
                <a href="#" onclick="addToCart(<?php echo $product['id']; ?>, 1); return false;">Add to Cart</a>
            </div>
        </div>
    <?php } ?>

    <script>
        // Add JavaScript for handling AJAX requests if needed
        function addToCart(productId, quantity) {
            alert('Product added to cart!');
            window.location.href = 'cart.php';
        }
    </script>
    
    <p><a href="cart.php">View Cart</a></p>
</body>
</html>


<?php
require_once('cart_functions.php');

// Get cart items
$cartItems = getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        /* Add your CSS styles here */
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
        }
        .total-price {
            font-size: 24px;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <?php if (empty($cartItems)) { ?>
        <p>Your cart is empty.</p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item) { ?>
                    <tr class="cart-item">
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" 
                                   onchange="updateQuantity(<?php echo $item['cart_id']; ?>, this.value);">
                        </td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="#" onclick="deleteItem(<?php echo $item['cart_id']; ?>); return false;">Delete</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="total-price">Total: <?php echo number_format(getCartTotal(), 2); ?></p>
        <button onclick="checkout()">Checkout</button>
    <?php } ?>

    <script>
        function updateQuantity(cartId, quantity) {
            alert('Quantity updated!');
            window.location.href = 'cart.php';
        }

        function deleteItem(cartId) {
            if (confirm('Are you sure you want to delete this item?')) {
                <?php deleteCartItem(1); ?> // Replace with actual AJAX call in production
                window.location.href = 'cart.php';
            }
        }

        function checkout() {
            alert('Proceeding to checkout!');
            window.location.href = 'checkout.php';
        }
    </script>

    <p><a href="products.php">Back to Products</a></p>
</body>
</html>


<?php
require_once('cart_functions.php');

// Get cart items
$cartItems = getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <?php if (empty($cartItems)) { ?>
        <p>Your cart is empty.</p>
        <p><a href="products.php">Back to Products</a></p>
    <?php } else { ?>
        <h1>Checkout</h1>
        
        <form action="#" method="POST">
            <!-- Add your checkout form fields here -->
            <div style="margin-bottom: 20px;">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="address">Address:</label><br>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>

            <button type="submit" style="background-color: green; color: white;">Place Order</button>
        </form>

        <p><a href="cart.php">Back to Cart</a></p>
    <?php } ?>
</body>
</html>


<?php
session_start();

// Sample product data
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'price' => 25.00),
    array('id' => 2, 'name' => 'Product 2', 'price' => 45.00),
    array('id' => 3, 'name' => 'Product 3', 'price' => 65.00)
);

if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart') {
    $productId = $_GET['id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        $_SESSION['cart'][$productId] = array(
            'id' => $productId,
            'name' => $products[$productId - 1]['name'],
            'price' => $products[$productId - 1]['price'],
            'quantity' => 1
        );
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
</head>
<body>
    <h2>Product List</h2>
    
    <?php foreach ($products as $product): ?>
        <div style="margin: 10px 0;">
            <h3><?php echo $product['name']; ?></h3>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <a href="add_to_cart.php?id=<?php echo $product['id']; ?>">Add to Cart</a>
        </div>
    <?php endforeach; ?>
    
    <a href="cart.php">View Cart</a>
</body>
</html>


<?php
session_start();

$total = 0;
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h2>Your Shopping Cart</h2>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <?php
                $total += ($item['price'] * $item['quantity']);
                ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><input type="text" name="quantity-<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>"></td>
                    <td>$<?php echo number_format(($item['price'] * $item['quantity']), 2); ?></td>
                    <td><a href="delete_from_cart.php?id=<?php echo $item['id']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
        <br>
        <form action="update_cart.php" method="post">
            <input type="submit" value="Update Cart">
        </form>
    <?php endif; ?>
    
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    header("Location: index.php?action=add_to_cart&id=$productId");
}
?>


<?php
session_start();

foreach ($_POST as $key => $value) {
    if (strpos($key, 'quantity-') === 0) {
        $id = substr($key, 9);
        $quantity = intval($value);
        
        if ($quantity > 0) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
}

header("Location: cart.php");
?>


<?php
session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    unset($_SESSION['cart'][$productId]);
}

header("Location: cart.php");
?>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Sample products (you would typically get this from a database in a real application)
$products = array(
    1 => array(
        'name' => 'Product 1',
        'price' => 29.99,
        'description' => 'Description for Product 1'
    ),
    2 => array(
        'name' => 'Product 2',
        'price' => 49.99,
        'description' => 'Description for Product 2'
    ),
    3 => array(
        'name' => 'Product 3',
        'price' => 19.99,
        'description' => 'Description for Product 3'
    )
);

// Handle adding items to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        // Check if product already exists in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = array(
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => $quantity
            );
        }
    }
}

// Handle removing items from the cart
if (isset($_GET['remove'])) {
    $productId = intval($_GET['remove']);
    unset($_SESSION['cart'][$productId]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .product-listing {
            margin-bottom: 30px;
        }
        .cart-item {
            border-bottom: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 5px;
        }
        .total-cost {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <!-- Product Listing -->
    <div class="product-listing">
        <?php foreach ($products as $id => $product): ?>
            <form method="post" action="">
                <div style="float: left; margin-right: 20px;">
                    <img src="placeholder-image.jpg" alt="<?php echo $product['name']; ?>" width="100">
                </div>
                <div style="float: left;">
                    <h3><?php echo $product['name']; ?></h3>
                    <p><?php echo $product['description']; ?></p>
                    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <input type="number" name="quantity" min="1" max="99" value="1">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </div>
                <div style="clear: both;"></div>
            </form>
        <?php endforeach; ?>
    </div>

    <!-- Cart Contents -->
    <h2>Cart Items:</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
            <div class="cart-item">
                <p><?php echo $item['name']; ?></p>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
                <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                <a href="?remove=<?php echo $id; ?>">Remove</a>
            </div>
        <?php endforeach; ?>

        <!-- Total Cost Calculation -->
        <?php 
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
        ?>
        <div class="total-cost">
            Subtotal: $<?php echo number_format($total, 2); ?>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <!-- Checkout Button -->
    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="post" action="checkout.php">
            <button type="submit">Checkout</button>
        </form>
    <?php endif; ?>
</body>
</html>


<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cart';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
class Product {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getProducts() {
        $query = "SELECT * FROM products";
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }
}
?>


<?php
session_start();
class Cart {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function addToCart($productId, $userId) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
            
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getCartItems($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT c.id, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function updateCartItem($itemId, $quantity) {
        try {
            $stmt = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $itemId);
            $stmt->execute();
            
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function removeCartItem($itemId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = ?");
            $stmt->bind_param("i", $itemId);
            $stmt->execute();
            
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>


<?php
include_once 'config.php';
include_once 'Product.php';
include_once 'Cart.php';

$product = new Product($conn);
$cart = new Cart($conn);

$user_id = 1; // Assume user is logged in

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            $cart->addToCart($_GET['id'], $user_id);
            break;
        case 'update':
            $cart->updateCartItem($_POST['item_id'], $_POST['quantity']);
            break;
        case 'remove':
            $cart->removeCartItem($_GET['id']);
            break;
    }
}

$products = $product->getProducts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .cart-list {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Products</h1>
        
        <div class="product-grid">
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <strong>Price: $<?php echo number_format($row['price'], 2); ?></strong><br>
                    <a href="?action=add&id=<?php echo $row['id']; ?>">Add to Cart</a>
                </div>
            <?php endwhile; ?>
        </div>

        <h2>Your Cart</h2>
        
        <?php 
        $cartItems = $cart->getCartItems($user_id);
        if ($cartItems): 
        ?>
        <table class="cart-list">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php while ($item = $cartItems->fetch_assoc()): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td>
                    <form action="?action=update" method="post">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 50px;">
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                <td><a href="?action=remove&id=<?php echo $item['id']; ?>">Remove</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php else: ?>
        <p>Your cart is empty.</p>
        <?php endif; ?>

    </div>
</body>
</html>


<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($id, $name, $price) {
    global $db;
    $found = false;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = array(
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to show cart items
function show_cart() {
    $total_price = 0;
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $key => $item) {
        $total = $item['price'] * $item['quantity'];
        $total_price += $total;
        
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>₹" . number_format($item['price'], 2) . "</td>";
        echo "<td><input type='text' name='quantity[".$key."]' value='".$item['quantity']."' size='3'></td>";
        echo "<td>₹" . number_format($total, 2) . "</td>";
        echo "<td><a href='cart.php?action=remove&id=".$item['id']."'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "<tr><td colspan='4'><strong>Total:</strong></td><td>₹" . number_format($total_price, 2) . "</td></tr>";
    echo "</table>";
}

// Function to remove item from cart
function remove_from_cart($id) {
    $new_cart = array();
    
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] != $id) {
            $new_cart[] = $item;
        }
    }
    
    $_SESSION['cart'] = $new_cart;
}

// Function to update cart quantities
function update_cart() {
    if (isset($_POST['update'])) {
        foreach ($_POST['quantity'] as $key => $qty) {
            $_SESSION['cart'][$key]['quantity'] = $qty;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<?php
// Sample product data (you can replace this with your database connection)
$products = array(
    array('id' => 1, 'name' => 'Laptop', 'price' => 50000),
    array('id' => 2, 'name' => 'Phone', 'price' => 10000),
    array('id' => 3, 'name' => 'Tablet', 'price' => 7000)
);

// Display products
echo "<h2>Products</h2>";
foreach ($products as $product) {
    echo "<div style='float: left; margin-right: 20px; padding: 10px; border: 1px solid #ddd;'>";
    echo "<h3>".$product['name']."</h3>";
    echo "₹".number_format($product['price'], 2)."<br />";
    echo "<a href='cart.php?action=add&id=".$product['id']."&name=".$product['name']."&price=".$product['price']."' style='color: #0066cc;'>Add to Cart</a>";
    echo "</div>";
}

// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            if (isset($_GET['id'], $_GET['name'], $_GET['price'])) {
                add_to_cart($_GET['id'], $_GET['name'], $_GET['price']);
            }
            break;
            
        case 'remove':
            if (isset($_GET['id'])) {
                remove_from_cart($_GET['id']);
            }
            break;
    }
}

// Update cart quantities
if (isset($_POST['update'])) {
    update_cart();
}

echo "<h2>Your Cart</h2>";
show_cart();

// Checkout form
echo "<form method='post'>";
echo "<input type='submit' name='update' value='Update Quantities' style='margin-bottom: 10px;'/>";
echo "</form>";

if (!empty($_SESSION['cart'])) {
    echo "<a href='checkout.php' style='float: right; color: #0066cc;'>Checkout</a>";
}

?>
</body>
</html>


  <?php
  session_start();
  
  if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
      header("Location: products.php?error=Invalid product ID");
      exit();
  }
  
  $product_id = intval($_GET['product_id']);
  $quantity = isset($_GET['quantity']) && is_numeric($_GET['quantity']) ? intval($_GET['quantity']) : 1;
  
  if ($quantity < 1) {
      header("Location: products.php?error=Quantity must be at least 1");
      exit();
  }
  
  if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
  }
  
  $found = false;
  foreach ($_SESSION['cart'] as &$item) {
      if ($item['product_id'] == $product_id) {
          $item['quantity'] += $quantity;
          $found = true;
          break;
      }
  }
  
  if (!$found) {
      $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
  }
  
  header("Location: show_cart.php");
  exit();
  ?>
  

- **show_cart.php:**
  
  <?php
  session_start();
  require_once('products.php');
  
  if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
      echo "<p>Your cart is empty.</p>";
      exit();
  }
  
  $total = 0;
  foreach ($_SESSION['cart'] as $index => $item) {
      $product_id = $item['product_id'];
      if (!isset($products[$product_id])) continue; // Skip invalid products
      
      $price = 1000; // Replace with actual product price from database
      $total += $price * $item['quantity'];
      
      echo "<div class='cart-item'>";
      echo "<img src='images/{$products[$product_id]['image']}' alt='{$products[$product_id]['name']}'>";
      echo "<h3>{$products[$product_id]['name']}</h3>";
      echo "<p>Quantity: {$item['quantity']}</p>";
      echo "<a href='remove_item.php?index=$index'>Remove</a>";
      echo "<form action='update_quantity.php' method='post'>";
      echo "<input type='hidden' name='index' value='$index'>";
      echo "<input type='number' name='quantity' min='1' value='{$item['quantity']}'>";
      echo "<button type='submit'>Update Quantity</button>";
      echo "</form>";
      echo "</div>";
  }
  
  echo "<h2>Total: $total</h2>";
  ?>
  

- **remove_item.php:**
  
  <?php
  session_start();
  
  if (isset($_GET['index']) && is_numeric($_GET['index'])) {
      $index = intval($_GET['index']);
      if (isset($_SESSION['cart'][$index])) {
          array_splice($_SESSION['cart'], $index, 1);
      }
  }
  
  header("Location: show_cart.php");
  exit();
  ?>
  

- **update_quantity.php:**
  
  <?php
  session_start();
  
  if (isset($_POST['index']) && is_numeric($_POST['index']) && isset($_POST['quantity']) && is_numeric($_POST['quantity'])) {
      $index = intval($_POST['index']);
      $quantity = intval($_POST['quantity']);
      
      if ($quantity < 1) {
          header("Location: show_cart.php?error=Quantity must be at least 1");
          exit();
      }
      
      if (isset($_SESSION['cart'][$index])) {
          $_SESSION['cart'][$index]['quantity'] = $quantity;
      }
  }
  
  header("Location: show_cart.php");
  exit();
  ?>
  

- **products.php:**
  
  <?php
  $products = array(
      1 => array('name' => 'Product 1', 'price' => 1000, 'image' => 'product1.jpg'),
      2 => array('name' => 'Product 2', 'price' => 1500, 'image' => 'product2.jpg'),
      // Add more products as needed
  );
  ?>
  

**HTML Example (products.php):**


<?php
session_start();
?>


<?php
session_start();

// Product data (you would typically get this from your database)
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];

// Check if the product is already in the cart
if (isset($_SESSION['cart'][$product_id])) {
    // Update quantity
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    // Add new item to cart
    $_SESSION['cart'][$product_id] = array(
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => 1
    );
}

// Redirect back to the product page or cart page
header("Location: view_cart.php");
exit();
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])): ?>
        <h2>Your Shopping Cart</h2>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
                <strong><?php echo $item['name']; ?></strong><br>
                Price: $<?php echo $item['price']; ?><br>
                Quantity: <?php echo $item['quantity']; ?>
                
                <form action="update_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>">
                    <button type="submit">Update</button>
                </form>
                
                <form action="delete_from_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
        
        <h3>Total: $<?php echo array_sum(array_column($_SESSION['cart'], 'price')); ?></h3>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="products.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }

    header("Location: view_cart.php");
    exit();
}
?>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: view_cart.php");
    exit();
}
?>


<?php
session_start();

// Sample product data (you would typically get this from your database)
$products = array(
    array(
        'id' => 1,
        'name' => 'Product 1',
        'price' => 19.99
    ),
    array(
        'id' => 2,
        'name' => 'Product 2',
        'price' => 29.99
    ),
    array(
        'id' => 3,
        'name' => 'Product 3',
        'price' => 39.99
    )
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h3><?php echo $product['name']; ?></h3>
            <p>$<?php echo $product['price']; ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>

    <a href="view_cart.php">View Cart</a>
</body>
</html>


<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Page</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .product { border: 1px solid #ddd; padding: 10px; margin: 5px; }
    </style>
</head>
<body>
    <?php
    include_once 'config.php';
    
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
            echo "<button onclick='addToCart(" . $row['id'] . ")'>Add to Cart</button>";
            echo "</div>";
        }
    } else {
        echo "No products found.";
    }
    
    $conn->close();
    ?>
    
    <script>
        function addToCart(productId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send("productId=" + productId);
        }
    </script>
</body>
</html>


<?php session_start(); ?>
<?php
include_once 'config.php';

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];
    
    // Check if product exists
    $sql_product = "SELECT id FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql_product);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die(json_encode(array('status' => 'error', 'message' => 'Product not found.')));
    }
    
    // Check if item already exists in cart
    $sql_cart = "SELECT id FROM shopping_cart WHERE product_id = ? AND user_session_id = ?";
    $stmt = $conn->prepare($sql_cart);
    $stmt->bind_param("is", $productId, session_id());
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Update quantity
        $sql_update = "UPDATE shopping_cart SET quantity = quantity + 1 WHERE product_id = ? AND user_session_id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("is", $productId, session_id());
        $stmt->execute();
    } else {
        // Add new item
        $sql_insert = "INSERT INTO shopping_cart (product_id, user_session_id, quantity) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("is", $productId, session_id());
        $stmt->execute();
    }
    
    echo json_encode(array('status' => 'success', 'message' => 'Item added to cart.'));
} else {
    die(json_encode(array('status' => 'error', 'message' => 'Invalid request.')));
}

$conn->close();
?>


<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .cart-item { border: 1px solid #ddd; padding: 10px; margin: 5px; }
    </style>
</head>
<body>
    <?php
    include_once 'config.php';
    
    $sql = "SELECT sc.id AS cart_id, p.*, sc.quantity 
            FROM shopping_cart sc 
            JOIN products p ON sc.product_id = p.id 
            WHERE sc.user_session_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", session_id());
    $stmt->execute();
    
    $result = $stmt->get_result();
    $total = 0;
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='cart-item'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
            echo "<p>Quantity: " . $row['quantity'] . "</p>";
            echo "<button onclick='updateQuantity(" . $row['cart_id'] . ", \"increase\")'>Increase</button>";
            echo "<button onclick='updateQuantity(" . $row['cart_id'] . ", \"decrease\")'>Decrease</button>";
            echo "<button onclick='deleteItem(" . $row['cart_id'] . ")'>Delete</button>";
            echo "</div>";
            
            $total += $row['price'] * $row['quantity'];
        }
        echo "<h2>Total: $" . number_format($total, 2) . "</h2>";
    } else {
        echo "Your cart is empty.";
    }
    
    $conn->close();
    ?>
    
    <script>
        function updateQuantity(cartId, action) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_quantity.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    window.location.reload(); // Refresh to update cart
                }
            };
            xhr.send("cartId=" + cartId + "&action=" + action);
        }

        function deleteItem(cartId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_item.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    window.location.reload(); // Refresh to update cart
                }
            };
            xhr.send("cartId=" + cartId);
        }
    </script>
</body>
</html>


<?php session_start(); ?>
<?php
include_once 'config.php';

if (isset($_POST['cartId']) && isset($_POST['action'])) {
    $cartId = $_POST['cartId'];
    $action = $_POST['action'];
    
    if ($action == "increase") {
        $sql = "UPDATE shopping_cart SET quantity = quantity + 1 WHERE id = ?";
    } else if ($action == "decrease") {
        $sql = "UPDATE shopping_cart SET quantity = quantity - 1 WHERE id = ?";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    
    echo json_encode(array('status' => 'success'));
} else {
    die(json_encode(array('status' => 'error')));
}

$conn->close();
?>


<?php session_start(); ?>
<?php
include_once 'config.php';

if (isset($_POST['cartId'])) {
    $cartId = $_POST['cartId'];
    
    $sql = "DELETE FROM shopping_cart WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    
    echo json_encode(array('status' => 'success'));
} else {
    die(json_encode(array('status' => 'error')));
}

$conn->close();
?>


<?php
// config.php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'shopping_cart';

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
// index.php
session_start();
include 'config.php';

$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div style="border: 1px solid #ddd; padding: 10px; margin: 5px;">
            <h2><?php echo $row['name']; ?></h2>
            <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
            <?php if ($row['image']) { ?>
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="max-width: 100px;">
            <?php } ?>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <input type="number" name="quantity" min="1" max="99" value="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php } ?>

    <a href="show_cart.php">View Cart</a>
</body>
</html>


<?php
// add_to_cart.php
session_start();
include 'config.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product exists in database
    $check_product = mysqli_query($conn, "SELECT * FROM products WHERE product_id=$product_id");
    if (mysqli_num_rows($check_product) == 0) {
        die("Product does not exist.");
    }

    // Get session id or create a new one
    $session_id = session_id();

    // Check if item already exists in cart
    $existing_cart_item = mysqli_query($conn, "SELECT * FROM cart WHERE product_id=$product_id AND session_id='$session_id'");
    if (mysqli_num_rows($existing_cart_item) > 0) {
        // Update quantity
        $update = mysqli_query($conn, "UPDATE cart SET quantity=quantity+$quantity WHERE product_id=$product_id AND session_id='$session_id'");
    } else {
        // Add new item to cart
        $insert = mysqli_query($conn, "INSERT INTO cart (product_id, quantity, session_id) VALUES ($product_id, $quantity, '$session_id')");
    }

    if (isset($update) || isset($insert)) {
        header("Location: index.php");
        exit();
    } else {
        die("Error adding to cart.");
    }
}
?>


<?php
// update_cart.php
session_start();
include 'config.php';

if (isset($_POST['update'])) {
    foreach ($_POST as $key => $value) {
        if ($key != 'update') {
            list($product_id, $cart_id) = explode('-', $key);
            $quantity = $value;

            // Update the quantity
            $update = mysqli_query($conn, "UPDATE cart SET quantity=$quantity WHERE cart_id=$cart_id");
        }
    }

    header("Location: show_cart.php");
    exit();
}
?>


<?php
// remove_from_cart.php
session_start();
include 'config.php';

if (isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    
    // Delete the item
    $delete = mysqli_query($conn, "DELETE FROM cart WHERE cart_id=$cart_id");
    
    if ($delete) {
        header("Location: show_cart.php");
        exit();
    } else {
        die("Error deleting item.");
    }
}
?>


<?php
// show_cart.php
session_start();
include 'config.php';

$session_id = session_id();

// Get all items in the cart for this session
$result = mysqli_query($conn, "SELECT c.*, p.* FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.session_id='$session_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <form action="update_cart.php" method="post">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div style="border: 1px solid #ddd; padding: 10px; margin: 5px;">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                    <?php if ($row['image']) { ?>
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="max-width: 100px;">
                    <?php } ?>
                    <input type="number" name="<?php echo $row['product_id'] . '-' . $row['cart_id']; ?>" min="1" value="<?php echo $row['quantity']; ?>">
                    <a href="remove_from_cart.php?cart_id=<?php echo $row['cart_id']; ?>">Remove</a>
                </div>
            <?php } ?>
            <button type="submit">Update Cart</button>
        </form>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>

    <br>
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();
?>


<?php
function add_to_cart($product_id) {
    global $mysqli;
    
    // Check if product exists
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Check if item is already in cart
        $cart_query = "SELECT * FROM cart WHERE product_id = ? AND user_id = ?";
        $cart_stmt = $mysqli->prepare($cart_query);
        $cart_stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
        $cart_stmt->execute();
        $cart_result = $cart_stmt->get_result();
        
        if ($cart_result->num_rows > 0) {
            // Update quantity
            $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = ? AND user_id = ?";
            $update_stmt = $mysqli->prepare($update_query);
            $update_stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
            $update_stmt->execute();
        } else {
            // Add new item
            $add_query = "INSERT INTO cart (product_id, user_id) VALUES (?, ?)";
            $add_stmt = $mysqli->prepare($add_query);
            $add_stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
            $add_stmt->execute();
        }
    }
}

function get_cart_items() {
    global $mysqli;
    
    $query = "SELECT c.id AS cart_id, p.*, c.quantity FROM products p JOIN cart c ON p.id = c.product_id WHERE c.user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->get_result();
}

function update_quantity($cart_item_id, $quantity) {
    global $mysqli;
    
    if ($quantity < 1) {
        delete_from_cart($cart_item_id);
        return;
    }
    
    $query = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $quantity, $cart_item_id);
    $stmt->execute();
}

function delete_from_cart($cart_item_id) {
    global $mysqli;
    
    $query = "DELETE FROM cart WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $cart_item_id);
    $stmt->execute();
}

function calculate_total() {
    global $mysqli;
    
    $total = 0;
    $result = get_cart_items();
    
    while ($row = $result->fetch_assoc()) {
        $total += $row['price'] * $row['quantity'];
    }
    
    return $total;
}
?>


<?php
include('cart_functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if ($cart_items = get_cart_items()): ?>
        <h1>Your Cart</h1>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $cart_items->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                            <input type="number" name="quantity" min="1" value="<?php echo $row['quantity']; ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td><?php echo $row['price'] * $row['quantity']; ?></td>
                    <td><a href="delete_from_cart.php?cart_id=<?php echo $row['cart_id']; ?>">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <h2>Total: <?php echo calculate_total(); ?></h2>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>


<?php
include('cart_functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    update_quantity($_POST['cart_id'], $_POST['quantity']);
}

header("Location: view_cart.php");
?>


<?php
include('cart_functions.php');

if (isset($_GET['cart_id'])) {
    delete_from_cart($_GET['cart_id']);
}

header("Location: view_cart.php");
?>


<?php
// Connect to database and fetch products
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <?php while ($row = $products->fetch_assoc()): ?>
        <div>
            <h2><?php echo $row['name']; ?></h2>
            <p><?php echo $row['description']; ?></p>
            <p>Price: <?php echo $row['price']; ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endwhile; ?>
</body>
</html>


<?php
include('cart_functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    add_to_cart($_POST['product_id']);
}

header("Location: product_listing.php");
?>


<?php
$mysqli = new mysqli('localhost', 'username', 'password', 'database_name');

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}
?>


include('db_connect.php');


<?php
session_start();
include('db_connection.php');

// Get all products from database
$query = "SELECT * FROM products";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product">';
        echo '<h3>' . $row['product_name'] . '</h3>';
        echo '<p>' . $row['description'] . '</p>';
        echo '<p>Price: $' . number_format($row['price'], 2) . '</p>';
        echo '<form action="add_to_cart.php" method="post">';
        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
        echo '<input type="number" name="quantity" min="1" max="' . $row['stock'] . '" value="1">';
        echo '<button type="submit">Add to Cart</button>';
        echo '</form>';
        echo '</div>';
    }
} else {
    echo "No products available";
}
$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Get product details from database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Check if product already exists in cart
        $cart_item = array(
            'id' => $product['id'],
            'name' => $product['product_name'],
            'price' => $product['price'],
            'quantity' => $quantity
        );

        if (isset($_SESSION['cart'])) {
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    // Update quantity
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_push($_SESSION['cart'], $cart_item);
            }
        } else {
            $_SESSION['cart'] = array($cart_item);
        }

        header("Location: cart.php");
        exit();
    }
}

$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$cart_items = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if (count($cart_items) > 0): ?>
        <h1>Your Shopping Cart</h1>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>" style="width: 50px;">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="delete_from_cart.php?product_id=<?php echo $item['id']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Total: $<?php 
            $total = 0;
            foreach ($cart_items as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            echo number_format($total, 2);
        ?></h3>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="order.php" method="post">
                <button type="submit">Order Now</button>
            </form>
        <?php else: ?>
            <p>Please login to place an order.</p>
            <a href="login.php">Login</a> | <a href="register.php">Register</a>
        <?php endif; ?>

    <?php else: ?>
        <h1>Your cart is empty!</h1>
    <?php endif; ?>

    <br><br>
    <a href="index.php">Continue Shopping</a>
</body>
</html>

<?php
$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate quantity is a positive integer
    if (is_numeric($quantity) && (int)$quantity > 0) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }

    header("Location: cart.php");
    exit();
}

$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    // Remove item from cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    header("Location: cart.php");
    exit();
}

$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'];
    $cart_items = $_SESSION['cart'];

    // Insert each cart item into orders table
    foreach ($cart_items as $item) {
        $query = "INSERT INTO orders (user_id, product_details, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isii", $user_id, serialize($item), $item['quantity'], $item['price']);
        if (!$stmt->execute()) {
            die('Error: ' . $stmt->error);
        }
    }

    // Clear the cart
    unset($_SESSION['cart']);

    echo "Order placed successfully!";
} else {
    echo "You need to login and have items in your cart to place an order.";
}

$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];

    $query = "INSERT INTO users (name, email, password_hash, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $email, $password, $phone);

    if ($stmt->execute()) {
        echo "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        die('Error: ' . $stmt->error);
    }
}

$conn->close();
?>


<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: cart.php");
            exit();
        }
    }

    echo "Invalid email or password!";
}

$conn->close();
?>


<?php
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['email']);
header("Location: index.html");
exit();
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_cart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();

// Helper functions for cart operations
require_once('cart_functions.php');

// Add item to cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    add_to_cart($product_id);
}

// Update cart quantity functionality
if (isset($_POST['update_cart'])) {
    foreach ($_POST as $key => $value) {
        if ($key != 'update_cart') {
            update_cart_quantity($key, $value);
        }
    }
}

// Remove item from cart functionality
if (isset($_GET['remove_item'])) {
    remove_from_cart($_GET['remove_item']);
}

// Checkout functionality
if (isset($_POST['checkout'])) {
    checkout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        .cart-summary {
            margin-top: 20px;
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Display products
        $result = mysqli_query($conn, "SELECT * FROM products");
        if (mysqli_num_rows($result) > 0) {
            echo "<h2>Products</h2>";
            echo "<div class='product-grid'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product-card'>";
                echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' style='max-width: 150px;'>";
                echo "<h3>" . $row['name'] . "</h3>";
                echo "<p>" . $row['description'] . "</p>";
                echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
                echo "</form>";
                echo "</div>";
            }
            echo "</div>";
        }
        ?>

        <?php
        // Display cart summary
        if (!empty($_SESSION['cart'])) {
            $total = calculate_total();
            echo "<h2>Your Cart</h2>";
            echo "<form method='post' action=''>";
            echo "<table>";
            echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";
            foreach ($_SESSION['cart'] as $item) {
                $product_id = $item['id'];
                $result = mysqli_query($conn, "SELECT * FROM products WHERE id=" . $product_id);
                if ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td><input type='number' name='" . $item['id'] . "' value='" . $item['quantity'] . "' min='1'></td>";
                    echo "<td>$" . number_format($row['price'], 2) . "</td>";
                    echo "<td>$" . number_format($row['price'] * $item['quantity'], 2) . "</td>";
                    echo "<td><a href='?remove_item=" . $item['id'] . "'>Remove</a></td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
            echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
            echo "<button type='submit' name='update_cart'>Update Cart</button>";
            echo "<button type='submit' formaction='checkout.php' formmethod='post' name='checkout'>Checkout</button>";
            echo "</form>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
// cart_functions.php
function add_to_cart($product_id) {
    global $conn;
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    $exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $exists = true;
            $item['quantity']++;
            break;
        }
    }
    
    if (!$exists) {
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id=" . $product_id);
        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['cart'][] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            );
        }
    }
}

function update_cart_quantity($item_id, $quantity) {
    global $conn;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}

function remove_from_cart($item_id) {
    global $conn;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
}

function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

function checkout() {
    // Process checkout here
    $_SESSION['cart'] = array();
    header("Location: index.php");
}
?>


<?php
session_start();

// Initialize products array (you can replace this with data from your database)
$products = array(
    1 => array('id' => 1, 'name' => 'Product 1', 'price' => 29.99),
    2 => array('id' => 2, 'name' => 'Product 2', 'price' => 49.99),
    3 => array('id' => 3, 'name' => 'Product 3', 'price' => 19.99)
);

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to the cart
function addToCart($productId) {
    global $products;
    
    if (isset($products[$productId])) {
        $product = $products[$productId];
        
        // Check if product is already in the cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity']++;
                return;
            }
        }
        
        // If not, add new item to the cart
        $_SESSION['cart'][] = array(
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        );
    }
}

// Function to update quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if ($newQuantity < 1) {
        return;
    }
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity;
            break;
        }
    }
}

// Function to delete an item from the cart
function deleteItem($productId) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($productId) {
        return $item['id'] != $productId;
    });
}

// Function to calculate total price of the cart
function getCartTotal() {
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}
?>


<?php
include('cart-functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
        .total-price {
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <?php if (!empty($_SESSION['cart'])) : ?>
        <div class="cart-items">
            <?php foreach ($_SESSION['cart'] as $item) : ?>
                <div class="cart-item">
                    <h3><?php echo $item['name']; ?></h3>
                    <p>Price: <?php echo $item['price']; ?>$</p>
                    <form action="" method="post">
                        <input type="hidden" name="productId" value="<?php echo $item['id']; ?>">
                        <label>Quantity: </label>
                        <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>">
                        <button type="submit">Update</button>
                    </form>
                    <a href="cart-functions.php?delete=<?php echo $item['id']; ?>">Delete</a>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="total-price">
            Total: <?php echo getCartTotal(); ?>$
        </p>

        <a href="products.php">Continue Shopping</a>
    <?php else : ?>
        <p>Your cart is empty.</p>
        <a href="products.php">Start shopping</a>
    <?php endif; ?>

    <!-- Handle form submissions -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $productId = $_POST['productId'];
        $newQuantity = $_POST['quantity'];
        updateQuantity($productId, $newQuantity);
        header('Location: cart.php');
        exit();
    }

    if (isset($_GET['delete'])) {
        deleteItem($_GET['delete']);
        header('Location: cart.php');
        exit();
    }
    ?>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create products table if not exists
$sql = "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10, 2),
    description TEXT
)
";

$conn->query($sql);

// Create cart table if not exists
$sql = "
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    quantity INT DEFAULT 1,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
)
";

$conn->query($sql);

// Add sample products
$sql = "INSERT INTO products (name, price, description) VALUES 
('Product 1', 19.99, 'Description for product 1'),
('Product 2', 29.99, 'Description for product 2'),
('Product 3', 39.99, 'Description for product 3')";

$conn->query($sql);

// Add to cart function
function add_to_cart($product_id, $quantity = 1) {
    global $conn;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $user_id = $_SESSION['user_id'];

    try {
        // Check if item already exists in cart
        $sql = "SELECT id FROM cart WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $product_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update quantity
            $sql = "UPDATE cart SET quantity = quantity + ? WHERE product_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iii', $quantity, $product_id, $user_id);
            $stmt->execute();
        } else {
            // Add new item
            $sql = "INSERT INTO cart (product_id, user_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iii', $product_id, $user_id, $quantity);
            $stmt->execute();
        }

        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Get cart contents
function get_cart_contents() {
    global $conn;

    if (!isset($_SESSION['user_id'])) {
        return array();
    }

    $user_id = $_SESSION['user_id'];

    try {
        $sql = "SELECT c.id, p.name, p.price, c.quantity 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return array();
    }
}

// Update cart quantity
function update_cart_quantity($cart_item_id, $quantity) {
    global $conn;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $quantity, $cart_item_id);
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Remove item from cart
function remove_from_cart($cart_item_id) {
    global $conn;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        $sql = "DELETE FROM cart WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $cart_item_id);
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:
$_SESSION['user_id'] = 1; // Set user ID

// Add item to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

// Update quantity
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    update_cart_quantity($_POST['cart_item_id'], $_POST['quantity']);
}

// Remove item from cart
if (isset($_GET['remove'])) {
    remove_from_cart($_GET['remove']);
}

// Display cart contents
$cart_contents = get_cart_contents();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php if (!empty($cart_contents)) : ?>
            <h3>Your Cart:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_contents as $item) : ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update_quantity" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                            <td>
                                <a href="?remove=<?php echo $item['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to remove this item?')" 
                                   class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>

        <!-- Add items to cart -->
        <h3 class="mt-4">Add Items:</h3>
        <div class="row">
            <?php
            // Get products from database
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            while ($product = $result->fetch_assoc()) :
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Add to cart using AJAX -->
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: window.location.href,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        location.reload(); // Refresh to show updated cart
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>


<?php
session_start();

// Database connection (replace with your own database credentials)
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add item to cart
function addToCart($item_id, $quantity) {
    global $conn;
    
    // Check if the item exists in the database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Item exists, add to cart
        $_SESSION['cart'][$item_id] = array(
            'id' => $item_id,
            'quantity' => $quantity
        );
    }
}

// Function to update cart quantity
function updateCart($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to calculate cart total
function getCartTotal() {
    global $conn;
    
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        // Get item price from database
        $sql = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $price = $result->fetch_assoc()['price'];
            $total += $price * $item['quantity'];
        }
    }
    
    return $total;
}

// Function to display cart content
function displayCart() {
    global $conn;
    
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        echo "<table>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item_id => $item) {
            // Get item details from database
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $item['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                
                echo "<tr>";
                echo "<td>" . $product['name'] . "</td>";
                echo "<td>$" . number_format($product['price'], 2) . "</td>";
                echo "<td><input type='number' name='quantity[" . $item_id . "]' value='" . $item['quantity'] . "' min='1'></td>";
                echo "<td>$" . number_format($product['price'] * $item['quantity'], 2) . "</td>";
                echo "<td><form method='post'><input type='hidden' name='remove' value='" . $item_id . "'><button type='submit'>Remove</button></form></td>";
                echo "</tr>";
            }
        }
        
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove'])) {
        removeFromCart($_POST['remove']);
    } elseif (isset($_POST['update'])) {
        foreach ($_POST['quantity'] as $item_id => $quantity) {
            updateCart($item_id, $quantity);
        }
    }
}

// Close database connection
$conn->close();
?>


<?php
// Initialize session
session_start();

// Create Cart class
class Cart {
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }

    // Add item to cart
    public function add_item($item_id, $item_name, $price) {
        if (empty($item_id)) {
            return "Item ID is required.";
        }
        
        if (!isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] = array(
                'item_id' => $item_id,
                'name' => $item_name,
                'price' => $price,
                'quantity' => 1
            );
        } else {
            $_SESSION['cart'][$item_id]['quantity']++;
        }
        
        return "Item added to cart successfully.";
    }

    // Remove item from cart
    public function remove_item($item_id) {
        if (isset($_SESSION['cart'][$item_id])) {
            unset($_SESSION['cart'][$item_id]);
            return "Item removed from cart successfully.";
        } else {
            return "Item not found in cart.";
        }
    }

    // Update item quantity
    public function update_quantity($item_id, $quantity) {
        if (!isset($_SESSION['cart'][$item_id])) {
            return "Item not found in cart.";
        }
        
        if ($quantity < 1) {
            return "Quantity must be at least 1.";
        }
        
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        return "Quantity updated successfully.";
    }

    // Get all items from cart
    public function get_items() {
        return $_SESSION['cart'];
    }

    // Calculate total price
    public function calculate_total() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
        return $total;
    }
}

// Create instance of Cart
$cart = new Cart();

// Handle form actions
$message = '';

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            if (!empty($_POST['item_id']) && !empty($_POST['price'])) {
                $result = $cart->add_item($_POST['item_id'], $_POST['name'], $_POST['price']);
                $message = $result;
            }
            break;

        case 'update':
            if (!empty($_POST['item_id']) && isset($_POST['quantity'])) {
                $result = $cart->update_quantity($_POST['item_id'], $_POST['quantity']);
                $message = $result;
            }
            break;

        case 'remove':
            if (!empty($_POST['item_id'])) {
                $result = $cart->remove_item($_POST['item_id']);
                $message = $result;
            }
            break;
    }
}

// Get cart items
$cart_items = $cart->get_items();
$total_price = $cart->calculate_total();

// Display cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "Your cart is empty.";
} else {
    echo "<table border='1'>";
    echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($cart_items as $item) {
        $total = $item['price'] * $item['quantity'];
        
        // Update form
        $update_form = "
            <form method='post'>
                <input type='hidden' name='action' value='update'>
                <input type='hidden' name='item_id' value='{$item['item_id']}'>
                Quantity: <input type='number' name='quantity' min='1' value='{$item['quantity']}'>
                <button type='submit'>Update</button>
            </form>
        ";
        
        // Remove form
        $remove_form = "
            <form method='post' style='display:inline;'>
                <input type='hidden' name='action' value='remove'>
                <input type='hidden' name='item_id' value='{$item['item_id']}'>
                <button type='submit'>Remove</button>
            </form>
        ";
        
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>\${$item['price']}</td>";
        echo "<td>{$update_form}</td>";
        echo "<td>\${$total}</td>";
        echo "<td>{$remove_form}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<h3>Total: \$$total_price</h3>";
}

// Display message
if (!empty($message)) {
    echo "<div class='alert alert-info'>$message</div>";
}
?>

<!-- Add item form -->
<form method="post">
    <input type="hidden" name="action" value="add">
    Item ID: <input type="text" name="item_id"><br>
    Name: <input type="text" name="name"><br>
    Price: <input type="number" name="price"><br>
    <button type="submit">Add to Cart</button>
</form>

<!-- Add some CSS for styling -->
<style>
.alert {
    padding: 10px;
    margin-top: 20px;
}
.alert-info {
    background-color: #f0f8ff;
    color: #346a8b;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 15px;
    text-align: left;
}
</style>


<?php
session_start();

// Sample products (you can replace this with your database)
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'price' => 29.99, 'description' => 'This is product 1'),
    array('id' => 2, 'name' => 'Product 2', 'price' => 49.99, 'description' => 'This is product 2'),
    array('id' => 3, 'name' => 'Product 3', 'price' => 19.99, 'description' => 'This is product 3')
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php foreach ($products as $product): ?>
        <div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <p>Price: <?php echo '$' . number_format($product['price'], 2); ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <input type="number" name="quantity" min="1" value="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>

    <a href="cart.php">View Cart</a>
</body>
</html>


<?php
session_start();

// Get product details from the form submission
$id = $_POST['id'];
$quantity = $_POST['quantity'];

// Check if cart exists in session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if product already exists in cart
if (array_key_exists($id, $_SESSION['cart'])) {
    // Update quantity
    $_SESSION['cart'][$id]['quantity'] += $quantity;
} else {
    // Add new product to cart
    $_SESSION['cart'][$id] = array(
        'id' => $id,
        'quantity' => $quantity,
        'price' => 29.99, // Replace with dynamic price from your products
    );
}

// Redirect back to the product list page
header('Location: index.php');
exit();


<?php
session_start();

// Get total price
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <table border="1">
            <tr>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>">
                    </td>
                    <td><?php echo '$' . number_format($item['price'], 2); ?></td>
                    <td><?php echo '$' . number_format(($item['price'] * $item['quantity']), 2); ?></td>
                    <td>
                        <button type="submit">Update</button>
                        </form>
                        <a href="delete_from_cart.php?id=<?php echo $item['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h3>Total: <?php echo '$' . number_format($total, 2); ?></h3>
        <a href="index.php">Continue Shopping</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
    
    <form action="checkout.php" method="post">
        <button type="submit">Checkout</button>
    </form>
</body>
</html>


<?php
session_start();

// Get product details from the form submission
$id = $_POST['id'];
$quantity = $_POST['quantity'];

if (isset($_SESSION['cart'][$id])) {
    // Update quantity
    $_SESSION['cart'][$id]['quantity'] = $quantity;
}

// Redirect back to the cart page
header('Location: cart.php');
exit();


<?php
session_start();

$id = $_GET['id'];

if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

// Redirect back to the cart page
header('Location: cart.php');
exit();


<?php
// config.php - Database configuration
$host = 'localhost';
$dbname = 'shopping_cart';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>



<?php
// functions.php - Cart functions

require_once 'config.php';

function get_products($conn) {
    $stmt = $conn->query("SELECT * FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_to_cart($product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        if (array_key_exists($product_id, $_SESSION['cart'])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = array(
                'id' => $product_id,
                'name' => get_product_name($conn, $product_id),
                'price' => get_product_price($conn, $product_id),
                'quantity' => $quantity
            );
        }
    } else {
        $_SESSION['cart'] = array();
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => get_product_name($conn, $product_id),
            'price' => get_product_price($conn, $product_id),
            'quantity' => $quantity
        );
    }
}

function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

function view_cart($conn) {
    if (isset($_SESSION['cart'])) {
        $items = $_SESSION['cart'];
        return $items;
    } else {
        return array();
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function update_quantity($product_id, $quantity) {
    if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

function get_product_name($conn, $product_id) {
    $stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['name'];
}

function get_product_price($conn, $product_id) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['price'];
}
?>



<?php
// view_cart.php - Display cart contents

session_start();
require_once 'functions.php';

$conn = require_once('config.php');

$cart_items = view_cart($conn);
$total = calculate_total();

echo "<h2>Your Shopping Cart</h2>";
if (!empty($cart_items)) {
    echo "<table>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($cart_items as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>$".number_format($item['price'], 2)."</td>";
        echo "<td><input type='text' name='quantity{$item['id']}' value='{$item['quantity']}' size='3'></td>";
        echo "<td>$".number_format($subtotal, 2)."</td>";
        echo "<td><a href='remove_from_cart.php?id={$item['id']}'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<p>Total: $".number_format($total, 2)."</p>";
    echo "<form method='post' action='update_cart.php'>";
    echo "<input type='submit' value='Update Cart'>";
    echo "</form>";
} else {
    echo "Your cart is empty.";
}
?>



<?php
// add_to_cart.php - Add item to cart

session_start();
require_once 'functions.php';

$conn = require_once('config.php');

if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $product_id = $_GET['id'];
    $quantity = $_GET['quantity'];
    
    add_to_cart($product_id, $quantity);
}

header("Location: view_cart.php");
exit();
?>



<?php
// remove_from_cart.php - Remove item from cart

session_start();
require_once 'functions.php';

$conn = require_once('config.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    remove_from_cart($product_id);
}

header("Location: view_cart.php");
exit();
?>



<?php
// update_cart.php - Update cart quantities

session_start();
require_once 'functions.php';

$conn = require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($_POST["quantity{$item['id']}"])) {
            $quantity = $_POST["quantity{$item['id']}"];
            update_quantity($item['id'], $quantity);
        }
    }
}

header("Location: view_cart.php");
exit();
?>



<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cart';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add to cart function
function addToCart($productId) {
    global $conn;
    
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = array();
    }
    
    // Check if product already exists in cart
    if (array_key_exists($productId, $cart)) {
        $cart[$productId]['quantity']++;
    } else {
        // Get product details from database
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $cart[$productId] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            );
        }
    }
    
    $_SESSION['cart'] = $cart;
}

// Remove from cart function
function removeFromCart($productId) {
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Update quantity function
function updateQuantity($productId, $quantity) {
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Display cart contents
function displayCart() {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>$$item[price]</td>";
            echo "<td><input type='number' name='quantity[" . $item['id'] . "]' value='" . $item['quantity'] . "'></td>";
            echo "<td><a href='remove.php?id=" . $item['id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
    }
}

// Calculate total price
function calculateTotal() {
    if (isset($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    } else {
        return 0;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart {
            width: 300px;
            float: right;
            padding: 20px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <!-- Products -->
    <?php
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div style='float:left; margin:10px; padding:10px; border:1px solid #ccc;'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>$$row[price]</p>";
        echo "<a href='add.php?id=" . $row['id'] . "'>Add to Cart</a>";
        echo "</div>";
    }
    ?>

    <!-- Cart -->
    <div class="cart">
        <h2>Your Cart</h2>
        <?php if (isset($_SESSION['cart'])): ?>
            <table>
                <?php displayCart(); ?>
            </table>
            <p>Total: $$<?php echo calculateTotal(); ?></p>
            <form action="update.php" method="post">
                <input type="submit" value="Update Cart">
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
session_start();
if (isset($_GET['id'])) {
    addToCart($_GET['id']);
}
header("Location: index.php");
?>


<?php
session_start();
if (isset($_GET['id'])) {
    removeFromCart($_GET['id']);
}
header("Location: index.php");
?>


<?php
session_start();
if (isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $productId => $qty) {
        updateQuantity($productId, $qty);
    }
}
header("Location: index.php");
?>


<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


<?php
session_start();
?>


<?php
include 'db_connection.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the item already exists in the cart for this session
    $checkCart = $conn->prepare("SELECT * FROM order_details WHERE product_id = ? AND order_id = ?");
    $checkCart->execute([$productId, session_id()]);

    if ($checkCart->rowCount() > 0) {
        // Update quantity
        $updateCart = $conn->prepare("UPDATE order_details SET quantity = quantity + ? WHERE product_id = ? AND order_id = ?");
        $updateCart->execute([$quantity, $productId, session_id()]);
    } else {
        // Insert new item
        $insertCart = $conn->prepare("INSERT INTO order_details (product_id, quantity, order_id) VALUES (?, ?, ?)");
        $insertCart->execute([$productId, $quantity, session_id()]);
    }
}
?>


<?php
include 'db_connection.php';

$cartItems = $conn->prepare("SELECT * FROM order_details WHERE order_id = ?");
$cartItems->execute([session_id()]);

$totalPrice = 0;
foreach ($cartItems as $item) {
    // Get product details
    $product = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $product->execute([$item['product_id']]);
    $productData = $product->fetch(PDO::FETCH_ASSOC);

    $price = $productData['price'];
    $totalPrice += ($price * $item['quantity']);
}

echo "<h2>Your Cart</h2>";
if ($cartItems->rowCount() > 0) {
    echo "<table>";
    foreach ($cartItems as $item) {
        // Fetch product details (as above)
        echo "<tr><td>{$productData['name']}</td><td>Quantity: {$item['quantity']}</td><td>Price: $".number_format($price, 2)."</td></tr>";
    }
    echo "</table>";
    echo "Total Price: $" . number_format($totalPrice, 2);
} else {
    echo "Your cart is empty.";
}
?>


<?php
include 'db_connection.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];

    $updateCart = $conn->prepare("UPDATE order_details SET quantity = ? WHERE product_id = ? AND order_id = ?");
    $updateCart->execute([$newQuantity, $productId, session_id()]);
}
?>


<?php
include 'db_connection.php';

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    $deleteItem = $conn->prepare("DELETE FROM order_details WHERE product_id = ? AND order_id = ?");
    $deleteItem->execute([$productId, session_id()]);
}
?>


<?php
include 'db_connection.php';

$products = $conn->prepare("SELECT * FROM products");
$products->execute();

foreach ($products as $product) {
    echo "<div class='product'>";
    echo "<h3>{$product['name']}</h3>";
    echo "<p>Description: {$product['description']}</p>";
    echo "<p>Price: \$" . number_format($product['price'], 2) . "</p>";
    echo "<form action='add_to_cart.php' method='post'>";
    echo "<input type='hidden' name='product_id' value='{$product['id']}'>";
    echo "<input type='number' name='quantity' min='1' value='1'>";
    echo "<button type='submit'>Add to Cart</button>";
    echo "</form>";
    echo "</div>";
}
?>


<?php
// Initialize session
session_start();

// Include database connection (assuming you have one)
include('db_connection.php');

// Get product data from database
$products = array();
$result = mysqli_query($conn, "SELECT * FROM products");
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Function to calculate cart total
function cart_total() {
    global $conn;
    $total = 0;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            
            // Get product price from database
            $query = "SELECT price FROM products WHERE id = $product_id";
            $result = mysqli_query($conn, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                $total += $row['price'] * $quantity;
            }
        }
    }
    
    return number_format($total, 2);
}

// Function to update cart
function update_cart($product_id, $action) {
    global $conn;
    
    if ($action == 'add') {
        // Add product to cart
        $query = "SELECT id, name, price FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $item = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            );
            
            if (!isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = $item;
            } else {
                $_SESSION['cart'][$product_id]['quantity']++;
            }
        }
    } elseif ($action == 'remove') {
        // Remove product from cart
        unset($_SESSION['cart'][$product_id]);
    } elseif ($action == 'update_quantity') {
        // Update quantity of product in cart
        $new_quantity = intval($_POST['quantity']);
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to display cart items
function display_cart() {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            echo "<tr>";
            echo "<td>{$item['name']}</td>";
            echo "<td>$$item[price]</td>";
            echo "<td><input type='number' value='{$item['quantity']}' onchange='updateQuantity({$item['id']})'></td>";
            echo "<td><a href='cart.php?action=remove&id={$item['id']}'>Remove</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}

// Function to process checkout
function process_checkout() {
    global $conn;
    
    if (isset($_SESSION['cart'])) {
        // Insert order into database
        date_default_timezone_set("UTC");
        $order_date = date('Y-m-d H:i:s');
        $total = cart_total();
        
        $query = "INSERT INTO orders (date, total) VALUES ('$order_date', $total)";
        mysqli_query($conn, $query);
        
        // Get order ID
        $order_id = mysqli_insert_id($conn);
        
        // Insert order details
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            
            $query = "INSERT INTO order_details (order_id, product_id, quantity) 
                     VALUES ($order_id, $product_id, $quantity)";
            mysqli_query($conn, $query);
        }
        
        // Clear cart
        unset($_SESSION['cart']);
        
        return true;
    } else {
        return false;
    }
}
?>


<?php
// Initialize cart array
$cart = array();

// Check if cookie exists
if (isset($_COOKIE['cart'])) {
    $cart = unserialize($_COOKIE['cart']);
}

// Add item to cart
if ($_GET['action'] == 'add') {
    $item_id = $_GET['id'];
    $item_name = $_GET['name'];
    $item_price = $_GET['price'];

    if (isset($cart[$item_id])) {
        // Item already in cart, increment quantity
        $cart[$item_id]['quantity']++;
    } else {
        // Add new item to cart
        $cart[$item_id] = array(
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => 1
        );
    }

    // Update cookie
    setcookie('cart', serialize($cart), time() + 3600);
}

// Remove item from cart
if ($_GET['action'] == 'remove') {
    $item_id = $_GET['id'];
    
    if (isset($cart[$item_id])) {
        unset($cart[$item_id]);
        setcookie('cart', serialize($cart), time() + 3600);
    }
}

// Update item quantity
if ($_POST['action'] == 'update') {
    foreach ($_POST['quantity'] as $item_id => $quantity) {
        if ($quantity < 1) {
            unset($cart[$item_id]);
        } else {
            $cart[$item_id]['quantity'] = $quantity;
        }
    }

    setcookie('cart', serialize($cart), time() + 3600);
}

// Calculate total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart</h1>
    
    <!-- Add items -->
    <div id="products">
        <a href="?action=add&id=1&name=Product%201&price=19.99">Add Product 1 ($19.99)</a><br>
        <a href="?action=add&id=2&name=Product%202&price=29.99">Add Product 2 ($29.99)</a><br>
        <a href="?action=add&id=3&name=Product%203&price=39.99">Add Product 3 ($39.99)</a><br>
    </div>

    <!-- Display cart -->
    <?php if (!empty($cart)): ?>
    <h2>Your Cart</h2>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <table>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($cart as $item_id => $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td><input type="text" name="quantity[<?php echo $item_id; ?>]" value="<?php echo $item['quantity']; ?>" size="3"></td>
                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                <td><a href="?action=remove&id=<?php echo $item_id; ?>">Remove</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p>Total: $<?php echo number_format($total, 2); ?></p>
        <input type="submit" value="Update Cart">
    </form>
    <?php else: ?>
    <p>Your cart is empty.</p>
    <?php endif; ?>

</body>
</html>


<?php
session_start();

// Check if session is not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function addToCart($item) {
    global $conn;
    
    // Item should have: id, name, price
    
    $sku = $item['id'];
    
    if (isset($_SESSION['cart'][$sku])) {
        // Update quantity
        $_SESSION['cart'][$sku]['quantity']++;
    } else {
        // Add new item
        $_SESSION['cart'][$sku] = array(
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => 1
        );
    }
}

// Remove item from cart
function removeFromCart($sku) {
    if (isset($_SESSION['cart'][$sku])) {
        unset($_SESSION['cart'][$sku]);
    }
}

// Update quantity of an item
function updateQuantity($sku, $quantity) {
    if ($quantity > 0 && isset($_SESSION['cart'][$sku])) {
        $_SESSION['cart'][$sku]['quantity'] = $quantity;
    } else {
        removeFromCart($sku);
    }
}

// Display cart content
function displayCart() {
    $output = '';
    
    if (empty($_SESSION['cart'])) {
        return '<p>Your cart is empty.</p>';
    }
    
    $output .= '
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
    
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        $output .= '
            <tr>
                <td>' . $item['name'] . '</td>
                <td>$' . number_format($item['price'], 2) . '</td>
                <td>
                    <input type="number" 
                           value="' . $item['quantity'] . '" 
                           onChange="updateQuantity(' . $item['id'] . ', this.value)">
                </td>
                <td>$' . number_format($subtotal, 2) . '</td>
                <td><a href="#" onclick="removeFromCart(' . $item['id'] . ')">Remove</a></td>
            </tr>';
    }
    
    $output .= '
            </tbody>
        </table>
        
        <p>Grand Total: $' . number_format($total, 2) . '</p>
        <button onclick="checkout()">Checkout</button>';
    
    return $output;
}

// Example usage:
$item1 = array(
    'id' => 1,
    'name' => 'Product 1',
    'price' => 19.99
);

addToCart($item1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .cart-table th, .cart-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .cart-table th {
            background-color: #f5f5f5;
        }
        
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>
    
    <!-- Add items to cart -->
    <div id="cart">
        <?php echo displayCart(); ?>
    </div>

    <script>
        function removeFromCart(sku) {
            if (confirm("Are you sure you want to remove this item?")) {
                window.location.href = 'index.php?action=remove&sku=' + sku;
            }
        }

        function updateQuantity(id, quantity) {
            window.location.href = 'index.php?action=update&id=' . id + '&quantity=' + quantity;
        }

        function checkout() {
            window.location.href = 'checkout.php';
        }
    </script>
</body>
</html>


<?php
$host = "localhost";
$user = "root";
$password = "";
$db_name = "your_database_name";

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
session_start();
include('db.php');

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
            echo "<img src='" . $row['image_path'] . "' width='100' />";
            echo "<form action='add_to_cart.php' method='post'>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='Add to Cart'>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "No products found.";
    }
    ?>
</body>
</html>


<?php
session_start();
include('db.php');

$product_id = $_POST['id'];

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
} else {
    $cart = array();
}

if (!array_key_exists($product_id, $cart)) {
    $cart[$product_id] = 1;
} else {
    $cart[$product_id]++;
}

$_SESSION['cart'] = $cart;

header("Location: index.php");
?>


<?php
session_start();
include('db.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <?php if (empty($cart)): ?>
        <h2>Your cart is empty.</h2>
    <?php else: ?>
        <h2>Your Cart:</h2>
        <form action="update_cart.php" method="post">
            <?php foreach ($cart as $product_id => $quantity): ?>
                <?php
                $sql = "SELECT * FROM products WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <div class="cart-item">
                    <?php echo $row['name']; ?>
                    <br>
                    Price: $<?php echo number_format($row['price'], 2); ?>
                    <br>
                    Quantity:
                    <input type="number" name="<?php echo $product_id; ?>" value="<?php echo $quantity; ?>">
                    <br>
                    Total: $<?php echo number_format($row['price'] * $quantity, 2); ?>
                    <br>
                    <a href="remove_item.php?id=<?php echo $product_id; ?>">Remove</a>
                </div>
            <?php endforeach; ?>
            <br>
            <input type="submit" value="Update Cart">
        </form>
    <?php endif; ?>

    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();
include('db.php');

$product_id = $_GET['id'];
$cart = $_SESSION['cart'];

if (array_key_exists($product_id, $cart)) {
    unset($cart[$product_id]);
}

$_SESSION['cart'] = $cart;

header("Location: view_cart.php");
?>


<?php
session_start();
include('db.php');

$cart = $_SESSION['cart'];

foreach ($_POST as $product_id => $quantity) {
    if (is_numeric($quantity)) {
        $cart[$product_id] = $quantity;
    }
}

$_SESSION['cart'] = $cart;

header("Location: view_cart.php");
?>


<?php
session_start();
include('db.php');

if (empty($_SESSION['cart'])) {
    header("Location: view_cart.php");
}

$cart = $_SESSION['cart'];
$total = 0;

foreach ($cart as $product_id => $quantity) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $total += $row['price'] * $quantity;
    }
}

$_SESSION['order_total'] = $total;

header("Location: payment.php");
?>


<?php
session_start();
$total = $_SESSION['order_total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body>
    <h2>Payment Information</h2>
    <p>Total Amount: $<?php echo number_format($total, 2); ?></p>
    <!-- Add payment form fields here -->
    <form action="process_payment.php" method="post">
        <input type="hidden" name="amount" value="<?php echo $total; ?>">
        <label>Credit Card Number:</label>
        <input type="text" name="card_number" required><br>
        <label>Expiration Date:</label>
        <input type="text" name="expiration_date" required><br>
        <label>CVV:</label>
        <input type="text" name="cvv" required><br>
        <input type="submit" value="Pay Now">
    </form>
</body>
</html>


<?php
session_start();
include('db.php');

$amount = $_POST['amount'];
// Process payment logic here (e.g., using Stripe, PayPal, etc.)

// For this example, we'll just clear the cart and show a success message.
$_SESSION['cart'] = array();

header("Location: thank_you.php");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
</head>
<body>
    <h2>Thank you for your purchase!</h2>
    <p>Your order has been successfully processed.</p>
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();
require_once 'db.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $quantity = 1) {
    global $conn;
    
    // Check if product exists
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        // Check if item is already in cart
        if (isset($_SESSION['cart'][$productId])) {
            // Update quantity
            $newQuantity = $_SESSION['cart'][$productId]['quantity'] + $quantity;
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        } else {
            // Add new item to cart
            $_SESSION['cart'][$productId] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image']
            );
        }
    } else {
        // Product not found
        return false;
    }

    return true;
}

// Function to update cart item quantity
function updateCartItem($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        return true;
    }
    return false;
}

// Function to delete item from cart
function deleteCartItem($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        return true;
    }
    return false;
}

// Function to calculate total price
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Function to display cart items
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    foreach ($_SESSION['cart'] as $item) {
        echo "<div class='cart-item'>";
        echo "<img src='" . $item['image'] . "' alt='" . $item['name'] . "'>";
        echo "<h3>" . $item['name'] . "</h3>";
        echo "<p>Price: $" . number_format($item['price'], 2) . "</p>";
        echo "<input type='number' value='" . $item['quantity'] . "' min='1' max='99'>";
        echo "<button onclick='updateQuantity(" . $item['id'] . ")'>Update</button>";
        echo "<button onclick='deleteItem(" . $item['id'] . ")'>Delete</button>";
        echo "</div>";
    }
}

// Function to empty the cart
function emptyCart() {
    $_SESSION['cart'] = array();
}

// Example usage:
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            if (!empty($_GET['id']) && !empty($_GET['quantity'])) {
                addToCart($_GET['id'], $_GET['quantity']);
            }
            break;
        case 'update':
            if (!empty($_GET['id'])) {
                updateCartItem($_GET['id'], $_GET['quantity']);
            }
            break;
        case 'delete':
            if (!empty($_GET['id'])) {
                deleteCartItem($_GET['id']);
            }
            break;
    }
}

// Display cart contents
displayCart();
?>


<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "shopping_cart";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


<?php
require_once 'config.php';

// Add product to cart
function addToCart($product_id) {
    global $conn;
    
    $session_id = UUID();
    
    try {
        // Check if product is already in cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND session_id = ?");
        $stmt->execute([$product_id, $session_id]);
        
        if ($stmt->rowCount() > 0) {
            updateCartQuantity($product_id, $session_id, 1);
            return true;
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (product_id, session_id) VALUES (?, ?)");
            $stmt->execute([$product_id, $session_id]);
            return true;
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Get all products
function getProducts() {
    global $conn;
    
    try {
        $stmt = $conn->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Get cart items
function getCartItems() {
    global $conn;
    
    $session_id = UUID();
    
    try {
        $stmt = $conn->prepare("SELECT c.*, p.* FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ?");
        $stmt->execute([$session_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Update cart quantity
function updateCartQuantity($product_id, $quantity) {
    global $conn;
    
    $session_id = UUID();
    
    try {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ? AND session_id = ?");
        $stmt->execute([$quantity, $product_id, $session_id]);
        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Delete item from cart
function deleteCartItem($product_id) {
    global $conn;
    
    $session_id = UUID();
    
    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND session_id = ?");
        $stmt->execute([$product_id, $session_id]);
        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Get cart totals
function getCartTotals() {
    global $conn;
    
    $session_id = UUID();
    
    try {
        $stmt = $conn->prepare("SELECT SUM(p.price * c.quantity) as subtotal FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ?");
        $stmt->execute([$session_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calculate tax and grand total
        $tax_rate = 0.13; // 13% tax rate
        $subtotal = $result['subtotal'];
        $tax = $subtotal * $tax_rate;
        $grand_total = $subtotal + $tax;
        
        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'grand_total' => $grand_total
        ];
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
?>


<?php
require_once 'functions.php';
$products = getProducts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .product-item {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .price {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Products</h1>
    <div class="product-grid">
        <?php foreach ($products as $product) { ?>
            <div class="product-item">
                <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" style="max-width: 200px;">
                <h3><?php echo $product['name']; ?></h3>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <?php if ($product['description']) { ?>
                    <p><?php echo $product['description']; ?></p>
                <?php } ?>
                <a href="add-to-cart.php?product_id=<?php echo $product['id']; ?>">Add to Cart</a>
            </div>
        <?php } ?>
    </div>
    
    <h2>Your Cart:</h2>
    <p><a href="cart.php">View Cart</a></p>
</body>
</html>


<?php
require_once 'functions.php';
if (!empty($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    
    if (addToCart($product_id)) {
        header("Location: index.php");
        exit();
    }
}
?>


<?php
require_once 'functions.php';
$cart_items = getCartItems();
$totals = getCartTotals();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px;
        }
        .cart-table th,
        .cart-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Your Cart</h1>
    
    <?php if ($cart_items) { ?>
        <table class="cart-table">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cart_items as $item) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="update-cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="delete-from-cart.php?product_id=<?php echo $item['id']; ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
        
        <h3>Cart Totals:</h3>
        <p>Subtotal: $<?php echo number_format($totals['subtotal'], 2); ?></p>
        <p>Tax (13%): $<?php echo number_format($totals['tax'], 2); ?></p>
        <p>Grand Total: $<?php echo number_format($totals['grand_total'], 2); ?></p>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>

    <h3><a href="index.php">Continue Shopping</a></h3>
</body>
</html>


<?php
require_once 'functions.php';
if (!empty($_POST['product_id']) && !empty($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity >= 1) {
        updateCartQuantity($product_id, $quantity);
    }
    
    header("Location: cart.php");
    exit();
}
?>


<?php
require_once 'functions.php';
if (!empty($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    
    deleteCartItem($product_id);
    
    header("Location: cart.php");
    exit();
}
?>


<?php
// Start session
session_start();

// Database connection (replace with your own database credentials)
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart handler
function cart_handler() {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'add':
                add_to_cart($_GET['product_id']);
                break;
            case 'view':
                view_cart();
                break;
            case 'update':
                update_quantity($_GET['product_id'], $_GET['quantity']);
                break;
            case 'remove':
                remove_item($_GET['product_id']);
                break;
        }
    }
}

// Add to cart function
function add_to_cart($product_id) {
    global $conn;

    // Check if product exists in database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if product is already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $row = $result->fetch_assoc();
            $_SESSION['cart'][$product_id] = array(
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            );
        }
    }
}

// View cart function
function view_cart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        $total = 0;
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;

            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>$" . number_format($item['price'], 2) . "</td>";
            echo "<td><input type='number' value='" . $item['quantity'] . "' onchange=\"updateQuantity($product_id, this.value)\"></td>";
            echo "<td>$" . number_format($subtotal, 2) . "</td>";
            echo "<td><a href='cart_handler.php?action=remove&product_id=$product_id'>Remove</a></td>";
            echo "</tr>";
        }

        echo "<tr><td colspan='4'>Total:</td><td>$" . number_format($total, 2) . "</td></tr>";
        echo "</table>";
    }
}

// Update quantity function
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id]) && $quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Remove item function
function remove_item($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Call cart handler
cart_handler();

$conn->close();
?>


session_start();


$products = [
    1 => ['name' => 'Laptop', 'price' => 999],
    2 => ['name' => 'Phone', 'price' => 499],
    // Add more products as needed
];


function get_cart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}


function add_to_cart($product_id) {
    $cart = get_cart();
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity']++;
    } else {
        $cart[$product_id] = [
            'id' => $product_id,
            'name' => $products[$product_id]['name'],
            'price' => $products[$product_id]['price'],
            'quantity' => 1
        ];
    }
    $_SESSION['cart'] = $cart;
}


function update_cart($product_id, $new_quantity) {
    if (!is_numeric($new_quantity) || $new_quantity < 1) {
        return false;
    }
    
    $cart = get_cart();
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
        $_SESSION['cart'] = $cart;
        return true;
    }
    return false;
}


function remove_from_cart($product_id) {
    $cart = get_cart();
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        $_SESSION['cart'] = $cart;
    }
}


function empty_cart() {
    $_SESSION['cart'] = [];
}


<?php
session_start();
include('cart_functions.php');

if (isset($_POST['product_id'])) {
    add_to_cart($_POST['product_id']);
}
header("Location: index.php");
exit();
?>


<?php
session_start();
include('cart_functions.php');
$cart = get_cart();

if (empty($cart)) {
    echo "Your cart is empty.";
} else {
    $total = 0;
    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        echo "<div>";
        echo "<h3>{$item['name']}</h3>";
        echo "<p>Quantity: {$item['quantity']}</p>";
        echo "<p>Price: \${$item['price']}</p>";
        echo "<p>Subtotal: \${$subtotal}</p>";
        echo "<form action='update_cart.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='{$item['id']}'>";
        echo "<input type='text' name='quantity' value='{$item['quantity']}'>";
        echo "<button type='submit'>Update</button>";
        echo "</form>";
        echo "<form action='remove_from_cart.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='{$item['id']}'>";
        echo "<button type='submit'>Remove</button>";
        echo "</form>";
        echo "</div><br/>";
    }
    echo "<h3>Total: \${$total}</h3>";
}
?>
<a href="index.php">Continue Shopping</a> | 
<form action="empty_cart.php" method="post">
    <button type="submit">Empty Cart</button>
</form>


<?php
session_start();
include('cart_functions.php');

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    update_cart($_POST['product_id'], $_POST['quantity']);
}
header("Location: view_cart.php");
exit();
?>


<?php
session_start();
include('cart_functions.php');

if (isset($_POST['product_id'])) {
    remove_from_cart($_POST['product_id']);
}
header("Location: view_cart.php");
exit();
?>


<?php
session_start();
include('cart_functions.php');
empty_cart();
header("Location: view_cart.php");
exit();
?>


<?php
session_start();
require_once('db_connection.php');

// Database connection function
function getConnection() {
    global $host, $username, $password, $dbname;
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Check if product exists
    $conn = getConnection();
    $sql = "SELECT * FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Check if item is already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $row = $result->fetch_assoc();
            $_SESSION['cart'][$product_id] = array(
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            );
        }
    }
    header("Location: cart.php");
    exit;
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}

// Update cart quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $key => $value) {
        if ($value == 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key]['quantity'] = $value;
        }
    }
    header("Location: cart.php");
    exit;
}

// Display cart items
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }
    
    $total = 0;
    echo "<table>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>₱" . number_format($item['price'], 2, '.', '') . "</td>";
        echo "<td><form action='cart.php' method='post'>";
        echo "<input type='number' name='quantity[$product_id]' value='{$item['quantity']}' min='0'>";
        echo "</form></td>";
        echo "<td><a href='cart.php?remove=$product_id'>Remove</a></td>";
        echo "</tr>";
        
        $total += $item['price'] * $item['quantity'];
    }
    echo "</table>";
    
    // Update cart quantity
    if (isset($_POST['update_cart'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            $_SESSION['cart'][$key]['quantity'] = $_POST['quantity'][$key];
        }
    }
    
    echo "<p>Total: ₱" . number_format($total, 2, '.', '') . "</p>";
}

// Display all products
function displayProducts() {
    $conn = getConnection();
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h3>{$row['name']}</h3>";
            echo "<p>₱" . number_format($row['price'], 2, '.', '') . "</p>";
            echo "<form action='cart.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
            echo "<input type='submit' name='add_to_cart' value='Add to Cart'>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "No products found.";
    }
}
?>


<?php
$host = 'localhost';
$dbname = 'your_database';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query('SELECT * FROM products');
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
</head>
<body>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <a href="add_to_cart.php?product_id=<?php echo $product['id']; ?>">Add to Cart</a>
        </div>
    <?php endforeach; ?>
</body>
</html>


<?php
session_start();

$product_id = $_GET['product_id'];
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

// Get product details
try {
    $conn = new PDO("mysql:host=localhost;dbname=your_database", 'root', '');
    $stmt = $conn->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($product && $quantity > 0) {
    // Get cart from cookie
    if (!isset($_COOKIE['cart'])) {
        $cart = array();
    } else {
        $cart = unserialize($_COOKIE['cart']);
        if (!is_array($cart)) {
            $cart = array();
        }
    }

    // Update or add product to cart
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] += $quantity;
    } else {
        $cart[$product_id] = array(
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        );
    }

    // Set the cookie again
    setcookie('cart', serialize($cart), time() + 3600);

    header("Location: product_list.php");
} else {
    die("Invalid product or quantity.");
}
?>


<?php
session_start();

// Get cart from cookie
if (!isset($_COOKIE['cart'])) {
    $cart = array();
} else {
    $cart = unserialize($_COOKIE['cart']);
    if (!is_array($cart)) {
        $cart = array();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
        <a href="product_list.php">Continue Shopping</a>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
            $total = 0;
            foreach ($cart as $product): 
                $subtotal = $product['price'] * $product['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $product['name']; ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
        <a href="checkout.php">Proceed to Checkout</a> | 
        <a href="product_list.php">Continue Shopping</a>
    <?php endif; ?>
</body>
</html>


<?php
session_start();

// Get cart from cookie
if (!isset($_COOKIE['cart'])) {
    header("Location: product_list.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>
    <form action="process_order.php" method="post">
        <div>
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="phone">Phone:</label><br>
            <input type="tel" id="phone" name="phone" required><br><br>

            <label for="address">Address:</label><br>
            <textarea id="address" name="address" rows="3" cols="40" required></textarea><br><br>

            <input type="submit" value="Place Order">
        </div>
    </form>
</body>
</html>


<?php
session_start();

// Get cart from cookie
if (!isset($_COOKIE['cart'])) {
    header("Location: product_list.php");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
        $conn = new PDO("mysql:host=localhost;dbname=your_database", 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert order
        $stmt = $conn->prepare('INSERT INTO orders (user_name, email, phone, address, total_amount, order_date) 
                              VALUES (:name, :email, :phone, :address, :total, NOW())');
        
        $cart_total = 0;
        foreach ($_COOKIE['cart'] as $item) {
            $cart_total += $item['price'] * $item['quantity'];
        }

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'total' => $cart_total
        ]);

        // Get last inserted order ID
        $order_id = $conn->lastInsertId();

        // Insert order line items
        foreach ($_COOKIE['cart'] as $item) {
            $stmt = $conn->prepare('INSERT INTO order_line_items (order_id, product_id, quantity, price)
                                  VALUES (:order_id, :product_id, :quantity, :price)');
            $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Clear cart
        setcookie('cart', '', time() - 3600);
        
        header("Location: order_confirmation.php?order_id=" . $order_id);

    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>


<?php
session_start();

$order_id = $_GET['order_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Thank you for your order!</h2>
    <p>Your order ID is: <?php echo $order_id; ?></p>
    <a href="product_list.php">Continue Shopping</a>
</body>
</html>


<?php
// Start session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Sample product data (you would typically fetch this from a database in a real application)
$products = array(
    1 => array('name' => 'Product 1', 'price' => 29.99),
    2 => array('name' => 'Product 2', 'price' => 49.99),
    3 => array('name' => 'Product 3', 'price' => 19.99),
);

// Function to add item to cart
function addToCart($productId, $quantity = 1) {
    global $products;
    
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = array(
            'id' => $productId,
            'name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => $quantity
        );
    } else {
        // If item exists, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to update item quantity
function updateQuantity($productId, $newQuantity) {
    if ($newQuantity > 0 && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    } elseif ($newQuantity <= 0) {
        removeFromCart($productId);
    }
}

// Function to calculate total price
function cartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .product-list, .cart {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

<?php if (!empty($_SESSION['cart'])) { ?>

<h2>Your Cart</h2>
<table class="cart">
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
    </tr>

<?php foreach ($_SESSION['cart'] as $item) { ?>

    <tr>
        <td><?php echo $item['name']; ?></td>
        <td>$<?php echo number_format($item['price'], 2); ?></td>
        <td><input type="number" name="quantity_<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>"></td>
        <td>$<?php echo number_format(($item['price'] * $item['quantity']), 2); ?></td>
        <td>
            <form action="" method="post">
                <input type="hidden" name="update_id" value="<?php echo $item['id']; ?>">
                <button type="submit">Update</button>
            </form>
            <form action="" method="post">
                <input type="hidden" name="remove_id" value="<?php echo $item['id']; ?>">
                <button type="submit">Remove</button>
            </form>
        </td>
    </tr>

<?php } ?>

    <tr>
        <td colspan="4">Total:</td>
        <td>$<?php echo number_format(cartTotal(), 2); ?></td>
    </tr>
</table>

<?php } else { ?>
    <h3>Your cart is empty!</h3>
<?php } ?>

<h2>Products</h2>
<table class="product-list">
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Action</th>
    </tr>

<?php foreach ($products as $id => $product) { ?>

    <tr>
        <td><?php echo $product['name']; ?></td>
        <td>$<?php echo number_format($product['price'], 2); ?></td>
        <td><a href="?add=<?php echo $id; ?>">Add to Cart</a></td>
    </tr>

<?php } ?>

</table>

<?php
// Handle form actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_id'])) {
        $productId = $_POST['update_id'];
        $newQuantity = intval($_POST["quantity_$productId"]);
        updateQuantity($productId, $newQuantity);
    } elseif (isset($_POST['remove_id'])) {
        $productId = $_POST['remove_id'];
        removeFromCart($productId);
    }
}

// Handle add to cart action
if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);
    addToCart($productId);
}
?>
</body>
</html>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $name, $price) {
    global $message;
    
    // Check if item is already in cart
    if (isset($_SESSION['cart'][$item_id])) {
        $message = "Item is already in your cart!";
        return false;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$item_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
        $message = "Item added to your cart!";
        return true;
    }
}

// Function to remove item from cart
function removeFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
        $message = "Item removed from your cart!";
    } else {
        $message = "Item not found in your cart!";
    }
}

// Function to update item quantity
function updateQuantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        $message = "Quantity updated!";
    } else {
        $message = "Item not found in your cart!";
    }
}

// Function to clear the entire cart
function clearCart() {
    $_SESSION['cart'] = array();
    $message = "Your cart has been cleared!";
}

// Display cart contents
function displayCart() {
    if (empty($_SESSION['cart'])) {
        return "<p>Your cart is empty!</p>";
    }
    
    $output = '<table border="1" cellpadding="5" cellspacing="0">';
    $output .= '<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>';
    
    foreach ($_SESSION['cart'] as $item_id => $item) {
        $total = $item['price'] * $item['quantity'];
        $output .= '<tr>';
        $output .= '<td>' . $item['name'] . '</td>';
        $output .= '<td>$' . number_format($item['price'], 2) . '</td>';
        $output .= '<td><input type="number" min="1" value="' . $item['quantity'] . '" onChange="updateQuantity(' . $item_id . ', this.value)"></td>';
        $output .= '<td>$' . number_format($total, 2) . '</td>';
        $output .= '<td><button onclick="removeFromCart(' . $item_id . ')">Remove</button></td>';
        $output .= '</tr>';
    }
    
    $grand_total = calculateTotal();
    $output .= '<tr><td colspan="4" align="right"><strong>Grand Total:</strong></td><td>$' . number_format($grand_total, 2) . '</td></tr>';
    $output .= '</table>';
    
    return $output;
}

// Calculate cart total
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}
?>


<?php
session_start();
include 'cart.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <div class="cart-container">
        <?php
            if (empty($_SESSION['cart'])) {
                echo "<p>Your cart is empty!</p>";
                exit;
            }
            
            echo "<h2>Review Your Order</h2>";
            echo displayCart();
            
            // Calculate totals
            $grand_total = calculateTotal();
            echo "<p><strong>Grand Total:</strong> $" . number_format($grand_total, 2) . "</p>";
        ?>

        <!-- Payment form -->
        <form action="process_payment.php" method="POST">
            <h3>Payment Information</h3>
            <div style="margin-bottom: 10px;">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required>
            </div>
            <div style="margin-bottom: 10px;">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </div>
            <div style="margin-bottom: 10px;">
                <label for="card_number">Card Number:</label><br>
                <input type="text" id="card_number" name="card_number" pattern="[0-9]{16}" required>
            </div>
            <button type="submit">Place Order</button>
        </form>
    </div>
</body>
</html>


<?php
// Include configuration file
include('config.php');

// Start session
session_start();

// Function to add product to cart
function addToCart($productId) {
    $product = getProductById($productId);
    
    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'description' => $product['description']
            );
        } else {
            $_SESSION['cart'][$productId]['quantity']++;
        }
    }
}

// Function to get product by ID
function getProductById($productId) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Function to update cart
function updateCart($productId, $quantity) {
    if ($quantity > 0 && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to delete product from cart
function deleteProductFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to calculate total price
function calculateTotal() {
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    
    return $total;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
        }
        
        .cart-actions {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <?php
        if (!empty($_SESSION['cart'])) {
            echo "<h1>Your Cart</h1>";
            echo "<table>";
            echo "<tr><th>Product Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
            
            foreach ($_SESSION['cart'] as $productId => $item) {
                $totalItemPrice = $item['price'] * $item['quantity'];
                
                echo "<tr>";
                echo "<td>" . $item['name'] . "</td>";
                echo "<td>" . $item['description'] . "</td>";
                echo "<td>₹" . number_format($item['price'], 2) . "</td>";
                echo "<td><input type='number' min='1' value='" . $item['quantity'] . "' onchange=\"updateQuantity('$productId', this.value)\"></td>";
                echo "<td>₹" . number_format($totalItemPrice, 2) . "</td>";
                echo "<td><a href='cart.php?action=delete&id=$productId'>Delete</a></td>";
                echo "</tr>";
            }
            
            $total = calculateTotal();
            echo "<tr><td colspan='5' style='text-align: right; font-weight: bold;'>Total:</td><td>₹" . number_format($total, 2) . "</td></tr>";
            echo "</table>";
            
            // Add your checkout button or other functionality here
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'add':
                    addToCart($_POST['product_id']);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    break;
                
                case 'update':
                    updateCart($_POST['product_id'], $_POST['quantity']);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    break;
                
                case 'delete':
                    deleteProductFromCart($_GET['id']);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    break;
            }
        }
        ?>
    </div>

    <script>
        function updateQuantity(productId, quantity) {
            fetch('cart.php?action=update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=' + quantity
            });
            
            location.reload();
        }
    </script>
</body>
</html>


<?php
include('config.php');
session_start();

// Get all products from database
try {
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <div class="product-container">
        <?php if (!empty($products)) { ?>
            <?php foreach ($products as $product) { ?>
                <div class="product-item">
                    <h3><?php echo $product['name']; ?></h3>
                    <p><?php echo $product['description']; ?></p>
                    <p>Price: ₹<?php echo number_format($product['price'], 2); ?></p>
                    <form action="cart.php?action=add" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No products available.</p>
        <?php } ?>
    </div>
</body>
</html>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    
    // Check if product is already in cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        $_SESSION['cart'][$productId] = array(
            'name' => $productName,
            'price' => 29.99, // You can retrieve this from your database
            'quantity' => 1
        );
    }
}

// Update cart quantities
if (isset($_POST['update_cart'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        if (isset($_POST[$key . '_quantity'])) {
            $_SESSION['cart'][$key]['quantity'] = $_POST[$key . '_quantity'];
        }
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    unset($_SESSION['cart'][$productId]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <!-- Include CSS files -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <?php if (!empty($_SESSION['cart'])): ?>
            <h2>Your Cart</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="number" name="<?php echo $key; ?>_quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update_cart">Update</button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?remove=<?php echo $key; ?>">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
                // Calculate total price
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            ?>

            <h3>Total: $<?php echo number_format($total, 2); ?></h3>
            
            <!-- Checkout button -->
            <form method="post" action="checkout.php">
                <button type="submit">Checkout</button>
            </form>

        <?php else: ?>
            <p>Your cart is empty. Please add some items to your cart.</p>
        <?php endif; ?>

    </div>

    <?php include('footer.php'); ?>
</body>
</html>


<!-- header.php -->
<nav>
    <a href="index.php">Home</a> |
    <a href="products.php">Products</a> |
    <a href="cart.php">Cart</a>
</nav>

<!-- footer.php -->
<footer>
    <p>&copy; 2023 Your Store Name</p>
</footer>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <h2>Our Products</h2>
        
        <!-- Add more products as needed -->
        <form method="post" action="cart.php">
            <input type="hidden" name="product_id" value="1">
            <input type="hidden" name="product_name" value="Product 1">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <form method="post" action="cart.php">
            <input type="hidden" name="product_id" value="2">
            <input type="hidden" name="product_name" value="Product 2">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <form method="post" action="cart.php">
            <input type="hidden" name="product_id" value="3">
            <input type="hidden" name="product_name" value="Product 3">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>


<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "cart_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
include 'config.php';
session_start();

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        echo "<h3>" . $row["name"] . "</h3>";
        echo "<p>Price: $" . $row["price"] . "</p>";
        echo "<img src='" . $row["image"] . "' alt='" . $row["name"] . "'>";
        echo "<a href='add_to_cart.php?id=" . $row["id"] . "' class='add-to-cart'>Add to Cart</a>";
        echo "</div>";
    }
} else {
    echo "No products available.";
}
?>


<?php
include 'config.php';
session_start();

if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        if (!array_key_exists($product_id, $_SESSION['cart'])) {
            $_SESSION['cart'][$product_id] = array(
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            );
        } else {
            $_SESSION['cart'][$product_id]['quantity']++;
        }
        
        echo "Product added to cart!";
    }
}
?>


<?php
session_start();

echo "<h2>Your Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

if (isset($_SESSION['cart'])) {
    $total = 0;
    
    foreach ($_SESSION['cart'] as $key => $value) {
        echo "<tr>";
        echo "<td>" . $value['name'] . "</td>";
        echo "<td>$" . number_format($value['price'], 2) . "</td>";
        echo "<td><form action='update_quantity.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='$key'>";
        echo "<input type='number' name='quantity' min='1' value='" . $value['quantity'] . "'>";
        echo "<button type='submit'>Update</button>";
        echo "</form></td>";
        echo "<td>$" . number_format($value['price'] * $value['quantity'], 2) . "</td>";
        echo "<td><a href='remove_item.php?id=$key'>Remove</a></td>";
        echo "</tr>";
        
        $total += $value['price'] * $value['quantity'];
    }
    
    echo "<tr><td colspan='4'><strong>Total:</strong></td><td><strong>$" . number_format($total, 2) . "</strong></td></tr>";
} else {
    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
}
echo "</table>";
?>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}
?>


<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'shopping_cart';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to add item to cart
function addToCart($item_id) {
    global $conn;
    
    // Get product details from database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $product = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => 1
        );
        
        // Add to cart in session
        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'][$item_id] = $product;
        } else {
            $_SESSION['cart'] = array($item_id => $product);
        }
    }
}

// Function to update quantity of an item in cart
function updateQuantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        if ($quantity < 1) {
            // Show error message
            echo "<script>alert('Quantity cannot be less than 1!');</script>";
            return;
        }
        
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}

// Function to delete item from cart
function deleteItem($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to display cart items
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        $total = 0;
        echo "<table class='cart-table'>";
        echo "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            echo "<tr>";
            echo "<td>{$item['name']}</td>";
            echo "<td>$$item[price]</td>";
            echo "<td><input type='number' value='{$item['quantity']}' min='1' onchange='updateQuantity($id, this.value)'></td>";
            echo "<td>$$subtotal</td>";
            echo "<td><button onclick='deleteItem($id)'>Delete</button></td>";
            echo "</tr>";
        }
        
        echo "<tr><td colspan='4'><strong>Grand Total:</strong></td><td>$$total</td></tr>";
        echo "</table>";
    } else {
        echo "Your cart is empty!";
    }
}
?>


<?php
// Initialize session
session_start();

// Check if session cart exists, create if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    
    // Create an item array for the product
    $item = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => 1
    );
    
    // Add the item to the cart
    $_SESSION['cart'][$productId] = $item;
}

// Update cart quantities
if (isset($_POST['update_cart'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity_') !== false) {
            $productId = substr($key, 9);
            if ($value > 0) {
                $_SESSION['cart'][$productId]['quantity'] = $value;
            }
        }
    }
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $productId = $_POST['product_id'];
    unset($_SESSION['cart'][$productId]);
}

// Function to display cart contents
function displayCart() {
    $totalPrice = 0;
    if (!empty($_SESSION['cart'])) {
        echo "<h3>Shopping Cart</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td><form method='post' action=''>
                    <input type='hidden' name='product_id' value='" . $item['id'] . "'>
                    <input type='number' name='quantity_" . $item['id'] . "' value='" . $item['quantity'] . "' min='1'>
                  </form></td>";
            echo "<td>$$item[price]</td>";
            echo "<td><form method='post' action=''>
                    <input type='hidden' name='product_id' value='" . $item['id'] . "'>";
                    <button type='submit' name='remove_item'>Remove</button>
                  </form></td>";
            echo "</tr>";
        }
        
        echo "<tr><td colspan='3'><strong>Total: $$totalPrice</strong></td>";
        echo "<td><form method='post' action='checkout.php'>";
              <button type='submit' name='checkout'>Checkout</button>
            </form></td></tr>";
        
        echo "</table>";
    } else {
        echo "Your cart is empty!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php displayCart(); ?>

    <!-- Add new products here -->
    <h3>Add Items to Cart</h3>
    <div class="products">
        <form method="post" action="">
            <input type="hidden" name="product_id" value="1">
            <input type="hidden" name="product_name" value="Product 1">
            <input type="hidden" name="product_price" value="29.99">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <form method="post" action="">
            <input type="hidden" name="product_id" value="2">
            <input type="hidden" name="product_name" value="Product 2">
            <input type="hidden" name="product_price" value="49.99">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <!-- Add more products as needed -->
    </div>
</body>
</html>


<?php
include 'db_connection.php';

// Fetch all products from database
$query = "SELECT * FROM products";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
        echo "<p>Description: " . $row['description'] . "</p>";
        echo "<form action='add_to_cart.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
        echo "<button type='submit'>Add to Cart</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "No products available.";
}
$conn->close();
?>


<?php
include 'db_connection.php';

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    
    if (!isset($_COOKIE['cart'])) {
        // Initialize cookie with new product
        setcookie('cart', $productId . "|1", time() + 3600);
    } else {
        // Update existing cookie
        $cartContent = explode('&', $_COOKIE['cart']);
        $updatedCart = array();
        $found = false;
        
        foreach ($cartContent as $item) {
            list($id, $quantity) = explode('|', $item);
            if ($id == $productId) {
                $quantity++;
                $found = true;
            }
            $updatedCart[] = $id . '|' . $quantity;
        }
        
        if (!$found) {
            array_push($updatedCart, $productId . '|1');
        }
        
        setcookie('cart', implode('&', $updatedCart), time() + 3600);
    }
    
    echo "Product added to cart!";
}
?>


<?php
include 'db_connection.php';

if (isset($_COOKIE['cart'])) {
    $cartContent = explode('&', $_COOKIE['cart']);
    $totalPrice = 0;
    
    foreach ($cartContent as $item) {
        list($productId, $quantity) = explode('|', $item);
        
        $query = "SELECT * FROM products WHERE id=" . $productId;
        $result = $conn->query($query);
        if ($row = $result->fetch_assoc()) {
            echo "<div class='cart-item'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Quantity: " . $quantity . "</p>";
            $priceTotal = $row['price'] * $quantity;
            $totalPrice += $priceTotal;
            echo "<p>Subtotal: $" . number_format($priceTotal, 2) . "</p>";
            echo "<form action='remove_from_cart.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $productId . "'>";
            echo "<button type='submit'>Remove</button>";
            echo "</form>";
            echo "</div>";
        }
    }
    echo "<h3>Total Price: $" . number_format($totalPrice, 2) . "</h3>";
} else {
    echo "Your cart is empty.";
}
$conn->close();
?>


<?php
include 'db_connection.php';

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    
    if (isset($_COOKIE['cart'])) {
        $cartContent = explode('&', $_COOKIE['cart']);
        
        foreach ($cartContent as $key => $item) {
            list($id, $quantity) = explode('|', $item);
            if ($id == $productId) {
                unset($cartContent[$key]);
                break;
            }
        }
        
        if (empty($cartContent)) {
            setcookie('cart', '', time() - 3600); // Delete cookie
        } else {
            setcookie('cart', implode('&', $cartContent), time() + 3600);
        }
    }
    
    echo "Product removed from cart!";
}
?>


<?php
include 'db_connection.php';

if (isset($_COOKIE['cart'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userName = $_POST['user_name'];
        $email = $_POST['email'];
        
        // Insert into orders table
        $orderQuery = "INSERT INTO orders (user_name, email) VALUES (?, ?)";
        $stmt = $conn->prepare($orderQuery);
        $stmt->bind_param("ss", $userName, $email);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        
        // Process cart items
        $cartContent = explode('&', $_COOKIE['cart']);
        foreach ($cartContent as $item) {
            list($productId, $quantity) = explode('|', $item);
            
            $orderDetailQuery = "INSERT INTO order_details (order_id, product_id, quantity)
                                VALUES (?, ?, ?)";
            $stmt = $conn->prepare($orderDetailQuery);
            $stmt->bind_param("iii", $orderId, $productId, $quantity);
            $stmt->execute();
        }
        
        // Clear cart
        setcookie('cart', '', time() - 3600);
        echo "Order placed successfully!";
    } else {
        // Display checkout form
        if (isset($_COOKIE['cart'])) {
            echo "<form action='checkout.php' method='post'>";
            echo "<input type='text' name='user_name' placeholder='Your Name' required><br>";
            echo "<input type='email' name='email' placeholder='Email Address' required><br>";
            echo "<button type='submit'>Place Order</button>";
            echo "</form>";
        } else {
            echo "Your cart is empty. Nothing to checkout!";
        }
    }
} else {
    echo "Your cart is empty.";
}
$conn->close();
?>


<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shopping_cart';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to add item to cart
function addToCart($productId) {
    global $conn;
    
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = array();
    }
    
    // Check if product exists in database
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        // Check if item is already in cart
        if (array_key_exists($productId, $cart)) {
            // Update quantity
            $cart[$productId]['quantity']++;
        } else {
            // Add new item
            $cart[$productId] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'description' => $product['description'],
                'stock' => $product['stock']
            );
        }
        
        $_SESSION['cart'] = $cart;
    } else {
        return false; // Product not found
    }
}

// Function to update cart item quantity
function updateCartItem($productId, $quantity) {
    global $conn;
    
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        
        if (array_key_exists($productId, $cart)) {
            // Update quantity only if new quantity is greater than 0 and less than or equal to stock
            if ($quantity > 0 && $quantity <= $cart[$productId]['stock']) {
                $cart[$productId]['quantity'] = $quantity;
                $_SESSION['cart'] = $cart;
            }
        }
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        
        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]);
            $_SESSION['cart'] = $cart;
        }
    }
}

// Function to get cart contents
function getCartContents() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return array();
    }
}

// Function to calculate total price
function calculateTotal() {
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    
    // Add tax (assuming 10% tax rate)
    $tax = $total * 0.10;
    return array('subtotal' => $total, 'tax' => $tax, 'grand_total' => $total + $tax);
}

// Function to display cart contents
function displayCart() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }
    
    $cart = $_SESSION['cart'];
    ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cart as $item) {
                ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['description']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><input type="number" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>"></td>
                    <td>$<?php echo number_format(($item['price'] * $item['quantity']), 2); ?></td>
                    <td><button onclick="removeFromCart(<?php echo $item['id']; ?>)">Remove</button></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <?php 
    // Display order summary
    $total = calculateTotal();
    ?>
    <div class="order-summary">
        <h3>Order Summary</h3>
        <p>Subtotal: $<?php echo number_format($total['subtotal'], 2); ?></p>
        <p>Tax: $<?php echo number_format($total['tax'], 2); ?></p>
        <p>Grand Total: $<?php echo number_format($total['grand_total'], 2); ?></p>
    </div>

    <?php
}

// Function to process order
function processOrder() {
    global $conn;

    // Check if cart is not empty
    if (!empty($_SESSION['cart'])) {
        // Insert order into database
        $orderSql = "INSERT INTO orders (user_id, product_id, quantity, order_date) VALUES ";
        
        foreach ($_SESSION['cart'] as $item) {
            $userId = isset($_POST['user_id']) ? $_POST['user_id'] : 1; // Assuming user is logged in
            $productId = $item['id'];
            $quantity = $item['quantity'];
            
            // Add to order SQL statement
            $orderSql .= "('$userId', '$productId', '$quantity', NOW()), ";
        }
        
        // Remove last comma and space
        $orderSql = rtrim($orderSql, ', ');
        
        if (mysqli_query($conn, $orderSql)) {
            // Clear cart
            unset($_SESSION['cart']);
            
            echo "<p>Order processed successfully!</p>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}
?>


<?php
include('cart.php');

// Sample product data
$products = array(
    1 => array(
        'id' => 1,
        'name' => 'Laptop',
        'price' => 999.99,
        'description' => 'High-performance laptop with latest features.',
        'stock' => 5
    ),
    2 => array(
        'id' => 2,
        'name' => 'Smartphone',
        'price' => 699.99,
        'description' => 'Latest smartphone with advanced camera system.',
        'stock' => 10
    )
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <?php if (isset($_GET['action'])) { ?>
        <?php switch ($_GET['action']) { 
            case 'add':
                addToCart($_GET['id']);
                break;
                
            case 'remove':
                removeFromCart($_GET['id']);
                break;
                
            case 'update':
                updateCartItem($_GET['id'], $_GET['quantity']);
                break;
        } ?>
    <?php } ?>

    <h1>Products</h1>
    <div class="products">
        <?php foreach ($products as $product) { ?>
            <div class="product">
                <h2><?php echo $product['name']; ?></h2>
                <p><?php echo $product['description']; ?></p>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <?php if ($product['stock'] > 0) { ?>
                    <form action="index.php" method="get">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                <?php } else { ?>
                    <p>Out of stock</p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <h2>Your Cart</h2>
    <?php displayCart(); ?>

    <?php if (!empty($_SESSION['cart'])) { ?>
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="order">
            <p>Enter your details:</p>
            <input type="text" name="user_id" placeholder="User ID" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Place Order</button>
        </form>
    <?php } ?>

    <?php if (isset($_POST['action']) && $_POST['action'] == 'order') {
        processOrder();
    } ?>
</body>
</html>


<?php
// config.php - Database configuration file
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "cart_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<?php
// add_to_cart.php - Add items to cart functionality

session_start();

$product_id = $_GET['id'];
$quantity = 1;

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    
    if (!array_key_exists($product_id, $cart)) {
        // Product not in cart, add it
        $cart[$product_id] = array(
            'id' => $product_id,
            'quantity' => $quantity
        );
    } else {
        // Product already exists, increment quantity
        $cart[$product_id]['quantity']++;
    }
} else {
    // Cart doesn't exist, create new cart
    $cart = array();
    $cart[$product_id] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );
}

$_SESSION['cart'] = $cart;

echo "Item added to cart!";
?>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - View Cart</title>
</head>
<body>

<?php
// view_cart.php - View items in cart functionality

session_start();

$cart = $_SESSION['cart'];
$total_price = 0;

if (!empty($cart)) {
    foreach ($cart as $item) {
        // Query database for product details
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $price = $row['price'];
            $total_price += $price * $quantity;
            
            echo "<div>";
            echo "Product: " . $row['name'] . "<br />";
            echo "Price: $" . number_format($price, 2) . "<br />";
            echo "Quantity: " . $quantity . "<br />";
            echo "</div>";
        }
    }
    
    echo "<h3>Total Price: $" . number_format($total_price, 2) . "</h3>";
} else {
    echo "Your cart is empty!";
}

?>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - Checkout</title>
</head>
<body>

<?php
// checkout.php - Checkout functionality

session_start();

if (isset($_POST['checkout'])) {
    // Process order
    
    $cart = $_SESSION['cart'];
    
    if (!empty($cart)) {
        foreach ($cart as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            
            // Insert into orders table
            $sql = "INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
            $stmt->execute();
        }
        
        // Clear cart
        unset($_SESSION['cart']);
        
        echo "Thank you for your purchase!";
    } else {
        echo "Your cart is empty!";
    }
}

?>

</body>
</html>


<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get products from database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'][$productId])) {
        // Get product details from database
        $sql = "SELECT * FROM products WHERE id = $productId";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);
        
        $_SESSION['cart'][$productId] = array(
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        );
    } else {
        $_SESSION['cart'][$productId]['quantity']++;
    }
}

// Update cart quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST as $key => $value) {
        if ($key != 'update_cart') {
            $product_id = $key;
            $quantity = $value;
            
            if (!empty($quantity) && is_numeric($quantity)) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            }
        }
    }
}

// Delete product from cart
if (isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        
        // Destroy session if cart is empty
        if (empty($_SESSION['cart'])) {
            session_unset();
            session_destroy();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .product-list { margin-bottom: 20px; }
        .cart-item { border: 1px solid #ddd; padding: 10px; margin: 5px 0; }
        .total-price { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Products</h1>
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div style="margin-bottom: 10px;">
                <h3><?php echo $product['name']; ?></h3>
                <p>Price: <?php echo $product['price']; ?> $</p>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Your Cart</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
                <div class="cart-item">
                    <p><?php echo $item['name']; ?></p>
                    <p>Price: <?php echo $item['price']; ?> $</p>
                    <input type="number" name="<?php echo $productId; ?>" value="<?php echo $item['quantity']; ?>" min="1">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                        <button type="submit" name="delete_product">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
            
            <div class="total-price">
                Total Price: <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item) {
                        $total += ($item['price'] * $item['quantity']);
                    }
                    echo $total . ' $';
                ?>
            </div>
            
            <button type="submit" name="update_cart">Update Cart</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="<?php echo $_SERVER['PHP_SELF']; ?>">Refresh Page</a>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>


<?php
// Start session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($id, $name, $price) {
    global $item_id;
    $item_id = uniqid(); // Generate unique ID for each item
    
    $new_item = array(
        'id' => $item_id,
        'product_id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => 1
    );
    
    $_SESSION['cart'][$item_id] = $new_item;
}

// Function to update cart item quantity
function updateCart($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to calculate total price
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
} else {
    echo "<table border='1'>";
    echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$$item[price]</td>";
        echo "<td><input type='number' value='$item[quantity]' onchange='updateQuantity($item[id], this.value)'></td>";
        echo "<td>$$subtotal</td>";
        echo "<td><a href='remove.php?id=$item[id]'>Remove</a></td>";
        echo "</tr>";
    }
    
    $total = calculateTotal();
    echo "<tr><td colspan='4'><strong>Total:</strong></td><td>$$total</td></tr>";
    echo "</table>";
}

// Example items to add to cart
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <!-- Add some sample products -->
    <h2>Products</h2>
    <div class="products">
        <div class="product">
            <h3>Product 1 - $10.99</h3>
            <a href="?action=add&id=1&name=Product%201&price=10.99">Add to Cart</a>
        </div>
        
        <div class="product">
            <h3>Product 2 - $15.99</h3>
            <a href="?action=add&id=2&name=Product%202&price=15.99">Add to Cart</a>
        </div>
        
        <div class="product">
            <h3>Product 3 - $7.99</h3>
            <a href="?action=add&id=3&name=Product%203&price=7.99">Add to Cart</a>
        </div>
    </div>

    <!-- Handle actions -->
    <?php
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'add') {
            addToCart($_GET['id'], $_GET['name'], $_GET['price']);
        } elseif ($_GET['action'] == 'remove') {
            removeFromCart($_GET['id']);
        }
    }
    ?>

</body>
</html>



<?php
// Initialize session
session_start();

// Function to add item to cart
function addToCart($product_id, $product_name, $price) {
    global $cart;
    
    // Check if session exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if product is already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => 1
        );
    }
    
    return true;
}

// Function to update cart item quantity
function updateCartItem($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        return true;
    }
    return false;
}

// Function to remove item from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    return false;
}

// Function to display cart items
function displayCart() {
    $total_price = 0;
    
    if (!empty($_SESSION['cart'])) {
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $total = $item['price'] * $item['quantity'];
            $total_price += $total;
            
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td> $" . number_format($item['price'], 2, '.', '') . "</td>";
            echo "<td><input type='number' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $item['product_id'] . ", this.value)'></td>";
            echo "<td> $" . number_format($total, 2, '.', '') . "</td>";
            echo "<td><button onclick='removeFromCart(" . $item['product_id'] . ")'>Remove</button></td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<h3>Total Price: $" . number_format($total_price, 2, '.', '') . "</h3>";
    } else {
        echo "Your cart is empty!";
    }
}

// Function to get items count
function getCartItemCount() {
    if (isset($_SESSION['cart'])) {
        return count($_SESSION['cart']);
    }
    return 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <script>
        function updateQuantity(product_id, quantity) {
            if (quantity > 0) {
                // Make AJAX call to update quantity
                alert("Quantity updated!");
            } else {
                removeFromCart(product_id);
            }
        }

        function removeFromCart(product_id) {
            // Make AJAX call to remove item
            alert("Item removed from cart!");
        }
    </script>
</head>
<body>
    <h1>Shopping Cart</h1>
    
    <!-- Add items to cart -->
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="product_id" value="1">
        <input type="hidden" name="product_name" value="Product 1">
        <input type="hidden" name="price" value="29.99">
        <button type="submit">Add Product 1 to Cart</button>
    </form>

    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="product_id" value="2">
        <input type="hidden" name="product_name" value="Product 2">
        <input type="hidden" name="price" value="49.99">
        <button type="submit">Add Product 2 to Cart</button>
    </form>

    <!-- Display cart -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        
        addToCart($product_id, $product_name, $price);
    }

    displayCart();
    ?>

</body>
</html>


<?php
session_start();

// Initialize products array (you can connect this to your database)
$products = [
    1 => ['name' => 'Product 1', 'price' => 20.00],
    2 => ['name' => 'Product 2', 'price' => 30.00],
    3 => ['name' => 'Product 3', 'price' => 40.00]
];

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $productId = intval($_GET['id']);
    
    if (isset($products[$productId])) {
        // Check if product is already in cart
        if (array_key_exists($productId, $_SESSION['cart'])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $productId,
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => 1
            ];
        }
    }
}

// Remove product from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $productId = intval($_GET['id']);
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
    }
}

// Update product quantity in cart
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    foreach ($_SESSION['cart'] as $key => $item) {
        if (isset($_POST["quantity{$item['id']}"])) {
            $newQuantity = intval($_POST["quantity{$item['id']}"]);
            
            if ($newQuantity > 0) {
                $_SESSION['cart'][$key]['quantity'] = $newQuantity;
            }
        }
    }
}

// Calculate cart totals
$cartTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartTotal += ($item['price'] * $item['quantity']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .cart {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .cart th, .cart td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <!-- Products List -->
    <h3>Add Items to Cart:</h3>
    <?php foreach ($products as $id => $product): ?>
        <div style="margin-bottom: 10px;">
            <strong><?php echo $product['name']; ?></strong> - $<?php echo number_format($product['price'], 2); ?>
            <a href="?action=add&id=<?php echo $id; ?>">Add to Cart</a>
        </div>
    <?php endforeach; ?>

    <!-- Cart Contents -->
    <?php if (!empty($_SESSION['cart'])): ?>
        <h3>Cart Items:</h3>
        <table class="cart">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="update">
                            <input type="number" name="quantity<?php echo $item['id']; ?>" min="1" value="<?php echo $item['quantity']; ?>" style="width: 50px;">
                    </td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <a href="?action=remove&id=<?php echo $item['id']; ?>">Remove</a>
                        <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4">Total:</td>
                <td>$<?php echo number_format($cartTotal, 2); ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Your cart is empty. Add some items to continue.</p>
    <?php endif; ?>
</body>
</html>


<?php
session_start();
?>


function addToCart($productId) {
    $cartItem = array(
        'product_id' => $productId,
        'quantity' => 1
    );

    // Check if cart is not set in session, initialize it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = $cartItem;
    }
}


function viewCart() {
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
        echo "Your cart is empty.";
        return;
    }

    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        // Assuming you have a way to get product details from database
        $product = getProductFromDatabase($item['product_id']);
        $price = $product['price'];
        $total += ($price * $item['quantity']);

        echo "<div>";
        echo "<h3>" . $product['name'] . "</h3>";
        echo "<p>Quantity: " . $item['quantity'] . "</p>";
        echo "<p>Price: $" . number_format($price, 2) . "</p>";
        echo "</div>";
    }

    echo "<h4>Total: $" . number_format($total, 2) . "</h4>";
}


function updateCart($productId, $quantity) {
    if (!isset($_SESSION['cart'])) {
        return;
    }

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}


function removeFromCart($productId) {
    if (!isset($_SESSION['cart'])) {
        return;
    }

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Re-index the array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}


<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
<?php
// Include your database connection and product retrieval function here

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            addToCart($_GET['product_id']);
            break;
        case 'remove':
            removeFromCart($_GET['product_id']);
            break;
    }
}

viewCart();
?>
</body>
</html>


<?php
session_start();
require 'database_connection.php';

function add_to_cart($product_id, $quantity = 1) {
    global $conn;
    
    // Check if the product exists in the database
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return false;
    }
    
    // Check if the product is already in the cart
    $session_id = session_id();
    $stmt = $conn->prepare("SELECT id FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param("si", $session_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update quantity
        $row = $result->fetch_assoc();
        $cart_id = $row['id'];
        $new_quantity = $quantity + get_cart_item_quantity($product_id);
        update_cart_quantity($cart_id, $new_quantity);
    } else {
        // Add new item to cart
        $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $session_id, $product_id, $quantity);
        $stmt->execute();
    }
    
    return true;
}

function get_cart_items() {
    global $conn;
    $session_id = session_id();
    
    $stmt = $conn->prepare("SELECT c.id AS cart_id, p.*, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_cart_item_quantity($product_id) {
    global $conn;
    $session_id = session_id();
    
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param("si", $session_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['quantity'];
    }
    
    return 0;
}

function update_cart_quantity($cart_id, $quantity) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $quantity, $cart_id);
    $stmt->execute();
}

function remove_from_cart($cart_id) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
}

function empty_cart() {
    global $conn;
    $session_id = session_id();
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
}

function calculate_total() {
    global $conn;
    $session_id = session_id();
    
    $total = 0;
    $cart_items = get_cart_items();
    
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}
?>


<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cart';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
session_start();
require 'cart.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; padding: 20px; }
        .product { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Shopping Cart</h1>
        
        <?php
        // Add sample products (you should replace this with your own product fetching logic)
        $products = array(
            array('id' => 1, 'name' => 'Product 1', 'price' => 10.00, 'description' => 'Description for Product 1'),
            array('id' => 2, 'name' => 'Product 2', 'price' => 20.00, 'description' => 'Description for Product 2'),
            array('id' => 3, 'name' => 'Product 3', 'price' => 30.00, 'description' => 'Description for Product 3')
        );
        
        foreach ($products as $product) {
            echo "<div class='product'>";
            echo "<h2>{$product['name']}</h2>";
            echo "<p>Price: \${$product['price']}</p>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='product_id' value='{$product['id']}'>";
            echo "<input type='number' name='quantity' min='1' value='1'>";
            echo "<button type='submit'>Add to Cart</button>";
            echo "</form>";
            echo "</div>";
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            
            add_to_cart($product_id, $quantity);
            header("Location: cart_view.php");
            exit();
        }
        ?>
    </div>
</body>
</html>


<?php
session_start();
require 'cart.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart View</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; padding: 20px; }
        .cart-item { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Shopping Cart</h1>
        
        <?php
        $cart_items = get_cart_items();
        if (empty($cart_items)) {
            echo "<p>Your cart is empty.</p>";
        } else {
            foreach ($cart_items as $item) {
                echo "<div class='cart-item'>";
                echo "<h3>{$item['name']}</h3>";
                echo "<p>Price: \${$item['price']}</p>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='cart_id' value='{$item['cart_id']}'>";
                echo "<input type='number' name='quantity' min='1' value='{$item['quantity']}'>";
                echo "<button type='submit'>Update Quantity</button>";
                echo "</form>";
                echo "<a href='remove_from_cart.php?cart_id={$item['cart_id']}'>Remove</a>";
                echo "</div>";
            }
            
            $total = calculate_total();
            echo "<h2>Total: \${$total}</h2>";
            echo "<a href='empty_cart.php'>Empty Cart</a>";
        }
        ?>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cart_id = $_POST['cart_id'];
            $quantity = $_POST['quantity'];
            
            update_cart_quantity($cart_id, $quantity);
            header("Location: cart_view.php");
            exit();
        }
        ?>
    </div>
</body>
</html>


<?php
session_start();
require 'cart.php';

if (isset($_GET['cart_id'])) {
    remove_from_cart($_GET['cart_id']);
    header("Location: cart_view.php");
    exit();
}
?>


<?php
session_start();
require 'cart.php';

empty_cart();
header("Location: cart_view.php");
exit();
?>


<?php
session_start();

// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "shop";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add to cart function
function add_to_cart($product_id) {
    global $conn;
    
    $_SESSION['cart'][$product_id] = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] + 1 : 1;
    return true;
}

// Get product details from database
function get_product_details($product_id) {
    global $conn;
    
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($result);
}

// Update cart function
function update_cart($product_id, $quantity) {
    global $conn;
    
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove from cart function
function remove_from_cart($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Display cart contents
function display_cart() {
    global $conn;
    
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty!";
        return;
    }
    
    $total = 0;
    echo "<table>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = get_product_details($product_id);
        echo "<tr>";
        echo "<td>" . $product['name'] . "</td>";
        echo "<td>Quantity: <input type='number' name='quantity[" . $product_id . "]' value='" . $quantity . "' min='1'></td>";
        echo "<td><button form='update-form'>Update</button></td>";
        echo "<td><a href='cart.php?action=remove&id=" . $product_id . "'>Remove</a></td>";
        echo "</tr>";
        
        $total += ($quantity * $product['price']);
    }
    echo "<tr><td>Total: $" . number_format($total, 2) . "</td></tr>";
    echo "</table>";
}

// Process cart actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            add_to_cart($_GET['id']);
            header("Location: cart.php");
            break;
            
        case 'remove':
            remove_from_cart($_GET['id']);
            header("Location: cart.php");
            break;
    }
}

// Update cart quantities
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        update_cart($product_id, $quantity);
    }
    header("Location: cart.php");
}
?>


<?php
session_start();

// Database connection (same as above)

if (!isset($_SESSION['cart'])) {
    header("Location: cart.php");
}

// Get user details from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment']);

    // Insert order into database
    $sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    $total_amount = calculate_total();
    
    // Assuming user is logged in and you have their ID stored in session
    $user_id = $_SESSION['user_id'];
    
    mysqli_stmt_bind_param($stmt, "id", $user_id, $total_amount);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($conn);

    // Insert order details
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql_details = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_details = mysqli_prepare($conn, $sql_details);
        $price = get_product_details($product_id)['price'];
        
        mysqli_stmt_bind_param($stmt_details, "iidi", $order_id, $product_id, $quantity, $price);
        mysqli_stmt_execute($stmt_details);
    }

    // Clear cart
    unset($_SESSION['cart']);
    
    header("Location: thank_you.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    
    <?php display_cart(); ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address"></textarea><br>

        <label for="payment">Payment Method:</label><br>
        <select id="payment" name="payment">
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
        </select><br>

        <input type="submit" name="checkout" value="Complete Purchase">
    </form>
</body>
</html>


<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thank You!</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Your order has been successfully processed.</p>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1>Our Products</h1>
    <?php
    // Sample products array
    $products = [
        ['id' => 1, 'name' => 'Product 1', 'price' => 29.99],
        ['id' => 2, 'name' => 'Product 2', 'price' => 49.99],
        ['id' => 3, 'name' => 'Product 3', 'price' => 19.99]
    ];
    
    foreach ($products as $product) {
        echo "<div class='product'>";
        echo "<h2>{$product['name']}</h2>";
        echo "<p>Price: \${$product['price']}</p>";
        echo "<button onclick='addToCart(".$product['id'].")'>Add to Cart</button>";
        echo "</div>";
    }
    ?>
    
    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=1'
            })
            .then(response => response.text())
            .then(data => {
                alert('Item added to cart!');
                // Optionally update the cart display here
            });
        }
    </script>
</body>
</html>


<?php
session_start();

// Check if product ID and quantity are provided
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    die('Invalid request');
}

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if product already in cart
if (array_key_exists($product_id, $_SESSION['cart'])) {
    // Update quantity
    $_SESSION['cart'][$product_id] += $quantity;
} else {
    // Add new product to cart
    $_SESSION['cart'][$product_id] = $quantity;
}

// Redirect back to products page or show success message
header('Location: view_cart.php');
exit();


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <div id="cart-items">
        <?php
        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is empty.</p>";
        } else {
            $total = 0;
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                // For this example, we'll assume product details are known
                // In a real application, you'd query the database for product details
                if ($product_id == 1) {
                    $name = 'Product 1';
                    $price = 29.99;
                } elseif ($product_id == 2) {
                    $name = 'Product 2';
                    $price = 49.99;
                } elseif ($product_id == 3) {
                    $name = 'Product 3';
                    $price = 19.99;
                }
                
                echo "<div class='cart-item'>";
                echo "<h3>{$name}</h3>";
                echo "<p>Price: \${$price}</p>";
                echo "<p>Quantity: <input type='number' value='{$quantity}' id='qty_{$product_id}'></p>";
                echo "<button onclick='updateCart(".$product_id.")'>Update</button>";
                echo "<button onclick='deleteItem(".$product_id.")'>Delete</button>";
                echo "</div>";
                
                $total += ($price * $quantity);
            }
            echo "<h2>Total: \${$total}</h2>";
        }
        ?>
    </div>
    
    <script>
        function updateCart(productId) {
            const quantity = document.getElementById('qty_' + productId).value;
            
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=' + quantity
            })
            .then(response => response.text())
            .then(data => {
                location.reload(); // Refresh to show updated cart
            });
        }
        
        function deleteItem(productId) {
            fetch('delete_item.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.text())
            .then(data => {
                location.reload(); // Refresh to show updated cart
            });
        }
    </script>
</body>
</html>


<?php
session_start();

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    die('Invalid request');
}

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

if ($quantity < 1) {
    die('Quantity must be at least 1');
}

if (isset($_SESSION['cart'][$product_id])) {
    if ($quantity == 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

header('Location: view_cart.php');
exit();


<?php
session_start();

if (!isset($_POST['product_id'])) {
    die('Invalid request');
}

$product_id = $_POST['product_id'];

if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

header('Location: view_cart.php');
exit();


<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Our Products</h2>
    
    <!-- Product Cards -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        // Sample product data
        $products = array(
            array('id' => 1, 'name' => 'Product 1', 'price' => 29.99, 'description' => 'This is a sample product'),
            array('id' => 2, 'name' => 'Product 2', 'price' => 49.99, 'description' => 'Another sample product'),
            array('id' => 3, 'name' => 'Product 3', 'price' => 19.99, 'description' => 'Third sample product'),
        );

        foreach ($products as $product) {
            echo '
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">'.$product['name'].'</h5>
                            <p class="card-text">$'.$product['price'].'</p>
                            <p class="card-text">'.$product['description'].'</p>
                            <button onclick="addToCart('.$product['id'].')" class="btn btn-primary">Add to Cart</button>
                        </div>
                    </div>
                </div>';
        }
        ?>
    </div>

    <!-- Shopping Cart Preview -->
    <div class="mt-4">
        <h3>Shopping Cart:</h3>
        <p id="cartCount">0 items</p>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
function addToCart(productId) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + productId
    })
    .then(response => response.text())
    .then(count => document.getElementById('cartCount').textContent = count + ' items');
}
</script>

</body>
</html>


<?php
session_start();

if (isset($_POST['id'])) {
    $productId = $_POST['id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // Increment quantity
        $_SESSION['cart'][$productId]++;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$productId] = 1;
    }
}

echo count($_SESSION['cart']);
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <?php
    if (empty($_SESSION['cart'])) {
        echo '<h2>Your cart is empty!</h2>';
    } else {
        $products = array(
            1 => array('name' => 'Product 1', 'price' => 29.99),
            2 => array('name' => 'Product 2', 'price' => 49.99),
            3 => array('name' => 'Product 3', 'price' => 19.99),
        );
        
        $totalPrice = 0;
        echo '<h2>Shopping Cart:</h2>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Product</th><th>Quantity</th><th>Total</th><th>Action</th></tr></thead>';
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            if (isset($products[$productId])) {
                $price = $products[$productId]['price'];
                $total = $price * $quantity;
                $totalPrice += $total;
                
                echo '<tr>';
                echo '<td>'.$products[$productId]['name'].'</td>';
                echo '<td><input type="number" value="'.$quantity.'" min="1"></td>';
                echo '<td>$'.$total.'</td>';
                echo '<td><button onclick="removeFromCart('.$productId.')">Remove</button></td>';
                echo '</tr>';
            }
        }
        
        echo '<tr><td colspan="3"><strong>Total: $'.$totalPrice.'</strong></td><td></td></tr>';
        echo '</table>';
    }
    ?>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
function removeFromCart(productId) {
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + productId
    })
    .then(response => response.text())
    .then(location.reload());
}
</script>

</body>
</html>


<?php
session_start();

if (isset($_POST['id'])) {
    $productId = $_POST['id'];
    
    if (!empty($_SESSION['cart']) && isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

header('Location: cart.php');
exit();
?>


<?php
session_start();
?>


<?php
$products = array(
    1 => array(
        'name' => 'Product 1',
        'price' => 25.00,
        'description' => 'Description for Product 1'
    ),
    2 => array(
        'name' => 'Product 2',
        'price' => 35.00,
        'description' => 'Description for Product 2'
    )
);
?>


<?php foreach ($products as $id => $product): ?>
    <div class="product">
        <h3><?php echo $product['name']; ?></h3>
        <p><?php echo $product['description']; ?></p>
        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
        <form action="add_to_cart.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
<?php endforeach; ?>


<?php
session_start();

$id = $_POST['id'];
$product = $products[$id];

if (isset($_SESSION['cart'][$id])) {
    // Increment quantity if item already exists in cart
    $_SESSION['cart'][$id]['quantity']++;
} else {
    // Add new item to cart
    $_SESSION['cart'][$id] = array(
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    );
}

header("Location: view_cart.php");
exit();
?>


<?php
session_start();

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

function displayCart() {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>$" . number_format($item['price'], 2) . "</td>";
            echo "<td><input type='number' name='quantity[" . $id . "]' value='" . $item['quantity'] . "'></td>";
            echo "<td>$" . number_format(($item['price'] * $item['quantity']), 2) . "</td>";
            echo "<td><button onclick=\"removeFromCart($id)\">Remove</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Your cart is empty!</td></tr>";
    }
}
?>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $id => $qty) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    } elseif (isset($_GET['remove'])) {
        $id = $_GET['remove'];
        unset($_SESSION['cart'][$id]);
    }

    header("Location: view_cart.php");
    exit();
}
?>


<?php
session_start();

$total = calculateTotal();
?>

<h2>Checkout</h2>
<p>Total Amount: $<?php echo number_format($total, 2); ?></p>

<form action="process_order.php" method="post">
    <div class="checkout-info">
        <h3>Billing Information</h3>
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="address" placeholder="Address" required>
    </div>

    <button type="submit">Place Order</button>
</form>


<?php
session_start();

if (isset($_SESSION['cart'])) {
    // Here you would typically connect to your database and process the order
    // For this example, we'll just clear the cart
    unset($_SESSION['cart']);
}

header("Location: thank_you.php");
exit();
?>


<h2>Thank You for Your Order!</h2>
<p>Your order has been processed successfully.</p>
<a href="index.php">Continue Shopping</a>


class Product {
    private $id;
    private $name;
    private $price;
    private $description;
    private $image;

    public function __construct($id, $name, $price, $description = '', $image = '') {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
}


class Cart {
    private $items;
    private $total;

    public function __construct($items = array()) {
        $this->items = $items;
        $this->calculateTotal();
    }

    public function addItem($productId, $quantity = 1) {
        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $this->items[$productId] = array(
                'product_id' => $productId,
                'quantity' => $quantity
            );
        }
        $this->calculateTotal();
    }

    public function removeItem($productId) {
        unset($this->items[$productId]);
        $this->calculateTotal();
    }

    public function updateQuantity($productId, $newQuantity) {
        if (isset($this->items[$productId])) {
            if ($newQuantity > 0) {
                $this->items[$productId]['quantity'] = $newQuantity;
            } else {
                unset($this->items[$productId]);
            }
            $this->calculateTotal();
        }
    }

    public function getItems() {
        return $this->items;
    }

    private function calculateTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            // Here you would fetch the product price from your database
            // For this example, we're assuming a fixed price of 10.00 per item
            $price = 10.00; 
            $total += ($item['quantity'] * $price);
        }
        $this->total = $total;
    }

    public function getTotal() {
        return $this->total;
    }
}


session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new Cart();
}

$cart = $_SESSION['cart'];


$product_id = (int)$_GET['product_id'];
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($product_id > 0 && $quantity > 0) {
    $cart->addItem($product_id, $quantity);
    $_SESSION['cart'] = $cart;
}


$product_id = (int)$_GET['product_id'];

if ($product_id > 0) {
    $cart->removeItem($product_id);
    $_SESSION['cart'] = $cart;
}


$product_id = (int)$_POST['product_id'];
$new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($product_id > 0 && $new_quantity >= 0) {
    $cart->updateQuantity($product_id, $new_quantity);
    $_SESSION['cart'] = $cart;
}


echo "<h2>Your Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Product</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

foreach ($cart->getItems() as $item) {
    // Fetch the product details from your database
    // For this example, we're using dummy data
    $product = new Product(
        $item['product_id'],
        "Product " . $item['product_id'],
        10.00,
        "Description for product " . $item['product_id'],
        "image_" . $item['product_id'] . ".jpg"
    );

    echo "<tr>";
    echo "<td>" . $product->getName() . "</td>";
    echo "<td><input type='number' name='quantity[" . $product->getId() . "]' value='" . $item['quantity'] . "' min='1'></td>";
    echo "<td>$" . ($product->getPrice() * $item['quantity']) . "</td>";
    echo "<td><a href='remove.php?product_id=" . $product->getId() . "'>Remove</a></td>";
    echo "</tr>";
}

echo "</table>";
echo "<h3>Total: $" . number_format($cart->getTotal(), 2) . "</h3>";


class Cart {
    // ... existing code ...

    public function saveToDatabase($user_id = null) {
        foreach ($this->items as $item) {
            $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
            $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $item['product_id'], $item['quantity']]);
        }
    }
}


class Cart {
    // ... existing code ...

    public static function loadFromDatabase($user_id) {
        $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $stmt = $db->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $items = array();
        
        foreach ($stmt->fetchAll() as $row) {
            $items[$row['product_id']] = array(
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity']
            );
        }
        
        return new Cart($items);
    }
}


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$username = 'username';
$password = 'password';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Include classes
include_once('Product.php');
include_once('Cart.php');

// Check if cart exists in session, if not, create a new one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new Cart();
}

$cart = $_SESSION['cart'];
?>


<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
session_start();

class Cart {
    private $db;

    public function __construct() {
        require_once 'db.php';
        $this->db = new mysqli($host, $username, $password, $dbname);
    }

    // Add item to cart
    public function addItem($productId) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $sql = "SELECT name, price FROM products WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if ($product) {
                $_SESSION['cart'][$productId] = array(
                    'id' => $productId,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => 1
                );
            }
        }
    }

    // Remove item from cart
    public function removeItem($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    // Update item quantity
    public function updateQuantity($productId, $quantity) {
        if ($quantity > 0 && isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
    }

    // Get cart items
    public function getCartItems() {
        return $_SESSION['cart'] ?? array();
    }

    // Calculate total price
    public function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
        return $total;
    }
}
?>


<?php
require_once 'db.php';
$query = "SELECT * FROM products";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>{$row['name']}</h3>";
        echo "<p>Price: \${$row['price']}</p>";
        echo "<a href='add_to_cart.php?id={$row['id']}'>Add to Cart</a>";
        echo "</div>";
    }
}
$conn->close();
?>


<?php
session_start();
require_once 'Cart.php';

$cart = new Cart();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $cart->addItem($productId);
}

header('Location: view_cart.php');
exit();
?>


<?php
require_once 'Cart.php';

$cart = new Cart();
$cartItems = $cart->getCartItems();

if (!empty($cartItems)) {
    echo "<table>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($cartItems as $item) {
        $totalItem = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>\${$item['price']}</td>";
        echo "<td><form method='post' action='update_cart.php'><input type='hidden' name='id' value='{$item['id']}'>";
        echo "<input type='number' name='quantity' min='1' value='{$item['quantity']}'>";
        echo "</form></td>";
        echo "<td>\${$totalItem}</td>";
        echo "<td><a href='remove_from_cart.php?id={$item['id']}'>Remove</a></td>";
        echo "</tr>";
    }
    
    $grandTotal = $cart->calculateTotal();
    echo "<tr><td colspan='4'><strong>Grand Total:</strong></td><td>\${$grandTotal}</td></tr>";
    echo "</table>";
} else {
    echo "Your cart is empty.";
}
?>


<?php
session_start();
require_once 'Cart.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0) {
        $cart = new Cart();
        $cart->updateQuantity($productId, $quantity);
    }
}

header('Location: view_cart.php');
exit();
?>


<?php
session_start();
require_once 'Cart.php';

if (isset($_GET['id'])) {
    $cart = new Cart();
    $cart->removeItem($_GET['id']);
}

header('Location: view_cart.php');
exit();
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    header('Location: view_cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    
    <form action="process_order.php" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        
        <label for="address">Address:</label><br>
        <textarea id="address" name="address" required></textarea><br>
        
        <label for="phone">Phone:</label><br>
        <input type="tel" id="phone" name="phone" required><br>
        
        <input type="submit" value="Place Order">
    </form>
</body>
</html>


<?php
session_start();
require_once 'Cart.php';
require_once 'db.php';

if (!isset($_SESSION['cart'])) {
    header('Location: view_cart.php');
    exit();
}

$cart = new Cart();
$cartItems = $cart->getCartItems();

// Insert into orders table
foreach ($cartItems as $item) {
    $sql = "INSERT INTO orders (product_id, quantity, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Clear cart
unset($_SESSION['cart']);

echo "Order placed successfully!";
header('Location: order_confirmation.php');
exit();
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    header('Location: view_cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Your Order Has Been Placed!</h1>
    
    <p>Thank you for your order. Your order details are as follows:</p>
    
    <?php
    $cart = new Cart();
    $cartItems = $cart->getCartItems();
    $grandTotal = $cart->calculateTotal();
    ?>
    
    <table>
        <tr><th>Product</th><th>Quantity</th><th>Total</th></tr>
        
        <?php foreach ($cartItems as $item): ?>
            <?php $totalItem = $item['price'] * $item['quantity']; ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($totalItem, 2); ?></td>
            </tr>
        <?php endforeach; ?>
        
        <tr><td colspan="3"><strong>Grand Total: $<?php echo number_format($grandTotal, 2); ?></strong></td></tr>
    </table>
    
    <p>Your order will be processed shortly. Please check your email for confirmation.</p>
</body>
</html>


<?php
// Initialize session
session_start();

// Check if cart exists in session, if not create empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $product_id = $_GET['id'];
    $product_name = $_GET['name'];
    $product_price = $_GET['price'];

    if (!array_key_exists($product_id, $_SESSION['cart'])) {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => $product_name,
            'quantity' => 1,
            'price' => $product_price
        );
    } else {
        // Increment quantity of existing product
        $_SESSION['cart'][$product_id]['quantity']++;
    }

    echo "Item added to cart";
}

// Update item quantity
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $product_id = $_GET['id'];
    $new_quantity = intval($_GET['quantity']);

    if ($new_quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }

    echo "Quantity updated";
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = $_GET['id'];
    
    unset($_SESSION['cart'][$product_id]);

    echo "Item removed from cart";
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
echo "<a href='products.php'>Continue Shopping</a><br><br>";

if (empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
} else {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;

        // Display each item
        echo "<div style='border:1px solid #ddd; padding:10px;margin-bottom:5px;'>";
        echo "Product ID: " . $item['id'] . "<br>";
        echo "Product Name: " . $item['name'] . "<br>";
        echo "Quantity: <input type='number' id='quantity-" . $item['id'] . "' value='" . $item['quantity'] . "' min='1'>";
        echo "<button onclick=\"updateQuantity(" . $item['id'] . ")\">Update</button><br>";
        echo "Price: \$" . number_format($item['price'], 2) . "<br>";
        echo "Subtotal: \$" . number_format($subtotal, 2) . "<br>";
        echo "<a href='cart.php?action=remove&id=" . $item['id'] . "'>Remove</a>";
        echo "</div>";
    }

    // Display total
    echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
}

// JavaScript for updating quantity dynamically
echo "<script>
function updateQuantity(id) {
    var new_quantity = document.getElementById('quantity-' + id).value;
    window.location.href = 'cart.php?action=update&id=' + id + '&quantity=' + new_quantity;
}
</script>";
?>



<?php
class ShoppingCart {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Add item to cart
    public function addItem($productId, $quantity = 1) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $userId = $_SESSION['user_id'] ?? null;

        try {
            // Check if item already exists in cart
            $stmt = $this->db->prepare("
                SELECT id FROM shopping_cart 
                WHERE product_id = ? AND user_id = ?
            ");
            $stmt->execute([$productId, $userId]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                // Update quantity
                $this->updateItemQuantity($existingItem['id'], $quantity);
                return true;
            } else {
                // Insert new item
                $stmt = $this->db->prepare("
                    INSERT INTO shopping_cart (product_id, quantity, user_id)
                    VALUES (?, ?, ?)
                ");
                $result = $stmt->execute([$productId, $quantity, $userId]);
                return $result;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // View cart items
    public function viewCart() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $userId = $_SESSION['user_id'] ?? null;

        try {
            $stmt = $this->db->prepare("
                SELECT sc.id, p.name, p.description, p.price, 
                       sc.quantity, sc.added_at
                FROM shopping_cart sc
                JOIN products p ON sc.product_id = p.id
                WHERE sc.user_id = ?
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Update item quantity
    public function updateItemQuantity($cartItemId, $quantity) {
        try {
            if ($quantity <= 0) {
                $this->removeItem($cartItemId);
                return true;
            }

            $stmt = $this->db->prepare("
                UPDATE shopping_cart 
                SET quantity = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $result = $stmt->execute([$quantity, $cartItemId]);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Remove item from cart
    public function removeItem($cartItemId) {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM shopping_cart 
                WHERE id = ?
            ");
            $result = $stmt->execute([$cartItemId]);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}


<?php
require_once 'ShoppingCart.php';

// Database connection
try {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

$cart = new ShoppingCart($db);

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    $cart->addItem($productId, $quantity);
}

// Update cart items
if (isset($_POST['update_cart'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'item_quantity_') === 0) {
            $cartItemId = substr($key, strlen('item_quantity_'));
            $cart->updateItemQuantity($cartItemId, $value);
        }
    }
}

// Remove item from cart
if (isset($_GET['remove_item'])) {
    $cartItemId = $_GET['remove_item'];
    $cart->removeItem($cartItemId);
}


session_start();
$_SESSION['user_id'] = $userId;


// config.php
<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "shopping_cart";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


// classes/Product.php
<?php
class Product {
    private $id;
    private $name;
    private $price;
    private $description;

    public function __construct($id, $name, $price, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
}
?>


// classes/Cart.php
<?php
session_start();

class Cart {
    private $cartItems = array();
    private $totalPrice = 0;

    // Add item to cart
    public function addItem($productId, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if ($this->isProductExists($productId)) {
            $this->updateQuantity($productId, $quantity);
        } else {
            $product = new Product(...$this->getProductDetails($productId));
            $item = array(
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $quantity
            );
            $_SESSION['cart'][$productId] = $item;
        }
    }

    // Check if product exists in cart
    private function isProductExists($productId) {
        return isset($_SESSION['cart'][$productId]);
    }

    // Update quantity of an item
    public function updateQuantity($productId, $quantity) {
        if ($this->isProductExists($productId)) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
    }

    // Remove item from cart
    public function removeItem($productId) {
        if ($this->isProductExists($productId)) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    // Calculate total price
    public function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return number_format($total, 2);
    }

    // Get product details from database
    private function getProductDetails($productId) {
        include_once 'config.php';
        $stmt = $conn->prepare("SELECT id, name, price, description FROM products WHERE id = ?");
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get all items in cart
    public function getCartItems() {
        return $_SESSION['cart'];
    }
}
?>


// classes/Checkout.php
<?php
class Checkout extends Cart {
    private $orderDetails;

    public function __construct($orderDetails) {
        $this->orderDetails = $orderDetails;
    }

    // Process order
    public function processOrder() {
        include_once 'config.php';

        $totalPrice = $this->calculateTotal();

        $stmt = $conn->prepare("INSERT INTO orders (name, email, address, total_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssd', $this->orderDetails['name'], $this->orderDetails['email'], $this->orderDetails['address'], $totalPrice);

        if ($stmt->execute()) {
            // Clear the cart
            $_SESSION['cart'] = array();
            return true;
        } else {
            return false;
        }
    }
}
?>


// index.php
<?php
include_once 'classes/Product.php';
include_once 'classes/Cart.php';

$cart = new Cart();

// Get all products from database
include_once 'config.php';
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item { margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .cart-total { font-size: 24px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Products</h1>
    <?php while ($product = $result->fetch_assoc()): ?>
        <div class="cart-item">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endwhile; ?>

    <?php if (!empty($cart->getCartItems())): ?>
        <h2>Your Cart</h2>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cart->getCartItems() as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="remove_from_cart.php?product_id=<?php echo $item['id']; ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3 class="cart-total">Total: $<?php echo $cart->calculateTotal(); ?></h3>
        <h3><a href="checkout.php">Checkout</a></h3>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>


// add_to_cart.php
<?php
session_start();
include_once 'classes/Cart.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $cart = new Cart();
    $cart->addItem($_POST['product_id'], $_POST['quantity']);
    header("Location: index.php");
} else {
    header("Location: index.php");
}
?>


// update_cart.php
<?php
session_start();
include_once 'classes/Cart.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $cart = new Cart();
    $cart->updateQuantity($_POST['product_id'], $_POST['quantity']);
    header("Location: index.php");
} else {
    header("Location: index.php");
}
?>


// remove_from_cart.php
<?php
session_start();
include_once 'classes/Cart.php';

if (isset($_GET['product_id'])) {
    $cart = new Cart();
    $cart->removeItem($_GET['product_id']);
    header("Location: index.php");
} else {
    header("Location: index.php");
}
?>


<?php
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'price' => 100, 'description' => 'This is product 1'),
    array('id' => 2, 'name' => 'Product 2', 'price' => 200, 'description' => 'This is product 2'),
    array('id' => 3, 'name' => 'Product 3', 'price' => 300, 'description' => 'This is product 3')
);

foreach ($products as $product) {
    echo "<div class='product'>";
    echo "<h3>{$product['name']}</h3>";
    echo "<p>Price: \${$product['price']}</p>";
    echo "<p>Description: {$product['description']}</p>";
    echo "<form action='add_to_cart.php' method='post'>";
    echo "<input type='hidden' name='id' value='{$product['id']}'>";
    echo "<input type='number' name='quantity' min='1' max='5' value='1'>";
    echo "<button type='submit'>Add to Cart</button>";
    echo "</form>";
    echo "</div>";
}
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

if (!empty($_SESSION['cart'])) {
    echo "<div class='cart'>";
    echo "<table>";
    echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>{$item['quantity']}</td>";
        echo "<td>\${$item['price']}</td>";
        echo "<td>\${$item['price'] * $item['quantity']}</td>";
        echo "<td><a href='remove_from_cart.php?id={$item['id']}'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<h3>Total: \${getCartTotal()}</h3>";
    echo "<button onclick=\"window.location.href='checkout.php'\">Checkout</button>";
    echo "</div>";
} else {
    echo "Your cart is empty!";
}
?>


<?php
session_start();

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['id'];
    $quantity = $_POST['quantity'];

    // Load products data
    include 'products.php';

    // Find product by ID
    foreach ($products as $product) {
        if ($product['id'] == $product_id) {
            $item = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            );

            // Add item to cart
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = $item;
            }
        }
    }

    header("Location: index.php");
    exit();
}
?>


<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    
    header("Location: index.php");
    exit();
}
?>


<?php
session_start();

if (!empty($_SESSION['cart'])) {
    echo "<h2>Checkout</h2>";
    echo "<p>Thank you for shopping with us!</p>";
    echo "<p>Your order has been placed successfully.</p>";
    echo "<button onclick=\"window.location.href='index.php'\">Continue Shopping</button>";
    
    // Clear the cart after checkout
    unset($_SESSION['cart']);
} else {
    header("Location: index.php");
}
?>


<?php
session_start();
require 'db/connect.php';
include_once 'classes/cart.class.php';

$cart = new Cart($pdo);

$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Store</title>
</head>
<body>
    <h1>Welcome to Our Store</h1>
    <?php foreach ($products as $product): ?>
        <div>
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
    <p><a href="cart.php">View Your Cart</a></p>
</body>
</html>


<?php
session_start();
require 'db/connect.php';
include_once 'classes/cart.class.php';

$cart = new Cart($pdo);

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $cart->addToCart($productId);
}

header("Location: index.php");
exit();


<?php
session_start();
require 'db/connect.php';
include_once 'classes/cart.class.php';

$cart = new Cart($pdo);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Shopping Cart</title>
</head>
<body>
    <?php if ($cart->getTotalItems() > 0): ?>
        <h1>Your Cart</h1>
        <table border="1">
            <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>
            <?php foreach ($cart->getCartItems() as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><form action="update_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                        <button type="submit">Update</button>
                    </form></td>
                    <td>$<?php echo number_format($item['total'], 2); ?></td>
                    <td><a href="remove_from_cart.php?product_id=<?php echo $item['id']; ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>Total: $<?php echo number_format($cart->getCartTotal(), 2); ?></p>
        <form action="checkout.php" method="POST">
            <button type="submit">Checkout</button>
        </form>
    <?php else: ?>
        <h2>Your cart is empty.</h2>
    <?php endif; ?>
    <p><a href="index.php">Continue Shopping</a></p>
</body>
</html>


<?php
session_start();
require 'db/connect.php';
include_once 'classes/cart.class.php';

$cart = new Cart($pdo);

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity']));
    $cart->updateQuantity($productId, $quantity);
}

header("Location: cart.php");
exit();


<?php
session_start();
require 'db/connect.php';
include_once 'classes/cart.class.php';

$cart = new Cart($pdo);

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $cart->removeFromCart($productId);
}

header("Location: cart.php");
exit();


<?php
session_start();
require 'db/connect.php';
include_once 'classes/cart.class.php';

$cart = new Cart($pdo);

if (isset($_POST['user_name'], $_POST['email'])) {
    $userName = htmlspecialchars($_POST['user_name']);
    $email = htmlspecialchars($_POST['email']);
    
    if ($cart->getTotalItems() > 0) {
        $totalAmount = $cart->getCartTotal();
        
        try {
            $sql = "INSERT INTO orders (user_name, email, total_amount) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userName, $email, $totalAmount]);
            
            // Clear the cart
            $cart->clearCart();
            
            header("Location: order_confirmation.php?order_id=" . $pdo->lastInsertId());
            exit();
        } catch (PDOException $e) {
            die("Error processing your order: " . $e->getMessage());
        }
    } else {
        die("Your cart is empty. Please add items to your cart before checking out.");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <?php if ($cart->getTotalItems() > 0): ?>
        <h1>Complete Your Purchase</h1>
        <form action="checkout.php" method="POST">
            <p>Your cart total: $<?php echo number_format($cart->getCartTotal(), 2); ?></p>
            <label for="user_name">Name:</label><br>
            <input type="text" id="user_name" name="user_name" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <button type="submit">Place Order</button>
        </form>
    <?php else: ?>
        <h2>Your cart is empty. Please add items to your cart before checking out.</h2>
    <?php endif; ?>
</body>
</html>


<?php
session_start();
require 'db/connect.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;

if ($order_id) {
    $sql = "SELECT * FROM orders WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        die("Order not found.");
    }
} else {
    die("Invalid order ID.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Your order details:</p>
    <ul>
        <li>Name: <?php echo $order['user_name']; ?></li>
        <li>Email: <?php echo $order['email']; ?></li>
        <li>Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></li>
        <li>Order Date: <?php echo date('Y-m-d H:i:s', strtotime($order['order_date'])); ?></li>
    </ul>
    <p>We will send a confirmation email to <?php echo $order['email']; ?> shortly.</p>
</body>
</html>


<?php
class Cart {
    private $pdo;
    private $cart;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        session_start();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = &$_SESSION['cart'];
    }

    public function addToCart($productId) {
        // Check if product exists
        try {
            $sql = "SELECT id FROM products WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$productId]);
            if ($stmt->fetch()) {
                if (isset($this->cart[$productId])) {
                    $this->cart[$productId]['quantity']++;
                } else {
                    $this->cart[$productId] = array(
                        'id' => $productId,
                        'quantity' => 1
                    );
                }
            }
        } catch (PDOException $e) {
            die("Error adding product: " . $e->getMessage());
        }
    }

    public function removeFromCart($productId) {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
        }
    }

    public function updateQuantity($productId, $quantity) {
        if ($quantity < 1) {
            $quantity = 1;
        }
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] = $quantity;
        }
    }

    public function clearCart() {
        $this->cart = array();
    }

    public function getCartItems() {
        $items = array();
        foreach ($this->cart as $productId => $item) {
            try {
                $sql = "SELECT * FROM products WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    $items[] = array(
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $item['quantity'],
                        'total' => $item['quantity'] * $product['price']
                    );
                }
            } catch (PDOException $e) {
                die("Error fetching product: " . $e->getMessage());
            }
        }
        return $items;
    }

    public function getCartTotal() {
        $total = 0.00;
        foreach ($this->cart as $productId => $item) {
            try {
                $sql = "SELECT price FROM products WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$productId]);
                $price = $stmt->fetchColumn();
                
                if ($price !== false) {
                    $total += $item['quantity'] * $price;
                }
            } catch (PDOException $e) {
                die("Error calculating total: " . $e->getMessage());
            }
        }
        return $total;
    }

    public function getTotalItems() {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }
}


<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_cart";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <h1>Welcome to Our Store</h1>
    
    <?php
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<h2>" . $row['name'] . "</h2>";
            echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
            echo "<p>Description: " . $row['description'] . "</p>";
            echo "<form action='add_to_cart.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
            echo "<button type='submit'>Add to Cart</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "No products available.";
    }
    ?>

    <p><a href="view_cart.php">View Cart</a></p>
</body>
</html>


<?php
session_start();
include 'config.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // Check if product exists
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $product = mysqli_fetch_assoc($result);
        
        // Add to cart
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = array(
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            );
        } else {
            $_SESSION['cart'][$product_id]['quantity']++;
        }
    }
}

header("Location: index.php");
exit();
?>


<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (!empty($_SESSION['cart'])): ?>
        <table border="1">
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $item): 
                $total += $item['price'] * $item['quantity'];
            ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="remove_from_cart.php?id=<?php echo $id; ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
        <form action="checkout.php" method="post">
            <p>Proceed to Checkout</p>
            <button type="submit">Checkout</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="index.php">Go back to shopping</a>
    <?php endif; ?>

    <p><a href="index.php">Continue Shopping</a></p>
</body>
</html>


<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    if (!empty($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

header("Location: view_cart.php");
exit();
?>


<?php session_start(); include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])): ?>
        <h1>Checkout</h1>
        
        <form action="process_order.php" method="post">
            <p><label>Your Name:</label><br>
            <input type="text" name="name" required></p>
            
            <p><label>Email:</label><br>
            <input type="email" name="email" required></p>
            
            <button type="submit">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="index.php">Go back to shopping</a>
    <?php endif; ?>
</body>
</html>


<?php session_start(); include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])):
        $name = $_POST['name'];
        $email = $_POST['email'];

        foreach ($_SESSION['cart'] as $id => $item):
            $sql = "INSERT INTO orders (product_id, user_name, user_email, quantity) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "isss", $id, $name, $email, $item['quantity']);
            mysqli_stmt_execute($stmt);
        endforeach;

        unset($_SESSION['cart']);
    ?>

        <h2>Thank you for your order!</h2>
        <p>Your order has been received and will be processed shortly.</p>
        <a href="index.php">Continue Shopping</a>

    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="index.php">Go back to shopping</a>
    <?php endif; ?>

<?php mysqli_close($conn); ?>
</body>
</html>


<?php
// Database connection
$host = 'localhost';
$dbname = 'shopping_cart';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Start session
session_start();

// Check if cart exists in session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId) {
    global $pdo;
    
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        $cart = $_SESSION['cart'];
        
        // Check if item already in cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            );
        }
        
        $_SESSION['cart'] = $cart;
    } else {
        echo "Product not found!";
    }
}

// Function to update cart item quantity
function updateCart($productId, $newQuantity) {
    global $pdo;
    
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Function to delete item from cart
function deleteFromCart($productId) {
    global $pdo;
    
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product && isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to display cart items
function displayCart() {
    global $pdo;
    
    $cart = $_SESSION['cart'];
    $total = 0;
    
    if (!empty($cart)) {
        echo "<table>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($cart as $item) {
            $productTotal = $item['price'] * $item['quantity'];
            $total += $productTotal;
            
            echo "<tr>";
            echo "<td>{$item['name']}</td>";
            echo "<td>$$item[price]</td>";
            echo "<td><input type='number' value='{$item['quantity']}' onChange='updateQuantity(".$item['id'].", this.value)'></td>";
            echo "<td>$$productTotal</td>";
            echo "<td><button onclick='deleteItem(".$item['id'].")'>Delete</button></td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<h3>Grand Total: $$total</h3>";
    } else {
        echo "Your cart is empty!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <script>
        function updateQuantity(productId, quantity) {
            if (quantity > 0) {
                // Make an AJAX call to update the quantity
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'update_cart.php?productId=' + productId + '&quantity=' + quantity);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        location.reload(); // Refresh to show updated cart
                    }
                };
                xhr.send();
            }
        }

        function deleteItem(productId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'delete_cart.php?productId=' + productId);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload(); // Refresh to show updated cart
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <h1>Shopping Cart</h1>
    
    <?php displayCart(); ?>

    <!-- Add more products here -->
    <div style="margin-top: 20px;">
        <h2>Available Products</h2>
        <?php
            $stmt = $pdo->query("SELECT * FROM products");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div>";
                echo "<h3>{$row['name']}</h3>";
                echo "<p>Price: $$row[price]</p>";
                echo "<button onclick='addToCart($row[id])'>Add to Cart</button>";
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>

// update_cart.php
<?php
session_start();
require_once 'db_connection.php';

if (isset($_GET['productId']) && isset($_GET['quantity'])) {
    $productId = $_GET['productId'];
    $newQuantity = $_GET['quantity'];
    
    if ($newQuantity < 1) {
        echo "Quantity must be at least 1!";
    } else {
        updateCart($productId, $newQuantity);
    }
}
?>

// delete_cart.php
<?php
session_start();
require_once 'db_connection.php';

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];
    deleteFromCart($productId);
}
?>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($item_id, $item_name, $price) {
    global $db;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity']++;
            return;
        }
    }

    // If not, add the new item to the cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'name' => $item_name,
        'price' => $price,
        'quantity' => 1
    );
}

// Function to remove item from cart
function remove_from_cart($item_id) {
    global $db;
    
    // Loop through the cart and unset the matching item
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Re-index the array keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Function to update item quantity in cart
function update_cart_quantity($item_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id && $new_quantity > 0) {
            $item['quantity'] = $new_quantity;
        }
    }
}

// Function to get items in cart
function get_cart_items() {
    return $_SESSION['cart'];
}

// Function to calculate total price of all items in cart
function calculate_total_price() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}
?>


<?php
session_start();
include('cart_functions.php');

// Sample product data (you can replace this with your actual product database)
$products = array(
    array(
        'id' => 1,
        'name' => 'Product 1',
        'price' => 29.99
    ),
    array(
        'id' => 2,
        'name' => 'Product 2',
        'price' => 49.99
    ),
    // Add more products as needed
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Store</title>
</head>
<body>
    <h1>Welcome to our store!</h1>
    
    <?php if (!empty($products)) { ?>
        <div class="product-list">
            <?php foreach ($products as $product) { ?>
                <div class="product-item">
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="item_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="item_name" value="<?php echo $product['name']; ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>No products available.</p>
    <?php } ?>

    <h2>Your Cart:</h2>
    <?php include('cart.php'); ?>

    <style>
        .product-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</body>
</html>

<?php
// Handle adding items to cart
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['item_id'], $_POST['item_name'], $_POST['price']);
}
?>


<?php
include('cart_functions.php');
$cart_items = get_cart_items();
$total_price = calculate_total_price();
?>

<div class="cart">
    <?php if (!empty($cart_items)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item) { ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td><input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1"></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <button type="submit" name="update_cart">Update</button>
                                <a href="?remove=<?php echo $item['id']; ?>">Remove</a>
                            </td>
                        </tr>
                    </form>
                <?php } ?>
            </tbody>
        </table>
        
        <h3>Total Price: $<?php echo number_format($total_price, 2); ?></h3>
        <button onclick="window.location.href='checkout.php'">Checkout</button>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>
</div>

<?php
// Handle removal and update of items from cart
if (isset($_GET['remove'])) {
    remove_from_cart($_GET['remove']);
} elseif (isset($_POST['update_cart'])) {
    update_cart_quantity($_POST['item_id'], $_POST['quantity']);
}
?>


<?php
session_start();
include('cart_functions.php');

if (empty(get_cart_items())) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    
    <?php include('cart.php'); ?>

    <form action="#" method="post">
        <!-- Add your checkout form fields here -->
        <h2>Billing Information:</h2>
        <p>Name: <input type="text" name="name"></p>
        <p>Email: <input type="email" name="email"></p>
        <p>Address: <textarea name="address"></textarea></p>
        
        <button type="submit">Place Order</button>
    </form>

    <?php
    // Handle the order placement here
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process the payment and place the order
        // You can add your payment processing logic here
        echo "<h2>Thank you for your order!</h2>";
        unset($_SESSION['cart']);
        header("refresh: 3; url=index.php");
    }
    ?>
</body>
</html>


<?php
session_start();

// Function to add item to cart
function addToCart($item_id, $item_name, $price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (!isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = array(
            'id' => $item_id,
            'name' => $item_name,
            'price' => $price,
            'quantity' => 1,
            'total' => $price * 1
        );
    } else {
        $_SESSION['cart'][$item_id]['quantity']++;
        $_SESSION['cart'][$item_id]['total'] = $_SESSION['cart'][$item_id]['price'] * $_SESSION['cart'][$item_id]['quantity'];
    }
}

// Function to update cart
function updateCart($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        $_SESSION['cart'][$item_id]['total'] = $_SESSION['cart'][$item_id]['price'] * $quantity;
    }
}

// Function to delete item from cart
function deleteFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to calculate total price
function totalCartPrice() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['total'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        .remove-btn {
            background-color: #ff4444;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <?php
        // Sample items (you can replace this with your own data source)
        $items = array(
            1 => array('id' => 1, 'name' => 'Product 1', 'price' => 25),
            2 => array('id' => 2, 'name' => 'Product 2', 'price' => 30),
            3 => array('id' => 3, 'name' => 'Product 3', 'price' => 45)
        );
        
        // Add sample item to cart
        if (isset($_GET['add'])) {
            $item_id = $_GET['add'];
            $item_name = $items[$item_id]['name'];
            $price = $items[$item_id]['price'];
            addToCart($item_id, $item_name, $price);
        }
        
        // Update cart
        if (isset($_POST['update'])) {
            foreach ($_POST['quantity'] as $item_id => $quantity) {
                updateCart($item_id, $quantity);
            }
        }
        
        // Delete item from cart
        if (isset($_GET['delete'])) {
            $item_id = $_GET['delete'];
            deleteFromCart($item_id);
        }
        ?>
        
        <h1>Shopping Cart</h1>
        
        <?php if (!empty($_SESSION['cart'])): ?>
        <form method="post">
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                
                <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>"></td>
                    <td>$<?php echo number_format($item['total'], 2); ?></td>
                    <td><a href="?delete=<?php echo $item['id']; ?>"><button class="remove-btn">Remove</button></a></td>
                </tr>
                <?php endforeach; ?>
                
                <tr>
                    <td colspan="3"></td>
                    <td><strong>Subtotal: $<?php echo number_format(totalCartPrice(), 2); ?></strong></td>
                    <td></td>
                </tr>
            </table>
            
            <input type="submit" name="update" value="Update Cart">
        </form>
        
        <a href="?add=1">Add Product 1</a> |
        <a href="?add=2">Add Product 2</a> |
        <a href="?add=3">Add Product 3</a> |
        <a href="#">Checkout</a> |
        <a href="#">View Store</a>
        
        <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="#">View Store</a>
        <?php endif; ?>
    </div>
</body>
</html>


session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


session_start();
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $name = $_GET['name'];
    $price = $_GET['price'];

    if (!isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    } else {
        $_SESSION['cart'][$item_id]['quantity']++;
    }
}


session_start();
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    echo "<table border='1'>";
    echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";
    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td><form method='post' action='update_cart.php'>";
        echo "<input type='hidden' name='item_id' value='$id'>";
        echo "<input type='number' name='quantity' min='1' value='" . $item['quantity'] . "'>";
        echo "<button type='submit'>Update</button>";
        echo "</form></td>";
        echo "<td><form method='post' action='remove_from_cart.php'>";
        echo "<input type='hidden' name='item_id' value='$id'>";
        echo "<button type='submit'>Remove</button>";
        echo "</form></td>";
        echo "</tr>";
    }
    echo "</table>";
}


session_start();
if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $quantity = max(1, intval($_POST['quantity']));

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}
header("Location: view_cart.php");


session_start();
if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    unset($_SESSION['cart'][$item_id]);
}
header("Location: view_cart.php");


<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_cart";

$conn = mysql_connect($host, $username, $password) or die("Connection failed: " . mysql_error());
mysql_select_db($dbname, $conn);
?>


<?php
session_start();
require_once('db_connection.php');

function addToCart($productId) {
    // Check if product exists in database
    $result = mysql_query("SELECT id FROM products WHERE id = '$productId'");
    
    if (mysql_num_rows($result) == 0) {
        die("Product does not exist.");
    }
    
    $sessionId = session_id();
    
    // Check if item already in cart
    $checkCart = mysql_query("SELECT * FROM cart WHERE product_id = '$productId' AND session_id = '$sessionId'");
    
    if (mysql_num_rows($checkCart) > 0) {
        // Update quantity
        mysql_query("UPDATE cart SET quantity = quantity + 1 WHERE product_id = '$productId' AND session_id = '$sessionId'");
    } else {
        // Add new item to cart
        mysql_query("INSERT INTO cart (product_id, quantity, session_id) VALUES ('$productId', '1', '$sessionId')");
    }
    
    echo "Item added to cart successfully!";
}

// Example usage:
if (isset($_GET['product_id'])) {
    addToCart($_GET['product_id']);
}
?>


<?php
session_start();
require_once('db_connection.php');

function viewCart() {
    $sessionId = session_id();
    
    // Get cart items
    $cartItems = mysql_query("SELECT c.id, p.name, p.price, c.quantity FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.session_id = '$sessionId'");
    
    if (mysql_num_rows($cartItems) == 0) {
        echo "Your cart is empty!";
        return;
    }
    
    while ($item = mysql_fetch_assoc($cartItems)) {
        $productId = $item['id'];
        $productName = $item['name'];
        $productPrice = $item['price'];
        $quantity = $item['quantity'];
        
        echo "Product ID: $productId <br>";
        echo "Name: $productName <br>";
        echo "Price: $$productPrice <br>";
        echo "Quantity: $quantity <br><br>";
    }
}

viewCart();
?>


<?php
session_start();
require_once('db_connection.php');

function updateCart($productId, $newQuantity) {
    if ($newQuantity <= 0) {
        deleteFromCart($productId);
        return;
    }
    
    $sessionId = session_id();
    
    mysql_query("UPDATE cart SET quantity = '$newQuantity' WHERE product_id = '$productId' AND session_id = '$sessionId'");
    
    echo "Quantity updated successfully!";
}

// Example usage:
if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    updateCart($_GET['product_id'], $_GET['quantity']);
}
?>


<?php
session_start();
require_once('db_connection.php');

function deleteFromCart($productId) {
    $sessionId = session_id();
    
    mysql_query("DELETE FROM cart WHERE product_id = '$productId' AND session_id = '$sessionId'");
    
    echo "Item removed from cart successfully!";
}

// Example usage:
if (isset($_GET['product_id'])) {
    deleteFromCart($_GET['product_id']);
}
?>


<?php
session_start();
require_once('db_connection.php');

function clearCart() {
    $sessionId = session_id();
    
    mysql_query("DELETE FROM cart WHERE session_id = '$sessionId'");
    
    echo "Cart cleared successfully!";
}

clearCart();
?>


<?php
session_start();

// Add item to cart
echo addToCart(1); // Assuming product ID 1 exists

// View cart contents
viewCart();

// Update quantity of an item in cart
updateCart(1, 2);

// Delete an item from cart
deleteFromCart(1);

// Clear all items from cart
clearCart();
?>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'])) {
        // Check if product already exists in cart
        if (array_key_exists($product_id, $_SESSION['cart'])) {
            // Increment quantity
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $_SESSION['cart'][$product_id] = array(
                'id' => $product_id,
                'quantity' => $quantity
            );
        }
    } else {
        // Initialize cart if not exists
        $_SESSION['cart'] = array(
            $product_id => array(
                'id' => $product_id,
                'quantity' => $quantity
            )
        );
    }

    header("Location: index.php?message=Product added to cart successfully!");
}
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Cart</title>
    <style>
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px;
        }
        .cart-table th, .cart-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    <?php
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        echo '<table class="cart-table">';
        echo '<tr><th>Product ID</th><th>Quantity</th><th>Action</th></tr>';
        
        foreach ($_SESSION['cart'] as $item) {
            echo '<tr>';
            echo '<td>' . $item['id'] . '</td>';
            echo '<td>' . $item['quantity'] . '</td>';
            echo '<td>';
            echo '<form action="update_quantity.php" method="post">';
            echo '<input type="hidden" name="product_id" value="' . $item['id'] . '">';
            echo '<input type="number" name="new_quantity" min="1" value="' . $item['quantity'] . '" style="width:50px;">';
            echo '<button type="submit">Update</button>';
            echo '</form>';
            echo '<br>';
            echo '<a href="remove_item.php?product_id=' . $item['id'] . '">Remove</a>';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>

    <?php
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        // You can calculate total price here and proceed to checkout
        echo '<h2>Grand Total:</h2>';
        // Add your calculation logic here
        echo '<a href="checkout.php">Proceed to Checkout</a>';
    }
    ?>

    <br>
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
        // Validate quantity input
        if (is_numeric($new_quantity) && $new_quantity >= 1) {
            $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
            header("Location: view_cart.php?message=Quantity updated successfully!");
        } else {
            header("Location: view_cart.php?error=Invalid quantity entered!");
        }
    } else {
        header("Location: view_cart.php?error=Product not found in cart!");
    }
}
?>


<?php
session_start();

if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // Re-index the cart array to maintain sequential keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

header("Location: view_cart.php");
?>


<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    
    <?php
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
        echo '<p>Your cart is empty. Please add items to continue checkout.</p>';
        echo '<a href="index.php">Continue Shopping</a>';
        exit();
    }
    ?>

    <h2>Review Your Order:</h2>
    <?php
    foreach ($_SESSION['cart'] as $item) {
        // Here you would typically fetch product details from a database
        // For this example, we'll just display the product ID and quantity
        echo '<p>Product #' . $item['id'] . ' x' . $item['quantity'] . '</p>';
    }
    
    // Add your payment processing logic here
    ?>

    <h2>Payment Details:</h2>
    <form action="process_payment.php" method="post">
        <!-- Add your payment form fields here -->
        <input type="text" name="card_number" placeholder="Card Number" required>
        <input type="text" name="card_name" placeholder="Name on Card" required>
        <input type="text" name="expiry_date" placeholder="Expiry Date (MM/YY)" required>
        <input type="password" name="cvv" placeholder="CVV" required>
        <button type="submit">Complete Purchase</button>
    </form>

    <br>
    <a href="view_cart.php">Back to Cart</a>
</body>
</html>


<?php
session_start();

// Here you would typically implement your payment processing logic
// For this example, we'll just display a success message

unset($_SESSION['cart']); // Clear the cart after purchase

header("Location: thank_you.php");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
</head>
<body>
    <h1>Thank You for Your Purchase!</h1>
    <p>Your order has been processed successfully.</p>
    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Product table structure:
// CREATE TABLE products (
//     id INT PRIMARY KEY AUTO_INCREMENT,
//     name VARCHAR(255),
//     price DECIMAL(10, 2),
//     description TEXT
// );

// Index.php - Display products and add to cart functionality

if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart') {
    $productId = $_GET['id'];
    
    // Check if product exists in database
    $productQuery = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $productQuery);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        // Add to cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = array(
                'id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            );
        } else {
            // Increment quantity if product already exists in cart
            $_SESSION['cart'][$productId]['quantity']++;
        }
    }
}

// Cart.php - Display cart contents

function displayCart() {
    global $conn;
    
    if (isset($_SESSION['cart'])) {
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $productId = $item['id'];
            
            // Get product details from database
            $productQuery = "SELECT * FROM products WHERE id = ?";
            $stmt = mysqli_prepare($conn, $productQuery);
            mysqli_stmt_bind_param($stmt, "i", $productId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $product = mysqli_fetch_assoc($result);
                
                echo "<tr>";
                echo "<td>" . $product['name'] . "</td>";
                echo "<td>$" . number_format($product['price'], 2) . "</td>";
                echo "<td><input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'></td>";
                echo "<td>$" . number_format($product['price'] * $item['quantity'], 2) . "</td>";
                echo "<td><a href='cart.php?action=remove&id=" . $productId . "'>Remove</a></td>";
                echo "</tr>";
                
                $total += $product['price'] * $item['quantity'];
            }
        }
        
        echo "<tr><td colspan='4'><strong>Grand Total:</strong></td><td>$" . number_format($total, 2) . "</td></tr>";
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $productId = $_GET['id'];
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        
        // Re-index the array to avoid empty indices
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Update cart quantities (to be implemented)

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php displayCart(); ?>
</body>
</html>

// Checkout.php - Process checkout

if (isset($_POST['checkout'])) {
    // Implement checkout logic here
    // You would typically:
    // 1. Validate the cart is not empty
    // 2. Get user information for shipping/billing
    // 3. Save order to database
    // 4. Empty the cart
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <!-- Display checkout form -->
    <?php else: ?>
        <p>Your cart is empty. Please add items to your cart first.</p>
    <?php endif; ?>
</body>
</html>


<?php
session_start();
?>


<?php
require_once 'db_connection.php';

function get_cart_id() {
    if (!isset($_SESSION['cart_id'])) {
        $cart_id = md5(uniqid(rand(), true)); // Generate unique cart ID
        $_SESSION['cart_id'] = $cart_id;
    }
    return $_SESSION['cart_id'];
}

function add_to_cart($product_id, $price) {
    $cart_id = get_cart_id();
    
    $stmt = mysqli_prepare($conn, "SELECT id FROM cart WHERE product_id=? AND cart_id=?");
    mysqli_stmt_bind_param($stmt, "ii", $product_id, $cart_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $updateStmt = mysqli_prepare($conn, "UPDATE cart SET quantity=quantity+1 WHERE product_id=? AND cart_id=?");
        mysqli_stmt_bind_param($updateStmt, "ii", $product_id, $cart_id);
        mysqli_stmt_execute($updateStmt);
    } else {
        $insertStmt = mysqli_prepare($conn, "INSERT INTO cart (product_id, price, quantity, cart_id) VALUES (?, ?, 1, ?)");
        mysqli_stmt_bind_param($insertStmt, "iii", $product_id, $price, $cart_id);
        mysqli_stmt_execute($insertStmt);
    }
}

function update_cart_quantity($item_id, $quantity) {
    $cart_id = get_cart_id();
    
    if ($quantity <= 0) {
        remove_from_cart($item_id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE cart SET quantity=? WHERE id=? AND cart_id=?");
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $item_id, $cart_id);
        mysqli_stmt_execute($stmt);
    }
}

function remove_from_cart($item_id) {
    $cart_id = get_cart_id();
    
    $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE id=? AND cart_id=?");
    mysqli_stmt_bind_param($stmt, "ii", $item_id, $cart_id);
    mysqli_stmt_execute($stmt);
}

function get_cart_items() {
    $cart_id = get_cart_id();
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM cart WHERE cart_id=?");
    mysqli_stmt_bind_param($stmt, "s", $cart_id);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}

function calculate_total() {
    $total = 0;
    $result = get_cart_items();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $total += $row['price'] * $row['quantity'];
    }
    
    return $total;
}
?>


<?php
include_once 'cart_functions.php';

$result = get_cart_items();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h2>Your Shopping Cart</h2>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1">
            <tr>
                <th>Product ID</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['product_id']; ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                            <button type="submit" name="update">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                    <td><a href="remove_from_cart.php?item_id=<?php echo $row['id']; ?>">Remove</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <h3>Total: $<?php echo number_format(calculate_total(), 2); ?></h3>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>


<?php
include_once 'cart_functions.php';

if (isset($_POST['update'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    
    update_cart_quantity($item_id, $quantity);
}

header("Location: view_cart.php");
?>


<?php
include_once 'cart_functions.php';

if (isset($_GET['item_id'])) {
    remove_from_cart($_GET['item_id']);
}

header("Location: view_cart.php");
?>


<a href="add_to_cart.php?product_id=<?php echo $product_id; ?>&price=<?php echo $price; ?>">Add to Cart</a>


<?php
include_once 'cart_functions.php';

if (isset($_GET['product_id']) && isset($_GET['price'])) {
    add_to_cart($_GET['product_id'], $_GET['price']);
}

header("Location: view_cart.php");
?>


<?php
// Initialize session
session_start();

// Database connection
include('db_connection.php');

// Products display page (products.php)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $product_id = intval($_GET['id']);
    
    if (!empty($product_id)) {
        $sql = "SELECT * FROM products WHERE id=$product_id";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        if ($product) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            } else {
                $_SESSION['cart'][$product_id] = array(
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => 1
                );
            }
        }
    }
}

// Remove from cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = intval($_GET['id']);
    
    if (!empty($product_id)) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Cart display page (cart.php)
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .product {
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }
        .total {
            font-weight: bold;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="cart">
        <?php
        if (empty($_SESSION['cart'])) {
            echo "<h2>Your cart is empty</h2>";
        } else {
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                ?>
                <div class="product">
                    <?php echo $item['name']; ?> - <?php echo $item['price']; ?> x <?php echo $item['quantity']; ?>
                    <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>">Remove</a>
                </div>
                <?php
            }
            ?>
            <div class="total">
                Total: <?php echo $total; ?>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- Add products here -->
    <?php
    // Display products from database
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    while ($product = mysqli_fetch_assoc($result)) {
        ?>
        <div class="product">
            <?php echo $product['name']; ?> - <?php echo $product['price']; ?>
            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>">Add to Cart</a>
        </div>
        <?php
    }
    ?>

</body>
</html>

// Database connection file (db_connection.php)
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_store";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

// Create products table in your database:
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10, 2)
);

// Add some sample products
INSERT INTO products (name, price) VALUES
('Product 1', 19.99),
('Product 2', 29.99),
('Product 3', 39.99);


// db_config.php
<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "shopping_cart";

$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


// start_session.php
<?php
session_start();
?>

Add this line at the beginning of your PHP files that require session management.


// add_to_cart.php
<?php
include('db_config.php');
include('start_session.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    // Get product details from database
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($result);
    
    if (!$product) {
        die("Product not found!");
    }
    
    if (isset($_SESSION['cart'])) {
        // Check if product already exists in cart
        $is_product_in_cart = false;
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['id'] == $product_id) {
                $_SESSION['cart'][$key]['quantity'] += 1;
                $is_product_in_cart = true;
                break;
            }
        }
        
        // If product not in cart, add it
        if (!$is_product_in_cart) {
            $item = array(
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'image' => $product['image']
            );
            
            $_SESSION['cart'][$product_id] = $item;
        }
    } else {
        // Initialize cart if not exists
        $item = array(
            'id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1,
            'image' => $product['image']
        );
        
        $_SESSION['cart'][$product_id] = $item;
    }
}

header("Location: cart.php");
?>


// cart.php
<?php
include('db_config.php');
include('start_session.php');

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total_amount = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Your Shopping Cart</h2>
    
    <?php if (!empty($cart_items)) : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item) : ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form action="update_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>" style="width: 50px;">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <a href="remove_from_cart.php?product_id=<?php echo $item['id']; ?>" class="btn btn-danger">Remove</a>
                        </td>
                    </tr>
                <?php 
                    $total_amount += ($item['price'] * $item['quantity']);
                endforeach; ?>
            </tbody>
        </table>
        
        <h3>Total Amount: $<?php echo number_format($total_amount, 2); ?></h3>
        <a href="checkout.php" class="btn btn-success">Checkout</a>
    <?php else : ?>
        <p>Your cart is empty.</p>
        <a href="products.php" class="btn btn-primary">Continue Shopping</a>
    <?php endif; ?>
</div>

<!-- Include Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>


// update_cart.php
<?php
include('db_config.php');
include('start_session.php');

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    if (is_numeric($quantity) && $quantity >= 1) {
        if (!empty($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    }
}

header("Location: cart.php");
?>


// remove_from_cart.php
<?php
include('start_session.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    if (!empty($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

header("Location: cart.php");
?>


// products.php
<?php
include('db_config.php');

$result = mysqli_query($conn, "SELECT * FROM products");
$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Available Products</h2>
    
    <?php if (!empty($products)) : ?>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-md-3">
                    <div class="thumbnail">
                        <?php if ($product['image']) : ?>
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="max-height: 200px;">
                        <?php endif; ?>
                        <div class="caption">
                            <h3><?php echo $product['name']; ?></h3>
                            <p>$<?php echo number_format($product['price'], 2); ?></p>
                            <a href="add_to_cart.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>There are no products available.</p>
    <?php endif; ?>
</div>

<!-- Include Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>


<?php
// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Query products
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        echo "<h2>" . $row['name'] . "</h2>";
        echo "<p>Price: $" . number_format($row['price'], 2) . "</p>";
        echo "<p>Description: " . $row['description'] . "</p>";
        echo "<form action='add_to_cart.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
        echo "<input type='number' name='quantity' min='1' max='" . $row['stock'] . "' value='1'>";
        echo "<button type='submit'>Add to Cart</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "No products found.";
}

mysqli_close($conn);
?>


<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Update quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new item
        $conn = mysqli_connect('localhost', 'root', '', 'shopping_cart');
        
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['cart'][$product_id] = array(
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $quantity
            );
        }

        mysqli_close($conn);
    }
}

header("Location: view_cart.php");
exit();
?>


<?php
session_start();

if (isset($_SESSION['cart'])) {
    $total = 0;
    echo "<h2>Your Cart</h2>";
    echo "<table>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total_item = $item['price'] * $item['quantity'];
        $total += $total_item;
        
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td><form action='update_cart.php' method='post'><input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='number' name='quantity' min='1' max='99' value='" . $item['quantity'] . "' style='width:50px;'>";
        echo "</form></td>";
        echo "<td>$" . number_format($total_item, 2) . "</td>";
        echo "<td><a href='remove_from_cart.php?product_id=" . $product_id . "'>Remove</a></td>";
        echo "</tr>";
    }

    echo "<tr><td colspan='4'><strong>Total:</strong></td><td>$" . number_format($total, 2) . "</td></tr>";
    echo "</table>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>


<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity'])); // Ensure at least 1

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

header("Location: view_cart.php");
exit();
?>


<?php
session_start();

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

header("Location: view_cart.php");
exit();
?>


<?php
session_start();

if (!empty($_SESSION['cart'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Process the order
        $conn = mysqli_connect('localhost', 'root', '', 'shopping_cart');

        foreach ($_SESSION['cart'] as $product_id => $item) {
            $sql = "INSERT INTO orders (user_id, product_id, quantity, total_price, order_status) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iisss", 1, $product_id, $item['quantity'], ($item['price'] * $item['quantity']), 'pending');
            mysqli_stmt_execute($stmt);
        }

        unset($_SESSION['cart']);
        
        mysqli_close($conn);
    }
?>


<?php
// Initialize session
session_start();

// Check if cart is already set in the session, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Sample products (you can replace this with a database)
$products = array(
    1 => array('name' => 'Product 1', 'price' => 19.99),
    2 => array('name' => 'Product 2', 'price' => 29.99),
    3 => array('name' => 'Product 3', 'price' => 9.99)
);

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if (!empty($products[$productId])) {
        // Check if product already exists in the cart
        if (isset($_SESSION['cart'][$productId])) {
            // Update quantity
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $_SESSION['cart'][$productId] = array(
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => $quantity
            );
        }
    }
}

// Update cart functionality
if (isset($_POST['update_cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        if (isset($_POST["quantity_$id"])) {
            $newQuantity = intval($_POST["quantity_$id"]);
            if ($newQuantity > 0) {
                $_SESSION['cart'][$id]['quantity'] = $newQuantity;
            }
        }
    }
}

// Clear cart functionality
if (isset($_GET['clear_cart'])) {
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Shopping Cart</a>
        <button class="btn btn-primary" onclick="window.location.href='cart.php'">View Cart</button>
    </div>
</nav>

<div class="container mt-4">

<?php
// Display products
echo "<h2>Products Available</h2>";
echo "<div class='row'>";
foreach ($products as $id => $product) {
    echo "<div class='col-md-3 mb-4'>";
    echo "<div class='card'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>".$product['name']."</h5>";
    echo "<p class='card-text'>$".$product['price']."</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='".$id."'>";
    echo "<input type='number' min='1' name='quantity' value='1'>";
    echo "<button type='submit' name='add_to_cart' class='btn btn-primary'>Add to Cart</button>";
    echo "</form>";
    echo "</div></div></div>";
}
echo "</div>";

// Display cart
if (!empty($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $subtotal = 0;
    
    echo "<h2>Your Cart</h2>";
    echo "<form method='post'>";
    echo "<table class='table'>";
    echo "<thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead>";
    foreach ($cart as $id => $item) {
        $total = $item['price'] * $item['quantity'];
        $subtotal += $total;
        
        echo "<tr>";
        echo "<td>".$item['name']."</td>";
        echo "<td>$".$item['price']."</td>";
        echo "<td><input type='number' min='1' name='quantity_$id' value='".$item['quantity']."'></td>";
        echo "<td>$".number_format($total, 2)."</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<button type='submit' name='update_cart' class='btn btn-primary mr-2'>Update Cart</button>";
    echo "<a href='cart.php?clear_cart=1' class='btn btn-danger mr-2'>Clear Cart</a>";
    echo "<a href='checkout.php' class='btn btn-success'>Checkout</a>";
    echo "</form>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>

</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<?php
session_start();
include('db_connection.php');

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Welcome to Our Store</h1>
    
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '
                <div class="product">
                    <img src="images/'.$row['image'].'" alt="'.$row['name'].'">
                    <h3>'.$row['name'].'</h3>
                    <p>$'.$row['price'].'</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="'.$row['id'].'">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>';
        }
    } else {
        echo "No products available.";
    }
    ?>
    
    <a href="cart.php" style="float: right;">View Cart</a>
</body>
</html>

<?php
$conn->close();
?>


<?php
session_start();

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        $conn = new mysqli('localhost', 'root', '', 'shopping_cart');
        $sql = "SELECT * FROM products WHERE id = '$product_id'";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        
        $_SESSION['cart'][$product_id] = array(
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        );
    }
}

header('Location: index.php');
?>


<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php if (empty($_SESSION['cart'])) { ?>
        <h2>Your cart is empty!</h2>
        <a href="index.php">Back to Shop</a>
    <?php } else { ?>
        <h2>Your Cart</h2>
        
        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($_SESSION['cart'] as $product_id => $item) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><input type="number" name="quantity" value="<?php echo $item['quantity']; ?>"></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <form action="remove_from_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        
        <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
    <?php } ?>
</body>
</html>


<?php
session_start();

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    if (!empty($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

header('Location: cart.php');
?>


<?php
session_start();
include('db_connection.php');

if (empty($_SESSION['cart'])) {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php if (!isset($_POST['submit'])) { ?>
        <h2>Checkout</h2>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3" required></textarea><br><br>
            
            <input type="submit" name="submit" value="Place Order">
        </form>
    <?php } else { 
        $total = 0;
        
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        $sql = "INSERT INTO orders (user_name, email, address, total) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssd", $_POST['name'], $_POST['email'], $_POST['address'], $total);
        $stmt->execute();
        
        session_unset();
        session_destroy();
        
        echo "<h2>Order placed successfully!</h2>";
        header('Location: index.php');
    } ?>
</body>
</html>

<?php
$conn->close();
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_cart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
session_start();

class Cart {
    private $cart = array();
    
    public function __construct() {
        if (isset($_SESSION['cart'])) {
            $this->cart = $_SESSION['cart'];
        } else {
            $this->cart = array();
        }
    }

    // Add item to cart
    public function addItem($item_id, $name, $price) {
        if (!isset($this->cart[$item_id])) {
            $this->cart[$item_id] = array(
                'id' => $item_id,
                'name' => $name,
                'price' => $price,
                'quantity' => 1
            );
        } else {
            // If item already exists, increment quantity
            $this->cart[$item_id]['quantity']++;
        }
        $this->saveCart();
    }

    // Remove item from cart
    public function removeItem($item_id) {
        if (isset($this->cart[$item_id])) {
            unset($this->cart[$item_id]);
        }
        $this->saveCart();
    }

    // Update item quantity
    public function updateQuantity($item_id, $quantity) {
        if ($quantity > 0 && isset($this->cart[$item_id])) {
            $this->cart[$item_id]['quantity'] = $quantity;
        } elseif ($quantity <= 0 && isset($this->cart[$item_id])) {
            unset($this->cart[$item_id]);
        }
        $this->saveCart();
    }

    // Get total price of cart
    public function getTotalPrice() {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
        return $total;
    }

    // Save cart to session
    private function saveCart() {
        $_SESSION['cart'] = $this->cart;
    }

    // Get all items in cart
    public function getItems() {
        return $this->cart;
    }
}

// Example usage:

// Initialize cart
$cart = new Cart();

// Add item to cart
$cart->addItem(1, "Product 1", 29.99);
$cart->addItem(2, "Product 2", 49.99);

// Update quantity
$cart->updateQuantity(1, 3);

// Remove item
$cart->removeItem(2);

// Display cart items and total price
echo "<pre>";
print_r($cart->getItems());
echo "</pre>";
echo "Total Price: $" . $cart->getTotalPrice();
?>

