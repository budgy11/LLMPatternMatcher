
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
    $productPrice = $_POST['product_price'];

    // Check if product ID is provided
    if (empty($productId)) {
        $_SESSION['message'] = "Error: Product ID not found.";
        header("Location: view_cart.php");
        exit();
    }

    $itemExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity']++;
            $itemExists = true;
            break;
        }
    }

    if (!$itemExists) {
        $_SESSION['cart'][] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'quantity' => 1
        );
    }

    // Set success message
    $_SESSION['message'] = "Item added to cart successfully!";
    header("Location: view_cart.php");
    exit();
}

// Display cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    $total = 0;
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    foreach ($_SESSION['cart'] as $key => $item) {
        $subtotal = $item['product_price'] * $item['quantity'];
        $total += $subtotal;
        echo "<tr>";
        echo "<td>" . $item['product_name'] . "</td>";
        echo "<td>₹" . number_format($item['product_price'], 2) . "</td>";
        echo "<td><input type='number' min='1' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $key . ", this.value)'></td>";
        echo "<td>₹" . number_format($subtotal, 2) . "</td>";
        echo "<td><a href='remove_item.php?id=" . $key . "'>Remove</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p>Grand Total: ₹" . number_format($total, 2) . "</p>";
}

// Clear cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = array();
    header("Location: view_cart.php");
    exit();
}
?>

<!-- Include these links -->
<a href="add_item.php">Add Item</a> |
<?php if (!empty($_SESSION['cart'])) { ?>
    <a href="?clear=1">Clear Cart</a>
<?php } ?>

<!-- Optional: Display message -->
<?php
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle adding items to cart via form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        // Get product details from the form
        $productId = $_POST['product_id'];
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        // Check if item already exists in cart
        $itemExists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] += $quantity;
                $itemExists = true;
                break;
            }
        }

        // Add new item to cart if it doesn't exist
        if (!$itemExists) {
            $newItem = array(
                'product_id' => $productId,
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => $quantity
            );
            $_SESSION['cart'][] = $newItem;
        }

        // Redirect back to cart or product page
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}

// Display the cart items
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>($" . number_format($item['price'], 2) . ")</td>";
        echo "<td><input type='number' value='" . $item['quantity'] . "'></td>";
        echo "<td>($" . number_format($subtotal, 2) . ")</td>";
        echo "<td><a href='remove_item.php?product_id=" . $item['product_id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p>Total: $" . number_format($total, 2) . "</p>";
}

// Include a link to view the cart
echo "<p><a href='view_cart.php'>View Cart</a></p>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Page</title>
</head>
<body>
    <!-- Product form -->
    <h2>Add Item to Cart</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="product_id" value="1">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="number" name="product_price" step="0.01" min="0" required>
        <input type="number" name="quantity" value="1">
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

    <!-- Remove item script -->
    <?php include 'remove_item.php'; ?>
</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $found = true;
            // Update quantity (you can modify this logic as needed)
            $item['quantity'] += $quantity;
            break;
        }
    }

    if (!$found) {
        array_push($_SESSION['cart'], array(
            'id' => $item_id,
            'quantity' => $quantity
        ));
    }

    header("Location: cart.php");
    exit();
}

// Remove item from cart
if (isset($_POST['action']) && $_POST['action'] == 'remove') {
    $item_id = intval($_POST['item_id']);

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            // Reindex the array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }

    header("Location: cart.php");
    exit();
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
        <ul>
            <?php foreach ($_SESSION['cart'] as $item) { ?>
                <li>Item ID: <?php echo $item['id']; ?> | Quantity: <?php echo $item['quantity']; ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <button type="submit">Remove</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <!-- Example Add Form -->
    <h2>Add Items to Cart</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Item ID: <input type="number" name="item_id" required><br>
        Quantity: <input type="number" name="quantity" min="1" value="1" required><br>
        <button type="submit" name="action" value="add">Add to Cart</button>
    </form>
</body>
</html>


<?php
// Start the session
session_start();

// Check if the cart exists in the session, initialize it if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id) {
    // Check if product is already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add new product
        $item = array(
            'id' => $product_id,
            'name' => get_product_name($product_id),
            'price' => get_product_price($product_id),
            'quantity' => 1
        );
        $_SESSION['cart'][$product_id] = $item;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity of an item in cart
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id]) && $quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Get product details from your database or array
function get_product_name($product_id) {
    // Replace with actual data
    $products = array(
        '1' => 'Product A',
        '2' => 'Product B',
        '3' => 'Product C'
    );
    return $products[$product_id];
}

function get_product_price($product_id) {
    // Replace with actual data
    $prices = array(
        '1' => 10.00,
        '2' => 20.00,
        '3' => 30.00
    );
    return $prices[$product_id];
}

// Example usage in your application

// If adding an item to cart
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    add_to_cart($_GET['id']);
}

// If removing an item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    remove_from_cart($_GET['id']);
}

// If updating quantity
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $product_id => $qty) {
        update_quantity($product_id, $qty);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])) { ?>
        <h1>Your Shopping Cart</h1>
        <form method="post" action="">
            <table border="1">
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
                        <td><input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>"></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="?action=remove&id=<?php echo $item['id']; ?>">Remove</a></td>
                    </tr>
                <?php } ?>
            </table>
            <input type="submit" name="update" value="Update Cart">
        </form>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>

    <!-- Add items to cart -->
    <h1>Products</h1>
    <ul>
        <li><a href="?action=add&id=1">Add Product A to Cart</a></li>
        <li><a href="?action=add&id=2">Add Product B to Cart</a></li>
        <li><a href="?action=add&id=3">Add Product C to Cart</a></li>
    </ul>
</body>
</html>

<?php
// Destroy the session when done (optional)
session_unset();
// session_destroy();
?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function addToCart($productId) {
    global $db; // Assuming you have a database connection
    
    // Get product details from database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Check if item already exists in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
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
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Display cart contents
function displayCart() {
    $total = 0;
    
    echo "<table>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$$item[price]</td>";
        echo "<td><input type='number' min='1' value='" . $item['quantity'] . "'></td>";
        echo "<td>$$subtotal</td>";
        echo "<td><button onclick='removeFromCart($item[id])'>Remove</button></td>";
        echo "</tr>";
    }
    
    echo "<tr><td colspan='4'><strong>Total: $$total</strong></td></tr>";
    echo "</table>";
}

// Destroy session when user logs out
function logout() {
    session_unset();
    session_destroy();
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    addToCart($_POST['product_id']);
}

if (isset($_POST['remove_from_cart'])) {
    removeFromCart($_POST['product_id']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php displayCart(); ?>
    
    <!-- Add product form -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="product_id" value="1">
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

    <!-- Logout button -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>


<?php
// Start the session
session_start();

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example product data (this would typically come from your database)
$products = array(
    1 => array('name' => 'Product 1', 'price' => 10.99),
    2 => array('name' => 'Product 2', 'price' => 19.99),
    3 => array('name' => 'Product 3', 'price' => 5.99)
);

// Add item to cart
function addToCart($productId, $quantity = 1) {
    global $products;
    
    if (isset($products[$productId])) {
        // Check if product is already in cart
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = array(
                'id' => $productId,
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => 0
            );
        }
        
        // Increment quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    }
}

// Remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Display cart contents
if (!empty($_SESSION['cart'])) {
    echo '<h2>Shopping Cart</h2>';
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        echo "<div>";
        echo "Product: {$item['name']}<br />";
        echo "Price: \${$item['price']}<br />";
        echo "Quantity: {$item['quantity']}<br />";
        echo "Subtotal: \${$subtotal}<br />";
        echo '<a href="?remove=' . $item['id'] . '">Remove</a>';
        echo "</div><br />";
    }
    
    echo "<h3>Total: \${$total}</h3>";
} else {
    echo 'Your cart is empty.';
}

// Handle remove action
if (isset($_GET['remove'])) {
    removeFromCart($_GET['remove']);
}

// Example of adding items to the cart
addToCart(1, 2); // Add product ID 1 with quantity 2
addToCart(3, 5); // Add product ID 3 with quantity 5

?>


<?php
// Start the session
session_start();

// Initialize shopping cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $productPrice) {
    global $mysqli;
    
    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $(productId)) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        // Add new product to cart
        $_SESSION['cart'][] = array(
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => 1
        );
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            // Re-index the array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

// Function to update item quantity
function updateQuantity($productId, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $quantity;
            break;
        }
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

// Display items in cart
if (!empty($_SESSION['cart'])) {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " x" . $item['quantity'] . " - $" . number_format($item['price'], 2) . "</li>";
        echo "<a href='?action=remove&id=" . $item['id'] . "'>Remove</a>";
    }
    echo "</ul>";
    echo "<p>Total: $" . number_format(cartTotal(), 2) . "</p>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// Destroy the session when done
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION);
    session_destroy();
}
?>


addToCart($productId, $productName, $productPrice);


removeFromCart($productId);


updateQuantity($productId, $newQuantity);


unset($_SESSION);
session_destroy();


<?php
// Start the session
session_start();

// Check if the cart exists in the session; if not, initialize it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example product data (you can replace this with your database query)
$products = [
    1 => ['name' => 'Product 1', 'price' => 100],
    2 => ['name' => 'Product 2', 'price' => 200],
    3 => ['name' => 'Product 3', 'price' => 300]
];

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    if (!empty($_POST['product_id']) && !empty($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Check if the product already exists in the cart
        if (isset($_SESSION['cart'][$productId])) {
            // Update quantity
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $_SESSION['cart'][$productId] = [
                'id' => $productId,
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => $quantity
            ];
        }

        // Redirect back to the previous page after adding to cart
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Please fill in all required fields!";
    }
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $item) {
    $total = $item['price'] * $item['quantity'];
    echo "<tr>";
    echo "<td>{$item['name']}</td>";
    echo "<td>${$item['price']}</td>";
    echo "<td><input type='number' value='{$item['quantity']}'></td>";
    echo "<td>${$total}</td>";
    echo "<td><a href='remove_from_cart.php?id={$item['id']}'>Remove</a></td>";
    echo "</tr>";
}
echo "</table>";

// Example form to add products to cart
echo "<h2>Products</h2>";
foreach ($products as $id => $product) {
    echo "<form method='post' action='add_to_cart.php'>";
    echo "<input type='hidden' name='product_id' value='{$id}'>";
    echo "<input type='text' name='quantity' placeholder='Quantity'>";
    echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
    echo "</form>";
}
?>

<!-- remove_from_cart.php -->
<?php
session_start();
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    unset($_SESSION['cart'][$productId]);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>


<?php
// Start the session
session_start();

// Check if cart exists in the session. If not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get product ID from query string (you should filter and validate this)
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Add the product to cart
if ($product_id > 0) {
    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'quantity' => 1
        );
    }

    // Redirect back to the products page or wherever you came from
    header('Location: products.php');
    exit;
}

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "<br />";
    }
}

// You might want to add links to modify the cart or checkout
echo "<p><a href='products.php'>Continue Shopping</a></p>";
?>



<?php
// Start the session
session_start();

// Check if the session is initialized, initialize if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Sample products (you can replace this with your own product data)
$products = array(
    1 => array('name' => 'Product 1', 'price' => 29.99, 'image' => 'product1.jpg'),
    2 => array('name' => 'Product 2', 'price' => 49.99, 'image' => 'product2.jpg'),
    3 => array('name' => 'Product 3', 'price' => 19.99, 'image' => 'product3.jpg')
);

// Add to cart functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $productId = intval($_POST['product_id']);
        
        // Check if product exists in the products array
        if (isset($products[$productId])) {
            // Check if product already exists in cart
            if (!isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] = array(
                    'name' => $products[$productId]['name'],
                    'price' => $products[$productId]['price'],
                    'quantity' => 1
                );
            } else {
                // If product exists, increment quantity
                $_SESSION['cart'][$productId]['quantity']++;
            }
        }
    }
    
    // Remove from cart functionality
    if (isset($_POST['remove_from_cart'])) {
        $productId = intval($_POST['product_id']);
        
        // Check if product exists in cart and remove it
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }
}

// Function to calculate total price of items in the cart
function get_cart_total() {
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php if (!empty($_SESSION['cart'])): ?>
            <h2>Your Cart</h2>
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
                    <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <button type="submit" name="remove_from_cart" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="1"><strong>Total: $<?php echo number_format(get_cart_total(), 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>

        <!-- Products Display -->
        <h2 class="mt-4">Available Products</h2>
        <div class="row">
            <?php foreach ($products as $productId => $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<?php
session_start();

// Function to add item to cart
function addToCart($productId, $productName, $price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        // Item already exists in cart - increment quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to update quantity of an item in cart
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Function to delete item from cart
function deleteItemFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to display cart contents
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $total = $item['price'] * $item['quantity'];
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>$" . number_format($item['price'], 2) . "</td>";
            echo "<td><input type='number' name='quantity[" . $item['product_id'] . "]' value='" . $item['quantity'] . "' min='1'></td>";
            echo "<td>$" . number_format($total, 2) . "</td>";
            echo "<td><a href='delete_item.php?id=" . $item['product_id'] . "'>Delete</a></td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}

// Example usage:
// Add item to cart
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $productId = 1;
    $productName = "Product Name";
    $price = 29.99;
    addToCart($productId, $productName, $price);
}

// Update quantity of item in cart
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $productId => $newQuantity) {
        updateQuantity($productId, $newQuantity);
    }
}

// Delete item from cart
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (isset($_GET['id'])) {
        deleteItemFromCart($_GET['id']);
    }
}

// Display cart contents
displayCart();
?>


<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
    }
}
header("Location: view_cart.php");
exit();
?>


<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head><title>Shopping Cart</title></head>
<body>
<h1>Your Shopping Cart</h1>
<table border="1">
    <tr><th>Product ID</th><th>Quantity</th><th>Action</th></tr>
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <tr>
                <td><?php echo $item['id']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><a href="remove_from_cart.php?id=<?php echo $item['id']; ?>">Remove</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="3">Your cart is empty.</td></tr>
    <?php endif; ?>
</table>
<br>
<a href="add_to_cart.php">Add More Items</a>
</body>
</html>


<?php
session_start();
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $productId) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        // Re-index the array to avoid gaps in keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
header("Location: view_cart.php");
exit();
?>


<?php
// Start the session
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function addToCart($item_id, $item_name, $price) {
    global $db;
    
    // Check if item is already in cart
    $found = false;
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $item_id) {
            $_SESSION['cart'][$index]['quantity']++;
            $found = true;
            break;
        }
    }
    
    // If item not found, add it
    if (!$found) {
        $new_item = array(
            'id' => $item_id,
            'name' => $item_name,
            'price' => $price,
            'quantity' => 1
        );
        $_SESSION['cart'][] = $new_item;
    }
}

// Update item quantity in cart
function updateCart($item_id, $quantity) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $item_id) {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
            break;
        }
    }
}

// Remove item from cart
function removeItem($item_id) {
    $index = array_search($item_id, array_column($_SESSION['cart'], 'id'));
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
    }
}

// Display cart contents
function displayCart() {
    $total_price = 0;
    echo "<table>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>" . $item['price'] . "</td>";
        echo "<td><input type='number' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $item['id'] . ", this.value)'></td>";
        echo "<td><button onclick='removeItem(" . $item['id'] . ")'>Remove</button></td>";
        echo "</tr>";
        
        $total_price += $item['price'] * $item['quantity'];
    }
    echo "<tr><td colspan='4'><strong>Total: $" . number_format($total_price, 2) . "</strong></td></tr>";
    echo "</table>";
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    addToCart(1, 'Product Name', 9.99);
} elseif (isset($_GET['remove'])) {
    removeItem($_GET['remove']);
}

// Security measures
session_regenerate(); // Prevent session fixation

// Set session cookie parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Display cart contents
displayCart();

// Destroy session when done
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
}
?>


<?php
// Start the session
session_name("user_session");
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product already exists in cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $productIdToRemove = $_GET['product_id'];
    if (array_key_exists($productIdToRemove, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productIdToRemove]);
    }
}

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        echo "<li>Product ID: $product_id | Quantity: $quantity ";
        // Add remove link
        echo "<a href='?action=remove&product_id=$product_id'>Remove</a></li>";
    }
    echo "</ul>";
} else {
    echo "Your cart is empty.";
}

// Sample products to add to cart
echo "<h2>Products</h2>";
$products = array(
    1 => 'Product 1',
    2 => 'Product 2',
    3 => 'Product 3'
);

foreach ($products as $id => $name) {
    echo "<div>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='$id'>";
    echo "<input type='text' name='quantity' placeholder='Quantity'>";
    echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
    echo "</form>";
    echo "</div>";
}
?>


<?php
// Start the session
session_start();

// Check if session is not started
if (!isset($_SESSION)) {
    session_start();
}

// Function to initialize the shopping cart session
function init_cart() {
    // If cart doesn't exist, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
}

// Function to add item(s) to the cart
function add_to_cart($product_id, $name, $price, $quantity = 1, $size = '') {
    // Initialize cart session
    init_cart();

    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update quantity and size
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['size'] = $size;
    } else {
        // Add new item to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'size' => $size
        );
    }
}

// Function to remove item from the cart
function remove_from_cart($product_id) {
    // Initialize cart session
    init_cart();

    // Check if product exists in cart and unset it
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // Re-index the cart array after removing an item
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to empty the entire shopping cart
function empty_cart() {
    // Initialize cart session and set it to an empty array
    init_cart();
    
    // Empty all items from the cart
    $_SESSION['cart'] = array();
}

// Example usage:

// Adding an item to the cart
add_to_cart(1, 'Laptop', 999.99, 2, '15.6"');

// Output current cart contents
echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

// Removing an item from the cart
remove_from_cart(1);

// Emptying the cart
empty_cart();

?>


<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Display cart items
echo "<h2>Your Cart</h2>";
$total = 0;

foreach ($_SESSION['cart'] as $item) {
    echo "Product ID: " . $item['id'] . "<br />";
    echo "Name: " . $item['name'] . "<br />";
    echo "Price: $" . number_format($item['price'], 2) . "<br />";
    echo "Quantity: " . $item['quantity'] . "<br />";
    echo "Total: $" . number_format($item['price'] * $item['quantity'], 2) . "<br /><br />";
    
    $total += ($item['price'] * $item['quantity']);
}

echo "Grand Total: $" . number_format($total, 2) . "<br />";

// Add product to cart
if (isset($_GET['add'])) {
    $productId = $_GET['add'];
    // Assume you fetch product details from database or predefined array
    $products = array(
        1 => array('id' => 1, 'name' => 'Product 1', 'price' => 10.99),
        2 => array('id' => 2, 'name' => 'Product 2', 'price' => 19.99)
    );
    
    if (isset($products[$productId])) {
        $product = $products[$productId];
        $itemExists = false;
        
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity']++;
                $itemExists = true;
                break;
            }
        }
        
        if (!$itemExists) {
            $newItem = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'timestamp' => time()
            );
            $_SESSION['cart'][] = $newItem;
        }
    }
}

// Example link to add product
echo "<a href='?add=1'>Add Product 1</a><br />";
echo "<a href='?add=2'>Add Product 2</a>";
?>


<?php
session_start();
?>


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get product details from POST request
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // If exists, increase quantity
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        // If not exists, add new product with quantity
        $_SESSION['cart'][$productId] = $quantity;
    }
}


if (isset($_SESSION['cart'])) {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        // Assume you have a function or array to get product details by ID
        $product = getProductDetails($productId);
        echo "<li>{$product['name']} - Quantity: {$quantity}</li>";
    }
    echo "</ul>";
} else {
    echo "Your cart is empty.";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        if (isset($_POST["quantity_{$productId}"])) {
            $_SESSION['cart'][$productId] = $_POST["quantity_{$productId}"];
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['remove'])) {
    $productIdToRemove = $_GET['remove'];
    
    if (isset($_SESSION['cart'][$productIdToRemove])) {
        unset($_SESSION['cart'][$productIdToRemove]);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    $_SESSION['cart'] = array();
}


<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Update quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        if (isset($_POST["quantity_{$productId}"])) {
            $_SESSION['cart'][$productId] = $_POST["quantity_{$productId}"];
        }
    }
}

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['remove'])) {
    $productIdToRemove = $_GET['remove'];
    
    if (isset($_SESSION['cart'][$productIdToRemove])) {
        unset($_SESSION['cart'][$productIdToRemove]);
    }
}

// Clear cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Welcome to Our Store</h1>

<!-- Add to Cart Form -->
<form method="post">
    <input type="hidden" name="product_id" value="1">
    <input type="number" name="quantity" placeholder="Quantity" min="1" required>
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<!-- Display Cart -->
<h2>Your Cart:</h2>
<form method="post">
    <?php if (!empty($_SESSION['cart'])): ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $productId => $quantity): ?>
                <li>
                    Product <?php echo $productId; ?> - Quantity: 
                    <input type="number" name="quantity_<?php echo $productId; ?>" value="<?php echo $quantity; ?>">
                    <a href="?remove=<?php echo $productId; ?>">Remove</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <button type="submit" name="update">Update Cart</button><br>
        <button type="submit" name="clear_cart">Clear Cart</button>
    <?php else: ?>
        Your cart is empty.
    <?php endif; ?>
</form>

<!-- Checkout Button -->
<form method="post">
    <button type="submit" name="checkout">Checkout</button>
</form>

</body>
</html>


<?php
session_start();
// Set cookie parameters for security and compatibility
ini_set('session.cookie_httponly', 'On');
ini_set('session.cookie_secure', 1);
?>


<?php
session_start();

// Get product details from POST request
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
} else {
    $cart = array();
}

// Check if the product is already in the cart
if (array_key_exists($product_id, $cart)) {
    // Update quantity
    $cart[$product_id]['quantity'] += $quantity;
} else {
    // Add new item
    $cart[$product_id] = array(
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );
}

$_SESSION['cart'] = $cart;
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
    <?php if (!empty($_SESSION['cart'])): ?>
        <h1>Your Cart</h1>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo '$' . number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><a href="remove_item.php?id=<?php echo $id; ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <p><a href="index.php">Continue Shopping</a></p>
    <p><a href="clear_cart.php">Clear Cart</a></p>
</body>
</html>


<?php
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (!empty($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        
        // Re-index the session array to maintain continuity
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

header("Location: view_cart.php");
exit();
?>


<?php
session_start();

// Unset and destroy the cart session
unset($_SESSION['cart']);
$_SESSION = array();
session_unset();
session_destroy();

header("Location: index.php");
exit();
?>


<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($item_id, $quantity) {
    global $connection;
    $item_id = mysqli_real_escape_string($connection, $item_id);
    
    if (isset($_SESSION['cart'][$item_id])) {
        // Update quantity
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Get item details from database
        $query = "SELECT * FROM products WHERE id = '$item_id'";
        $result = mysqli_query($connection, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $item = mysqli_fetch_assoc($result);
            $_SESSION['cart'][$item_id] = array(
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $quantity
            );
        }
    }
}

// Function to remove item from cart
function remove_from_cart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
        // Re-index the array if needed
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to update item quantity in cart
function update_cart($item_id, $new_quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}

// Function to display cart contents
function display_cart() {
    $total = 0;
    
    echo "<table>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td><input type='number' name='quantity[" . $item['id'] . "]' value='" . $item['quantity'] . "' min='1'></td>";
        echo "<td>$" . number_format($subtotal, 2) . "</td>";
        echo "<td><a href='cart.php?action=remove&id=" . $item['id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "<tr><td colspan='4'><strong>Grand Total:</strong></td><td>$" . number_format($total, 2) . "</td></tr>";
    echo "</table>";
}

// Handle actions
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add' && isset($_GET['id']) && isset($_GET['quantity'])) {
        add_to_cart($_GET['id'], $_GET['quantity']);
    } elseif ($_GET['action'] == 'remove' && isset($_GET['id'])) {
        remove_from_cart($_GET['id']);
    }
}

// Update quantities if form submitted
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $item_id => $qty) {
        update_cart($item_id, $qty);
    }
}
?>


<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $product_name = $_POST['product_name'];
    $price = floatval($_POST['price']);

    // Create an array for the new item
    $item = array(
        'id' => $product_id,
        'name' => $product_name,
        'quantity' => 1,
        'price' => $price
    );

    // Add the item to the cart
    $_SESSION['cart'][$product_id] = $item;
}

// Update cart quantities
if (isset($_POST['update_cart'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity_') !== false) {
            $product_id = str_replace('quantity_', '', $key);
            $quantity = intval($value);

            // Ensure quantity is not negative
            if ($quantity >= 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            }
        }
    }
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $product_id = intval($_POST['product_id']);
    
    // Unset the item from the cart
    unset($_SESSION['cart'][$product_id]);
}

// Displaying the cart items
echo "<h2>Shopping Cart</h2>";
echo "<form method='post'>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $item) {
    echo "<tr>";
    echo "<td>" . $item['name'] . "</td>";
    echo "<td>£" . number_format($item['price'], 2) . "</td>";
    echo "<td><input type='text' name='quantity_" . $item['id'] . "' value='" . $item['quantity'] . "' size='3'></td>";
    echo "<td><button type='submit' name='remove_item' onclick=\"return confirm('Are you sure to remove this item?');\">Remove</button></td>";
    echo "</tr>";
}

echo "</table>";

// Update button
echo "<p><input type='hidden' name='update_cart'><button type='submit'>Update Cart</button></p>";

// Proceed to checkout button
echo "<p><button type='button'><a href='checkout.php'>Proceed to Checkout</a></button></p>";
echo "</form>";
?>

<!-- Add item form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
    <input type="hidden" name="price" value="<?php echo $price; ?>">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>


     session_start();
     if (!isset($_SESSION['cart'])) {
         $_SESSION['cart'] = array();
     }
     

3. **Add Items to Cart**
   - When a user adds an item, retrieve the product ID from POST data. Check if the product is already in the cart.
     - If it exists, increment the quantity.
     - If not, add the new product with its details (e.g., name, price).
     
     function addToCart($productId) {
         include 'db_connection.php';
         $result = mysqli_query($conn, "SELECT id, name, price FROM products WHERE id=$productId");
         $product = mysqli_fetch_assoc($result);
         if ($product) {
             if (isset($_SESSION['cart'][$productId])) {
                 $_SESSION['cart'][$productId]['quantity']++;
             } else {
                 $_SESSION['cart'][$productId] = array(
                     'id' => $productId,
                     'name' => $product['name'],
                     'price' => $product['price'],
                     'quantity' => 1
                 );
             }
         }
     }
     

4. **Update Cart Quantities**
   - Allow users to adjust quantities by iterating over each item and updating their counts.
     
     function updateCart($cartData) {
         foreach ($cartData as $productId => $quantity) {
             if (isset($_SESSION['cart'][$productId])) {
                 $_SESSION['cart'][$productId]['quantity'] = $quantity;
             }
         }
     }
     

5. **Remove Items from Cart**
   - Provide a method to remove items by unsetting the respective session array element.
     
     function removeFromCart($productId) {
         if (isset($_SESSION['cart'][$productId])) {
             unset($_SESSION['cart'][$productId]);
             $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index keys
         }
     }
     

6. **Display Cart Contents**
   - Loop through the cart session to display each item's details and calculate totals.
     
     function displayCart() {
         $total = 0;
         foreach ($_SESSION['cart'] as $item) {
             echo "Product: {$item['name']}<br>";
             echo "Price: \${$item['price']}<br>";
             echo "Quantity: {$item['quantity']}<br>";
             $subtotal = $item['price'] * $item['quantity'];
             echo "Subtotal: \$" . number_format($subtotal, 2) . "<br><hr>";
             $total += $subtotal;
         }
         echo "Total Amount: \$" . number_format($total, 2);
     }
     

7. **Handling Session Data**
   - PHP automatically serializes session data when storing it, so no manual serialization is needed.

8. **Testing and Validation**
   - Ensure functions handle cases where product IDs are invalid or quantities become negative.
   - Test adding the same product multiple times to verify quantity increments.

### Final Code Example



<?php
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to add items to the cart
function addToCart($item_id, $quantity = 1) {
    // Initialize cart in session if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if item already exists in cart
    if (isset($_SESSION['cart'][$item_id])) {
        // Increment quantity
        $_SESSION['cart'][$item_id] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$item_id] = $quantity;
    }
    
    // Save session data
    session_write_close();
}

// Function to remove items from the cart
function removeFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
        session_write_close();
    }
}

// Function to update quantity of items in cart
function updateCartItem($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = $quantity;
        session_write_close();
    } else {
        // Handle error: item not found in cart
        return false;
    }
}

// Function to empty the entire cart
function emptyCart() {
    unset($_SESSION['cart']);
    session_write_close();
}

// Example usage:
// Add an item with ID 1 and quantity 2
addToCart(1, 2);

// Remove an item with ID 1
removeFromCart(1);

// Update quantity of item with ID 1 to 3
updateCartItem(1, 3);

// Empty the entire cart
emptyCart();
?>


<?php
// Start the session
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example product data (you would typically retrieve this from a database)
$product_id = 1;
$product_name = "Example Product";
$product_price = 9.99;

// Add product to cart
if (!isset($_POST['add_to_cart'])) {
?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
        <input type="hidden" name="product_price" value="<?php echo $product_price; ?>">
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
<?php
} else {
    // Sanitize input data
    $product_id = htmlspecialchars($_POST['product_id']);
    $product_name = htmlspecialchars($_POST['product_name']);
    $product_price = htmlspecialchars($_POST['product_price']);

    // Check if the product is already in the cart
    $cart_items = $_SESSION['cart'];
    $item_exists = false;

    foreach ($cart_items as $item) {
        if ($item['id'] == $product_id) {
            $item_exists = true;
            break;
        }
    }

    if ($item_exists) {
        echo "Product is already in the cart!";
    } else {
        // Add new product to the cart
        $cart_items[] = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price
        );
        
        $_SESSION['cart'] = $cart_items;
        echo "Product added to cart successfully!";
    }
}

// Display the cart contents
echo "<h2>Your Cart</h2>";
echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

// Destroy the session (optional)
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
}
?>


<?php
session_start();

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
?>

<h2>Your Shopping Cart</h2>
<table border="1">
    <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
    <?php foreach ($cart as $item): ?>
    <tr>
        <td><?php echo $item['id']; ?></td>
        <td><?php echo $item['name']; ?></td>
        <td>$<?php echo number_format($item['price'], 2); ?></td>
        <td><a href="remove_product.php?id=<?php echo $item['id']; ?>">Remove</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="index.php">Continue Shopping</a>

<?php
} else {
    echo "Your cart is empty!";
}
?>


<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Remove the product from the cart
    $cart = $_SESSION['cart'];
    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            break;
        }
    }
    
    // Re-index the array
    $_SESSION['cart'] = array_values($cart);
}

// Redirect back to cart page
header('Location: view_cart.php');
exit();
?>


<?php
// Start the session
session_start();

class CartSessionHandler {
    private $cart;

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = &$_SESSION['cart'];
    }

    // Add item to cart
    public function addToCart($item_id, $item_name, $price, $quantity = 1) {
        if (isset($this->cart[$item_id])) {
            // If item exists in cart, increment quantity
            $this->cart[$item_id]['quantity'] += $quantity;
        } else {
            // Add new item to cart
            $this->cart[$item_id] = array(
                'id' => $item_id,
                'name' => $item_name,
                'price' => $price,
                'quantity' => $quantity
            );
        }
    }

    // Remove item from cart
    public function removeFromCart($item_id) {
        if (isset($this->cart[$item_id])) {
            unset($this->cart[$item_id]);
            // Re-index the array to maintain numeric keys
            $this->cart = array_values($this->cart);
        }
    }

    // Calculate total price of cart items
    public function calculateTotal() {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
        return $total;
    }

    // Clear all items from cart
    public function clearCart() {
        unset($_SESSION['cart']);
        $_SESSION['cart'] = array();
        $this->cart = &$_SESSION['cart'];
    }

    // Get all items in cart
    public function getCartItems() {
        return $this->cart;
    }
}

// Usage example:
$cartHandler = new CartSessionHandler();

// Add item to cart
$cartHandler->addToCart(1, 'Product 1', 29.99, 2);
$cartHandler->addToCart(2, 'Product 2', 49.99, 1);

// Remove item from cart
$cartHandler->removeFromCart(1);

// Display cart contents
echo "<h3>Shopping Cart</h3>";
foreach ($cartHandler->getCartItems() as $item) {
    echo "Item: {$item['name']} - Quantity: {$item['quantity']} - Price: \${$item['price']}</br>";
}

// Calculate and display total price
$total = $cartHandler->calculateTotal();
echo "<h3>Total: \$$total</h3>";

// Clear cart
// $cartHandler->clearCart();

?>


<?php
session_start();

$item_id = $_GET['item_id'];
$item_name = $_GET['item_name'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

array_push($_SESSION['cart'], array(
    'id' => $item_id,
    'name' => $item_name
));

header('Location: view_cart.php');
?>


<?php
session_start();

echo "<h2>Shopping Cart</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $index => $item) {
        echo "Item ID: " . $item['id'] . ", Name: " . $item['name'];
        echo "<a href='remove_item.php?index=$index'>Remove</a><br>";
    }
} else {
    echo "Your cart is empty.";
}
echo "<a href='add_item.php'>Add Item</a>";
?>


<?php
session_start();

$index = $_GET['index'];
if (isset($_SESSION['cart'][$index])) {
    unset($_SESSION['cart'][$index]);
}

// Re-index the array to maintain sequential keys
$_SESSION['cart'] = array_values($_SESSION['cart']);
header('Location: view_cart.php');
?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $productPrice) {
    global $productId, $productName, $productPrice;
    
    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$productId])) {
        // If exists, increment quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // If not exists, add new product to cart
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'quantity' => 1
        );
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Re-index the session array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to update quantity of an item
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Function to display cart contents
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $total = $item['product_price'] * $item['quantity'];
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>$" . number_format($item['product_price'], 2) . "</td>";
            echo "<td><input type='number' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $item['product_id'] . ", this.value)'></td>";
            echo "<td>$" . number_format($total, 2) . "</td>";
            echo "<td><button onclick='removeFromCart(" . $item['product_id'] . ")'>Remove</button></td>";
            echo "</tr>";
        }
        
        // Calculate and display total amount
        $cartTotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cartTotal += $item['product_price'] * $item['quantity'];
        }
        
        echo "<tr><td colspan='4'><strong>Total:</strong></td><td>$" . number_format($cartTotal, 2) . "</td></tr>";
        echo "</table>";
    } else {
        echo "Your cart is empty!";
    }
}

// Example usage:
addToCart(1, 'Laptop', 999.99);
addToCart(2, 'Phone', 499.99);

displayCart();

?>


<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Check if product exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    unset($item); // Break the reference
    
    if (!$found) {
        $_SESSION['cart'][] = array(
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => 100, // Example price
            'name' => 'Product Name' // Example name
        );
    }
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $productId = $_POST['product_id'];
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Re-index the array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Empty cart
if (isset($_POST['empty_cart'])) {
    unset($_SESSION['cart']);
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if (!empty($_SESSION['cart'])): ?>
        <h1>Your Cart</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table border="1">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $total += $item['quantity'] * $item['price'];
                    ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                        <td>
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="submit" name="remove_item" value="Remove">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p>Total: $<?php echo number_format($total, 2); ?></p>
            <input type="submit" name="empty_cart" value="Empty Cart">
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <h1>Add to Cart</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="product_id" placeholder="Enter product ID">
        <input type="number" name="quantity" value="1">
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>
</body>
</html>


<?php
// Initialize the session
session_start();

// Check if the cart exists in the session. If not, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example: Adding items to the cart
$item_id = 1;
$quantity = 2;

// Check if the item already exists in the cart
if (array_search($item_id, array_column($_SESSION['cart'], 'item_id')) !== false) {
    // Update the quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['item_id'] == $item_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }
} else {
    // Add new item to the cart
    $_SESSION['cart'][] = array(
        'item_id' => $item_id,
        'quantity' => $quantity
    );
}

// Display the contents of the cart
echo "<h2>Your Cart:</h2>";
foreach ($_SESSION['cart'] as $item) {
    echo "Item ID: " . $item['item_id'] . ", Quantity: " . $item['quantity'] . "<br>";
}

// Remove an item from the cart (example)
if (isset($_GET['remove'])) {
    $remove_item_id = $_GET['remove'];
    
    // Search for the item and remove it
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['item_id'] == $remove_item_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Re-index the array keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Optional: Destroy the session when user logs out
// session_unset();
// session_destroy();

?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_to_cart($product_id, $name, $price) {
    global $db;
    
    // Check if product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        // Re-index the array to maintain clean session data
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Update item quantity in cart
function update_cart($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id]) && is_numeric($quantity)) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Display cart content
function display_cart() {
    $total = 0;
    echo "<table>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td> $" . number_format($item['price'], 2) . "</td>";
        echo "<td><input type='number' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $item['id'] . ")'></td>";
        echo "<td><button onclick='removeItem(" . $item['id'] . ")'>Remove</button></td>";
        echo "</tr>";
        
        // Calculate total
        $total += ($item['price'] * $item['quantity']);
    }
    echo "<tr><td colspan='4'><strong>Total: $" . number_format($total, 2) . "</strong></td></tr>";
    echo "</table>";
}
?>


<?php
// Initialize the session
session_start();
?>


<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve item details from POST
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $name = isset($_POST['item_name']) ? htmlspecialchars(trim($_POST['item_name'])) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if item is already in cart
    $item_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item_exists = true;
            break;
        }
    }

    if ($item_exists) {
        echo "Item already exists in cart!";
    } else {
        // Add new item
        $_SESSION['cart'][] = array(
            'id' => $item_id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
        echo "Item added to cart successfully!";
    }
} else {
    echo "Error: Invalid request method.";
}
?>


<?php
session_start();

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "Your cart is empty.";
} else {
    echo "<h1>Your Cart</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Item ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['id'] . "</td>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td><a href='remove_from_cart.php?id=" . $item['id'] . "'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>


<?php
session_start();

if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);

    if (isset($_SESSION['cart'])) {
        // Loop through cart and remove item with matching id
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $item_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        // Re-index the array to avoid gaps in keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    echo "Item removed from cart!";
} else {
    echo "Error: No item specified.";
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
    <?php include 'view_cart.php'; ?>
</body>
</html>


<?php
// Code from Step 3 above...
?>


<?php
// Code from Step 5 above...
?>


<?php
// Code from Step 4 above...
?>


<?php
// Start the session
session_start();

// Set session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Set session name (optional, but recommended)
session_name('user_cart_session');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
$item_id = isset($_GET['add']) ? intval($_GET['add']) : null;
if ($item_id !== null) {
    // Assuming you have a database connection and product details
    $product_id = $item_id;
    $product_name = "Product " . $product_id;  // Replace with actual product name from database
    $product_price = 29.99;  // Replace with actual product price from database

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity']++;
    }
}

// Remove item from cart
$item_id_remove = isset($_GET['remove']) ? intval($_GET['remove']) : null;
if ($item_id_remove !== null) {
    unset($_SESSION['cart'][$item_id_remove]);
}

// Update quantity of item
$item_update = isset($_GET['update']) ? intval($_GET['update']) : null;
$new_quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

if ($item_update !== null && $new_quantity > 0) {
    $_SESSION['cart'][$item_update]['quantity'] = $new_quantity;
}

// Clear cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    $_SESSION['cart'] = array();
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $id => $item) {
    $total = $item['price'] * $item['quantity'];
    echo "<tr>";
    echo "<td>" . $item['name'] . "</td>";
    echo "<td>$" . number_format($item['price'], 2) . "</td>";
    echo "<td><input type='number' value='" . $item['quantity'] . "' onChange=\"updateQuantity($id, this.value)\"></td>";
    echo "<td>$" . number_format($total, 2) . "</td>";
    echo "<td><a href='?remove=$id'>Remove</a></td>";
    echo "</tr>";
}

echo "</table>";

// Display total price
$total_price = array_sum(array_map(function($item) {
    return $item['price'] * $item['quantity'];
}, $_SESSION['cart']));

echo "<h3>Total Price: $" . number_format($total_price, 2) . "</h3>";
?>

<!-- Add some styling -->
<style>
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

input[type="number"] {
    width: 60px;
    padding: 3px;
}

h2, h3 {
    color: #333;
    margin-bottom: 20px;
}
</style>

<!-- JavaScript for quantity update -->
<script>
function updateQuantity(id, quantity) {
    if (quantity > 0) {
        window.location.href = '?update=' + id + '&quantity=' + quantity;
    }
}
</script>

<!-- Add items to cart example links -->
<p>Add Items:</p>
<a href="?add=1">Add Product 1</a> |
<a href="?add=2">Add Product 2</a> |
<a href="?add=3">Add Product 3</a> |

<!-- Clear cart button -->
<br><br>
<button onclick="window.location.href='?clear'">Clear Cart</button>


<?php
session_start();
?>


function addToCart($item) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][] = $item;
    } else {
        $_SESSION['cart'] = array($item);
    }
}


if (!empty($_SESSION['cart'])) {
    echo "<h2>Your Cart:</h2>";
    foreach ($_SESSION['cart'] as $key => $value) {
        echo "Item #" . ($key + 1) . ": " . $value . "<br>";
    }
} else {
    echo "Your cart is empty.";
}


function removeFromCart($index) {
    if (!empty($_SESSION['cart'])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
    }
}


session_unset();
session_destroy();


<?php
session_start();

$item = "Product 1";
addToCart($item);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php include 'display_cart.php'; ?>
</body>
</html>


<?php
if (!empty($_SESSION['cart'])) {
    echo "<h2>Your Cart:</h2>";
    foreach ($_SESSION['cart'] as $key => $value) {
        echo "Item #" . ($key + 1) . ": " . $value . "<br>";
        echo "<a href='remove_from_cart.php?key=$key'>Remove</a><br><br>";
    }
} else {
    echo "Your cart is empty.";
}
?>


<?php
session_start();

if (isset($_GET['key'])) {
    $index = intval($_GET['key']);
    removeFromCart($index);
}

header("Location: display_cart.php");
exit();
?>


<?php
session_start();
?>


// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addToCart($productId, $name, $price) {
    // Check if product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity']++;
            $item['total'] = $item['price'] * $item['quantity'];
            return;
        }
    }

    // Add new product to cart
    $_SESSION['cart'][] = array(
        'product_id' => $productId,
        'name' => $name,
        'price' => $price,
        'quantity' => 1,
        'total' => $price
    );
}


function updateQuantity($productId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] = $newQuantity;
            $item['total'] = $item['price'] * $item['quantity'];
            return;
        }
    }
}


function removeFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Re-index the array keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}


function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['total'];
    }
    return $total;
}


function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty!";
        return;
    }

    foreach ($_SESSION['cart'] as $item) {
        echo "<div>";
        echo "<p>Product ID: " . $item['product_id'] . "</p>";
        echo "<p>Name: " . $item['name'] . "</p>";
        echo "<p>Price: $" . number_format($item['price'], 2) . "</p>";
        echo "<p>Quantity: " . $item['quantity'] . "</p>";
        echo "<p>Total: $" . number_format($item['total'], 2) . "</p>";
        echo "</div>";
    }
}


function clearCart() {
    unset($_SESSION['cart']);
    // Initialize empty cart again
    $_SESSION['cart'] = array();
}


addToCart(1, "Laptop", 999.99);


updateQuantity(1, 2);


removeFromCart(1);


displayCart();


clearCart();


<?php
// Start the session
session_start();

// Check if cart exists in session, if not initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $productPrice) {
    global $mysqli;
    
    // Check if product is already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        // Add new item to cart
        $_SESSION['cart'][] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $productPrice,
            'quantity' => 1
        );
    } else {
        // Update quantity if product already exists in cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity']++;
                break;
            }
        }
    }
}

// Function to update item quantity in cart
function updateQuantity($productId, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
            break;
        }
    }
}

// Function to calculate total price
function cartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    echo "<table border='1'>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['product_name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td><form method='post'><input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'></td>";
        echo "<td>$" . number_format(($item['price'] * $item['quantity']), 2) . "</td>";
        echo "<td><button type='submit'>Update</button> | <a href='cart.php?action=remove&id=" . $item['product_id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Display total price
    echo "<h3>Total: $" . number_format(cartTotal(), 2) . "</h3>";
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['quantity'])) {
        $productId = $_GET['id'];
        updateQuantity($productId, $_POST['quantity']);
    }
}

// Handle remove action
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $productId = $_GET['id'];
    removeFromCart($productId);
}
?>


<?php
// Start the session
session_start();

// Function to add item to cart
function add_to_cart($product_id, $product_name, $price) {
    // Check if cart is already set in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if item is already in cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to update quantity of an item in cart
function update_cart_item($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        // Re-index the array keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to display cart contents
function display_cart() {
    $output = '';
    
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $output .= "<h2>Your Shopping Cart</h2>";
        $output .= "<table border='1'>";
        $output .= "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $total_price = $item['price'] * $item['quantity'];
            $output .= "<tr>";
            $output .= "<td>{$item['product_name']}</td>";
            $output .= "<td>$$item[price]</td>";
            $output .= "<td><input type='number' value='{$item['quantity']}' onchange=\"updateQuantity({$item['product_id']}, this.value)\"></td>";
            $output .= "<td>$$total_price</td>";
            $output .= "<td><a href='remove_from_cart.php?product_id={$item['product_id']}'>Remove</a></td>";
            $output .= "</tr>";
        }
        
        $output .= "</table>";
    } else {
        $output .= "Your cart is empty.";
    }
    
    return $output;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


add_to_cart(1, 'Product 1', 29.99);


update_cart_item(1, 2); // Update quantity to 2 for product ID 1


remove_from_cart(1); // Remove product ID 1 from cart


echo display_cart();


<?php
// Initialize the session
session_start();
?>


// Check if the cart exists; if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


if (isset($_GET['action']) && $_GET['action'] == 'add') {
    if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
        
        // Check if product already exists in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += 1;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        
        header("Location: cart.php?action=added&product_id=$product_id");
        exit();
    } else {
        // Handle error: invalid product ID
        header("Location: cart.php?error=invalid_product");
        exit();
    }
}


if (isset($_GET['action']) && $_GET['action'] == 'update') {
    if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
        
        // Ensure the new quantity is valid
        if (isset($_GET['qty']) && is_numeric($_GET['qty'])) {
            $qty = intval($_GET['qty']);
            
            if ($qty > 0) {
                $_SESSION['cart'][$product_id] = $qty;
                header("Location: cart.php?action=updated&product_id=$product_id");
                exit();
            } else {
                // Remove the item if quantity is zero or negative
                unset($_SESSION['cart'][$product_id]);
                header("Location: cart.php?action=removed&product_id=$product_id");
                exit();
            }
        }
    }
}


if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
        
        // Remove the product from the cart
        unset($_SESSION['cart'][$product_id]);
        header("Location: cart.php?action=removed&product_id=$product_id");
        exit();
    }
}


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$cartContents = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    
    <?php if (!empty($cartContents)): ?>
        <table border="1">
            <tr>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($cartContents as $product_id => $quantity): ?>
                <tr>
                    <td><?php echo $product_id; ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td>
                        <a href="cart.php?action=remove&product_id=<?php echo $product_id; ?>">Remove</a> |
                        <a href="cart.php?action=update&product_id=<?php echo $product_id; ?>">Update Quantity</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <!-- Add some test links -->
    <h2>Add Items to Cart:</h2>
    <ul>
        <li><a href="cart.php?action=add&product_id=1">Add Product 1</a></li>
        <li><a href="cart.php?action=add&product_id=2">Add Product 2</a></li>
        <li><a href="cart.php?action=add&product_id=3">Add Product 3</a></li>
    </ul>

    <?php
    // Display messages based on the action
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'added':
                echo "<p>Product " . $_GET['product_id'] . " has been added to your cart!</p>";
                break;
            case 'removed':
                echo "<p>Product " . $_GET['product_id'] . " has been removed from your cart!</p>";
                break;
            case 'updated':
                echo "<p>Quantity for product " . $_GET['product_id'] . " has been updated!</p>";
                break;
        }
    }

    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'invalid_product':
                echo "<p>Error: Invalid product ID!</p>";
                break;
        }
    }
    ?>
</body>
</html>


<?php
// Start the session
session_start();

// Check if the cart exists in the session. If not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $productPrice) {
    global $error;
    
    // Check if product ID is valid
    if (empty($productId)) {
        $error = "Invalid product ID.";
        return false;
    }

    // Create an array for the item
    $item = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => 1
    );

    // Add the item to the cart session
    $_SESSION['cart'][$productId] = $item;
    return true;
}

// Function to display cart contents
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>₹" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td><a href='cart.php?action=remove&id=" . $item['id'] . "'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Function to remove item from cart
function removeItem($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Re-index the array after unsetting an element
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to clear all items from cart
function clearCart() {
    $_SESSION['cart'] = array();
}

// Handle cart actions based on request parameters
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $productName = "Product " . $productId; // Example product name
            $productPrice = 9.99 + $productId * 10; // Example price
            addToCart($productId, $productName, $productPrice);
            break;

        case 'remove':
            if (isset($_GET['id'])) {
                removeItem(intval($_GET['id']));
            }
            break;

        case 'clear':
            clearCart();
            break;
    }
}

// Display cart contents
displayCart();

// Optional: Add links to add items or clear cart
echo "<p><a href='cart.php?action=add&id=1'>Add Item 1</a></p>";
echo "<p><a href='cart.php?action=add&id=2'>Add Item 2</a></p>";
echo "<p><a href='cart.php?action=clear'>Clear Cart</a></p>";

// Display any error messages
if (isset($error)) {
    echo "<p>Error: " . $error . "</p>";
}
?>


<?php
session_start();

// Set up the save path for sessions if not already set
$savePath = 'path/to/session/directory';
if (!ini_get('session.save_path')) {
    ini_set('session.save_path', $savePath);
}
?>


// Sample product data (you would typically fetch this from a database)
$products = [
    1 => [
        'id' => 1,
        'name' => 'Laptop',
        'price' => 999.99,
        'quantity' => 5 // Stock quantity
    ],
    2 => [
        'id' => 2,
        'name' => 'Smartphone',
        'price' => 699.99,
        'quantity' => 10
    ],
    3 => [
        'id' => 3,
        'name' => 'Tablet',
        'price' => 299.99,
        'quantity' => 15
    ]
];


function addToCart($productId, $products) {
    global $products;
    
    // Check if the product exists in the products array
    if (!isset($products[$productId])) {
        return false;
    }
    
    // Get product details
    $product = $products[$productId];
    
    // Initialize cart session if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Increment quantity, ensuring we don't exceed stock
        $currentQuantity = $_SESSION['cart'][$productId]['quantity'];
        $newQuantity = $currentQuantity + 1;
        
        if ($newQuantity <= $product['quantity']) {
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        }
    } else {
        // Add the product to the cart with a quantity of 1
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        ];
    }
    
    return true;
}


function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Re-index the cart array to maintain clean keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true;
    }
    return false;
}


function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        // Ensure the new quantity is a positive integer and doesn't exceed stock
        $newQuantity = max(1, min((int)$newQuantity, $products[$productId]['quantity']));
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        return true;
    }
    return false;
}


function calculateCartTotal() {
    $total = 0.00;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
    }
    
    return number_format($total, 2); // Format to two decimal places
}


<?php if (!empty($_SESSION['cart'])) : ?>
    <h2>Your Shopping Cart</h2>
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
            <?php foreach ($_SESSION['cart'] as $item) : ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><input type="number" min="1" value="<?php echo $item['quantity']; ?>"></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="#" onclick="removeFromCart(<?php echo $item['id']; ?>)">Remove</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Total: $<?php echo calculateCartTotal(); ?></p>
<?php else : ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

<script>
function removeFromCart(productId) {
    // Make an AJAX request to remove the item from the cart
    fetch('cart.php?action=remove&productId=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh the page to update the cart display
            }
        });
}
</script>


// Add a product to the cart
productId = 1; // Product ID of the laptop
addToCart($productId, $products);

// Remove a product from the cart
removeFromCart(2); // Removes product with ID 2 (Smartphone)

// Update quantity of an item in the cart
updateQuantity(3, 5); // Sets the quantity of product ID 3 to 5

// Calculate and display the total amount
echo "Total Amount: $" . calculateCartTotal();


<?php
session_start();

// Sample product data (you would typically fetch this from a database)
$products = [
    1 => [
        'id' => 1,
        'name' => 'Laptop',
        'price' => 999.99,
        'quantity' => 5 // Stock quantity
    ],
    2 => [
        'id' => 2,
        'name' => 'Smartphone',
        'price' => 699.99,
        'quantity' => 10
    ],
    3 => [
        'id' => 3,
        'name' => 'Tablet',
        'price' => 299.99,
        'quantity' => 15
    ]
];

// Add a product to the cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['productId'])) {
    $productId = (int)$_GET['productId'];
    addToCart($productId, $products);
}

// Remove a product from the cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['productId'])) {
    $productId = (int)$_GET['productId'];
    removeFromCart($productId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <?php require_once 'cart-functions.php'; ?>
    
    <!-- Display Products -->
    <h1>Available Products</h1>
    <?php foreach ($products as $product) : ?>
        <div style="margin-bottom: 20px;">
            <h3><?php echo $product['name']; ?></h3>
            <p>$<?php echo number_format($product['price'], 2); ?></p>
            <a href="?action=add&productId=<?php echo $product['id']; ?>">Add to Cart</a>
        </div>
    <?php endforeach; ?>

    <!-- Display Cart -->
    <?php if (!empty($_SESSION['cart'])) : ?>
        <h2>Your Cart</h2>
        <table border="1">
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
                <?php foreach ($_SESSION['cart'] as $item) : ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><input type="number" min="1" value="<?php echo $item['quantity']; ?>"></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="?action=remove&productId=<?php echo $item['id']; ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total: $<?php echo calculateCartTotal(); ?></p>
    <?php else : ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <!-- Scripts -->
    <script>
        function removeFromCart(productId) {
            fetch('cart.php?action=remove&productId=' + productId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Refresh the page to update the cart display
                    }
                });
        }
    </script>
</body>
</html>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function addToCart($productId, $productName, $productPrice) {
    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$productId])) {
        // Increment quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Add new product
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $productPrice,
            'quantity' => 1
        );
    }
}

// Remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Update quantity of an item in cart
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId]) && is_numeric($newQuantity)) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Calculate total price
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Display cart contents
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
        foreach ($_SESSION['cart'] as $item) {
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>$" . number_format($item['price'], 2) . "</td>";
            echo "<td><input type='number' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $item['product_id'] . ", this.value);'></td>";
            echo "<td>$" . number_format(($item['price'] * $item['quantity']), 2) . "</td>";
            echo "<td><button onclick='removeFromCart(" . $item['product_id'] . ");'>Remove</button></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p>Total: $" . number_format(calculateTotal(), 2) . "</p>";
    } else {
        echo "Your cart is empty.";
    }
}

// Example usage:
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            addToCart(1, 'Product 1', 29.99);
            header("Location: $_SERVER[PHP_SELF]");
            break;
        
        case 'remove':
            removeFromCart($_GET['id']);
            header("Location: $_SERVER[PHP_SELF]");
            break;
        
        case 'update':
            updateQuantity($_GET['id'], $_GET['quantity']);
            header("Location: $_SERVER[PHP_SELF]");
            break;
    }
}

// Display cart
if (isset($_SESSION['cart'])) {
    displayCart();
} else {
    echo "No items in cart.";
}
?>


<?php
session_start();
?>


<?php
// Get product details from your database or form
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
$product_price = isset($_POST['product_price']) ? $_POST['product_price'] : 0;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to the cart
$item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => $quantity
);

$_SESSION['cart'][$product_id] = $item;

echo "Item added to cart!";
?>


<?php
// Check if cart exists in session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        $total = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>".$item['name']."</td>";
        echo "<td>$".$item['price']."</td>";
        echo "<td>".$item['quantity']."</td>";
        echo "<td>$".number_format($total, 2)."</td>";
        echo "<td><a href='remove_item.php?id=".$item['id']."'>Remove</a></td>";
        echo "</tr>";
    }

    // Calculate cart total
    $cart_total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += ($item['price'] * $item['quantity']);
    }
    echo "<tr><td colspan='4'><strong>Cart Total:</strong></td><td>$".number_format($cart_total, 2)."</td></tr>";
    echo "</table>";
} else {
    echo "Your cart is empty!";
}
?>


<?php
session_start();

// Get product ID from URL
$product_id = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($product_id)) {
    // Remove the item from cart
    unset($_SESSION['cart'][$product_id]);

    // If cart becomes empty, reset it
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
}

// Redirect back to cart page
header("Location: view_cart.php");
exit();
?>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    $new_quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    if (!empty($product_id)) {
        // Update quantity
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Redirect back to cart page
header("Location: view_cart.php");
exit();
?>


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Assuming $product_id, $product_name, and $price are variables containing product details

$item = array(
    'product_id' => $product_id,
    'name' => $product_name,
    'quantity' => 1,
    'price' => $price
);

if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if the item already exists
    $_SESSION['cart'][$product_id]['quantity']++;
} else {
    // Add new item to cart
    $_SESSION['cart'][$product_id] = $item;
}


if (!empty($_SESSION['cart'])) {
    echo "<h2>Your Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        echo "<tr>";
        echo "<td>" . $item['product_id'] . "</td>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td><input type='number' name='quantity[" . $item['product_id'] . "]' value='" . $item['quantity'] . "'></td>";
        echo "<td>" . $item['price'] . "</td>";
        echo "<td>" . $subtotal . "</td>";
        echo "<td><a href='remove_item.php?id=" . $item['product_id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
} else {
    echo "<p>Your cart is empty.</p>";
}


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


<?php
session_start();

if (!empty($_SESSION['cart'])) {
    // Process the checkout here
    // For example, save to database or proceed with payment
    
    echo "<h2>Checkout</h2>";
    echo "<p>Your order has been processed!</p>";
    
    // Clear the cart after checkout
    unset($_SESSION['cart']);
} else {
    echo "<p>Your cart is empty.</p>";
}
?>


<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to login page or home page
header("Location: index.php");
exit();
?>


<?php
// Start the session
session_start();

// Check if the cart exists in the session, if not, initialize it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get product details (you would typically get these from a database)
$product_id = 1;
$product_name = "Sample Product";
$product_price = 9.99;

// Add item to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        
        // Check if product already exists in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                // Increment quantity
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        // If product doesn't exist in cart, add it
        if (!$found) {
            $item_array = array(
                'product_id' => $product_id,
                'product_name' => $product_name,
                'price' => $product_price,
                'quantity' => 1
            );
            $_SESSION['cart'][] = $item_array;
        }

        // Redirect to cart page or show confirmation message
        echo "<script>alert('Product added to cart');</script>";
    }
}

// Display cart contents
echo "Cart Contents:<br>";
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += ($item['price'] * $item['quantity']);
    echo $item['product_name'] . " x" . $item['quantity'] . "<br>";
}

echo "Total Price: $" . number_format($total_price, 2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Add to Cart</h1>
    
    <!-- Product details form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
        <input type="hidden" name="product_price" value="<?php echo $product_price; ?>">
        
        Quantity: <input type="number" name="quantity" min="1" max="99" value="1">
        <br>
        <button type="submit">Add to Cart</button>
    </form>

    <h2>Current Cart</h2>
    <p>Total Items: <?php echo count($_SESSION['cart']); ?></p>
    <p>Total Price: <?php echo "$" . number_format($total_price, 2); ?></p>

    <a href="view_cart.php">View Cart</a> | 
    <a href="continue_shopping.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $price) {
    global $cart;
    
    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    
    // Add new item if not found
    if (!$found) {
        $_SESSION['cart'][] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to display cart contents
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
    } else {
        echo "<table>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $total = $item['price'] * $item['quantity'];
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>$$item[price]</td>";
            echo "<td><input type='number' min='1' value='" . $item['quantity'] . "'></td>";
            echo "<td>$$total</td>";
            echo "<td><a href='?remove=" . $item['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    
    // Re-index the array keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Function to calculate cart total
function calculateTotal() {
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}

// Initialize cart if it doesn't exist
initializeCart();

// Example usage:
if (isset($_GET['add'])) {
    // Add item to cart
    addToCart(1, 'Product 1', 29.99);
} elseif (isset($_GET['remove'])) {
    // Remove item from cart
    removeFromCart($_GET['remove']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <!-- Example product to add -->
    <a href="?add=1">Add Product 1 to Cart</a>

    <?php displayCart(); ?>

    <!-- Show total price -->
    <h3>Total: $<?php echo calculateTotal(); ?></h3>

    <!-- Destroy session -->
    <form action="" method="post">
        <input type="submit" name="destroy_session" value="Logout">
    </form>

    <?php
    // Destroy session when user logs out
    if (isset($_POST['destroy_session'])) {
        session_unset();
        session_destroy();
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
function addToCart($item_id, $item_name, $price, $quantity) {
    global $db;
    
    // Check if item is already in cart
    $is_in_cart = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['item_id'] == $item_id) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            $is_in_cart = true;
            break;
        }
    }

    // If item is not in cart, add it
    if (!$is_in_cart) {
        $_SESSION['cart'][] = array(
            'item_id' => $item_id,
            'item_name' => $item_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to update item quantity in cart
function updateCart($item_id, $quantity) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['item_id'] == $item_id) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
            
            // If quantity is 0, remove item from cart
            if ($quantity == 0) {
                unset($_SESSION['cart'][$key]);
                // Re-index the array to maintain keys
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
            break;
        }
    }
}

// Function to remove item from cart
function removeFromCart($item_id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['item_id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            // Re-index the array to maintain keys
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
}

// Function to display cart contents
function displayCart() {
    $total_price = 0;
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        foreach ($_SESSION['cart'] as $item) {
            $price = $item['price'];
            $quantity = $item['quantity'];
            $total = $price * $quantity;
            $total_price += $total;

            echo "<tr>";
            echo "<td>" . $item['item_name'] . "</td>";
            echo "<td>₹" . number_format($price, 2) . "</td>";
            echo "<td><a href='update_cart.php?action=decrease&id=" . $item['item_id'] . "'>-</a> " . $quantity . " <a href='update_cart.php?action=increase&id=" . $item['item_id'] . "'>+</a></td>";
            echo "<td>₹" . number_format($total, 2) . "</td>";
            echo "<td><a href='cart.php?action=remove&id=" . $item['item_id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<h3>Total: ₹" . number_format($total_price, 2) . "</h3>";
    } else {
        echo "Your cart is empty!";
    }
}

// Example usage:
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            $item_id = $_GET['id'];
            // Get item details from database or array
            $item_name = "Item Name";
            $price = 99.99;
            $quantity = 1;
            addToCart($item_id, $item_name, $price, $quantity);
            break;

        case 'update':
            $item_id = $_GET['id'];
            $quantity = $_GET['quantity'];
            updateCart($item_id, $quantity);
            break;

        case 'remove':
            $item_id = $_GET['id'];
            removeFromCart($item_id);
            break;
    }
}

// Display cart
displayCart();
?>


session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


session_start();

if (isset($_POST['add_to_cart'])) {
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    $productName = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $quantity = isset($_POST['quantity']) && ctype_digit($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if ($productId) {
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = array(
                'product_id' => $productId,
                'name' => $productName,
                'price' => $price,
                'quantity' => $quantity
            );
        }

        $message = "Item added to cart successfully!";
    } else {
        $message = "Error: Product not found.";
    }

    header("Location: product_page.php?msg=" . urlencode($message));
    die();
}


session_start();

echo "<h1>Shopping Cart</h1>";
if (!empty($_SESSION['cart'])) {
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    foreach ($_SESSION['cart'] as $item) {
        $total = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>${$item['price']}</td>";
        echo "<td>{$item['quantity']}</td>";
        echo "<td>${$total}</td>";
        echo "<td><a href='remove_from_cart.php?id={$item['product_id']}'>Remove</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Your cart is empty.";
}


session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Re-index the array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

header("Location: view_cart.php");
die();


<?php
// Initialize the session
session_start();

// Check if cart exists in the session, if not initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName) {
    global $mysqli;
    
    // Check if product is already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    
    // If not found, add new product to cart
    if (!$found) {
        $newItem = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'quantity' => 1
        );
        $_SESSION['cart'][] = $newItem;
    }
    
    // Redirect back to the product page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Function to display cart contents
function showCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        $total = 0;
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . number_format($subtotal, 2) . "</td>";
            echo "<td><a href='remove_from_cart.php?id=" . $item['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
        
        echo "<tr><td colspan='3'><strong>Grand Total:</strong></td><td>$" . number_format($total, 2) . "</td></tr>";
        echo "</table>";
    }
}

// Function to clear cart
function clearCart() {
    $_SESSION['cart'] = array();
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <!-- Add to cart form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        <input type="hidden" name="product_name" value="<?php echo $productName; ?>">
        <button type="submit">Add to Cart</button>
    </form>

    <!-- Display cart contents -->
    <?php showCart(); ?>

    <!-- Clear cart button -->
    <a href="clear_cart.php">Clear Cart</a>
</body>
</html>


<?php
// Start the session
session_start();

// Check if the cart is already set in the session, initialize it if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add an item to the cart (example)
$item_id = 1;
$item_name = "Product A";
$item_price = 29.99;
$item_quantity = 1;

// Create an associative array for the item
$item = array(
    'id' => $item_id,
    'name' => $item_name,
    'price' => $item_price,
    'quantity' => $item_quantity
);

// Add the item to the cart
array_push($_SESSION['cart'], $item);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php
    // Display the contents of the cart
    if (!empty($_SESSION['cart'])) {
        echo "<h2>Your Cart:</h2>";
        echo "<ul>";
        foreach ($_SESSION['cart'] as $index => $item) {
            echo "<li>";
            echo "ID: " . $item['id'];
            echo ", Name: " . $item['name'];
            echo ", Price: $" . number_format($item['price'], 2);
            echo ", Quantity: " . $item['quantity'];
            echo " <a href='remove_from_cart.php?index=" . $index . "'>Remove</a>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>
    
    <?php
    // Add an item form
    if (isset($_POST['add_to_cart'])) {
        $item_id = $_POST['item_id'];
        $item_name = $_POST['item_name'];
        $item_price = $_POST['item_price'];
        $item_quantity = $_POST['item_quantity'];
        
        // Validate input data
        if (!empty($item_id) && !empty($item_name) && !empty($item_price)) {
            $new_item = array(
                'id' => $item_id,
                'name' => $item_name,
                'price' => $item_price,
                'quantity' => $item_quantity
            );
            
            array_push($_SESSION['cart'], $new_item);
            echo "<p>Item added to cart!</p>";
        } else {
            echo "<p>Please fill in all required fields.</p>";
        }
    }
    ?>
    
    <h2>Add Item to Cart</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="item_id">Item ID:</label><br>
        <input type="text" id="item_id" name="item_id"><br><br>
        
        <label for="item_name">Item Name:</label><br>
        <input type="text" id="item_name" name="item_name"><br><br>
        
        <label for="item_price">Price:</label><br>
        <input type="number" step="0.01" id="item_price" name="item_price"><br><br>
        
        <label for="item_quantity">Quantity:</label><br>
        <input type="number" min="1" id="item_quantity" name="item_quantity" value="1"><br><br>
        
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>
</body>
</html>

<?php
// Remove an item from the cart (example)
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    
    if (!empty($index) && isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Re-index the array to maintain proper keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        echo "<p>Item removed from cart!</p>";
    } else {
        echo "<p>Invalid item index.</p>";
    }
}
?>


<?php
session_start(); // Initialize the session

// Check if cart exists in session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $item_name, $item_price) {
    global $conn; // Database connection
    
    // Check if the product exists in database
    $sql = "SELECT * FROM products WHERE id = '$item_id'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // If item not present in cart, add it
        if (!isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] = array(
                'id' => $item_id,
                'name' => $item_name,
                'price' => $item_price,
                'quantity' => 1
            );
            echo "Item added to cart!";
        } else {
            // If item already exists, increment quantity
            $_SESSION['cart'][$item_id]['quantity']++;
            echo "Quantity updated!";
        }
    } else {
        echo "Product not found!";
    }
}

// Function to view cart contents
function viewCart() {
    $total = 0;
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty!</p>";
    } else {
        echo "<table>";
        echo "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            echo "<tr>";
            echo "<td>".$item['name']."</td>";
            echo "<td>$".$item['price']."</td>";
            echo "<td>".$item['quantity']."</td>";
            echo "<td>$".number_format($subtotal, 2)."</td>";
            echo "</tr>";
        }
        
        echo "<tr><td colspan='3'><strong>Total:</strong></td><td>$".number_format($total, 2)."</td></tr>";
        echo "</table>";
    }
}

// Function to update item quantity
function updateQuantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        echo "Quantity updated!";
    }
}

// Function to delete item from cart
function deleteItem($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
        echo "Item removed from cart!";
    }
}

// Example usage:

if ($_GET['action'] == 'add') {
    $id = $_GET['id'];
    $name = $_GET['name'];
    $price = $_GET['price'];
    
    addToCart($id, $name, $price);
} elseif ($_GET['action'] == 'view') {
    viewCart();
} elseif ($_GET['action'] == 'update') {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    
    updateQuantity($id, $quantity);
} elseif ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    
    deleteItem($id);
}

?>


<?php
session_start();

// Sample product data (you can replace this with your own product fetching logic)
$products = [
    ['id' => 1, 'name' => 'Product 1', 'price' => 29.99],
    ['id' => 2, 'name' => 'Product 2', 'price' => 49.99],
    ['id' => 3, 'name' => 'Product 3', 'price' => 19.99]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
        <a href="view_cart.php">View Cart</a>
    <?php } ?>

    <h2>Products</h2>
    <ul>
        <?php foreach ($products as $product) { ?>
            <li>
                <?php echo $product['name']; ?> - $<?php echo number_format($product['price'], 2); ?>
                <form action="add_to_cart.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="number" name="quantity" min="1" value="1">
                    <button type="submit">Add to Cart</button>
                </form>
            </li>
        <?php } ?>
    </ul>
</body>
</html>


<?php
session_start();

// Get product data from form submission
$product_id = $_POST['id'];
$quantity = $_POST['quantity'];

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if item already exists in the cart
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
        $found = true;
        $item['quantity'] += $quantity;
        break;
    }
}

if (!$found) {
    // Add new item to the cart
    array_push($_SESSION['cart'], array(
        'id' => $product_id,
        'quantity' => $quantity
    ));
}

// Redirect back to main page or wherever you want
header("Location: index.php");
exit();


<?php
session_start();

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "Your cart is empty!";
    exit();
}

// For demonstration, we'll use a simple array of products (replace with your actual product data)
$products = [
    ['id' => 1, 'name' => 'Product 1', 'price' => 29.99],
    ['id' => 2, 'name' => 'Product 2', 'price' => 49.99],
    ['id' => 3, 'name' => 'Product 3', 'price' => 19.99]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h2>Your Shopping Cart</h2>
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $item) {
                // Find the product details using the ID
                foreach ($products as $product) {
                    if ($product['id'] == $item['id']) {
                        $total_price = $product['price'] * $item['quantity'];
                        ?>
                        <li>
                            <?php echo $product['name']; ?> (Quantity: <?php echo $item['quantity']; ?>)
                            - Total: $<?php echo number_format($total_price, 2); ?>
                            <form action="update_cart.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>">
                                <button type="submit">Update</button>
                            </form>
                            <form action="remove_from_cart.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </li>
                        <?php
                        break;
                    }
                }
            } ?>
        </ul>
        <a href="remove_from_cart.php">Empty Cart</a>
    <?php } else { ?>
        <p>Your cart is empty!</p>
    <?php } ?>

    <a href="index.php">Continue Shopping</a>
</body>
</html>


<?php
session_start();

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['id'];
    $new_quantity = $_POST['quantity'];

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }

    // Redirect back to cart view
    header("Location: view_cart.php");
    exit();
}


<?php
session_start();

if (isset($_POST['id'])) {
    $product_id = $_POST['id'];

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Redirect back to cart view
    header("Location: view_cart.php");
    exit();
}


<?php
function format_price($number) {
    return number_format($number, 2);
}
?>


<?php
session_start();
?>


<?php
session_start();

// Get product details from form submission
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
$product_price = isset($_POST['product_price']) ? $_POST['product_price'] : '';
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Create item array
$item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => $quantity
);

// Check if product already exists in cart
$found = false;
foreach ($_SESSION['cart'] as &$item_cart) {
    if ($item_cart['id'] == $product_id) {
        $item_cart['quantity'] += $quantity;
        $found = true;
        break;
    }
}

if (!$found) {
    array_push($_SESSION['cart'], $item);
}

// Redirect back to cart page
header("Location: view_cart.php");
exit();
?>


<?php
session_start();

$total_price = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /* Add your CSS styles here */
        .cart-item { margin: 10px; padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h2>Your Shopping Cart</h2>

    <?php
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $index => $item) {
            $total_price += ($item['price'] * $item['quantity']);
    ?>

        <div class="cart-item">
            <h3><?php echo $item['name']; ?></h3>
            <p>Price: <?php echo "$" . number_format($item['price'], 2); ?></p>
            <p>Quantity: <?php echo $item['quantity']; ?></p>
            <a href="remove_from_cart.php?index=<?php echo $index; ?>">Remove</a>
        </div>

    <?php
        }
        ?>

        <h3>Total Price: <?php echo "$" . number_format($total_price, 2); ?></h3>
        <a href="checkout.php">Proceed to Checkout</a>

    <?php
    } else {
        ?>

        <p>Your cart is empty.</p>

    <?php
    }
    ?>
</body>
</html>


<?php
session_start();

if (isset($_GET['index'])) {
    $index = $_GET['index'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Re-index the array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Redirect back to cart page
header("Location: view_cart.php");
exit();
?>


<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: login.php"); // Redirect to login page
exit();
?>


<form action="add_to_cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
    <input type="number" name="quantity" min="1" value="1">
    <button type="submit">Add to Cart</button>
</form>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $quantity = 1) {
    global $db;
    
    // Validate input
    $item_id = intval($item_id);
    $quantity = max(1, intval($quantity));
    
    if (isset($_SESSION['cart'][$item_id])) {
        // If item already exists in cart, update quantity
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Get product details from database
        $result = $db->query("SELECT * FROM products WHERE id = $item_id");
        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $_SESSION['cart'][$item_id] = array(
                'id' => $item_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            );
        }
    }
}

// Function to update cart quantity
function updateCart($item_id, $quantity) {
    $item_id = intval($item_id);
    $quantity = max(1, intval($quantity));
    
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($item_id) {
    $item_id = intval($item_id);
    
    unset($_SESSION['cart'][$item_id]);
    // Re-index the array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Display cart contents
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        foreach ($_SESSION['cart'] as $item) {
            echo "<tr>";
                echo "<td>{$item['name']}</td>";
                echo "<td>$$item['price']}</td>";
                echo "<td><input type='number' value='{$item['quantity']}' onChange='updateQuantity({$item['id']}, this.value)'></td>";
                echo "<td><a href='remove.php?id={$item['id']}'>Remove</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}

// Example usage:
// Adding item to cart
if (isset($_POST['add_to_cart'])) {
    addToCart($_POST['item_id'], $_POST['quantity']);
}

// Updating quantity
if (isset($_POST['update_quantity'])) {
    updateCart($_POST['item_id'], $_POST['quantity']);
}

// Removing item from cart
if (isset($_GET['remove_item'])) {
    removeFromCart($_GET['remove_item']);
}
?>


<?php
// Initialize the session
session_start();

// Include session configuration file
include_once "session_config.php";

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize an empty array for the cart
    $_SESSION['cart'] = array();
}

// Adding items to the cart
function addToCart($productId, $productName, $productPrice) {
    global $mysqli;
    
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // If yes, increment the quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // If no, add the product to the cart
        $item = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'quantity' => 1
        );
        $_SESSION['cart'][$productId] = $item;
    }
}

// Displaying the cart contents
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        return;
    }

    $total = 0;

    // Loop through each item in the cart
    foreach ($_SESSION['cart'] as $item) {
        $productTotal = $item['product_price'] * $item['quantity'];
        $total += $productTotal;

        echo "<div class='cart-item'>";
        echo "<p>Product ID: " . $item['product_id'] . "</p>";
        echo "<p>Product Name: " . $item['product_name'] . "</p>";
        echo "<p>Price: $" . number_format($item['product_price'], 2) . "</p>";
        echo "<p>Quantity: " . $item['quantity'] . "</p>";
        echo "<p>Total: $" . number_format($productTotal, 2) . "</p>";
        echo "</div>";
    }

    // Display the grand total
    echo "<h3>Grand Total: $" . number_format($total, 2) . "</h3>";
}

// Removing items from the cart
function removeItemFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Re-index the array to maintain proper structure
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Example usage:
if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);
    addToCart($productId, "Product Name", 29.99);
} elseif (isset($_GET['remove'])) {
    $productId = intval($_GET['remove']);
    removeItemFromCart($productId);
}

// Display the cart
displayCart();
?>


<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set session save path
$save_path = ini_set('session.save_path', '/tmp');
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add to Cart</title>
</head>
<body>
    <h1>Product Details</h1>
    <form action="add_to_cart.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input type="text" name="product_name" placeholder="Enter product name">
        <input type="number" name="price" placeholder="Enter price">
        <input type="number" name="quantity" placeholder="Enter quantity">
        <button type="submit">Add to Cart</button>
    </form>
</body>
</html>


<?php
session_start();

// Get product details from form
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : 0;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

// Validate input
if (empty($product_id) || empty($product_name) || $price <= 0 || $quantity <= 0) {
    die("Invalid product details");
}

// Sanitize input to prevent XSS attacks
$product_id = htmlspecialchars($product_id);
$product_name = htmlspecialchars($product_name);
$price = floatval($price);
$quantity = intval($quantity);

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if product already exists in cart
$product_exists = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $product_id) {
        $item['quantity'] += $quantity;
        $item['price'] += $price * $quantity;
        $product_exists = true;
        break;
    }
}

// If product does not exist, add it to the cart
if (!$product_exists) {
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'product_name' => $product_name,
        'price' => $price * $quantity,
        'quantity' => $quantity
    );
}

// Redirect back to the cart page
header("Location: view_cart.php");
exit();
?>


<?php
session_start();

// Check if cart exists in session
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
    <?php if (empty($_SESSION['cart'])) { ?>
        <p>Your cart is empty.</p>
    <?php } else { ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $item) { ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo "$" . number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="remove_from_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <p><a href="continue_shopping.php">Continue Shopping</a></p>
</body>
</html>


<?php
session_start();

// Get product ID from form
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';

// Validate input
if (empty($product_id)) {
    die("Invalid product ID");
}

// Remove the item from cart
foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['product_id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        break;
    }
}

// Re-index the array keys
$_SESSION['cart'] = array_values($_SESSION['cart']);

// Redirect back to cart page
header("Location: view_cart.php");
exit();
?>


<?php
// Start the session
session_start();

// Check if the cart exists in the session, initialize if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($productId, $productName, $productPrice) {
    global $mysqli; // Assuming you have a database connection
    
    // Check if the product exists in the database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Check if the item is already in the cart
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = array(
                'product_id' => $productId,
                'product_name' => $productName,
                'product_price' => $productPrice,
                'quantity' => 1
            );
            return true;
        }
    }
    return false;
}

// Function to remove an item from the cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Re-index the array keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true;
    }
    return false;
}

// Function to display the cart items
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<h2>Your Cart</h2>";
        echo "<ul>";
        foreach ($_SESSION['cart'] as $item) {
            echo "<li>" . $item['product_name'] . " - $" . number_format($item['product_price'], 2, '.', '') . 
                 " <button onclick='removeFromCart(" . $item['product_id'] . ")'>Remove</button></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = intval($_POST['product_id']);
        $productName = $_POST['product_name'];
        $productPrice = floatval($_POST['product_price']);
        
        if (addToCart($productId, $productName, $productPrice)) {
            echo "Item added to cart!";
        } else {
            echo "Error adding item.";
        }
    } elseif (isset($_POST['remove_from_cart'])) {
        $productId = intval($_POST['product_id']);
        if (removeFromCart($productId)) {
            echo "Item removed from cart!";
        } else {
            echo "Error removing item.";
        }
    }
}

// Display the current state of the cart
displayCart();
?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart function
function addToCart($itemId, $itemName, $itemPrice, $quantity) {
    global $cart;
    
    // Check if the item is already in the cart
    $itemExists = false;
    foreach ($cart as $item) {
        if ($item['id'] == $itemId) {
            $itemExists = true;
            break;
        }
    }

    if (!$itemExists) {
        $cart[] = array(
            'id' => $itemId,
            'name' => $itemName,
            'price' => $itemPrice,
            'quantity' => $quantity
        );
    } else {
        // Increment quantity if item already exists
        foreach ($cart as &$item) {
            if ($item['id'] == $itemId) {
                $item['quantity'] += $quantity;
            }
        }
    }

    // Update session cart
    $_SESSION['cart'] = $cart;
}

// Display cart contents function
function displayCart() {
    global $cart;

    echo "<h2>Shopping Cart</h2>";
    if (empty($cart)) {
        echo "Your cart is empty.";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Item ID</th><th>Item Name</th><th>Price</th><th>Quantity</th></tr>";
        foreach ($cart as $item) {
            echo "<tr>";
            echo "<td>" . $item['id'] . "</td>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>$" . number_format($item['price'], 2) . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// Example usage:
$cart = $_SESSION['cart'];

// Add items to cart
addToCart(1, 'Laptop', 999.99, 1);
addToCart(2, 'Phone', 499.99, 2);

// Display cart contents
displayCart();

?>


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addToCart($product_id, $product_name, $price) {
    // Create an item array with product details and quantity
    $item = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => 1
    );
    
    // Check if the cart is empty
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array($item);
    } else {
        $found = false;
        
        // Loop through existing items to check for duplicates
        foreach ($_SESSION['cart'] as &$existing_item) {
            if ($existing_item['id'] == $product_id) {
                $existing_item['quantity']++;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            // Add new item to the cart
            array_push($_SESSION['cart'], $item);
        }
    }
}


function removeFromCart($product_id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            // Re-index the array to avoid gaps in keys
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
}


<?php
if (!empty($_SESSION['cart'])) {
    echo "<table>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item) {
        $total = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td> $" . number_format($item['price'], 2, '.', '') . "</td>";
        echo "<td><input type='number' value='" . $item['quantity'] . "' min='1'></td>";
        echo "<td> $" . number_format($total, 2, '.', '') . "</td>";
        echo "<td><a href='remove.php?id=" . $item['id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "Your cart is empty.";
}
?>


<?php
session_start();

// Add an item to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = 1;
    $product_name = "Example Product";
    $price = 29.99;
    addToCart($product_id, $product_name, $price);
}

// Remove an item from the cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['id'];
    removeFromCart($product_id);
}
?>


<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Include the header file
include("header.php");
?>

<!-- Add to Cart Form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id">
    <button type="submit">Add to Cart</button>
</form>

<?php
// Add item to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    
    if (isset($product_id) && !empty($product_id)) {
        if (isset($_SESSION['cart'][$product_id])) {
            // Increment quantity
            $_SESSION['cart'][$product_id]++;
        } else {
            // Add new item
            $_SESSION['cart'][$product_id] = 1;
        }
    }
}

// Display cart contents
echo "<h2>Shopping Cart:</h2>";
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $item_id => $quantity) {
        echo "Item ID: $item_id, Quantity: $quantity <br>";
    }
}

// Include the footer file
include("footer.php");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>


<br>
<a href="cart.php">View Cart</a><br>
<a href="logout.php">Logout</a>
</body>
</html>


     if (!isset($_SESSION['cart'])) {
         $_SESSION['cart'] = array();
     }
     

3. **Add Items to Cart:**
   - Retrieve the product ID from the query string.
   - Check if the product exists in the cart. If not, add it with a quantity of 1; otherwise, increment the quantity.
     
     if (!isset($_SESSION['cart'][$product_id])) {
         $_SESSION['cart'][$product_id] = array(
             'id' => $product_id,
             'quantity' => 1
         );
     } else {
         $_SESSION['cart'][$product_id]['quantity']++;
     }
     

4. **Remove Items from Cart:**
   - Remove the product ID from the session cart and reindex the array.
     
     if (isset($_SESSION['cart'][$product_id])) {
         unset($_SESSION['cart'][$product_id]);
         $_SESSION['cart'] = array_values($_SESSION['cart']);
     }
     

5. **Update Item Quantity:**
   - Validate the new quantity and update it in the session.
     
     $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
     $_SESSION['cart'][$product_id]['quantity'] = $quantity;
     

6. **Clear the Cart:**
   - Reset the cart to an empty array.
     
     $_SESSION['cart'] = array();
     

7. **Display Cart Contents:**
   - Loop through the session cart and display each item's details and total price.

**Example Code Implementation:**



<?php
session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


if (isset($_POST['add_to_cart'])) {
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    $productName = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : 0;

    if ($productId && $productName && $price) {
        // Check if product is already in cart
        if (isset($_SESSION['cart'][$productId])) {
            // Increment quantity
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            // Add new item
            $_SESSION['cart'][$productId] = array(
                'name' => $productName,
                'price' => $price,
                'quantity' => 1
            );
        }
    }
}


echo "<h2>Your Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

$total = 0;

foreach ($_SESSION['cart'] as $productId => $item) {
    $productTotal = $item['price'] * $item['quantity'];
    $total += $productTotal;
    echo "<tr>";
    echo "<td>{$item['name']}</td>";
    echo "<td>₹{$item['price']}</td>";
    echo "<td>{$item['quantity']}</td>";
    echo "<td>₹" . number_format($productTotal, 2) . "</td>";
    echo "<td><a href='cart.php?action=remove&product_id=$productId'>Remove</a></td>";
    echo "</tr>";
}

echo "<tr><td colspan='4' align='right'><strong>Grand Total: ₹" . number_format($total, 2) . "</strong></td></tr>";
echo "</table>";


if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    // Product details from form or database
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    $productName = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : 0;

    if ($productId && $productName && $price) {
        // Check if product exists in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = array(
                'name' => $productName,
                'price' => $price,
                'quantity' => 1
            );
        }
    }
}

// Remove from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Display cart
echo "<h2>Your Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

$total = 0;

foreach ($_SESSION['cart'] as $productId => $item) {
    $productTotal = $item['price'] * $item['quantity'];
    $total += $productTotal;
    echo "<tr>";
    echo "<td>{$item['name']}</td>";
    echo "<td>₹{$item['price']}</td>";
    echo "<td>{$item['quantity']}</td>";
    echo "<td>₹" . number_format($productTotal, 2) . "</td>";
    echo "<td><a href='cart.php?action=remove&product_id=$productId'>Remove</a></td>";
    echo "</tr>";
}

echo "<tr><td colspan='4' align='right'><strong>Grand Total: ₹" . number_format($total, 2) . "</strong></td></tr>";
echo "</table>";
?>


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];

    // Create an array for the product item
    $item = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price
    );

    // Add the item to the cart session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    array_push($_SESSION['cart'], $item);

    // Redirect back to the cart page or a confirmation page
    header("Location: view_cart.php");
    exit();
}
?>


<?php
session_start();
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
            border: 1px solid #ccc;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px dotted #ccc;
        }
    </style>
</head>
<body>
    <div class="cart">
        <?php if (empty($_SESSION['cart'])) { ?>
            <p>Your cart is empty.</p>
        <?php } else { ?>
            <h2>Shopping Cart</h2>
            <ul>
                <?php foreach ($_SESSION['cart'] as $index => $item) { ?>
                    <li class="cart-item">
                        <span><?php echo $item['name']; ?></span>
                        <span>$<?php echo number_format($item['price'], 2); ?></span>
                        <a href="edit_cart.php?index=<?php echo $index; ?>">Edit</a>
                        <a href="remove_from_cart.php?index=<?php echo $index; ?>">Remove</a>
                    </li>
                <?php } ?>
            </ul>
            <p>Total: $<?php echo number_format(array_sum(array_column($_SESSION['cart'], 'price')), 2); ?></p>
        <?php } ?>
    </div>
</body>
</html>


<?php
session_start();

if (isset($_GET['index']) && is_numeric($_GET['index'])) {
    $index = $_GET['index'];
    
    if (isset($_SESSION['cart'][$index])) {
        // You can modify the item here or redirect to a form for editing quantities
        unset($_SESSION['cart'][$index]);
        array_values($_SESSION['cart']);
        
        header("Location: view_cart.php");
        exit();
    }
}
?>


<?php
session_start();

if (isset($_GET['index']) && is_numeric($_GET['index'])) {
    $index = $_GET['index'];
    
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        array_values($_SESSION['cart']);
        
        header("Location: view_cart.php");
        exit();
    }
}
?>


<?php
// Start the session
session_start();

// Check if the cart exists in the session, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $name, $price) {
    global $conn; // Assuming you have a database connection

    // Check if the item is already in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // Update quantity
        $_SESSION['cart'][$item_id]['quantity']++;
        $_SESSION['cart'][$item_id]['total_price'] = $_SESSION['cart'][$item_id]['price'] * $_SESSION['cart'][$item_id]['quantity'];
    } else {
        // Add new item to cart
        $new_item = array(
            'id' => $item_id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1,
            'total_price' => $price
        );
        $_SESSION['cart'][$item_id] = $new_item;
    }
}

// Function to update cart item quantity
function updateCartItem($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        $_SESSION['cart'][$item_id]['total_price'] = $_SESSION['cart'][$item_id]['price'] * $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to calculate grand total of cart items
function getGrandTotal() {
    $grand_total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $grand_total += $item['total_price'];
    }
    return $grand_total;
}

// Example usage:
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            // Add item to cart
            $item_id = intval($_GET['id']);
            $name = "Product ".$_GET['id'];
            $price = 19.99; // Example price
            addToCart($item_id, $name, $price);
            break;
            
        case 'update':
            // Update quantity of item
            $item_id = intval($_GET['id']);
            $quantity = intval($_GET['quantity']);
            updateCartItem($item_id, $quantity);
            break;

        case 'remove':
            // Remove item from cart
            $item_id = intval($_GET['id']);
            removeFromCart($item_id);
            break;
            
        case 'clear':
            // Clear all items from cart
            $_SESSION['cart'] = array();
            break;
    }
}

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $item) {
    echo "<tr>";
    echo "<td>".$item['name']."</td>";
    echo "<td>$".$item['price']."</td>";
    echo "<td><input type='number' value='".$item['quantity']."' onChange='updateQuantity(".$item['id'].", this.value)'></td>";
    echo "<td>$".$item['total_price']."</td>";
    echo "<td><a href='cart.php?action=remove&id=".$item['id']."'>Remove</a></td>";
    echo "</tr>";
}

echo "</table>";
echo "<h3>Grand Total: $".getGrandTotal()."</h3>";

// Include a clear cart button
echo "<br /><a href='cart.php?action=clear'>Clear Cart</a>";
?>


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Get product details (e.g., from a form submission)
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

// Check if the product is already in the cart
if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id] += $quantity;
} else {
    // If not, add the product to the cart with the given quantity
    $_SESSION['cart'][$product_id] = $quantity;
}

echo "Product added to cart successfully!";


if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // Regenerate the session ID to prevent session fixation attacks
        session_regenerate();
        
        echo "Product removed from cart successfully!";
    }
}


if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = max(0, intval($_POST['quantity'])); // Ensure quantity is not negative

    if ($new_quantity === 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $new_quantity;
    }

    session_regenerate(); // Regenerate the session ID for security
}


if (!empty($_SESSION['cart'])) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Here, you would typically fetch product details from a database
        // For this example, we'll assume each product ID corresponds to a product name
        // and price.
        
        $product_name = "Product " . $product_id;
        $price = 10.00; // Replace with actual product price
        
        echo "<li>";
        echo "$product_name - Quantity: $quantity";
        echo "</li>";
    }
    
    echo "</ul>";
} else {
    echo "Your cart is empty!";
}


<?php
// Initialize session
session_start();

// Check if session is already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set initial cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $name, $price) {
    global $conn; // Database connection

    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    // If not found, add new item to cart
    if (!$found) {
        $uniqueId = uniqid();
        $_SESSION['cart'][] = array(
            'product_id' => $productId,
            'name' => $name,
            'price' => $price,
            'quantity' => 1,
            'unique_id' => $uniqueId
        );
    }

    // Update session cart
    $_SESSION['cart'] = $_SESSION['cart'];
}

// Function to remove item from cart
function removeFromCart($uniqueId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['unique_id'] == $uniqueId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Re-index array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Function to display cart content
function displayCart() {
    $total = 0;
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>₹" . number_format($item['price'], 2) . "</td>";
        echo "<td><input type='number' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $item['unique_id'] . ", this.value)' style='width:50px'></td>";
        echo "<td>₹" . number_format($subtotal, 2) . "</td>";
        echo "<td><a href='remove.php?id=" . $item['unique_id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "<tr><td colspan='4' style='text-align:right; font-weight:bold;'>Grand Total:</td><td>₹" . number_format($total, 2) . "</td></tr>";
    echo "</table>";
}

// Function to update quantity
function updateQuantity($uniqueId, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['unique_id'] == $uniqueId) {
            $item['quantity'] = max(1, intval($quantity));
            break;
        }
    }
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
// Add item to cart
addToCart(1, 'Product Name', 100);

// Display cart content
echo "<h2>Shopping Cart</h2>";
displayCart();

// Remove item from cart (link in HTML)
<a href='remove.php?id=<?php echo $item['unique_id']; ?>'>Remove</a>


<?php
// Start the session
session_start();

// Check if session is not set, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $item_name, $price) {
    global $cart;
    
    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if item already exists in cart
    if (isset($_SESSION['cart'][$item_id])) {
        // If exists, increment quantity
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        // If not exists, add new item
        $_SESSION['cart'][$item_id] = array(
            'item_id' => $item_id,
            'name' => $item_name,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to update cart quantity
function updateCart($item_id, $quantity) {
    global $cart;
    
    if ($quantity == 0) {
        removeItemFromCart($item_id);
        return;
    }
    
    $_SESSION['cart'][$item_id]['quantity'] = $quantity;
}

// Function to remove item from cart
function removeItemFromCart($item_id) {
    unset($_SESSION['cart'][$item_id]);
}

// Function to calculate total price
function calculateTotal() {
    $total_price = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total_price += ($item['price'] * $item['quantity']);
    }
    
    return $total_price;
}

// Display cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Item Name</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $item_id => $item) {
    $total = $item['price'] * $item['quantity'];
    echo "<tr>";
    echo "<td>" . $item['name'] . "</td>";
    echo "<td><input type='number' min='1' value='" . $item['quantity'] . "' onChange='updateQuantity(" . $item_id . ", this.value)'></td>";
    echo "<td>£" . number_format($item['price'], 2) . "</td>";
    echo "<td>£" . number_format($total, 2) . "</td>";
    echo "<td><a href='remove.php?id=" . $item_id . "'>Remove</a></td>";
    echo "</tr>";
}

echo "</table>";
echo "<p>Total: £" . number_format(calculateTotal(), 2) . "</p>";

// Destroy session when done
session_unset();
session_destroy();

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$cart = $_SESSION['cart'];

// Example product data (you would typically retrieve this from a database)
$products = [
    1 => ['name' => 'Product 1', 'price' => 19.99],
    2 => ['name' => 'Product 2', 'price' => 29.99],
    3 => ['name' => 'Product 3', 'price' => 39.99]
];

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    
    // Check if the product exists in your products array or database
    if (!isset($products[$productId])) {
        die('Product not found!');
    }
    
    // Product details
    $product = [
        'id' => $productId,
        'name' => $products[$productId]['name'],
        'price' => $products[$productId]['price'],
        'quantity' => 1,
        'total_price' => $products[$productId]['price']
    ];
    
    // Check if product is already in cart
    $productExists = false;
    foreach ($cart as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += 1;
            $item['total_price'] = $item['price'] * $item['quantity'];
            $productExists = true;
            break;
        }
    }
    
    // Add new product to cart
    if (!$productExists) {
        array_push($cart, $product);
    }
    
    // Update the session cart
    $_SESSION['cart'] = $cart;
    
    // Redirect back to the cart page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $productId = intval($_GET['remove']);
    
    foreach ($cart as $key => $item) {
        if ($item['id'] == $productId) {
            unset($cart[$key]);
            break;
        }
    }
    
    // Re-index the array
    $_SESSION['cart'] = array_values($cart);
    
    // Redirect back to the cart page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Display cart contents
echo '<h2>Your Cart</h2>';
if (empty($cart)) {
    echo 'Your cart is empty.';
} else {
    foreach ($cart as $item) {
        echo "<div>";
        echo "Product: {$item['name']}<br />";
        echo "Price: \${$item['price']}<br />";
        echo "Quantity: {$item['quantity']}<br />";
        echo "Total Price: \${$item['total_price']}<br />";
        echo "<a href='?remove={$item['id']}'>Remove</a>";
        echo "</div><hr />";
    }
}

// Optional: Display the total amount
$total = array_sum(array_column($cart, 'total_price'));
echo '<h3>Total Amount: $' . number_format($total, 2) . '</h3>';
?>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function addToCart($productId, $productName, $productPrice) {
    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$productId])) {
        // Increment quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$productId] = array(
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => 1
        );
    }
}

// Calculate total price of items in cart
function cartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $productIdToRemove = $_GET['remove'];
    unset($_SESSION['cart'][$productIdToRemove]);
}

// Display cart contents
echo "<h2>Your Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $item) {
    echo "<tr>";
    echo "<td>" . $item['name'] . "</td>";
    echo "<td>$" . number_format($item['price'], 2) . "</td>";
    echo "<td>" . $item['quantity'] . "</td>";
    echo "<td><a href='?remove=" . $item['id'] . "'>Remove</a></td>";
    echo "</tr>";
}

echo "</table>";
echo "<p>Total: $" . number_format(cartTotal(), 2) . "</p>";
?>

<!-- Example usage to add items to cart -->
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?add=1&name=Product%201&price=49.99">Add Product 1</a><br>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?add=2&name=Product%202&price=99.99">Add Product 2</a>

<?php
// Handle adding items to cart when clicking the links above
if (isset($_GET['add'])) {
    $productId = $_GET['add'];
    $productName = urldecode($_GET['name']);
    $productPrice = $_GET['price'];
    addToCart($productId, $productName, $productPrice);
}
?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add product to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product details from form
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Validate input
    if ($productId > 0 && $quantity >= 1) {
        // Check if product already exists in cart
        if (isset($_SESSION['cart'][$productId])) {
            // Update quantity
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $_SESSION['cart'][$productId] = array(
                'product_id' => $productId,
                'quantity' => $quantity
            );
        }
    }
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $productIdToRemove = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$productIdToRemove])) {
        unset($_SESSION['cart'][$productIdToRemove]);
    }
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
        .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <!-- Add product form -->
    <form method="post" action="">
        Product ID: <input type="number" name="product_id" required><br>
        Quantity: <input type="number" name="quantity" value="1" min="1" required><br>
        <button type="submit">Add to Cart</button>
    </form>

    <!-- Display cart contents -->
    <?php if (!empty($_SESSION['cart'])) { ?>
        <h2>Cart Items:</h2>
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <div class="cart-item">
                Product ID: <?= $item['product_id'] ?><br>
                Quantity: <?= $item['quantity'] ?> 
                (<a href="?remove=<?= $item['product_id'] ?>">Remove</a>)
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>

</body>
</html>


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


if (isset($_GET['item_id']) && isset($_GET['quantity'])) {
    $item_id = $_GET['item_id'];
    $quantity = $_GET['quantity'];

    if (!empty($item_id) && !empty($quantity)) {
        if (is_numeric($quantity) && $quantity > 0) {
            // Update the cart
            if (isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id] += $quantity;
            } else {
                $_SESSION['cart'][$item_id] = $quantity;
            }
        } else {
            echo "Please enter a valid quantity.";
        }
    } else {
        echo "Item ID and quantity are required.";
    }
}


echo "<h2>Your Cart:</h2>";
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item_id => $quantity) {
        echo "Item ID: $item_id, Quantity: $quantity<br>";
        // Include remove functionality
        include_once 'remove_item.php';
    }
} else {
    echo "Your cart is empty.";
}


<?php
session_start();
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    if (!empty($item_id)) {
        unset($_SESSION['cart'][$item_id]);
    }
}
?>


foreach ($_SESSION['cart'] as $item_id => $quantity) {
    echo "Item ID: $item_id, Quantity: $quantity ";
    // Add a remove link that calls remove_item.php with the item_id
    echo "<a href='remove_item.php?item_id=$item_id'>Remove</a><br>";
}


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle adding items to cart
if (isset($_GET['item_id']) && isset($_GET['quantity'])) {
    $item_id = $_GET['item_id'];
    $quantity = $_GET['quantity'];

    if (!empty($item_id) && !empty($quantity)) {
        if (is_numeric($quantity) && $quantity > 0) {
            // Update the cart
            if (isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id] += $quantity;
            } else {
                $_SESSION['cart'][$item_id] = $quantity;
            }
        } else {
            echo "Please enter a valid quantity.";
        }
    } else {
        echo "Item ID and quantity are required.";
    }
}

// Display cart contents
echo "<h2>Your Cart:</h2>";
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item_id => $quantity) {
        echo "Item ID: $item_id, Quantity: $quantity ";
        // Include remove functionality
        echo "<a href='remove_item.php?item_id=$item_id'>Remove</a><br>";
    }
} else {
    echo "Your cart is empty.";
}

// HTML form to add items
echo <<<_END
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
    Item ID: <input type="text" name="item_id"><br>
    Quantity: <input type="number" name="quantity" min="1"><br>
    <button type="submit">Add to Cart</button>
</form>
_END;
?>


<?php
session_start();
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    if (!empty($item_id)) {
        unset($_SESSION['cart'][$item_id]);
    }
}
// Redirect back to cart page or reload
header("Location: cart.php");
exit();
?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
    global $cart;
    
    // Check if product already exists in cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            $product_exists = true;
            break;
        }
    }
    
    // If product doesn't exist, add it to cart
    if (!$product_exists) {
        $_SESSION['cart'][] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $cart;
    
    // Find and remove the product from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $cart;
    
    // Find and update the product quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

// Function to calculate cart total
function calculate_total() {
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    
    return $total;
}

// Example usage:
// Add items to cart
add_to_cart(1, 'Product A', 29.99, 2);
add_to_cart(2, 'Product B', 49.99, 1);

// Display cart contents
echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

// Remove an item from cart
remove_from_cart(2);

// Update quantity of an item
update_quantity(1, 3);

// Calculate and display total
echo "Cart Total: $" . calculate_total();

// Display updated cart contents
echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

// End the session
session_write_close();
?>


session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Assuming you're receiving product data from a form or query parameters
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // If not, add the new product to the cart
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => 1
        );
    }
}


echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $item) {
        echo "Product: " . $item['product_name'] . "<br />";
        echo "Price: $" . number_format($item['price'], 2) . "<br />";
        echo "Quantity: " . $item['quantity'] . "<br />";
        echo "Total: $" . number_format($item['price'] * $item['quantity'], 2) . "<br /><br />";
    }
}


if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        echo "Item removed from cart.";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        if (isset($_POST["quantity_$id"])) {
            $quantity = $_POST["quantity_$id"];
            if ($quantity > 0) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }
    }
}


$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

echo "Total Amount: $" . number_format($total, 2);

// Here you would typically add code to process payment,
// save the order to a database, and then clear the cart.
$_SESSION['cart'] = array();


<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    unset($_SESSION['cart'][$productId]);
}

// Update quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        if (isset($_POST["quantity_$id"])) {
            $quantity = $_POST["quantity_$id"];
            if ($quantity > 0) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }
    }
}

// Calculate total
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
    <?php if (empty($_SESSION['cart'])): ?>
        <h2>Your Shopping Cart is Empty</h2>
    <?php else: ?>
        <h2>Your Shopping Cart</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <div>
                    <p><?php echo $item['product_name']; ?></p>
                    <p>Price: <?php echo "$" . number_format($item['price'], 2); ?></p>
                    <p>Quantity: 
                        <input type="text" name="<?php echo "quantity_$id"; ?>" value="<?php echo $item['quantity']; ?>" size="3">
                        <a href="?remove=<?php echo $id; ?>">Remove</a>
                    </p>
                </div>
            <?php endforeach; ?>
            <br/>
            <input type="submit" name="update" value="Update Cart">
        </form>
    <?php endif; ?>

    <h3>Total: <?php echo "$" . number_format($total, 2); ?></h3>

    <!-- Add a form to add items -->
    <h2>Add Items</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="add" value="1">
        Product ID: <input type="text" name="product_id"><br/>
        Product Name: <input type="text" name="product_name"><br/>
        Price: <input type="text" name="price"><br/>
        <input type="submit" name="add_item" value="Add to Cart">
    </form>

    <!-- Checkout button -->
    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="checkout" value="1">
            <input type="submit" value="Proceed to Checkout">
        </form>
    <?php endif; ?>

</body>
</html>


<?php
// Start the session
session_start();

// Initialize cart session variable if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example product data (you can replace this with your actual product data)
$products = array(
    1 => array('id' => 1, 'name' => 'Product 1', 'price' => 19.99),
    2 => array('id' => 2, 'name' => 'Product 2', 'price' => 29.99),
    3 => array('id' => 3, 'name' => 'Product 3', 'price' => 9.99)
);

// Add item to cart
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $productId = intval($_GET['id']);
    
    // Check if the product exists in the products array
    if (isset($products[$productId])) {
        // Check if the item is already in the cart
        $itemExists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity']++;
                $itemExists = true;
                break;
            }
        }
        
        // If the item doesn't exist, add it to the cart
        if (!$itemExists) {
            $_SESSION['cart'][] = array(
                'id' => $products[$productId]['id'],
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => 1
            );
        }
    }
}

// Update quantity of an item in cart
if (isset($_POST['update'])) {
    foreach ($_SESSION['cart'] as &$item) {
        if (isset($_POST["quantity_{$item['id']}"])) {
            $quantity = intval($_POST["quantity_{$item['id']}"]);
            if ($quantity > 0) {
                $item['quantity'] = $quantity;
            }
        }
    }
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $productId = intval($_GET['id']);
    
    // Loop through the cart and remove the specified product
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Display cart items
echo "<h2>Your Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $item) {
    $total = $item['price'] * $item['quantity'];
    echo "<tr>";
    echo "<td>{$item['name']}</td>";
    echo "<td>$" . number_format($item['price'], 2) . "</td>";
    echo "<td><form method='post'><input type='number' name='quantity_{$item['id']}' min='1' value='{$item['quantity']}'></form></td>";
    echo "<td>$" . number_format($total, 2) . "</td>";
    echo "<td><a href='?action=remove&id={$item['id']}'>Remove</a></td>";
    echo "</tr>";
}

echo "</table>";

// Display product catalog
echo "<h2>Product Catalog</h2>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Action</th></tr>";

foreach ($products as $product) {
    echo "<tr>";
    echo "<td>{$product['name']}</td>";
    echo "<td>$" . number_format($product['price'], 2) . "</td>";
    echo "<td><a href='?action=add&id={$product['id']}'>Add to Cart</a></td>";
    echo "</tr>";
}

echo "</table>";

// Update cart button
echo "<form method='post'>";
echo "<input type='submit' name='update' value='Update Cart'>";
echo "</form>";
?>


<?php
session_start();

// Initialize cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $price, $quantity) {
    global $cart;
    
    // Check if product is already in the cart
    foreach ($_SESSION['cart'] as $key => $product) {
        if ($product['product_id'] == $productId) {
            // If exists, increase quantity
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            return true;
        }
    }
    
    // Add new product to cart
    $_SESSION['cart'][] = array(
        'product_id' => $productId,
        'product_name' => $productName,
        'price' => $price,
        'quantity' => $quantity
    );
    return true;
}

// Function to display cart items
function displayCart() {
    $total = 0;
    echo "<table>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $key => $product) {
        $itemTotal = $product['price'] * $product['quantity'];
        $total += $itemTotal;
        
        echo "<tr>";
        echo "<td>" . $product['product_name'] . "</td>";
        echo "<td>₹" . number_format($product['price'], 2) . "</td>";
        echo "<td><input type='number' value='" . $product['quantity'] . "'></td>";
        echo "<td>₹" . number_format($itemTotal, 2) . "</td>";
        echo "<td><a href='remove_from_cart.php?id=$key'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "<tr><td colspan='4'><strong>Total: ₹" . number_format($total, 2) . "</strong></td><td></td></tr>";
    echo "</table>";
}

// Function to remove item from cart
function removeFromCart($key) {
    unset($_SESSION['cart'][$key]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
}

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
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {background-color: #f5f5f5;}
    </style>
</head>
<body>

<?php
// Example usage:
// Include the cart.php file at the top of each page
include 'cart.php';

// Add item to cart example
if (isset($_GET['add'])) {
    addToCart(1, 'Product 1', 19.99, 2);
}

// Remove item from cart example
if (isset($_GET['remove'])) {
    removeFromCart($_GET['id']);
}

// Display cart items
displayCart();
?>

</body>
</html>


addToCart($productId, $productName, $price, $quantity);


removeFromCart($key);


<?php
// Start the session
session_start();

// Include necessary files
include_once 'header.php';

function add_item($product_id, $name, $price, $quantity) {
    // Initialize session variable if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if item already exists in cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Update quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

function remove_item($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}
?>

<!-- HTML Content -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <?php
    // Initialize session cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add item to cart
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['add_to_cart'])) {
            add_item($_POST['product_id'], $_POST['name'], $_POST['price'], $_POST['quantity']);
        }
        if (isset($_POST['remove_item'])) {
            remove_item($_POST['product_id']);
        }
    }

    // Display cart items
    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    if (empty($_SESSION['cart'])) {
        echo "<tr><td colspan='5'>Your cart is empty!</td></tr>";
    } else {
        foreach ($_SESSION['cart'] as $product_id => $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>₹" . number_format($item['price'], 2) . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>₹" . number_format(($item['price'] * $item['quantity']), 2) . "</td>";
            echo "<td><form method='post'><input type='hidden' name='product_id' value='$product_id'><button type='submit' name='remove_item'>Remove</button></form></td>";
            echo "</tr>";
        }
    }

    // Display total amount
    if (!empty($_SESSION['cart'])) {
        $total = calculate_total();
        echo "<tr><td colspan='4'><strong>Total:</strong> ₹" . number_format($total, 2) . "</td></tr>";
    }

    echo "</table>";

    // Add item form
    echo "<h3>Add Item to Cart</h3>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='add_to_cart' value='1'>";
    echo "<input type='text' name='product_id' placeholder='Product ID'>";
    echo "<input type='text' name='name' placeholder='Product Name'>";
    echo "<input type='number' name='price' placeholder='Price'>";
    echo "<input type='number' name='quantity' placeholder='Quantity'>";
    echo "<button type='submit'>Add to Cart</button>";
    echo "</form>";
    ?>

</body>
</html>

<?php
include_once 'footer.php';
?>


<?php
// Initialize the session
session_start();

// Check if the cart exists in the session, if not, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add items to the cart
function addToCart($item_id, $item_name, $price, $quantity) {
    global $connection;
    
    // Check if item already exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // Update quantity and price
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
        $_SESSION['cart'][$item_id]['price'] = $price * $quantity;
    } else {
        // Add new item to the cart
        $_SESSION['cart'][$item_id] = array(
            'id' => $item_id,
            'name' => $item_name,
            'price' => $price * $quantity,
            'quantity' => $quantity
        );
    }
}

// Example of adding items to the cart
addToCart(1, "Product 1", 10.99, 2);
addToCart(2, "Product 2", 5.99, 1);

// Function to display cart contents
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Item ID</th><th>Item Name</th><th>Quantity</th><th>Price</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            echo "<tr>";
            echo "<td>" . $item['id'] . "</td>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . number_format($item['price'], 2) . "</td>";
            echo "<td><a href='remove_item.php?id=" . $item['id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
}

// Display the cart contents
displayCart();

// Function to remove an item from the cart
function removeItem($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
        // Re-index the cart array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Example of removing an item from the cart
if (isset($_GET['remove'])) {
    removeItem($_GET['id']);
}

// Destroy the session when user logs out or completes purchase
if (isset($_GET['logout'])) {
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
}
?>


<?php
// Start the session
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity = 1) {
    global $pdo; // Assuming you have a database connection

    // Check if product exists in the database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Add to cart
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'quantity' => $quantity,
            'name' => $product['name'],
            'price' => $product['price']
        );
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = max(1, $quantity);
    }
}

// Function to display cart contents
function display_cart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        return;
    }

    foreach ($_SESSION['cart'] as $item) {
        echo "<div>";
        echo "Product ID: " . $item['id'] . "<br />";
        echo "Name: " . $item['name'] . "<br />";
        echo "Price: $" . number_format($item['price'], 2) . "<br />";
        echo "Quantity: " . $item['quantity'] . "<br />";
        echo "</div>";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'add':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    add_to_cart($_GET['id']);
                }
                break;
            
            case 'remove':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    remove_from_cart($_GET['id']);
                }
                break;

            case 'update':
                if (isset($_GET['id']) && isset($_GET['quantity']) 
                    && is_numeric($_GET['id']) && is_numeric($_GET['quantity'])) {
                    update_quantity($_GET['id'], $_GET['quantity']);
                }
                break;
        }
    }
}

// Display the cart
display_cart();
?>


<?php
// Start the session
session_start();

// Check if cart exists in session, if not initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $productPrice) {
    // If product is already in cart, increment quantity
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$productId] = array(
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => 1
        );
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Add product to cart example
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    addToCart($productId, $productName, $productPrice);
}

// Remove product from cart example
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    removeFromCart($productId);
}

// Display cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<table>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $product) {
        echo "<tr>";
        echo "<td>" . $product['name'] . "</td>";
        echo "<td>$" . number_format($product['price'], 2) . "</td>";
        echo "<td>" . $product['quantity'] . "</td>";
        echo "<td>$" . number_format($product['price'] * $product['quantity'], 2) . "</td>";
        echo "<td><a href='cart.php?remove=$productId'>Remove</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
}

echo "</table>";

// Display total price
$total = 0;
foreach ($_SESSION['cart'] as $product) {
    $total += $product['price'] * $product['quantity'];
}
echo "<h3>Total Price: $" . number_format($total, 2) . "</h3>";
?>

<!-- Add product form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="product_id" value="1">
    <input type="hidden" name="product_name" value="Product Name">
    <input type="hidden" name="product_price" value="9.99">
    <button type="submit">Add to Cart</button>
</form>


<?php
session_start();

// Check if cart session exists, if not create one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id) {
    global $cart;
    
    // Initialize cart if it doesn't exist
    if (empty($cart)) {
        $cart = array(
            $product_id => array(
                'id' => $product_id,
                'quantity' => 1
            )
        );
    } else {
        // Check if product already exists in cart
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity']++;
        } else {
            $cart[$product_id] = array(
                'id' => $product_id,
                'quantity' => 1
            );
        }
    }
    
    // Save updated cart to session
    $_SESSION['cart'] = $cart;
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $cart;
    
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity']--;
        
        // If quantity is zero or less, remove the product entirely
        if ($cart[$product_id]['quantity'] <= 0) {
            unset($cart[$product_id]);
        }
        
        // Save updated cart to session
        $_SESSION['cart'] = $cart;
    }
}

// Function to update item quantity in cart
function update_cart($product_id, $quantity) {
    global $cart;
    
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
        
        // Save updated cart to session
        $_SESSION['cart'] = $cart;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        add_to_cart($_POST['product_id']);
    } elseif (isset($_POST['remove_from_cart'])) {
        remove_from_cart($_POST['product_id']);
    } elseif (isset($_POST['update_quantity'])) {
        update_cart($_POST['product_id'], $_POST['quantity']);
    }
}

// Display cart contents
echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <!-- Add to Cart Form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Product ID: <input type="text" name="product_id"><br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

    <!-- Remove from Cart Form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Product ID: <input type="text" name="product_id"><br>
        <input type="submit" name="remove_from_cart" value="Remove from Cart">
    </form>

    <!-- Update Quantity Form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Product ID: <input type="text" name="product_id"><br>
        New Quantity: <input type="number" name="quantity"><br>
        <input type="submit" name="update_quantity" value="Update Quantity">
    </form>
</body>
</html>


<?php
// Start or initialize the session
session_start();

// Check if the cart exists in the session; if not, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add items to the cart
function addToCart($productId, $name, $price) {
    global $quantity;
    // Set default quantity if not set
    if (empty($quantity)) {
        $quantity = 1;
    }
    
    // Check if item is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // If not, add new item to the cart
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to display cart items
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    // Display the cart contents
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['product_id'] . "</td>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>" . $item['price'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td><a href='removeFromCart.php?productId=" . $item['product_id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    addToCart($productId, $name, $price);
}

// Display the cart
displayCart();
?>


<?php
// Start the session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $name, $price) {
    global $db;
    
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] == $product_id) {
            $_SESSION['cart'][$index]['quantity']++;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'name' => $name,
        'price' => $price,
        'quantity' => 1
    );
}

// Function to update quantity of an item
function update_quantity($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] == $product_id) {
            $_SESSION['cart'][$index]['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$index]);
            // Re-index the array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            return;
        }
    }
}

// Function to calculate total price of cart items
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Example usage:
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            add_to_cart(1, 'Product 1', 9.99);
            break;
        case 'update':
            update_quantity(1, $_POST['quantity']);
            break;
        case 'remove':
            remove_from_cart($_GET['product_id']);
            break;
    }
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " x" . $item['quantity'] . " - $" . number_format($item['price'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . number_format(calculate_total(), 2) . "</p>";
} else {
    echo "Your cart is empty.";
}

// Example buttons to modify cart
echo <<<HTML
<br>
<a href="?action=add">Add Item</a> |
<form method="post" action="?action=update">
Quantity: <input type="number" name="quantity" value="1">
<input type="submit" value="Update Quantity">
</form> |
<a href="?action=remove&product_id=1">Remove Item</a>
HTML;
?>


<?php
// Start the session
session_start();

// Check if the cart exists in the session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price) {
    global $quantity;
    
    // Check if product is already in the cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If quantity is set, update it; otherwise keep it as 1
        if (isset($quantity)) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        }
        
        // Update the total price for this product
        $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    } else {
        // Set default quantity to 1 if not set
        $qty = isset($quantity) ? $quantity : 1;
        
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $qty,
            'total_price' => $price * $qty
        );
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if ($new_quantity > 0 && array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['price'] * $new_quantity;
    }
}

// Function to calculate total price of all items in the cart
function calculate_total() {
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['total_price'];
    }
    
    return $total;
}

// Example usage:
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            // Add product with id 1, name "Product A", price 29.99
            add_to_cart(1, "Product A", 29.99);
            break;
            
        case 'remove':
            remove_from_cart($_GET['id']);
            break;
            
        case 'update':
            update_quantity($_GET['id'], $_GET['quantity']);
            break;
    }
}

// Display the cart
echo "<h1>Shopping Cart</h1>";
echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

// Display total price
echo "Total: $" . calculate_total();
?>


<?php
// Start the session
session_start();

// Check if the cart exists in the session; if not, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_GET['add_to_cart'])) {
    $productId = intval($_GET['product_id']);
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

    // Check if the product is already in the cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // Update quantity
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$productId] = $quantity;
    }

    // Redirect back to the cart page or product list
    header('Location: cart.php');
    exit();
}

// Remove item from cart
if (isset($_GET['remove_id'])) {
    $productId = intval($_GET['remove_id']);
    
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
    }
    
    // Redirect back to the cart page or product list
    header('Location: cart.php');
    exit();
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Product ID</th><th>Quantity</th><th>Action</th></tr>";

foreach ($_SESSION['cart'] as $productId => $quantity) {
    echo "<tr>";
    echo "<td>$productId</td>";
    echo "<td>$quantity</td>";
    echo "<td>";
    echo "<a href='?remove_id=$productId'>Remove</a> | ";
    echo "<a href='#' onclick=\"updateQuantity($productId)\">Update Quantity</a>";
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Update quantity form
echo "<script>
function updateQuantity(productId) {
    var newQuantity = prompt('Enter new quantity:', '');
    if (newQuantity !== null && !isNaN(newQuantity)) {
        window.location.href = '?update_quantity=1&product_id=' + productId + '&quantity=' + newQuantity;
    }
}
</script>";
?>

<?php
// Handle quantity update
if (isset($_GET['update_quantity'])) {
    $productId = intval($_GET['product_id']);
    $newQuantity = intval($_GET['quantity']);

    if ($newQuantity > 0 && array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $newQuantity;
    }

    // Redirect back to the cart page or product list
    header('Location: cart.php');
    exit();
}
?>


<?php
// Start the session
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addToCart($item_id, $name, $price) {
    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        // If it doesn't exist, add the item to the cart
        $_SESSION['cart'][$item_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    }
}


function removeFromCart($item_id) {
    // Check if the item exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


function updateQuantity($item_id, $quantity) {
    // Check if the item exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}


<?php
// Check if the cart is not empty
if (!empty($_SESSION['cart'])) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
    
    foreach ($_SESSION['cart'] as $item_id => $item) {
        $total = $item['price'] * $item['quantity'];
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td><input type='number' value='" . $item['quantity'] . "' onChange='updateQuantity(" . $item_id . ", this.value)'></td>";
        echo "<td>$" . number_format($total, 2) . "</td>";
        echo "<td><button onClick='removeFromCart(" . $item_id . ")'>Remove</button></td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>


function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return number_format($total, 2);
}


function emptyCart() {
    unset($_SESSION['cart']);
}


addToCart(1, 'Product 1', 19.99);


removeFromCart(1);


updateQuantity(1, 2);


displayCart();


echo "Total: $" . getCartTotal();


emptyCart();


<?php
session_start();
?>


class ShoppingCart {
    private $cart;

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = &$_SESSION['cart'];
    }

    public function add_item($product_id, $quantity = 1) {
        if (isset($this->cart[$product_id])) {
            $this->cart[$product_id]['quantity'] += $quantity;
        } else {
            $this->cart[$product_id] = array(
                'id' => $product_id,
                'quantity' => $quantity,
                'price' => 0.00 // Set default price
            );
        }
    }

    public function update_quantity($product_id, $new_quantity) {
        if (isset($this->cart[$product_id])) {
            $this->cart[$product_id]['quantity'] = max(0, $new_quantity);
        }
    }

    public function remove_item($product_id) {
        unset($this->cart[$product_id]);
    }

    public function clear_cart() {
        $this->cart = array();
    }

    public function get_cart_contents() {
        return $this->cart;
    }
}


<?php
session_start();
include 'ShoppingCart.php';

$cart = new ShoppingCart();

// Add an item
$cart->add_item(1, 2); // Adds product ID 1 with quantity 2

// Update quantity
$cart->update_quantity(1, 3);

// Remove an item
$cart->remove_item(1);

// Clear the cart
$cart->clear_cart();

// Get cart contents
$contents = $cart->get_cart_contents();
print_r($contents);
?>


<?php
session_start();
include 'ShoppingCart.php';

$cart = new ShoppingCart();
$contents = $cart->get_cart_contents();

if (empty($contents)) {
    echo "Your cart is empty.";
} else {
    foreach ($contents as $item) {
        echo "Product ID: {$item['id']}, Quantity: {$item['quantity']}<br>";
    }
}
?>


// Serialize
$_SESSION['cart'] = serialize($cartContents);

// Unserialize
$cartContents = unserialize($_SESSION['cart']);


<?php
session_start(); // Start the session

// Initialize cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
$item_id = 1; // Example item ID
$quantity = 2; // Example quantity

if (isset($_GET['action']) && $_GET['action'] == 'add') {
    if (!isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = array(
            'id' => $item_id,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    }
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $item) {
        echo "Item ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "<br>";
    }
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    if (!empty($_GET['item_id'])) {
        $item_id = intval($_GET['item_id']);
        if (isset($_SESSION['cart'][$item_id])) {
            unset($_SESSION['cart'][$item_id]);
            // Re-index the array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

// Destroy session when done
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
}
?>


<?php
// Start the session
session_start();

// Check if session exists, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $name, $price) {
    global $Cart;
    // Check if product already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to update quantity of an item in cart
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to calculate total price of items in cart
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Example usage:
// Add item to cart
add_to_cart(1, 'Product 1', 29.99);

// Update quantity of an item
update_quantity(1, 3);

// Remove item from cart
remove_from_cart(1);

// Calculate total price
$total = calculate_total();
echo "Total Price: $" . number_format($total, 2);

// Destroy the session when done
session_unset();
session_destroy();
?>


<?php
session_start();
?>


<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function addToCart($item_id, $item_name, $price) {
    global $mysqli;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['item_id'] == $item_id) {
            $item['quantity']++;
            return;
        }
    }
    
    // If not, add the item to the cart
    $_SESSION['cart'][] = array(
        'item_id' => $item_id,
        'item_name' => $item_name,
        'price' => $price,
        'quantity' => 1
    );
}


function removeFromCart($item_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['item_id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function updateQuantity($item_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['item_id'] == $item_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}


function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        foreach ($_SESSION['cart'] as $item) {
            $total = $item['price'] * $item['quantity'];
            echo "<tr>";
            echo "<td>" . $item['item_name'] . "</td>";
            echo "<td>₹" . number_format($item['price'], 2) . "</td>";
            echo "<td><input type='number' value='" . $item['quantity'] . "' onchange='updateQuantity(" . $item['item_id'] . ", this.value)'></td>";
            echo "<td>₹" . number_format($total, 2) . "</td>";
            echo "<td><button onclick='removeFromCart(" . $item['item_id'] . ")'>Remove</button></td>";
            echo "</tr>";
        }
        
        // Calculate and display total amount
        $cart_total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cart_total += $item['price'] * $item['quantity'];
        }
        
        echo "<tr><td colspan='4'>Total Amount:</td><td>₹" . number_format($cart_total, 2) . "</td></tr>";
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}


<?php
$item_id = 1;
$item_name = "Product Name";
$price = 100;

addToCart($item_id, $item_name, $price);
?>


<?php
displayCart();
?>


<?php
$item_id = 1;
removeFromCart($item_id);
?>


<?php
$item_id = 1;
$quantity = 2;
updateQuantity($item_id, $quantity);
?>


<?php
// Start the session
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_to_cart($product_id, $quantity) {
    // Sanitize inputs
    $product_id = intval($product_id);
    $quantity = intval($quantity);

    if ($quantity > 0) {
        // Check if product is already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        return true;
    }
    
    return false;
}

// Remove item from cart
function remove_from_cart($product_id) {
    $product_id = intval($product_id);
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    
    return false;
}

// Update quantity of an item in cart
function update_quantity($product_id, $quantity) {
    $product_id = intval($product_id);
    $quantity = intval($quantity);

    if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
        return true;
    }
    
    // If quantity is zero, remove the item
    if ($quantity <= 0 && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    
    return false;
}

// Get cart contents
function get_cart_contents() {
    return $_SESSION['cart'];
}

// Example usage:
// Add item to cart with product ID 1 and quantity 2
add_to_cart(1, 2);

// Remove item with product ID 1 from cart
remove_from_cart(1);

// Update quantity of item with product ID 1 to 3
update_quantity(1, 3);

// Get all items in the cart
$cart = get_cart_contents();

// Always close the session after operations
session_write_close();
?>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $productName, $price) {
    global $mysqli;
    
    // Check if product is already in cart
    $itemExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $itemExists = true;
            break;
        }
    }
    
    if ($itemExists) {
        // Increment quantity
        $item['quantity']++;
    } else {
        // Add new item to cart
        $_SESSION['cart'][] = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Function to update cart quantity
function updateCartQuantity($productId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            if ($newQuantity > 0) {
                $item['quantity'] = $newQuantity;
            } else {
                // Remove item if quantity is zero or negative
                removeItemFromCart($productId);
            }
        }
    }
}

// Function to remove item from cart
function removeItemFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
        }
    }
    
    // Re-index the array keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Function to calculate total price
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

// Example usage:
// Add item to cart
addToCart(1, 'Product A', 29.99);
addToCart(2, 'Product B', 49.99);

// Update quantity
updateCartQuantity(1, 3);

// Remove item from cart
removeItemFromCart(2);

// Get total price
echo "Total: $" . number_format(getCartTotal(), 2);

?>


addToCart($productId, $productName, $price);


updateCartQuantity($productId, $newQuantity);


removeItemFromCart($productId);


$total = getCartTotal();


<?php
// Start the session
session_start();

// Set some default session options (optional)
ini_set('session.cookie_httponly', 1);
ini_set('session.save_path', '/path/to/session/directory');

// Function to add item to cart
function addToCart($productId, $quantity) {
    global $_SESSION;

    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if product already exists in cart
    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            $productExists = true;
            break;
        }
    }

    // Add new product to cart if it doesn't exist
    if (!$productExists) {
        $_SESSION['cart'][] = array(
            'id' => $productId,
            'quantity' => $quantity
        );
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    global $_SESSION;

    // Check if cart exists and has items
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        // Search for product in cart
        $index = array_search($productId, array_column($_SESSION['cart'], 'id'));

        // Remove item if found
        if ($index !== false) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
        }
    }
}

// Function to display cart contents
function displayCart() {
    global $_SESSION;

    // Check if cart exists and has items
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        echo "<h2>Your Cart</h2>";
        echo "<ul>";
        foreach ($_SESSION['cart'] as $item) {
            // Here you would typically fetch product details from a database or array
            // For this example, we'll assume product details are known
            $product = getProductDetails($item['id']);
            echo "<li>{$product['name']} ({$product['price']}) x {$item['quantity']}</li>";
        }
        echo "</ul>";
    } else {
        echo "Your cart is empty.";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                addToCart($_POST['product_id'], $_POST['quantity']);
                break;
            case 'remove':
                removeFromCart($_POST['product_id']);
                break;
        }
    }
}

// Display cart contents
displayCart();

// Example function to get product details (you would typically fetch this from a database)
function getProductDetails($productId) {
    // This is just an example array - replace with your actual data source
    $products = array(
        1 => array('name' => 'Product A', 'price' => '$9.99'),
        2 => array('name' => 'Product B', 'price' => '$14.99'),
        3 => array('name' => 'Product C', 'price' => '$19.99')
    );
    
    return $products[$productId];
}
?>


<?php
// Start the session
session_start();

// Check if the cart exists in the session. If not, initialize it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Adding items to the cart
function addToCart($productId, $productName, $productPrice) {
    // Create a new item array with default quantity 1
    $item = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => 1
    );
    
    // Add the item to the cart session
    $_SESSION['cart'][] = $item;
}

// Updating quantity of items in the cart
function updateCartQuantity($productId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity;
        }
    }
}

// Removing items from the cart
function removeFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
        }
    }
    
    // Re-index the array after removing an item
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Displaying items in the cart
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
    } else {
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $totalPrice += $subtotal;
            
            echo "<div>";
            echo "Product ID: " . $item['id'] . "<br />";
            echo "Product Name: " . $item['name'] . "<br />";
            echo "Quantity: " . $item['quantity'] . "<br />";
            echo "Subtotal: $" . number_format($subtotal, 2) . "<br />";
            echo "</div>";
        }
        
        echo "<h3>Total Price: $" . number_format($totalPrice, 2) . "</h3>";
    }
}

// Example usage:
// Add an item to the cart
addToCart(1, "Product One", 49.99);

// Update the quantity of an item in the cart
updateCartQuantity(1, 2);

// Remove an item from the cart
removeFromCart(1);

// Display all items in the cart
displayCart();

// End the session
session_unset();
session_destroy();
?>


<?php
session_start();
// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'add':
        if ($productId !== null) {
            $newItem = array(
                'id' => $productId,
                'name' => 'Product Name',
                'price' => 9.99,
                'quantity' => isset($_GET['quantity']) ? intval($_GET['quantity']) : 1
            );
            
            if (isset($_SESSION['cart'][$ productId])) {
                // Update quantity or details
                $_SESSION['cart'][$productId]['quantity'] += $newItem['quantity'];
            } else {
                $_SESSION['cart'][$productId] = $newItem;
            }
        }
        break;

    case 'remove':
        if ($productId !== null) {
            unset($_SESSION['cart'][$productId]);
            // Reindex the array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        break;
}

// Display cart contents
echo "<h1>Shopping Cart</h1>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>{$item['name']} - \${$item['price']} x {$item['quantity']}
              <form method='get'>
                  <input type='hidden' name='action' value='remove'>
                  <input type='hidden' name='product_id' value='{$item['id']}'>
                  <button type='submit'>Remove</button>
              </form>
            </li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// Optional: Set session lifetime
ini_set('session.gc_maxlifetime', 3600);


<?php
// Start the session
session_start();

// Check if the cart session exists, if not initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function addToCart($productId, $productName) {
    global $pdo; // Assuming you have a database connection

    // Retrieve product details from database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        // Check if the product already exists in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            // Add new product to the cart
            $_SESSION['cart'][] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            );
        }
    }

    // Redirect back to the previous page
    header("Location: product.php");
    exit();
}

// Display cart contents
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<table border='1'>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>$" . number_format($item['price'], 2) . "</td>";
            echo "<td><input type='number' name='quantity[" . $item['id'] . "]' value='" . $item['quantity'] . "'></td>";
            echo "<td>$" . number_format($item['price'] * $item['quantity'], 2) . "</td>";
            echo "<td><a href='cart.php?action=remove&id=" . $item['id'] . "'>Remove</a></td>";
            echo "</tr>";
            
            $total += $item['price'] * $item['quantity'];
        }
        
        echo "<tr><td colspan='4'><strong>Total:</strong></td><td>$" . number_format($total, 2) . "</td></tr>";
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}

// Clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}
?>


<?php
session_start();

// Include the cart functions
include 'cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        addToCart($_POST['product_id'], $_POST['product_name']);
    }
}
?>


<?php
session_start();

// Include the cart functions
include 'cart_functions.php';

displayCart();
?>

<a href="cart.php?action=clear">Clear Cart</a>


<?php
// Start the session
session_start();

// Check if the cart exists in the session, initialize it if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($product_id, $name, $price) {
    global $conn; // Database connection (optional)
    
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If exists, increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // If not exists, add new item to cart
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'name'       => $name,
            'price'      => $price,
            'quantity'   => 1
        );
    }
}

// Function to remove item from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        // Re-index the session array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $productId = 1;
    $productName = "Sample Product";
    $productPrice = 29.99;
    
    addToCart($productId, $productName, $productPrice);
}

if (isset($_GET['remove_item'])) {
    $productId = $_GET['remove_item'];
    removeFromCart($productId);
}

// Displaying the cart items
echo "<h2>Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td><a href='cart.php?remove_item=" . $item['product_id'] . "'>Remove</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>Your cart is empty.</td></tr>";
}

echo "</table>";
?>



<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function addToCart($item_id, $name, $price) {
    global $conn; // Assuming $conn is your database connection

    // Check if item exists in the database
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        
        // Check if item is already in the cart
        $cart = &$_SESSION['cart'];
        $found = false;
        foreach ($cart as &$itemCart) {
            if ($itemCart['id'] == $item_id) {
                $itemCart['quantity']++;
                $found = true;
                break;
            }
        }

        // Add new item if not found
        if (!$found) {
            $cart[] = array(
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => 1
            );
        }
    } else {
        // Handle error: item not found in database
        echo "Item not found.";
    }
}

function removeFromCart($item_id) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $item_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
    }
}

function updateQuantity($item_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $item_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}

function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        foreach ($_SESSION['cart'] as $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>Price: $" . number_format($item['price'], 2, '.', '') . "</td>";
            echo "<td>Quantity: " . $item['quantity'] . "</td>";
            echo "<td><a href='cart.php?action=remove&id=" . $item['id'] . "'>Remove</a></td>";
            echo "<td>
                <form method='post' action=''>
                    <input type='hidden' name='id' value='" . $item['id'] . "'>
                    <input type='number' name='quantity' min='1' value='" . $item['quantity'] . "' style='width:50px;'>
                    <input type='submit' name='update_qty' value='Update'>
                </form>
              </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Your cart is empty.";
    }
}

function clearCart() {
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <!-- Include your CSS here -->
    <style>
        /* Add some basic styling */
        body { font-family: Arial, sans-serif; }
        .cart-item { padding: 10px; border-bottom: 1px solid #ddd; }
        .total-price { font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <?php
    // Handle actions based on form submissions or GET requests
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'add':
                if (isset($_POST['id'], $_POST['name'], $_POST['price'])) {
                    addToCart($_POST['id'], $_POST['name'], $_POST['price']);
                }
                break;
                
            case 'remove':
                if (isset($_GET['id'])) {
                    removeFromCart($_GET['id']);
                }
                break;
        }
    }

    // Update quantity handling
    if (isset($_POST['update_qty'])) {
        if (isset($_POST['id'], $_POST['quantity'])) {
            updateQuantity($_POST['id'], $_POST['quantity']);
        }
    }

    // Clear cart button
    if (isset($_GET['clear'])) {
        clearCart();
    }
    ?>

    <h2>Your Shopping Cart</h2>
    
    <!-- Add items to the cart -->
    <form method="post" action="?action=add">
        Item ID: <input type="number" name="id" required><br>
        Name: <input type="text" name="name" required><br>
        Price: $<input type="number" step="0.01" name="price" required><br>
        <input type="submit" value="Add to Cart">
    </form>

    <!-- Display current cart -->
    <?php displayCart(); ?>

    <!-- Clear cart button -->
    <a href="?clear">Clear Cart</a>
</body>
</html>


<?php
// Start the session
session_start();

// Check if session is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Adding items to cart
function addToCart($productId, $quantity) {
    // Get product details from database (replace with your actual query)
    $product = getProductDetails($productId);
    
    if ($product) {
        $item = array(
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        );
        
        // Check if product already exists in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $item;
        }
    }
}

// Removing items from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Updating item quantity in cart
function updateCartItem($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to get product details from database
function getProductDetails($productId) {
    // Replace with your actual database query
    $product = array(
        'id' => $productId,
        'name' => 'Product Name',
        'price' => 100.00
    );
    
    return $product;
}

// Displaying cart contents
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty!";
    } else {
        foreach ($_SESSION['cart'] as $item) {
            echo "Product: " . $item['name'] . "<br>";
            echo "Price: $" . number_format($item['price'], 2) . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "Total: $" . number_format($item['price'] * $item['quantity'], 2) . "<br><br>";
        }
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                addToCart($_POST['product_id'], $_POST['quantity']);
                break;
            case 'remove':
                removeFromCart($_POST['product_id']);
                break;
            case 'update':
                updateCartItem($_POST['product_id'], $_POST['quantity']);
                break;
        }
    }
}

// Display cart
displayCart();

// Don't forget to close the session
session_write_close();
?>


<?php
// Initialize session
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($productId, $quantity) {
    global $/cart;
    
    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$productId])) {
        // If exists, increment quantity
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        // If not exists, add new item
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    global $cart;
    
    // Check if product exists in cart
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Reset session array after removal
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to get total items in cart
function getTotalItems() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item;
    }
    return $total;
}

// Function to calculate total amount
function getTotalAmount($productId, $quantity) {
    $total = 0;
    foreach ($_SESSION['cart'] as $product => $qty) {
        // Assuming product ID is the same as price for simplicity
        $total += $product * $qty;
    }
    return $total;
}

// Example usage:

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($productId, $quantity);
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    removeFromCart($productId);
}

// Display cart contents
echo "Your Cart:<br>";
foreach ($_SESSION['cart'] as $product => $qty) {
    echo "Product: $product, Quantity: $qty<br>";
}
echo "Total Items: " . getTotalItems() . "<br>";
echo "Total Amount: $" . getTotalAmount();

// Links to add/remove items
echo "<a href='add_item.php'>Add Item</a><br>";
echo "<a href='remove_item.php?remove=$productId'>Remove Item</a>";
?>


<?php
// Start the session
session_start();

// Function to add item to cart
function addToCart($productId, $name, $price) {
    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if product already exists in cart
    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['productId'] == $productId) {
            $productExists = true;
            break;
        }
    }
    
    if (!$productExists) {
        // Add new product to cart
        $_SESSION['cart'][] = array(
            'productId' => $productId,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    } else {
        echo "Product already exists in cart.";
    }
}

// Function to update item quantity
function updateCart($productId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['productId'] == $productId) {
            $item['quantity'] = $newQuantity;
            break;
        }
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    $index = 0;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['productId'] == $productId) {
            unset($_SESSION['cart'][$key]);
            // Re-index the array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
}

// Function to display cart contents
function displayCart() {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>₹" . number_format($item['price'], 2) . "</td>";
            echo "<td><input type='number' value='" . $item['quantity'] . "' onChange='updateQuantity(" . $item['productId'] . ", this.value)'></td>";
            echo "<td>₹" . number_format($subtotal, 2) . "</td>";
            echo "<td><button onClick='removeFromCart(" . $item['productId'] . ")'>Remove</button></td>";
            echo "</tr>";
        }
        echo "<tr><td colspan='3'><strong>Total:</strong></td><td>₹" . number_format($total, 2) . "</td><td></td></tr>";
        echo "</table>";
    } else {
        echo "Your cart is empty!";
    }
}

// Example usage:
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            $productId = $_GET['productId'];
            $name = $_GET['name'];
            $price = $_GET['price'];
            addToCart($productId, $name, $price);
            break;
            
        case 'update':
            $productId = $_GET['productId'];
            $quantity = $_GET['quantity'];
            updateCart($productId, $quantity);
            break;
            
        case 'remove':
            $productId = $_GET['productId'];
            removeFromCart($productId);
            break;
    }
}

// Display cart
displayCart();
?>


   <?php
   session_start();
   

2. **Initialize the Cart:**
   Check if the cart exists in the session; initialize it as an empty array if not.

   
   if (!isset($_SESSION['cart'])) {
       $_SESSION['cart'] = array();
   }
   

3. **Add Items to the Cart:**
   When a user adds a product, retrieve its details from the database and add it to the cart or update its quantity.

   
   $productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

   if ($productId > 0) {
       // Connect to your database and fetch product details
       $result = mysqli_query($conn, "SELECT name, price FROM products WHERE id = $productId");
       if ($row = mysqli_fetch_assoc($result)) {
           if (isset($_SESSION['cart'][$productId])) {
               $_SESSION['cart'][$productId]['quantity']++;
           } else {
               $_SESSION['cart'][$productId] = array(
                   'name' => $row['name'],
                   'price' => $row['price'],
                   'quantity' => 1
               );
           }
       }
   }
   

4. **Display Cart Items:**
   Loop through the cart items and display each product's details along with the total.

   
   if (!empty($_SESSION['cart'])) {
       echo "<h2>Your Shopping Cart</h2>";
       $total = 0;
       foreach ($_SESSION['cart'] as $id => $item) {
           $subtotal = $item['price'] * $item['quantity'];
           $total += $subtotal;
           echo "<div>Product: {$item['name']} | Quantity: {$item['quantity']} | Price: \${$item['price']}</div>";
       }
       echo "<h3>Total: \${$total}</h3>";
   } else {
       echo "Your cart is empty.";
   }
   

5. **Remove Items from the Cart:**
   Provide a way to remove items by including links or buttons that trigger removal scripts.

6. **Checkout Process:**
   When checking out, process the order and reset the cart.

   
   if (isset($_POST['checkout'])) {
       // Process checkout here...
       unset($_SESSION['cart']);
   }
   

**Example Implementation:**


