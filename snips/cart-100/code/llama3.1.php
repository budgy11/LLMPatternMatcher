
<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'total' => 0,
);


function add_item($item_id, $name, $price) {
    global $cart;
    $existing_item = false;

    // Check if item already exists in cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            $existing_item = true;
            break;
        }
    }

    // Add new item or update existing one
    if (!$existing_item) {
        $cart['items'][] = array(
            'id' => $item_id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1,
        );
    } else {
        // Update quantity of existing item
        foreach ($cart['items'] as &$item) {
            if ($item['id'] == $item_id) {
                $item['quantity']++;
                break;
            }
        }
    }

    // Update total value
    $cart['total'] = array_sum(array_column($cart['items'], 'price')) * count($cart['items']);
}


function remove_item($item_id) {
    global $cart;
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            unset($item);
            break;
        }
    }

    // Update total value
    $cart['total'] = array_sum(array_column($cart['items'], 'price')) * count($cart['items']);
}


function display_cart() {
    global $cart;
    echo '<h2>Cart Contents:</h2>';
    foreach ($cart['items'] as $item) {
        echo sprintf('<p>ID: %d, Name: %s, Price: %.2f, Quantity: %d</p>', $item['id'], $item['name'], $item['price'], $item['quantity']);
    }
    echo '<p>Total: ' . $cart['total'] . '</p>';
}


// Initialize session and cart array
session_start();
$cart = array(
    'items' => array(),
    'total' => 0,
);

// Add items to cart
add_item(1, 'Item 1', 9.99);
add_item(2, 'Item 2', 19.99);
add_item(3, 'Item 3', 29.99);

// Display cart contents
display_cart();

// Remove item from cart
remove_item(2);

// Display updated cart contents
display_cart();


<?php
session_start();
?>


// File: cart.php

// Check if cart array is set in session. If not, create it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($item_name, $item_quantity) {
    global $_SESSION;
    
    // Check if item already exists in the cart and update quantity if so
    foreach ($_SESSION['cart'] as &$item) {
        if ($item[0] == $item_name) {
            $item[1] += $item_quantity; // Increase quantity of existing item
            return; // Exit function after updating quantity
        }
    }

    // If the item does not exist in cart, add it with specified quantity
    $_SESSION['cart'][] = array($item_name, $item_quantity);
}

// Example usage: Adding an item to the cart
add_to_cart('Product A', 2);

// Display contents of the cart for demonstration purposes
foreach ($_SESSION['cart'] as $item) {
    echo "Item Name: $item[0], Quantity: $item[1]<br>";
}


// Function to update quantity of an item in the cart
function update_cart_quantity($item_name, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item[0] == $item_name) {
            $item[1] = $new_quantity; // Update quantity of existing item
            return; // Exit function after updating quantity
        }
    }
}

// Function to remove an item from the cart
function remove_from_cart($item_name) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item[0] == $item_name) {
            unset($_SESSION['cart'][$key]); // Remove item from cart
            return; // Exit function after removing item
        }
    }
}


// Function to clear the cart completely
function clear_cart() {
    global $_SESSION;
    
    unset($_SESSION['cart']); // Remove cart array from session
}


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }

    // Add new item to cart if it doesn't exist
    if (!isset($item)) {
        $_SESSION['cart'][] = array(
            'id' => $product_id,
            'name' => 'Product Name', // Replace with actual product name
            'price' => 19.99, // Replace with actual price
            'quantity' => $quantity
        );
    }
}
?>


<?php
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Update product quantity in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}
?>


<?php
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];

    // Remove product from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Re-index the array after removing item
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
?>


<?php
// Display cart contents
echo '<h2>Cart Contents:</h2>';
foreach ($_SESSION['cart'] as $item) {
    echo '<p>Product: ' . $item['name'] . ' (' . $item['quantity'] . ' x $' . $item['price'] . ')</p>';
}

// Display total cost
echo '<p>Total Cost: $' . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) . '</p>';

// Form to add item to cart
?>
<form action="" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <label>Quantity:</label>
    <input type="number" name="quantity"><br><br>
    <input type="submit" value="Add to Cart">
</form>

// Form to update cart quantity
?>
<form action="" method="post">
    <input type="hidden" name="update_cart" value="1">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <label>New Quantity:</label>
    <input type="number" name="quantity"><br><br>
    <input type="submit" value="Update Cart">
</form>

// Form to remove item from cart
?>
<form action="" method="post">
    <input type="hidden" name="remove_item" value="1">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <input type="submit" value="Remove Item">
</form>


// Initialize the session
session_start();

// Function to add item to cart
function addToCart($itemId) {
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['id'] == $itemId) {
            // Item already exists, increment quantity
            $_SESSION['cart'][$key]['quantity']++;
            return;
        }
    }

    // Item doesn't exist, add it to cart
    $_SESSION['cart'][] = array(
        'id' => $itemId,
        'name' => '',  // Add item name here
        'price' => 0.00,  // Add item price here
        'quantity' => 1
    );
}

// Function to display cart contents
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<li>ID: {$item['id']} | Quantity: {$item['quantity']} | Price: {$item['price']}</li>";
    }

    echo "</ul>";
}

// Example usage
$itemId = 123; // Replace with actual item ID
addToCart($itemId);
displayCart();


// init_cart_session.php
<?php
session_start();

// Check if cart session exists, create it if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// add_to_cart.php
<?php
session_start();

// Get the product ID and quantity from the request
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if product already exists in cart
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
        // Increment quantity
        $item['quantity'] += $quantity;
        break;
    }
}

// Add product to cart if not found
if (!isset($item)) {
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


// view_cart.php
<?php
session_start();

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    // Display cart contents
    foreach ($_SESSION['cart'] as $item) {
        echo "<p>Product ID: {$item['id']} | Quantity: {$item['quantity']}</p>";
    }
}


// init_cart_session.php
<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// add_to_cart.php
<?php
session_start();

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
        $item['quantity'] += $quantity;
        break;
    }
}

if (!isset($item)) {
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// view_cart.php
<?php
session_start();

if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $item) {
        echo "<p>Product ID: {$item['id']} | Quantity: {$item['quantity']}</p>";
    }
}


// Start the session
session_start();


// Initialize the cart array
$_SESSION['cart'] = array();


function add_item_to_cart($item_id, $quantity) {
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it's already in the cart, increment the quantity
            $item['quantity'] += $quantity;
            return true; // Item was already in the cart
        }
    }

    // Add new item to the cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'name' => '', // Assuming we have a function to get item name by ID
        'price' => 0.00, // Assuming we have a function to get item price by ID
        'quantity' => $quantity
    );

    return true; // Item added successfully
}


function update_quantity($item_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated successfully
        }
    }

    return false; // Item not found in the cart
}


function remove_item($item_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return true; // Item removed successfully
        }
    }

    return false; // Item not found in the cart
}


// Start the session
session_start();

// Initialize the cart array
$_SESSION['cart'] = array();

// Add items to the cart
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);

// Update quantity
update_quantity(1, 4);

// Remove item from cart
remove_item(2);

// Display the cart contents
print_r($_SESSION['cart']);


<?php
// Start the session
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get product ID and quantity from form submission (e.g. from a button click)
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Add product to cart if it doesn't exist, or increment its quantity
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Display cart contents
echo '<h2>Cart Contents:</h2>';
echo '<table border="1">';
foreach ($_SESSION['cart'] as $product_id => $details) {
    echo '<tr>';
    echo '<td>' . $product_id . '</td>';
    echo '<td>' . $details['quantity'] . '</td>';
    echo '</tr>';
}
echo '</table>';

// Display total cost
$total_cost = 0;
foreach ($_SESSION['cart'] as $product_id => $details) {
    // Assuming prices are stored in an array (e.g. `$prices[$product_id]`)
    $price = $prices[$product_id];
    $total_cost += $price * $details['quantity'];
}
echo '<p>Total cost: $" . number_format($total_cost, 2) . "</p>";


<?php
// Start the session
session_start();

// Get product ID and quantity from form submission (e.g. from a button click)
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Add product to cart if it doesn't exist, or increment its quantity
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Redirect back to cart page (assuming it's stored in `$_SERVER['HTTP_REFERER']`)
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();


session_start();


// Assuming you have products stored somewhere and $product_id and $quantity are variables holding the selected product and quantity.
$_SESSION['cart']['products'][] = array('id' => $product_id, 'quantity' => $quantity);


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array('products' => array());
}
// Then add a product...
$_SESSION['cart']['products'][] = array('id' => $product_id, 'quantity' => $quantity);


$cart = $_SESSION['cart'];
if (isset($cart)) {
    foreach ($cart['products'] as $item) {
        // Process each item in the cart...
        echo "Product ID: " . $item['id'] . ", Quantity: " . $item['quantity'];
    }
}


$_SESSION['cart']['products'] = array();
// Or if you want to completely remove the session:
session_destroy();


<?php
// Check if the user is logged in (optional)
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array(
            'product_name' => get_product_name($product_id),
            'price' => get_product_price($product_id),
            'quantity' => $quantity
        );
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of an item in the cart
function update_quantity_in_cart($product_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get total cost of items in cart
function get_total_cost() {
    global $_SESSION;
    $total_cost = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_cost += $item['price'] * $item['quantity'];
    }
    return $total_cost;
}

// Function to display the contents of the cart
function display_cart() {
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    echo '<table>';
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo '<tr><td>' . $item['product_name'] . '</td><td>$' . number_format($item['price'], 2) . '</td><td>' . $item['quantity'] . '</td></tr>';
    }
    echo '</table>';
}

// Add item to cart (example)
add_item_to_cart(1, 2);

// Display the contents of the cart
display_cart();

// Get total cost of items in cart
echo 'Total Cost: $' . number_format(get_total_cost(), 2);
?>


<?php
// Example login form
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Authenticate user
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'password') {
        session_start();
        $_SESSION['username'] = $_POST['username'];
    }
}
?>


<?php
session_start();
?>


$cart = [
    'items' => [], // list of items in the cart
    'total' => 0, // total cost of the items in the cart
];


function addItemToCart($product_id, $quantity) {
    global $cart;

    // Check if product exists already in cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $new_item = [
        'id' => $product_id,
        'name' => $productName, // assume $productName is available
        'price' => $productPrice, // assume $productPrice is available
        'quantity' => $quantity,
    ];
    $cart['items'][] = $new_item;
    $cart['total'] += ($productPrice * $quantity);

    // Save cart data to session
    $_SESSION['cart'] = $cart;
}


function showCart() {
    global $cart;

    if (empty($cart['items'])) {
        echo "Your cart is empty.";
        return;
    }

    echo "<h2>My Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

    foreach ($cart['items'] as $item) {
        echo "<tr>";
        echo "<td>$item[name]</td>";
        echo "<td>\$" . number_format($item['price'], 2) . "</td>";
        echo "<td>$item[quantity]</td>";
        echo "<td>\$" . number_format($item['price'] * $item['quantity'], 2) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "Total: \$$cart[total]";
}


<?php require_once 'cart.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
</head>
<body>

<h1>Welcome to my store!</h1>

<form action="" method="post">
    <input type="hidden" name="product_id" value="1">
    <input type="number" name="quantity" min="1" max="10">
    <button type="submit">Add to Cart</button>
</form>

<?php showCart(); ?>


// Initialize the cart session
session_start();


// Create the cart array
$_SESSION['cart'] = array();


// Function to add an item to the cart
function add_item($product_id, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it's already in the cart, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If it's not in the cart, add a new item
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity
    );
}


// Function to display the cart
function display_cart() {
    // Check if the cart is empty
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        return;
    }

    // Display each item in the cart
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['product_id']} - Quantity: {$item['quantity']}<br>";
    }
}


// Initialize the cart session
session_start();

// Add some items to the cart
add_item(1, 2); // Product ID 1, quantity 2
add_item(2, 3); // Product ID 2, quantity 3

// Display the cart
display_cart();


<?php
session_start();
?>


<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
$count = count($_SESSION['cart']);
?>


<?php
function add_product_to_cart($product_id) {
    global $count;
    if (in_array($product_id, $_SESSION['cart'])) {
        // Product is already in cart, increment quantity
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity']++;
                break;
            }
        }
    } else {
        // Add new product to cart
        $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
    }
    $count = count($_SESSION['cart']);
}
?>


<?php
add_product_to_cart(123); // Add product with ID 123 to cart
?>


<?php
function remove_product_from_cart($product_id) {
    global $count;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $count = count($_SESSION['cart']);
}
?>


<?php
remove_product_from_cart(123); // Remove product with ID 123 from cart
?>


<?php
echo 'Cart Contents:';
foreach ($_SESSION['cart'] as $item) {
    echo '<br/>' . $item['id'] . ' x' . $item['quantity'];
}
?>


<?php
// Product ID 123 is added to cart
add_product_to_cart(123);

// Product ID 456 is also added to cart
add_product_to_cart(456);

// Display cart contents
echo 'Cart Contents:';
foreach ($_SESSION['cart'] as $item) {
    echo '<br/>' . $item['id'] . ' x' . $item['quantity'];
}

// Remove product with ID 123 from cart
remove_product_from_cart(123);
?>


<?php
session_start();

// Check if cart is already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
// Get the product ID from the URL parameter
$product_id = $_GET['product_id'];

// Check if the product is already in the cart
if (in_array($product_id, $_SESSION['cart'])) {
    echo "Product already in cart.";
} else {
    // Add the product to the cart
    $_SESSION['cart'][] = $product_id;

    // Update the cart session
    $_SESSION['cart_count'] = count($_SESSION['cart']);

    echo "Product added to cart successfully!";
}
?>


<?php
// Get the product ID from the URL parameter
$product_id = $_GET['product_id'];

// Check if the product is in the cart
if (in_array($product_id, $_SESSION['cart'])) {
    // Remove the product from the cart
    unset($_SESSION['cart'][array_search($product_id, $_SESSION['cart'])]);

    // Update the cart session
    $_SESSION['cart_count'] = count($_SESSION['cart']);

    echo "Product removed from cart successfully!";
} else {
    echo "Product not found in cart.";
}
?>


<?php
// Get the cart contents
$cart_contents = $_SESSION['cart'];

// Display the cart contents
echo "Cart Contents:</br>";
foreach ($cart_contents as $product_id) {
    echo "$product_id</br>";
}
?>


<?php require_once 'cart.php'; ?>


<?php
// Start the session
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Add an item to the cart
function add_to_cart($product_id) {
    global $_SESSION;
    $product_id = (int)$product_id; // Cast to integer to prevent SQL injection
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => 1);
    } else {
        $_SESSION['cart'][$product_id]['quantity']++;
    }
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    $product_id = (int)$product_id; // Cast to integer to prevent SQL injection
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of an item in the cart
function update_cart($product_id, $new_quantity) {
    global $_SESSION;
    $product_id = (int)$product_id; // Cast to integer to prevent SQL injection
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = (int)$new_quantity;
    }
}

// Display the cart contents
function display_cart() {
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $product_id => $item) {
        // Fetch product details from database (not shown here)
        $product_name = 'Product Name'; // Replace with actual product name
        $price = 19.99; // Replace with actual price
        echo '<p>Product ID: ' . $product_id . ', Quantity: ' . $item['quantity'] . ', Price: £' . number_format($price, 2) . '</p>';
    }
}

// Example usage:
add_to_cart(1);
add_to_cart(2);
update_cart(1, 3);
remove_from_cart(2);

display_cart();
?>


<?php

// Start session
session_start();

// Set default values for the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add a product to the cart
function addToCart($productId, $quantity) {
    global $_SESSION;
    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // Increment quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new product to the cart
    $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
}

// Function to update a product in the cart
function updateCart($productId, $newQuantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // Update quantity of existing item
            $item['quantity'] = $newQuantity;
            return;
        }
    }
}

// Function to remove a product from the cart
function removeFromCart($productId) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

?>


<?php

// Include cart logic
include 'cart.php';

// Connect to database (example using PDO)
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';
try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Retrieve product list
$query = "SELECT * FROM products";
$products = $pdo->query($query)->fetchAll();

?>

<!-- Display product list -->
<h1>Product List</h1>
<ul>
    <?php foreach ($products as $product) { ?>
        <li>
            <?= $product['name'] ?> (<?= $product['price'] ?>)
            <button class="add-to-cart" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
        </li>
    <?php } ?>
</ul>

<!-- Display cart -->
<h1>Cart</h1>
<ul>
    <?php foreach ($_SESSION['cart'] as $item) { ?>
        <li>
            <?= $item['name'] ?> (<?= $item['quantity'] ?> x <?= $item['price'] ?>)
            <button class="update-cart" data-product-id="<?= $item['id'] ?>">Update</button>
            <button class="remove-from-cart" data-product-id="<?= $item['id'] ?>">Remove</button>
        </li>
    <?php } ?>
</ul>

<!-- JavaScript to handle cart updates -->
<script>
    // Add event listeners for buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.dataset.productId;
            addToCart(productId, 1);
            window.location.reload();
        });
    });

    document.querySelectorAll('.update-cart').forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.dataset.productId;
            const newQuantity = prompt('Enter new quantity:', '');
            updateCart(productId, parseInt(newQuantity));
            window.location.reload();
        });
    });

    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.dataset.productId;
            removeFromCart(productId);
            window.location.reload();
        });
    });
</script>


<?php
session_start();
?>


$_SESSION['cart'] = array();


function addToCart($item_id, $quantity) {
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}


function updateCartQuantity($item_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}


function removeFromCart($item_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}


// Add an item to the cart
addToCart(1, 2); // Adds item with ID 1 and quantity 2

// Update the quantity of an existing item in the cart
updateCartQuantity(1, 3); // Updates the quantity of item with ID 1 to 3

// Remove an item from the cart
removeFromCart(1); // Removes item with ID 1 from the cart


session_write_close();


<?php
session_start();

// Check if cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];

    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity if item is already in cart
            $item['quantity']++;
            break;
        }
    }

    // Add new item to cart if it's not already there
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => 1);
    }

    // Update cart session
    $_SESSION['cart'] = $_SESSION['cart'];
}

// Display cart contents
?>

<div class="shopping-cart">
    <h2>Shopping Cart</h2>

    <?php foreach ($_SESSION['cart'] as $item): ?>
        <div class="cart-item">
            <span><?php echo $item['name']; ?></span>
            <span>Price: <?php echo $item['price']; ?></span>
            <span>Quantity: <?php echo $item['quantity']; ?></span>
        </div>
    <?php endforeach; ?>

    <!-- Cart summary and total -->
    <p>Total: <?php echo array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $_SESSION['cart'])); ?> </p>

    <!-- Empty cart message -->
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Cart is empty!</p>
    <?php endif; ?>

    <!-- Remove item from cart form -->
    <form action="" method="post">
        <input type="hidden" name="remove_item" value="1">
        <button type="submit">Empty Cart</button>
    </form>
</div>

<!-- Add to cart form -->
<form action="" method="get">
    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="add_to_cart"><br><br>
    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name"><br><br>
    <label for="price">Price:</label>
    <input type="number" id="price" name="price"><br><br>

    <button type="submit">Add to Cart</button>
</form>


<?php

function update_cart_session() {
    // Update cart session after adding or removing items
}

?>


<?php
// Initialize the session
session_start();

// Function to add item to cart
function addItemToCart($itemId, $itemName, $itemPrice) {
  // Check if the item is already in the cart
  foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['id'] == $itemId) {
      // If it's already in the cart, increment its quantity
      $_SESSION['cart'][$key]['quantity']++;
      return;
    }
  }

  // If it's not in the cart, add a new item to the array
  $newItem = array(
    'id' => $itemId,
    'name' => $itemName,
    'price' => $itemPrice,
    'quantity' => 1
  );
  $_SESSION['cart'][] = $newItem;
}

// Function to remove item from cart
function removeItemFromCart($itemId) {
  // Check if the item is in the cart
  foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['id'] == $itemId) {
      // If it's in the cart, delete it from the array
      unset($_SESSION['cart'][$key]);
      return;
    }
  }
}

// Function to update item quantity in cart
function updateQuantity($itemId, $newQuantity) {
  // Check if the item is in the cart
  foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['id'] == $itemId) {
      // If it's in the cart, update its quantity
      $_SESSION['cart'][$key]['quantity'] = $newQuantity;
      return;
    }
  }
}

// Function to display cart contents
function displayCart() {
  echo "Cart Contents:
";
  foreach ($_SESSION['cart'] as $item) {
    echo "$item[name] x $item[quantity] = $" . number_format($item['price'] * $item['quantity'], 2) . "
";
  }
}

// Example usage
if (isset($_GET['action'])) {
  switch ($_GET['action']) {
    case 'add':
      $itemId = $_GET['id'];
      $itemName = $_GET['name'];
      $itemPrice = $_GET['price'];
      addItemToCart($itemId, $itemName, $itemPrice);
      break;
    case 'remove':
      $itemId = $_GET['id'];
      removeItemFromCart($itemId);
      break;
    case 'update_quantity':
      $itemId = $_GET['id'];
      $newQuantity = $_GET['quantity'];
      updateQuantity($itemId, $newQuantity);
      break;
  }
}

// Display cart contents
displayCart();
?>


<?php
session_start();
?>


function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Ensure the product ID is an integer for easy comparison
    $product_id = (int)$product_id;
    
    // Check if the product already exists in cart to avoid duplicates and update quantities instead
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;  // Increment quantity
            return;  // Exit function early since we've updated an existing item.
        }
    }
    
    // If the product isn't in cart, add it with its ID and quantity.
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


function update_cart_item($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == (int)$product_id) {
            $item['quantity'] = $new_quantity;
            return;  // Exit early.
        }
    }
    
    echo "Product not found in cart.";
}


function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['id'] == (int)$product_id) {
                unset($_SESSION['cart'][$key]);
                return;  // Exit early.
            }
        }
        
        // If the product isn't found, just continue execution without an error.
    }
}


function show_cart() {
    if (isset($_SESSION['cart'])) {
        echo "Your Cart:
";
        foreach ($_SESSION['cart'] as $item) {
            echo "$item[id] x $item[quantity]
";
        }
    } else {
        echo "Cart is empty.";
    }
}


add_to_cart(1, 2); // Add product with ID 1 in quantity of 2.
add_to_cart(2);    // Add product with ID 2 in default quantity of 1.

show_cart(); // Display the current state of your cart.

update_cart_item(1, 3); // Update quantity of item with ID 1 to 3.

remove_from_cart(2);

show_cart();


class Cart {
  private $cart;

  public function __construct() {
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }
    $this->cart = &$_SESSION['cart'];
  }

  public function add_item($product_id, $quantity) {
    if (array_key_exists($product_id, $this->cart)) {
      $this->cart[$product_id] += $quantity;
    } else {
      $this->cart[$product_id] = $quantity;
    }
  }

  public function remove_item($product_id) {
    if (array_key_exists($product_id, $this->cart)) {
      unset($this->cart[$product_id]);
    }
  }

  public function update_quantity($product_id, $new_quantity) {
    if (array_key_exists($product_id, $this->cart)) {
      $this->cart[$product_id] = $new_quantity;
    }
  }

  public function get_cart() {
    return $this->cart;
  }

  public function calculate_total() {
    $total = 0;
    foreach ($this->cart as $item) {
      $total += $item * // assuming product prices are stored in a database or array
    }
    return $total;
  }
}


// Initialize the cart session
$cart = new Cart();

// Add items to the cart
$cart->add_item(1, 2); // add 2 items with product ID 1
$cart->add_item(3, 1); // add 1 item with product ID 3

// Update quantity of an item
$cart->update_quantity(1, 3); // update quantity of item with product ID 1 to 3

// Remove an item from the cart
$cart->remove_item(3);

// Get the current cart contents
print_r($cart->get_cart());

// Calculate the total cost of items in the cart
echo $cart->calculate_total();


session_start();


$cart = array(
    'products' => array()
);


function add_to_cart($product_id, $product_name, $price) {
    global $cart;
    $item = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => 1 // Default quantity is 1
    );
    $cart['products'][] = $item;
}


function update_cart_quantity($product_id, $new_quantity) {
    global $cart;
    foreach ($cart['products'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}


function remove_from_cart($product_id) {
    global $cart;
    foreach ($cart['products'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart['products'][$key]);
            break;
        }
    }
}


function display_cart() {
    global $cart;
    echo "Cart Contents:
";
    foreach ($cart['products'] as $item) {
        echo "$item[name] x $item[quantity] = $" . number_format($item['price'] * $item['quantity']) . "
";
    }
}


// Start session
session_start();

// Add products to cart
add_to_cart(1, "Product 1", 9.99);
add_to_cart(2, "Product 2", 19.99);

// Update cart quantity
update_cart_quantity(1, 3);

// Remove product from cart
remove_from_cart(2);

// Display cart
display_cart();


<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
);


function add_item_to_cart($item_id, $item_name, $price, $quantity) {
    global $cart;
    if (!isset($cart['items'][$item_id])) {
        $cart['items'][$item_id] = array(
            'name' => $item_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
    update_cart_subtotal();
}


function update_cart_subtotal() {
    global $cart;
    $subtotal = 0;
    foreach ($cart['items'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $cart['subtotal'] = $subtotal;
}


function update_cart_total() {
    global $cart;
    $tax_rate = 0.1; // 10% tax rate
    $cart['total'] = $cart['subtotal'] + ($cart['subtotal'] * $tax_rate);
}


function display_cart() {
    global $cart;
    echo '<h2>Cart:</h2>';
    echo '<ul>';
    foreach ($cart['items'] as $item) {
        echo '<li>' . $item['name'] . ' x ' . $item['quantity'] . ' = $' . number_format($item['price'] * $item['quantity'], 2) . '</li>';
    }
    echo '<li>Subtotal: $' . number_format($cart['subtotal'], 2) . '</li>';
    echo '<li>Tax (10%): $' . number_format(($cart['subtotal'] * 0.1), 2) . '</li>';
    echo '<li>Total: $' . number_format($cart['total'], 2) . '</li>';
    echo '</ul>';
}


<?php
session_start();

$cart = array(
    'items' => array(),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
);

add_item_to_cart(1, 'Apple', 1.99, 2);
add_item_to_cart(2, 'Banana', 0.99, 3);
update_cart_subtotal();
update_cart_total();

display_cart();
?>


<?php
session_start();

// If no cart exists yet, create one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example function to add an item to the cart
function addToCart($id) {
    if (isset($_SESSION['cart'][$id])) {
        // Increment quantity of existing item
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        // Add new item with default quantity 1
        $_SESSION['cart'][$id] = array('name' => 'Item Name', // You would replace this with the actual item name
                                      'price' => '9.99',   // You would replace this with the actual price
                                      'quantity' => 1);
    }
}

// Example function to display cart contents
function showCart() {
    global $cart;
    echo '<h2>Cart Contents:</h2>';
    if (count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $item) {
            echo 'Item: ' . $item['name'] . ', Price: $' . number_format($item['price'], 2) . ', Quantity: ' . $item['quantity'] . '<br>';
        }
    } else {
        echo 'Your cart is empty.';
    }
}

// Add an example item to the cart
addToCart('ITEM-001');

// Display cart contents
showCart();

// To remove items from the cart
function removeFromCart($id) {
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}
?>


session_start();


// Define an empty cart if it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(
        'items' => array(),
        'quantities' => array()
    );
}

function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if item is already in cart
    foreach ($_SESSION['cart']['items'] as $key => $value) {
        if ($value == $item_id) {
            // If it's already there, increase quantity and mark for update
            $_SESSION['cart']['quantities'][$key] += $quantity;
            return true; // Item is in cart now
        }
    }
    
    // If not found, add the item to the cart with specified quantity
    array_push($_SESSION['cart']['items'], $item_id);
    $_SESSION['cart']['quantities'][] = $quantity;
    return false; // New item added to cart
}

function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    if (!in_array($item_id, $_SESSION['cart']['items'])) {
        return; // Item not found in cart
    }
    
    $key = array_search($item_id, $_SESSION['cart']['items']);
    unset($_SESSION['cart']['quantities'][$key]);
    unset($_SESSION['cart']['items'][$key]);
}

function update_item_quantity($item_id, $new_quantity) {
    global $_SESSION;
    $index = array_search($item_id, $_SESSION['cart']['items']);
    if ($index !== false && !empty($_SESSION['cart']['quantities'][$index])) {
        $_SESSION['cart']['quantities'][$index] = $new_quantity;
    }
}


// To test - add an item to the cart
add_item_to_cart(1, 2); // Add one item with ID=1 and quantity of 2

// Display what's in the cart
echo "Items in Cart:
";
foreach ($_SESSION['cart']['items'] as $key => $value) {
    echo 'Item: ' . $value . ', Quantity: ' . $_SESSION['cart']['quantities'][$key] . "
";
}

// Example of removing an item from the cart
remove_item_from_cart(1);

// Display updated cart contents
echo "Updated Items in Cart:
";
foreach ($_SESSION['cart']['items'] as $key => $value) {
    echo 'Item: ' . $value . ', Quantity: ' . $_SESSION['cart']['quantities'][$key] . "
";
}


session_start();


$_SESSION['cart'] = array(); // Initialize an empty array to store cart items
$_SESSION['cart_total'] = 0; // Initialize a variable to store the total cost of items in the cart


function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update item's quantity
            $item['quantity'] += $quantity;
            break;
        }
    }
    
    // If product not found, add it to the cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('id' => $product_id, 'name' => '', 'price' => 0, 'quantity' => $quantity);
    }
    
    // Update total cost
    $_SESSION['cart_total'] += $product_id * $quantity;
}


function remove_item_from_cart($product_id) {
    global $_SESSION;
    
    unset($_SESSION['cart'][$product_id]);
}


function display_cart_contents() {
    global $_SESSION;
    
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: $item[id] - Name: $item[name] - Price: \$" . number_format($item['price'], 2) . " x $item[quantity] = $" . number_format($item['price'] * $item['quantity'], 2);
    }
}


// Add items to cart
add_item_to_cart(1, 2); // Product ID 1 with quantity 2
add_item_to_cart(2, 3); // Product ID 2 with quantity 3

// Display cart contents
display_cart_contents();

// Remove item from cart
remove_item_from_cart(1);

// Display updated cart contents
display_cart_contents();


<?php
if (!isset($_SESSION)) {
    session_start();
}
?>


<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    if (!empty($item_id) && !empty($quantity)) {
        // Check if the item is already in the cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $item_id) {
                // If it's already in the cart, increment the quantity
                $item['quantity'] += $quantity;
                break;
            }
        }

        // If the item is not in the cart, add it
        if (!isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] = array('id' => $item_id, 'quantity' => $quantity);
        }
    }
}
?>


<?php
if (isset($_POST['remove_from_cart'])) {
    $item_id = $_POST['item_id'];

    if (!empty($item_id)) {
        unset($_SESSION['cart'][$item_id]);
    }
}
?>


<?php
echo '<h2>Cart:</h2>';
foreach ($_SESSION['cart'] as $item) {
    echo 'Item ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
}
?>


<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart form
echo '<form action="" method="post">';
echo '<input type="hidden" name="add_to_cart" value="1">';
echo 'Item ID: <input type="text" name="item_id"><br>';
echo 'Quantity: <input type="number" name="quantity"><br>';
echo '<button type="submit">Add to Cart</button>';
echo '</form>';

// Remove item from cart form
echo '<form action="" method="post">';
echo '<input type="hidden" name="remove_from_cart" value="1">';
echo 'Item ID: <input type="text" name="item_id"><br>';
echo '<button type="submit">Remove from Cart</button>';
echo '</form>';

if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    if (!empty($item_id) && !empty($quantity)) {
        // Check if the item is already in the cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $item_id) {
                // If it's already in the cart, increment the quantity
                $item['quantity'] += $quantity;
                break;
            }
        }

        // If the item is not in the cart, add it
        if (!isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] = array('id' => $item_id, 'quantity' => $quantity);
        }
    }
}

if (isset($_POST['remove_from_cart'])) {
    $item_id = $_POST['item_id'];

    if (!empty($item_id)) {
        unset($_SESSION['cart'][$item_id]);
    }
}

echo '<h2>Cart:</h2>';
foreach ($_SESSION['cart'] as $item) {
    echo 'Item ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
}
?>


session_start();


$cart = array();


// Add product 1 to the cart
$cart[] = array(
    'product_id' => 1,
    'name' => 'Product 1',
    'price' => 9.99,
    'quantity' => 2
);

// Add product 2 to the cart
$cart[] = array(
    'product_id' => 2,
    'name' => 'Product 2',
    'price' => 19.99,
    'quantity' => 1
);


$_SESSION['cart'] = $cart;


// Get the cart from session
$cart = $_SESSION['cart'];


session_start();

// Define the cart array
$cart = array();

// Add product 1 to the cart
$cart[] = array(
    'product_id' => 1,
    'name' => 'Product 1',
    'price' => 9.99,
    'quantity' => 2
);

// Add product 2 to the cart
$cart[] = array(
    'product_id' => 2,
    'name' => 'Product 2',
    'price' => 19.99,
    'quantity' => 1
);

// Save the cart to session
$_SESSION['cart'] = $cart;

// Get the cart from session
$cart = $_SESSION['cart'];

// Display the cart contents
echo "Cart Contents:
";
foreach ($cart as $item) {
    echo "Product: " . $item['name'] . ", Price: $" . $item['price'] . ", Quantity: " . $item['quantity'] . "
";
}


// Assuming this script is named 'cart.php'

<?php

session_start();

// Define constants for easier reference if needed
define('CART_SESSION_NAME', 'cart');

function get_cart() {
    global $cart;
    
    if (!isset($_SESSION[CART_SESSION_NAME])) {
        $_SESSION[CART_SESSION_NAME] = [];
    }
    
    return &$_SESSION[CART_SESSION_NAME];
}

// Function to add item to cart
function add_item($product_id, $price, $quantity) {
    global $cart;
    
    $cart = get_cart();
    
    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to cart
    array_push($cart, ['id' => $product_id, 'price' => $price, 'quantity' => $quantity]);
}

// Function to remove item from cart
function remove_item($item_id) {
    global $cart;
    
    $cart = get_cart();
    
    // Find the key of the product to be removed
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            unset($item);
            return true;
        }
    }
    
    // If not found, return false
    return false;
}

// Example usage:

// Adding an item
add_item(1, 10.99, 2);

// Displaying cart content
$cart = get_cart();
foreach ($cart as $item) {
    echo "Product ID: $item[id] - Price: $" . number_format($item['price'], 2) . " - Quantity: $item[quantity]<br>";
}

// Removing an item
remove_item(1);

// Displaying updated cart content
$cart = get_cart();
echo '<hr>';
foreach ($cart as $item) {
    echo "Product ID: $item[id] - Price: $" . number_format($item['price'], 2) . " - Quantity: $item[quantity]<br>";
}

?>


<?php
// Start session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, update quantity
            $item['quantity'] += $quantity;
            return; // Return early to avoid duplicate entry
        }
    }
    
    // Add product to cart if not already there
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Check if product is in cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // Return early to avoid duplicate entry
        }
    }
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Check if product is in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return; // Return early to avoid duplicate entry
        }
    }
}

// Function to get cart contents
function get_cart_contents() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}

// Example usage:
add_to_cart(1, 2); // Add product with ID 1 in quantity of 2
remove_from_cart(3); // Remove product with ID 3 from cart
update_quantity(1, 5); // Update quantity of product with ID 1 to 5

print_r(get_cart_contents()); // Output: Array ( [0] => Array ( [id] => 1 [quantity] => 5 ) )


<?php
session_start();
print_r(session_status());
?>


<?php

// Start the session
session_start();

// Check if the cart is already set in the session, and create a new one if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Get the current products in the cart
    $products = &$_SESSION['cart'];
    
    // Check if the product is already in the cart
    foreach ($products as &$item) {
        if ($item['id'] == $product_id) {
            // If it's already there, increment its quantity
            $item['quantity'] += $quantity;
            return; // Exit early
        }
    }
    
    // Add new product to the cart array
    $products[] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to update an item in the cart
function update_cart_item($product_id, $new_quantity) {
    global $_SESSION;
    
    // Get the current products in the cart
    $products = &$_SESSION['cart'];
    
    // Loop through each product to find and update the one we're interested in
    foreach ($products as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return; // Exit early
        }
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Get the current products in the cart
    $products = &$_SESSION['cart'];
    
    // Loop through each product and remove it if we find a match
    foreach ($products as &$item) {
        if ($item['id'] == $product_id) {
            unset($items[array_search($item, $items)]);
            return; // Exit early
        }
    }
}

// Example usage:
add_to_cart(12345, 2); // Add product with ID 12345 to the cart in quantity 2

update_cart_item(12345, 3); // Update the quantity of product 12345 to 3

remove_from_cart(67890); // Remove product 67890 from the cart

// Print out the contents of the cart
print_r($_SESSION['cart']);

?>


class Cart {
  private $sessionName;
  private $items;

  public function __construct($sessionName) {
    $this->sessionName = $sessionName;
    $this->items = array();
  }

  /**
   * Add an item to the cart.
   *
   * @param string $id Product ID
   * @param int $quantity Quantity of product
   */
  public function addItem($id, $quantity) {
    if (isset($this->items[$id])) {
      $this->items[$id]['quantity'] += $quantity;
    } else {
      $this->items[$id] = array('id' => $id, 'quantity' => $quantity);
    }
  }

  /**
   * Remove an item from the cart.
   *
   * @param string $id Product ID
   */
  public function removeItem($id) {
    if (isset($this->items[$id])) {
      unset($this->items[$id]);
    }
  }

  /**
   * Update the quantity of an item in the cart.
   *
   * @param string $id Product ID
   * @param int $quantity New quantity
   */
  public function updateItemQuantity($id, $quantity) {
    if (isset($this->items[$id])) {
      $this->items[$id]['quantity'] = $quantity;
    }
  }

  /**
   * Get the cart contents.
   *
   * @return array Cart items
   */
  public function getCartContents() {
    return $this->items;
  }

  /**
   * Save the cart contents to session.
   */
  public function saveToSession() {
    $_SESSION[$this->sessionName] = $this->items;
  }

  /**
   * Load the cart contents from session.
   *
   * @return array Cart items
   */
  public function loadFromSession() {
    if (isset($_SESSION[$this->sessionName])) {
      return $_SESSION[$this->sessionName];
    }
    return array();
  }
}


// Create a new cart instance with session name 'cart'
$cart = new Cart('cart');

// Add an item to the cart
$cart->addItem(1, 2);

// Remove an item from the cart
$cart->removeItem(1);

// Update the quantity of an item in the cart
$cart->updateItemQuantity(2, 3);

// Save the cart contents to session
$cart->saveToSession();

// Load the cart contents from session
$items = $cart->loadFromSession();
print_r($items);


// Configuration settings
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Establish database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add product to cart
function addToCart($productId, $quantity) {
    global $conn;

    // Check if product exists in database
    $query = "SELECT * FROM products WHERE id = '$productId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Retrieve user ID from session (assuming you have one set)
        $userId = $_SESSION['user_id'];

        // Check if product already exists in cart
        $query = "SELECT * FROM cart WHERE user_id = '$userId' AND product_id = '$productId'";
        $result2 = $conn->query($query);

        if ($result2->num_rows > 0) {
            // Update quantity if product is already in cart
            $row = $result2->fetch_assoc();
            $newQuantity = $row['quantity'] + $quantity;
            $updateQuery = "UPDATE cart SET quantity = '$newQuantity' WHERE id = '" . $row['id'] . "'";
            $conn->query($updateQuery);
        } else {
            // Add new product to cart
            $insertQuery = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$userId', '$productId', '$quantity')";
            $conn->query($insertQuery);
        }

        return true;
    } else {
        return false; // Product not found in database
    }
}

// Function to view cart contents
function viewCart() {
    global $conn;

    // Retrieve user ID from session (assuming you have one set)
    $userId = $_SESSION['user_id'];

    // Retrieve all products in cart for current user
    $query = "SELECT * FROM cart WHERE user_id = '$userId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Retrieve product details from database
            $productQuery = "SELECT * FROM products WHERE id = '" . $row['product_id'] . "'";
            $productResult = $conn->query($productQuery);
            $productRow = $productResult->fetch_assoc();

            echo 'Product: ' . $productRow['name'] . ' | Quantity: ' . $row['quantity'] . ' | Price: $' . number_format($productRow['price'], 2) . '<br>';
        }

        return true;
    } else {
        return false; // Cart is empty
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (addToCart($productId, $quantity)) {
        echo 'Product added to cart!';
    } else {
        echo 'Failed to add product to cart.';
    }
}

if (isset($_POST['view_cart'])) {
    viewCart();
}


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart System</title>
</head>
<body>

    <!-- Form to add product to cart -->
    <form action="" method="post">
        <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity"><br><br>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

    <!-- Button to view cart contents -->
    <button type="submit" name="view_cart">View Cart Contents</button>

    <?php
    if (isset($_POST['view_cart'])) {
        viewCart();
    }
    ?>
</body>
</html>


class Cart {
    public function __construct() {
        // Initialize an empty array to hold cart items
        $_SESSION['cart'] = [];
    }

    public function addProduct($productId, $quantity = 1) {
        // Check if product already exists in cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                // Increment quantity of existing item
                $item['quantity'] += $quantity;
                return true; // Product added successfully
            }
        }

        // Add new product to cart array
        $_SESSION['cart'][] = [
            'product_id' => $productId,
            'quantity'   => $quantity
        ];

        return true; // Product added successfully
    }

    public function removeProduct($productId) {
        // Find and remove product from cart array
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $productId) {
                unset($_SESSION['cart'][$key]);
                return true; // Product removed successfully
            }
        }

        return false; // Product not found in cart
    }

    public function updateQuantity($productId, $newQuantity) {
        // Find and update product quantity in cart array
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $newQuantity;
                return true; // Quantity updated successfully
            }
        }

        return false; // Product not found in cart
    }

    public function getCart() {
        return $_SESSION['cart'];
    }
}


require_once 'cart.php';

// Initialize Cart object
$cart = new Cart();

// Add products to cart
$cart->addProduct(1, 2); // Add product with ID 1 and quantity 2
$cart->addProduct(2, 3); // Add product with ID 2 and quantity 3

// Update quantity of existing product
$cart->updateQuantity(1, 4);

// Remove product from cart
$cart->removeProduct(2);

// Display cart contents
print_r($cart->getCart());


<?php
session_start();

// Check if cart is already set in the session, if not initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_to_cart($item_id) {
    global $_SESSION;
    $item_id = (int) $item_id;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        // If item is already in cart, increment quantity
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$item_id] = array(
            'name' => '',
            'price' => 0,
            'quantity' => 1,
            'subtotal' => 0
        );
    }
}

// Remove item from cart
function remove_from_cart($item_id) {
    global $_SESSION;
    $item_id = (int) $item_id;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Update quantity of item in cart
function update_quantity($item_id, $quantity) {
    global $_SESSION;
    $item_id = (int) $item_id;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] = (int) $quantity;
    }
}

// Calculate subtotal of cart
function calculate_subtotal() {
    global $_SESSION;
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += ($item['price'] * $item['quantity']);
    }
    return $subtotal;
}

// Print contents of cart
function print_cart() {
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>';
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item_id => $item) {
        $subtotal += ($item['price'] * $item['quantity']);
        echo '<tr><td>' . $item['name'] . '</td><td>$' . number_format($item['price'], 2) . '</td><td>' . $item['quantity'] . '</td><td>$' . number_format(($item['price'] * $item['quantity']), 2) . '</td></tr>';
    }
    echo '<tr><td colspan="3">Total:</td><td>$' . number_format($subtotal, 2) . '</td></tr>';
    echo '</table>';
}

// Add example item to cart
add_to_cart(1);

// Print contents of cart
print_cart();

?>


// Add item 2 to cart
add_to_cart(2);

// Remove item 1 from cart
remove_from_cart(1);

// Update quantity of item 2 in cart to 3
update_quantity(2, 3);


<?php
session_start();
?>


$_SESSION['cart'] = array(
    'items' => array(),
    'total' => 0
);


function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart']['items'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment the quantity of the existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to the cart
    $_SESSION['cart']['items'][] = array(
        'id' => $product_id,
        'name' => $product_name, // Replace with actual product name
        'price' => $product_price, // Replace with actual price
        'quantity' => $quantity
    );

    // Update the total cost
    $_SESSION['cart']['total'] += ($product_price * $quantity);
}


function display_cart() {
    global $_SESSION;

    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart']['items'] as $item) {
        echo "Item: " . $item['name'] . " (x" . $item['quantity'] . ") - $" . ($item['price'] * $item['quantity']) . "<br>";
    }
    echo "Total: $" . $_SESSION['cart']['total'];
}


<?php
session_start();

// Initialize cart session data
$_SESSION['cart'] = array(
    'items' => array(),
    'total' => 0
);

// Add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart']['items'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment the quantity of the existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to the cart
    $_SESSION['cart']['items'][] = array(
        'id' => $product_id,
        'name' => "Product Name", // Replace with actual product name
        'price' => 9.99, // Replace with actual price
        'quantity' => $quantity
    );

    // Update the total cost
    $_SESSION['cart']['total'] += ($product_price * $quantity);
}

// Display cart contents
function display_cart() {
    global $_SESSION;

    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart']['items'] as $item) {
        echo "Item: " . $item['name'] . " (x" . $item['quantity'] . ") - $" . ($item['price'] * $item['quantity']) . "<br>";
    }
    echo "Total: $" . $_SESSION['cart']['total'];
}

// Example usage
add_item_to_cart(1, 2);
display_cart();
?>


// config.php
<?php

const CART_SESSION_KEY = 'cart';
const CART_ITEM_KEY = 'item_';

?>


// index.php (or any other file that will handle cart functionality)
<?php

require_once 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

?>


// Add item to cart
function addToCart($item_id, $quantity) {
    if (isset($_SESSION[CART_SESSION_KEY])) {
        $cart = $_SESSION[CART_SESSION_KEY];
    } else {
        $cart = array();
        $_SESSION[CART_SESSION_KEY] = $cart;
    }

    // Create a unique key for the item
    $key = CART_ITEM_KEY . $item_id;

    // Check if the item is already in the cart
    if (array_key_exists($key, $cart)) {
        // Increment the quantity of the existing item
        $cart[$key]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $cart[$key] = array('id' => $item_id, 'quantity' => $quantity);
    }

    $_SESSION[CART_SESSION_KEY] = $cart;

    return true;
}


addToCart(1, 2); // Adds 2 of item with ID 1 to cart


// Display cart contents
function displayCart() {
    if (isset($_SESSION[CART_SESSION_KEY])) {
        $cart = $_SESSION[CART_SESSION_KEY];

        echo '<h2>Cart Contents:</h2>';
        foreach ($cart as $key => $item) {
            $item_id = substr($key, strlen(CART_ITEM_KEY));
            echo '<p>' . $item['quantity'] . ' x Item ' . $item_id . '</p>';
        }
    } else {
        echo '<p>Cart is empty.</p>';
    }
}


displayCart();


// Remove item from cart
function removeFromCart($item_id) {
    if (isset($_SESSION[CART_SESSION_KEY])) {
        $cart = $_SESSION[CART_SESSION_KEY];

        // Create a unique key for the item
        $key = CART_ITEM_KEY . $item_id;

        // Check if the item exists in the cart
        if (array_key_exists($key, $cart)) {
            unset($cart[$key]);

            // If the item is deleted successfully, remove the entire cart session
            if (empty($cart)) {
                unset($_SESSION[CART_SESSION_KEY]);
            }
        }

        return true;
    } else {
        return false;
    }
}


removeFromCart(1); // Removes item with ID 1 from cart


<?php
// Start session
session_start();

// Initialize an empty cart if it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // Update quantity
            $item['quantity'] += $quantity;
            return; // exit function early
        }
    }

    // Item not found, add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'quantity' => $quantity
    );
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    // Find index of item in cart
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$index]);
            return; // exit function early
        }
    }

    // Item not found, do nothing
}

// Function to update quantity of item in cart
function update_quantity($item_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return; // exit function early
        }
    }

    // Item not found, do nothing
}

// Add item to cart example
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);

// Remove item from cart example
remove_item_from_cart(1);

// Update quantity of item in cart example
update_quantity(2, 4);

// Print out the contents of the cart
print_r($_SESSION['cart']);


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    ?>
    <h1>Your Cart:</h1>
    <table border="1">
        <tr>
            <th>Item ID</th>
            <th>Quantity</th>
        </tr>
        <?php
        foreach ($_SESSION['cart'] as $item) {
            ?>
            <tr>
                <td><?php echo $item['id']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}
?>


<?php

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>


<?php

// Get product ID from URL parameter
$productId = $_GET['id'];

// Retrieve product details from database
$product = retrieveProduct($productId);

// Add product to cart
if (isset($_SESSION['cart'])) {
    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity']++;
            $productExists = true;
            break;
        }
    }

    // Add product to cart if it doesn't exist
    if (!$productExists) {
        $_SESSION['cart'][] = array(
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        );
    }
}

// Redirect to cart page
header('Location: cart.php');
exit;

function retrieveProduct($id) {
    // Retrieve product from database (example using PDO)
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

    $stmt = $db->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch();
}

?>


<?php

// Display cart contents
echo '<h1>Cart Contents</h1>';
echo '<table border="1">';

foreach ($_SESSION['cart'] as $item) {
    echo '<tr><td>' . $item['name'] . '</td><td>$' . number_format($item['price']) . ' x ' . $item['quantity'] . '</td></tr>';
}

echo '</table>';

// Total cart value
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += ($item['price'] * $item['quantity']);
}
echo '<p>Total: $' . number_format($total) . '</p>';

?>


<?php

// Assuming we are using a MySQL database connection named $dbConnection

function addProductToCart($productId, $userId = null) {
    global $dbConnection;

    if ($userId === null) {
        // If user_id is not provided, try to get it from the session
        $userId = $_SESSION['user_id'];
        if (!isset($_SESSION['user_id'])) {
            return "Error: User ID not found in session.";
        }
    }

    $query = "INSERT INTO cart (user_id, product_id) VALUES ($userId, $productId)";
    $result = mysqli_query($dbConnection, $query);

    if (!$result) {
        return "Error adding product to cart.";
    } else {
        return "Product added successfully!";
    }
}

function getCartContents() {
    global $dbConnection;

    $query = "SELECT * FROM cart WHERE user_id = ".$_SESSION['user_id'];
    $result = mysqli_query($dbConnection, $query);

    if (!$result) {
        return null;
    }

    $cartItems = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $cartItems[] = array(
            'product_id' => $row['product_id'],
            'quantity' => $row['quantity']
        );
    }
    return $cartItems;
}

function updateCartQuantity($productId, $newQuantity) {
    global $dbConnection;

    if ($newQuantity < 1) {
        return "Error: Quantity must be greater than zero.";
    }

    $query = "UPDATE cart SET quantity=$newQuantity WHERE user_id=".$_SESSION['user_id']." AND product_id=$productId";
    $result = mysqli_query($dbConnection, $query);

    if (!$result) {
        return "Error updating cart quantity.";
    } else {
        return "Cart updated successfully!";
    }
}

function removeProductFromCart($productId) {
    global $dbConnection;

    $query = "DELETE FROM cart WHERE user_id=".$_SESSION['user_id']." AND product_id=$productId";
    $result = mysqli_query($dbConnection, $query);

    if (!$result) {
        return "Error removing product from cart.";
    } else {
        return "Product removed successfully!";
    }
}

session_start();

// Example usage:
if (isset($_POST['add_to_cart'])) {
    echo addProductToCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
    echo updateCartQuantity($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    echo removeProductFromCart($_POST['product_id']);
}

// To display cart contents:
$cartContents = getCartContents();
if ($cartContents) {
    echo "Your Cart Contents:
";
    foreach ($cartContents as $item) {
        echo "Product ID: ".$item['product_id']." Quantity: ".$item['quantity']. "
";
    }
} else {
    echo "Your cart is empty.";
}

?>


<?php

// Session Start
session_start();

// If there's no cart data, create it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_name, $price) {
    global $_SESSION;

    // Check if the product is already in the cart to update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] == $product_name) {
            $item['quantity']++;
            break;
        }
    }

    // If the product isn't already in the cart, add it.
    else {
        $_SESSION['cart'][] = array('name' => $product_name, 'price' => $price, 'quantity' => 1);
    }
}

function remove_from_cart($product_name) {
    global $_SESSION;

    // Remove all instances of this product from the cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['name'] == $product_name) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // If no products were removed, do nothing.
}

function update_quantity($product_name, $new_quantity) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] == $product_name) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

function display_cart() {
    echo "<h2>Cart Contents:</h2>";
    global $_SESSION;

    foreach ($_SESSION['cart'] as $item) {
        echo "$item[name] x $item[quantity]: $" . $item['price'] * $item['quantity'];
        echo "<br>";
    }
}

// Example usage
if (isset($_POST['add'])) {
    add_to_cart($_POST['product'], $_POST['price']);
}
if (isset($_POST['remove'])) {
    remove_from_cart($_POST['product']);
}
if (isset($_POST['update'])) {
    update_quantity($_POST['product'], $_POST['quantity']);
}

// Example Display of Cart Contents
display_cart();

?>
<form action="" method="post">
    <input type="text" name="product" placeholder="Product Name">
    <input type="number" name="price" placeholder="Price">
    <button type="submit" name="add">Add to Cart</button>
</form>

<form action="" method="post">
    <input type="text" name="product" placeholder="Remove Product">
    <button type="submit" name="remove">Remove from Cart</button>
</form>

<form action="" method="post">
    <input type="hidden" name="product" value="<?php echo $_POST['product']; ?>">
    <input type="number" name="quantity" placeholder="New Quantity">
    <button type="submit" name="update">Update Quantity</button>
</form>


<?php

// Start the session
session_start();

// Initialize the cart array if it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity
            $item['quantity'] += $quantity;
            return; // Item is already in cart, so we're done
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );
}

function update_item_in_cart($product_id, $new_quantity) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity
            $item['quantity'] = $new_quantity;
            return; // Item updated successfully
        }
    }

    // Product not found in cart, so add it
    add_item_to_cart($product_id, $new_quantity);
}

function remove_item_from_cart($product_id) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            // Remove item from cart
            unset($_SESSION['cart'][$key]);
            return; // Item removed successfully
        }
    }

    // Product not found in cart, so do nothing
}

// Example usage:

// Add 2 items to cart with product ID 1 and quantity 3
add_item_to_cart(1, 3);

// Update quantity of item with product ID 1 to 5
update_item_in_cart(1, 5);

// Remove item with product ID 1 from cart
remove_item_from_cart(1);

?>


<?php
// Step 1: Start the session if it hasn't been started yet
session_start();

// Step 2: Retrieve or create the cart array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Step 3: Add an item to the cart, update quantities if needed
$itemId = '123'; // Replace with the actual ID of the product being added.
$quantity = 2; // The quantity of this item to add.

if (isset($_SESSION['cart'][$itemId])) {
    $_SESSION['cart'][$itemId] += $quantity;
} else {
    $_SESSION['cart'][$itemId] = $quantity;
}

// Optionally, you can display the contents of the cart here for verification.
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

// Step 4: Save changes to the session
$_SESSION['cart'] = array_filter($_SESSION['cart']); // This isn't strictly necessary but helps remove empty keys.

// If you want to keep everything in one place, including the session ID and starting it:
session_write_close();

// Always verify the cart's contents before attempting any database operations.
// Remember to adjust this example according to your actual project requirements, especially how products are identified within the cart.
?>


class Cart {
    private $sessionName = 'cart';
    private $items;

    public function __construct() {
        if (!isset($_SESSION[$this->sessionName])) {
            $_SESSION[$this->sessionName] = array();
        }
        $this->items = $_SESSION[$this->sessionName];
    }

    public function addProduct($productId, $quantity) {
        if (array_key_exists($productId, $this->items)) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $this->items[$productId] = array('quantity' => $quantity);
        }
        $_SESSION[$this->sessionName] = $this->items;
    }

    public function removeProduct($productId) {
        if (array_key_exists($productId, $this->items)) {
            unset($this->items[$productId]);
            $_SESSION[$this->sessionName] = $this->items;
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotalQuantity() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }

    public function getTotalPrice($prices) {
        $total = 0;
        foreach ($this->items as $productId => $item) {
            if (array_key_exists($productId, $prices)) {
                $total += $prices[$productId] * $item['quantity'];
            }
        }
        return $total;
    }
}


require_once 'Cart.php';

$cart = new Cart();


$productId = 1;
$quantity = 2;

$cart->addProduct($productId, $quantity);


$productId = 1;

$cart->removeProduct($productId);


print_r($cart->getItems());


echo $cart->getTotalQuantity();


$prices = array(
    1 => 10.99,
    2 => 5.99,
);

echo $cart->getTotalPrice($prices);


<?php
session_start();
?>


$cart = array();


function addToCart($product_id, $quantity) {
    global $cart;
    
    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If not, add a new item to the cart
    $cart[] = array(
        'id' => $product_id,
        'name' => '',  // We'll fill this in later with the product name
        'price' => '',
        'quantity' => $quantity
    );
}


function updateQuantity($product_id, $new_quantity) {
    global $cart;
    
    // Find the product in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // Update the quantity
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}


function removeFromCart($product_id) {
    global $cart;
    
    // Find the product in the cart and remove it
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }
}


function saveCartToSession() {
    global $cart;
    
    $_SESSION['cart'] = $cart;
}


function loadCartFromSession() {
    global $cart;
    
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        // If there's no cart in the session, create a new one
        $cart = array();
    }
}


// Add item to cart
addToCart(123, 2);

// Save cart to session
saveCartToSession();

// Load cart from session (not necessary if we're on the same page)
loadCartFromSession();

// Display contents of cart
foreach ($cart as $item) {
    echo "Item: " . $item['name'] . ", Quantity: " . $item['quantity'];
}

// Remove item from cart
removeFromCart(123);
saveCartToSession();


// Start session
session_start();

// Function to add item to cart (if it's already there, increment quantity)
function addToCart($productId, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if product is already in cart and update its quantity
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $productId) {
            $product['quantity'] += $quantity;
            return; // If it's found, we don't need to add it again
        }
    }
    
    // Add the product to cart (new entry)
    $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
}

// Function to update quantity of an item in cart
function updateQuantity($productId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $productId) {
            $product['quantity'] = $newQuantity;
            return; // If it's found, we don't need to loop further
        }
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    foreach ($_SESSION['cart'] as $key => &$product) {
        if ($product['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return; // If it's found, we can break the loop
        }
    }
}

// Function to clear entire cart
function clearCart() {
    $_SESSION['cart'] = array();
}

// Example usage:
if (isset($_POST['add'])) {
    addToCart($_POST['productId'], $_POST['quantity']);
} elseif (isset($_POST['update'])) {
    updateQuantity($_POST['productId'], $_POST['newQuantity']);
} elseif (isset($_POST['remove'])) {
    removeFromCart($_POST['productId']);
}

// Example of displaying the cart content
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        echo 'Product ID: ' . $product['id'] . ', Quantity: ' . $product['quantity'] . '<br>';
    }
} else {
    echo "Your cart is empty.";
}

// Don't forget to call session_write_close() at the end if you're outputting content
session_write_close();


<?php

// Start the session
session_start();

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Function to display current cart contents
function display_cart() {
    echo '<h2>Your Cart</h2>';
    echo '<ul>';
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Assume we have a function to retrieve product details by ID
        $product = get_product($product_id);
        echo '<li>' . $product['name'] . ' x' . $quantity . '</li>';
    }
    echo '</ul>';
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
} elseif (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

?>


<?php

include 'cart.php';

// Display cart contents
display_cart();

?>


// index.php (cart index page)

<?php
    // Start the session
    if (!isset($_SESSION)) {
        session_start();
    }

    // Initialize empty cart array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add product to cart
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        if ($product_id > 0 && $quantity > 0) {
            // Check if the product is already in the cart
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    $item['quantity'] += $quantity;
                    break;
                }
            }

            // If the product is not in the cart, add it
            else {
                $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
            }
        }
    }

    // Display products in cart
?>

<div class="cart">
    <h2>Cart</h2>
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>

        <?php
            // Display cart products
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    echo '<tr>';
                    echo '<td>' . $item['id'] . '</td>';
                    // Fetch product details from database
                    $product = getProduct($item['id']);
                    echo '<td>' . $product->name . '</td>';
                    echo '<td>$' . number_format($product->price, 2) . '</td>';
                    echo '<td>' . $item['quantity'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<p>No products in cart.</p>';
            }
        ?>
    </table>

    <?php
        // Display total cost
        if (!empty($_SESSION['cart'])) {
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $product = getProduct($item['id']);
                $total += ($product->price * $item['quantity']);
            }
            echo '<p>Total: $' . number_format($total, 2) . '</p>';
        }
    ?>
</div>

// Function to fetch product details
function getProduct($id) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'ecommerce');

    // Query product data from database
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    // Fetch product details
    $product = mysqli_fetch_assoc($result);
    return $product;
}


// checkout.php (checkout page)

<?php
    // Start the session
    if (!isset($_SESSION)) {
        session_start();
    }

    // Display products in cart
?>

<div class="cart">
    <h2>Cart</h2>
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Remove</th>
        </tr>

        <?php
            // Display cart products
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $key => $item) {
                    echo '<tr>';
                    echo '<td>' . $item['id'] . '</td>';
                    // Fetch product details from database
                    $product = getProduct($item['id']);
                    echo '<td>' . $product->name . '</td>';
                    echo '<td>$' . number_format($product->price, 2) . '</td>';
                    echo '<td>' . $item['quantity'] . '</td>';
                    echo '<td><a href="#" class="remove" data-id="' . $key . '">Remove</a></td>';
                    echo '</tr>';

                    // Remove product from cart when remove button is clicked
                    if (isset($_POST['remove'])) {
                        unset($_SESSION['cart'][$_POST['remove']]);
                        $_SESSION['cart'] = array_values($_SESSION['cart']);
                    }
                }
            } else {
                echo '<p>No products in cart.</p>';
            }
        ?>
    </table>

    <?php
        // Display total cost
        if (!empty($_SESSION['cart'])) {
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $product = getProduct($item['id']);
                $total += ($product->price * $item['quantity']);
            }
            echo '<p>Total: $' . number_format($total, 2) . '</p>';
        }
    ?>
</div>

// Remove event handler for remove buttons
$(document).ready(function() {
    $('.remove').on('click', function() {
        var id = $(this).data('id');
        $.post('checkout.php', { 'remove': id }, function(data) {
            // Update cart contents on page load
            location.reload();
        });
    });
});


// Assuming you have this in your 'cart' page:
<?php
    session_start();
    
    // Example array of products (in real scenarios, these would come from a database)
    $products = [
        ['id' => 1, 'name' => 'Product A', 'price' => 10.99],
        ['id' => 2, 'name' => 'Product B', 'price' => 19.99]
    ];

    // If you're adding a product to the cart for the first time
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // You can now add items to the cart like this:
    $addToCart = 'product_b'; // Product B was clicked or added
    if (in_array($addToCart, array_column($products, 'id'))) {
        $index = array_search(array_column($products, 'id'), $products)[$addToCart];
        
        $_SESSION['cart'][] = [
            'id' => $products[$index]['id'],
            'name' => $products[$index]['name'],
            'price' => $products[$index]['price'],
            'quantity' => 1
        ];
    }

    // Displaying the cart contents:
    if (isset($_SESSION['cart'])) {
        echo "Your Cart:
";
        foreach ($_SESSION['cart'] as $item) {
            echo "ID: {$item['id']}, Name: {$item['name']}, Price: {$item['price']} x {$item['quantity']}
";
        }
    } else {
        echo "Your cart is empty.";
    }

    // Remove item from cart
    if (isset($_POST['remove'])) {
        $removeIndex = array_search($_POST['id'], array_column($_SESSION['cart'], 'id'));
        unset($_SESSION['cart'][$removeIndex]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
?>

<form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $addToCart; ?>">
    <button type="submit">Add to Cart</button>
</form>

<form action="" method="post">
    <?php if (isset($removeIndex)): ?>
        <input type="hidden" name="id" value="<?php echo $products[$index]['id']; ?>">
    <?php endif; ?>
    <button type="submit" name="remove">Remove from Cart</button>
</form>


<?php
session_start();
?>


function addProductToCart($productId) {
    // Get existing items from session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // If the product is already in the cart, increment its quantity
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += 1;
    }
    // Otherwise, add it to the cart with a quantity of 1
    else {
        $_SESSION['cart'][$productId] = 1;
    }

    return true; // For now, let's just echo out confirmation
}

// Example usage:
addProductToCart('product-123');


function removeFromCart($productId) {
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
        return true; // Confirmation for now
    }
    
    // If product not found in cart, echo an error or log it
    return false;
}

// Example usage:
removeFromCart('product-123');


function viewCart() {
    if (isset($_SESSION['cart'])) {
        echo "<h2>Your Cart:</h2>";
        foreach ($_SESSION['cart'] as $id => $quantity) {
            // Assuming 'product-123' is a unique identifier for your product, you'd need to fetch the actual product info from your database
            echo "Product: $id (Quantity: $quantity)<br>";
        }
    } else {
        echo "<p>No products in cart.</p>";
    }
}

// Example usage:
viewCart();


function updateQuantity($productId, $quantity) {
    // Check if the session is set
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $quantity;
        return true; // Confirmation for now
    }
    
    // If product not found in cart, echo an error or log it
    return false;
}

// Example usage:
updateQuantity('product-123', 3);


<?php
session_start();
?>


// Function to initialize or retrieve cart data from session
function getCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    return $_SESSION['cart'];
}

// Function to add an item to the cart
function addToCart($product, $quantity) {
    $cart = getCart();
    $found = false;
    
    // Check if product is already in cart
    foreach ($cart as &$item) {
        if ($item['product'] == $product) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    // If not found, add it to the cart
    if (!$found) {
        $cart[] = array('product' => $product, 'quantity' => $quantity);
    }
    
    $_SESSION['cart'] = $cart;
}

// Function to display the contents of the cart
function showCart() {
    $cart = getCart();
    echo "Your Cart:
";
    foreach ($cart as $item) {
        echo "- " . $item['product'] . ": " . $item['quantity'] . "
";
    }
}

// Function to remove an item from the cart
function removeFromCart($product) {
    $cart = getCart();
    
    // Remove all occurrences of the product in the cart
    foreach ($cart as $key => $item) {
        if ($item['product'] == $product) {
            unset($cart[$key]);
        }
    }
    
    $_SESSION['cart'] = array_values($cart);
}


// Add some items to the cart
addToCart('Product A', 2);
addToCart('Product B', 3);

// Display the cart
showCart();

// Remove one product from the cart
removeFromCart('Product A');

// Again, display the updated cart contents
showCart();


// Initialize the cart session
session_start();


[
    'items' => [
        [
            'id' => int,
            'name' => string,
            'price' => float,
            'quantity' => int
        ],
        ...
    ]
]


// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart']['items'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity if product is already in cart
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart if not already present
    $_SESSION['cart']['items'][] = [
        'id' => $product_id,
        'name' => '', // Will be populated later
        'price' => 0.00, // Will be populated later
        'quantity' => $quantity
    ];
}


// Function to populate cart item details
function populate_cart_item($product_id) {
    // Assume we have a database connection and query to fetch product details
    $product = fetch_product_details($product_id);

    if ($product) {
        foreach ($_SESSION['cart']['items'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['name'] = $product['name'];
                $item['price'] = $product['price'];
                break;
            }
        }
    }
}


// Function to update cart quantity
function update_cart_quantity($product_id, $new_quantity) {
    // Check if product is present in cart
    foreach ($_SESSION['cart']['items'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}


// Function to remove item from cart
function remove_from_cart($product_id) {
    // Check if product is present in cart
    foreach ($_SESSION['cart']['items'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart']['items'][$key]);
            break;
        }
    }
}


// Initialize cart session
session_start();

// Add item to cart
add_to_cart(123, 2);

// Populate cart item details
populate_cart_item(123);

// Update cart quantity
update_cart_quantity(123, 3);

// Remove item from cart
remove_from_cart(123);


<?php
session_start();

// Check if the cart session already exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $cart;
    
    // Check if product is already in cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            // Update quantity if it's greater than the current one
            if ($quantity > $item['quantity']) {
                $item['quantity'] = $quantity;
            }
            return; // exit function
        }
    }
    
    // Add new item to cart
    $cart[] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $cart;
    
    foreach ($cart as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            break;
        }
    }
}

// Function to update quantity of an item in cart
function update_quantity($product_id, $new_quantity) {
    global $cart;
    
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

// Function to get cart contents
function get_cart_contents() {
    global $cart;
    return $cart;
}
?>


<?php
require 'cart.php';

// Add item to cart
add_to_cart(1, 2);

// Update quantity of an item in cart
update_quantity(1, 3);

// Remove item from cart
remove_from_cart(1);

// Get cart contents
$cart_contents = get_cart_contents();
print_r($cart_contents);
?>


<?php
// Start the session
session_start();

// Define the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($id, $name, $price) {
    global $_SESSION;
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // If it's already in the cart, increment the quantity
            $item['quantity']++;
            return;
        }
    }
    // Add new item to the cart
    $_SESSION['cart'][] = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
}

// Function to remove an item from the cart
function removeFromCart($id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
    return false;
}

// Function to update the quantity of an item in the cart
function updateQuantity($id, $newQuantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $newQuantity;
            return true;
        }
    }
    return false;
}

// Example usage:
addToCart(1, 'Product 1', 9.99);
addToCart(2, 'Product 2', 19.99);
removeFromCart(2);

// Print the cart contents
print_r($_SESSION['cart']);
?>


<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $cart;
    if (isset($GLOBALS['cart'][$product_id])) {
        $GLOBALS['cart'][$product_id] += $quantity;
    } else {
        $GLOBALS['cart'][$product_id] = $quantity;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $cart;
    unset($GLOBALS['cart'][$product_id]);
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $cart;
    if (isset($cart[$product_id])) {
        $cart[$product_id] = $new_quantity;
    }
}

// Function to display cart contents
function display_cart() {
    global $cart;
    echo "<h2>Cart Contents:</h2>";
    foreach ($cart as $product_id => $quantity) {
        // Retrieve product details from database
        $product_name = retrieve_product_details($product_id)['name'];
        echo "$product_name x $quantity<br>";
    }
}

// Function to retrieve product details
function retrieve_product_details($product_id) {
    // Assume this function connects to a database and retrieves the product details
    // Replace with actual implementation
    return array('name' => 'Product Name', 'price' => 9.99);
}
?>


// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Add item to cart
add_to_cart(1, 2);

// Remove item from cart
remove_from_cart(1);

// Update quantity of item in cart
update_quantity(1, 3);

// Display cart contents
display_cart();

// Retrieve product details for a specific product
$product_details = retrieve_product_details(1);
echo $product_details['name'] . "<br>";


<?php
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it with an empty array
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_item_to_cart($product_id) {
    global $_SESSION;
    $item_exists = false;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item_exists = true;
            break;
        }
    }

    // If it's not, add it to the cart
    if (!$item_exists) {
        $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
    } else {
        // If it is, increment the quantity of that item in the cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity']++;
                break;
            }
        }
    }

    // Save the updated session data
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

// Function to remove an item from the cart
function remove_item_from_cart($product_id) {
    global $_SESSION;

    // Check if the product is in the cart and remove it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Save the updated session data
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

// Function to update the quantity of an item in the cart
function update_item_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Check if the product is in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }

    // Save the updated session data
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

// Display the current cart contents
function display_cart() {
    global $_SESSION;

    // Print out the items in the cart
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Product ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}

// Example usage:
add_item_to_cart(123);
add_item_to_cart(456); // This will increment the quantity of product 456

remove_item_from_cart(123);

update_item_quantity(456, 2);

display_cart();
?>


<?php
session_start();

// Check if cart is already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
session_start();

// Get item ID, name, price, etc.
$item_id = $_POST['item_id'];
$item_name = $_POST['item_name'];
$price = $_POST['price'];

// Add item to cart array
$_SESSION['cart'][] = array(
    'item_id' => $item_id,
    'name' => $item_name,
    'price' => $price,
);

// Redirect user back to cart page
header('Location: cart.php');
exit;
?>


<?php
session_start();

// Display cart items
echo '<h2>Cart</h2>';
foreach ($_SESSION['cart'] as $item) {
    echo '<p>' . $item['name'] . ' ($' . $item['price'] . ')';
    echo ' <a href="remove_from_cart.php?item_id=' . $item['item_id'] . '">Remove</a></p>';
}

// Display total cost
$total_cost = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_cost += $item['price'];
}
echo '<h2>Total Cost: $' . number_format($total_cost, 2) . '</h2>';
?>


<?php
session_start();

// Get item ID to remove
$item_id = $_GET['item_id'];

// Remove item from cart array
unset($_SESSION['cart'][$_SESSION['cart'].indexOf($item_id)]);

// Redirect user back to cart page
header('Location: cart.php');
exit;
?>


<?php
session_start();
?>


$cart = array();


function add_to_cart($product_id) {
    global $cart;
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    } else {
        $_SESSION['cart'] = array($product_id);
    }
}


function view_cart() {
    global $cart;
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return array();
    }
}


function remove_from_cart($product_id) {
    global $cart;
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_diff($_SESSION['cart'], array($product_id));
    }
}


function update_cart_quantity($product_id, $quantity) {
    global $cart;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$id) {
            if ($id == $product_id) {
                $_SESSION['cart'][$id] = array('quantity' => $quantity);
            }
        }
    }
}


// Start the session
session_start();

// Add item to cart
add_to_cart(1);

// View cart contents
print_r(view_cart());

// Remove item from cart
remove_from_cart(1);

// Update quantity
update_cart_quantity(2, 3);


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already in cart, update quantity
        }
    }
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // Product removed from cart
        }
    }
}

// Function to update item quantity in cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return; // Quantity updated
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Product ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}

// Example usage:
add_to_cart(1, 3); // Add product with id 1 to cart
display_cart(); // Display current cart contents

?>


include 'cart.php';
add_to_cart(1, 3); // Add product with id 1 to cart


echo '<pre>';
display_cart(); // Display current cart contents
echo '</pre>';


remove_from_cart(1); // Remove product with id 1 from cart


update_quantity(1, 2); // Update quantity of product with id 1 to 2


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Establish database connection
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
  echo "Error connecting to database: " . $e->getMessage();
  exit;
}

// Function to add product to cart
function add_product_to_cart($product_id, $user_id) {
  global $conn;

  // Check if product is already in cart
  $query = "SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    // Product is already in cart, update quantity
    $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
  } else {
    // Product is not in cart, insert new row
    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
  }
}

// Function to view cart contents
function view_cart_contents($user_id) {
  global $conn;

  // Retrieve cart contents
  $query = "SELECT * FROM cart WHERE user_id = :user_id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  return $stmt->fetchAll();
}

// Function to remove product from cart
function remove_product_from_cart($product_id, $user_id) {
  global $conn;

  // Delete product from cart
  $query = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();
}

// Example usage
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $user_id = $_SESSION['user_id']; // Assuming user is logged in and has a session
  add_product_to_cart($product_id, $user_id);
}

if (isset($_POST['view_cart'])) {
  $user_id = $_SESSION['user_id'];
  $cart_contents = view_cart_contents($user_id);
  print_r($cart_contents); // Output cart contents
}

if (isset($_POST['remove_product'])) {
  $product_id = $_POST['product_id'];
  $user_id = $_SESSION['user_id'];
  remove_product_from_cart($product_id, $user_id);
}
?>


<form method="post">
  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>


<form method="post">
  <button type="submit" name="view_cart">View Cart</button>
</form>


<form method="post">
  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
  <button type="submit" name="remove_product">Remove Product</button>
</form>


<?php

// Configuration variables
define('CART_SESSION_NAME', 'user_cart');
define('CART_TABLE_NAME', 'cart');

// Function to initialize the cart session
function init_cart_session() {
  if (!isset($_SESSION[CART_SESSION_NAME])) {
    $_SESSION[CART_SESSION_NAME] = array();
  }
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
  init_cart_session();

  // Check if the product is already in the cart
  foreach ($_SESSION[CART_SESSION_NAME] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] += $quantity;
      return;
    }
  }

  // Add new item to the cart
  $_SESSION[CART_SESSION_NAME][] = array(
    'id' => $product_id,
    'quantity' => $quantity
  );
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  init_cart_session();

  foreach ($_SESSION[CART_SESSION_NAME] as $key => &$item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION[CART_SESSION_NAME][$key]);
      return;
    }
  }
}

// Function to update the quantity of an item in the cart
function update_cart_item($product_id, $new_quantity) {
  init_cart_session();

  foreach ($_SESSION[CART_SESSION_NAME] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] = $new_quantity;
      return;
    }
  }
}

// Function to get the cart contents
function get_cart_contents() {
  init_cart_session();

  return $_SESSION[CART_SESSION_NAME];
}

// Function to save the cart contents to the database
function save_cart_to_database() {
  init_cart_session();

  // Insert new items into the cart table
  foreach (get_cart_contents() as $item) {
    db_query("INSERT INTO " . CART_TABLE_NAME . " (user_id, product_id, quantity)
      VALUES (:user_id, :product_id, :quantity)", array(
        ':user_id' => $_SESSION['user_id'],
        ':product_id' => $item['id'],
        ':quantity' => $item['quantity']
      ));
  }

  // Update existing items in the cart table
  foreach (get_cart_contents() as $item) {
    db_query("UPDATE " . CART_TABLE_NAME . "
      SET quantity = :quantity
      WHERE product_id = :product_id", array(
        ':quantity' => $item['quantity'],
        ':product_id' => $item['id']
      ));
  }
}


// Add a product to the cart with quantity 2
add_to_cart(1, 2);

// Remove a product from the cart
remove_from_cart(1);

// Update the quantity of an item in the cart
update_cart_item(1, 3);

// Save the current cart contents to the database
save_cart_to_database();


<?php
session_start();
?>


$cart = array(); // Array to hold cart items
$total_items = 0; // Total number of items in cart
$total_cost = 0.00; // Total cost of items in cart


function add_to_cart($item_id, $quantity) {
    global $cart, $total_items, $total_cost;
    
    // Check if item already exists in cart
    foreach ($cart as $key => $value) {
        if ($value['id'] == $item_id) {
            // Update quantity and cost
            $cart[$key]['quantity'] += $quantity;
            $cart[$key]['cost'] = $quantity * get_item_cost($item_id);
            break;
        }
    }
    
    // Add new item to cart if it doesn't exist
    else {
        $cart[] = array(
            'id' => $item_id,
            'name' => get_item_name($item_id),
            'cost' => get_item_cost($item_id) * $quantity,
            'quantity' => $quantity
        );
        
        // Update total items and cost
        $total_items += $quantity;
        $total_cost += $cart[count($cart) - 1]['cost'];
    }
    
    // Save cart to session
    $_SESSION['cart'] = $cart;
}


function display_cart() {
    global $cart;
    
    echo '<h2>Cart Contents</h2>';
    echo '<ul>';
    foreach ($cart as $item) {
        echo '<li>' . $item['name'] . ' x ' . $item['quantity'] . ' = &#36;' . number_format($item['cost'], 2) . '</li>';
    }
    echo '</ul>';
    
    // Display total items and cost
    echo '<p>Total Items: ' . $total_items . '</p>';
    echo '<p>Total Cost: &#36;' . number_format($total_cost, 2) . '</p>';
}


// Initialize session and cart variables
session_start();
$cart = array();
$total_items = 0;
$total_cost = 0.00;

// Add items to cart
add_to_cart(1, 2); // Add item with ID 1 x 2
add_to_cart(2, 3); // Add item with ID 2 x 3

// Display cart contents
display_cart();


<?php
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // If it is, increment the quantity
        $_SESSION['cart'][$item_id] += $quantity;
    } else {
        // If not, add the item to the cart with the given quantity
        $_SESSION['cart'][$item_id] = $quantity;
    }
}

// Function to remove item from cart
function remove_from_cart($item_id) {
    global $_SESSION;
    
    // Check if the item is in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // If it is, unset it from the cart
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to update quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    
    // Check if the item is in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // If it is, update its quantity
        $_SESSION['cart'][$item_id] = $new_quantity;
    }
}

// Example usage:
// Add an item to the cart
add_to_cart(1, 2); // Item ID 1 with quantity 2

// Remove an item from the cart
remove_from_cart(1);

// Update the quantity of an item in the cart
update_quantity(1, 3);


<?php
require 'cart.php';

// Display the contents of the cart
echo '<h2>Cart Contents:</h2>';
foreach ($_SESSION['cart'] as $item_id => $quantity) {
    echo "Item ID: $item_id, Quantity: $quantity<br>";
}


session_start();


// Initialize cart session variables
$_SESSION['cart'] = array();
$_SESSION['cart_total'] = 0;


function add_product_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity if product is already in cart
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new product to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


function update_cart_total() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * get_product_price($item['id']);
    }
    $_SESSION['cart_total'] = $total;
}


// Add products to cart
add_product_to_cart(1, 2); // Product ID 1 with quantity 2
add_product_to_cart(2, 3); // Product ID 2 with quantity 3

// Update cart total
update_cart_total();

// Print cart contents and total
echo "Cart Contents:
";
foreach ($_SESSION['cart'] as $item) {
    echo "$item[id] x $item[quantity]
";
}
echo "
Total: " . $_SESSION['cart_total'];


<?php
session_start();

// Initialize cart ID (if it doesn't exist)
if (!isset($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = uniqid();
}
?>


<?php
session_start();

// Get product ID and quantity from form submission
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if product exists in cart (optional)
if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity of existing item
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
} else {
    // Add new item to cart
    $_SESSION['cart'][$product_id] = [
        'name' => 'Product Name',  // Replace with actual product name
        'price' => 9.99,           // Replace with actual price
        'quantity' => $quantity
    ];
}

// Redirect to cart page for display
header('Location: cart.php');
exit;
?>


<?php
session_start();

// Get cart items from session array
$items = $_SESSION['cart'];

?>

<!-- Display cart items -->
<table>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>

    <?php foreach ($items as $item_id => $item): ?>
        <tr>
            <td><?= $item['name'] ?></td>
            <td>$<?= number_format($item['price'], 2) ?></td>
            <td><?= $item['quantity'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

<!-- Display total cost -->
<p>Total: <?= number_format(array_sum(array_map(function ($item) { return $item['price'] * $item['quantity']; }, $items)), 2) ?></p>

<!-- Provide a form to update quantities or remove items -->
<form method="post">
    <input type="hidden" name="cart_update">
    <button type="submit">Update Cart</button>
</form>


<?php
session_start();

// Get action from form submission (remove or update quantity)
if ($_POST['cart_update'] == 'remove') {
    // Remove item from cart by product ID
    unset($_SESSION['cart'][$_POST['product_id']]);
} elseif ($_POST['cart_update'] == 'update_quantity') {
    // Update quantity of existing item
    $_SESSION['cart'][$_POST['product_id']]['quantity'] = $_POST['new_quantity'];
}

// Redirect to cart page for display
header('Location: cart.php');
exit;
?>


<?php
session_start();

// Check if cart is already in session, if not initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update quantity of item in cart
function update_quantity_in_cart($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    }
}

// Display cart contents
function display_cart_contents() {
    global $_SESSION;
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Retrieve product details from database or other source...
        $product_name = retrieve_product_name($product_id);
        $price = retrieve_product_price($product_id);
        echo "$product_name x $quantity = $" . ($price * $quantity) . "<br>";
    }
}

// Function to retrieve product name and price (example: retrieve from database)
function retrieve_product_name($product_id) {
    // Example database query...
    $db = new mysqli("localhost", "username", "password", "database");
    $result = $db->query("SELECT name FROM products WHERE id = '$product_id'");
    return $result->fetch_assoc()['name'];
}

// Function to retrieve product price (example: retrieve from database)
function retrieve_product_price($product_id) {
    // Example database query...
    $db = new mysqli("localhost", "username", "password", "database");
    $result = $db->query("SELECT price FROM products WHERE id = '$product_id'");
    return $result->fetch_assoc()['price'];
}
?>


add_item_to_cart(123, 2); // Add 2 of product with ID 123 to cart


remove_item_from_cart(123); // Remove product with ID 123 from cart


update_quantity_in_cart(123, 3); // Update quantity of product with ID 123 to 3


display_cart_contents();


session_start();


<?php

// Start session
session_start();

function add_to_cart($product_id, $quantity = 1) {
    // Check if product_id is set in session['cart'] array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // If the item already exists in cart, increment its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Exit function to prevent duplicate additions
        }
    }

    // If not existing, add it with new quantity and timestamp
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'timestamp'  => time(),
        'quantity'   => $quantity
    );
}

function remove_from_cart($product_id) {
    if (!isset($_SESSION['cart'])) return; // Nothing to remove

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            return;
        }
    }
}

function view_cart() {
    global $db; // Assuming a database connection is necessary for product info
    
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        return;
    }

    foreach ($_SESSION['cart'] as $item) {
        // Display item's name, price, and quantity
        // You would replace this with actual database queries to get the product names and prices
        // Here we assume you have the functions get_product_name($product_id) and get_product_price($product_id)
        echo "Product: " . get_product_name($item['product_id']) .
             ", Price: $" . get_product_price($item['product_id']) .
             ", Quantity: " . $item['quantity'] .
             "<br>";
    }
}

// Example usage:
add_to_cart(1, 2); // Adds item with ID 1 in quantity of 2
add_to_cart(3);
view_cart();

?>


function get_product_name($product_id) {
    // Simulated database query for illustration only.
    global $db;
    
    $query = "SELECT name FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result)['name'];
}

function get_product_price($product_id) {
    // Simulated database query for illustration only.
    global $db;
    
    $query = "SELECT price FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result)['price'];
}


<?php
session_start();

// Define the cart key
$_SESSION['cart'] = array();
?>


<?php
function addToCart($productId, $quantity) {
    // Check if product exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            return; // Item already exists, update quantity
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
}
?>


<?php
function viewCart() {
    // Print the cart contents
    echo "<h2>My Cart:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['id']} (Quantity: {$item['quantity']})<br>";
    }
}
?>


<?php
function updateQuantity($productId, $newQuantity) {
    // Find the item in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity;
            return; // Item found and updated
        }
    }

    echo "Error: Product not found in cart.";
}
?>


<?php
function removeFromCart($productId) {
    // Find the item in the cart and remove it
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return; // Item removed
        }
    }

    echo "Error: Product not found in cart.";
}
?>


<?php
require_once 'config.php';
require_once 'functions.php';

// Add an item to the cart
addToCart(1, 2);

// View the cart contents
viewCart();

// Update the quantity of an item
updateQuantity(1, 3);

// Remove an item from the cart
removeFromCart(1);
?>


// Initialize session
session_start();

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
  // Check if product is already in cart
  foreach ($_SESSION['cart_items'] as &$item) {
    if ($item['product_id'] == $product_id) {
      // Increment quantity
      $item['quantity'] += $quantity;
      return true;
    }
  }

  // Add new item to cart
  $_SESSION['cart_items'][] = array(
    'product_id' => $product_id,
    'product_name' => '', // Will be populated later
    'quantity' => $quantity,
    'price' => 0.00 // Will be populated later
  );

  return true;
}

// Function to update item quantity in cart
function update_cart_item($product_id, $new_quantity) {
  foreach ($_SESSION['cart_items'] as &$item) {
    if ($item['product_id'] == $product_id) {
      $item['quantity'] = $new_quantity;
      return true;
    }
  }

  return false; // Item not found in cart
}

// Function to remove item from cart
function remove_from_cart($product_id) {
  $_SESSION['cart_items'] = array_filter($_SESSION['cart_items'], function($item) use ($product_id) {
    return $item['product_id'] != $product_id;
  });

  return true; // Item removed successfully
}

// Function to display cart contents
function display_cart() {
  echo "<h2>Cart Contents:</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Product</th><th>Quantity</th><th>Price</th></tr>";

  foreach ($_SESSION['cart_items'] as $item) {
    echo "<tr>";
    echo "<td>" . $item['product_name'] . "</td>";
    echo "<td>" . $item['quantity'] . "</td>";
    echo "<td>" . number_format($item['price'], 2) . "</td>";
    echo "</tr>";
  }

  echo "</table>";

  // Calculate total cart value
  $total = 0;
  foreach ($_SESSION['cart_items'] as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  echo "<p>Total: $" . number_format($total, 2) . "</p>";
}


<?php require_once 'cart.php'; ?>

<!-- HTML form to add product to cart -->
<form action="" method="post">
  <label>Product ID:</label>
  <input type="number" name="product_id" value="1">
  <br>
  <label>Quantity:</label>
  <input type="number" name="quantity" value="2">
  <br>
  <button type="submit">Add to Cart</button>
</form>

<?php
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
  add_to_cart($_POST['product_id'], $_POST['quantity']);
}

// Display cart contents
display_cart();
?>


<?php
session_start();
?>


function addToCart($product_id, $quantity) {
    global $cart;
    
    // If the session doesn't exist yet, create it.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if this product is already in the cart and update its quantity
    foreach ($GLOBALS['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Exit early since we've updated an existing item.
        }
    }
    
    // If the product isn't in the cart yet, add it
    $GLOBALS['cart'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity
    );
}

// Example usage:
addToCart(1, 2); // Adds or increases quantity of item with id 1.


if (isset($_SESSION['cart'])) {
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product: {$item['product_id']} - Quantity: {$item['quantity']}<br>";
    }
}


function removeFromCart($product_id) {
    global $cart;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                break; // Exit the loop once we've found and removed it.
            }
        }
    }
}

// Example usage:
removeFromCart(1); // Removes item with id 1 from cart.


<?php
session_start();

function addToCart($product_id, $quantity) {
    global $cart;
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    foreach ($GLOBALS['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; 
        }
    }
    
    $GLOBALS['cart'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity
    );
}

function removeFromCart($product_id) {
    global $cart;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                break; 
            }
        }
    }
}

// Example usage:
addToCart(1, 2);
echo "Initial Cart Contents:<br>";
foreach ($_SESSION['cart'] as $item) {
    echo "Product: {$item['product_id']} - Quantity: {$item['quantity']}<br>";
}

removeFromCart(1);

echo "<br>After Removing Item from Cart:<br>";
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        echo "Product: {$item['product_id']} - Quantity: {$item['quantity']}<br>";
    }
}
?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['id'] == $product_id) {
            // Update quantity
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            return;
        }
    }

    // Add new product to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function update_cart_item($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

function remove_from_cart($product_id) {
    // Remove item from cart by product ID
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
        return $item['id'] != $product_id;
    });
}

function get_cart_contents() {
    return $_SESSION['cart'];
}
?>


add_to_cart(123, 2); // Add product with ID 123 in quantity 2


update_cart_item(123, 3); // Update product with ID 123 to quantity 3


remove_from_cart(123);


$cart_contents = get_cart_contents();
print_r($cart_contents); // Output: Array ( [0] => Array ( [id] => 123 [quantity] => 3 ) )


<?php
session_start();
?>


// Function to add item(s) to cart
function addItemToCart($id, $quantity) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        // If product is already in cart, increment its quantity instead of adding a new entry.
        foreach ($_SESSION['cart'] as &$product) {
            if ($product['id'] == $id) {
                $product['quantity'] += $quantity;
                return true;  // Item added or incremented successfully
            }
        }
    }

    // If not in cart, add it with given quantity.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    array_push($_SESSION['cart'], ['id' => $id, 'quantity' => $quantity]);
    return true;  // Item added successfully
}

// Function to remove item from cart by its id.
function removeFromCart($id) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$product) {
            if ($product['id'] == $id) {
                unset($product);
                break;
            }
        }

        // If the product is not found, nothing to do.
    } else {
        $_SESSION['cart'] = array();
    }
}

// Function to update quantity of item in cart
function updateCartQuantity($id, $newQuantity) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$product) {
            if ($product['id'] == $id) {
                $product['quantity'] = $newQuantity;
                return true;  // Quantity updated successfully
            }
        }
    }

    return false;  // Error in updating quantity.
}

// Function to calculate total price of items in cart
function getCartTotal() {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as &$product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }

    return 0;  // Cart is empty
}


// Assuming $products is an array where keys are product ids and values are arrays containing 'price' for simplicity.
$products = [
    1 => ['price' => 10],
    // Rest of the products...
];

// Add to cart with quantity of 3
addItemToCart(1, 3);

// Display total price
echo "Total Price: $" . getCartTotal();


<?php
session_start();
?>


class Cart {
    public function __construct() {
        // Initialize the cart as an empty array if it doesn't exist in session
        $this->cart = $_SESSION['cart'] ?? [];
    }

    public function add($item) {
        // Add item to cart, overwriting any existing quantity of same item
        $this->cart[$item['id']] = ['name' => $item['name'], 'quantity' => 1];
    }

    public function remove($itemId) {
        // Remove item from cart
        unset($this->cart[$itemId]);
    }

    public function updateQuantity($itemId, $newQuantity) {
        // Update quantity of existing item in cart
        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['quantity'] = $newQuantity;
        }
    }
}


$cart = new Cart();

// Adding items to cart
$cart->add(['id' => 1, 'name' => 'Product A']);
$cart->add(['id' => 2, 'name' => 'Product B']);

// Removing an item from cart
$cart->remove(1);

// Updating the quantity of an existing item
$cart->updateQuantity(2, 3);


$_SESSION['cart'] = $cart->cart;


<?php
session_start();

class Cart {
    public function __construct() {
        $this->cart = $_SESSION['cart'] ?? [];
    }

    public function add($item) {
        $this->cart[$item['id']] = ['name' => $item['name'], 'quantity' => 1];
    }

    public function remove($itemId) {
        unset($this->cart[$itemId]);
    }

    public function updateQuantity($itemId, $newQuantity) {
        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['quantity'] = $newQuantity;
        }
    }
}

$cart = new Cart();

// Adding items to cart
$cart->add(['id' => 1, 'name' => 'Product A']);
$cart->add(['id' => 2, 'name' => 'Product B']);

// Removing an item from cart
$cart->remove(1);

// Updating the quantity of an existing item
$cart->updateQuantity(2, 3);

$_SESSION['cart'] = $cart->cart;
?>


<?php
session_start();
?>


// config.php

$cart = array(
    'items' => array(),
    'total_items' => 0,
    'total_cost' => 0.00
);


// functions.php

function add_to_cart($item_id, $quantity) {
    global $cart;

    // Check if item already exists in cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $cart['total_items']++;
    $cart['total_cost'] += $quantity * get_item_price($item_id);
    $cart['items'][] = array(
        'id' => $item_id,
        'quantity' => $quantity
    );
}


// functions.php

function remove_from_cart($item_id) {
    global $cart;

    // Find index of item in cart
    $index = null;
    foreach ($cart['items'] as $i => &$item) {
        if ($item['id'] == $item_id) {
            $index = $i;
            break;
        }
    }

    // Remove item from cart
    if ($index !== null) {
        unset($cart['items'][$index]);
        $cart['total_items']--;
        $cart['total_cost'] -= get_item_price($item_id);
    }
}


// functions.php

function update_cart_session() {
    global $cart;

    $_SESSION['cart'] = serialize($cart);
}


// functions.php

function get_cart_items() {
    global $cart;
    return unserialize($_SESSION['cart']);
}


add_to_cart(1, 2);
update_cart_session();


remove_from_cart(1);
update_cart_session();


$cart_items = get_cart_items();
print_r($cart_items);


<?php

// Initialize session
session_start();

// Define some constants for the shopping cart
define('CART_SESSION_NAME', 'shopping_cart');
define('ITEM_ID_KEY', 'item_id');
define('QUANTITY_KEY', 'quantity');

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    // Check if product exists in session
    if (!isset($_SESSION[CART_SESSION_NAME][$product_id])) {
        $_SESSION[CART_SESSION_NAME][$product_id] = array(
            ITEM_ID_KEY => $product_id,
            'name' => '', // Get name from database or API call
            'price' => 0.00, // Get price from database or API call
            QUANTITY_KEY => $quantity
        );
    } else {
        // Increment quantity if item already exists in cart
        $_SESSION[CART_SESSION_NAME][$product_id][QUANTITY_KEY] += $quantity;
    }
}

// Function to update cart contents
function update_cart_contents() {
    // Remove any items that have zero quantity
    foreach ($_SESSION[CART_SESSION_NAME] as $item_id => $item) {
        if ($item[QUANTITY_KEY] <= 0) {
            unset($_SESSION[CART_SESSION_NAME][$item_id]);
        }
    }
}

// Function to get total cost of cart contents
function get_total_cost() {
    update_cart_contents();
    $total = 0.00;
    foreach ($_SESSION[CART_SESSION_NAME] as $item) {
        $total += ($item['price'] * $item[QUANTITY_KEY]);
    }
    return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    add_item_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['update_cart'])) {
    update_cart_contents();
}

?>


<?php require_once 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<form action="" method="post">
    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id"><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity"><br><br>
    <input type="submit" value="Add to Cart" name="add_to_cart">
</form>

<h2>Cart Contents:</h2>

<table border="1">
    <?php foreach ($_SESSION[CART_SESSION_NAME] as $item) { ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item[QUANTITY_KEY]; ?></td>
            <td><?php echo $item['price'] * $item[QUANTITY_KEY]; ?></td>
        </tr>
    <?php } ?>
</table>

<h2>Total Cost:</h2>
<p><?php echo get_total_cost(); ?></p>

<form action="" method="post">
    <input type="submit" value="Update Cart" name="update_cart">
</form>

</body>
</html>


<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'total' => 0,
    'count' => 0
);


function add_item_to_cart($item_id, $quantity) {
    global $cart;
    
    // Check if the item already exists in the cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            // Increment the quantity of the existing item
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to the cart
    $cart['items'][] = array(
        'id' => $item_id,
        'name' => 'Item ' . $item_id, // Replace with actual item name
        'price' => 10.99, // Replace with actual price
        'quantity' => $quantity
    );
    
    // Update the total and count of items in the cart
    $cart['total'] += $quantity * $cart['items'][count($cart['items']) - 1]['price'];
    $cart['count']++;
}


function remove_item_from_cart($item_id) {
    global $cart;
    
    // Find the index of the item to be removed
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            unset($item);
            break;
        }
    }
    
    // Update the total and count of items in the cart
    $cart['total'] -= $cart['items'][count($cart['items']) - 1]['price'];
    $cart['count]--;
}


function display_cart() {
    global $cart;
    
    echo "Cart Contents:<br>";
    foreach ($cart['items'] as &$item) {
        echo "$item[quantity] x " . $item['name'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
    }
    echo "Total: $" . number_format($cart['total'], 2);
}


<?php
session_start();

$cart = array(
    'items' => array(),
    'total' => 0,
    'count' => 0
);

function add_item_to_cart($item_id, $quantity) {
    global $cart;
    
    // ... (rest of the function remains the same)
}

function remove_item_from_cart($item_id) {
    global $cart;
    
    // ... (rest of the function remains the same)
}

function display_cart() {
    global $cart;
    
    // ... (rest of the function remains the same)
}

// Example usage:
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);

display_cart();

?>


class Cart {
    private $session_name = 'cart';

    public function __construct() {
        if (!isset($_SESSION[$this->session_name])) {
            $_SESSION[$this->session_name] = array();
        }
    }

    public function add($item, $quantity) {
        if (array_key_exists($item, $_SESSION[$this->session_name])) {
            $_SESSION[$this->session_name][$item] += $quantity;
        } else {
            $_SESSION[$this->session_name][$item] = $quantity;
        }
    }

    public function remove($item) {
        unset($_SESSION[$this->session_name][$item]);
    }

    public function update($item, $quantity) {
        if (array_key_exists($item, $_SESSION[$this->session_name])) {
            $_SESSION[$this->session_name][$item] = $quantity;
        }
    }

    public function getItems() {
        return $_SESSION[$this->session_name];
    }

    public function countItems() {
        return count($_SESSION[$this->session_name]);
    }

    public function getTotalCost() {
        $total = 0;
        foreach ($_SESSION[$this->session_name] as $item => $quantity) {
            // Assume we have a method to get the cost of an item
            $cost = self::getItemCost($item);
            $total += $cost * $quantity;
        }
        return $total;
    }

    private static function getItemCost($item) {
        // Replace with your own logic to get the cost of an item
        // For demonstration purposes, let's assume a random cost
        $cost = rand(1, 10);
        return $cost;
    }
}


$cart = new Cart();

// Add items to cart
$cart->add('item1', 2);
$cart->add('item2', 3);

// Get all items in cart
print_r($cart->getItems());

// Remove an item from cart
$cart->remove('item1');

// Update the quantity of an item
$cart->update('item2', 4);

// Print total cost of cart
echo $cart->getTotalCost();


// cart.php

session_start();

// Initialize an empty array if it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Set some default values (you can change these as needed)
$products = array(
    'product1' => array('name' => 'Product 1', 'price' => 9.99),
    'product2' => array('name' => 'Product 2', 'price' => 14.99),
    // Add more products here...
);

// Example function to add a product to the cart
function add_to_cart($product_id, $quantity) {
    global $products;
    
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $products[$product_id]['name'],
            'price' => $products[$product_id]['price'],
            'quantity' => (int)$quantity
        );
        
        // Update the cart total if it's a new product
        recalculate_cart_total();
    } else {
        // If the product is already in the cart, increment its quantity
        $_SESSION['cart'][$product_id]['quantity'] += (int)$quantity;
        recalculate_cart_total();
    }
}

// Example function to remove a product from the cart
function remove_from_cart($product_id) {
    unset($_SESSION['cart'][$product_id]);
    recalculate_cart_total();
}

// Helper function to calculate and store the cart total
function recalculate_cart_total() {
    global $products;
    
    // Clear any existing cart total
    if (isset($_SESSION['total'])) {
        unset($_SESSION['total']);
    }
    
    // Calculate the new cart total
    $cart_total = 0;
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $cart_total += ($product['price'] * $product['quantity']);
    }
    
    $_SESSION['total'] = $cart_total;
}


// Add 3 units of Product 1 to the cart
add_to_cart('product1', 3);


// Remove all instances of Product 2 from the cart
remove_from_cart('product2');


// cart_contents.php

session_start();

echo '<h2>Cart Contents:</h2>';

// Loop through each product in the cart
foreach ($_SESSION['cart'] as $product_id => $product) {
    echo '<p>' . $product['name'] . ' (' . $product['quantity'] . ' @ $' . number_format($product['price'], 2) . ') = $' . number_format(($product['price'] * $product['quantity']), 2) . '</p>';
}

echo '<hr>';

// Display the cart total
echo '<h3>Total: $' . number_format($_SESSION['total'], 2) . '</h3>';


class Cart {
    private $db;

    public function __construct() {
        $this->db = mysqli_connect('your_host', 'your_username', 'your_password', 'your_database');
    }

    public function add_item($user_id, $product_id, $quantity) {
        // Check if the product already exists in the cart
        $result = mysqli_query($this->db, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
        if (mysqli_num_rows($result) > 0) {
            // If the product exists, update its quantity
            mysqli_query($this->db, "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'");
        } else {
            // If the product does not exist, add it to the cart
            mysqli_query($this->db, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', $quantity)");
        }
    }

    public function remove_item($user_id, $product_id) {
        // Remove the item from the cart
        mysqli_query($this->db, "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
    }

    public function get_cart_items($user_id) {
        // Get all items in the cart for the given user
        $result = mysqli_query($this->db, "SELECT * FROM cart WHERE user_id = '$user_id'");
        return $result;
    }

    public function update_quantity($user_id, $product_id, $quantity) {
        // Update the quantity of a specific item in the cart
        mysqli_query($this->db, "UPDATE cart SET quantity = $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'");
    }
}


session_start();

if (!isset($_SESSION['cart'])) {
    // If the cart is not set, create it
    $_SESSION['cart'] = array();
}

$cart = new Cart();

// Add an item to the cart
$cart->add_item($_SESSION['user_id'], 1, 2);

// Remove an item from the cart
$cart->remove_item($_SESSION['user_id'], 1);

// Get all items in the cart
$items = $cart->get_cart_items($_SESSION['user_id']);

// Update the quantity of a specific item
$cart->update_quantity($_SESSION['user_id'], 1, 3);


// Start the session
session_start();

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to display cart contents
function display_cart() {
    if (!isset($_SESSION['cart'])) {
        echo "Cart is empty.";
    } else {
        echo "Your Cart Contents:<br>";
        foreach ($_SESSION['cart'] as $item) {
            echo "$item[quantity] x Item ID: $item[id]<br>";
        }
    }
}

// Add item to cart
if (isset($_POST['add_item'])) {
    add_item_to_cart($_POST['item_id'], $_POST['quantity']);
}

// Display cart contents
display_cart();


<!-- Form to add items to cart -->
<form action="cart.php" method="post">
    <input type="hidden" name="add_item" value="1">
    <label for="item_id">Item ID:</label>
    <input type="text" id="item_id" name="item_id"><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity"><br><br>
    <button type="submit">Add to Cart</button>
</form>

<!-- Display cart contents -->
<?php include 'cart.php'; ?>


<?php
session_start();
?>


$cart_item = array(
    'id' => '',
    'name' => '',
    'quantity' => 0,
    'price' => ''
);


function add_to_cart($product_id) {
    global $cart;
    
    // Check if product id is valid or not 
    if ($product_id != '') {
        
        // If product is already in the cart, increment its quantity
        foreach ($GLOBALS['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += 1;
                return true; // Item was found and added to cart.
            }
        }

        // Product not found in cart. Add it with quantity 1
        $new_item = array(
            'id' => $product_id,
            'name' => '', // You would normally fetch the product name here
            'quantity' => 1,
            'price' => '' // You would normally fetch the product price here
        );
        
        // Add new item to cart
        $GLOBALS['cart'][] = $new_item;
    }
    
    return false; // Product could not be added to cart.
}


function remove_from_cart($product_id) {
    global $cart;
    
    foreach ($GLOBALS['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($GLOBALS['cart'][$key]);
            return true; // Item was found and removed from cart.
        }
    }
    
    return false; // Product could not be removed from cart.
}


function display_cart() {
    global $cart;
    
    if (count($cart) > 0) {
        echo "You have the following items in your cart:";
        
        foreach ($cart as &$item) {
            // Fetch product name and price here to display them.
            // For example:
            echo "<br/>Product: " . $item['name'] . " - Quantity: " . $item['quantity'];
        }
    } else {
        echo 'Your cart is empty.';
    }
}


session_start();

$cart = array(); // Initialize the cart

// Add products to the cart
add_to_cart('product1');
add_to_cart('product2');

// Display cart contents
display_cart();


<?php
session_start();
?>


// Assuming $product_id and $quantity are defined somewhere (e.g., from a form submission or database query)

if (isset($_SESSION['cart'])) {
    // If cart is already set, append new item
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
} else {
    // Initialize the cart with the new item
    $_SESSION['cart'] = array(array('product_id' => $product_id, 'quantity' => $quantity));
}


// Example to list all items in the cart
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['product_id']} | Quantity: {$item['quantity']}
";
    }
}


// Example to increase quantity of a specific product by a certain amount
foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $target_product_id) {
        $item['quantity'] += $additional_quantity;
        break; // Exit loop since we've found the item and updated it
    }
}

// Example to remove an item from the cart
unset($_SESSION['cart'][array_search(array('product_id' => $target_product_id), $_SESSION['cart'])]);


unset($_SESSION['cart']);
session_destroy(); // Optionally destroy the session if needed


<?php
session_start();

// If this is the first page load or no cart exists yet,
// create an empty array for the cart.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add items to cart
function addItemToCart($productId, $quantity) {
    global $_SESSION;
    
    // Check if product already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // Update quantity instead of adding duplicate item.
            $item['quantity'] += $quantity;
            return;
        }
    }

    $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
}

// Example usage to add items
addItemToCart(1, 2); // Product ID 1 with quantity of 2 added.
addItemToCart(2, 3);

// Function to remove item from cart (simplified for brevity)
function removeItemFromCart($productId) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
        }
    }
}

// Example usage to display cart
echo "Your Cart:
";
foreach ($_SESSION['cart'] as $item) {
    echo "Product ID: {$item['id']} - Quantity: {$item['quantity']}
";
}
?>


// Initialize the session
session_start();

// Set the cart as an empty array by default
$_SESSION['cart'] = array();


function add_to_cart($product_id) {
    // Check if the product ID is already in the cart
    if (in_array($product_id, $_SESSION['cart'])) {
        echo "Product already exists in your cart!";
    } else {
        array_push($_SESSION['cart'], $product_id);
        echo "Product added to cart successfully!";
    }
}

function remove_from_cart($product_id) {
    // Check if the product ID exists in the cart
    if (in_array($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][array_search($product_id, $_SESSION['cart'])]);
        echo "Product removed from cart successfully!";
    } else {
        echo "Product does not exist in your cart.";
    }
}


function display_cart() {
    $cart = $_SESSION['cart'];
    echo "Your Cart Contents:</br>";
    foreach ($cart as $product_id) {
        // Retrieve product details from database (for example)
        $product_name = get_product_name($product_id);
        echo "$product_name</br>";
    }
}


// Add product with ID "123" to cart
add_to_cart(123);

// Display current cart contents
display_cart();

// Remove product with ID "123" from cart
remove_from_cart(123);


<?php

// Initialize the session
session_start();

// Set the cart as an empty array by default
$_SESSION['cart'] = array();

function add_to_cart($product_id) {
    if (in_array($product_id, $_SESSION['cart'])) {
        echo "Product already exists in your cart!";
    } else {
        array_push($_SESSION['cart'], $product_id);
        echo "Product added to cart successfully!";
    }
}

function remove_from_cart($product_id) {
    if (in_array($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][array_search($product_id, $_SESSION['cart'])]);
        echo "Product removed from cart successfully!";
    } else {
        echo "Product does not exist in your cart.";
    }
}

function display_cart() {
    $cart = $_SESSION['cart'];
    echo "Your Cart Contents:</br>";
    foreach ($cart as $product_id) {
        // Retrieve product details from database (for example)
        $product_name = get_product_name($product_id);
        echo "$product_name</br>";
    }
}

// Example usage
add_to_cart(123);

display_cart();

remove_from_cart(123);

?>


<?php
session_start();

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    // Get existing items in cart
    $cart = &$_SESSION['cart'];

    // Check if product already exists in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $cart[] = array(
        'id' => $product_id,
        'quantity' => $quantity,
    );
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    // Get existing items in cart
    $cart = &$_SESSION['cart'];

    // Remove product from cart
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
        }
    }
}

// Function to update quantity of item in cart
function update_quantity_in_cart($product_id, $new_quantity) {
    // Get existing items in cart
    $cart = &$_SESSION['cart'];

    // Find product in cart and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
        }
    }
}

// Add item to cart (example usage)
add_item_to_cart(1, 2);

// Remove item from cart (example usage)
remove_item_from_cart(1);

// Update quantity of item in cart (example usage)
update_quantity_in_cart(1, 3);
?>


<?php
session_start();

// Get existing items in cart
$cart = &$_SESSION['cart'];

// Display cart contents
echo '<h2>Cart Contents</h2>';
echo '<ul>';

// Loop through each item in cart and display its details
foreach ($cart as &$item) {
    echo '<li>';
    echo 'Product ID: ' . $item['id'];
    echo '</li>';
}

echo '</ul>';

// Display total quantity of items in cart
echo '<p>Total Quantity: ' . array_sum(array_column($cart, 'quantity')) . '</p>';
?>


add_item_to_cart(1, 2);


remove_item_from_cart(1);


update_quantity_in_cart(1, 3);


// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already exists, increment quantity and exit
        }
    }
    
    // If not, add new item to the cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to update a product's quantity in the cart
function update_cart($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find the index of the product in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return; // Product found, update quantity and exit
        }
    }
}

// Function to remove a product from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Remove the product from the cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // Product removed, exit
        }
    }
}

// Function to calculate the total cost of the items in the cart
function calculate_total() {
    global $_SESSION;
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * get_product_price($item['product_id']);
    }
    return $total;
}

// Function to display the contents of the cart
function display_cart() {
    global $_SESSION;
    
    echo '<h2>Your Cart</h2>';
    echo '<table border="1">';
    echo '<tr><th>Product ID</th><th>Quantity</th><th>Price</th></tr>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr><td>' . $item['product_id'] . '</td><td>' . $item['quantity'] . '</td><td>' . get_product_price($item['product_id']) . '</td></tr>';
    }
    echo '</table>';
}


<?php
session_start();

// Add a product to the cart (example)
add_to_cart(123, 2);

// Update a product's quantity in the cart (example)
update_cart(123, 3);

// Remove a product from the cart (example)
remove_from_cart(123);

// Display the contents of the cart
display_cart();

echo 'Total: $' . calculate_total();
?>


<?php
// Configuration
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add product to cart
function add_to_cart() {
  global $conn;
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][] = array('product_id' => $_POST['product_id'], 'quantity' => $_POST['quantity']);
  } else {
    $_SESSION['cart'] = array(array('product_id' => $_POST['product_id'], 'quantity' => $_POST['quantity']));
  }
}

// Function to update cart
function update_cart() {
  global $conn;
  foreach ($_SESSION['cart'] as &$item) {
    if (isset($_POST[$item['product_id']])) {
      $item['quantity'] = $_POST[$item['product_id']];
    }
  }
}

// Function to save cart to database
function save_cart_to_database() {
  global $conn;
  foreach ($_SESSION['cart'] as &$item) {
    if (!empty($item)) {
      $stmt = $conn->prepare("INSERT INTO `cart` (`user_id`, `product_id`, `quantity`) VALUES (?, ?, ?)");
      $stmt->bind_param("iis", $_SESSION['user_id'], $item['product_id'], $item['quantity']);
      $stmt->execute();
    }
  }
}

// Function to load cart from database
function load_cart_from_database() {
  global $conn;
  if (isset($_SESSION['cart'])) return $_SESSION['cart'];
  
  $stmt = $conn->prepare("SELECT * FROM `cart` WHERE `user_id` = ?");
  $stmt->bind_param("i", $_SESSION['user_id']);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $_SESSION['cart'][] = array('product_id' => $row['product_id'], 'quantity' => $row['quantity']);
    }
    
    return $_SESSION['cart'];
  } else {
    return array();
  }
}

// Function to delete product from cart
function delete_product_from_cart() {
  global $conn;
  foreach ($_SESSION['cart'] as $key => $item) {
    if (isset($_POST[$item['product_id']])) {
      unset($_SESSION['cart'][$key]);
    }
  }
}
?>


if (isset($_POST['save_cart'])) {
  save_cart_to_database();
}


session_start();


$cart = array();


function add_item_to_cart($product_id, $quantity) {
    global $cart;

    // Check if product already exists in cart
    if (isset($cart[$product_id])) {
        // Increment quantity of existing item
        $cart[$product_id]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $cart[$product_id] = array('quantity' => $quantity, 'price' => 0.00);
    }
}


$_SESSION['cart'] = $cart;


function display_cart() {
    global $cart;

    echo "<h2>Cart Contents:</h2>";
    foreach ($cart as $product_id => $item) {
        echo "Product ID: $product_id | Quantity: $item[quantity] | Price: $item[price]<br>";
    }
}


// Initialize session
session_start();

// Create cart array
$cart = array();

// Add item to cart (example)
add_item_to_cart(1, 2);

// Update cart session
$_SESSION['cart'] = $cart;

// Display cart contents
display_cart();


<?php
// Start the session
session_start();

// If the cart is not set, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add item to cart
function addToCart($productId, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$productId])) {
        // If the product is already in the cart, increment its quantity
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        // Otherwise, add it to the cart with the given quantity
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    global $_SESSION;
    unset($_SESSION['cart'][$productId]);
}

// Function to update quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $newQuantity;
    }
}

// Function to display the contents of the cart
function displayCart() {
    global $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        // Retrieve product information from database (e.g. using MySQL)
        $product = getProductInfo($productId);
        echo "  * $product[name] ($quantity): $" . number_format($product['price'] * $quantity, 2);
    }
}

// Example usage:
if (isset($_POST['add'])) {
    addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove'])) {
    removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update'])) {
    updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

// Display the cart contents
displayCart();

?>


<?php include 'cart.php'; ?>

<form action="cart.php" method="post">
    <input type="hidden" name="add" value="true">
    <select name="product_id">
        <?php foreach (getProducts() as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php } ?>
    </select>
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="remove" value="true">
    <select name="product_id">
        <?php foreach (getProducts() as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php } ?>
    </select>
    <button type="submit">Remove from Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="update" value="true">
    <select name="product_id">
        <?php foreach (getProducts() as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php } ?>
    </select>
    <input type="number" name="new_quantity" value="1">
    <button type="submit">Update Quantity</button>
</form>

<?php displayCart(); ?>


session_start();


$_SESSION['cart'] = array(); // initialize empty cart array
$_SESSION['total'] = 0; // initialize total cost to zero


function add_item_to_cart($product_id, $quantity) {
    // check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // increment the quantity of existing item
            $item['quantity'] += $quantity;
            return; // exit function early
        }
    }

    // add new product to cart
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );

    // update total cost
    $_SESSION['total'] += $product_id * $quantity;
}


function remove_item_from_cart($product_id) {
    global $_SESSION;

    // find and remove product from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break; // exit loop early
        }
    }

    // update total cost
    $_SESSION['total'] -= $product_id * array_sum(array_column($_SESSION['cart'], 'quantity'));
}


add_item_to_cart(1, 2); // add product with ID 1 in quantity 2
add_item_to_cart(3, 1); // add product with ID 3 in quantity 1

// print current cart contents
print_r($_SESSION['cart']);

// remove item from cart
remove_item_from_cart(1);

// print updated cart contents
print_r($_SESSION['cart']);


<?php

// enable sessions
session_start();

// create cart session variables
$_SESSION['cart'] = array();
$_SESSION['total'] = 0;

function add_item_to_cart($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // exit function early
        }
    }

    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );

    $_SESSION['total'] += $product_id * $quantity;
}

function remove_item_from_cart($product_id) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break; // exit loop early
        }
    }

    $_SESSION['total'] -= $product_id * array_sum(array_column($_SESSION['cart'], 'quantity'));
}

// example usage
add_item_to_cart(1, 2);
add_item_to_cart(3, 1);

print_r($_SESSION['cart']);

remove_item_from_cart(1);

print_r($_SESSION['cart']);


<?php
// Start or resume an existing session
if (!isset($_SESSION)) {
    session_start();
}
?>


<?php
// Assume we're fetching data from a database or a file.
// For simplicity, let's just use an array.
$products = [
    ['id' => 1, 'name' => 'Product A', 'price' => 10.99],
    ['id' => 2, 'name' => 'Product B', 'price' => 5.99],
];

// If the user's cart is not already in session, create it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

?>

<!-- Form to add items to the cart -->
<form action="" method="post">
    <?php foreach ($products as $product) : ?>
        <input type="checkbox" name="add_to_cart[]" value="<?php echo $product['id']; ?>">
        <label><?php echo $product['name']; ?> - Price: <?php echo $product['price']; ?></label>
        <br>
    <?php endforeach; ?>

    <button type="submit">Add to Cart</button>
</form>

<?php
// If the form has been submitted, add items to cart.
if (isset($_POST['add_to_cart'])) {
    foreach ($_POST['add_to_cart'] as $productId) {
        if (!in_array($productId, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $productId;
        }
    }
}
?>


<?php
// Display items in the user's cart.
if (isset($_SESSION['cart'])) :
    echo "Your Cart: ";
    foreach ($_SESSION['cart'] as $item) {
        // Assuming we have a function to get product details from an id.
        // For simplicity, let's just assume it exists and call it here.
        echo getProductDetails($item)['name'] . ' ';
    }
else :
    echo "Your cart is empty.";
endif;
?>

<?php
// Function to get product details by id (simplified example).
function getProductDetails($id) {
    // This should query a database or some data storage for the actual product.
    $products = [
        1 => ['name' => 'Product A', 'price' => 10.99],
        2 => ['name' => 'Product B', 'price' => 5.99],
    ];
    return $products[$id];
}
?>


session_start();


<?php

// Start the session
session_start();

// Function to add an item to the cart
function addToCart($itemId) {
    global $cart;
    // Check if the item is already in the cart, increment its quantity
    if (isset($cart[$itemId])) {
        $cart[$itemId] += 1;
    } else {
        // Item not found, add it with a count of 1
        $cart[$itemId] = 1;
    }
}

// Function to remove an item from the cart
function removeFromCart($itemId) {
    global $cart;
    if (isset($cart[$itemId])) {
        unset($cart[$itemId]);
    }
}

// Function to clear the entire cart
function clearCart() {
    global $cart;
    session_destroy();
    $cart = array(); // Reset the cart for next uses
}

// Initialize the cart as a session variable
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
$cart =& $_SESSION['cart']; // Reference the cart in the global scope

// Example usage:
if (isset($_POST['add']) && $_POST['add'] == "Add") {
    addToCart($_POST['itemId']);
} elseif (isset($_POST['remove'])) {
    removeFromCart($_POST['itemId']);
}

?>

<!-- HTML to display and interact with the cart -->
<form action="" method="post">
    <input type="hidden" name="add" value="Add"/>
    Item ID: <input type="text" name="itemId"/><br/>
    <button type="submit">Add to Cart</button>
</form>

<br/>

<!-- Displaying existing items in the cart -->
<?php
echo "Cart Contents:<br>";
foreach ($cart as $item => $quantity) {
    echo "$item: $quantity<br>";
}
?>

<form action="" method="post">
    <input type="hidden" name="remove"/>
    Item ID for removal: <input type="text" name="itemId"/><br/>
    <button type="submit">Remove from Cart</button>
</form>

<!-- Clear cart button -->
<button onclick="document.location.href='?clearCart=true'">Clear Cart</button>

<?php
if (isset($_GET['clearCart'])) {
    clearCart();
}
?>



<?php
session_start();
?>


class Cart {
    private $items = array();

    public function addItem($product_id, $quantity) {
        if (isset($this->items[$product_id])) {
            $this->items[$product_id] += $quantity;
        } else {
            $this->items[$product_id] = $quantity;
        }
    }

    public function removeItem($product_id) {
        if (isset($this->items[$product_id])) {
            unset($this->items[$product_id]);
        }
    }

    public function getItems() {
        return $this->items;
    }
}


<?php
// Include the Cart class
require_once 'cart.php';

// Create a new instance of the Cart class
$cart = new Cart();

// Set the cart session variables
$_SESSION['cart'] = $cart;

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    global $cart;
    $cart->addItem($product_id, $quantity);
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    global $cart;
    $cart->removeItem($product_id);
}

// Function to get items in cart
function getItemsInCart() {
    global $cart;
    return $cart->getItems();
}
?>


// Add item to cart
addItemToCart(1, 2); // adds 2 of product with id 1

// Remove item from cart
removeItemFromCart(1);

// Get items in cart
$items = getItemsInCart();
print_r($items);


<?php

// Step 1: Start Session
session_start();

// Define your database connection info, if needed
// $conn = mysqli_connect("localhost", "username", "password", "database");

// Cart variables (assuming we're using a simple key-value array for each item)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
    global $_SESSION;
    // Check if the item is already in cart to update quantity
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $item_id) {
            $product['quantity'] += $quantity; // Update quantity
            return true; // Return true to indicate item was found and updated.
        }
    }

    // If not in cart, add it with default quantity (1)
    $_SESSION['cart'][] = array('id' => $item_id, 'name' => $item_name, 'price' => $price, 'quantity' => $quantity);
    return true; // Item was added
}

function update_cart($item_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $item_id) {
            $product['quantity'] = $new_quantity;
            break; // No need to continue checking after updating
        }
    }
}

function remove_from_cart($item_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$product) {
        if ($product['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            break; // Remove the item from session
        }
    }

    // If the cart is empty after removing an item, clear it completely.
    if (count($_SESSION['cart']) === 0) {
        $_SESSION['cart'] = array();
    }
}

function display_cart() {
    global $_SESSION;
    $total = 0; // Initialize total for displaying
    echo "<h2>Shopping Cart:</h2><ul>";
    foreach ($_SESSION['cart'] as $product) {
        echo "<li>" . $product['name'] . " ({$product['quantity']} x {$product['price']}) = $" . ($product['price'] * $product['quantity']) . "</li>";
        $total += $product['price'] * $product['quantity'];
    }
    echo "<p>Total: $$total</p></ul>";
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['item_id'], $_POST['item_name'], $_POST['price']);
} elseif (isset($_POST['update_item'])) {
    update_cart($_POST['item_id'], $_POST['new_quantity']);
} elseif (isset($_POST['remove_item'])) {
    remove_from_cart($_POST['item_id']);
}

display_cart();

// Example HTML for adding items:
?>
<form action="" method="post">
    <input type="hidden" name="item_id" value="12345">
    <input type="text" name="item_name" value="Example Item" readonly>
    <input type="number" min="1" name="quantity" value="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<!-- For updating quantity -->
<form action="" method="post">
    <input type="hidden" name="item_id" value="<?php echo $_SESSION['cart'][0]['id']; ?>">
    <label>Quantity:</label>
    <input type="number" min="1" name="new_quantity">
    <button type="submit" name="update_item">Update Quantity</button>
</form>

<!-- For removing item -->
<form action="" method="post">
    <input type="hidden" name="item_id" value="<?php echo $_SESSION['cart'][0]['id']; ?>">
    <button type="submit" name="remove_item">Remove Item</button>
</form>


<?php
// Start the session
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it does, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add a new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to update an item in the cart
function update_cart($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update the quantity
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            // Remove the item from the cart
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to calculate the total cost of items in the cart
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        $price = get_product_price($item['id']); // Assuming a function to get product price
        $total += $price * $item['quantity'];
    }
    return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['update_cart'])) {
    update_cart($_POST['product_id'], $_POST['new_quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    remove_from_cart($_POST['product_id']);
}

// Display the cart contents
echo "Cart Contents:
";
foreach ($_SESSION['cart'] as $item) {
    echo "Product ID: $item[id], Quantity: $item[quantity]
";
}

// Calculate and display the total cost
echo "
Total Cost: $" . calculate_total();
?>


<?php
session_start();

// Check if cart is already set in session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($item_id, $quantity) {
    global $_SESSION;
    // Check if item is already in cart, if so increment quantity
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Add new item to cart with initial quantity
        $_SESSION['cart'][$item_id] = array(
            'product_id' => $item_id,
            'name' => '', // You can retrieve the product name from your database if needed
            'price' => 0, // You can retrieve the price from your database if needed
            'quantity' => $quantity
        );
    }
}

// Function to remove item from cart
function removeItemFromCart($item_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$item_id]);
}

// Function to update quantity of an item in cart
function updateQuantity($item_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}

// Add example items to cart
addItemToCart(1, 2);
addItemToCart(3, 1);

// Print out contents of cart
echo "Cart contents:
";
foreach ($_SESSION['cart'] as $item) {
    echo $item['product_id'] . ": " . $item['quantity'] . "
";
}


function addItemToCart($item_id, $quantity) {
    global $_SESSION;
    // Retrieve product details from database...
    $product = getProductDetails($item_id);
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = array(
            'product_id' => $item_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        );
    }
}


function getProductDetails($product_id) {
    // Connect to your database...
    $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute(array($product_id));
    return $stmt->fetch();
}


<?php

// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// Set the cart session key
define('CART_SESSION_KEY', 'cart');

?>


<?php

function add_to_cart($product_id, $quantity) {
    // Get the current session
    global $_SESSION;
    
    // Check if the product is already in the cart
    if (isset($_SESSION[CART_SESSION_KEY][$product_id])) {
        // If it is, increment the quantity
        $_SESSION[CART_SESSION_KEY][$product_id] += $quantity;
    } else {
        // If not, add it to the cart with the specified quantity
        $_SESSION[CART_SESSION_KEY][$product_id] = $quantity;
    }
}

function update_cart($product_id, $new_quantity) {
    global $_SESSION;
    
    // Check if the product is in the cart
    if (isset($_SESSION[CART_SESSION_KEY][$product_id])) {
        // Update the quantity
        $_SESSION[CART_SESSION_KEY][$product_id] = $new_quantity;
    }
}

function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Check if the product is in the cart
    if (isset($_SESSION[CART_SESSION_KEY][$product_id])) {
        // Remove it from the cart
        unset($_SESSION[CART_SESSION_KEY][$product_id]);
    }
}

function get_cart() {
    global $_SESSION;
    
    return $_SESSION[CART_SESSION_KEY];
}

?>


<?php

// Include the session and functions files
include 'session.php';
include 'functions.php';

// Initialize the product ID and quantity for a new item to be added to the cart
$product_id = 1;
$quantity = 2;

// Add the item to the cart
add_to_cart($product_id, $quantity);

// Get the current cart contents
$cart_contents = get_cart();

print_r($cart_contents);

?>


<?php
// Set the session name
$_SESSION['cart'] = array();

// Function to add item to cart
function addToCart($product_id, $quantity) {
  global $_SESSION;
  
  // Check if product is already in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] += $quantity;
      return;
    }
  }
  
  // Add new item to cart
  $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function removeFromCart($product_id) {
  global $_SESSION;
  
  // Remove item from cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      unset($item);
      return;
    }
  }
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $quantity) {
  global $_SESSION;
  
  // Update quantity of item in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] = $quantity;
      return;
    }
  }
}

// Function to get total cost of items in cart
function getTotalCost() {
  global $_SESSION;
  
  // Calculate total cost
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
  addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
  removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
  updateQuantity($_POST['product_id'], $_POST['quantity']);
}

// Display cart contents
?>
<table>
  <tr>
    <th>Product</th>
    <th>Quantity</th>
    <th>Total Cost</th>
  </tr>
  <?php foreach ($_SESSION['cart'] as $item) : ?>
  <tr>
    <td><?php echo $item['name']; ?></td>
    <td><?php echo $item['quantity']; ?></td>
    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
  </tr>
  <?php endforeach; ?>
</table>

Total Cost: <?php echo number_format(getTotalCost(), 2); ?>

<form action="" method="post">
  <input type="hidden" name="add_to_cart" value="1">
  <label>Product ID:</label>
  <input type="text" name="product_id"><br><br>
  <label>Quantity:</label>
  <input type="text" name="quantity"><br><br>
  <button type="submit">Add to Cart</button>
</form>

<form action="" method="post">
  <input type="hidden" name="remove_from_cart" value="1">
  <label>Product ID:</label>
  <input type="text" name="product_id"><br><br>
  <button type="submit">Remove from Cart</button>
</form>

<form action="" method="post">
  <input type="hidden" name="update_quantity" value="1">
  <label>Product ID:</label>
  <input type="text" name="product_id"><br><br>
  <label>New Quantity:</label>
  <input type="text" name="quantity"><br><br>
  <button type="submit">Update Quantity</button>
</form>


<?php
// Start the session
session_start();

// Include cart functions
include 'cart.php';

// Example usage:
if (isset($_POST['add_to_cart'])) {
  addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
  removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
  updateQuantity($_POST['product_id'], $_POST['quantity']);
}

// Display cart contents
?>
<!DOCTYPE html>
<html>
<head>
  <title>Cart Example</title>
</head>
<body>
  <?php include 'cart.php'; ?>
</body>
</html>


<?php
// Start the session
session_start();

// If the cart array doesn't exist, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id) {
    // Get product details (replace with your own database query)
    $product = get_product_details($product_id);
    
    // Add product to cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'quantity' => 1,
            'price' => $product['price'],
            'name' => $product['name']
        );
    } else {
        // If product is already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get cart contents
function get_cart_contents() {
    return $_SESSION['cart'];
}

// Example usage:
add_to_cart(1); // Add product 1 to cart
remove_from_cart(2); // Remove product 2 from cart
update_quantity(3, 5); // Update quantity of product 3 in cart

// Display cart contents
print_r(get_cart_contents());
?>


<?php
function get_product_details($product_id) {
    // Replace with your own database query to retrieve product details
    $db = mysqli_connect("localhost", "username", "password", "database");
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db, $query);
    $product = mysqli_fetch_assoc($result);
    return $product;
}
?>


<?php

// Cart constants
define('CART_SESSION_NAME', 'user_cart');

function initCart() {
    // Initialize the cart session if it doesn't exist
    if (!isset($_SESSION[CART_SESSION_NAME])) {
        $_SESSION[CART_SESSION_NAME] = array();
    }
}

function addProductToCart($productId, $quantity) {
    // Add a product to the cart with a specific quantity
    $cart = &$_SESSION[CART_SESSION_NAME];
    if (array_key_exists($productId, $cart)) {
        // If the product is already in the cart, increment its quantity
        $cart[$productId] += $quantity;
    } else {
        // Otherwise, add it to the cart with the specified quantity
        $cart[$productId] = $quantity;
    }
}

function removeProductFromCart($productId) {
    // Remove a product from the cart
    $cart = &$_SESSION[CART_SESSION_NAME];
    if (array_key_exists($productId, $cart)) {
        unset($cart[$productId]);
    }
}

function updateProductQuantityInCart($productId, $newQuantity) {
    // Update the quantity of an existing product in the cart
    $cart = &$_SESSION[CART_SESSION_NAME];
    if (array_key_exists($productId, $cart)) {
        $cart[$productId] = $newQuantity;
    }
}

function getProductsInCart() {
    // Retrieve all products currently in the cart
    return $_SESSION[CART_SESSION_NAME];
}

?>


<?php

require_once 'cart.php';

initCart();

// Get the products currently in the cart
$products = getProductsInCart();

?>

<h2>My Cart</h2>

<table>
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $productId => $quantity) { ?>
        <tr>
            <td><?php echo $productId; ?></td>
            <td><?php echo $quantity; ?></td>
            <td>$<?php // calculate total price based on product data ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<p><a href="#">Update Cart</a></p>


<?php
// Initialize the session
session_start();
?>


// Define the cart structure
$cart = array(
    'products' => array(),
    'total' => 0,
    'subtotal' => 0,
    'tax_rate' => 0.10 // 10% tax rate for example
);


// Function to add product to cart
function add_product_to_cart($product_id, $quantity) {
    global $cart;

    // Check if product is already in cart
    foreach ($cart['products'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update existing item's quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new product to cart
    $cart['products'][] = array(
        'id' => $product_id,
        'name' => '', // Replace with actual product name
        'price' => 0, // Replace with actual price
        'quantity' => $quantity
    );

    // Update subtotal and total
    $cart['subtotal'] += $product_id * $quantity;
    $cart['total'] = round($cart['subtotal'] + ($cart['subtotal'] * $cart['tax_rate']), 2);
}


// Function to update cart
function update_cart() {
    global $cart;

    // Calculate subtotal and total again
    $cart['subtotal'] = array_sum(array_column($cart['products'], 'price')) * count($cart['products']);
    $cart['total'] = round($cart['subtotal'] + ($cart['subtotal'] * $cart['tax_rate']), 2);
}


// Function to save cart to session
function save_cart_to_session() {
    global $cart;

    // Encode cart array as JSON
    $_SESSION['cart'] = json_encode($cart);
}


// Function to load cart from session
function load_cart_from_session() {
    global $cart;

    // Decode cart array from JSON
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
    }
}


// Initialize session and load cart data
session_start();
load_cart_from_session();

// Add product to cart
add_product_to_cart(123, 2);

// Update cart
update_cart();

// Save cart to session
save_cart_to_session();

// Print cart contents
print_r($cart);


<?php
session_start();

// Check if the cart session exists, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Function to update cart item quantity
function update_cart_item($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// Function to get cart contents
function get_cart_contents() {
    global $_SESSION;
    return $_SESSION['cart'];
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
} elseif (isset($_POST['update_item'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    update_cart_item($product_id, $new_quantity);
} elseif (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Display cart contents
echo '<h2>Cart Contents:</h2>';
$cart_contents = get_cart_contents();
foreach ($cart_contents as $product_id => $item) {
    echo 'Product ID: ' . $product_id . ', Quantity: ' . $item['quantity'] . '<br>';
}
?>


<?php
include 'cart.php';

// Example usage:
?>
<form action="cart.php" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    <select name="product_id">
        <?php foreach ($products as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php } ?>
    </select>
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="update_item" value="1">
    <select name="product_id">
        <?php foreach ($products as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php } ?>
    </select>
    <input type="number" name="new_quantity" value="1">
    <button type="submit">Update Quantity</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="remove_item" value="1">
    <select name="product_id">
        <?php foreach ($products as $product) { ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php } ?>
    </select>
    <button type="submit">Remove from Cart</button>
</form>

<?php
echo '<h2>Cart Contents:</h2>';
$cart_contents = get_cart_contents();
foreach ($cart_contents as $product_id => $item) {
    echo 'Product ID: ' . $product_id . ', Quantity: ' . $item['quantity'] . '<br>';
}
?>


<?php
session_start();

// Include database connection settings or use PDO for security reasons
// For example:
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database using PDO (more secure)
try {
    $pdo = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUsername, $dbPassword);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Sample product array for demonstration purposes
$products = [
    [ 'id' => 1, 'name' => 'Product A', 'price' => 19.99 ],
    [ 'id' => 2, 'name' => 'Product B', 'price' => 9.99 ]
];

// Function to add product to cart
function addToCart($productId) {
    global $pdo;
    
    // Check if product exists in database for demonstration purposes
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id=:id');
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $productData = $stmt->fetch();
    
    if (!$productData) {
        echo "Product not found.";
        return;
    }
    
    // Create session variable for cart items
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product is already in cart to avoid duplicates
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            echo "Product already in cart.";
            return;
        }
    }
    
    // Add product to cart
    $_SESSION['cart'][] = [
        'id' => $productData['id'],
        'name' => $productData['name'],
        'price' => $productData['price']
    ];
}

// Function to view cart contents
function viewCart() {
    global $pdo;
    
    // Retrieve products from database for demonstration purposes (in a real scenario, you would directly use the stored session data)
    if (!isset($_SESSION['cart'])) {
        echo "Your cart is empty.";
        return;
    }
    
    foreach ($_SESSION['cart'] as $item) {
        echo "Product Name: {$item['name']} | Price: {$item['price']}<br>";
    }
}

// Test adding products to the cart
foreach ($products as $product) {
    addToCart($product['id']);
}
?>


session_start();


$_SESSION['cart'] = array();


function add_to_cart($product_id, $name, $price, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            // If it's already in the cart, increment the quantity
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            return;
        }
    }

    // If not, add the product to the cart
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    );
}


function remove_from_cart($product_id) {
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            // If it's in the cart, unset the item
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function update_quantity($product_id, $new_quantity) {
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            // If it's in the cart, update the quantity
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;
            return;
        }
    }
}


function display_cart() {
    echo '<h2>Cart</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<p>' . $item['name'] . ' x' . $item['quantity'] . ' = $' . number_format($item['price'] * $item['quantity'], 2) . '</p>';
    }
}


// Start a session
session_start();

// Add items to the cart
add_to_cart(1, 'Product 1', 9.99, 2);
add_to_cart(2, 'Product 2', 19.99, 1);

// Display the cart
display_cart();

// Remove an item from the cart
remove_from_cart(1);

// Update the quantity of an item in the cart
update_quantity(2, 3);

// Display the updated cart
display_cart();


// cart.php

// Initialize the session
session_start();


// In cart.php

$cart = array(
    'items' => array(),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
);


// In cart.php

function add_item($item_id, $quantity) {
    global $cart;
    
    // Check if the item is already in the cart
    foreach ($cart['items'] as &$item) {
        if ($item[0] == $item_id) {
            // Update the quantity
            $item[1] += $quantity;
            return; // exit early to prevent duplicates
        }
    }
    
    // Add new item to the cart
    $cart['items'][] = array($item_id, $quantity);
    $cart['subtotal'] += ($item_id * $quantity); // assuming $item_id is the price of each item
}


// In any PHP file

// Add an item to the cart
add_item($item_id, $quantity);

// Update the session
$_SESSION['cart'] = $cart;


// In any PHP file

if (isset($_SESSION['cart'])) {
    $cart_data = $_SESSION['cart'];
    
    // Print cart contents
    echo "Cart Contents:
";
    foreach ($cart_data['items'] as $item) {
        echo "- " . $item[0] . " x " . $item[1] . "
";
    }
    
    // Calculate subtotal, tax, and total
    $subtotal = array_sum(array_column($cart_data['items'], 0)) * count($cart_data['items']);
    $tax = ($subtotal * 0.08); // assume 8% sales tax
    $total = $subtotal + $tax;
    
    echo "Subtotal: $" . number_format($subtotal) . "
";
    echo "Tax (8%): $" . number_format($tax) . "
";
    echo "Total: $" . number_format($total) . "
";
}


<?php
session_start();
?>


// If the session doesn't exist or hasn't been initiated yet, start it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id) {
    global $_SESSION;
    
    // Assuming $quantity is provided when adding the item
    if (isset($_POST['quantity']) && !empty($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
        
        // If the item exists, update its quantity. Otherwise, add it to the cart.
        if (array_key_exists($item_id, $_SESSION['cart'])) {
            $_SESSION['cart'][$item_id] += $quantity;
        } else {
            $_SESSION['cart'][$item_id] = array('quantity' => $quantity); // Simplified data structure for this example
        }
    }
}


// Function to display cart contents
function show_cart_contents() {
    global $_SESSION;
    
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $product_id => $details) {
            echo "<li>$product_id - Quantity: " . $details['quantity'] . "</li>";
        }
        echo "</ul>";
    }
}


// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
        
        // If all items are removed, clear the cart array
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}


<?php
session_start();

// Add item to cart function call
if (isset($_POST['add_to_cart'])) {
    add_item_to_cart($_POST['product_id']);
}

// Remove item from cart function call
if (isset($_POST['remove_from_cart'])) {
    remove_item_from_cart($_POST['item_id']);
}

// Displaying the cart contents
show_cart_contents();
?>


// cart.php


<?php
session_start();

// Set default cart values
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(
        'items' => array(),
        'total' => 0,
        'tax_rate' => 0.08, // Default tax rate (8%)
        'shipping_cost' => 5.00 // Default shipping cost
    );
}
?>


function add_item($item_id, $quantity) {
    global $_SESSION;

    // Find existing item or create new one
    if (isset($_SESSION['cart']['items'][$item_id])) {
        $_SESSION['cart']['items'][$item_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart']['items'][$item_id] = array(
            'price' => 19.99, // Default price
            'quantity' => $quantity,
            'name' => 'Item Name'
        );
    }

    // Update total and tax calculations
    recalculate_cart();
}


function remove_item($item_id) {
    global $_SESSION;

    if (isset($_SESSION['cart']['items'][$item_id])) {
        unset($_SESSION['cart']['items'][$item_id]);

        // Update total and tax calculations
        recalculate_cart();
    }
}


function recalculate_cart() {
    global $_SESSION;

    // Calculate total cost
    $total = 0;
    foreach ($_SESSION['cart']['items'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    $_SESSION['cart']['total'] = round($total, 2);

    // Calculate tax amount
    $tax_amount = $total * $_SESSION['cart']['tax_rate'];
    $_SESSION['cart']['tax_amount'] = round($tax_amount, 2);

    // Calculate shipping total
    $_SESSION['cart']['shipping_total'] = round($_SESSION['cart']['shipping_cost'], 2);
}


add_item(1, 2); // Add 2 items with ID 1


remove_item(1);


echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';


session_start();


// Set an empty cart if none exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


function addItemToCart($itemId) {
    // Assuming $itemId is unique for each product and stored in your database.
    if (!isset($_SESSION['cart'][$itemId])) {
        $_SESSION['cart'][$itemId] = 1; // Defaults to one item
    } else {
        $_SESSION['cart'][$itemId]++;
    }
}


function removeFromCart($itemId) {
    if (isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
        $_SESSION['cart'] = array_filter($_SESSION['cart']);
    }
}


function updateCartItemQuantity($itemId, $quantity) {
    if (isset($_SESSION['cart'][$itemId]) && is_numeric($quantity)) {
        $_SESSION['cart'][$itemId] = max(1, $quantity); // Ensures quantity doesn't go below 1.
    }
}


function clearCart() {
    unset($_SESSION['cart']);
    $_SESSION['cart'] = [];
}


session_start();

if (isset($_POST['action']) && $_POST['action'] == 'add') {
    addItemToCart($_POST['product_id']);
} elseif ($_POST['action'] == 'remove') {
    removeFromCart($_POST['product_id']);
} elseif ($_POST['action'] == 'update') {
    updateCartItemQuantity($_POST['product_id'], $_POST['quantity']);
}

// Displaying the cart
echo "Your Cart:
";
foreach ($_SESSION['cart'] as $item => $quantity) {
    echo "$item: $quantity
";
}


<?php
// For PHP >= 5.4
// session_start();

// Include session.php if you have it (a wrapper for session functions)
require 'path/to/your/session.php';
?>


<?php

class Cart {
    private $sessionName = "cart";
    public $items;

    public function __construct() {
        if (isset($_SESSION[$this->sessionName])) {
            $this->items = unserialize($_SESSION[$this->sessionName]);
        } else {
            $this->items = array();
        }
    }

    public function addItem($id, $name, $price) {
        if (!isset($this->items[$id])) {
            $this->items[$id] = array(
                "name" => $name,
                "price" => $price,
                "quantity" => 1
            );
        } else {
            $this->items[$id]["quantity"]++;
        }
        
        $_SESSION[$this->sessionName] = serialize($this->items);
    }

    public function displayCart() {
        foreach ($this->items as $item) {
            echo "Product: $item[name] | Price: $" . number_format($item["price"]) . " x" . $item["quantity"] . " = $" . number_format($item["price"] * $item["quantity"]) . "<br>";
        }
    }

    public function clearCart() {
        unset($_SESSION[$this->sessionName]);
    }
}

// Usage example
$cart = new Cart();

if (isset($_POST['add'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    $cart->addItem($id, $name, $price);
}

?>

<!-- Displaying the cart content -->
<h2>Your Cart:</h2>
<?php $cart->displayCart(); ?>

<!-- Adding items to cart form example -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="id" value="1">
    <input type="text" name="name" value="Product 1">
    <input type="number" name="price" step=".01" min="0.01" value="19.99">
    <button type="submit" name="add">Add to Cart</button>
</form>

<!-- Clear cart button example -->
<button onclick="<?php $cart->clearCart(); ?>">Clear Cart</button>


// config.php

class Config {
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'shopping_cart';

    // Other configurations...
}


// Cart.php

class Cart {
    private $cart_id;
    private $session;

    public function __construct() {
        $this->cart_id = uniqid();
        $this->session = session_status() === PHP_SESSION_ACTIVE ? session_id() : 'no_session';
    }

    public function add_item($product_id, $quantity) {
        // Check if product is already in cart
        if ($this->is_product_in_cart($product_id)) {
            $current_quantity = $this->get_item_quantity($product_id);
            $new_quantity = (int)$current_quantity + (int)$quantity;
            $this->update_item_quantity($product_id, $new_quantity);
        } else {
            // Add new product to cart
            $this->set_item_in_cart($product_id, $quantity);
        }
    }

    public function remove_item($product_id) {
        $this->delete_item_from_cart($product_id);
    }

    public function update_item_quantity($product_id, $new_quantity) {
        // Update quantity in cart
        // This would require a database connection...
        // For simplicity, we'll just store it in session for now.
        $_SESSION['cart_items'][$this->cart_id][$product_id] = (int)$new_quantity;
    }

    public function get_cart_contents() {
        if (!isset($_SESSION['cart_items'][$this->cart_id])) {
            return array();
        }
        return $_SESSION['cart_items'][$this->cart_id];
    }

    private function is_product_in_cart($product_id) {
        // Check if product is already in cart
        return isset($_SESSION['cart_items'][$this->cart_id][$product_id]);
    }

    private function set_item_in_cart($product_id, $quantity) {
        $_SESSION['cart_items'][$this->cart_id][$product_id] = (int)$quantity;
    }

    private function delete_item_from_cart($product_id) {
        unset($_SESSION['cart_items'][$this->cart_id][$product_id]);
    }

    private function get_item_quantity($product_id) {
        return $_SESSION['cart_items'][$this->cart_id][$product_id];
    }
}


// index.php

require_once 'config.php';
require_once 'Cart.php';

$cart = new Cart();

// Add item to cart
$cart->add_item(1, 2); // Product ID: 1, Quantity: 2

// Remove item from cart
$cart->remove_item(1);

// Get cart contents
$contents = $cart->get_cart_contents();
print_r($contents);


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_item_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increase quantity if product is already in cart
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function update_item_in_cart($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // Item not found in cart
    echo "Item not found in cart.";
}

function remove_item_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }

    // Item not found in cart
    echo "Item not found in cart.";
}

function display_cart() {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo $item['id'] . " x " . $item['quantity'] . "<br>";
    }
}


add_item_to_cart(1, 2); // Add product with ID 1 in quantity of 2


update_item_in_cart(1, 3); // Update product with ID 1 to quantity of 3


remove_item_from_cart(1); // Remove product with ID 1 from cart


display_cart(); // Output: Cart Contents:
                 // Product ID x Quantity<br>
                 // ...


<?php

// Initialize the session.
session_start();

// Function to add items to the cart.
function addToCart($itemId, $quantity) {
    global $_SESSION;
    
    // Check if the item is already in the cart and update quantity accordingly.
    if (isset($_SESSION['cart'][$itemId])) {
        $_SESSION['cart'][$itemId] += $quantity;
    } else {
        // If not, add it with its initial quantity.
        $_SESSION['cart'][$itemId] = $quantity;
    }
}

// Function to remove items from the cart.
function removeFromCart($itemId) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming $itemId and $quantity are POSTed values.
    addToCart($_POST["item_id"], $_POST["quantity"]);
    
    // To update existing items in the cart (e.g., increase/decrease quantity).
    if (!empty($_POST["update_id"])) {
        addToCart($_POST["update_id"], $_POST["new_quantity"]);
    }
}

// Example usage to remove an item.
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["remove_item"])) {
    removeFromCart($_GET["remove_item"]);
    
    // After modifying the cart, consider redirecting back to the main page
    // or whatever view is displaying the current state of the cart.
}

// Display cart content.
if (isset($_SESSION['cart'])) {
    echo "<h2>Cart Content:</h2>";
    foreach ($_SESSION['cart'] as $itemId => $quantity) {
        echo "Item: $itemId Quantity: $quantity<br>";
    }
} else {
    echo "<p>No items in the cart.</p>";
}

// Remember to always start your PHP files with session_start() for proper functionality.
?>


<?php
// Start the session
session_start();

// Set the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($id, $name, $price) {
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $id) {
            // If it exists, increment the quantity
            $item['quantity']++;
            return;
        }
    }

    // If not, add new item to cart
    $_SESSION['cart'][] = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
}

// Function to remove item from cart
function removeItemFromCart($id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Add some items to the cart for demonstration purposes
addItemToCart(1, 'Product 1', 10.99);
addItemToCart(2, 'Product 2', 9.99);

// Display the contents of the cart
echo "<h2>Shopping Cart</h2>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th></tr>";

foreach ($_SESSION['cart'] as $item) {
    echo "<tr>";
    echo "<td>" . $item['id'] . "</td>";
    echo "<td>" . $item['name'] . "</td>";
    echo "<td>" . $item['price'] . "</td>";
    echo "<td>" . $item['quantity'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Link to remove an item from the cart
echo '<a href="removeItem.php?id=1">Remove Product 1</a> ';


<?php
session_start();

// Get the ID of the item to be removed
$id = $_GET['id'];

// Remove the item from the cart
removeItemFromCart($id);

// Redirect back to the cart page
header("Location: cart.php");
exit();


session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_item($product_id, $quantity = 1) {
    global $_SESSION;

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => 0);
    }

    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
}


function view_cart() {
    global $_SESSION;

    $cart = $_SESSION['cart'];
    $output = '';

    foreach ($cart as $product_id => $item) {
        $output .= '<li>Product ID: ' . $product_id . ', Quantity: ' . $item['quantity'] . '</li>';
    }

    return $output;
}


function remove_item($product_id) {
    global $_SESSION;

    unset($_SESSION['cart'][$product_id]);
}


<?php
require 'cart.php';

// Add items to the cart
add_item(1, 2); // Product ID 1 with quantity 2
add_item(2, 3); // Product ID 2 with quantity 3

// View cart contents
echo view_cart();

// Remove an item from the cart
remove_item(1);

// View cart contents again
echo view_cart();
?>


<?php
// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    echo "You must be logged in to access your cart.";
    exit;
}

// Initialize the cart array
$cart = array();

// Set the session variables
$_SESSION['cart'] = $cart;

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    global $_SESSION;
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            // Increase the quantity of the existing item
            $cart[$key]['quantity'] += $quantity;
            break;
        }
    }

    // Add new product to cart if it's not already there
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = array(
            'product_id' => $product_id,
            'name' => '', // You'll need to get the product name from your database
            'quantity' => $quantity,
            'price' => 0.00, // You'll need to get the product price from your database
        );
    }
}

// Function to remove item from cart
function removeFromCart($product_id) {
    global $_SESSION;
    global $cart;

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    global $_SESSION;
    global $cart;

    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add item to cart
    addItemToCart($_POST['product_id'], $_POST['quantity']);
} else {
    // Display cart contents
    echo "Your Cart Contents:
";
    foreach ($cart as $item) {
        echo "$item[name] x $item[quantity] = $" . number_format($item['price'] * $item['quantity'], 2) . "
";
    }
}


<?php
// Initialize the session
session_start();

// Define the cart array
$cart = array(
    'items' => array(),
    'total' => 0,
);

// Check if the cart is already in the session
if (!isset($_SESSION['cart'])) {
    // If not, set it to our default cart array
    $_SESSION['cart'] = $cart;
}

// Function to add an item to the cart
function add_item($id, $name, $price) {
    global $cart;
    $item = array(
        'id' => $id,
        'name' => $name,
        'price' => $price,
    );
    // Check if the item is already in the cart
    foreach ($cart['items'] as &$i) {
        if ($i['id'] == $id) {
            // If it is, increment its quantity
            $i['quantity']++;
            return;
        }
    }
    // If not, add it to the cart
    $item['quantity'] = 1;
    $cart['items'][] = $item;
    $cart['total'] += $price;
}

// Function to remove an item from the cart
function remove_item($id) {
    global $cart;
    foreach ($cart['items'] as &$i) {
        if ($i['id'] == $id) {
            // Remove the item from the cart
            unset($i);
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function update_quantity($id, $quantity) {
    global $cart;
    foreach ($cart['items'] as &$i) {
        if ($i['id'] == $id) {
            // Update the quantity of the item
            $i['quantity'] = $quantity;
            return;
        }
    }
}

// Function to get the cart contents
function get_cart() {
    global $cart;
    return $cart;
}

// Add an example item to the cart
add_item(1, 'Example Item', 9.99);

// Print the current state of the cart
print_r(get_cart());

?>


// Add a new item to the cart
add_item(2, 'New Item', 19.99);


// Remove the first item from the cart
remove_item(1);


// Update the quantity of the second item to 3
update_quantity(2, 3);


// Print the current state of the cart
print_r(get_cart());


// cart.php

function init_cart() {
    // Initialize an empty cart array if it doesn't exist
    $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
}

function add_to_cart($product_id, $quantity) {
    // Add or update product in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function view_cart() {
    // Return the contents of the cart
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
}


// product.php

class Product {
    public $id;
    public $name;
    public $price;

    function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}


// index.php

require_once 'cart.php';
require_once 'product.php';

init_cart();

$product1 = new Product(1, 'Product A', 10.99);
$product2 = new Product(2, 'Product B', 5.99);

add_to_cart($product1->id, 2); // Add 2 of product A
add_to_cart($product2->id, 3); // Add 3 of product B

echo "Cart Contents:
";
print_r(view_cart());


Cart Contents:
Array
(
    [1] => 2
    [2] => 3
)


<?php
session_start();

// Set default cart values if not set before
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
function add_item_to_cart($product_id, $quantity) {
    // Get current product details
    $product = new Product($product_id);
    
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product->getId()) {
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $product->getId(),
        'name' => $product->getName(),
        'price' => $product->getPrice(),
        'quantity' => $quantity,
    );
}
?>


<?php
function update_item_quantity($item_id, $new_quantity) {
    // Find the item to update
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}
?>


<?php
function remove_item_from_cart($item_id) {
    // Find and remove the item
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}
?>


<?php
foreach ($_SESSION['cart'] as $item) {
    echo "Item: {$item['name']} (${$item['price']}) - Quantity: {$item['quantity']}
";
}
?>


<?php
session_start();

// Set default cart values if not set before
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_item_to_cart($product_id, $quantity) {
    // Code as above...
}

function update_item_quantity($item_id, $new_quantity) {
    // Code as above...
}

function remove_item_from_cart($item_id) {
    // Code as above...
}

// Example usage:
$productId = 1;
$quantity = 2;

add_item_to_cart($productId, $quantity);
update_item_quantity($productId, 3);

echo "Cart Contents:
";
foreach ($_SESSION['cart'] as $item) {
    echo "Item: {$item['name']} (${$item['price']}) - Quantity: {$item['quantity']}
";
}
?>


class Cart {
    private $items;

    public function __construct() {
        $this->items = array();
    }

    public function add($item, $quantity) {
        if (isset($this->items[$item])) {
            $this->items[$item] += $quantity;
        } else {
            $this->items[$item] = $quantity;
        }
    }

    public function remove($item) {
        unset($this->items[$item]);
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotalQuantity() {
        $total = 0;
        foreach ($this->items as $item => $quantity) {
            $total += $quantity;
        }
        return $total;
    }
}


session_start();

$cart = new Cart();
$_SESSION['cart'] = $cart;


$item_id = $_POST['item_id'];
$quantity = (int) $_POST['quantity'];

if (!isset($_SESSION['cart'])) {
    $cart = new Cart();
    $_SESSION['cart'] = $cart;
}

$_SESSION['cart']->add($item_id, $quantity);


if (isset($_SESSION['cart'])) {
    echo '<ul>';
    foreach ($_SESSION['cart']->getItems() as $item => $quantity) {
        echo '<li>' . $item . ' x ' . $quantity . '</li>';
    }
    echo '</ul>';
} else {
    echo 'Your cart is empty.';
}


<?php
// Starting session
session_start();

// If the 'cart' key does not exist in the $_SESSION array, create it.
if (!isset($_SESSION['cart'])) {
    // Initialize an empty cart for this user. We'll use a multidimensional array to hold item IDs and quantities.
    $_SESSION['cart'] = array();
}
?>


// Adding an item to the cart. This assumes that each item has a unique ID.
function addToCart($itemID) {
    global $_SESSION;
    
    // Check if the item is already in the cart to avoid duplicate entries.
    foreach ($_SESSION['cart'] as $i => $item) {
        if ($item[0] == $itemID) {
            // If it's there, increment its quantity by 1.
            $_SESSION['cart'][$i][1]++;
            return;
        }
    }
    
    // Not found in the cart. Add a new entry with a quantity of 1.
    $_SESSION['cart'][] = array($itemID, 1);
}

// Example usage
addToCart(123); // Item ID 123 added to cart with a quantity of 1.


// Displaying the contents of the cart.
function displayCart() {
    global $_SESSION;
    
    echo "Your Cart:
";
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            // Assuming we have a function to display item information based on its ID.
            displayItemInfo($item[0], $item[1]);
        }
    } else {
        echo "Your cart is empty.
";
    }
}

// Example usage
displayCart();


function removeFromCart($itemID) {
    global $_SESSION;
    
    // Find all instances of this item ID in the cart and delete them.
    foreach (array_keys($_SESSION['cart']) as $i => $key) {
        if ($_SESSION['cart'][$i][0] == $itemID) {
            unset($_SESSION['cart'][$i]);
        }
    }
    
    // Re-index array to ensure keys match original intent
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Example usage
removeFromCart(123); // Remove item 123 from cart.


function clearCart() {
    global $_SESSION;
    
    unset($_SESSION['cart']); // Unset the 'cart' index in the session.
}


<?php
// Ensure sessions are started for this script
session_start();

// If the user adds or updates products, we need a way to keep track of them in the cart.
if (!isset($_SESSION['cart'])) {
    // Initialize an empty cart array if it doesn't exist yet
    $_SESSION['cart'] = array();
}

function updateCart() {
    global $quantity;
    
    // Update the quantity of products in the session variable 'cart'
    foreach ($_POST as $product_id => $value) {
        if ($value != '') {
            // Assuming product IDs are numeric and quantities are strings for validation
            $_SESSION['cart'][$product_id] = array('quantity' => filter_input(INPUT_POST, $product_id, FILTER_SANITIZE_NUMBER_INT));
        }
    }
    
    // Save the changes to the session
    session_write_close();
}

// Example function to display the contents of the cart (not yet implemented)
function displayCart() {
    global $_SESSION;
    if (!empty($_SESSION['cart'])) {
        echo "Your Cart:
";
        foreach ($_SESSION['cart'] as $product_id => $values) {
            // Display each product with its quantity
            echo "Product ID: $product_id, Quantity: $values[quantity]
";
        }
    } else {
        echo "Cart is empty.";
    }
}

// Example form to add products (you'll need to adapt this for your database or API)
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="product_1" placeholder="Product 1 ID"><br><br>
    <input type="text" name="product_2" placeholder="Product 2 ID"><br><br>
    <!-- Add more product input fields as needed -->
    
    <button type="submit">Add to Cart</button>
</form>

<?php
// Update the cart when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updateCart();
}

displayCart(); // Display the current state of your cart
?>


<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="product_1" placeholder="Product 1 ID"><br><br>
    <input type="number" min="0" max="10" value="1" name="quantity_1" placeholder="Quantity of Product 1"><br><br>
    
    <input type="text" name="product_2" placeholder="Product 2 ID"><br><br>
    <input type="number" min="0" max="10" value="1" name="quantity_2" placeholder="Quantity of Product 2"><br><br>
    
    <!-- Add more product input fields as needed -->
    
    <button type="submit">Add to Cart</button>
</form>


<?php
session_start();

// Check if the session has been started, and if not start it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($item_id, $item_name, $price) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += 1; // increment quantity
            return true; // item found, quantity increased
        }
    }
    
    // Item not found, add it to the cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'name' => $item_name,
        'price' => $price,
        'quantity' => 1
    );
    
    return true; // item added to cart
}

// Function to remove an item from the cart
function removeFromCart($item_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return true; // item removed
        }
    }
    
    return false; // item not found in cart
}

// Function to update the quantity of an item in the cart
function updateQuantity($item_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity; // update quantity
            return true; // quantity updated
        }
    }
    
    return false; // item not found in cart
}

// Function to get the contents of the cart
function getCartContents() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}

// Example usage
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    
    addItemToCart($item_id, $item_name, $price);
}

if (isset($_POST['remove_from_cart'])) {
    $item_id = $_POST['item_id'];
    removeFromCart($item_id);
}

if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];
    
    updateQuantity($item_id, $new_quantity);
}


<?php
require_once 'cart.php';

// Display the cart contents
$cart_contents = getCartContents();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart Example</title>
</head>
<body>
    <h1>Cart Contents:</h1>
    <table border="1">
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th></th>
        </tr>
        <?php foreach ($cart_contents as $item) { ?>
            <tr>
                <td><?php echo $item['id']; ?></td>
                <td><?php echo $item['name']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><a href="#" onclick="removeItem(<?php echo $item['id']; ?>)">Remove</a></td>
            </tr>
        <?php } ?>
    </table>

    <h1>Cart Actions:</h1>
    <form action="" method="post">
        <input type="hidden" name="add_to_cart" value="true">
        <select name="item_id">
            <?php foreach ($cart_contents as $item) { ?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Add to Cart">
    </form>

    <h1>Remove Item:</h1>
    <form action="" method="post">
        <input type="hidden" name="remove_from_cart" value="true">
        <select name="item_id">
            <?php foreach ($cart_contents as $item) { ?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Remove from Cart">
    </form>

    <h1>Update Quantity:</h1>
    <form action="" method="post">
        <input type="hidden" name="update_quantity" value="true">
        <select name="item_id">
            <?php foreach ($cart_contents as $item) { ?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
            <?php } ?>
        </select>
        <input type="number" name="quantity">
        <input type="submit" value="Update Quantity">
    </form>

    <script>
        function removeItem(itemId) {
            document.getElementById('remove-form').item_id.value = itemId;
            document.getElementById('remove-form').submit();
        }
    </script>
</body>
</html>


// Initialize the cart array if it doesn't exist in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity if product is already in cart
            $item['quantity'] += $quantity;
            return;
        }
    }
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Update quantity of item in cart
function update_quantity($product_id, $quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Display cart contents
function display_cart() {
    global $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<p>ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "</p>";
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    remove_from_cart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
    update_quantity($_POST['product_id'], $_POST['quantity']);
}

display_cart();


<?php
require_once 'cart.php';

// Start the session if it doesn't exist
if (!session_start()) {
    die('Could not start session');
}

?>

<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $_POST['product_id']; ?>">
    <input type="hidden" name="quantity" value="<?php echo $_POST['quantity']; ?>">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $_POST['product_id']; ?>">
    <button type="submit" name="remove_from_cart">Remove from Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $_POST['product_id']; ?>">
    <input type="text" name="quantity" value="<?php echo $_POST['quantity']; ?>">
    <button type="submit" name="update_quantity">Update Quantity</button>
</form>

<?php
// If the form was submitted, process it and display cart contents
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ...
}
?>


<?php
// Configuration - You'll need to replace this with your database settings.
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Establishing a connection to the database.
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Starting session
session_start();

function add_item_to_cart($product_id, $quantity) {
    global $conn;
    
    // Ensure the product exists and quantity is valid.
    if (get_product_quantity($product_id) >= $quantity) {
        $sql = "INSERT INTO cart_items (user_id, product_id, quantity)
                VALUES (?, ?, ?)";
        
        // Update user's cart
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
        $result = $stmt->execute();
        
        if ($result) {
            echo "Product added to cart successfully!";
        } else {
            echo "Failed to add product to cart.";
        }
    } else {
        echo "Insufficient quantity of the product in stock.";
    }
}

function get_cart_contents() {
    global $conn;
    
    // Get all items for this user from cart_items table.
    $sql = "SELECT c.id, p.name, ci.quantity, p.price
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.user_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Display contents of the cart.
        while ($row = $result->fetch_assoc()) {
            echo $row["name"] . " x" . $row["quantity"] . " - $" . number_format($row['price'], 2) . "<br>";
        }
    } else {
        echo "Error fetching cart contents.";
    }
}

function remove_item_from_cart($item_id) {
    global $conn;
    
    // Remove product from the user's cart.
    $sql = "DELETE FROM cart_items
            WHERE id = ? AND user_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $item_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        echo "Product removed from cart successfully.";
    } else {
        echo "Failed to remove product from cart.";
    }
}

function get_product_quantity($product_id) {
    global $conn;
    
    // Get current quantity of a product.
    $sql = "SELECT quantity FROM products WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        return $result = $stmt->get_result()->fetch_assoc()['quantity'];
    } else {
        echo "Error fetching product quantity.";
    }
}

// Sample usage
if (isset($_POST['add_to_cart'])) {
    add_item_to_cart($_POST['product_id'], $_POST['quantity']);
}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<?php
// Display the contents of the cart.
get_cart_contents();

// Remove item from cart (example)
if (isset($_POST['remove_item'])) {
    remove_item_from_cart($_POST['item_id']);
}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="item_id" value="<?php echo $cart_item_id; ?>">
    <button type="submit" name="remove_item">Remove from Cart</button>
</form>

<?php
// Ending the session.
session_write_close();
?>


<?php
// Start the session
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it exists, update the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    // If not, add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update quantity of item in cart
function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Example usage:
if (isset($_GET['add_to_cart'])) {
    add_item_to_cart($_GET['product_id'], 1);
} elseif (isset($_GET['remove_from_cart'])) {
    remove_item_from_cart($_GET['product_id']);
} elseif (isset($_GET['update_quantity'])) {
    update_quantity($_GET['product_id'], $_GET['new_quantity']);
}

// Print the cart contents
?>
<table>
    <tr>
        <th>Product ID</th>
        <th>Quantity</th>
    </tr>
    <?php foreach ($_SESSION['cart'] as $item) { ?>
        <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
        </tr>
    <?php } ?>
</table>

<?php
// Clear the cart session (optional)
if (isset($_GET['clear_cart'])) {
    unset($_SESSION['cart']);
}
?>


<?php

// Initialize the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not, redirect to login page
    header('Location: login.php');
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Function to add an item to the cart
function add_item_to_cart($product_id) {
  global $conn; // assume you have a database connection established

  // Insert new cart item into the database
  $query = "INSERT INTO cart (user_id, product_id, quantity, price)
            VALUES ('$user_id', '$product_id', '1', '(SELECT price FROM products WHERE id = '$product_id')')";
  mysqli_query($conn, $query);

  // Update the session with the new item
  $_SESSION['cart'][$product_id] = array(
    'quantity' => 1,
    'price' => (float) $_POST['price']
  );
}

// Function to update an item in the cart
function update_item_in_cart($product_id, $new_quantity) {
  global $conn;

  // Update the database with new quantity
  $query = "UPDATE cart SET quantity = '$new_quantity'
            WHERE user_id = '$user_id' AND product_id = '$product_id'";
  mysqli_query($conn, $query);

  // Update the session with the new quantity
  $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
}

// Function to remove an item from the cart
function remove_item_from_cart($product_id) {
  global $conn;

  // Delete the item from the database
  $query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
  mysqli_query($conn, $query);

  // Remove the item from the session
  unset($_SESSION['cart'][$product_id]);
}

// Function to get the contents of the cart
function get_cart_contents() {
  global $conn;

  // Query the database for the user's cart items
  $query = "SELECT * FROM cart WHERE user_id = '$user_id'";
  $results = mysqli_query($conn, $query);

  // Create an array to store the cart contents
  $cart_contents = array();

  while ($row = mysqli_fetch_assoc($results)) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
      $_SESSION['cart'][$product_id] = array(
        'quantity' => $quantity,
        'price' => (float) $_POST['price']
      );
    }
  }

  return $_SESSION['cart'];
}

?>


<?php
// Start the session
session_start();

// If the cart is empty, initialize it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($productId, $productName, $price) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // If it's already in the cart, increment the quantity
            $item['quantity']++;
            return;
        }
    }

    // If not, add it to the cart with a quantity of 1
    $_SESSION['cart'][] = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $price,
        'quantity' => 1
    );
}

// Function to update item in cart
function updateItemInCart($productId, $newQuantity) {
    // Find the product in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity;
            return;
        }
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    // Find the product in the cart and remove it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to display cart contents
function displayCart() {
    echo "<h2>Cart Contents:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>$item[name]</td>";
        echo "<td>\$$item[price]</td>";
        echo "<td>$item[quantity]</td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Example usage:
addItemToCart(1, 'Product 1', 9.99);
addItemToCart(2, 'Product 2', 19.99);

displayCart();


<?php
session_start();
?>


function add_to_cart($product_id, $quantity, $product_name) {
    // Check if cart session already exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add product to cart
    $_SESSION['cart'][$product_id] = array(
        'quantity' => $quantity,
        'name' => $product_name
    );
}


function update_quantity($product_id, $new_quantity) {
    // Check if cart session exists and product is in cart
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


function remove_product($product_id) {
    // Check if cart session exists and product is in cart
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


function display_cart() {
    // Check if cart session exists
    if (isset($_SESSION['cart'])) {
        echo "Cart Contents:<br>";
        foreach ($_SESSION['cart'] as $product_id => $product) {
            echo "$product[name] x $product[quantity]<br>";
        }
    } else {
        echo "No products in cart.";
    }
}


// Add product to cart
add_to_cart(1, 2, 'Product 1');
add_to_cart(2, 3, 'Product 2');

// Display cart contents
display_cart();

// Update quantity of a product
update_quantity(1, 4);

// Display updated cart contents
display_cart();

// Remove a product from the cart
remove_product(2);


<?php
// Configuration Settings
session_start();

$cart_name = 'user_cart';
$max_items = 10; // Maximum number of items allowed in the cart
?>


<?php
// Include Configuration Settings
require_once('config.php');

// Function to add item to cart
function addToCart($item_id, $quantity) {
    global $cart_name;
    
    // Check if session is already active
    if (!isset($_SESSION[$cart_name])) {
        $_SESSION[$cart_name] = array();
    }
    
    // Check if item already exists in the cart
    foreach ($_SESSION[$cart_name] as &$item) {
        if ($item['id'] == $item_id) {
            // Update existing item's quantity if it exceeds the new quantity
            if ($quantity > $item['quantity']) {
                $item['quantity'] = $quantity;
            }
            return; // Exit function after updating existing item
        }
    }
    
    // If not, add a new item to the cart
    $_SESSION[$cart_name][] = array(
        'id' => $item_id,
        'name' => '', // You can fetch this from your database based on the item's ID
        'quantity' => $quantity
    );
}

// Function to remove an item from the cart
function removeFromCart($item_id) {
    global $cart_name;
    
    if (isset($_SESSION[$cart_name])) {
        foreach ($_SESSION[$cart_name] as $key => &$item) {
            if ($item['id'] == $item_id) {
                unset($_SESSION[$cart_name][$key]);
                return; // Exit function after removing the item
            }
        }
    }
}

// Example usage: Add 2 items to the cart
addToCart(1, 2);
addToCart(3, 1);

// Example usage: Remove an item from the cart
removeFromCart(1);
?>


<?php
// Include Configuration Settings
require_once('config.php');

// Display contents of the cart
echo "Your Cart:
";
if (isset($_SESSION[$cart_name]) && count($_SESSION[$cart_name]) > 0) {
    foreach ($_SESSION[$cart_name] as $item) {
        echo "Item ID: {$item['id']}, Quantity: {$item['quantity']}
";
    }
} else {
    echo "Your cart is empty.
";
}
?>


<?php
session_start();

// Check if cart is already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'items' => [], // Array to store items in the cart
        'totalPrice' => 0, // Total price of all items
        'quantity' => 0 // Number of items in the cart
    ];
}
?>


<form id="add-to-cart-form">
    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
    <button type="submit">Add to Cart</button>
</form>

<script>
    $(document).ready(function() {
        $("#add-to-cart-form").submit(function(e) {
            e.preventDefault();
            var productId = $(this).find('input[name="product_id"]').val();
            $.ajax({
                url: 'cart_ajax.php',
                type: 'POST',
                data: {action: 'add', product_id: productId},
                success: function(data) {
                    // Handle the response from cart_ajax.php
                    console.log(data);
                    location.reload(); // Reload page to see updated cart
                }
            });
        });
    });
</script>


<?php
session_start();

if (isset($_POST['action']) && !empty($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Add item to cart if it's not already there or update quantity otherwise
    if (!in_array($productId, $_SESSION['cart']['items'])) {
        array_push($_SESSION['cart']['items'], $productId);
        $_SESSION['cart']['quantity']++;
    } else {
        foreach ($_SESSION['cart']['items'] as &$item) {
            if ($item == $productId) {
                break;
            }
        }

        // Update quantity of the item
        $index = array_search($productId, $_SESSION['cart']['items']);
        unset($_SESSION['cart']['items'][$index]);
        array_push($_SESSION['cart']['items'], $productId);
    }

    // Recalculate total price based on quantities and prices of products (assuming prices are in session)
    foreach ($_SESSION['cart']['items'] as $item) {
        $_SESSION['cart']['totalPrice'] += $_POST['price']; // 'price' assumed sent from client
    }
}
?>


<table>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Total Price</th>
    </tr>

    <?php foreach ($_SESSION['cart']['items'] as $item) : ?>
    <tr>
        <td><?php echo getProductName($item); ?></td>
        <td><?php echo $_SESSION['cart']['quantity']; ?></td>
        <td><?php echo number_format($_SESSION['cart']['totalPrice'], 2); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
    // If you're using JavaScript for client-side updates, this is where you'd update the display.
    function updateCartDisplay() {
        $.ajax({
            url: 'cart_ajax.php',
            type: 'GET',
            data: {action: 'display'},
            success: function(data) {
                $('#cart-display').html(data);
            }
        });
    }

    // Call this on page load and whenever the cart is updated.
    updateCartDisplay();
</script>


session_start();


// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Function to add item to cart
function addToCart($productId, $productName, $productPrice) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += 1;
            return; // Item already exists, so just update its quantity
        }
    }

    // Item not found, add it to the cart
    $_SESSION['cart'][] = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => 1
    );
}


// Function to remove item from cart by ID
function removeFromCart($itemId) {
    global $_SESSION;
    
    // Loop through the session cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $itemId) {
            unset($_SESSION['cart'][$key]);
            return; // Item removed successfully
        }
    }
}


// Function to update item's quantity in cart
function updateQuantity($itemId, $newQuantity) {
    global $_SESSION;
    
    // Find the item and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId && $newQuantity > 0) {
            $item['quantity'] = $newQuantity;
            return; // Quantity updated successfully
        }
    }
}


// Example of displaying what's currently in the cart
if (isset($_SESSION['cart'])) {
    echo "<h2>Cart</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[name] x $item[quantity] = $" . ($item['price'] * $item['quantity']) . "<br>";
    }
}


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function addToCart($productId, $productName, $productPrice) {
    global $_SESSION;
    
    // Check if item already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += 1;
            return; // Item already exists, so just update its quantity
        }
    }

    // Item not found, add it to the cart
    $_SESSION['cart'][] = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => 1
    );
}

function removeFromCart($itemId) {
    global $_SESSION;
    
    // Loop through the session cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $itemId) {
            unset($_SESSION['cart'][$key]);
            return; // Item removed successfully
        }
    }
}

function updateQuantity($itemId, $newQuantity) {
    global $_SESSION;
    
    // Find the item and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId && $newQuantity > 0) {
            $item['quantity'] = $newQuantity;
            return; // Quantity updated successfully
        }
    }
}

// Example usage: Add an item to the cart
addToCart(1, "Item 1", 10.99);

// Display the items currently in the cart
if (isset($_SESSION['cart'])) {
    echo "<h2>Cart</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[name] x $item[quantity] = $" . ($item['price'] * $item['quantity']) . "<br>";
    }
}
?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function add_item_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}


function remove_item_from_cart($product_id) {
    // Find and remove item from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function display_cart() {
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<p>' . $item['product_id'] . ' x ' . $item['quantity'] . '</p>';
    }
}


<?php
require_once 'cart.php';

// Add items to cart
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);

// Display cart contents
display_cart();

// Remove item from cart
remove_item_from_cart(1);

// Display updated cart contents
display_cart();
?>


<?php
// Start the session
session_start();

// Define constants for cart keys
define('CART_KEY', 'cart_items');
define('QUANTITY_KEY', 'quantity');

// Function to add item to cart
function add_item_to_cart($item_id, $quantity = 1) {
    // Get existing items from session or initialize an empty array if not set
    $_SESSION[CART_KEY] = isset($_SESSION[CART_KEY]) ? $_SESSION[CART_KEY] : [];

    // Check if item already exists in cart and update quantity if needed
    foreach ($_SESSION[CART_KEY] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }

    // Add new item to cart if not found
    else {
        $_SESSION[CART_KEY][] = ['id' => $item_id, 'quantity' => $quantity];
    }

    // Update session with updated cart items
    $_SESSION[CART_KEY] = array_values($_SESSION[CART_KEY]);
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    // Get existing items from session
    $_SESSION[CART_KEY] = isset($_SESSION[CART_KEY]) ? $_SESSION[CART_KEY] : [];

    // Remove item with matching id
    foreach ($_SESSION[CART_KEY] as $key => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION[CART_KEY][$key]);
            break;
        }
    }

    // Update session with updated cart items
    $_SESSION[CART_KEY] = array_values($_SESSION[CART_KEY]);
}

// Function to update quantity of item in cart
function update_item_quantity($item_id, $new_quantity) {
    // Get existing items from session
    $_SESSION[CART_KEY] = isset($_SESSION[CART_KEY]) ? $_SESSION[CART_KEY] : [];

    // Update quantity of matching item
    foreach ($_SESSION[CART_KEY] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }

    // Update session with updated cart items
    $_SESSION[CART_KEY] = array_values($_SESSION[CART_KEY]);
}

// Example usage:
// Add item to cart
add_item_to_cart(1, 2);

// Remove item from cart
remove_item_from_cart(1);

// Update quantity of item in cart
update_item_quantity(1, 3);
?>


<?php
session_start();
?>


$cart = [
    'items' => [], // list of items in the cart
    'subtotal' => 0, // subtotal of all items
    'total' => 0 // total cost including tax and shipping (if applicable)
];


function add_item_to_cart($item_id, $quantity) {
    global $cart;

    // Check if item is already in cart
    $existing_item = array_filter($cart['items'], function ($i) use ($item_id) {
        return $i['id'] == $item_id;
    });

    if (!empty($existing_item)) {
        // Update quantity of existing item
        $existing_item[0]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $cart['items'][] = [
            'id' => $item_id,
            'name' => 'Item Name', // replace with actual item name
            'price' => 9.99, // replace with actual price
            'quantity' => $quantity
        ];
    }

    update_cart_subtotal();
}

// Example usage:
add_item_to_cart(123, 2);


function update_cart_subtotal() {
    global $cart;

    $cart['subtotal'] = array_reduce($cart['items'], function ($acc, $item) {
        return $acc + $item['price'] * $item['quantity'];
    }, 0);
}


function display_cart() {
    global $cart;

    echo '<h2>Cart Contents:</h2>';
    foreach ($cart['items'] as $item) {
        echo "<p>ID: {$item['id']} Name: {$item['name']} Price: \${$item['price']} Quantity: {$item['quantity']}</p>";
    }
}


<?php
session_start();

// Initialize cart data structure
$cart = [
    'items' => [],
    'subtotal' => 0,
    'total' => 0
];

// Add items to cart
add_item_to_cart(123, 2);
add_item_to_cart(456, 1);

// Display cart contents
display_cart();

// Update cart subtotal and total (if applicable)
update_cart_subtotal();


class Cart {
    private $cart;

    public function __construct() {
        $this->cart = array();
    }

    // Add item to the cart
    public function add_item($id, $name, $price) {
        if (array_key_exists($id, $this->cart)) {
            $this->cart[$id]['quantity']++;
        } else {
            $this->cart[$id] = array('name' => $name, 'price' => $price, 'quantity' => 1);
        }
    }

    // Remove item from the cart
    public function remove_item($id) {
        if (array_key_exists($id, $this->cart)) {
            unset($this->cart[$id]);
        }
    }

    // Update quantity of an item in the cart
    public function update_quantity($id, $quantity) {
        if (array_key_exists($id, $this->cart)) {
            $this->cart[$id]['quantity'] = $quantity;
        }
    }

    // Get the contents of the cart
    public function get_contents() {
        return $this->cart;
    }

    // Save the cart to session
    public function save_to_session($session_name) {
        $_SESSION[$session_name] = $this->cart;
    }

    // Load the cart from session
    public static function load_from_session($session_name) {
        if (isset($_SESSION[$session_name])) {
            return $_SESSION[$session_name];
        } else {
            return array();
        }
    }
}


// Initialize the cart
$cart = new Cart();

// Add items to the cart
$cart->add_item(1, 'Product 1', 10.99);
$cart->add_item(2, 'Product 2', 5.99);

// Update quantity of an item in the cart
$cart->update_quantity(1, 3);

// Remove an item from the cart
$cart->remove_item(2);

// Save the cart to session
$cart->save_to_session('user_cart');

// Load the cart from session (on a different page or request)
$user_cart = Cart::load_from_session('user_cart');
print_r($user_cart);


<?php
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($itemId) {
    global $item;
    if (in_array($itemId, $_SESSION['cart'])) {
        echo "Item already in cart!";
    } else {
        // Add item to cart and update session
        $_SESSION['cart'][] = $itemId;
        echo "Item added to cart!";
    }
}

// Function to remove item from cart
function removeFromCart($itemId) {
    global $item;
    if (in_array($itemId, $_SESSION['cart'])) {
        // Remove item from cart and update session
        unset($_SESSION['cart'][array_search($itemId, $_SESSION['cart'])]);
        echo "Item removed from cart!";
    } else {
        echo "Item not in cart!";
    }
}

// Add or remove items to/from the cart based on user interactions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            addItemToCart($_GET['id']);
            break;
        case 'remove':
            removeFromCart($_GET['id']);
            break;
        default:
            echo "Invalid action!";
    }
}

// Display the cart contents
echo "<h2>My Cart:</h2>";
if (!empty($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
    foreach ($cartItems as $itemId) {
        // Assuming we have a function to get item details by ID
        $itemDetails = getItemDetails($itemId);
        echo "Item ID: $itemId | Name: $itemDetails[name] | Price: $" . number_format($itemDetails['price']);
    }
} else {
    echo "Your cart is empty!";
}


<?php

// Start the session
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>


function add_to_cart($product_id) {
    global $_SESSION;

    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment the quantity
            $item['quantity']++;
            return;
        }
    }

    // Add new product to the cart with a quantity of 1
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
}


function display_cart() {
    global $_SESSION;

    // Loop through the cart items
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "<br>";
    }
}


// Add a product to the cart
add_to_cart(123);

// Display the cart contents
display_cart();


<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity']++;
            return;
        }
    }

    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
}

function display_cart() {
    global $_SESSION;

    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "<br>";
    }
}

// Example usage
add_to_cart(123);
display_cart();

?>


<?php
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You must be logged in to access your cart.";
    exit;
}

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get the product ID and quantity from the form data
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if the product is already in the cart
foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['id'] == $product_id) {
        // Update the quantity of the existing item
        $_SESSION['cart'][$key]['quantity'] += $quantity;
        break;
    }
}

// Add the product to the cart if it's not already there
if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array('id' => $product_id, 'quantity' => $quantity);
}

// Save the updated cart session
session_write_close();
echo "Product added to cart!";
?>


<?php
// Start the session
session_start();

// Get the product data from the database (example using PDO)
$pdo = new PDO('mysql:host=localhost;dbname=shop', 'username', 'password');
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindParam(':id', $_GET['product_id']);
$stmt->execute();
$product_data = $stmt->fetch();

// Display the product information
echo "Product ID: " . $product_data['id'] . "<br>";
echo "Product Name: " . $product_data['name'] . "<br>";
echo "Price: $" . $product_data['price'] . "<br>";

// Add to cart form
?>
<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_data['id']; ?>">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" max="10">
    <button type="submit">Add to Cart</button>
</form>

<?php
// Display the cart contents (example using a simple loop)
if (isset($_SESSION['cart'])) {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        echo "Product ID: " . $product_data['id'] . "<br>";
        echo "Quantity: " . $product_data['quantity'] . "<br><br>";
    }
}
?>


class Cart {
    private $cart;

    public function __construct() {
        // Initialize the cart as an empty array if it doesn't exist in the session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = $_SESSION['cart'];
    }

    /**
     * Add a product to the cart
     *
     * @param int $id Product ID
     * @param string $name Product name
     * @param float $price Product price
     * @param int $quantity Quantity of the product
     */
    public function addProduct($id, $name, $price, $quantity) {
        // Check if the product is already in the cart
        foreach ($this->cart as &$product) {
            if ($product['id'] == $id) {
                // If it is, increment the quantity
                $product['quantity'] += $quantity;
                return;
            }
        }

        // If not, add it to the cart
        $this->cart[] = array(
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    }

    /**
     * Remove a product from the cart
     *
     * @param int $id Product ID
     */
    public function removeProduct($id) {
        // Check if the product is in the cart
        foreach ($this->cart as $key => &$product) {
            if ($product['id'] == $id) {
                // If it is, unset it from the cart
                unset($this->cart[$key]);
                return;
            }
        }
    }

    /**
     * Update a product's quantity in the cart
     *
     * @param int $id Product ID
     * @param int $quantity New quantity
     */
    public function updateQuantity($id, $quantity) {
        // Check if the product is in the cart
        foreach ($this->cart as &$product) {
            if ($product['id'] == $id) {
                // If it is, update its quantity
                $product['quantity'] = $quantity;
                return;
            }
        }
    }

    /**
     * Get the total cost of the products in the cart
     *
     * @return float Total cost
     */
    public function getTotalCost() {
        $total = 0;
        foreach ($this->cart as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }

    /**
     * Get the cart contents
     *
     * @return array Cart contents
     */
    public function getCartContents() {
        return $this->cart;
    }
}


// Start the session
session_start();

// Initialize the cart
$cart = new Cart();

// Add a product to the cart
$cart->addProduct(1, 'Product 1', 9.99, 2);

// Get the total cost of the products in the cart
echo $cart->getTotalCost(); // Output: 19.98

// Update a product's quantity
$cart->updateQuantity(1, 3);

// Remove a product from the cart
$cart->removeProduct(1);

// Get the cart contents
print_r($cart->getCartContents());


// Initialize the cart session
session_start();

// Check if the cart is already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    // Ensure the product id is an integer
    $product_id = (int) $product_id;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it's already in the cart, increment its quantity
            $item['quantity'] += (int) $quantity;
            return; // don't add it again
        }
    }

    // If it's not in the cart yet, add it with a new item
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => (int) $quantity
    );
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    // Ensure the product id is an integer
    $product_id = (int) $product_id;

    // Find and remove the product from the cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // don't add it again
        }
    }

    // If it's not in the cart yet, do nothing
}

// Function to update item quantity in cart
function update_cart_item($product_id, $quantity) {
    global $_SESSION;
    // Ensure the product id and quantity are integers
    $product_id = (int) $product_id;
    $quantity = (int) $quantity;

    // Find the product in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update its quantity
            $item['quantity'] = $quantity;
            return; // done!
        }
    }

    // If it's not in the cart yet, do nothing
}

// Example usage:
add_to_cart(123, 2); // add product with id 123 to cart with quantity 2
remove_from_cart(123); // remove product with id 123 from cart
update_cart_item(123, 3); // update quantity of product with id 123 in cart

// Print the contents of the cart for debugging purposes:
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';


<?php

// Start the session
session_start();

// Set cart ID (if it doesn't exist)
if (!isset($_SESSION['cart_id'])) {
    $cartId = uniqid();
    $_SESSION['cart_id'] = $cartId;
}

// Function to add item to cart
function addItemToCart($productId, $quantity) {
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'quantity' => $quantity,
            'subtotal' => 0
        );
    } else {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
        $_SESSION['cart'][$productId]['subtotal'] = $_SESSION['cart'][$productId]['price'] * $_SESSION['cart'][$productId]['quantity'];
    }
}

// Function to update cart item quantity
function updateCartQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        $_SESSION['cart'][$productId]['subtotal'] = $_SESSION['cart'][$productId]['price'] * $newQuantity;
    }
}

// Function to remove item from cart
function removeItemFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    addItemToCart($productId, $quantity);
} elseif (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['new_quantity'];

    updateCartQuantity($productId, $newQuantity);
} elseif (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];

    removeItemFromCart($productId);
}

// Display cart contents
echo '<h2>Cart Contents:</h2>';
echo '<ul>';

foreach ($_SESSION['cart'] as $product) {
    echo '<li>' . $product['product_id'] . ' x ' . $product['quantity'] . '</li>';
}

echo '</ul>';

?>


<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="12345">
    <input type="number" name="quantity" value="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="12345">
    <input type="number" name="new_quantity" value="2">
    <button type="submit" name="update_quantity">Update Quantity</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="12345">
    <button type="submit" name="remove_from_cart">Remove from Cart</button>
</form>


// Create a new session, or resume an existing one
session_start();

// Check if the cart is already stored in the session
if (!isset($_SESSION['cart'])) {
    // If not, create a new empty cart array
    $_SESSION['cart'] = [];
}


function add_to_cart($product_id, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return; // We're done
        }
    }

    // If not, add a new item to the cart
    $_SESSION['cart'][] = ['id' => $product_id, 'quantity' => $quantity];
}


add_to_cart(1, 2); // Add 2 of product #1 to cart
add_to_cart(3, 1); // Add 1 of product #3 to cart


echo "Cart Contents:
";
foreach ($_SESSION['cart'] as $item) {
    echo "- " . $item['id'] . ": " . $item['quantity'] . "
";
}


function remove_from_cart($product_id) {
    // Loop through the cart array and find the item with the matching ID
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            // If found, unset the item from the array
            unset($_SESSION['cart'][$key]);
            return; // We're done
        }
    }

    // If not found, do nothing (or throw an error)
}


remove_from_cart(1); // Remove 2 of product #1 from cart


session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function add_to_cart($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // We're done
        }
    }

    $_SESSION['cart'][] = ['id' => $product_id, 'quantity' => $quantity];
}

function remove_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // We're done
        }
    }

    // If not found, do nothing (or throw an error)
}

// Example usage:
add_to_cart(1, 2); // Add 2 of product #1 to cart
echo "Cart Contents:
";
foreach ($_SESSION['cart'] as $item) {
    echo "- " . $item['id'] . ": " . $item['quantity'] . "
";
}

remove_from_cart(1); // Remove 2 of product #1 from cart


<?php
// Start the session
session_start();

// Set the cart array in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            return; // Item already exists, update quantity
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return; // Item removed
        }
    }
}

// Function to update quantity of item in cart
function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return; // Quantity updated
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[id] x $item[quantity]<br>";
    }
}
?>


<?php include 'cart.php'; ?>

<form action="" method="post">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
    <button type="submit">Add to Cart</button>
</form>

<form action="" method="post">
    <input type="text" name="quantity" placeholder="Quantity">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
    <button type="submit">Update Quantity</button>
</form>

<button type="submit">Remove from Cart</button>

<?php
if (isset($_POST['item_id'])) {
    if (empty($_POST['quantity'])) {
        add_item_to_cart($_POST['item_id'], 1);
    } else {
        update_quantity($_POST['item_id'], $_POST['quantity']);
    }
}
?>


<?php include 'cart.php'; ?>

<button type="submit">View Cart</button>

<div class="cart_contents">
    <?php display_cart(); ?>
</div>


// Create a new session or resume an existing one
session_start();


// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'items' => [],
        'totals' => [
            'subTotal' => 0.00,
            'taxes' => 0.00,
            'total' => 0.00
        ]
    ];
}


function addToCart($itemId, $quantity) {
    global $_SESSION;

    // Check if the item is already in the cart
    if (isset($_SESSION['cart']['items'][$itemId])) {
        // Update the quantity of existing items
        $_SESSION['cart']['items'][$itemId] += $quantity;
    } else {
        // Add new item to the cart
        $_SESSION['cart']['items'][$itemId] = $quantity;

        // Calculate totals and update them in session
        calculateTotals();
    }
}

// Example usage:
addToCart(1, 2); // Adds 2 items with ID 1 to the cart


function removeFromCart($itemId) {
    global $_SESSION;

    if (isset($_SESSION['cart']['items'][$itemId])) {
        unset($_SESSION['cart']['items'][$itemId]);
        calculateTotals();
    }
}

// Example usage:
removeFromCart(1); // Removes items with ID 1 from the cart


function calculateTotals() {
    global $_SESSION;

    // Initialize subtotal, tax, and total with zero for each item
    $subTotal = 0.00;
    $taxes = 0.00;
    $total = 0.00;

    // Iterate over the items in the cart
    foreach ($_SESSION['cart']['items'] as $itemId => $quantity) {
        // Assume we have a function to get item prices or calculate them somehow
        $price = getItemPrice($itemId);
        $subTotal += $price * $quantity;
        $taxes += $price * $quantity * 0.08; // For example, assuming an 8% tax rate
    }

    // Calculate total and store it in session
    $_SESSION['cart']['totals'] = [
        'subTotal' => number_format($subTotal, 2),
        'taxes' => number_format($taxes, 2),
        'total' => number_format($subTotal + $taxes, 2)
    ];
}

// Example usage after adding or removing an item from the cart
calculateTotals();


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'items' => [],
        'totals' => [
            'subTotal' => 0.00,
            'taxes' => 0.00,
            'total' => 0.00
        ]
    ];
}

function addToCart($itemId, $quantity) {
    global $_SESSION;

    // ... (same as above)
}

function removeFromCart($itemId) {
    global $_SESSION;

    // ... (same as above)
}

function calculateTotals() {
    global $_SESSION;

    // ... (same as above)
}

// Example usage:
addToCart(1, 2);
calculateTotals();

// Display the cart contents and totals
?>


<?php
session_start();
?>


<?php
// Assuming $product_id is the ID of the product being added
if (isset($_POST['add_to_cart'])) {
    // Add item to session cart array if it doesn't exist yet
    if (!isset($_SESSION['cart'][$_POST['product_id']])) {
        $_SESSION['cart'][$_POST['product_id']] = 1;
    } else {
        $_SESSION['cart'][$_POST['product_id']] += 1;
    }
}

// Example of displaying the cart contents
if (isset($_SESSION['cart'])) {
    echo "Your Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $id => $quantity) {
        // You would replace this with a database query to get product details based on ID
        echo "$id: Quantity = $quantity<br>";
    }
}
?>


<?php
if (isset($_POST['remove_from_cart'])) {
    unset($_SESSION['cart'][$_POST['product_id']]);
}
?>


<?php
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
// Check if the cart session already exists
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = array('quantity' => $quantity);
    }
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$item_id]);
}

// Function to update quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}

// Add a product to the cart
$prod_id = 123; // Replace with actual product ID
$prod_qty = 2; // Replace with actual quantity
add_item_to_cart($prod_id, $prod_qty);

// Remove a product from the cart
remove_item_from_cart(456); // Replace with actual product ID

// Update quantity of an item in the cart
update_quantity(123, 3); // Replace with actual product ID and new quantity

// Display contents of cart
print_r($_SESSION['cart']);
?>


// Start the session
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already exists in cart, increment quantity instead of adding new item.
        }
    }

    // Add product to cart with default quantity
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function remove_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // Product removed from cart.
        }
    }
}

function update_quantity($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return; // Quantity updated for product in cart.
        }
    }
}


<?php
session_start();
?>


$_SESSION['cart'] = array();


function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product already exists in cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


function remove_from_cart($product_id) {
    global $_SESSION;
    
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


function display_cart() {
    global $_SESSION;
    
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Retrieve product details from database (e.g. using PDO)
        $product = get_product($product_id);
        
        echo "Product: " . $product['name'] . " (" . $quantity . ")<br>";
    }
}


<?php
session_start();
$_SESSION['cart'] = array();

// Add items to cart
add_to_cart(1, 2); // Product ID: 1, Quantity: 2
add_to_cart(3, 1); // Product ID: 3, Quantity: 1

// Display cart contents
display_cart();

// Remove item from cart
remove_from_cart(1);

// Display updated cart contents
display_cart();
?>


<?php
session_start();
?>


function add_to_cart($product_id, $quantity) {
    // Check if cart is set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new product to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


function view_cart() {
    // Check if cart is set
    if (!isset($_SESSION['cart'])) {
        return 'Your cart is empty.';
    }

    // Display cart contents
    echo '<h2>Your Cart:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo $item['id'] . ': x' . $item['quantity'] . '<br>';
    }
}


function remove_from_cart($product_id) {
    // Check if cart is set
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                return;
            }
        }
    }
}


// Add product to cart
add_to_cart(1, 2);

// View cart contents
view_cart();

// Remove product from cart
remove_from_cart(1);


<?php
session_start();

function add_to_cart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }

    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function view_cart() {
    if (!isset($_SESSION['cart'])) {
        return 'Your cart is empty.';
    }

    echo '<h2>Your Cart:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo $item['id'] . ': x' . $item['quantity'] . '<br>';
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                return;
            }
        }
    }
}

// Example usage
add_to_cart(1, 2);
view_cart();
remove_from_cart(1);

?>


    session.auto_start = On
    

**Step 2: Create a cart session**

Create a new PHP file, for example, `cart.php`. In this file, we will add two functions to manage our cart session.



<?php
// Start the session
session_start();

// If cart is not set, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Display the cart
echo "Current Cart:<br>";
print_r($_SESSION['cart']);

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // If product is already in cart, increment quantity
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array('price' => 9.99, 'quantity' => $quantity);
    }
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}
?>


<?php
if (!isset($_SESSION)) {
    session_start();
}
// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart, increment quantity if already in cart
$item_id = $_POST['item_id'];
$quantity = $_POST['quantity'];

// If the item is already in the cart, increase its quantity by the added amount.
if (array_key_exists($item_id, $_SESSION['cart'])) {
    $_SESSION['cart'][$item_id] += $quantity;
} else {
    // Otherwise, add it to the cart
    $_SESSION['cart'][$item_id] = $quantity;
}

// Redirect back to your main page or a view cart page
header('Location: view_cart.php');
exit();
?>


<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Cart</title>
</head>
<body>

<h2>Your Shopping Cart:</h2>

<?php
// Display each item in the cart with its quantity
foreach ($_SESSION['cart'] as $item_id => $quantity) {
    echo "Item ID: $item_id, Quantity: $quantity<br>";
}
?>

<p>Total Items: <?= count($_SESSION['cart']) ?></p>
</body>
</html>


<form action="add_to_cart.php" method="post">
    Item ID: <input type="text" name="item_id"><br><br>
    Quantity: <input type="number" name="quantity"><br><br>
    <input type="submit" value="Add to Cart">
</form>


// Start the session
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
  global $_SESSION;
  if (array_key_exists($product_id, $_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
  global $_SESSION;
  unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
  global $_SESSION;
  if (array_key_exists($product_id, $_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] = $new_quantity;
  }
}

// Function to get items in cart
function get_cart() {
  return $_SESSION['cart'];
}

// Add item to cart example
add_to_cart(1, 2); // Add product with ID 1 to cart in quantity of 2

// Remove item from cart example
remove_from_cart(1);

// Update quantity of item in cart example
update_quantity(1, 3);


// Start the session
session_start();

// Get items in cart
$cart = get_cart();

// Display items in cart
echo "<h2>Your Cart:</h2>";
foreach ($cart as $product_id => $quantity) {
  echo "Product ID: $product_id, Quantity: $quantity<br/>";
}


<?php
// Session start
session_start();

// If the cart is not set, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Product data (e.g., from a database)
$products = array(
    array('id' => 1, 'name' => 'Product 1', 'price' => 9.99),
    array('id' => 2, 'name' => 'Product 2', 'price' => 19.99),
    // ...
);

// Add a product to the cart
function add_product_to_cart($product_id) {
    global $products;
    foreach ($products as $product) {
        if ($product['id'] == $product_id) {
            if (!in_array($product_id, $_SESSION['cart'])) {
                $_SESSION['cart'][] = array('product_id' => $product_id);
            }
            break;
        }
    }
}

// Remove a product from the cart
function remove_product_from_cart($product_id) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of a product in the cart
function update_quantity_in_cart($product_id, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Get the total cost of the cart
function get_total_cost() {
    global $_SESSION;
    $total_cost = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $product_data = null;
        foreach ($GLOBALS['products'] as $p) {
            if ($p['id'] == $product_id) {
                $product_data = $p;
                break;
            }
        }
        if ($product_data !== null) {
            $total_cost += $product_data['price'];
        }
    }
    return $total_cost;
}

// Example usage
add_product_to_cart(1); // Add Product 1 to the cart
update_quantity_in_cart(1, 2); // Update quantity of Product 1 to 2

echo "Total cost: $" . get_total_cost();
?>


// Start the session
session_start();

// Create a new cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id, $quantity) {
    // Check if the product is already in the cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Update the quantity of the existing item
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Add a new item to the cart
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Example usage:
add_to_cart(1, 2);  // Adds 2 items of product with ID 1 to the cart


function remove_from_cart($product_id) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example usage:
remove_from_cart(1);  // Removes item with ID 1 from the cart


function display_cart() {
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "- Product ID: $product_id<br>";
        echo "- Quantity: $item[quantity]<br><br>";
    }
}

// Example usage:
display_cart();


<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

function remove_from_cart($product_id) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function display_cart() {
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "- Product ID: $product_id<br>";
        echo "- Quantity: $item[quantity]<br><br>";
    }
}

add_to_cart(1, 2);
display_cart();
remove_from_cart(1);

?>


<?php
// Initialize the session
session_start();

// If the cart is not set, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_item_to_cart($product_id) {
    global $db; // assuming you have a database connection established

    // Check if the product exists in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            // If it does, increment the quantity of that item
            $_SESSION['cart'][$key]['quantity']++;
            return;
        }
    }

    // If not, add the product to the cart with a quantity of 1
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => 'Product Name', // replace with actual product name from database
        'price' => '9.99', // replace with actual price from database
        'quantity' => 1
    );
}

// Function to remove an item from the cart
function remove_item_from_cart($product_id) {
    global $db; // assuming you have a database connection established

    // Find the index of the product in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            // Remove the product from the cart
            unset($_SESSION['cart'][$key]);
            return;
        }
    }

    // If the product is not found, do nothing
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $db; // assuming you have a database connection established

    // Find the index of the product in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            // Update the quantity of that item
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;
            return;
        }
    }

    // If the product is not found, do nothing
}

// Add a product to the cart
add_item_to_cart(1);

// Remove a product from the cart
remove_item_from_cart(1);

// Update the quantity of a product in the cart
update_quantity(1, 2);
?>


<?php
// Initialize the session
session_start();

// Print out the contents of the cart
print_r($_SESSION['cart']);
?>


<?php

// Set up database connection (replace with your own DB settings)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add item to cart
function add_to_cart($user_id, $product_id, $quantity) {
  global $conn;
  $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $user_id, $product_id, $quantity);
  $stmt->execute();
  return $conn->insert_id;
}

// Function to get cart items
function get_cart_items($user_id) {
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Function to update quantity of item in cart
function update_cart_item($cart_item_id, $new_quantity) {
  global $conn;
  $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
  $stmt->bind_param("ii", $new_quantity, $cart_item_id);
  $stmt->execute();
}

// Function to delete item from cart
function delete_from_cart($cart_item_id) {
  global $conn;
  $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
  $stmt->bind_param("i", $cart_item_id);
  $stmt->execute();
}

?>


session_start();

// Set up user ID
$user_id = $_SESSION['user_id'];

// Add item to cart
$product_id = 1;
$quantity = 2;
$cart_item_id = add_to_cart($user_id, $product_id, $quantity);

// Get cart items
$cart_items = get_cart_items($user_id);
print_r($cart_items); // Array of cart items

// Update quantity of item in cart
$cart_item_id = 1;
$new_quantity = 3;
update_cart_item($cart_item_id, $new_quantity);

// Delete item from cart
$cart_item_id = 2;
delete_from_cart($cart_item_id);


<?php
session_start();

// Check if the cart session already exists, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    // Get product details from database (replace with your own logic)
    $product_name = "Product Name";
    $product_price = 9.99;

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity, 'price' => $product_price);
    } else {
        // Increment quantity of existing product
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to update item in cart
function update_item_in_cart($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// Function to get total cost of items in cart
function get_total_cost() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Test the functions
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);

echo "Total cost: " . get_total_cost() . "
";

update_item_in_cart(1, 5);

echo "Updated total cost: " . get_total_cost() . "
";

remove_item_from_cart(2);

echo "After removing item, total cost: " . get_total_cost() . "
";


<?php
session_start();
?>


$_SESSION['cart'] = array();


function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity
            $item['quantity'] += $quantity;
            return true; // Product added successfully
        }
    }
    
    // Add new product to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return true; // Product added successfully
}


function update_cart_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find product in cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated successfully
        }
    }
    
    return false; // Product not found in cart
}


function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find and remove product from cart
    foreach (array_keys($_SESSION['cart']) as $index) {
        if ($_SESSION['cart'][$index]['id'] == $product_id) {
            unset($_SESSION['cart'][$index]);
            return true; // Product removed successfully
        }
    }
    
    return false; // Product not found in cart
}


function display_cart() {
    global $_SESSION;
    
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['id']} (Quantity: {$item['quantity']})<br>";
    }
}


add_to_cart(123, 2);


update_cart_quantity(123, 3);


remove_from_cart(123);


display_cart();


<?php
// Initialize session
session_start();

// Check if cart is set, otherwise initialize it as empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add product to cart
function add_to_cart($product_id) {
    // Get current products in cart
    $cart_products = $_SESSION['cart'];

    // Add product to cart if it's not already there
    if (!in_array($product_id, $cart_products)) {
        array_push($cart_products, $product_id);
        $_SESSION['cart'] = $cart_products;
    }
}

// Function to remove product from cart
function remove_from_cart($product_id) {
    // Get current products in cart
    $cart_products = $_SESSION['cart'];

    // Remove product from cart if it's there
    if (in_array($product_id, $cart_products)) {
        $index = array_search($product_id, $cart_products);
        unset($cart_products[$index]);
        $_SESSION['cart'] = $cart_products;
    }
}

// Function to update quantity of product in cart
function update_quantity($product_id, $quantity) {
    // Get current products in cart
    $cart_products = $_SESSION['cart'];

    // Update quantity if product is in cart
    if (in_array($product_id, $cart_products)) {
        foreach ($cart_products as $key => $pid) {
            if ($pid == $product_id) {
                $cart_products[$key] = array('id' => $product_id, 'quantity' => $quantity);
                $_SESSION['cart'] = $cart_products;
                break;
            }
        }
    }
}
?>


// Add product to cart
add_to_cart(1);

// Remove product from cart
remove_from_cart(1);

// Update quantity of product in cart
update_quantity(1, 2);


// Create a new cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to update quantity of existing item in cart
function update_cart_item($product_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['id']} | Quantity: {$item['quantity']}<br>";
    }
}


// Add item to cart
add_to_cart(1, 2);

// Update quantity of existing item in cart
update_cart_item(1, 3);

// Remove item from cart
remove_from_cart(1);

// Display cart contents
display_cart();


// cart.php

class Cart {
    private $sessionId;

    public function __construct() {
        $this->sessionId = session_id();
    }

    public function addProduct($productId, $quantity) {
        if (!isset($_SESSION[$this->sessionId]['cart'])) {
            $_SESSION[$this->sessionId]['cart'] = array();
        }
        $_SESSION[$this->sessionId]['cart'][$productId] = array(
            'quantity' => $quantity,
            'price' => // get product price from database
        );
    }

    public function removeProduct($productId) {
        if (isset($_SESSION[$this->sessionId]['cart'][$productId])) {
            unset($_SESSION[$this->sessionId]['cart'][$productId]);
        }
    }

    public function updateQuantity($productId, $quantity) {
        if (isset($_SESSION[$this->sessionId]['cart'][$productId])) {
            $_SESSION[$this->sessionId]['cart'][$productId]['quantity'] = $quantity;
        }
    }

    public function getTotal() {
        $total = 0;
        foreach ($_SESSION[$this->sessionId]['cart'] as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }

    public function getProducts() {
        return $_SESSION[$this->sessionId]['cart'];
    }
}


require_once 'cart.php';

$cart = new Cart();
session_start(); // start the session


// add product to cart
$cart->addProduct(1, 2);

// remove product from cart
$cart->removeProduct(1);

// update quantity of a product
$cart->updateQuantity(1, 3);


$products = $cart->getProducts();
foreach ($products as $product) {
    echo 'Product ID: ' . $product['id'] . '<br>';
    echo 'Quantity: ' . $product['quantity'] . '<br>';
    echo 'Price: ' . $product['price'] . '<br>';
}


$total = $cart->getTotal();
echo 'Cart Total: $' . number_format($total, 2);


<?php
// Initialize session
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item, $quantity) {
    global $_SESSION;
    $existingItem = false;

    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] == $item['id']) {
            $existingItem = true;
            $cartItem['quantity'] += $quantity;
            break;
        }
    }

    // Add new item to cart if not existing
    if (!$existingItem) {
        $_SESSION['cart'][] = array('id' => $item['id'], 'name' => $item['name'], 'price' => $item['price'], 'quantity' => $quantity);
    }
}

// Function to remove item from cart
function removeFromCart($itemId) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] == $itemId) {
            unset($cartItem);
            break;
        }
    }

    // Re-index array after removing item
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Function to update quantity of item in cart
function updateQuantity($itemId, $newQuantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] == $itemId) {
            $cartItem['quantity'] = $newQuantity;
            break;
        }
    }
}

// Example usage:
$item1 = array('id' => 1, 'name' => 'Product 1', 'price' => 9.99);
$item2 = array('id' => 2, 'name' => 'Product 2', 'price' => 19.99);

addToCart($item1, 2);
addToCart($item2, 3);

echo "Current cart: ";
print_r($_SESSION['cart']);

removeFromCart(1);
updateQuantity(2, 4);

echo "
Updated cart: ";
print_r($_SESSION['cart']);
?>


<?php
session_start();
?>


function addToCart($id, $name, $price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $itemExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // Increase quantity of existing item
            $item['quantity']++;
            $itemExists = true;
            break;
        }
    }

    if (!$itemExists) {
        $_SESSION['cart'][] = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
    }
}


function viewCart() {
    if (!isset($_SESSION['cart'])) {
        return "Your cart is empty.";
    }

    $total = 0;
    echo "Your Cart:<br>";
    foreach ($_SESSION['cart'] as $item) {
        $subTotal = $item['price'] * $item['quantity'];
        $total += $subTotal;
        echo "$item[name] x $item[quantity] = \$" . number_format($subTotal, 2) . "<br>";
    }
    echo "Total: $" . number_format($total, 2);
}


function removeFromCart($id) {
    if (isset($_SESSION['cart'])) {
        $newCart = array_filter($_SESSION['cart'], function ($item) use ($id) {
            return $item['id'] != $id;
        });
        $_SESSION['cart'] = $newCart;
    }
}


addToCart(1, "Product 1", 9.99);
addToCart(2, "Product 2", 4.99); // Product 1 quantity will be increased if it already exists in cart.


viewCart();


function isLoggedIn() {
    // Assuming you're using a database and sessions for user authentication
    return isset($_SESSION['user_id']);
}


function addToCart($product_id, $quantity = 1) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        return "You must log in to buy this product.";
    }
    
    // Set up product data (example: array of available products)
    $products = [1 => ['name' => 'Product A', 'price' => 10.99], 2 => ['name' => 'Product B', 'price' => 5.99]];
    
    // Get the current cart from session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product is already in cart and update quantity accordingly
    if (isset($_SESSION['cart'][$product_id])) {
        $quantity += $_SESSION['cart'][$product_id]['quantity'];
        unset($_SESSION['cart'][$product_id]);
    }
    
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = ['name' => $products[$product_id]['name'], 'price' => $products[$product_id]['price'], 'quantity' => $quantity];
}


function displayCart() {
    // Check if user is logged in
    if (!isLoggedIn()) {
        return "You must log in to view your cart.";
    }
    
    // Get current cart from session
    $cart = $_SESSION['cart'];
    
    // Display each item in the cart
    foreach ($cart as $item) {
        echo $item['name'] . ' x' . $item['quantity'] . ' = $' . (float)$item['price'] * (int)$item['quantity'] . '<br>';
    }
}


<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You must log in to access this page.";
} else {

    // Display cart button that calls addToCart when clicked
    ?>
    <button onclick="addToCart(1)">Buy Product A</button>
    <?php

    // To display the current cart contents, you would call displayCart();
    // For simplicity and assuming we're displaying it dynamically or on-demand,
    // I'll just include a basic echo here for demonstration:
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += (float)$item['price'] * (int)$item['quantity'];
    }
    echo 'Total: $' . $total;

    // Example function call to add an item to cart
    addToCart(2, 3);

}
?>

<button onclick="displayCart()">View Cart</button>


// Initialize the session
session_start();

// Set default cart values
$cart = array();
$total = 0;

// Function to add item to cart
function addToCart($item) {
    global $cart, $total;
    if (!isset($cart[$item['id']])) {
        $cart[$item['id']] = array(
            'quantity' => 1,
            'price' => $item['price'],
            'total' => $item['price']
        );
    } else {
        $cart[$item['id']]['quantity']++;
        $cart[$item['id']]['total'] += $item['price'];
    }
    $total = array_sum(array_column($cart, 'total'));
}

// Function to remove item from cart
function removeFromCart($itemId) {
    global $cart;
    if (isset($cart[$itemId])) {
        unset($cart[$itemId]);
    }
}

// Function to update quantity of an item in the cart
function updateQuantity($itemId, $newQuantity) {
    global $cart;
    if (isset($cart[$itemId])) {
        $cart[$itemId]['quantity'] = $newQuantity;
        $cart[$itemId]['total'] = $cart[$itemId]['price'] * $newQuantity;
    }
}

// Function to clear cart
function clearCart() {
    global $cart;
    $cart = array();
}


<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart</h1>

    <?php if (isset($_SESSION['cart'])) : ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php foreach ($cart as $item) : ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['total'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p>Total: <?= $total ?></p>

        <form action="" method="post">
            <input type="hidden" name="itemId" value="<?= $itemId ?>">
            <input type="text" name="newQuantity" placeholder="New quantity">
            <button type="submit">Update Quantity</button>
        </form>

        <a href="?action=remove&itemId=<?= $itemId ?>">Remove Item</a>

    <?php else : ?>
        <p>Cart is empty!</p>
    <?php endif; ?>

    <h2>Add item to cart:</h2>

    <form action="" method="post">
        <input type="text" name="name" placeholder="Name of item">
        <input type="number" name="price" placeholder="Price of item">
        <button type="submit">Add to Cart</button>
    </form>
</body>
</html>

<?php if (isset($_POST['action']) && $_POST['action'] == 'remove') {
    removeFromCart($_POST['itemId']);
} elseif (isset($_POST['action']) && $_POST['action'] == 'updateQuantity') {
    updateQuantity($_POST['itemId'], $_POST['newQuantity']);
} else {
    $itemName = $_POST['name'];
    $itemPrice = $_POST['price'];
    addToCart(array('id' => 1, 'name' => $itemName, 'price' => $itemPrice));
}


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item($product_id, $quantity) {
  global $_SESSION;
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

// Function to remove item from cart
function remove_item($product_id) {
  global $_SESSION;
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  global $_SESSION;
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $new_quantity;
  }
}

// Function to get total cost of items in cart
function get_total() {
  global $_SESSION;
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // assume we have a function to get the price of an item
    $price = get_price($product_id);
    $total += $price * $quantity;
  }
  return $total;
}

// Example usage:
add_item(1, 2); // add 2 units of product with ID 1
remove_item(1); // remove product with ID 1 from cart
update_quantity(1, 3); // update quantity of product with ID 1 to 3

echo "Total: $" . get_total();


<form>
  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
  <input type="number" name="quantity" value="1">
  <button type="submit">Add to Cart</button>
</form>


<?php foreach ($_SESSION['cart'] as $product_id => $quantity) { ?>
  <p><?php echo "Product ID: $product_id (Quantity: $quantity)"; ?></p>
<?php } ?>

<p>Total: <?php echo get_total(); ?></p>


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function add_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment the quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


function remove_from_cart($product_id) {
    global $_SESSION;

    // Find and remove item from the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            unset($item);
            break;
        }
    }

    // Re-index array to remove gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}


function update_cart_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Find and update item quantity in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}


function display_cart() {
    global $_SESSION;

    // Print out each item in the cart
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['id']} | Quantity: {$item['quantity']}<br>";
    }
}


// Add item to cart
add_to_cart(1, 2);

// Remove item from cart
remove_from_cart(1);

// Update quantity of item in cart
update_cart_quantity(3, 4);

// Display cart contents
display_cart();


<?php
session_start();
?>


function add_to_cart($item_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$item_id] = array(
            'quantity' => $quantity,
            'price' => get_item_price($item_id)
        );
    } else {
        $_SESSION['cart'] = array($item_id => array('quantity' => $quantity, 'price' => get_item_price($item_id)));
    }
}


function remove_from_cart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


function update_quantity($item_id, $new_quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}


function display_cart() {
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Item ID</th><th>Quantity</th><th>Total Price</th></tr>';
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo '<tr>';
        echo '<td>' . $item_id . '</td>';
        echo '<td>' . $item_data['quantity'] . '</td>';
        echo '<td>' . $item_data['price'] * $item_data['quantity'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}


// Initialize session
session_start();

// Add items to cart
add_to_cart(1, 2);
add_to_cart(3, 1);

// Display cart contents
display_cart();

// Update quantity of existing item
update_quantity(1, 3);

// Remove item from cart
remove_from_cart(3);

// Display updated cart contents
display_cart();


class Cart {
  private $cart;

  function __construct() {
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }
    $this->cart = $_SESSION['cart'];
  }

  function add_item($product_id, $quantity) {
    if (isset($this->cart[$product_id])) {
      $this->cart[$product_id] += $quantity;
    } else {
      $this->cart[$product_id] = $quantity;
    }
    $_SESSION['cart'] = $this->cart;
  }

  function remove_item($product_id) {
    if (isset($this->cart[$product_id])) {
      unset($this->cart[$product_id]);
      $_SESSION['cart'] = $this->cart;
    }
  }

  function update_quantity($product_id, $quantity) {
    if (isset($this->cart[$product_id])) {
      $this->cart[$product_id] = $quantity;
      $_SESSION['cart'] = $this->cart;
    }
  }

  function get_cart() {
    return $this->cart;
  }

  function get_total() {
    $total = 0;
    foreach ($this->cart as $id => $quantity) {
      // assume you have a function to retrieve product prices
      $price = get_product_price($id);
      $total += $price * $quantity;
    }
    return $total;
  }
}


// create an instance of the Cart class
$cart = new Cart();

// add items to cart
$cart->add_item(1, 2); // product_id = 1, quantity = 2
$cart->add_item(2, 3);

// remove item from cart
$cart->remove_item(1);

// update quantity of item in cart
$cart->update_quantity(2, 4);

// get current cart contents
$cart_contents = $cart->get_cart();
print_r($cart_contents); // array containing product IDs and quantities

// get total cost of items in cart
$total_cost = $cart->get_total();
echo "Total: $" . $total_cost;


<?php
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not, redirect to login page
    header('Location: login.php');
    exit;
}

// Get the current user's username
$username = $_SESSION['username'];

// Create a cart session for the user (if it doesn't exist already)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    global $username;
    global $_SESSION;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'][$username] as $item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            break;
        }
    }

    // If not, add a new item to the cart
    if (!isset($_SESSION['cart'][$username][$product_id])) {
        $_SESSION['cart'][$username][$product_id] = array(
            'id' => $product_id,
            'quantity' => $quantity
        );
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    global $username;
    global $_SESSION;

    // Remove the product from the cart
    unset($_SESSION['cart'][$username][$product_id]);
}

// Function to update a product's quantity in the cart
function updateQuantity($product_id, $quantity) {
    global $username;
    global $_SESSION;

    // Update the product's quantity in the cart
    foreach ($_SESSION['cart'][$username] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            break;
        }
    }
}

// Add some example products to the cart
addToCart(1, 2);
addToCart(2, 3);

// Display the current cart contents
echo "Current Cart Contents:
";
foreach ($_SESSION['cart'][$username] as $item) {
    echo "$item[id]: $item[quantity]
";
}
?>


<?php
// Set the session variables.
ini_set('session.use_only_cookies', 1);
ini_set('session.name', 'your_session_name');
ini_set('session.gc_probability', 5); // Change this value based on your requirements

// Create or resume a session as needed.
session_start();

// Set the cookie's lifespan
setcookie(session_name(), '', time() - 2592000, "/", false, false);
?>


<?php
// Connect to your database.
$conn = mysqli_connect("localhost", "username", "password", "your_database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// Function to add an item to the cart
function addToCart($productId, $productName, $price, $quantity)
{
    global $conn;
    
    // Check if product is already in cart.
    $query = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."' AND product_id = '$productId'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // If item exists in the cart, update its quantity.
        while ($row = mysqli_fetch_assoc($result)) {
            $updateQuery = "UPDATE cart SET quantity = quantity + '$quantity' WHERE user_id = '".$_SESSION['user_id']."' AND product_id = '$productId'";
            mysqli_query($conn, $updateQuery);
        }
    } else {
        // If item doesn't exist in the cart, add it.
        $insertQuery = "INSERT INTO cart (user_id, product_id, name, price, quantity) VALUES ('".$_SESSION['user_id']."', '$productId', '$productName', '$price', '$quantity')";
        mysqli_query($conn, $insertQuery);
    }
}

// Function to view the cart
function viewCart()
{
    global $conn;
    
    // Retrieve all items in the user's cart.
    $query = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Product Name: $row[name] - Price: $" . number_format($row['price']) . " - Quantity: $row[quantity]<br>";
        }
    } else {
        echo "Your cart is empty.";
    }

    // Close the database connection
    mysqli_close($conn);
}

// Example usage:
if (isset($_POST['add_item'])) {
    addToCart($_POST['product_id'], $_POST['name'], $_POST['price'], $_POST['quantity']);
} elseif (isset($_POST['view_cart'])) {
    viewCart();
}
?>


// Start the session
session_start();

// Initialize the cart as an empty array
$_SESSION['cart'] = array();

// Set some other useful variables (optional)
$_SESSION['total_cost'] = 0;
$_SESSION['num_items'] = 0;


function add_product_to_cart($product_id) {
    // Get the current products in the cart
    $cart = $_SESSION['cart'];

    // Check if the product is already in the cart
    foreach ($cart as $key => $value) {
        if ($value['id'] == $product_id) {
            // If it's already there, increment the quantity
            $cart[$key]['quantity']++;
            break;
        }
    }

    // If not, add it to the cart with a quantity of 1
    else {
        $cart[] = array('id' => $product_id, 'quantity' => 1);
    }

    // Update the total cost and number of items
    $_SESSION['total_cost'] += get_product_price($product_id) * count(array_filter($cart, function($product) use ($product_id) { return $product['id'] == $product_id; }));
    $_SESSION['num_items'] = count($_SESSION['cart']);

    // Save the updated cart to the session
    $_SESSION['cart'] = array_values($cart);
}


function remove_product_from_cart($product_id) {
    // Get the current products in the cart
    $cart = $_SESSION['cart'];

    // Find and remove the product from the cart
    foreach ($cart as $key => $value) {
        if ($value['id'] == $product_id) {
            unset($cart[$key]);
            break;
        }
    }

    // Update the total cost and number of items
    $_SESSION['total_cost'] -= get_product_price($product_id);
    $_SESSION['num_items'] = count($_SESSION['cart']);

    // Save the updated cart to the session
    $_SESSION['cart'] = array_values($cart);
}


function view_cart() {
    $cart = $_SESSION['cart'];
    echo "Cart Contents:
";
    foreach ($cart as $product) {
        echo "$" . get_product_price($product['id']) . " x " . $product['quantity'] . "
";
    }
    echo "Total Cost: $" . $_SESSION['total_cost'] . "
";
}


session_start();

// Initialize the cart as an empty array
$_SESSION['cart'] = array();

// Set some other useful variables (optional)
$_SESSION['total_cost'] = 0;
$_SESSION['num_items'] = 0;

function add_product_to_cart($product_id) {
    // Get the current products in the cart
    $cart = $_SESSION['cart'];

    // Check if the product is already in the cart
    foreach ($cart as $key => $value) {
        if ($value['id'] == $product_id) {
            // If it's already there, increment the quantity
            $cart[$key]['quantity']++;
            break;
        }
    }

    // If not, add it to the cart with a quantity of 1
    else {
        $cart[] = array('id' => $product_id, 'quantity' => 1);
    }

    // Update the total cost and number of items
    $_SESSION['total_cost'] += get_product_price($product_id) * count(array_filter($cart, function($product) use ($product_id) { return $product['id'] == $product_id; }));
    $_SESSION['num_items'] = count($_SESSION['cart']);

    // Save the updated cart to the session
    $_SESSION['cart'] = array_values($cart);
}

function remove_product_from_cart($product_id) {
    // Get the current products in the cart
    $cart = $_SESSION['cart'];

    // Find and remove the product from the cart
    foreach ($cart as $key => $value) {
        if ($value['id'] == $product_id) {
            unset($cart[$key]);
            break;
        }
    }

    // Update the total cost and number of items
    $_SESSION['total_cost'] -= get_product_price($product_id);
    $_SESSION['num_items'] = count($_SESSION['cart']);

    // Save the updated cart to the session
    $_SESSION['cart'] = array_values($cart);
}

function view_cart() {
    $cart = $_SESSION['cart'];
    echo "Cart Contents:
";
    foreach ($cart as $product) {
        echo "$" . get_product_price($product['id']) . " x " . $product['quantity'] . "
";
    }
    echo "Total Cost: $" . $_SESSION['total_cost'] . "
";
}

// Example usage
add_product_to_cart(1);
view_cart();
remove_product_from_cart(1);
view_cart();


<?php

// Configuration
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add product to cart
function add_to_cart($product_id, $quantity) {
    global $conn;
    
    // Check if product already exists in cart
    $query = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."' AND product_id = '".$product_id."'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        // Update quantity of existing product
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $price = $row['price'];
        
        $update_query = "UPDATE cart SET quantity = '".$new_quantity."', price = '".$price."' WHERE id = '".$row['id']."'";
        $conn->query($update_query);
    } else {
        // Insert new product into cart
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity, price) VALUES ('".$_SESSION['user_id']."', '".$product_id."', '".$quantity."', 'NULL')";
        $conn->query($insert_query);
    }
}

// Function to remove product from cart
function remove_from_cart($product_id) {
    global $conn;
    
    // Delete product from cart
    $delete_query = "DELETE FROM cart WHERE user_id = '".$_SESSION['user_id']."' AND product_id = '".$product_id."'";
    $conn->query($delete_query);
}

// Function to update quantity of product in cart
function update_quantity($product_id, $new_quantity) {
    global $conn;
    
    // Update quantity of product in cart
    $update_query = "UPDATE cart SET quantity = '".$new_quantity."' WHERE user_id = '".$_SESSION['user_id']."' AND product_id = '".$product_id."'";
    $conn->query($update_query);
}

// Function to get products in cart
function get_cart() {
    global $conn;
    
    // Get products in cart
    $query = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."'";
    $result = $conn->query($query);
    
    return $result;
}

?>


<?php

// Include cart.php
include 'cart.php';

// Get products in cart
$products = get_cart();

// Display cart contents
echo "<h2>Cart Contents:</h2>";
echo "<table border='1'>";
echo "<tr><th>Product</th><th>Quantity</th><th>Total Price</th></tr>";

foreach ($products as $product) {
    echo "<tr>";
    echo "<td>".$product['name']."</td>";
    echo "<td>".$product['quantity']."</td>";
    echo "<td>".$product['price']." * ".$product['quantity']."</td>";
    echo "</tr>";
}

echo "</table>";

?>


<?php

// Include cart.php
include 'cart.php';

// Update quantity of product in cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    
    update_quantity($product_id, $new_quantity);
}

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    add_to_cart($product_id, $quantity);
}

// Remove product from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    
    remove_from_cart($product_id);
}

?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        // If product already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'name' => '', // will be populated later
            'price' => 0.00,
            'quantity' => $quantity
        );
    }
}

// Remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update quantity of existing item in cart
function update_quantity_in_cart($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Display cart contents
function display_cart() {
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo '<p>' . $item['name'] . ' x ' . $item['quantity'] . '</p>';
    }
}

// Example usage:
add_item_to_cart(1, 2); // Add product with ID 1 to cart in quantity of 2
add_item_to_cart(1, 3); // Increment quantity of product with ID 1 by 3

display_cart(); // Display contents of cart

remove_item_from_cart(1); // Remove product with ID 1 from cart

update_quantity_in_cart(1, 5); // Update quantity of product with ID 1 to 5


<?php

// Include the database connection file
require_once 'db.php';

// Create a session
if (!isset($_SESSION)) {
    session_start();
}

// Add product to cart
function add_product_to_cart($product_id) {
    global $conn;
    
    // Check if the product already exists in the cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute(array($_SESSION['user_id'], $product_id));
    $result = $stmt->fetchAll();
    
    if ($result) {
        // If it does, update the quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute(array($_SESSION['user_id'], $product_id));
    } else {
        // Otherwise add a new entry to the cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute(array($_SESSION['user_id'], $product_id));
    }
}

// Remove product from cart
function remove_product_from_cart($product_id) {
    global $conn;
    
    // Delete the entry from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute(array($_SESSION['user_id'], $product_id));
}

// View products in cart
function view_cart() {
    global $conn;
    
    // Get all entries from the cart for this user
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute(array($_SESSION['user_id']));
    $result = $stmt->fetchAll();
    
    return $result;
}

// Example usage:
if (isset($_GET['add'])) {
    add_product_to_cart($_GET['id']);
} elseif (isset($_GET['remove'])) {
    remove_product_from_cart($_GET['id']);
}

// Get the products in the cart
$cart = view_cart();

?>


<?php

// Your database connection settings
$dbhost = 'localhost';
$dbname = 'your_database_name';
$dbuser = 'your_username';
$dbpass = 'your_password';

$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

?>


<?php
// Start the session
session_start();

// Check if the cart is already set in the session, or initialize it if not
if (!isset($_SESSION['cart'])) {
    // Initialize an empty array for the cart contents
    $_SESSION['cart'] = array();
}

// Example function to add a product to the cart
function addToCart($id, $name, $price) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item[0] == $id) {
            // If it's there, increment its quantity
            $item[2]++;
            return; // Do nothing else
        }
    }
    
    // If not, add it to the cart with a quantity of 1
    $_SESSION['cart'][] = array($id, $name, 1);
}

// Example function to update quantities in the cart
function updateCart($id, $newQuantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item[0] == $id) {
            // Update the quantity
            $item[2] = $newQuantity;
            return; // Do nothing else
        }
    }
}

// Example function to remove items from the cart
function removeFromCart($id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item[0] == $id) {
            unset($_SESSION['cart'][$key]);
            return; // Do nothing else
        }
    }
}

// Example function to display the contents of the cart
function displayCart() {
    global $_SESSION;
    
    echo "Your Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[1] x $item[2]: $" . number_format($item[2] * $item[2], 2) . "<br>";
    }
}

// Add an item to the cart
addToCart(101, 'Product 1', 19.99);

// Update quantity of an item in the cart
updateCart(101, 3);

// Remove an item from the cart
removeFromCart(101);

// Display the current contents of the cart
displayCart();

?>


<?php
session_start();

// Check if the cart is already in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($product_id, $quantity) {
    global $_SESSION;
    
    // Get product details from database (replace with your own logic)
    $product = getProductDetails($product_id);
    
    if ($product !== false) {
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = array(
                'product' => $product,
                'quantity' => 0
            );
        }
        
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($product_id) {
    global $_SESSION;
    
    unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of an item in cart
function updateQuantity($product_id, $new_quantity) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get total cart value
function getTotalCartValue() {
    global $_SESSION;
    
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['product']['price'] * $item['quantity'];
    }
    
    return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
    updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

// Display cart contents
?>
<h1>Cart Contents:</h1>
<ul>
<?php foreach ($_SESSION['cart'] as $item) { ?>
    <li>
        <?php echo $item['product']['name']; ?> (x<?php echo $item['quantity']; ?>)
        <a href="#" onclick="updateQuantity(<?php echo $item['product']['id']; ?>, 1)">+</a> |
        <a href="#" onclick="removeFromCart(<?php echo $item['product']['id']; ?>)">Remove</a>
    </li>
<?php } ?>
</ul>

<h2>Total: <?php echo getTotalCartValue(); ?></h2>


// Replace with your own database logic to get product details
function getProductDetails($product_id) {
    // Example data
    $products = array(
        1 => array('name' => 'Product 1', 'price' => 19.99),
        2 => array('name' => 'Product 2', 'price' => 9.99)
    );
    
    return isset($products[$product_id]) ? $products[$product_id] : false;
}


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($item_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$item_name])) {
        $_SESSION['cart'][$item_name] = array(
            'name' => $item_name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        // Item already exists, increment quantity
        $existing_item = $_SESSION['cart'][$item_name];
        $existing_item['quantity'] += $quantity;
    }
}


function update_quantity($item_name, $new_quantity) {
    if (isset($_SESSION['cart'][$item_name])) {
        $_SESSION['cart'][$item_name]['quantity'] = $new_quantity;
    }
}

function remove_item($item_name) {
    if (isset($_SESSION['cart'][$item_name])) {
        unset($_SESSION['cart'][$item_name]);
    }
}


function display_cart() {
    if (isset($_SESSION['cart'])) {
        echo '<h2>Cart Contents:</h2>';
        foreach ($_SESSION['cart'] as $item_name => $details) {
            echo "<p>" . $details['name'] . " x " . $details['quantity'] . "</p>";
        }
    } else {
        echo 'No items in cart.';
    }
}


// Initialize cart session variable
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add items to cart
add_to_cart('Apple', 1.99);
add_to_cart('Banana', 0.49, 3);

// Display cart contents
display_cart();

// Update quantity of an item
update_quantity('Apple', 2);

// Remove an item from the cart
remove_item('Banana');


session_start();


$_SESSION['cart'] = array();


function add_to_cart($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity if product is already in cart
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => get_product_name($product_id), // assume this function exists
        'price' => get_product_price($product_id), // assume this function exists
        'quantity' => $quantity
    );
}


function view_cart() {
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[name] x $item[quantity] = $" . ($item['price'] * $item['quantity']) . "<br>";
    }
}


// Initialize cart session
session_start();
$_SESSION['cart'] = array();

// Add items to cart
add_to_cart(1, 2); // add product with ID 1 in quantity 2
add_to_cart(3, 4); // add product with ID 3 in quantity 4

// View cart contents
view_cart();


session_start();


$cart = array(
    'products' => array(),
    'subtotal' => 0
);


function add_product_to_cart($product_id, $quantity) {
    global $cart;
    
    // Check if the product is already in the cart
    foreach ($cart['products'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }
    
    // If not, add it to the cart
    else {
        $cart['products'][] = array(
            'id' => $product_id,
            'quantity' => $quantity
        );
        
        // Update the subtotal
        $cart['subtotal'] += $product_id * $quantity;
    }
}


function get_cart_contents() {
    global $cart;
    
    return $cart;
}


function remove_product_from_cart($product_id) {
    global $cart;
    
    // Check if the product is in the cart
    foreach ($cart['products'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart['products'][$key]);
            break;
        }
    }
}


// Set up the cart data structure
$cart = array(
    'products' => array(),
    'subtotal' => 0
);

// Add some products to the cart
add_product_to_cart(1, 2);
add_product_to_cart(2, 3);

// Display the cart contents
print_r(get_cart_contents());

// Remove a product from the cart
remove_product_from_cart(2);

// Display the updated cart contents
print_r(get_cart_contents());


Array
(
    [products] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [quantity] => 2
                )

            [1] => Array
                (
                    [id] => 2
                    [quantity] => 3
                )

        )

    [subtotal] => 11
)

Array
(
    [products] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [quantity] => 2
                )

        )

    [subtotal] => 2
)


<?php
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($item_id) {
    global $cart;
    $cart[] = $item_id;
    $_SESSION['cart'] = $cart;
}

// Remove item from cart
function remove_item_from_cart($item_id) {
    global $cart;
    foreach ($cart as $key => $value) {
        if ($value == $item_id) {
            unset($cart[$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($cart);
}

// Display cart contents
function display_cart() {
    global $cart;
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $item_id) {
        // Retrieve item details from database (e.g. using item_id)
        $item_details = retrieve_item_details($item_id);
        echo "$item_id: $item_details[title] x $item_details[quantity]<br>";
    }
}

// Function to retrieve item details from database
function retrieve_item_details($item_id) {
    // Connect to database and execute query to retrieve item details
    // ...
    return array('title' => 'Item 1', 'quantity' => 2); // example data
}

// Example usage:
add_item_to_cart(123);
display_cart();

?>


<?php
require 'cart.php';

add_item_to_cart(123);
add_item_to_cart(456);

display_cart();

remove_item_from_cart(123);

?>


<?php
session_start();

// Check if the cart is already in the session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            return true; // Item already in cart, increment quantity
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
    return false; // New item added to cart
}

// Function to remove item from cart
function removeFromCart($item_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return true; // Item removed from cart
        }
    }
    return false; // Item not found in cart
}

// Function to update quantity of an item in the cart
function updateQuantity($item_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated successfully
        }
    }
    return false; // Item not found in cart
}

// Function to display cart contents
function displayCart() {
    global $_SESSION;
    
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Item ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'];
        echo '<br>';
    }
}

// Example usage
if (isset($_POST['add_to_cart'])) {
    addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
    updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

displayCart();
?>


class Product {
    public $id;
    public $name;
    public $price;

    function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}


class Cart {
    private $_items;
    private $_session;

    function __construct($session) {
        $this->_session = $session;
        $this->_items = array();
    }

    function add_item($product, $quantity) {
        if (isset($this->_items[$product->id])) {
            $this->_items[$product->id]['quantity'] += $quantity;
        } else {
            $this->_items[$product->id] = array('product' => $product, 'quantity' => $quantity);
        }
    }

    function remove_item($product_id) {
        if (isset($this->_items[$product_id])) {
            unset($this->_items[$product_id]);
        }
    }

    function update_quantity($product_id, $new_quantity) {
        if (isset($this->_items[$product_id])) {
            $this->_items[$product_id]['quantity'] = $new_quantity;
        }
    }

    function get_total() {
        $total = 0;
        foreach ($this->_items as $item) {
            $total += $item['product']->price * $item['quantity'];
        }
        return $total;
    }

    function display_cart() {
        echo "Cart items:<br>";
        foreach ($this->_items as $item) {
            echo "- {$item['product']->name} x{$item['quantity']} = {$item['product']->price * $item['quantity']}$<br>";
        }
        echo "Total: {$this->get_total()}$";
    }

    function save_cart() {
        $_SESSION[$this->_session] = serialize($this->_items);
    }

    function load_cart() {
        if (isset($_SESSION[$this->_session])) {
            $this->_items = unserialize($_SESSION[$this->_session]);
        }
    }
}


$session_key = 'user_cart';

// Create a new cart object
$cart = new Cart($session_key);

// Add some products to the cart
$product1 = new Product(1, "Product 1", 10.99);
$product2 = new Product(2, "Product 2", 5.99);
$cart->add_item($product1, 3);
$cart->add_item($product2, 4);

// Display the cart
$cart->display_cart();

// Save the cart to session
$cart->save_cart();


// Start the session
session_start();

// Check if the cart is already set
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id) {
    global $db; // assuming you have a database connection object

    // Get product details from the database
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db, $query);
    $product = mysqli_fetch_assoc($result);

    // Add item to cart array
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'quantity' => 1,
            'price' => $product['price'],
            'name' => $product['name']
        );
    } else {
        // If the product is already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    }

    // Update cart total and item count
    update_cart_total();
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $db; // assuming you have a database connection object

    // Remove item from cart array
    unset($_SESSION['cart'][$product_id]);

    // Update cart total and item count
    update_cart_total();
}

// Function to update cart total and item count
function update_cart_total() {
    global $db; // assuming you have a database connection object

    // Calculate cart subtotal
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $query = "SELECT price FROM products WHERE id = '$product_id'";
        $result = mysqli_query($db, $query);
        $price = mysqli_fetch_assoc($result)['price'];
        $subtotal += $item['quantity'] * $price;
    }

    // Update session with cart total and item count
    $_SESSION['cart_total'] = $subtotal;
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

// Function to display cart contents
function display_cart() {
    global $db; // assuming you have a database connection object

    // Output cart contents as HTML table
    echo '<table>';
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $query = "SELECT * FROM products WHERE id = '$product_id'";
        $result = mysqli_query($db, $query);
        $product = mysqli_fetch_assoc($result);

        echo '<tr>';
        echo '<td>' . $product['name'] . '</td>';
        echo '<td>' . $item['quantity'] . ' x ' . $product['price'] . '</td>';
        echo '<td>' . ($item['quantity'] * $product['price']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';

    // Output cart total and item count
    echo '<p>Total: $' . $_SESSION['cart_total'] . ' (Items: ' . $_SESSION['cart_count'] . ')</p>';
}


// Add an item to the cart
add_to_cart(1);

// Display the cart contents
display_cart();

// Remove an item from the cart
remove_from_cart(1);


<?php
session_start();

// Set up default values for the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($product_id, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Function to remove item from cart
function removeFromCart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
}

// Remove a product from the cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Update quantity of a product in the cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($product_id, $new_quantity);
}

// Display the current state of the cart
?>
<div>
    <h2>Cart:</h2>
    <?php foreach ($_SESSION['cart'] as $product_id => $item) { ?>
        <div>
            <b><?php echo $product_id; ?></b>
            (<a href="#" class="remove" data-product-id="<?php echo $product_id; ?>">Remove</a>)
            Quantity: <?php echo $item['quantity']; ?>
            (<input type="number" value="<?php echo $item['quantity']; ?>" id="update_quantity_<?php echo $product_id; ?>" name="update_quantity" data-product-id="<?php echo $product_id; ?>">
            <button class="update">Update</button>)
        </div>
    <?php } ?>
</div>

<form action="" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    Product ID: <input type="text" name="product_id"><br>
    Quantity: <input type="number" name="quantity"><br>
    <button class="add">Add to Cart</button>
</form>

<form action="" method="post">
    <input type="hidden" name="remove_from_cart" value="1">
    Product ID: <input type="text" name="product_id"><br>
    <button class="remove">Remove from Cart</button>
</form>

<form action="" method="post">
    <input type="hidden" name="update_quantity" value="1">
    Product ID: <select name="product_id">
        <?php foreach ($_SESSION['cart'] as $product_id => $item) { ?>
            <option value="<?php echo $product_id; ?>"><?php echo $product_id; ?></option>
        <?php } ?>
    </select>
    Quantity: <input type="number" id="update_quantity_<?php echo $product_id; ?>" name="quantity"><br>
    <button class="update">Update</button>
</form>

<script>
    $(document).ready(function() {
        $('.remove').on('click', function(e) {
            e.preventDefault();
            var product_id = $(this).data('product-id');
            removeFromCart(product_id);
            updateCartDisplay();
        });

        $('.add').on('click', function(e) {
            e.preventDefault();
            var product_id = $('input[name="product_id"]').val();
            var quantity = $('input[name="quantity"]').val();
            addToCart(product_id, quantity);
            updateCartDisplay();
        });

        $('.update').on('click', function(e) {
            e.preventDefault();
            var product_id = $(this).data('product-id');
            var new_quantity = $('#update_quantity_' + product_id).val();
            updateQuantity(product_id, new_quantity);
            updateCartDisplay();
        });
    });

    function updateCartDisplay() {
        $.ajax({
            type: 'GET',
            url: 'cart.php',
            dataType: 'html',
            success: function(data) {
                $('#cart-display').html(data);
            }
        });
    }

</script>


class Cart {
    private $items;

    public function __construct() {
        $this->items = array();
    }

    public function add($item, $quantity) {
        if (array_key_exists($item, $this->items)) {
            $this->items[$item] += $quantity;
        } else {
            $this->items[$item] = $quantity;
        }
    }

    public function remove($item) {
        if (array_key_exists($item, $this->items)) {
            unset($this->items[$item]);
        }
    }

    public function getItems() {
        return $this->items;
    }
}


session_start();

$cart = new Cart();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = $_POST['item'];
    $quantity = (int) $_POST['quantity'];

    $cart->add($item, $quantity);
}


if (isset($_GET['remove'])) {
    $item = $_GET['remove'];

    $cart->remove($item);
}


<?php
session_start();

$cart = new Cart();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = $_POST['item'];
    $quantity = (int) $_POST['quantity'];

    $cart->add($item, $quantity);
}

if (isset($_GET['remove'])) {
    $item = $_GET['remove'];

    $cart->remove($item);
}
?>

<form action="" method="post">
    <input type="text" name="item" placeholder="Item Name">
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<ul>
    <?php foreach ($cart->getItems() as $item => $quantity): ?>
        <li><?= $item ?> x <?= $quantity ?></li>
    <?php endforeach; ?>
</ul>

<?php foreach ($cart->getItems() as $item => $quantity): ?>
    <a href="?remove=<?= $item ?>" class="remove-item">Remove Item</a>
<?php endforeach; ?>


<?php

// Initialize the session
session_start();

// Function to manage cart items
function updateCart($action = null, $item_id = null, $product_name = null, $quantity = 1) {
    global $cart;

    // Check if there's an existing cart session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array('items' => array());
    }

    // If we're adding or removing a specific item from the cart
    if ($action === 'add' || $action === 'remove') {

        $foundItem = false;

        foreach ($_SESSION['cart']['items'] as &$item) {
            if ($item['id'] == $item_id) {
                // Update quantity based on action
                if ($action === 'add') {
                    $item['quantity'] += $quantity;
                } elseif ($action === 'remove') {
                    $item['quantity'] -= $quantity;

                    // If item's quantity reaches 0, remove it from cart
                    if ($item['quantity'] <= 0) {
                        unset($item);
                        $foundItem = true;
                    }
                }

                break; // Exit the loop since we've updated this item
            }
        }

        if (!$foundItem && $action === 'add') {
            // Add new item to cart
            $_SESSION['cart']['items'][] = array('id' => $item_id, 'name' => $product_name, 'quantity' => $quantity);
        } elseif ($action === 'remove') {
            // If the action was remove and we didn't find an existing item or quantity is reduced to 0, add new item
            if (count($_SESSION['cart']['items']) > 1) {
                $_SESSION['cart']['items'][] = array('id' => $item_id, 'name' => $product_name, 'quantity' => 0);
            }
        }

    } // End of adding/removing specific items

    // Save updated cart to session
    $_SESSION['cart'] = array('items' => $_SESSION['cart']['items']);

}

// Example usage:
// Add an item with ID 1 and name "Product A" to the cart.
updateCart('add', 1, 'Product A');

// Remove one quantity of item with ID 1 from the cart.
updateCart('remove', 1);

?>


// Starting the session
session_start();

// Assuming we have a function to get product information based on its ID

function getProduct($id) {
    // This is just a placeholder; you'd replace it with your actual database query or whatever method you're using.
    $products = array(
        '1' => array('name' => 'Product 1', 'price' => 9.99),
        '2' => array('name' => 'Product 2', 'price' => 19.99),
    );
    
    // Return the product details
    return $products[$id];
}

// Adding a product to the cart

function addProductToCart($id, $quantity = 1) {
    global $product; // Assuming you have a global variable for the product
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    $product = getProduct($id);
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // Increment quantity of existing item
            $item['quantity'] += $quantity;
            
            return; // Do not add another instance to the cart, we've incremented the existing one.
        }
    }
    
    // Add a new entry for this product into the cart
    $_SESSION['cart'][] = array('id' => $id, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity);
}

// Example usage:
addProductToCart(1); // Add 1 quantity of Product 1 to the cart


<?php
session_start();

// Check if cart is already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
function addToCart($item_id) {
    // Get the current cart
    $cart = $_SESSION['cart'];

    // Check if item is already in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Increment quantity
            $item['quantity']++;
            return;
        }
    }

    // Add new item to cart
    $cart[] = array(
        'id' => $item_id,
        'quantity' => 1
    );

    // Update session cart
    $_SESSION['cart'] = $cart;
}
?>


<?php
function viewCart() {
    // Get the current cart
    $cart = $_SESSION['cart'];

    // Display cart contents
    echo '<h2>Your Cart</h2>';
    foreach ($cart as $item) {
        echo '<p>ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '</p>';
    }
}
?>


<?php
function removeFromCart($item_id) {
    // Get the current cart
    $cart = $_SESSION['cart'];

    // Find and remove item from cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            unset($item);
            break;
        }
    }

    // Update session cart
    $_SESSION['cart'] = array_filter($cart);
}
?>


<?php
// Add item to cart
addToCart(1);

// View cart contents
viewCart();

// Remove item from cart
removeFromCart(1);
?>


<?php
session_start();

// Check if the 'cart' session variable exists; create it if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add items to cart
function add_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if item already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If the item is already in cart, increase its quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart if not already present
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to remove items from cart
function remove_from_cart($item_id) {
    global $_SESSION;
    
    // Remove the first occurrence of the item with id=$item_id
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }

    // If item not found, do nothing
}

// Function to update quantity of an item in cart
function update_quantity($item_id, $new_quantity) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Example of adding items to cart
$item1_id = 123; // Item ID (replace with actual item ID)
$quantity1 = 2;

$item2_id = 456; // Another item ID
$quantity2 = 3;

add_to_cart($item1_id, $quantity1);
add_to_cart($item2_id, $quantity2);

// Display cart contents
print_r($_SESSION['cart']);

// Clear the entire cart
$_SESSION['cart'] = array();

// Remove one specific item from cart
remove_from_cart(123); // Assuming item with ID 123 exists in cart

// Update quantity of an existing item
update_quantity(456, 4); // Change quantity of item with ID 456 to 4

?>


// Set session variables for cart
session_start();
$_SESSION['cart'] = array();

function add_to_cart($item_id, $quantity) {
  // Check if item is already in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $item_id) {
      // If item is already in cart, increment quantity
      $item['quantity'] += $quantity;
      return;
    }
  }

  // Add new item to cart
  $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

function update_cart_item($item_id, $new_quantity) {
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $item_id) {
      // Update quantity of existing item
      $item['quantity'] = $new_quantity;
      return;
    }
  }
}

function remove_from_cart($item_id) {
  // Remove item from cart by ID
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $item_id) {
      unset($_SESSION['cart'][$key]);
      return;
    }
  }
}

function display_cart() {
  echo '<h2>Cart:</h2>';
  foreach ($_SESSION['cart'] as $item) {
    echo 'Item ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
  }
}


// Start session (already done in cart.php)
// Add item to cart
add_to_cart(1, 2); // adds item with ID 1, quantity 2

// Display cart
display_cart();

// Update item quantity
update_cart_item(1, 3); // updates quantity of item with ID 1 to 3

// Remove item from cart
remove_from_cart(1);


<?php
// Step 1: Initialize the Session
session_start();

// Step 2: If cart data is not already in session, create an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Step 3: Define functions for adding items to and displaying the cart

function add_item($item_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        // If item already in cart, update quantity
        $_SESSION['cart'][$item_id] += $quantity;
    } else {
        // Add new item to cart with specified quantity
        $_SESSION['cart'][$item_id] = $quantity;
    }
}

function display_cart() {
    global $_SESSION;
    if (!empty($_SESSION['cart'])) {
        echo "Cart Contents:<br>";
        foreach ($_SESSION['cart'] as $item => $quantity) {
            // Display item ID and quantity
            echo "$item: $quantity<br>";
        }
    } else {
        echo "Your cart is empty.<br>";
    }
}

// Step 4: Example of adding an item to the cart
// Add a book with ID 'book1' and quantity of 2
add_item('book1', 2);

// Display the contents of the cart
display_cart();

// Step 5: Remove an item from the cart
function remove_item($item_id) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Example of removing an item from the cart
remove_item('book1');
display_cart();

?>


session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}


function view_cart() {
    global $_SESSION;
    
    echo "<h2>Cart:</h2>";
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Retrieve product details from database (assuming we have a function to do this)
        $product_name = get_product_name($product_id);
        $price = get_product_price($product_id);
        
        echo "$product_name ($quantity x \$${price})<br>";
        $total += $price * $quantity;
    }
    
    echo "<p>Total: \$$total</p>";
}


function remove_from_cart($product_id) {
    global $_SESSION;
    
    unset($_SESSION['cart'][$product_id]);
}


// Start session
session_start();

// Add product to cart (e.g. when user clicks "Add to Cart" button)
add_to_cart(1);

// View cart
view_cart();

// Remove product from cart (e.g. when user clicks "Remove" button next to a product)
remove_from_cart(1);


<?php

// Initialize cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_item_to_cart($id, $name, $price) {
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // Update quantity if item is already in cart
            $item['quantity']++;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
}

function remove_item_from_cart($id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

function update_cart() {
    // Display cart contents
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<p>Item ID: ' . $item['id'] . ', Name: ' . $item['name'] . ', Price: ' . $item['price'] . ', Quantity: ' . $item['quantity'] . '</p>';
    }

    // Display total cost
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    echo '<h2>Total Cost: $' . number_format($total, 2) . '</h2>';
}

// Example usage:
add_item_to_cart(1, 'Product A', 19.99);
add_item_to_cart(2, 'Product B', 9.99);
remove_item_from_cart(1);

update_cart();

?>


<?php

// Sample product data
$products = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 19.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 9.99)
);

?>


<?php
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it with an empty array
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id) {
    global $_SESSION;
    
    // Check if product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it is, increment the quantity of that item
            $item['quantity']++;
            return;
        }
    }

    // If not, add new item to the cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => 1);
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update quantity of item in cart
function update_quantity($product_id, $quantity) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Example usage:
add_to_cart(1); // Add product with ID 1 to the cart
add_to_cart(2); // Add product with ID 2 to the cart

print_r($_SESSION['cart']); // Output: Array ( [0] => Array ( [product_id] => 1 [quantity] => 1 ) [1] => Array ( [product_id] => 2 [quantity] => 1 ) )

remove_from_cart(1); // Remove product with ID 1 from the cart

update_quantity(2, 3); // Update quantity of product with ID 2 to 3

print_r($_SESSION['cart']); // Output: Array ( [0] => Array ( [product_id] => 2 [quantity] => 3 ) )
?>


<?php

// Start session management
session_start();

// Set a default value for the cart in case it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id && $item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product is in the cart, quantity updated.
        }
    }

    // If product is not already in the cart, add it
    $_SESSION['cart'][] = array('id' => uniqid(), 'product_id' => $product_id, 'quantity' => $quantity);
}

function display_cart() {
    global $_SESSION;
    
    echo "Your Cart:
";
    
    if (empty($_SESSION['cart'])) {
        echo "No items in your cart.
";
    } else {
        foreach ($_SESSION['cart'] as $item) {
            echo "$item[quantity] x Product ID: $item[product_id]
";
        }
        
        // Display total quantity for each product
        $total_quantities = array();
        foreach ($_SESSION['cart'] as $item) {
            if (isset($total_quantities[$item['product_id']])) {
                $total_quantities[$item['product_id']] += $item['quantity'];
            } else {
                $total_quantities[$item['product_id']] = $item['quantity'];
            }
        }
        
        echo "Total Items: ".array_sum(array_values($total_quantities))."
";
    }
}

// Example usage
add_to_cart(1, 2); // Add product with id 1 in quantity of 2 to the cart.
display_cart(); // Display the contents of your cart.

?>


<?php
// Start the session
session_start();

// Define the cart items array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['product_id'] == $product_id) {
            // If item is already in cart, increment quantity
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            return;
        }
    }
    
    // If product is not in cart, add it
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to update item quantity in cart
function update_item_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find the product in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // Update the quantity
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    
    // Find the product in the cart and delete it
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    add_item_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['update_quantity'])) {
    update_item_quantity($_POST['product_id'], $_POST['new_quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    remove_item_from_cart($_POST['product_id']);
}

// Print the cart contents
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

// Don't forget to close the session when you're done!
session_write_close();
?>


<?php
include 'cart.php';

// Example HTML form to add item to cart:
?>
<form action="cart.php" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <label>Quantity:</label>
    <input type="text" name="quantity"><br><br>
    <input type="submit" value="Add to Cart">
</form>

<?php
// Example HTML form to update item quantity:
?>
<form action="cart.php" method="post">
    <input type="hidden" name="update_quantity" value="1">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <label>New Quantity:</label>
    <input type="text" name="new_quantity"><br><br>
    <input type="submit" value="Update Quantity">
</form>

<?php
// Example HTML form to remove item from cart:
?>
<form action="cart.php" method="post">
    <input type="hidden" name="remove_from_cart" value="1">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <input type="submit" value="Remove from Cart">
</form>


<?php
// Initialize the session
session_start();

// Check if the cart is already in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($itemId) {
    global $cart;
    if (in_array($itemId, $cart)) {
        echo "Item already in cart!";
        return false;
    }
    $cart[] = $itemId;
    $_SESSION['cart'] = $cart;
    return true;
}

// Function to remove item from cart
function removeFromCart($itemId) {
    global $cart;
    if (!in_array($itemId, $cart)) {
        echo "Item not in cart!";
        return false;
    }
    unset($cart[array_search($itemId, $cart)]);
    $_SESSION['cart'] = $cart;
    return true;
}

// Function to view cart contents
function viewCart() {
    global $cart;
    foreach ($cart as $item) {
        echo "Item: $item <br>";
    }
}
?>


<?php
require_once 'cart.php';

// Add item to cart
addToCart(1);
addToCart(2);

// View cart contents
viewCart();

// Remove item from cart
removeFromCart(2);

// View cart contents again
viewCart();
?>


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add items to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it's already there, increment the quantity
            $item['quantity'] += $quantity;
            break;
        }
    }

    // If it's not already in the cart, add a new item
    if (!isset($item)) {
        $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    }
}

// Display the cart contents
?>


<?php
session_start();

// Display the cart contents
echo '<h2>Cart Contents</h2>';
echo '<table border="1">';
echo '<tr><th>Product ID</th><th>Quantity</th></tr>';

foreach ($_SESSION['cart'] as $item) {
    echo '<tr><td>' . $item['id'] . '</td><td>' . $item['quantity'] . '</td></tr>';
}

echo '</table>';
?>


<?php
session_start();

// Create a form to add products to the cart
?>

<form action="index.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">

    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>


<?php
session_start();
?>


$_SESSION['cart'] = array(); // Initialize empty cart if it doesn't exist


function addItemToCart($itemID, $quantity) {
    global $_SESSION;
    
    // Check if item already exists in cart
    if (array_key_exists('cart_'.$itemID, $_SESSION['cart'])) {
        // Update quantity of existing item
        $_SESSION['cart']['cart_'.$itemID] += $quantity;
    } else {
        // Add new item to cart with specified quantity
        $_SESSION['cart']['cart_'.$itemID] = $quantity;
    }
}


function removeFromCart($itemID) {
    global $_SESSION;
    
    // Check if item exists in cart
    if (array_key_exists('cart_'.$itemID, $_SESSION['cart'])) {
        unset($_SESSION['cart']['cart_'.$itemID]);
        
        // Re-index array after removing an element
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}


// Example to display each item's quantity in the cart
foreach ($_SESSION['cart'] as $itemID => $quantity) {
    echo "Item ID: $itemID, Quantity: $quantity<br>";
}


// Example to calculate total quantity of all items in the cart
$totalQuantity = 0;
foreach ($_SESSION['cart'] as $quantity) {
    $totalQuantity += $quantity;
}
echo "Total Quantity: $totalQuantity";


<?php

// Start session
session_start();

// Initialize cart if it doesn't exist
$_SESSION['cart'] = array();

function addItemToCart($itemID, $quantity) {
    global $_SESSION;
    
    // Check if item already exists in cart
    if (array_key_exists('cart_'.$itemID, $_SESSION['cart'])) {
        // Update quantity of existing item
        $_SESSION['cart']['cart_'.$itemID] += $quantity;
    } else {
        // Add new item to cart with specified quantity
        $_SESSION['cart']['cart_'.$itemID] = $quantity;
    }
}

function removeFromCart($itemID) {
    global $_SESSION;
    
    // Check if item exists in cart
    if (array_key_exists('cart_'.$itemID, $_SESSION['cart'])) {
        unset($_SESSION['cart']['cart_'.$itemID]);
        
        // Re-index array after removing an element
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Example usage:
addItemToCart(1, 2); // Add item ID 1 with quantity 2 to cart

echo "Cart contents:<br>";
foreach ($_SESSION['cart'] as $itemID => $quantity) {
    echo "Item ID: $itemID, Quantity: $quantity<br>";
}
?>


<?php

// Assuming 'items' is an array where each key is a product ID and each value is another array with details about the item (quantity and price)
function add_to_cart($product_id) {
    global $cart;
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if the product is already in the cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If it is, increment its quantity by 1
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // If not, add it with a quantity of 1 and default price (you might want to adjust this)
        $default_price = 10; // Replace with actual product price or database query
        
        $_SESSION['cart'][$product_id] = [
            'price' => $default_price,
            'quantity' => 1
        ];
    }
    
    // Update session data
    $_SESSION['cart'] = array_filter($_SESSION['cart']);
}

function remove_from_cart($product_id) {
    global $cart;
    
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
        
        // If cart is empty after removing an item, delete the 'cart' index
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
        
        // Update session data
        $_SESSION['cart'] = array_filter($_SESSION['cart']);
    }
}

function update_quantity($product_id, $quantity) {
    global $cart;
    
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] = max(1, $quantity); // Ensure quantity doesn't go below 1
        
        // Update session data
        $_SESSION['cart'] = array_filter($_SESSION['cart']);
    }
}

function get_cart_total() {
    global $cart;
    
    return array_sum(array_map(function($item) {
        return $item['price'] * $item['quantity'];
    }, $_SESSION['cart']));
}


// Assuming you have a page where items are displayed with 'Add to Cart' buttons

// Include the cart management functions file
include_once 'cart_functions.php';

// Start session if it hasn't been started already
if (!session_start()) {
    die('Could not start session');
}

// Example usage:
add_to_cart(123); // Adds item with ID 123 to cart
update_quantity(123, 2); // Updates quantity of item in cart
remove_from_cart(123); // Removes item from cart

echo get_cart_total(); // Outputs the total cost of items in cart


<?php
try {
    // Starting session management if it has not been started
    if (!isset($_SESSION)) {
        session_start();
    }

    function add_item_to_cart($product_id) {
        $cart = &$_SESSION['cart'];
        if (array_key_exists($product_id, $cart)) {
            $cart[$product_id] += 1;
        } else {
            $cart[$product_id] = 1;
        }
    }

    function remove_item_from_cart($product_id) {
        $cart = &$_SESSION['cart'];
        if (array_key_exists($product_id, $cart)) {
            unset($cart[$product_id]);
        }
    }

    // Display cart contents
    function display_cart() {
        $cart = $_SESSION['cart'] ?? [];
        foreach ($cart as $product_id => $quantity) {
            echo "Product ID: $product_id - Quantity: $quantity<br>";
        }
    }
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "
";
}
?>


<?php
// When button is clicked (via AJAX)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['productId'];
    add_item_to_cart($product_id);
}
?>


<?php display_cart(); ?>


session.save_handler = files


class Cart {
    private $sessionId;

    public function __construct() {
        $this->sessionId = session_id();
    }

    public function add($itemId, $quantity) {
        if (!isset($_SESSION[$this->sessionId]['items'])) {
            $_SESSION[$this->sessionId]['items'] = array();
        }
        $_SESSION[$this->sessionId]['items'][$itemId] = array(
            'quantity' => (int)$quantity,
            'totalPrice' => 0
        );
    }

    public function remove($itemId) {
        if (isset($_SESSION[$this->sessionId]['items'][$itemId])) {
            unset($_SESSION[$this->sessionId]['items'][$itemId]);
        }
    }

    public function updateQuantity($itemId, $newQuantity) {
        if (isset($_SESSION[$this->sessionId]['items'][$itemId])) {
            $_SESSION[$this->sessionId]['items'][$itemId]['quantity'] = (int)$newQuantity;
        }
    }

    public function getCartContents() {
        return isset($_SESSION[$this->sessionId]['items']) ? $_SESSION[$this->sessionId]['items'] : array();
    }

    public function getTotalPrice() {
        $total = 0;
        foreach ($_SESSION[$this->sessionId]['items'] as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }
}


require_once 'cart.php';

$cart = new Cart();

// Add an item to the cart
$cart->add(1, 2);

// Update the quantity of an item in the cart
$cart->updateQuantity(1, 3);

// Remove an item from the cart
$cart->remove(1);

// Get the total price of items in the cart
$totalPrice = $cart->getTotalPrice();


<?php
session_start();

// Check if the cart already exists in the session
if (!isset($_SESSION['cart'])) {
    // If not, create a new array to hold the cart items
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return true; // Product was added to existing item
        }
    }

    // If not, add a new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return true; // Product was added to cart
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true; // Product was removed from cart
        }
    }

    // If not, do nothing
    return false;
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity was updated
        }
    }

    // If not, do nothing
    return false;
}

// Example usage:
add_to_cart(1, 2); // Add 2 items with id 1 to cart
remove_from_cart(1); // Remove all items with id 1 from cart
update_quantity(1, 3); // Update quantity of item with id 1 to 3

print_r($_SESSION['cart']); // Output: Array ( [0] => Array ( [id] => 1 [quantity] => 3 ) )
?>


<?php
session_start();

// Check if cart is already set in session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($item_id, $quantity) {
    global $_SESSION;
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it does, increment quantity
            $item['quantity'] += $quantity;
            return; // exit function early
        }
    }
    // If item doesn't exist in cart, add it with new quantity
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_from_cart($item_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            break; // exit loop early
        }
    }
}

// Function to update quantity of item in cart
function update_quantity($item_id, $quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $quantity;
            return; // exit function early
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    echo '<h2>Your Cart:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Item ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}

// Example usage:
add_to_cart(1, 2); // add item with id 1 and quantity 2
display_cart(); // display current cart contents

?>


$cartConfig = [
    'session_name' => 'user_cart',
    'product_key' => 'product_id',
    'quantity_key' => 'quantity'
];


class Cart {
    private $cartConfig;
    private $session;

    public function __construct($config) {
        $this->cartConfig = $config;
        $this->session = $_SESSION[$config['session_name']];
    }

    public function addProduct($product_id, $quantity = 1) {
        if (!isset($this->session[$this->cartConfig['product_key']])) {
            $this->session[$this->cartConfig['product_key']] = [];
        }
        
        // Check if product is already in cart
        foreach ($this->session[$this->cartConfig['product_key']] as &$product) {
            if ($product['id'] == $product_id) {
                $product['quantity'] += $quantity;
                break;
            }
        } else {
            $this->session[$this->cartConfig['product_key']][$product_id] = ['id' => $product_id, 'quantity' => $quantity];
        }

        $_SESSION[$this->cartConfig['session_name']] = $this->session;
    }

    public function removeProduct($product_id) {
        if (isset($this->session[$this->cartConfig['product_key']][$product_id])) {
            unset($this->session[$this->cartConfig['product_key']][$product_id]);
            $_SESSION[$this->cartConfig['session_name']] = $this->session;
        }
    }

    public function updateQuantity($product_id, $quantity) {
        if (isset($this->session[$this->cartConfig['product_key']][$product_id])) {
            $this->session[$this->cartConfig['product_key']][$product_id]['quantity'] = $quantity;
            $_SESSION[$this->cartConfig['session_name']] = $this->session;
        }
    }

    public function getCartContents() {
        return $this->session[$this->cartConfig['product_key']];
    }
}


$cart = new Cart($cartConfig);

// Add product 1 with quantity 2
$cart->addProduct(1, 2);

// Remove product 1
$cart->removeProduct(1);

// Update quantity of product 3
$cart->updateQuantity(3, 5);

// Get cart contents
print_r($cart->getCartContents());


// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add product to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it does, increment quantity
            $item['quantity'] += $quantity;
            return true; // Product was found and updated
        }
    }
    
    // If product doesn't exist in cart, add it
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return true; // Product added to cart
}

// Function to remove product from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            unset($item); // Remove item from array
            return true; // Product was removed
        }
    }
    return false; // Product not found in cart
}

// Function to update quantity of product in cart
function update_cart_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated
        }
    }
    return false; // Product not found in cart
}

// Function to get products in cart
function get_cart_contents() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}


// Assuming we're on a page where the user has added some products to their cart

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    add_to_cart($product_id, $quantity);
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    remove_from_cart($product_id);
}

if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    update_cart_quantity($product_id, $new_quantity);
}


$cart_contents = get_cart_contents();
echo '<pre>';
print_r($cart_contents);
echo '</pre>';


<?php

// Initialize the session
session_start();

// Initialize the cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add items to the cart
function add_item($product_id, $quantity) {
    global $_SESSION;
    // Check if product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it does, update quantity if adding more or removing items.
            if ($quantity > 0) {
                $item['quantity'] += $quantity;
            } else {
                unset($item);
                break; // Remove from the cart
            }
            return;
        }
    }
    
    // If not already in cart, add it with quantity
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Example: Add a product to the cart
add_item(1, 2); // Product ID 1, Quantity 2

// Calculate totals (simplified example)
function calculate_totals() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $price = 10.99; // Simplified price - should fetch from database
        $total += $price * $item['quantity'];
    }
    return $total;
}

// Display the cart and its total (example)
echo "Cart: ";
print_r($_SESSION['cart']);
echo "
Total: $" . calculate_totals();

?>


<?php
// Start the session
session_start();

// Define the cart array to store items in the cart
$cart = array();

// Check if the cart already exists in the session
if (isset($_SESSION['cart'])) {
    // If it does, retrieve the existing cart from the session
    $cart = $_SESSION['cart'];
} else {
    // If not, create a new empty cart and store it in the session
    $_SESSION['cart'] = array();
}

// Add item to the cart
function add_item($item_id, $quantity) {
    global $cart;
    
    // Check if the item is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, increment the quantity and update the item
            $item['quantity'] += $quantity;
            return true;
        }
    }
    
    // If not, add a new item to the cart
    $cart[] = array('id' => $item_id, 'quantity' => $quantity);
    return true;
}

// Remove item from the cart
function remove_item($item_id) {
    global $cart;
    
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $item_id) {
            // If it is, remove the item from the cart
            unset($cart[$key]);
            return true;
        }
    }
}

// Update item quantity in the cart
function update_quantity($item_id, $new_quantity) {
    global $cart;
    
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, update the quantity
            $item['quantity'] = $new_quantity;
            return true;
        }
    }
}

// Display cart contents
function display_cart() {
    global $cart;
    
    echo "<h2>Cart Contents:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item ID</th><th>Quantity</th></tr>";
    
    foreach ($cart as $item) {
        echo "<tr><td>" . $item['id'] . "</td><td>" . $item['quantity'] . "</td></tr>";
    }
    
    echo "</table>";
}

// Example usage
if (isset($_POST['add_to_cart'])) {
    add_item($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    remove_item($_POST['item_id']);
} elseif (isset($_POST['update_quantity'])) {
    update_quantity($_POST['item_id'], $_POST['new_quantity']);
}

display_cart();

// Save the cart to session
$_SESSION['cart'] = $cart;
?>


<?php include 'cart.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart Example</title>
</head>
<body>
    <h1>Product List:</h1>
    <ul>
        <li><a href="#" id="add_to_cart" data-product-id="123">Add to Cart (1)</a></li>
        <li><a href="#" id="add_to_cart" data-product-id="456">Add to Cart (2)</a></li>
    </ul>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('a#add_to_cart').click(function(e) {
                e.preventDefault();
                var productId = $(this).attr('data-product-id');
                var quantity = 1;
                
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: {'product_id': productId, 'quantity': quantity},
                    success: function(data) {
                        // Add item to cart
                    }
                });
            });
        });
    </script>
</body>
</html>


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already exists, update quantity
        }
    }
    
    // Add new item to cart array
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Remove item from cart array
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break; // Exit loop after removing item
        }
    }
}

function update_cart_quantity($product_id, $quantity) {
    global $_SESSION;
    
    // Update quantity of existing product in cart array
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            break; // Exit loop after updating item
        }
    }
}

// Example usage:
// add_to_cart(1, 2); // Add product with ID 1 to cart with quantity 2
?>


<?php include 'cart.php'; ?>

<form action="add_to_cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<?php
// Display cart contents
echo '<h2>Cart Contents:</h2>';
foreach ($_SESSION['cart'] as $item) {
    echo "Product ID: {$item['id']} ({$item['quantity']} units)<br>";
}
?>


<?php include 'cart.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    
    add_to_cart($product_id, $quantity);
}
?>


<?php include 'cart.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    
    remove_from_cart($product_id);
}
?>


<?php include 'cart.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    
    update_cart_quantity($product_id, $quantity);
}
?>


<?php

// Session setup - This part should be included in your common PHP file or wherever you include it throughout your application.
session_start();

// If there's no 'cart' session yet, initialize an empty array for it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function add_item_to_cart($item_id, $product_name, $price) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $item_id) {
            // If it's there, update its quantity instead of duplicating it.
            $product['quantity'] += 1;
            return; // Exit early to prevent adding duplicate entries
        }
    }

    // If not in the cart, add a new entry for this item.
    $_SESSION['cart'][] = [
        'id' => $item_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => 1
    ];
}

function display_cart() {
    global $_SESSION;
    
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $product) {
        echo "$product[name] (x$product[quantity]) - \${$product['price']}<br>";
    }
    echo "Total: ";
    // Simplified total calculation; real-world apps might need to handle discounts and taxes.
    $total = array_sum(array_map(function($product) { return $product['price'] * $product['quantity']; }, $_SESSION['cart']));
    echo number_format($total, 2);
}

function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $item_id) {
            // If new quantity is less than or equal to zero, remove this item from the cart.
            if ($new_quantity <= 0) {
                unset($product);
                break;
            }
            $product['quantity'] = $new_quantity;
            break; // Exit early once the item's quantity has been updated.
        }
    }
}

function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$product) {
        if ($product['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            break; // Exit early once the item has been removed.
        }
    }
}

// Example usage:
add_item_to_cart(1, 'Product 1', 9.99);
add_item_to_cart(2, 'Product 2', 19.99);
display_cart();
update_quantity(1, 3); // Update quantity of Product 1 to 3
remove_item_from_cart(2); // Remove Product 2 from the cart
display_cart();

?>


<?php
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $cart;
    if (array_key_exists($item_id, $cart)) {
        $cart[$item_id] += $quantity;
    } else {
        $cart[$item_id] = $quantity;
    }
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $cart;
    if (array_key_exists($item_id, $cart)) {
        unset($cart[$item_id]);
    }
}

// Function to update quantity of item in cart
function update_quantity_in_cart($item_id, $new_quantity) {
    global $cart;
    if (array_key_exists($item_id, $cart)) {
        $cart[$item_id] = $new_quantity;
    }
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    add_item_to_cart($item_id, $quantity);
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $item_id = $_POST['item_id'];
    remove_item_from_cart($item_id);
}

// Update quantity of item in cart
if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity_in_cart($item_id, $new_quantity);
}

?>


<?php
function display_cart() {
    global $cart;
    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item ID</th><th>Quantity</th></tr>";
    foreach ($cart as $item_id => $quantity) {
        echo "<tr><td>$item_id</td><td>$quantity</td></tr>";
    }
    echo "</table>";
}

function calculate_subtotal() {
    global $cart;
    $subtotal = 0;
    foreach ($cart as $item_id => $quantity) {
        $price = get_item_price($item_id);
        $subtotal += $price * $quantity;
    }
    return $subtotal;
}

function calculate_tax() {
    global $cart;
    $tax_rate = 0.08; // 8% tax rate
    $subtotal = calculate_subtotal();
    $tax_amount = $subtotal * $tax_rate;
    return $tax_amount;
}

function calculate_total() {
    global $cart;
    $subtotal = calculate_subtotal();
    $tax_amount = calculate_tax();
    $total = $subtotal + $tax_amount;
    return $total;
}
?>


<?php
require_once 'cart.php';
require_once 'cart_functions.php';

// Display cart
display_cart();

// Calculate subtotal, tax and total
echo "<p>Subtotal: $" . calculate_subtotal() . "</p>";
echo "<p>Tax (8%): $" . calculate_tax() . "</p>";
echo "<p>Total: $" . calculate_total() . "</p>";

// Add item to cart form
?>
<form action="" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    Item ID: <input type="text" name="item_id"><br>
    Quantity: <input type="text" name="quantity"><br>
    <input type="submit" value="Add to Cart">
</form>

// Remove item from cart form
?>
<form action="" method="post">
    <input type="hidden" name="remove_from_cart" value="1">
    Item ID: <input type="text" name="item_id"><br>
    <input type="submit" value="Remove from Cart">
</form>

// Update quantity of item in cart form
?>
<form action="" method="post">
    <input type="hidden" name="update_quantity" value="1">
    Item ID: <input type="text" name="item_id"><br>
    New Quantity: <input type="text" name="quantity"><br>
    <input type="submit" value="Update Quantity">
</form>

?>


session_start();


$_SESSION['cart'] = [
    'items' => [], // Array of item details
    'subtotal' => 0.00, // Total before tax
    'tax' => 0.00, // Tax amount
    'total' => 0.00, // Final total
];


function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    if (isset($_SESSION['cart']['items'][$product_id])) {
        $_SESSION['cart']['items'][$product_id] += $quantity;
    } else {
        $_SESSION['cart']['items'][$product_id] = $quantity;
        
        // Calculate new subtotal and save it
        $_SESSION['cart']['subtotal'] = calculate_subtotal();
        $_SESSION['cart']['tax'] = calculate_tax();
        $_SESSION['cart']['total'] = calculate_total();
    }
}

function calculate_subtotal() {
    global $_SESSION;
    
    $subtotal = 0.00;
    foreach ($_SESSION['cart']['items'] as $item) {
        $subtotal += ($item * 10); // Assuming price is in a database or variable '$price'
    }
    
    return round($subtotal, 2);
}

function calculate_tax() {
    global $_SESSION;
    $tax_rate = 0.05; // Example tax rate of 5%
    return round($_SESSION['cart']['subtotal'] * $tax_rate, 2);
}

function calculate_total() {
    global $_SESSION;
    
    return round($_SESSION['cart']['subtotal'] + $_SESSION['cart']['tax'], 2);
}


function update_item_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    if (isset($_SESSION['cart']['items'][$product_id])) {
        $_SESSION['cart']['items'][$product_id] = $new_quantity;
        
        // Recalculate subtotal and total accordingly
        $_SESSION['cart']['subtotal'] = calculate_subtotal();
        $_SESSION['cart']['tax'] = calculate_tax();
        $_SESSION['cart']['total'] = calculate_total();
    } else {
        echo "Item not found in cart.";
    }
}


function remove_from_cart($product_id) {
    global $_SESSION;
    
    if (isset($_SESSION['cart']['items'][$product_id])) {
        unset($_SESSION['cart']['items'][$product_id]);
        
        // Recalculate subtotal and total accordingly
        $_SESSION['cart']['subtotal'] = calculate_subtotal();
        $_SESSION['cart']['tax'] = calculate_tax();
        $_SESSION['cart']['total'] = calculate_total();
    } else {
        echo "Item not found in cart.";
    }
}


<?php
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    // Check if product exists in cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of an item in cart
function update_quantity_in_cart($product_id, $new_quantity) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Add a product to cart
add_item_to_cart(1, 2); // Product ID: 1, Quantity: 2

// Remove a product from cart
remove_item_from_cart(1);

// Update quantity of an item in cart
update_quantity_in_cart(3, 5); // Product ID: 3, New Quantity: 5

// Display cart contents
print_r($_SESSION['cart']);


<?php
session_start();

// Check if the cart already exists in the session
if (!isset($_SESSION['cart'])) {
    // If not, create a new array to store cart items
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it does, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function removeFromCart($item_id) {
    global $_SESSION;

    // Find the index of the item in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            unset($item);
            return;
        }
    }
}

// Function to update quantity of an item in cart
function updateQuantity($item_id, $quantity) {
    global $_SESSION;

    // Find the index of the item in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
    updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

// Print the contents of the cart
print_r($_SESSION['cart']);
?>


<?php
include 'cart.php';

// Example form to add item to cart
if (isset($_POST['add_to_cart'])) {
    addToCart($_POST['product_id'], $_POST['quantity']);
}
?>

<form action="" method="post">
    <input type="hidden" name="add_to_cart" value="true">
    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id"><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity"><br><br>
    <button type="submit">Add to Cart</button>
</form>

<!-- Display cart contents -->
<?php
print_r($_SESSION['cart']);
?>


class Cart {
    private $session_name = 'cart';

    public function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION[$this->session_name] = array();
    }

    public function addItem($product_id, $quantity) {
        if (array_key_exists($product_id, $_SESSION[$this->session_name])) {
            $_SESSION[$this->session_name][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION[$this->session_name][$product_id] = array('quantity' => $quantity);
        }
    }

    public function removeItem($product_id) {
        if (array_key_exists($product_id, $_SESSION[$this->session_name])) {
            unset($_SESSION[$this->session_name][$product_id]);
        }
    }

    public function updateQuantity($product_id, $new_quantity) {
        if (array_key_exists($product_id, $_SESSION[$this->session_name])) {
            $_SESSION[$this->session_name][$product_id]['quantity'] = $new_quantity;
        }
    }

    public function getItems() {
        return $_SESSION[$this->session_name];
    }

    public function getTotalQuantity() {
        $total = 0;
        foreach ($_SESSION[$this->session_name] as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }
}


require_once 'cart.php';

$cart = new Cart();

// Add item to cart
$cart->addItem(1, 2); // product_id 1 with quantity 2

// Get items in cart
$items = $cart->getItems();
print_r($items);

// Remove item from cart
$cart->removeItem(1);

// Update quantity of an item
$cart->updateQuantity(1, 3);


<?php
// Initialize the session
session_start();

// Set the cart array as a session variable
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        // Increment the quantity of existing item
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$item_id] = array(
            'item_id' => $item_id,
            'name' => 'Item Name', // You can retrieve the name from your database or a separate array
            'price' => 9.99, // You can retrieve the price from your database or a separate array
            'quantity' => $quantity
        );
    }
}

// Remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Update quantity of item in cart
function update_quantity_of_item_in_cart($item_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}

// Display cart contents
function display_cart_contents() {
    global $_SESSION;
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<p>ID: ' . $item['item_id'] . ', Name: ' . $item['name'] . ', Quantity: ' . $item['quantity'] . ', Price: $' . $item['price'] * $item['quantity'] . '</p>';
    }
}

// Example usage
add_item_to_cart(1, 2); // Add item with ID 1 to cart with quantity 2
remove_item_from_cart(1);
update_quantity_of_cart(1, 3);

display_cart_contents();
?>


<?php
// Check if the cart session is set, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($id, $quantity) {
    global $db;
    // Get product details from database
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        // Check if item is already in cart, if yes increment quantity
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] += $quantity;
                break;
            }
        }
        
        // If not found, add new item to cart
        if (!isset($item)) {
            $_SESSION['cart'][] = array(
                'id' => $id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            );
        }
    }
}

// Function to update quantity of item in cart
function updateQuantity($id, $newQuantity) {
    global $db;
    
    // Find the index of the item in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $newQuantity;
            break;
        }
    }
}

// Function to remove item from cart
function removeFromCart($id) {
    global $db;
    
    // Find the index of the item in cart and unset it
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            unset($item);
            break;
        }
    }
}

// Function to get total cost of items in cart
function getTotalCost() {
    global $db;
    
    // Calculate total cost by summing up the price * quantity of each item
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Example usage:
addToCart(1, 2); // Add product with id 1 to cart in quantity of 2
updateQuantity(1, 3); // Update quantity of product with id 1 to 3
removeFromCart(1); // Remove product with id 1 from cart

echo "Total cost: " . getTotalCost(); // Output total cost of items in cart
?>


<?php
// Start the session
session_start();

// Define a function to add items to the cart
function add_to_cart($product_id, $quantity) {
  // Check if the product exists in the cart already
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // If it does, increment the quantity
      $item['quantity'] += $quantity;
      return true;
    }
  }

  // If not, add a new item to the cart
  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'name' => get_product_name($product_id), // assuming we have a function to get product name from id
    'quantity' => $quantity
  );
}

// Define a function to remove items from the cart
function remove_from_cart($product_id) {
  // Check if the item exists in the cart
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return true;
    }
  }

  return false;
}

// Define a function to get the total cost of the items in the cart
function calculate_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as &$item) {
    $total += $item['price']; // assuming we have a price field for each product
  }
  return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
  add_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
  remove_from_cart($_POST['product_id']);
}
?>


// Cart Controller (cart.php)
session_start();


// Cart structure
$cart = [
    'items' => [],
    'total' => 0,
];


function addItemToCart($itemId, $itemName, $itemPrice, $quantity) {
    global $cart;

    // Check if item already exists in cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $itemId) {
            // Update quantity if item already exists
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $cart['items'][] = [
        'id' => $itemId,
        'name' => $itemName,
        'price' => $itemPrice,
        'quantity' => $quantity,
    ];

    // Update total cost
    $cart['total'] += ($itemPrice * $quantity);

    // Store cart data in session
    $_SESSION['cart'] = $cart;
}


function removeItemFromCart($itemId) {
    global $cart;

    // Find index of item to remove
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $itemId) {
            // Remove item from cart
            unset($item);
            return;
        }
    }

    // Update total cost
    $cart['total'] -= (array_search($itemId, array_column($cart['items'], 'id')) * $cart['items'][array_search($itemId, array_column($cart['items'], 'id'))]['price']);

    // Store cart data in session
    $_SESSION['cart'] = $cart;
}


function updateQuantity($itemId, $newQuantity) {
    global $cart;

    // Find item to update
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $itemId) {
            // Update quantity
            $item['quantity'] = $newQuantity;
            return;
        }
    }

    // Store cart data in session
    $_SESSION['cart'] = $cart;
}


// Add item to cart
addItemToCart(1, 'Item 1', 10.99, 2);

// Update quantity of item
updateQuantity(1, 3);

// Remove item from cart
removeItemFromCart(1);


<?php
// Start the session
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If not, add a new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find the index of the product in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            unset($item);
            return;
        }
    }
}

// Function to update an item's quantity in the cart
function update_cart_item($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find the index of the product in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to get the total cost of items in the cart
function get_cart_total() {
    global $_SESSION;
    
    // Initialize the total cost
    $total = 0;
    
    // Iterate over each item in the cart and add its cost to the total
    foreach ($_SESSION['cart'] as &$item) {
        $product_price = // Get the price of the product from your database
        $total += $product_price * $item['quantity'];
    }
    
    return $total;
}

// Example usage:
add_to_cart(1, 2); // Add 2 items with ID 1 to the cart
update_cart_item(1, 3); // Update the quantity of item with ID 1 in the cart
remove_from_cart(1); // Remove item with ID 1 from the cart

// Print the total cost of items in the cart
echo get_cart_total();
?>


<?php
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function add_product_to_cart($product_id) {
    global $_SESSION;

    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            $_SESSION['cart'][$key]['quantity']++;
            return;
        }
    }

    // Add new product to cart
    $new_item = array('id' => $product_id, 'quantity' => 1);
    $_SESSION['cart'][] = $new_item;
}


function remove_product_from_cart($product_id) {
    global $_SESSION;

    // Check if product is in cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function update_cart_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Check if product is in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}


function display_cart() {
    global $_SESSION;

    echo "Cart Contents:
";
    foreach ($_SESSION['cart'] as $item) {
        echo "- " . $item['id'] . ": " . $item['quantity'] . "
";
    }
}


// Add product to cart
add_product_to_cart(1);

// Update quantity of product in cart
update_cart_quantity(1, 2);

// Remove product from cart
remove_product_from_cart(1);

// Display cart contents
display_cart();


<?php
// Start the session
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add an item to the cart
function add_item_to_cart($product_id) {
    global $mysqli; // Assuming you're using a MySQLi database connection

    // Get product details from the database
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $product_name = $row['name'];
        $product_price = $row['price'];

        // Check if the product already exists in the cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                // If it exists, increment the quantity
                $item['quantity']++;
                break;
            }
        }

        // If not, add it to the cart
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = array(
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => 1
            );
        }
    }
}

// Display the cart contents
function display_cart_contents() {
    global $_SESSION;

    if (count($_SESSION['cart']) > 0) {
        echo '<h2>Cart Contents:</h2>';
        echo '<table border="1">';
        echo '<tr><th>Product</th><th>Price</th><th>Quantity</th></tr>';

        foreach ($_SESSION['cart'] as $item) {
            echo '<tr>';
            echo '<td>' . $item['name'] . '</td>';
            echo '<td>$' . number_format($item['price'], 2) . '</td>';
            echo '<td>' . $item['quantity'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';

        // Calculate the total cost
        $total_cost = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_cost += ($item['price'] * $item['quantity']);
        }
        echo '<p>Total Cost: $' . number_format($total_cost, 2) . '</p>';
    } else {
        echo 'Your cart is empty.';
    }
}

// Remove an item from the cart
function remove_item_from_cart($product_id) {
    global $_SESSION;

    unset($_SESSION['cart'][$product_id]);
}
?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Example</title>
</head>
<body>

<h1>Add an item to the cart:</h1>
<form action="" method="post">
    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id"><br><br>
    <input type="submit" value="Add to Cart">
</form>

<h1>Cart Contents:</h1>
<?php display_cart_contents(); ?>

<form action="" method="post">
    <input type="hidden" name="remove_product_id" value="<?php echo $_GET['id']; ?>">
    <input type="submit" value="Remove from Cart">
</form>

</body>
</html>


// If the form is submitted, add an item to the cart or remove one
if (isset($_POST['product_id'])) {
    add_item_to_cart($_POST['product_id']);
}

if (isset($_POST['remove_product_id'])) {
    remove_item_from_cart($_POST['remove_product_id']);
}


// config/cart.php

$cartConfig = [
    'session_name' => 'cart',
    'item_key'     => 'items',
    'quantity_key' => 'quantities'
];


// lib/cart.php

use Config\CartConfig;

class UserCartSession {

    private $cartConfig;
    private $session;

    public function __construct(CartConfig $cartConfig) {
        $this->cartConfig = $cartConfig;
        $this->initSession();
    }

    private function initSession() {
        if (!isset($_SESSION[$this->cartConfig['session_name']])) {
            $_SESSION[$this->cartConfig['session_name']] = [
                $this->cartConfig['item_key']     => [],
                $this->cartConfig['quantity_key'] => []
            ];
        }
    }

    public function addItem($productId, $quantity) {
        $items =& $_SESSION[$this->cartConfig['session_name']][$this->cartConfig['item_key']];
        if (isset($items[$productId])) {
            $items[$productId] += $quantity;
        } else {
            $items[$productId] = $quantity;
        }
    }

    public function removeItem($productId) {
        $items =& $_SESSION[$this->cartConfig['session_name']][$this->cartConfig['item_key']];
        unset($items[$productId]);
    }

    public function updateQuantity($productId, $newQuantity) {
        $items =& $_SESSION[$this->cartConfig['session_name']][$this->cartConfig['item_key']];
        $items[$productId] = $newQuantity;
    }

    public function getItems() {
        return $_SESSION[$this->cartConfig['session_name']][$this->cartConfig['item_key']];
    }

    public function getQuantities() {
        return $_SESSION[$this->cartConfig['session_name']][$this->cartConfig['quantity_key']];
    }
}


// index.php

require 'lib/cart.php';

$cart = new UserCartSession(new Config\CartConfig);

// Add item to cart
$cart->addItem(1, 2); // product ID: 1, quantity: 2

// Get items in cart
print_r($cart->getItems()); // Array ( [1] => 2 )

// Update quantity of item
$cart->updateQuantity(1, 3);

// Remove item from cart
$cart->removeItem(1);


// Initialize an empty session for cart

<?php
session_start();

// Set the cart as a session variable, it will hold products information
$_SESSION['cart'] = array();

// Function to add product to the cart
function addToCart($product_id, $product_name, $price) {
    global $_SESSION;
    
    // Check if the product is already in the cart.
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it's already there, increment the quantity
            $item['quantity']++;
            return true;  // Return true to indicate that addition was successful
        }
    }

    // If not found in the cart, add new product with default quantity of 1.
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => 1
    );

    return true; // Return true to indicate that addition was successful
}

// Function to remove a product from the cart
function removeFromCart($product_id) {
    global $_SESSION;
    
    // Find and remove item from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;  // Return true to indicate that removal was successful
        }
    }

    return false; // Return false to indicate failure in removing product
}

// Function to calculate total cost of products in cart
function calculateTotalCost() {
    global $_SESSION;
    
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total; // Return the total cost
}

// Example usage:

// Add products to cart
addToCart(1, 'Product A', 10.99);
addToCart(2, 'Product B', 9.99);

// Remove product from cart
removeFromCart(2);

// Calculate and display total cost of items in cart.
echo 'Total cost: ' . calculateTotalCost();



<?php
session_start();


function add_to_cart($item_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        // If the item is already in cart, update its quantity.
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['id'] == $item_id) {
                $cart_item['quantity'] += $quantity;
                return; // Exit early to prevent duplicate updates
            }
        }
        
        // If the item is not in cart, add it.
        $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
    } else {
        // Initialize cart if it doesn't exist yet.
        $_SESSION['cart'] = array(array('id' => $item_id, 'quantity' => $quantity));
    }
    
    // Save the session to persist data between requests
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

// Example usage
add_to_cart(1, 2); // Add 2 items with id 1 to cart


// Example usage for displaying cart content
if (isset($_SESSION['cart'])) {
    echo "<p>Cart Count: " . $_SESSION['cart_count'] . "</p>";
    
    foreach ($_SESSION['cart'] as $item) {
        echo "Item ID: {$item['id']} | Quantity: {$item['quantity']}";
        echo "<br>"; // Newline for readability
    }
} else {
    echo "Your cart is empty.";
}


session_start();


$cart = [
    'items' => [], // stores individual item data
    'subtotal' => 0, // subtotal of all items
    'tax' => 0,      // tax amount (assuming 20% VAT)
    'total' => 0     // total cost including tax
];


function addItem($item_id, $price, $quantity) {
    global $cart;
    
    // Check if item already exists in cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity; // increment quantity
            return;
        }
    }
    
    // Item not found, add new entry
    $cart['items'][] = [
        'id' => $item_id,
        'price' => $price,
        'quantity' => $quantity
    ];
    
    updateCart();
}


function updateCart() {
    global $cart;
    
    // Calculate subtotal
    $subtotal = 0;
    foreach ($cart['items'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    $cart['subtotal'] = $subtotal;
    
    // Calculate tax (20% VAT)
    $tax = $subtotal * 0.2;
    $cart['tax'] = $tax;
    
    // Calculate total
    $total = $subtotal + $tax;
    $cart['total'] = $total;
}


function removeItem($item_id) {
    global $cart;
    
    foreach ($cart['items'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($cart['items'][$key]);
            updateCart();
            return;
        }
    }
}


// Start the session
session_start();

// Define cart structure
$cart = [
    'items' => [],
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
];

// Add items to cart
addItem(1, 9.99, 2);
addItem(3, 14.99, 1);

// Print cart contents
print_r($cart);

// Remove item from cart
removeItem(1);

// Print updated cart contents
print_r($cart);


<?php

// Initialize session
session_start();

// Define an empty cart array in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example product data
$products = array(
    'product1' => array('name' => 'Product 1', 'price' => 9.99),
    'product2' => array('name' => 'Product 2', 'price' => 19.99)
);

// Add products to cart on page load
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

?>


// Display cart contents
echo "Cart Contents:<br>";
foreach ($_SESSION['cart'] as $product_id) {
    echo $products[$product_id]['name'] . " (x" . count(array_keys($products, $product_id)) . ") - $" . number_format($products[$product_id]['price'] * count(array_keys($products, $product_id)), 2) . "<br>";
}


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If not, add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Update quantity of item in cart
function update_cart($product_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Display cart contents
function display_cart() {
    global $_SESSION;
    
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['id']} (x{$item['quantity']})<br>";
    }
}
?>


add_to_cart(1, 2); // Add product with ID 1 to cart with quantity 2
remove_from_cart(1); // Remove product with ID 1 from cart
update_cart(1, 3); // Update quantity of product with ID 1 to 3
display_cart(); // Display contents of cart


session_start();


// Example form submission handler (add_item_to_cart.php)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    // Check if item is already in the cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Update quantity of existing items or add new ones
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = array('price' => 0.00, 'quantity' => $quantity);
    }

    // You can also update the price of the item in the cart here if it's known
}

// Redirect or continue to display the updated cart


// Example: display_cart.php
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "Your Cart:<br>";
    
    foreach ($_SESSION['cart'] as $item_id => $item) {
        // Here, you would typically fetch the item's name and price from your database.
        // For simplicity, let's assume we have this information in a session variable or can retrieve it directly.
        
        echo "Item ID: $item_id<br>";
        echo "Quantity: $item[quantity]<br>";
        echo "Price: $" . number_format($item['price'], 2) . "<br><hr>";
    }
    
    // Optional: You could calculate and display a total here using array_sum or similar.
} else {
    echo "Your cart is empty.";
}


// Remove specific item from the cart (example: remove_item.php)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


// Clear all items from the cart
session_destroy(); // This will remove all session variables.
$_SESSION = array();


<?php
session_start();
?>


$_SESSION['cart'] = array();


function add_to_cart($product_id, $product_name, $price) {
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price
    );
}


function view_cart() {
    return $_SESSION['cart'];
}


function update_cart_item($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

function remove_from_cart($product_id) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
        return $item['id'] != $product_id;
    });
}


// Start a new session
session_start();

// Create an empty cart array
$_SESSION['cart'] = array();

// Add some items to the cart
add_to_cart(1, 'Product 1', 10.99);
add_to_cart(2, 'Product 2', 5.99);

// View the cart contents
print_r(view_cart());

// Update an item in the cart
update_cart_item(1, 3);

// Remove an item from the cart
remove_from_cart(2);

// View the updated cart contents
print_r(view_cart());


class Cart {
  private $session;

  public function __construct() {
    $this->session = $_SESSION;
  }

  // Add item to cart
  public function add($product_id, $quantity) {
    if (!isset($this->session['cart'])) {
      $this->session['cart'] = array();
    }
    if (isset($this->session['cart'][$product_id])) {
      $this->session['cart'][$product_id]['quantity'] += $quantity;
    } else {
      $this->session['cart'][$product_id] = array('quantity' => $quantity);
    }
  }

  // Remove item from cart
  public function remove($product_id) {
    if (isset($this->session['cart'][$product_id])) {
      unset($this->session['cart'][$product_id]);
    } else {
      echo "Product not found in cart.";
    }
  }

  // Update quantity of an item in the cart
  public function update($product_id, $quantity) {
    if (isset($this->session['cart'][$product_id])) {
      $this->session['cart'][$product_id]['quantity'] = $quantity;
    } else {
      echo "Product not found in cart.";
    }
  }

  // Get the contents of the cart
  public function getCart() {
    if (isset($this->session['cart'])) {
      return $this->session['cart'];
    } else {
      return array();
    }
  }

  // Calculate total cost of items in the cart
  public function getTotalCost() {
    $total = 0;
    foreach ($this->getCart() as $product_id => $item) {
      $price = get_product_price($product_id); // Assuming you have a function to get product price
      $total += $price * $item['quantity'];
    }
    return $total;
  }

  // Empty the cart
  public function emptyCart() {
    unset($this->session['cart']);
  }
}


// Initialize session
$_SESSION['cart'] = array();

// Create an instance of the Cart class
$cart = new Cart();

// Add items to cart
$cart->add(1, 2); // Product ID 1 with quantity 2
$cart->add(3, 1); // Product ID 3 with quantity 1

// Get contents of cart
print_r($cart->getCart());

// Remove an item from the cart
$cart->remove(1);

// Update quantity of an item in the cart
$cart->update(3, 2);

// Calculate total cost of items in the cart
echo $cart->getTotalCost();

// Empty the cart
$cart->emptyCart();


<?php
session_start();
?>


$_SESSION['cart'] = array();


function add_item_to_cart($product_id, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => 0, 'price' => 0);
    }
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    // Calculate the total price
    $_SESSION['cart'][$product_id]['price'] = get_product_price($product_id) * $quantity;
}


function remove_item_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


function update_quantity_of_item_in_cart($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        // Calculate the total price
        $_SESSION['cart'][$product_id]['price'] = get_product_price($product_id) * $new_quantity;
    }
}


function get_cart_contents() {
    return $_SESSION['cart'];
}


function calculate_total_price() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'];
    }
    return $total;
}


// Add item to cart
add_item_to_cart(1, 2);

// Remove item from cart
remove_item_from_cart(1);

// Update quantity of item in cart
update_quantity_of_item_in_cart(1, 3);

// Get cart contents
$cart_contents = get_cart_contents();

// Calculate total price
$total_price = calculate_total_price();


<?php
session_start();
?>


// Function to add an item to the cart
function add_to_cart($user_id, $product_id, $product_name, $quantity, $price) {
    // If item is already in cart, update its quantity instead of adding it again
    $existing_item = get_from_cart($user_id, $product_id);
    if ($existing_item) {
        update_quantity_in_cart($existing_item['id'], $quantity);
    } else {
        // Add new item to the cart
        add_new_item_to_cart($user_id, $product_id, $product_name, $quantity, $price);
    }
}

// Function to get an item from the cart by its ID and product ID
function get_from_cart($user_id, $product_id) {
    global $db;
    // Assuming you have a database connection variable $db
    $query = "SELECT * FROM cart_items WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}

// Function to add new item to the cart
function add_new_item_to_cart($user_id, $product_id, $product_name, $quantity, $price) {
    global $db;
    // Assuming you have a database connection variable $db
    $query = "INSERT INTO cart_items (user_id, product_id, product_name, quantity, price)
              VALUES ('$user_id', '$product_id', '$product_name', '$quantity', '$price')";
    mysqli_query($db, $query);
}

// Function to update the quantity of an item in the cart
function update_quantity_in_cart($id, $new_quantity) {
    global $db;
    // Assuming you have a database connection variable $db
    $query = "UPDATE cart_items SET quantity = '$new_quantity' WHERE id = '$id'";
    mysqli_query($db, $query);
}

// Function to remove an item from the cart by its ID and product ID
function remove_from_cart($user_id, $product_id) {
    global $db;
    // Assuming you have a database connection variable $db
    $query = "DELETE FROM cart_items WHERE user_id = '$user_id' AND product_id = '$product_id'";
    mysqli_query($db, $query);
}

// Function to view the items in the cart for a given user ID
function get_cart_contents($user_id) {
    global $db;
    // Assuming you have a database connection variable $db
    $query = "SELECT * FROM cart_items WHERE user_id = '$user_id'";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


<?php
// Assuming session has started at the top of your file

// Add an item to the cart with a user ID (assuming it's set previously), product ID, name, quantity, and price.
add_to_cart($_SESSION['user_id'], 1, 'Product A', 2, 19.99);

// Update the quantity of an item in the cart
update_quantity_in_cart(1, 3); // Assuming you have an existing item with ID 1

// Remove an item from the cart
remove_from_cart($_SESSION['user_id'], 1);

// Get all items in the user's cart
$cart_contents = get_cart_contents($_SESSION['user_id']);
print_r($cart_contents);
?>


<?php
    session_start();
?>


function addProductToCart($productId) {
    global $cart; // Assuming 'global' since we can't use class properties directly without defining a class

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }
}

// Usage example
addProductToCart(1); // Adds product with ID 1 to the cart


function getCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    return $_SESSION['cart'];
}

// Usage example
$cartContents = getCart();
print_r($cartContents);


function removeProductFromCart($productId) {
    global $cart;

    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
        
        // If cart becomes empty, delete it to prevent unnecessary storage
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}

// Usage example
removeProductFromCart(1); // Removes product with ID 1 from the cart


function emptyCart() {
    global $cart;

    unset($_SESSION['cart']);
}


<?php
session_start();

function addProductToCart($productId) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }
}

function getCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    return $_SESSION['cart'];
}

function removeProductFromCart($productId) {
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
        
        // If cart becomes empty, delete it to prevent unnecessary storage
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}

function emptyCart() {
    unset($_SESSION['cart']);
}

// Example usage:
addProductToCart(1);  // Add product with ID 1
$cart = getCart();  // Get cart contents for display
print_r($cart);

removeProductFromCart(1);  // Remove product with ID 1

emptyCart();  // Empty the cart

session_destroy();  // End session (don't forget this)


<?php

// Check if the cart is already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>


function addToCart($itemId, $itemName, $itemPrice) {
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            // If item exists, update its quantity
            $item['quantity']++;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $itemId, 'name' => $itemName, 'price' => $itemPrice, 'quantity' => 1);
}


function removeFromCart($itemId) {
    // Check if item exists in cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $itemId) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }

    // If item doesn't exist, do nothing
}


function updateCartItemQuantity($itemId, $newQuantity) {
    // Check if item exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            // Update item's quantity
            $item['quantity'] = $newQuantity;
            return;
        }
    }

    // If item doesn't exist, do nothing
}


<?php

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    ?>
    <h2>Your Cart</h2>
    <table border="1">
        <tr>
            <th>Item Name</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        <?php
        foreach ($_SESSION['cart'] as $item) {
            ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}

?>


addToCart(1, 'Apple', 1.99);


removeFromCart(1);


updateCartItemQuantity(1, 2);


<?php
session_start();

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // Increase quantity of existing item
            $item['quantity'] += $quantity;
            return true; // Item added successfully
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
    return true; // Item added successfully
}

function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    foreach ($GLOBALS['_SESSION']['cart'] as &$index => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$index]);
            return true; // Item removed successfully
        }
    }
    
    return false; // Item not found in cart
}

function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($GLOBALS['_SESSION']['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated successfully
        }
    }
    
    return false; // Item not found in cart
}

// Example usage:
add_item_to_cart(1, 2); // Add item with id=1 and quantity=2 to cart
update_quantity(1, 3); // Update quantity of item with id=1 to 3
print_r($_SESSION['cart']); // Print the contents of the cart

?>


<?php
require_once 'cart.php';

// Display cart contents
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

?>

<!-- Add item form -->
<form action="" method="post">
    <input type="hidden" name="item_id" value="1">
    <input type="number" name="quantity" value="2">
    <button type="submit">Add to Cart</button>
</form>

<!-- Remove item link (example) -->
<a href="#" onclick="remove_item_from_cart(1)">Remove Item 1 from cart</a>


<?php
session_start();


// Initialize cart items array
$cart = [];

function add_to_cart($product_id, $quantity) {
  global $cart;
  if (isset($cart[$product_id])) {
    $cart[$product_id] += $quantity;
  } else {
    $cart[$product_id] = $quantity;
  }
}

function display_cart() {
  global $cart;
  echo "Cart Contents:<br>";
  foreach ($cart as $product_id => $quantity) {
    // Retrieve product name and price from database
    // For simplicity, let's assume we have a function to retrieve product details
    $product_name = get_product_name($product_id);
    $price = get_product_price($product_id);
    echo "$product_name x $quantity = $" . ($price * $quantity) . "<br>";
  }
}


<form action="" method="post">
  <label for="product_id">Product ID:</label>
  <input type="text" id="product_id" name="product_id"><br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity"><br><br>
  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<?php
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  add_to_cart($product_id, $quantity);
}


<?php
display_cart();
?>


<?php
session_start();

$cart = [];

function add_to_cart($product_id, $quantity) {
  global $cart;
  if (isset($cart[$product_id])) {
    $cart[$product_id] += $quantity;
  } else {
    $cart[$product_id] = $quantity;
  }
}

function display_cart() {
  global $cart;
  echo "Cart Contents:<br>";
  foreach ($cart as $product_id => $quantity) {
    // Retrieve product name and price from database
    // For simplicity, let's assume we have a function to retrieve product details
    $product_name = get_product_name($product_id);
    $price = get_product_price($product_id);
    echo "$product_name x $quantity = $" . ($price * $quantity) . "<br>";
  }
}

if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  add_to_cart($product_id, $quantity);
}

?>

<form action="" method="post">
  <label for="product_id">Product ID:</label>
  <input type="text" id="product_id" name="product_id"><br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity"><br><br>
  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<?php
display_cart();
?>


<?php
// Start the session
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example product data
$products = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 9.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 19.99),
    array('id' => 3, 'name' => 'Product C', 'price' => 29.99)
);

// Add products to cart
foreach ($products as $product) {
    if (isset($_POST['add_to_cart_'.$product['id']])) {
        $_SESSION['cart'][] = array(
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price']
        );
    }
}

// Display cart contents
echo "Cart Contents:<br>";
foreach ($_SESSION['cart'] as $item) {
    echo $item['name'] . " - $" . $item['price'] . "<br>";
}
?>


<?php
// Start the session
session_start();

// Display cart contents
echo "Cart Contents:<br>";
foreach ($_SESSION['cart'] as $item) {
    echo $item['name'] . " - $" . $item['price'] . "<br>";
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $_POST['remove_item']) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Update quantity of item in cart
if (isset($_POST['update_quantity'])) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $_POST['update_quantity']['id']) {
            $item['quantity'] = $_POST['update_quantity']['quantity'];
            break;
        }
    }
}

// Display cart contents again
echo "Cart Contents:<br>";
foreach ($_SESSION['cart'] as $item) {
    echo $item['name'] . " - $" . $item['price'] . "<br>";
}
?>


<?php
// Start the session
session_start();

// Add product to cart
$_SESSION['cart'][] = array(
    'id' => $_POST['product_id'],
    'name' => $_POST['product_name'],
    'price' => $_POST['product_price']
);

// Redirect back to index.php
header('Location: index.php');
exit;
?>


<?php
// Start the session
session_start();

// Remove item from cart
foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $_POST['product_id']) {
        unset($_SESSION['cart'][$key]);
        break;
    }
}

// Redirect back to index.php
header('Location: index.php');
exit;
?>


<?php
// Start the session
session_start();

// Update quantity of item in cart
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $_POST['product_id']) {
        $item['quantity'] = $_POST['quantity'];
        break;
    }
}

// Redirect back to index.php
header('Location: index.php');
exit;
?>


session_start();


function addItem($itemId, $itemName, $itemPrice, $cartId) {
    global $_SESSION;
    
    // Retrieve existing cart items or initialize a new empty array if it doesn't exist.
    if (!isset($_SESSION[$cartId]['items'])) {
        $_SESSION[$cartId]['items'] = [];
    }
    
    // Check if item is already in the cart to update quantity instead of adding a duplicate
    foreach ($_SESSION[$cartId]['items'] as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] += 1;
            return; // Return early since we've updated an existing item
        }
    }
    
    // Add new item to cart
    $_SESSION[$cartId]['items'][] = ['id' => $itemId, 'name' => $itemName, 'price' => $itemPrice, 'quantity' => 1];
}


function viewCart($cartId) {
    global $_SESSION;
    
    if (isset($_SESSION[$cartId]['items'])) {
        echo "Cart Contents:
";
        foreach ($_SESSION[$cartId]['items'] as $item) {
            echo "- Item ID: {$item['id']} - Quantity: {$item['quantity']} - Total Price: {$item['price']} * {$item['quantity']}
";
        }
    } else {
        echo "Your cart is empty.";
    }
}


function removeItem($itemId, $cartId) {
    global $_SESSION;
    
    // Filter out items to be deleted
    if (isset($_SESSION[$cartId]['items'])) {
        $updatedItems = array_filter($_SESSION[$cartId]['items'], function($item) use ($itemId) {
            return $item['id'] != $itemId;
        });
        
        $_SESSION[$cartId]['items'] = $updatedItems;
    }
}


session_start();

// Sample cart ID
$cartId = 'user_cart';

// Adding items to the cart
addItem('123', 'Sample Item 1', 10.99, $cartId);
addItem('456', 'Sample Item 2', 9.99, $cartId);

// Viewing the cart contents
viewCart($cartId);

// Removing an item from the cart
removeItem('123', $cartId);

// Again viewing the cart contents after removal
viewCart($cartId);


<?php
session_start();

// Function to add an item to the cart
function addItemToCart($itemName, $itemPrice) {
    // Check if the item is already in the cart
    if (!isset($_SESSION['cart'][$itemName])) {
        $_SESSION['cart'][$itemName] = array('quantity' => 1, 'price' => $itemPrice);
    } else {
        // If it's already in the cart, increment its quantity
        $_SESSION['cart'][$itemName]['quantity']++;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($itemName) {
    unset($_SESSION['cart'][$itemName]);
}

// Function to update the quantity of an item in the cart
function updateItemQuantity($itemName, $newQuantity) {
    if (isset($_SESSION['cart'][$itemName])) {
        $_SESSION['cart'][$itemName]['quantity'] = $newQuantity;
    }
}

// Function to calculate the total cost of items in the cart
function calculateTotalCost() {
    $totalCost = 0;
    foreach ($_SESSION['cart'] as $item => $details) {
        $totalCost += $details['price'] * $details['quantity'];
    }
    return $totalCost;
}

// Example usage:
addItemToCart('Apple', 1.99);
addItemToCart('Banana', 0.99);

echo "Items in cart: ";
print_r($_SESSION['cart']);

$removeItem = 'Banana';
removeItemFromCart($removeItem);

echo "
Updated items in cart: ";
print_r($_SESSION['cart']);

$updateQuantity = array('Apple' => 3);
updateItemQuantity(key($updateQuantity), $updateQuantity[key($updateQuantity)]);

echo "
Final items in cart: ";
print_r($_SESSION['cart']);
echo "
Total cost: $" . calculateTotalCost();


// Initialize the session
session_start();


// Define the cart structure
$cart = array(
    'items' => array(),
    'total_cost' => 0,
    'num_items' => 0
);


// Function to add an item to the cart
function add_item_to_cart($product_id, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart['items'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it's already there, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add a new item to the cart
    $cart['items'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity
    );

    // Update the total cost and number of items
    $cart['total_cost'] += $quantity * (get_product_price($product_id)); // Replace with your own function to get product price
    $cart['num_items']++;
}


// Function to update the cart
function update_cart() {
    global $cart;

    // Update the total cost and number of items
    $cart['total_cost'] = array_sum(array_map(function($item) { return $item['quantity'] * (get_product_price($item['product_id'])); }, $cart['items']));
    $cart['num_items'] = count($cart['items']);
}


// Function to save the cart to the session
function save_cart() {
    global $cart;

    // Save the cart to the session
    $_SESSION['cart'] = $cart;
}


// cart.php

// Initialize session
session_start();

// Function to add product to cart
function add_to_cart($product_id, $quantity = 1) {
    // Check if product exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return true; // Product already exists, increment quantity and return
        }
    }

    // Add new product to cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
    return true;
}

// Function to remove product from cart
function remove_from_cart($product_id) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            unset($item); // Remove the item
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the cart array
            return true; // Product removed from cart
        }
    }
    return false;
}

// Function to update product quantity in cart
function update_quantity($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated
        }
    }
    return false;
}

// Function to get cart contents
function get_cart_contents() {
    return $_SESSION['cart'];
}


// index.php

// Include cart functions
require 'cart.php';

// Initialize session
session_start();

// Check if user is logged in (optional)
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to access the cart.";
    exit;
}

// Display product list
$products = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 9.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 19.99),
    array('id' => 3, 'name' => 'Product C', 'price' => 29.99)
);

echo "<h1>Products</h1>";
foreach ($products as $product) {
    echo "<p><a href='add_to_cart.php?product_id=" . $product['id'] . "'>" . $product['name'] . "</a> - $" . number_format($product['price'], 2) . "</p>";
}

// Display cart contents
echo "<h1>Cart Contents</h1>";
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        echo "<p>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</p>";
    }
} else {
    echo "<p>Cart is empty.</p>";
}


// add_to_cart.php

// Include cart functions
require 'cart.php';

// Get product ID from URL parameter
$product_id = $_GET['product_id'];

// Add product to cart with default quantity of 1
add_to_cart($product_id);

// Redirect back to index.php
header('Location: index.php');
exit;


<?php
session_start();
?>


// Adding an item to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product ID already exists in cart to update quantity instead of adding a duplicate entry.
    if (!array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['name'] = 'Product ' . $product_id; // You should fetch the actual name from your database
        $_SESSION['cart'][$product_id]['price'] = 9.99; // Price or any other attribute as per your needs
        $_SESSION['cart'][$product_id]['quantity'] = (int)$quantity;
    } else {
        // Update existing item in cart
        $_SESSION['cart'][$product_id]['quantity'] += (int)$quantity;
    }
}


// Example for displaying cart contents
foreach ($_SESSION['cart'] as $product_id => $details) {
    echo 'Product ID: ' . $product_id . ', Name: ' . $_SESSION['cart'][$product_id]['name'] . ', Price: ' . $_SESSION['cart'][$product_id]['price'] . ', Quantity: ' . $_SESSION['cart'][$product_id]['quantity'];
}


// Deleting an item from the cart
if (isset($_POST['delete_item'])) {
    $product_id = $_POST['product_id'];

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


<?php
session_start();

if (isset($_POST['add_to_cart'])) {
    // Add item to cart logic here...
}

// Display items in cart logic here...

// Remove item from cart logic here...
?>

<!-- HTML Form Example -->
<form action="" method="post">
    <input type="hidden" name="product_id" value="1"> <!-- Product ID -->
    <input type="text" name="quantity" placeholder="Quantity">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<!-- Display cart contents here... -->

<form action="" method="post">
    <?php foreach ($_SESSION['cart'] as $product_id => $details): ?>
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <button type="submit" name="delete_item">Delete</button>
    <?php endforeach; ?>
</form>

<!-- Rest of your HTML and PHP to manage cart here... -->


<?php
// Session start
session_start();

// If the cart is empty, set it to an array
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($itemID, $itemName, $itemPrice) {
  // Check if item already exists in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $itemID) {
      // Increment quantity if it's the same item
      $item['quantity'] += 1;
      return; // Exit function
    }
  }

  // Add new item to cart array
  $_SESSION['cart'][] = array(
    'id' => $itemID,
    'name' => $itemName,
    'price' => $itemPrice,
    'quantity' => 1
  );
}

// Function to update quantity of item in cart
function updateQuantity($itemID, $newQuantity) {
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $itemID) {
      $item['quantity'] = $newQuantity;
      return; // Exit function
    }
  }
}

// Function to remove item from cart
function removeFromCart($itemID) {
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $itemID) {
      unset($_SESSION['cart'][$key]);
      return; // Exit function
    }
  }
}

// Example usage:
$item1 = array('id' => 1, 'name' => 'Item 1', 'price' => 9.99);
$item2 = array('id' => 2, 'name' => 'Item 2', 'price' => 19.99);

addToCart($item1['id'], $item1['name'], $item1['price']);
addToCart($item2['id'], $item2['name'], $item2['price']);

// Print out cart contents
echo '<h2>Cart Contents:</h2>';
foreach ($_SESSION['cart'] as $item) {
  echo 'ID: ' . $item['id'] . ', Name: ' . $item['name'] . ', Price: ' . $item['price'] . ', Quantity: ' . $item['quantity'] . '<br />';
}

// Update quantity of item
updateQuantity(1, 3);

// Remove item from cart
removeFromCart(2);
?>


<?php
session_start();

// Set session variables for the cart
$_SESSION['cart'] = array(); // Initialize cart as an empty array
?>


<?php
function add_to_cart($product_id, $quantity) {
  global $_SESSION;

  // Check if product_id is already in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $product_id) {
      // If it is, increment the quantity
      $item['quantity'] += $quantity;
      return; // Exit early
    }
  }

  // If product_id not found, add new item to cart
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'quantity' => $quantity
  );
}
?>


<?php
add_to_cart(1, 2); // Add product with ID 1 in quantity of 2
add_to_cart(1, 3); // Add another 3 products with ID 1 (total: 5)
?>


<?php
foreach ($_SESSION['cart'] as $item) {
  echo "Product ID: {$item['product_id']} | Quantity: {$item['quantity']}
";
}
?>


<?php
function remove_from_cart($product_id) {
  global $_SESSION;

  // Remove product_id from cart array
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['product_id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      break; // Exit early
    }
  }
}
?>


<?php
remove_from_cart(1); // Remove product with ID 1 from cart
?>


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_item_to_cart($product_id, $quantity) {
    if ($quantity < 1) {
        throw new Exception('Invalid quantity');
    }

    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already exists, update its quantity
        }
    }

    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => $quantity,
    );
}


function remove_item_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }

    throw new Exception('Product not found in cart');
}


function update_item_quantity($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    throw new Exception('Product not found in cart');
}


// Start session
session_start();

// Add items to cart
add_item_to_cart(1, 2); // Product ID = 1, Quantity = 2
add_item_to_cart(3, 4);

// Print out the current cart
print_r($_SESSION['cart']);

// Update quantity of an item
update_item_quantity(1, 5);

// Remove an item from the cart
remove_item_from_cart(3);


// cart.php

class Cart {
    private $cart;

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = &$_SESSION['cart'];
    }

    public function add($product_id, $quantity) {
        if (array_key_exists($product_id, $this->cart)) {
            $this->cart[$product_id] += $quantity;
        } else {
            $this->cart[$product_id] = $quantity;
        }
    }

    public function remove($product_id) {
        unset($this->cart[$product_id]);
    }

    public function getContents() {
        return $this->cart;
    }
}


// index.php

require_once 'cart.php';

session_start();

$cart = new Cart();


// index.php (continued)

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $cart->add($product_id, $quantity);
}


// index.php (continued)

if (isset($_POST['view_cart'])) {
    echo '<pre>';
    print_r($cart->getContents());
    echo '</pre>';
}


// index.php (continued)

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    $cart->remove($product_id);
}


// cart.php

class Cart {
    private $cart;

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = &$_SESSION['cart'];
    }

    public function add($product_id, $quantity) {
        if (array_key_exists($product_id, $this->cart)) {
            $this->cart[$product_id] += $quantity;
        } else {
            $this->cart[$product_id] = $quantity;
        }
    }

    public function remove($product_id) {
        unset($this->cart[$product_id]);
    }

    public function getContents() {
        return $this->cart;
    }
}

// index.php

require_once 'cart.php';

session_start();

$cart = new Cart();

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $cart->add($product_id, $quantity);
}

if (isset($_POST['view_cart'])) {
    echo '<pre>';
    print_r($cart->getContents());
    echo '</pre>';
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    $cart->remove($product_id);
}


<?php
  // Start the session
  session_start();

  // Check if the cart is already set in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add an item to the cart
  function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
      $_SESSION['cart'][$item_id] += $quantity;
    } else {
      $_SESSION['cart'][$item_id] = $quantity;
    }
  }

  // Remove an item from the cart
  function remove_item_from_cart($item_id) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
      unset($_SESSION['cart'][$item_id]);
    }
  }

  // Update the quantity of an item in the cart
  function update_quantity_in_cart($item_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($item_id, $_SESSION['cart'])) {
      $_SESSION['cart'][$item_id] = $new_quantity;
    }
  }

  // Display the contents of the cart
  function display_cart() {
    global $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item_id => $quantity) {
      echo "Item ID: $item_id, Quantity: $quantity<br>";
    }
  }

  // Example usage:
  add_item_to_cart(1, 2); // Add item with ID 1 to cart in quantity of 2
  display_cart();
?>


<?php
require_once 'cart.php';

add_item_to_cart(1, 2);
remove_item_from_cart(1);
update_quantity_in_cart(1, 3);

display_cart();
?>


<?php
  // Start the session
  session_start();

  $_SESSION['cart'] = array();
?>


<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'subTotal' => 0,
    'tax' => 0,
    'total' => 0
);


function addItemToCart($productId, $quantity) {
    global $cart;

    // Check if product already exists in cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            return; // Product already exists, update quantity
        }
    }

    // Add new item to cart
    $cart['items'][] = array(
        'id' => $productId,
        'quantity' => $quantity,
        'price' => getPrice($productId) // Get price from database or external API
    );

    // Update subtotal, tax, and total
    updateCartTotals();
}


function removeItemFromCart($productId) {
    global $cart;

    // Find item in cart and remove it
    foreach ($cart['items'] as $key => &$item) {
        if ($item['id'] == $productId) {
            unset($cart['items'][$key]);
            return; // Item removed successfully
        }
    }

    // Update subtotal, tax, and total
    updateCartTotals();
}


function updateCartTotals() {
    global $cart;

    // Calculate subtotal
    $cart['subTotal'] = array_sum(array_column($cart['items'], 'price')) * array_sum(array_column($cart['items'], 'quantity'));

    // Calculate tax (assuming 8% tax rate)
    $cart['tax'] = $cart['subTotal'] * 0.08;

    // Calculate total
    $cart['total'] = $cart['subTotal'] + $cart['tax'];
}


// Add item to cart
addItemToCart(1, 2);

// Remove item from cart
removeItemFromCart(1);

// Display cart totals
echo "Subtotal: $" . $cart['subTotal'];
echo "Tax: $" . $cart['tax'];
echo "Total: $" . $cart['total'];


<?php
session_start();
?>


$cart = array(
    'products' => array(),
);


function add_to_cart($product_id, $quantity) {
    global $cart;
    
    // Check if product already exists in cart
    foreach ($cart['products'] as &$product) {
        if ($product['id'] == $product_id) {
            $product['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new product to cart
    $cart['products'][] = array(
        'id' => $product_id,
        'name' => '', // Get product name from database
        'price' => 0, // Get product price from database
        'quantity' => $quantity,
    );
}


function display_cart() {
    global $cart;
    
    echo "<h2>Cart Contents:</h2>";
    foreach ($cart['products'] as $product) {
        echo "Product ID: $product[id]<br>";
        echo "Name: $product[name]<br>";
        echo "Price: $" . number_format($product['price'], 2) . "<br>";
        echo "Quantity: $product[quantity]<br><hr>";
    }
}


<?php

// config.php
session_start();

// cart.php
$cart = array(
    'products' => array(),
);

function add_to_cart($product_id, $quantity) {
    global $cart;
    
    foreach ($cart['products'] as &$product) {
        if ($product['id'] == $product_id) {
            $product['quantity'] += $quantity;
            return;
        }
    }
    
    $cart['products'][] = array(
        'id' => $product_id,
        'name' => '', // Get product name from database
        'price' => 0, // Get product price from database
        'quantity' => $quantity,
    );
}

function display_cart() {
    global $cart;
    
    echo "<h2>Cart Contents:</h2>";
    foreach ($cart['products'] as $product) {
        echo "Product ID: $product[id]<br>";
        echo "Name: $product[name]<br>";
        echo "Price: $" . number_format($product['price'], 2) . "<br>";
        echo "Quantity: $product[quantity]<br><hr>";
    }
}

// index.php
require_once 'config.php';
require_once 'cart.php';

if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

display_cart();

?>


<?php

// Start the session
session_start();

// Function to add a product to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the 'cart' key exists in the session; create it if not.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add product to cart
    $exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;  // Increment quantity if it exists.
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $_SESSION['cart'][] = array('id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => $quantity);
    }

    // Save session data
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

// Function to remove a product from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart_count'] = count($_SESSION['cart']);
                break;
            }
        }
    }

    // Update session
}

// Function to view cart contents
function view_cart() {
    if (isset($_SESSION['cart'])) {
        echo "Your Cart Contents:
";
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
            echo $item['name'] . ' x' . $item['quantity'] . ' = $' . ($item['price'] * $item['quantity']) . "
";
        }
        echo "Total: $" . number_format($total, 2) . "

";
    } else {
        echo "Your cart is empty.

";
    }

    // Save session data
}

// Example usage:
add_to_cart(1, 'Product A', 9.99);
add_to_cart(2, 'Product B', 4.99, 3); // Quantity of Product B set to 3

view_cart();

?>


<?php

// Start the session
session_start();

// Set the cart array in the session, if it doesn't exist already
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  global $_SESSION;
  // Check if the product is already in the cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // If it is, increment the quantity
      $item['quantity'] += $quantity;
      return true;
    }
  }
  // If not, add a new item to the cart
  $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
  return true;
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  global $_SESSION;
  // Check if the product is in the cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // If it is, remove it
      unset($item);
      return true;
    }
  }
  // If not, do nothing
  return false;
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  global $_SESSION;
  // Check if the product is in the cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // If it is, update the quantity
      $item['quantity'] = $new_quantity;
      return true;
    }
  }
  // If not, do nothing
  return false;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
  addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
  removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
  updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

// Display the cart contents
echo '<h2>Cart Contents:</h2>';
foreach ($_SESSION['cart'] as $item) {
  echo '<p>Product ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '</p>';
}
?>


<?php

// Include the cart functions
include 'cart.php';

// Example usage:
if (isset($_POST['add_to_cart'])) {
  addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
  removeFromCart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
  updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

// Display the form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="product_id">Product ID:</label>
  <input type="text" id="product_id" name="product_id"><br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity"><br><br>
  <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="product_id">Product ID:</label>
  <input type="text" id="product_id" name="product_id"><br><br>
  <input type="submit" name="remove_from_cart" value="Remove from Cart">
</form>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="product_id">Product ID:</label>
  <input type="text" id="product_id" name="product_id"><br><br>
  <label for="new_quantity">New Quantity:</label>
  <input type="number" id="new_quantity" name="new_quantity"><br><br>
  <input type="submit" name="update_quantity" value="Update Quantity">
</form>

<?php
?>


<?php
session_start();
?>


<?php

// Start session if not already started
session_start();

// Function to add item to cart
function add_to_cart($id, $name, $price) {
    // If the product is already in the cart, update quantity
    if (array_key_exists($id, $_SESSION['cart'])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = ['quantity' => 1, 'name' => $name, 'price' => $price];
    }
}

// Function to remove item from cart
function remove_from_cart($id) {
    if (array_key_exists($id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$id]);
    }
}

// Function to update quantity of an item in the cart
function update_quantity($id, $newQuantity) {
    if (array_key_exists($id, $_SESSION['cart'])) {
        $_SESSION['cart'][$id]['quantity'] = $newQuantity;
    }
}

// Display cart contents
function display_cart() {
    echo "<h2>Cart Contents:</h2>";
    echo '<table border="1">';
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr><td>' . $item['name'] . '</td>';
        echo '<td>' . number_format($item['price'], 2) . ' each</td>';
        echo '<td>Quantity: ' . $item['quantity'] . '</td>';
        echo '<td>Total for this item: ' . number_format($item['price'] * $item['quantity'], 2) . '</td>';
    }
    echo '</table>';

    if (!empty($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        echo "<p>Total: " . number_format($total, 2) . "</p>";
    } else {
        echo "<p>Cart is empty.</p>";
    }

}

// Sample form to add items to cart
echo '<form action="" method="post">';
echo '<input type="hidden" name="item_id" value="1">';
echo '<button type="submit">Add 1 item of Product 1 to Cart</button>';
echo '</form>';

// Update session data based on form submissions
if (isset($_POST['item_id'])) {
    $id = $_POST['item_id'];
    // Assume the price and name are available somewhere for the product with this id.
    $price = 9.99; // Placeholder, replace with actual logic to get price and name from database or wherever they're stored.
    $name = 'Product ' . $id;

    add_to_cart($id, $name, $price);
}

// Display current cart contents
display_cart();

?>


session_start();


class Cart {
    private $items;

    public function __construct() {
        $this->items = array();
    }

    public function addItem($id, $name, $price, $quantity) {
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] += $quantity;
        } else {
            $this->items[$id] = array(
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity
            );
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function removeItem($id) {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
        }
    }

    public function updateQuantity($id, $quantity) {
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] = $quantity;
        }
    }
}


function add_item_to_cart($id, $name, $price, $quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = new Cart();
        $_SESSION['cart'] = $cart;
    }

    $cart->addItem($id, $name, $price, $quantity);
}

function get_cart_items() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart']->getItems();
    } else {
        return array();
    }
}

function remove_item_from_cart($id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $cart->removeItem($id);
    }
}

function update_quantity_in_cart($id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $cart->updateQuantity($id, $quantity);
    }
}


require_once 'cart_functions.php';

// Add some items to the cart
add_item_to_cart(1, 'Item 1', 10.99, 2);
add_item_to_cart(2, 'Item 2', 5.99, 3);

// Get all items in the cart
$items = get_cart_items();
print_r($items);

// Remove an item from the cart
remove_item_from_cart(1);

// Update the quantity of an item in the cart
update_quantity_in_cart(2, 4);


session_start();


$cart = array();


function add_item_to_cart($item_id, $quantity) {
    global $cart;
    
    // Check if the item is already in the cart
    foreach ($cart as $key => $value) {
        if ($value['id'] == $item_id) {
            // If it's already in the cart, update the quantity
            $cart[$key]['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to the cart
    $new_item = array(
        'id' => $item_id,
        'name' => get_item_name($item_id), // Assuming a function to retrieve item name
        'price' => get_item_price($item_id), // Assuming a function to retrieve item price
        'quantity' => $quantity
    );
    
    $cart[] = $new_item;
}


function remove_item_from_cart($item_id) {
    global $cart;
    
    foreach ($cart as $key => $value) {
        if ($value['id'] == $item_id) {
            unset($cart[$key]);
            return true;
        }
    }
    
    return false; // Item not found in cart
}


function update_cart_quantities() {
    global $cart;
    
    foreach ($cart as &$item) {
        if (isset($_POST['quantity']) && $_POST['quantity'][$item['id']] != '') {
            $item['quantity'] = $_POST['quantity'][$item['id']];
        }
    }
}


function display_cart_contents() {
    global $cart;
    
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Item ID</th><th>Name</th><th>Price</th><th>Quantity</th></tr>';
    
    foreach ($cart as $item) {
        echo '<tr>';
        echo '<td>' . $item['id'] . '</td>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>$' . number_format($item['price'], 2) . '</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
}


// Start session
session_start();

// Initialize cart array
$cart = array();

// Add items to cart
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);

// Display cart contents
display_cart_contents();


session_start();


// Example initialization for $_SESSION['cart']
$_SESSION['cart'] = array();


function addToCart($id, $name, $quantity) {
    global $_SESSION;
    
    // Check if item already exists in cart
    if (isset($_SESSION['cart'][$id])) {
        // If it does, increment the quantity
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        // Otherwise, add a new entry with initial quantity
        $_SESSION['cart'][$id] = array('name' => $name, 'quantity' => $quantity);
    }
}


function removeFromCart($id) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}


function updateQuantity($id, $newQuantity) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] = $newQuantity;
    }
}


// Starting with an empty cart
session_start();

$_SESSION['cart'] = array();

// Adding items
addToCart(1, 'Product 1', 2);
addToCart(2, 'Product 2', 3);

// Viewing current cart (example)
print_r($_SESSION['cart']);

// Removing item
removeFromCart(2);

// Updating quantity of existing item
updateQuantity(1, 5);


<?php
  // Start the session
  session_start();

  // If the cart is empty, set it to an empty array
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Function to add item to cart
  function add_item($item_id, $item_name, $price) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $item_id) {
        // If the item is already in the cart, increment its quantity
        $item['quantity']++;
        return;
      }
    }
    // If the item isn't in the cart, add it
    $_SESSION['cart'][] = array('id' => $item_id, 'name' => $item_name, 'price' => $price, 'quantity' => 1);
  }

  // Function to remove item from cart
  function remove_item($item_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
      if ($item['id'] == $item_id) {
        unset($_SESSION['cart'][$key]);
        return;
      }
    }
  }

  // Function to update item quantity in cart
  function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $item_id) {
        $item['quantity'] = $new_quantity;
        return;
      }
    }
  }

  // Function to get total cost of cart
  function get_total_cost() {
    global $_SESSION;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
    return $total;
  }

  // Example usage:
  add_item(1, 'Apple', 2.99);
  add_item(2, 'Banana', 0.99);
  add_item(3, 'Orange', 1.49);

  echo "Cart contents:
";
  print_r($_SESSION['cart']);

  echo "
Total cost: $" . get_total_cost();

?>


<?php

// Initialize session
session_start();

// Set cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function add_product_to_cart($product_id) {
    // Get the product details from the database (not shown in this example)
    $product = get_product_details($product_id);
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity']++;
            return; // Product already exists, increment quantity and exit
        }
    }
    
    // Add new product to cart with initial quantity of 1
    $_SESSION['cart'][] = ['id' => $product_id, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => 1];
}

function remove_product_from_cart($product_id) {
    // Find the product in the cart and delete it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // Product removed, exit
        }
    }
}

function update_product_quantity($product_id, $quantity) {
    // Find the product in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = max(1, $quantity); // Ensure minimum quantity of 1
            return; // Quantity updated, exit
        }
    }
}

?>


<?php

// Include the cart.php file
include 'cart.php';

// Assume you have a form with product ID as input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    
    // Add product to cart on submit
    add_product_to_cart($product_id);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<form action="" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <button type="submit">Add to Cart</button>
</form>

<ul>
    <?php foreach ($_SESSION['cart'] as $item): ?>
        <li><?php echo $item['name']; ?> x<?php echo $item['quantity']; ?> = $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></li>
    <?php endforeach; ?>
</ul>

<form action="" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <button type="submit">Remove from Cart</button>
</form>

<?php if (isset($_SESSION['cart'])): ?>
    <form action="" method="post">
        <label>Update Quantity:</label>
        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>">
        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
        <button type="submit">Update</button>
    </form>
<?php endif; ?>

</body>
</html>


<?php
// Initialize session
session_start();

// Set default values for cart and total
$_SESSION['cart'] = array();
$_SESSION['total'] = 0;
?>


<?php
// Check if user is logged in (not implemented here)
if (!isset($_SESSION['logged_in'])) {
    echo 'You must be logged in to add items to your cart.';
    exit;
}

// Get product ID from request
$product_id = $_POST['product_id'];

// Check if product exists in session
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
        // Increment quantity of existing item
        $item['quantity']++;
        break;
    }
}

// Add new product to cart if it doesn't exist
if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
        'id' => $product_id,
        'name' => 'Product Name', // Replace with actual product name
        'price' => 19.99, // Replace with actual product price
        'quantity' => 1
    );
}

// Update total in session
$_SESSION['total'] += $_SESSION['cart'][$product_id]['price'];

// Redirect to cart page
header('Location: view_cart.php');
exit;
?>


<?php
// Check if user is logged in (not implemented here)
if (!isset($_SESSION['logged_in'])) {
    echo 'You must be logged in to add items to your cart.';
    exit;
}

// Get product ID from request
$product_id = $_POST['product_id'];

// Remove product from session cart
unset($_SESSION['cart'][$product_id]);

// Update total in session
$_SESSION['total'] -= $_SESSION['cart'][$product_id]['price'];

// Redirect to cart page
header('Location: view_cart.php');
exit;
?>


<?php
// Check if user is logged in (not implemented here)
if (!isset($_SESSION['logged_in'])) {
    echo 'You must be logged in to view your cart.';
    exit;
}

// Display contents of cart and total
echo '<h2>Your Cart</h2>';
echo '<table border="1">';
echo '<tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>';

foreach ($_SESSION['cart'] as $item) {
    echo '<tr>';
    echo '<td>' . $item['name'] . '</td>';
    echo '<td>' . $item['quantity'] . '</td>';
    echo '<td>$' . number_format($item['price'], 2) . '</td>';
    echo '</tr>';
}

echo '</table>';
echo '<p>Total: $' . number_format($_SESSION['total'], 2) . '</p>';

// Add and remove buttons
?>
<form action="add_to_cart.php" method="post">
    <input type="hidden" name="product_id" value="1"> <!-- Replace with actual product ID -->
    <button type="submit">Add to Cart</button>
</form>

<form action="remove_from_cart.php" method="post">
    <input type="hidden" name="product_id" value="1"> <!-- Replace with actual product ID -->
    <button type="submit">Remove from Cart</button>
</form>


<?php
// Check if the cart session exists, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update item quantity in cart
function update_item_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Product ID: ' . $item['product_id'] . ', Quantity: ' . $item['quantity'] . '</br>';
    }
}

// Example usage
$product_id = 1;
$quantity = 3;

add_item_to_cart($product_id, $quantity);

display_cart();


<?php
session_start();
?>


// Assuming we have a simple product struct to work with
class Product {
    public $id;
    public $name;
    public $price;

    function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}

// Initialize cart in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addItemToCart($product) {
    global $cart;
    
    if (array_key_exists($product->id, $_SESSION['cart'])) {
        // Increment quantity if product exists
        $_SESSION['cart'][$product->id]['quantity']++;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product->id] = array(
            'product' => $product,
            'quantity' => 1
        );
    }
}


function removeItemFromCart($id) {
    global $cart;
    
    if (array_key_exists($id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$id]);
        
        // Re-index array for consistency
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}


// Create some sample products
$product1 = new Product(1, 'Product A', 9.99);
$product2 = new Product(2, 'Product B', 19.99);

// Add items to cart
addItemToCart($product1);
addItemToCart($product1); // Quantity increments
addItemToCart($product2);

// Display cart contents
echo "Your Cart:
";
foreach ($_SESSION['cart'] as $id => $item) {
    echo "Product: " . $item['product']->name . ", Quantity: " . $item['quantity'] . "
";
}


<?php

// Initialize the cart session
session_start();

// Check if the cart is already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>


<?php

function add_item_to_cart($item_id) {
    // Get the current user's cart session
    $cart = $_SESSION['cart'];

    // Check if the item is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, increment its quantity
            $item['quantity']++;
            return;
        }
    }

    // If not, add it to the cart with a quantity of 1
    $cart[] = array('id' => $item_id, 'quantity' => 1);
}

?>


<?php

function update_cart($item_id, $new_quantity) {
    // Get the current user's cart session
    $cart = $_SESSION['cart'];

    // Find the item in the cart and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // If the item is not found, add it to the cart with the new quantity
    $cart[] = array('id' => $item_id, 'quantity' => $new_quantity);
}

?>


<?php

function display_cart() {
    // Get the current user's cart session
    $cart = $_SESSION['cart'];

    // Output the cart contents
    echo '<h2>Cart Contents:</h2>';
    echo '<ul>';
    foreach ($cart as $item) {
        echo '<li>' . $item['id'] . ' x ' . $item['quantity'] . '</li>';
    }
    echo '</ul>';
}

?>


<?php

// Initialize the cart session
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_item_to_cart($item_id) {
    // ...
}

function update_cart($item_id, $new_quantity) {
    // ...
}

function display_cart() {
    // ...
}

?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Initialize item counter and total cost
$_SESSION['item_count'] = 0;
$_SESSION['total_cost'] = 0.00;
?>


function add_item_to_cart($item_id, $item_name, $price) {
    global $_SESSION;

    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If item exists, increment quantity and update total cost
            $item['quantity']++;
            $_SESSION['total_cost'] += $price;
            break;
        }
    }

    // Add new item to cart if it doesn't exist
    else {
        $_SESSION['cart'][] = array('id' => $item_id, 'name' => $item_name, 'price' => $price, 'quantity' => 1);
        $_SESSION['item_count']++;
        $_SESSION['total_cost'] += $price;
    }
}


function remove_item_from_cart($item_id) {
    global $_SESSION;

    // Check if item exists in cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            // Remove item from cart and update total cost
            unset($_SESSION['cart'][$key]);
            $_SESSION['total_cost'] -= $item['price'];
            $_SESSION['item_count']--;
            break;
        }
    }
}


function display_cart_contents() {
    global $_SESSION;

    // Output cart contents as HTML table
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr><td>' . $item['name'] . '</td><td>' . $item['quantity'] . '</td><td>$' . number_format($item['price'], 2) . '</td></tr>';
    }
    echo '<tr><td colspan="2">Total:</td><td>$' . number_format($_SESSION['total_cost'], 2) . '</td></tr>';
    echo '</table>';
}


item_id = 1;
item_name = 'Apple';
price = 0.99;

add_item_to_cart($item_id, $item_name, $price);


$item_id = 1;

remove_item_from_cart($item_id);


display_cart_contents();


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Initialize item counter and total cost
$_SESSION['item_count'] = 0;
$_SESSION['total_cost'] = 0.00;

function add_item_to_cart($item_id, $item_name, $price) {
    global $_SESSION;

    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If item exists, increment quantity and update total cost
            $item['quantity']++;
            $_SESSION['total_cost'] += $price;
            break;
        }
    }

    // Add new item to cart if it doesn't exist
    else {
        $_SESSION['cart'][] = array('id' => $item_id, 'name' => $item_name, 'price' => $price, 'quantity' => 1);
        $_SESSION['item_count']++;
        $_SESSION['total_cost'] += $price;
    }
}

function remove_item_from_cart($item_id) {
    global $_SESSION;

    // Check if item exists in cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            // Remove item from cart and update total cost
            unset($_SESSION['cart'][$key]);
            $_SESSION['total_cost'] -= $item['price'];
            $_SESSION['item_count']--;
            break;
        }
    }
}

function display_cart_contents() {
    global $_SESSION;

    // Output cart contents as HTML table
    echo '<h2>Cart Contents:</h2>';
    echo '<table border="1">';
    foreach ($_SESSION['cart'] as $item) {
        echo '<tr><td>' . $item['name'] . '</td><td>' . $item['quantity'] . '</td><td>$' . number_format($item['price'], 2) . '</td></tr>';
    }
    echo '<tr><td colspan="2">Total:</td><td>$' . number_format($_SESSION['total_cost'], 2) . '</td></tr>';
    echo '</table>';
}

// Example usage
$item_id = 1;
$item_name = 'Apple';
$price = 0.99;

add_item_to_cart($item_id, $item_name, $price);

display_cart_contents();
?>


<?php
session_start();
?>


// Initialize cart arrays
$_SESSION['cart'] = array(); // cart items
$_SESSION['cart_quantities'] = array(); // item quantities


// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item == $product_id) {
            // Increment quantity if item is already in cart
            $_SESSION['cart_quantities'][$key] += $quantity;
            return; // exit function early
        }
    }
    
    // Add new item to cart and update quantities array
    $_SESSION['cart'][] = $product_id;
    $_SESSION['cart_quantities'][] = $quantity;
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find index of product in cart array
    $index = array_search($product_id, $_SESSION['cart']);
    
    if ($index !== false) {
        // Remove product from both arrays
        unset($_SESSION['cart'][$index]);
        unset($_SESSION['cart_quantities'][$index]);
    }
}


// Add a product to the cart with quantity 2
add_to_cart(1, 2);


// Remove product with ID 1 from cart
remove_from_cart(1);


<?php
session_start();

// Initialize cart arrays
$_SESSION['cart'] = array(); // cart items
$_SESSION['cart_quantities'] = array(); // item quantities

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item == $product_id) {
            // Increment quantity if item is already in cart
            $_SESSION['cart_quantities'][$key] += $quantity;
            return; // exit function early
        }
    }
    
    // Add new item to cart and update quantities array
    $_SESSION['cart'][] = $product_id;
    $_SESSION['cart_quantities'][] = $quantity;
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find index of product in cart array
    $index = array_search($product_id, $_SESSION['cart']);
    
    if ($index !== false) {
        // Remove product from both arrays
        unset($_SESSION['cart'][$index]);
        unset($_SESSION['cart_quantities'][$index]);
    }
}

// Example usage:
add_to_cart(1);
remove_from_cart(1);

?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($product_id, $quantity) {
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // Increase quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function removeFromCart($product_id) {
    // Remove all instances of product from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
        }
    }

    // Re-index cart array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $new_quantity) {
    // Find and update existing item in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // Add new item to cart with updated quantity
    addToCart($product_id, $new_quantity);
}

// Function to get total cost of items in cart
function getTotalCost() {
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        // Assume prices are stored in a database or array for simplicity
        $prices = array(1 => 9.99, 2 => 14.99); // Replace with actual prices
        $total += $item['quantity'] * $prices[$item['product_id']];
    }
    return $total;
}

// Add example item to cart
addToCart(1, 2);

echo "Cart contents:
";
print_r($_SESSION['cart']);

echo "
Total cost: $" . getTotalCost();
?>


addToCart(3, 1);


removeFromCart(1);


updateQuantity(1, 3);


echo getTotalCost();


<?php
session_start();
?>


$cart = array(
    'product_id' => '',
    'quantity' => ''
);


function addToCart($product_id, $quantity) {
    global $cart;
    
    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it is, increment its quantity by the specified amount
            $item['quantity'] += $quantity;
            return; // Exit function early since we've updated existing item
        }
    }
    
    // If not, add a new item to the cart with the specified product ID and quantity
    $cart[] = array(
        'product_id' => $product_id,
        'quantity' => $quantity
    );
}


function displayCart() {
    global $cart;
    
    echo '<h2>Your Cart Contents:</h2>';
    foreach ($cart as $item) {
        echo 'Product ID: ' . $item['product_id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}


function removeFromCart($product_id) {
    global $cart;
    
    // Find the index of the product ID in our cart array
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[array_search($item, $cart)]);
            return; // Exit function early since we've found and removed item
        }
    }
}


<?php
session_start();

$cart = array(
    'product_id' => '',
    'quantity' => ''
);

// Example usage: Add 3 items to cart with product ID 1 and quantity of 2 each.
addToCart(1, 2);
addToCart(1, 2);
addToCart(2, 5); // Adding another item

// Display cart contents
displayCart();

// Remove an item from the cart
removeFromCart(1);

// Display updated cart contents
displayCart();
?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function add_to_cart($product_id) {
    // Get product details from database
    $product = get_product($product_id);
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity
            $item['quantity']++;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    );
}


function remove_from_cart($product_id) {
    // Find product in cart and delete it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function update_quantity($product_id, $quantity) {
    // Find product in cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}


function display_cart() {
    ?>
    <h2>Cart Contents:</h2>
    <ul>
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <li><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?> = <?php echo $item['price'] * $item['quantity']; ?></li>
        <?php } ?>
    </ul>
    <?php
}


<?php

// Initialize sessions
session_start();

// Database connection settings
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'cart_system';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!-- Add product to cart form -->
<form action="" method="post">
    <input type="hidden" name="product_id" value="<?php echo $_POST['product_id']; ?>">
    <button type="submit">Add to Cart</button>
</form>

<?php
// Get products from database
$query = "SELECT * FROM products";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    ?>
    <div>
        <?php echo $row['name']; ?> (<?php echo $row['price']; ?>)
        <form action="" method="post">
            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
    <?php
}

// Add product to cart function call
if (isset($_POST['product_id'])) {
    add_to_cart($_POST['product_id']);
}

// Display cart contents
display_cart();

?>


class CartSession {
  private $session;

  public function __construct() {
    // Start the session if it doesn't exist
    if (!isset($_SESSION)) {
      session_start();
    }
    $this->session = $_SESSION;
  }

  /**
   * Add an item to the cart
   *
   * @param string $product_id Product ID of the item
   * @param int $quantity Quantity of the item
   */
  public function addItem($product_id, $quantity) {
    if (!isset($this->session['cart'])) {
      $this->session['cart'] = array();
    }
    if (array_key_exists($product_id, $this->session['cart'])) {
      // If product is already in cart, increment its quantity
      $this->session['cart'][$product_id] += $quantity;
    } else {
      // Add new product to cart with initial quantity
      $this->session['cart'][$product_id] = $quantity;
    }
  }

  /**
   * Remove an item from the cart
   *
   * @param string $product_id Product ID of the item
   */
  public function removeItem($product_id) {
    if (array_key_exists($product_id, $this->session['cart'])) {
      unset($this->session['cart'][$product_id]);
    }
  }

  /**
   * Update the quantity of an item in the cart
   *
   * @param string $product_id Product ID of the item
   * @param int $quantity New quantity of the item
   */
  public function updateQuantity($product_id, $quantity) {
    if (array_key_exists($product_id, $this->session['cart'])) {
      $this->session['cart'][$product_id] = $quantity;
    }
  }

  /**
   * Get the contents of the cart
   *
   * @return array Cart contents
   */
  public function getCartContents() {
    return isset($this->session['cart']) ? $this->session['cart'] : array();
  }

  /**
   * Clear the cart
   */
  public function clearCart() {
    unset($this->session['cart']);
  }
}


$cart = new CartSession();

// Add some items to the cart
$cart->addItem('prod1', 2);
$cart->addItem('prod2', 3);

// Get the contents of the cart
echo '<pre>';
print_r($cart->getCartContents());
echo '</pre>';

// Update the quantity of an item in the cart
$cart->updateQuantity('prod1', 5);

// Remove an item from the cart
$cart->removeItem('prod2');

// Get the contents of the cart again
echo '<pre>';
print_r($cart->getCartContents());
echo '</pre>';

// Clear the cart
$cart->clearCart();


session_start();


$cart = array();
$total = 0;


function add_to_cart($product_id, $name, $price, $quantity) {
  global $cart;
  global $total;

  // Check if the item is already in the cart
  foreach ($cart as &$item) {
    if ($item['id'] == $product_id) {
      // If it is, update the quantity and calculate the new total
      $item['quantity'] += $quantity;
      $total += ($price * $quantity);
      return;
    }
  }

  // If not, add the item to the cart
  $cart[] = array('id' => $product_id, 'name' => $name, 'price' => $price, 'quantity' => $quantity);
  $total += ($price * $quantity);
}


$_SESSION['cart'] = $cart;
$_SESSION['total'] = $total;


if (isset($_SESSION['cart'])) {
  echo "Cart Contents:<br>";
  foreach ($_SESSION['cart'] as $item) {
    echo "$item[name] x $item[quantity] = \$$item[price]*$item[quantity]<br>";
  }
  echo "Total: $" . $_SESSION['total'];
} else {
  echo "Your cart is empty.";
}


<?php

session_start();

$cart = array();
$total = 0;

function add_to_cart($product_id, $name, $price, $quantity) {
  global $cart;
  global $total;

  foreach ($cart as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] += $quantity;
      $total += ($price * $quantity);
      return;
    }
  }

  $cart[] = array('id' => $product_id, 'name' => $name, 'price' => $price, 'quantity' => $quantity);
  $total += ($price * $quantity);
}

if (isset($_POST['add_to_cart'])) {
  add_to_cart($_POST['product_id'], $_POST['name'], $_POST['price'], $_POST['quantity']);
  $_SESSION['cart'] = $cart;
  $_SESSION['total'] = $total;
}

if (isset($_SESSION['cart'])) {
  echo "Cart Contents:<br>";
  foreach ($_SESSION['cart'] as $item) {
    echo "$item[name] x $item[quantity] = \$$item[price]*$item[quantity]<br>";
  }
  echo "Total: $" . $_SESSION['total'];
} else {
  echo "Your cart is empty.";
}

?>


class CartSession {
    private $cart = array();
    private $sessionId;

    public function __construct() {
        // Create a new session or retrieve the existing one
        if (!session_id()) {
            session_start();
        }
        $this->sessionId = session_id();

        // Initialize the cart array if it doesn't exist
        if (isset($_SESSION['cart'])) {
            $this->cart = $_SESSION['cart'];
        } else {
            $_SESSION['cart'] = array();
            $this->cart = $_SESSION['cart'];
        }
    }

    public function addProduct($productId, $quantity) {
        // Check if the product is already in the cart
        foreach ($this->cart as &$item) {
            if ($item['id'] == $productId) {
                // If it is, increment the quantity
                $item['quantity'] += $quantity;
                return;
            }
        }

        // If not, add a new item to the cart
        $this->cart[] = array(
            'id' => $productId,
            'quantity' => $quantity
        );
    }

    public function removeProduct($productId) {
        // Find the product in the cart and remove it if it exists
        foreach ($this->cart as $key => &$item) {
            if ($item['id'] == $productId) {
                unset($this->cart[$key]);
                return;
            }
        }
    }

    public function updateProductQuantity($productId, $newQuantity) {
        // Find the product in the cart and update its quantity
        foreach ($this->cart as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $newQuantity;
                return;
            }
        }
    }

    public function getCart() {
        return $this->cart;
    }

    public function getTotalCost() {
        // Calculate the total cost of the products in the cart
        $totalCost = 0;
        foreach ($this->cart as &$item) {
            $productPrice = getPrice($item['id']); // Get the price of the product from your database or API
            $totalCost += $productPrice * $item['quantity'];
        }
        return $totalCost;
    }

    public function clearCart() {
        // Clear all items from the cart
        $_SESSION['cart'] = array();
        $this->cart = array();
    }
}


// Create a new CartSession instance
$cart = new CartSession();

// Add some products to the cart
$cart->addProduct(1, 2);
$cart->addProduct(3, 4);

// Update the quantity of an existing product
$cart->updateProductQuantity(1, 5);

// Remove a product from the cart
$cart->removeProduct(3);

// Get the total cost of the products in the cart
$totalCost = $cart->getTotalCost();

// Clear the cart
$cart->clearCart();


session_start();


<?php
session_start();

// If the cart is empty, initialize it with an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItem($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment the quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function removeItem($product_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
    return false;
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return true;
        }
    }
    return false;
}

// Function to display the cart contents
function displayCart() {
    global $_SESSION;
    
    echo "<h2>Shopping Cart</h2>";
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            echo "<p>Product ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "</p>";
        }
    } else {
        echo "<p>Your cart is empty!</p>";
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    addItem($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
    removeItem($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
    updateQuantity($_POST['product_id'], $_POST['new_quantity']);
}

displayCart();
?>


<?php
require_once 'cart.php';

// Display form to add products to cart
echo "<form action='cart.php' method='post'>";
echo "<input type='hidden' name='add_to_cart' value='true'>";
echo "<label>Product ID:</label><br>";
echo "<input type='text' name='product_id'><br>";
echo "<label>Quantity:</label><br>";
echo "<input type='number' name='quantity'><br>";
echo "<input type='submit' value='Add to Cart'>";
echo "</form>";

// Display form to remove products from cart
echo "<form action='cart.php' method='post'>";
echo "<input type='hidden' name='remove_from_cart' value='true'>";
echo "<label>Product ID:</label><br>";
echo "<input type='text' name='product_id'><br>";
echo "<input type='submit' value='Remove from Cart'>";
echo "</form>";

// Display form to update quantity of products in cart
echo "<form action='cart.php' method='post'>";
echo "<input type='hidden' name='update_quantity' value='true'>";
echo "<label>Product ID:</label><br>";
echo "<input type='text' name='product_id'><br>";
echo "<label>New Quantity:</label><br>";
echo "<input type='number' name='new_quantity'><br>";
echo "<input type='submit' value='Update Quantity'>";
echo "</form>";

// Display cart contents
displayCart();
?>


<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'total' => 0
);


function add_to_cart($product_id) {
    global $cart;
    
    // Check if product already exists in cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity']++;
            return;
        }
    }
    
    // Add new product to cart
    $cart['items'][] = array(
        'id' => $product_id,
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'quantity' => 1
    );
    
    // Update total cost
    $cart['total'] += $_POST['price'];
}


function display_cart() {
    global $cart;
    
    echo '<h2>Cart Contents:</h2>';
    echo '<ul>';
    foreach ($cart['items'] as $item) {
        echo '<li>';
        echo $item['name'] . ' x ' . $item['quantity'];
        echo '</li>';
    }
    echo '</ul>';
    echo '<p>Total: $' . number_format($cart['total']) . '</p>';
}


function save_cart() {
    global $cart;
    
    $_SESSION['cart'] = $cart;
}


<?php
session_start();

// Initialize cart array
$cart = array(
    'items' => array(),
    'total' => 0
);

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    add_to_cart($_POST['product_id']);
}

// Display cart contents
display_cart();

// Save cart to session
save_cart();
?>


session_start();


<?php

// Start session
session_start();

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    // Get current cart array from session (or initialize if it doesn't exist)
    $cart = &$_SESSION['cart'];
    
    // If product is already in the cart, increment its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // No need to add it again
        }
    }
    
    // Add new item to cart array
    $cart[] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to view cart contents
function viewCart() {
    echo '<h2>Shopping Cart:</h2>';
    
    // Get current cart array from session (or initialize if it doesn't exist)
    $cart = &$_SESSION['cart'];
    
    // Display each item in the cart, with quantity and subtotal
    foreach ($cart as &$item) {
        echo "Product ID: $item[id] - Quantity: $item[quantity] - Subtotal: $" . ($item['quantity'] * 19.99);
    }
}

// Display form to add items to cart
echo '<form action="cart.php" method="post">';
echo 'Select product ID and quantity:<br>';
echo '<input type="text" name="product_id" size="5"><br>';
echo '<input type="text" name="quantity" size="2"><br>';
echo '<input type="submit" value="Add to Cart">';
echo '</form>';

// Add item to cart if form submitted
if (isset($_POST['product_id'])) {
    addItemToCart($_POST['product_id'], $_POST['quantity']);
}

// View cart contents
viewCart();

?>


<?php

// Example product list for demo purposes
$products = array(
    1 => 'Product A',
    2 => 'Product B',
    3 => 'Product C'
);

echo '<h2>Products:</h2>';
foreach ($products as $product_id => $product_name) {
    echo "$product_name (ID: $product_id)<br>";
}

?>


// Secure session settings
session_start();
session_regenerate_id(true);
session_set_cookie_params(0, '/', '', $secure = true, $httponly = true);

// ...

if (isset($_POST['product_id'])) {
    // Validate user input before processing it
    if (!is_numeric($_POST['product_id']) || !ctype_digit($_POST['quantity'])) {
        echo "Invalid product ID or quantity.";
    } else {
        addItemToCart($_POST['product_id'], $_POST['quantity']);
    }
}

// ...


// cart.php

class Cart {
  private $session_name = 'cart';

  public function __construct() {
    if (!isset($_SESSION[$this->session_name])) {
      $_SESSION[$this->session_name] = array();
    }
  }

  public function add_item($product_id, $quantity) {
    if (array_key_exists($product_id, $_SESSION[$this->session_name])) {
      $_SESSION[$this->session_name][$product_id]['quantity'] += $quantity;
    } else {
      $_SESSION[$this->session_name][$product_id] = array('quantity' => $quantity);
    }
  }

  public function remove_item($product_id) {
    if (array_key_exists($product_id, $_SESSION[$this->session_name])) {
      unset($_SESSION[$this->session_name][$product_id]);
    }
  }

  public function update_quantity($product_id, $new_quantity) {
    if (array_key_exists($product_id, $_SESSION[$this->session_name])) {
      $_SESSION[$this->session_name][$product_id]['quantity'] = $new_quantity;
    }
  }

  public function get_cart_contents() {
    return $_SESSION[$this->session_name];
  }
}


// index.php

require_once 'cart.php';

$cart = new Cart();

// Add items to cart
$cart->add_item(1, 2); // product_id 1, quantity 2
$cart->add_item(3, 4); // product_id 3, quantity 4

// Get cart contents
$contents = $cart->get_cart_contents();
print_r($contents);

// Remove item from cart
$cart->remove_item(3);

// Update quantity of an item in cart
$cart->update_quantity(1, 5);


class Cart {
    private $session;

    public function __construct() {
        $this->session = $_SESSION;
    }

    public function addItem($item_id, $quantity) {
        if (!isset($this->session['cart'])) {
            $this->session['cart'] = array();
        }
        $existingItem = false;
        foreach ($this->session['cart'] as &$item) {
            if ($item['id'] == $item_id) {
                $item['quantity'] += $quantity;
                $existingItem = true;
                break;
            }
        }
        if (!$existingItem) {
            $this->session['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
        }
    }

    public function removeItem($item_id) {
        if (isset($this->session['cart'])) {
            foreach ($this->session['cart'] as $key => $item) {
                if ($item['id'] == $item_id) {
                    unset($this->session['cart'][$key]);
                    break;
                }
            }
        }
    }

    public function updateItemQuantity($item_id, $quantity) {
        if (isset($this->session['cart'])) {
            foreach ($this->session['cart'] as &$item) {
                if ($item['id'] == $item_id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
        }
    }

    public function getItems() {
        return isset($this->session['cart']) ? $this->session['cart'] : array();
    }

    public function getItemQuantity($item_id) {
        if (isset($this->session['cart'])) {
            foreach ($this->session['cart'] as $item) {
                if ($item['id'] == $item_id) {
                    return $item['quantity'];
                }
            }
        }
        return 0;
    }

    public function clearCart() {
        unset($this->session['cart']);
    }
}


<?php
if (!isset($_SESSION)) {
    session_start();
}

$cart = new Cart();

// Now you can use $cart object in your application
?>


<?php
// Add item to cart with quantity 2
$cart->addItem(1, 2);

// Update quantity of item with id 1 to 3
$cart->updateItemQuantity(1, 3);

// Remove item with id 1 from cart
$cart->removeItem(1);
?>


<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If product is already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => '', // assume we're using a database or external data source for names
            'price' => 0, // assume we're using a database or external data source for prices
            'quantity' => $quantity
        );
    }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example usage:
add_to_cart(1, 2); // add 2 of product with id 1 to cart
add_to_cart(3, 1); // add 1 of product with id 3 to cart

// Print out contents of cart
print_r($_SESSION['cart']);
?>


// When the user adds an item to their cart...
add_to_cart(1, 2);

// Save the updated cart to the database or external storage system...
$db->insert('cart', array(
    'user_id' => $_SESSION['user_id'],
    'product_id' => 1,
    'quantity' => 2
));

// When the user returns to the site...
$cart = $db->get('cart', array(
    'where' => array(
        'user_id' => $_SESSION['user_id']
    )
));
$_SESSION['cart'] = array();
foreach ($cart as $item) {
    add_to_cart($item['product_id'], $item['quantity']);
}


session_start();


// Assuming you have products with unique IDs
if (isset($_SESSION['cart'])) {
    // If the cart session already exists, we'll work on it.
} else {
    $_SESSION['cart'] = array();
}


function addItemToCart($productId) {
    global $mysqli; // Assuming you're using a database for products
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        $product = getProductDataFromDB($productId);
        $_SESSION['cart'][$productId] = array('name' => $product['name'], 'price' => $product['price']);
        $_SESSION['cart'][$productId]['quantity'] = 1;
        
        // Update total cost and item count in session
        if (!isset($_SESSION['total_cost'])) {
            $_SESSION['total_cost'] = 0;
        }
        if (!isset($_SESSION['item_count'])) {
            $_SESSION['item_count'] = 0;
        }
        $_SESSION['total_cost'] += $product['price'];
        $_SESSION['item_count']++;
    }
}


function viewCart() {
    global $mysqli;
    
    // Print each item in the cart with quantity and total cost for that item
    foreach ($_SESSION['cart'] as $productId => $itemData) {
        $product = getProductDataFromDB($productId);
        echo "Product: " . $product['name'] . " (Quantity: " . $itemData['quantity'] . ") Total Cost: $" . ($itemData['price'] * $itemData['quantity']) . "<br>";
    }
    
    // Display total cost and item count
    echo "Total Items: " . $_SESSION['item_count'];
    echo "<br>Total Cost: $" . $_SESSION['total_cost'];
}


function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        
        // Update total cost and item count in session
        $_SESSION['total_cost'] -= $_SESSION['cart'][$productId]['price'];
        $_SESSION['item_count']--;
        
        if (!empty($_SESSION['cart'])) {
            ksort($_SESSION['cart']);
        }
    }
}


<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($productId) {
    global $mysqli; // Assuming you're using a database for products
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Simulate getting product data from DB, replace with actual function call
        $product = array('name' => 'Product Name', 'price' => 19.99);
        $_SESSION['cart'][$productId] = array('name' => $product['name'], 'price' => $product['price']);
        $_SESSION['cart'][$productId]['quantity'] = 1;
        
        // Update total cost and item count in session
        if (!isset($_SESSION['total_cost'])) {
            $_SESSION['total_cost'] = 0;
        }
        if (!isset($_SESSION['item_count'])) {
            $_SESSION['item_count'] = 0;
        }
        $_SESSION['total_cost'] += $product['price'];
        $_SESSION['item_count']++;
    }
}

// Function to view cart
function viewCart() {
    global $mysqli;
    
    // Print each item in the cart with quantity and total cost for that item
    foreach ($_SESSION['cart'] as $productId => $itemData) {
        echo "Product: " . $itemData['name'] . " (Quantity: " . $itemData['quantity'] . ") Total Cost: $" . ($itemData['price'] * $itemData['quantity']) . "<br>";
    }
    
    // Display total cost and item count
    echo "Total Items: " . $_SESSION['item_count'];
    echo "<br>Total Cost: $" . $_SESSION['total_cost'];
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        
        // Update total cost and item count in session
        $_SESSION['total_cost'] -= $_SESSION['cart'][$productId]['price'];
        $_SESSION['item_count']--;
        
        if (!empty($_SESSION['cart'])) {
            ksort($_SESSION['cart']);
        }
    }
}

// Example usage:
addItemToCart(1);
viewCart();
removeFromCart(1);

?>


<?php
// Start session
session_start();

// Function to add product to cart
function addToCart($productId) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = array();
    }
    
    // Check if product is already in the cart, update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += 1;
            return;
        }
    }
    
    // Add new product to the cart
    $product = getProduct($productId);
    $cart[] = array('id' => $productId, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => 1);
    $_SESSION['cart'] = $cart;
}

// Function to get product details by id
function getProduct($id) {
    global $db; // Assuming you've defined a database connection object
    
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($db, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return array();
    }
}

// Add a product to the cart
addToCart(1); // For example, add 'Product A' with id = 1

// Display the cart content
echo "Your Cart:
";
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    foreach ($cart as $item) {
        echo "$item[name] x $item[quantity]: $" . number_format($item['price'] * $item['quantity'], 2) . "
";
    }
}
?>


session_start();


// Assuming $product_id, $name, and $price are set from your database or source
if (isset($_POST['add_to_cart'])) {
    // Add the current product details to cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if the product is already in cart, increment its quantity otherwise
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1;  // Increment quantity of existing item
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = array(
            'id' => $product_id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1  // Quantity starts at 1
        );
    }
}


if (isset($_SESSION['cart'])) {
    echo '<h2>Cart Contents</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Product ID: ' . $item['id'] . ', Name: ' . $item['name'] . ', Price: ' . $item['price'] . ', Quantity: ' . $item['quantity'] . '<br/>';
    }
}


$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
echo '<h2>Total: $' . number_format($total, 2) . '</h2>';


<?php
session_start();

// Assuming $product_id, $name, and $price are set from your database or source

if (isset($_POST['add_to_cart'])) {
    // Add the current product details to cart...
    // The code here is similar to Step 3's example.
}

if (isset($_POST['remove_from_cart'])) {
    // Remove item from cart...
}

// Displaying Cart Items
if (isset($_SESSION['cart'])) {
    echo '<h2>Cart Contents</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Product ID: ' . $item['id'] . ', Name: ' . $item['name'] . ', Price: ' . $item['price'] . ', Quantity: ' . $item['quantity'];
        
        // Add a form to remove this item
        echo '<form action="" method="post">';
        echo '<input type="hidden" name="remove_item_id" value="' . $item['id'] . '">';
        echo '<button type="submit" name="remove_from_cart">Remove</button>';
        echo '</form><br/>';
    }
}

$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
echo '<h2>Total: $' . number_format($total, 2) . '</h2>';
?>


<?php
session_start();
?>


<?php
$cart = array(
    'items' => array(),
);
?>


<?php
function add_item($product_id, $quantity) {
    global $cart;
    if (isset($_SESSION['cart'])) {
        // If cart is already set, append new item to it
        $_SESSION['cart']['items'][] = array('id' => $product_id, 'quantity' => $quantity);
    } else {
        // Initialize cart with the new item
        $_SESSION['cart'] = array(
            'items' => array(array('id' => $product_id, 'quantity' => $quantity))
        );
    }
}
?>


<?php
function display_cart() {
    global $cart;
    if (isset($_SESSION['cart'])) {
        echo "Cart Contents:
";
        foreach ($_SESSION['cart']['items'] as $item) {
            echo "- Product ID: {$item['id']} x {$item['quantity']}
";
        }
    } else {
        echo "No items in cart.
";
    }
}
?>


<?php
// Initialize session and cart structure
session_start();
$cart = array(
    'items' => array(),
);

// Add some products to the cart
add_item(1, 2);
add_item(3, 1);
add_item(5, 4);

// Display cart contents
display_cart();

// Output:
// Cart Contents:
// - Product ID: 1 x 2
// - Product ID: 3 x 1
// - Product ID: 5 x 4
?>


<?php

// Start session
session_start();

// If cart is empty, initialize it with an array.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Functions to manage the cart
function add_to_cart($product_id) {
    global $db;
    // Assuming you are connecting to a database here. This is where your product data would be fetched based on its id.
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'quantity'   => 1
        );
    }
}

function remove_from_cart($product_id) {
    global $db;
    // Remove the product from cart if it exists.
    unset($_SESSION['cart'][$product_id]);
}

function update_cart_quantity($product_id, $new_quantity = null) {
    if ($new_quantity !== null && (int)$new_quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Update the cart based on user action
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            add_to_cart($_POST['product_id']);
            break;
        case 'remove':
            remove_from_cart($_POST['product_id']);
            break;
        case 'update_quantity':
            update_cart_quantity($_POST['product_id'], $_POST['quantity']);
            break;
    }
}

?>


;session.save_path = "C:\path\to\your\session\folder"


<?php
    session_start();
?>


<?php
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
?>


<?php
function addToCart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart and update quantity accordingly
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Already exists, so no need to add it again.
        }
    }

    // If not already in cart, add new item
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );
}
?>


<?php
function viewCart() {
    global $_SESSION;
    
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
    } else {
        foreach ($_SESSION['cart'] as $item) {
            echo $item['id'] . " - Quantity: " . $item['quantity'] . "<br>";
        }
    }
}
?>


<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function addToCart($product_id, $quantity) {
    // As defined above
}

addToCart(1, 2);
addToCart(2, 3);

viewCart();

?>


<?php
session_start();
?>


<?php
session_start();

// Assuming $product_id is the ID of the product being added and $quantity is the amount
if (isset($_POST['add_to_cart'])) {
    // Product ID from the form submission
    $pid = $_POST['product_id'];
    
    // Quantity from the form submission
    $qty = $_POST['quantity'];

    // Check if the product is already in the cart to update its quantity instead of adding it again.
    if (!isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] = array('name' => '', 'price' => 0, 'quantity' => $qty);
    } else {
        // If the product is already in the cart, update its quantity
        $_SESSION['cart'][$pid]['quantity'] += $qty;
    }

    // Update the name and price for new additions or when updating a product
    if (!$_SESSION['cart'][$pid]['name']) {  // This means it's a new item
        // Retrieve product info from database (simplified example)
        $_SESSION['cart'][$pid]['name'] = 'Product ' . $pid;
        $_SESSION['cart'][$pid]['price'] = $pid * 10;  // Simplified pricing logic based on ID
    }
}
?>


<?php
session_start();
?>

<h2>Cart</h2>

<table>
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>

    <?php
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $pid => $item) { ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
            </tr>
        <?php }
    } else {
        echo "<tr><td colspan='3'>No items in cart.</td></tr>";
    }
    ?>
</table>

<h2>Total: $<?php echo number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) , 2); ?></h2>


if (isset($_POST['remove_from_cart'])) {
    $pid = $_POST['product_id'];
    
    if (isset($_SESSION['cart'][$pid])) {
        unset($_SESSION['cart'][$pid]);
    }
}


if (isset($_POST['update_quantity'])) {
    $pid = $_POST['product_id'];
    $qty = $_POST['quantity'];
    
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity'] = $qty;
    }
}


<?php

// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

class Cart {
    private $cart;

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart =& $_SESSION['cart'];
    }

    // Add a product to the cart
    public function addProduct($id, $name, $price) {
        if (array_key_exists($id, $this->cart)) {
            $quantity = $this->cart[$id]['quantity'] + 1;
            $totalPrice = ($price * $this->cart[$id]['quantity']) + ($price);
            $this->cart[$id] = array('name' => $name, 'price' => $price, 'quantity' => $quantity, 'total_price' => $totalPrice);
        } else {
            $this->cart[$id] = array('name' => $name, 'price' => $price, 'quantity' => 1, 'total_price' => $price);
        }
    }

    // Remove a product from the cart
    public function removeProduct($id) {
        if (array_key_exists($id, $this->cart)) {
            unset($this->cart[$id]);
        }
    }

    // Clear the entire cart
    public function clearCart() {
        $_SESSION['cart'] = array();
    }

    // Calculate total cost of items in the cart
    public function calculateTotal() {
        $totalCost = 0;
        foreach ($this->cart as $item) {
            $totalCost += $item['total_price'];
        }
        return $totalCost;
    }
}

// Example usage:
$cart = new Cart();

// Adding a product to the cart
$cart->addProduct(1, 'Product 1', 10.99);
$cart->addProduct(2, 'Product 2', 5.49);

// Updating quantity of an item (not shown but can be similar to adding)
// $cart->updateQuantity(1, 3);

// Removing a product from the cart
$cart->removeProduct(2);

// Calculating total cost
$totalCost = $cart->calculateTotal();

print("Cart Contents:
");
foreach ($cart->cart as $item) {
    print($item['name'] . " x" . $item['quantity'] . " = $" . number_format($item['total_price'], 2) . "
");
}
print("
Total Cost: $" . number_format($cart->calculateTotal(), 2));


<?php

// Initialize an array to store the cart data in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($itemCode, $itemName, $price) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['code'] == $itemCode) {
            // If it's found, increase the quantity of that item
            $item['quantity']++;
            return; // Item found, exit function
        }
    }

    // If not found, add a new item to the cart array
    $_SESSION['cart'][] = array('code' => $itemCode, 'name' => $itemName, 'price' => $price, 'quantity' => 1);
}

// Function to remove an item from cart
function removeFromCart($itemCode) {
    global $_SESSION;
    
    // Filter the items in the cart and exclude the one that matches the code we're removing
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($itemCode) {
        return $item['code'] != $itemCode;
    });
}

// Function to update item quantity in cart
function updateQuantity($itemCode, $newQuantity) {
    global $_SESSION;
    
    // Loop through the items in the cart to find the one with the matching code
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['code'] == $itemCode) {
            // Update the quantity of that item
            $item['quantity'] = $newQuantity;
            return; // Item found, exit function
        }
    }
}

// Function to calculate total cost
function getTotalCost() {
    global $_SESSION;
    
    // Initialize the total cost to 0
    $totalCost = 0;
    
    // Loop through each item in the cart and add up its cost
    foreach ($_SESSION['cart'] as $item) {
        $totalCost += ($item['price'] * $item['quantity']);
    }
    
    return $totalCost;
}

// Display the current total cost of items in the cart
echo "Total Cost: $" . getTotalCost() . "<br>";

// Add some example items to the cart
addToCart("A123", "Apple Watch", 299.99);
addToCart("B456", "Bike Helmet", 49.99);

// Display what's currently in the cart
echo "<h3>Items in Your Cart:</h3>";
foreach ($_SESSION['cart'] as $item) {
    echo "Item: $item[name], Code: $item[code], Price: $" . $item['price'] . ", Quantity: $item[quantity]<br>";
}

// Let's remove one of the items
removeFromCart("A123");

?>


<?php

// If session isn't started, start it now
if (!isset($_SESSION)) {
    session_start();
}

// Set the path for the cart contents to store in session
$cart = &$_SESSION['cart'];

// Define function to add items to the cart
function addToCart($item_id, $item_name, $price) {
    global $cart;
    
    // Check if item already exists in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Increment quantity of existing item
            $item['quantity']++;
            return; // Item found, so we can stop looking.
        }
    }
    
    // If not found, add a new entry for the item to the cart
    $cart[] = array(
        'id' => $item_id,
        'name' => $item_name,
        'price' => $price,
        'quantity' => 1
    );
}

// Define function to remove items from the cart
function removeFromCart($item_id) {
    global $cart;
    
    // Find the item in the cart and decrement its quantity.
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            if (--$item['quantity'] <= 0) {
                unset($item); // If it's gone, remove it from the cart
                return true; // Item removed successfully
            }
            break;
        }
    }
    
    return false; // Item not found or quantity didn't go to zero.
}

// Define function to display cart content
function displayCartContent() {
    global $cart;
    
    echo '<h2>Cart Content:</h2>';
    if (count($cart) > 0) {
        foreach ($cart as &$item) {
            echo "Item: {$item['name']} Quantity: {$item['quantity']}. Price per item: £{$item['price']}. Subtotal for this item: £" . $item['price'] * $item['quantity'] . "<br>";
        }
        
        // Calculate the total cost
        $total = array_sum(array_map(function($i) { return ($i['price'] * $i['quantity']); }, $cart));
        echo "Total: £{$total}";
    } else {
        echo 'Your cart is empty.';
    }
}

// Example usage of functions

// Add items to the cart
addToCart(1, "Product 1", 10.99);
addToCart(2, "Product 2", 5.49);

// Remove an item from the cart
removeFromCart(1);

// Display the cart content
displayCartContent();

?>


<?php
session_start();
?>


$cart = array();


function add_to_cart($item_id, $quantity) {
    global $cart;
    
    if (array_key_exists($item_id, $cart)) {
        // Item already in cart, increment quantity
        $cart[$item_id] += $quantity;
    } else {
        // New item, add to cart with quantity
        $cart[$item_id] = $quantity;
    }
}


function update_cart_session() {
    global $cart;
    
    $_SESSION['cart'] = $cart;
}


if (array_key_exists('cart', $_SESSION)) {
    $cart = $_SESSION['cart'];
} else {
    $cart = array();
}


// Add an item to the cart with quantity 2
add_to_cart(1, 2);

// Update the cart session
update_cart_session();

// Retrieve the cart items from the session
$cart = $_SESSION['cart'];

// Display the cart contents
foreach ($cart as $item_id => $quantity) {
    echo "Item ID: $item_id, Quantity: $quantity<br>";
}


<?php

// Initialize the cart session array
$cart = $_SESSION['cart'] ?? [];

?>


function add_to_cart($item_id, $quantity = 1) {
    global $cart;

    // Check if the item already exists in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Increment the quantity of the existing item
            $item['quantity'] += $quantity;
            return true;
        }
    }

    // Add new item to the cart
    $cart[] = ['id' => $item_id, 'quantity' => $quantity];
    return false;
}


function remove_from_cart($item_id) {
    global $cart;

    // Find and remove the item from the cart
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($cart[$key]);
            break;
        }
    }

    return true; // Assume successful removal
}


function update_cart_quantity($item_id, $new_quantity) {
    global $cart;

    // Find the item in the cart and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return true;
        }
    }

    return false; // Item not found
}


// Initialize the cart session
session_start();
$_SESSION['cart'] = [];

// Add items to the cart
add_to_cart(1, 2); // Add 2x Item #1
add_to_cart(3, 1); // Add 1x Item #3

// Update quantity of an item
update_cart_quantity(1, 3); // Update 2x Item #1 to 3x

// Remove an item from the cart
remove_from_cart(3);

// Print the contents of the cart
print_r($cart);


Array
(
    [0] => Array
        (
            [id] => 1
            [quantity] => 3
        )

)


<?php
  // Start the session
  if (!isset($_SESSION)) {
    session_start();
  }

  // Check if the cart is already set in the session
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // Initialize an empty array for the cart
  }

  // Function to add item to cart
  function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (!isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    } else {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
  }

  // Function to remove item from cart
  function remove_item_from_cart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
  }

  // Function to update quantity of an item in the cart
  function update_quantity_in_cart($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
  }

  // Function to get the total quantity of all items in the cart
  function get_total_quantity_in_cart() {
    global $_SESSION;
    $total_quantity = 0;
    foreach ($_SESSION['cart'] as $item) {
      $total_quantity += $item['quantity'];
    }
    return $total_quantity;
  }

  // Function to calculate the total cost of all items in the cart
  function get_total_cost_in_cart() {
    global $_SESSION;
    // Assuming you have a function `get_product_price($product_id)` that returns the price of a product
    $total_cost = 0;
    foreach ($_SESSION['cart'] as $item) {
      $product_id = array_keys($_SESSION['cart'])[array_search($item, $_SESSION['cart'])];
      $price = get_product_price($product_id);
      $total_cost += $price * $item['quantity'];
    }
    return $total_cost;
  }

  // Example usage:
  add_item_to_cart(1, 2); // Add product with id 1 to cart in quantity 2
  remove_item_from_cart(1); // Remove product with id 1 from cart
  update_quantity_in_cart(1, 3); // Update quantity of product with id 1 in cart to 3

  echo "Total Quantity: " . get_total_quantity_in_cart() . "<br>";
  echo "Total Cost: $" . get_total_cost_in_cart();
?>


<?php

// Start the session
session_start();

// Initialize an empty cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>


<?php

// Get the product ID, name, price, and quantity from your database or form submission
$product_id = 123;
$product_name = "Example Product";
$price = 19.99;
$quantity = 2;

// Check if the product is already in the cart
if (in_array($product_id, $_SESSION['cart']['products'])) {
    // If it exists, update the quantity
    foreach ($_SESSION['cart']['products'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }
} else {
    // Add new product to cart
    $_SESSION['cart']['products'][] = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );
}

// Save the updated cart
$_SESSION['cart']['updated'] = true;

?>


<?php

// Get the current cart content
$cart = $_SESSION['cart']['products'];

// Output the cart contents
echo "<h2>Cart Contents:</h2>";
foreach ($cart as $item) {
    echo "$item[name] x $item[quantity] @ $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
}

?>


<?php

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo 'You must be logged in to add items to your cart.';
    exit;
}

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // Increment the quantity of the existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to the cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;

    // Find and remove the item with the specified product ID
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Update quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Find and update the quantity of the item with the specified product ID
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Display cart contents
function display_cart() {
    global $_SESSION;

    echo '<h2>Your Cart</h2>';
    foreach ($_SESSION['cart'] as &$item) {
        echo 'Product ID: ' . $item['product_id'] . ', Quantity: ' . $item['quantity'];
        echo '<br><a href="#" onclick="updateQuantity(' . $item['product_id'] . ', this)">Update Quantity</a>';
        echo '<br><a href="#" onclick="removeItem(' . $item['product_id'] . ')">Remove Item</a>';
    }
}

// Example usage
add_item_to_cart(1, 2);
add_item_to_cart(3, 4);

display_cart();

?>


session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addToCart($productId, $quantity = 1) {
    global $_SESSION;

    if (isset($_SESSION['cart'][$productId])) {
        // Product is already in the cart; update quantity.
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Add product to the cart with default quantity or specified.
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'name' => '', // You might want a more robust way of storing product data.
            'price' => 0, // Initial price; adjust based on real-time info.
            'quantity' => $quantity
        );
    }
}


function removeFromCart($productId) {
    global $_SESSION;

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}


function updateQuantity($productId, $newQuantity) {
    global $_SESSION;

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}


function displayCart() {
    global $_SESSION;

    echo "Your Cart:<br>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product Name: " . $item['name'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Price: $" . number_format($item['price'] * $item['quantity'], 2) . "<br><hr>";
    }
}


<?php

// Start the session
session_start();

// Check if the cart is already initialized
if (!isset($_SESSION['cart'])) {
    // Initialize an empty cart array
    $_SESSION['cart'] = array();
}

?>


<?php

// Get the product ID and quantity from the URL or form submission
$product_id = $_GET['id'];
$quantity = $_POST['quantity'];

// Check if the product ID and quantity are valid
if ($product_id && $quantity) {
    // Add the item to the cart
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);

    // Redirect back to the shop page
    header('Location: shop.php');
} else {
    echo 'Error adding product to cart';
}

?>


<?php

// Display the cart contents
echo '<h1>Cart Contents</h1>';
echo '<ul>';

foreach ($_SESSION['cart'] as $product_id => $item) {
    echo '<li>' . $item['quantity'] . ' x Product ID: ' . $product_id . '</li>';
}

echo '</ul>';

?>


<?php

// Check if the cart session exists, otherwise create a new one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>


<?php

// Get the product ID and quantity from the request data
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if the product is already in the cart
if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity by 1
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
} else {
    // If not, add the product to the cart with the specified quantity
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
}

// Redirect back to the cart page
header('Location: cart.php');
exit;

?>


<?php

// Display the cart contents
echo '<h2>Cart Contents:</h2>';
echo '<ul>';

foreach ($_SESSION['cart'] as $product_id => $product) {
    echo '<li>Product ID: ' . $product_id . ', Quantity: ' . $product['quantity'] . '</li>';
}

echo '</ul>';

// Display the total cost
$total_cost = 0;
foreach ($_SESSION['cart'] as $product_id => $product) {
    // Assume we have a function to get the product price from the database
    $price = getProductPrice($product_id);
    $total_cost += $price * $product['quantity'];
}

echo '<p>Total Cost: ' . $total_cost . '</p>';

?>

<!-- You can add a form to remove items or update quantities here -->


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return true;
        }
    }

    // If not, add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return true;
}

// Function to update quantity of item in cart
function update_cart_item($product_id, $new_quantity) {
    global $_SESSION;

    // Loop through cart items and find the one with matching ID
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update quantity of item
            $item['quantity'] = $new_quantity;
            return true;
        }
    }

    // If product is not found in cart, add it with new quantity
    add_to_cart($product_id, $new_quantity);
    return true;
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;

    // Loop through cart items and find the one with matching ID
    foreach ($_SESSION['cart'] as &$key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }

    // If product is not found in cart, do nothing
    return false;
}

// Function to display cart contents
function show_cart() {
    global $_SESSION;

    echo "<h2>Cart Contents:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Quantity</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>" . $item['id'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Example usage
add_to_cart(1, 2); // Add product with ID 1 to cart in quantity of 2
show_cart(); // Display contents of cart

?>


// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array('items' => array(), 'total' => 0);
}


function add_to_cart($product_id) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart']['items'] as $item) {
        if ($item['id'] == $product_id) {
            // Increment quantity of existing item
            $item['quantity']++;
            return;
        }
    }
    
    // Add new item to cart
    $_SESSION['cart']['items'][] = array('id' => $product_id, 'quantity' => 1);
}


function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find index of product in cart
    $index = -1;
    foreach ($_SESSION['cart']['items'] as $i => $item) {
        if ($item['id'] == $product_id) {
            $index = $i;
            break;
        }
    }
    
    // Remove item from cart
    if ($index !== -1) {
        unset($_SESSION['cart']['items'][$index]);
        $_SESSION['cart']['items'] = array_values($_SESSION['cart']['items']);
    }
}


function update_cart_total() {
    global $_SESSION;
    
    // Calculate total cost of all items
    $total = 0;
    foreach ($_SESSION['cart']['items'] as $item) {
        $price = get_product_price($item['id']); // Assuming function to retrieve product price exists
        $total += $price * $item['quantity'];
    }
    
    // Update cart total
    $_SESSION['cart']['total'] = $total;
}


// Start session
session_start();

// Add item to cart
add_to_cart(1);

// Remove item from cart
remove_from_cart(1);

// Update cart total
update_cart_total();


<?php

// Start the session or resume an existing one.
session_start();

// Function to add items to the cart
function addToCart($productId, $quantity) {
    // Check if the product is already in the cart.
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add it to the cart
    $_SESSION['cart'][] = array(
        'product_id' => $productId,
        'quantity'   => $quantity
    );
}

// Function to display the contents of the cart
function showCart() {
    if (isset($_SESSION['cart'])) {
        echo "<h2>Your Cart:</h2>";
        foreach ($_SESSION['cart'] as $item) {
            echo "Product ID: $item[product_id], Quantity: $item[quantity]<br>";
        }
        echo "<hr>";
    } else {
        echo "<p>No items in your cart.</p>";
    }
}

// Example usage:
// Add some items to the cart
addToCart(1, 2); // Add product with ID 1 in quantity of 2
addToCart(3, 5);

// Display the contents of the cart
showCart();

?>


<?php
session_start();
?>


$_SESSION['cart'] = array();


function addItemToCart($itemId, $itemName, $itemPrice) {
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            // Update quantity
            $item['quantity'] += 1;
            return; // Item already exists, exit function
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $itemId,
        'name' => $itemName,
        'price' => $itemPrice,
        'quantity' => 1
    );
}


function updateCartItemQuantity($itemId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] = $newQuantity;
            return; // Item found and updated, exit function
        }
    }

    // Item not found in cart, do nothing
}


function removeItemFromCart($itemId) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $itemId) {
            unset($_SESSION['cart'][$key]);
            return; // Item removed, exit function
        }
    }

    // Item not found in cart, do nothing
}


if (!empty($_SESSION['cart'])) {
    echo '<h2>Cart:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Item: ' . $item['name'] . ', Price: ' . $item['price'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}


<?php
session_start();

$_SESSION['cart'] = array();

function addItemToCart($itemId, $itemName, $itemPrice) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] += 1;
            return; // Item already exists, exit function
        }
    }

    $_SESSION['cart'][] = array(
        'id' => $itemId,
        'name' => $itemName,
        'price' => $itemPrice,
        'quantity' => 1
    );
}

function updateCartItemQuantity($itemId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] = $newQuantity;
            return; // Item found and updated, exit function
        }
    }

    // Item not found in cart, do nothing
}

function removeItemFromCart($itemId) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $itemId) {
            unset($_SESSION['cart'][$key]);
            return; // Item removed, exit function
        }
    }

    // Item not found in cart, do nothing
}

// Example usage:
addItemToCart(1, 'Item 1', 10.99);
addItemToCart(2, 'Item 2', 9.99);

if (!empty($_SESSION['cart'])) {
    echo '<h2>Cart:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Item: ' . $item['name'] . ', Price: ' . $item['price'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}
?>


<?php
// Start the session
session_start();

// Define the cart array
$cart = array();

// Check if the cart is already in the session
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}

// Add item to cart
function add_to_cart($product_id, $quantity) {
    global $cart;
    
    // Check if product is already in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already exists, increment quantity
        }
    }
    
    // Add new item to cart
    $cart[] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );
}

// Remove item from cart
function remove_from_cart($product_id) {
    global $cart;
    
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            unset($item);
            return; // Item removed successfully
        }
    }
}

// Update quantity of item in cart
function update_quantity($product_id, $quantity) {
    global $cart;
    
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return; // Quantity updated successfully
        }
    }
}

// Display cart contents
function display_cart() {
    global $cart;
    
    echo "Cart Contents:<br>";
    foreach ($cart as $item) {
        echo "$" . $item['id'] . " x " . $item['quantity'] . "<br>";
    }
}
?>


add_to_cart(123, 2); // Product ID: 123, Quantity: 2


remove_from_cart(123);


update_quantity(123, 1);


display_cart();


<?php
session_start();

// Check if the cart is already in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If not, add it to the cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Update item quantity in cart
function update_item_quantity($item_id, $quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Display cart contents
function display_cart() {
    global $_SESSION;
    
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[quantity] x Item #{$item[id]}<br>";
    }
}


<?php
require 'cart.php';

// Add item to cart
add_item_to_cart(1, 2); // add 2 items with id 1 to the cart

// Add another item to cart
add_item_to_cart(3, 1);

// Display cart contents
display_cart();

// Remove an item from cart
remove_item_from_cart(1);

// Update quantity of an item in cart
update_item_quantity(3, 2);


<?php
// Start the session
session_start();

// Check if cart exists; create one if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Sample item data - This is how you would add items to the cart
$item1 = array(
    'name' => 'Item 1',
    'price' => 9.99,
    'quantity' => 2
);

item2 = array(
    'name' => 'Item 2',
    'price' => 19.99,
    'quantity' => 1
);

// Add items to the cart
$_SESSION['cart'][] = $item1;
$_SESSION['cart'][] = $item2;

// Displaying the contents of the cart for demonstration
print_r($_SESSION['cart']);
?>


<?php

// Start session if not already started
session_start();

function add_item_to_cart($product_id, $quantity) {
    // Get current cart data from session or initialize it if not set
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    
    // Check if product is already in cart
    if (isset($cart[$product_id])) {
        // If yes, increment quantity
        $cart[$product_id] += $quantity;
    } else {
        // If not, add to cart with initial quantity
        $cart[$product_id] = $quantity;
    }
    
    // Update session with new cart data
    $_SESSION['cart'] = $cart;
}

function remove_item_from_cart($product_id) {
    // Get current cart data from session
    $cart = $_SESSION['cart'];
    
    // Check if product is in cart
    if (isset($cart[$product_id])) {
        // If yes, unset product from cart
        unset($cart[$product_id]);
        
        // Update session with new cart data
        $_SESSION['cart'] = $cart;
    }
}

function get_cart_contents() {
    // Get current cart data from session
    return $_SESSION['cart'];
}

?>


<?php

// Include our cart functions
include 'cart.php';

// Display current cart contents
$cart_contents = get_cart_contents();
?>

<h1>My Cart</h1>

<!-- Display cart items -->
<ul>
    <?php foreach ($cart_contents as $product_id => $quantity): ?>
        <li><?= $product_id ?> x <?= $quantity ?></li>
    <?php endforeach; ?>
</ul>

<!-- Form to add/remove items from cart -->
<form action="" method="post">
    <input type="hidden" name="product_id" value="">
    <input type="number" name="quantity" value="1">
    <button type="submit">Add/Update Item</button>
    <button type="submit" name="remove" value="true">Remove Item</button>
</form>

<?php

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id'])) {
        // Add or update item in cart
        add_item_to_cart($_POST['product_id'], $_POST['quantity']);
        
        // Redirect to current page to reflect changes
        header('Location: index.php');
        exit;
    } elseif (isset($_POST['remove'])) {
        // Remove item from cart
        remove_item_from_cart($_GET['id']);
        
        // Redirect to current page to reflect changes
        header('Location: index.php');
        exit;
    }
}

?>


function get_cart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    return $_SESSION['cart'];
}


function add_to_cart($product_id, $quantity) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Validate product ID and quantity
    if (!is_numeric($product_id) || !is_numeric($quantity)) {
        throw new Exception("Invalid product ID or quantity");
    }
    
    $found = false;
    foreach ($GLOBALS['get_cart']() as &$item) {
        if ($item['id'] == $product_id) {
            // Update existing item
            $item['quantity'] += (int)$quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        // Add new item to cart
        $GLOBALS['get_cart']()[count($GLOBALS['get_cart'])] = array(
            'id' => $product_id,
            'quantity' => (int)$quantity
        );
    }
}


function remove_from_cart($product_id) {
    if (empty($_SESSION['cart'])) {
        return;
    }
    
    // Validate product ID
    if (!is_numeric($product_id)) {
        throw new Exception("Invalid product ID");
    }
    
    $index = -1;
    foreach ($GLOBALS['get_cart']() as &$item) {
        if ($item['id'] == $product_id) {
            $index = array_search($item, $GLOBALS['get_cart']());
            break;
        }
    }
    
    if ($index !== false) {
        unset($GLOBALS['get_cart'][$index]);
        sort($GLOBALS['get_cart']);
    }
}


function update_quantity($product_id, $quantity) {
    if (empty($_SESSION['cart'])) {
        return;
    }
    
    // Validate product ID and quantity
    if (!is_numeric($product_id) || !is_numeric($quantity)) {
        throw new Exception("Invalid product ID or quantity");
    }
    
    foreach ($GLOBALS['get_cart']() as &$item) {
        if ($item['id'] == $product_id) {
            // Update existing item
            $item['quantity'] = (int)$quantity;
            break;
        }
    }
}


// Add an item to the cart with a quantity of 2.
add_to_cart(1, 2);

// Remove an item from the cart by its ID.
remove_from_cart(1);

// Update the quantity of an existing item in the cart.
update_quantity(1, 3);


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it exists, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    // If not, add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update quantity of item in cart
function update_quantity($product_id, $quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "<br>";
    }
}

// Example usage
add_item_to_cart(1, 2);
add_item_to_cart(2, 3);
display_cart();
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Assuming we have a form for selecting items and adding them to the cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    if (!in_array($item_id, $_SESSION['cart'])) {
        array_push($_SESSION['cart'], $item_id);
    } else {
        foreach ($_SESSION['cart'] as &$id) {
            if ($id == $item_id) {
                $id = $item_id; // Update quantity
                break;
            }
        }
    }

    echo "Item added to cart successfully.";
}

// Display items in the cart
if (count($_SESSION['cart']) > 0) {
    echo "Your Cart:";
    foreach ($_SESSION['cart'] as $id) {
        echo " - Item ID: $id";
    }
} else {
    echo "Cart is empty.";
}
?>


<?php
session_start();

echo "Your Cart Contents:<br>";
foreach ($_SESSION['cart'] as $item_id) {
    // Assuming we have a function to retrieve item details from the database
    $item_details = get_item_details($item_id);
    
    echo "Item ID: $item_id<br>";
    echo "Item Name: $item_details[name]<br>";
    echo "Quantity: ( quantity will be displayed here, assuming you store it somewhere in the session )<br><hr>";
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $item_id = $_GET['remove'];
    if (($key = array_search($item_id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }
    
    echo "Item removed from cart successfully.";
}

// Update item quantity in the cart
if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];

    // Assuming you have a way to update item quantities elsewhere
    if (($key = array_search($item_id, $_SESSION['cart'])) !== false) {
        $_SESSION['cart'][$key] = $item_id;
        
        echo "Quantity updated successfully.";
    }
}
?>


// Check if session is started, and start it if not
if (!isset($_SESSION)) {
    session_start();
}

// If no cart exists, initialize one with an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addToCart($productId, $quantity) {
    global $_SESSION;
    
    // Check if the product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // If it does, increment its quantity by adding the new quantity to the current one
            $item['quantity'] += $quantity;
            return; // No need to add duplicate products
        }
    }
    
    // If not found, add a new item to cart with specified id and quantity
    $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
}


function removeFromCart($productId) {
    global $_SESSION;
    
    // Find the product in cart and remove it if found
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return; // Remove from session directly
        }
    }
}


function updateQuantity($productId, $newQuantity) {
    global $_SESSION;
    
    // Find the product in cart and update its quantity if found
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity;
            return; // Update successfully
        }
    }
}


function displayCart() {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: $item[id], Quantity: $item[quantity]<br>";
    }
}


addToCart(1, 2); // Add product with id 1 in quantity 2


<?php
session_start();
?>


$_SESSION['cart'] = array();


function add_to_cart($id, $name, $quantity) {
    $_SESSION['cart'][] = array(
        'id' => $id,
        'name' => $name,
        'quantity' => $quantity
    );
}


function update_cart($id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}


function remove_from_cart($id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}


// Initialize the session and cart array
session_start();
$_SESSION['cart'] = array();

// Add some items to the cart
add_to_cart(1, 'Item 1', 2);
add_to_cart(2, 'Item 2', 3);

// Update an item's quantity in the cart
update_cart(1, 4);

// Remove an item from the cart
remove_from_cart(2);

// Print out the contents of the cart
print_r($_SESSION['cart']);


Array
(
    [0] => Array
        (
            [id] => 1
            [name] => Item 1
            [quantity] => 4
        )

)


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Remove item from cart
function remove_from_cart($product_id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Display cart contents
function display_cart() {
    global $_SESSION;
    echo "Cart Contents:
";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['id']} (Quantity: {$item['quantity']})
";
    }
}
?>

<form action="" method="post">
    <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
    <label>Quantity:</label>
    <input type="number" name="quantity" value="1">
    <button type="submit">Add to Cart</button>
</form>

<?php
if (isset($_POST['product_id'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

display_cart();
?>


<?php
session_start();

// Display products
$products = array(
    array('id' => 1, 'name' => 'Product A'),
    array('id' => 2, 'name' => 'Product B'),
    // ...
);
?>

<h1>Products</h1>
<ul>
    <?php foreach ($products as $product) { ?>
        <li><a href="?product_id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></li>
    <?php } ?>
</ul>

<?php
// Include the cart script
include 'cart.php';
?>


<?php

// Starting Session
session_start();

function add_item_to_cart($item_id, $item_name, $price) {
    // Check if the item is already in the cart.
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        $_SESSION['cart'][$item_id] = array('name' => $item_name, 'price' => $price, 'quantity' => 1);
    }
}

function update_quantity($item_id, $new_quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    } else {
        echo "Item not found in cart.";
    }
}

function remove_item_from_cart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    } else {
        echo "Item not found in cart.";
    }
}

function clear_cart() {
    $_SESSION['cart'] = array();
}

// Example usage:
if (isset($_POST['add'])) {
    add_item_to_cart($_POST['item_id'], $_POST['item_name'], $_POST['price']);
} elseif (isset($_POST['update'])) {
    update_quantity($_POST['item_id'], $_POST['quantity']);
} elseif (isset($_POST['remove'])) {
    remove_item_from_cart($_POST['item_id']);
} elseif (isset($_POST['clear'])) {
    clear_cart();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart Session</title>
</head>
<body>

<form action="" method="post">
    Item ID: <input type="text" name="item_id"><br><br>
    Item Name: <input type="text" name="item_name"><br><br>
    Price: <input type="text" name="price"><br><br>
    <button type="submit" name="add">Add to Cart</button>
</form>

<?php
if (isset($_SESSION['cart'])) {
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $item_id => $product) {
        echo "Item Name: {$product['name']} | Quantity: {$product['quantity']} | Price: {$product['price']}<br>";
        echo "<form action='' method='post'><input type='hidden' name='item_id' value='{$item_id}'>
                <button type='submit' name='remove'>Remove</button>
                </form><br>";
    }
}
?>

<form action="" method="post">
    Item ID: <input type="text" name="item_id"><br><br>
    New Quantity: <input type="text" name="quantity"><br><br>
    <button type="submit" name="update">Update Quantity</button>
</form>

<button type="submit" formaction="" formmethod="post" name="clear">Clear Cart</button>

</body>
</html>


session_start();


$cart = array(
    'products' => array(),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
);


function addToCart($productId, $name, $price, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart['products'] as &$product) {
        if ($product['id'] == $productId) {
            // Increment the quantity of the existing item
            $product['quantity'] += $quantity;
            return;
        }
    }

    // Add new product to the cart
    $cart['products'][] = array(
        'id' => $productId,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    );

    // Update subtotal, tax, and total
    $cart['subtotal'] += ($price * $quantity);
    $cart['tax'] = ($cart['subtotal'] * 0.08); // Assume 8% tax rate
    $cart['total'] = $cart['subtotal'] + $cart['tax'];
}


session_regenerate_id();
$_SESSION['cart'] = $cart;


print_r($_SESSION['cart']);


<?php

session_start();

$cart = array(
    'products' => array(),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
);

function addToCart($productId, $name, $price, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart['products'] as &$product) {
        if ($product['id'] == $productId) {
            // Increment the quantity of the existing item
            $product['quantity'] += $quantity;
            return;
        }
    }

    // Add new product to the cart
    $cart['products'][] = array(
        'id' => $productId,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    );

    // Update subtotal, tax, and total
    $cart['subtotal'] += ($price * $quantity);
    $cart['tax'] = ($cart['subtotal'] * 0.08); // Assume 8% tax rate
    $cart['total'] = $cart['subtotal'] + $cart['tax'];
}

// Add some sample products to the cart
addToCart(1, 'Product A', 9.99, 2);
addToCart(2, 'Product B', 19.99, 3);

session_regenerate_id();
$_SESSION['cart'] = $cart;

print_r($_SESSION['cart']);

?>


<?php

// Start the session
session_start();

// Check if the cart is already set
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return true;
        }
    }
    
    // If not, add it to the cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    // Check if the item is in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            // If it is, unset it from the array
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
}

// Update item quantity in cart
function update_item_quantity($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item is in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, update its quantity
            $item['quantity'] = $quantity;
            return true;
        }
    }
}

// Get items in cart
function get_items_in_cart() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}

// Print the cart contents
function print_cart_contents() {
    global $_SESSION;
    
    echo '<ul>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<li>' . $item['id'] . ' x ' . $item['quantity'] . '</li>';
    }
    echo '</ul>';
}

?>


// Add an item to the cart with quantity 2
add_item_to_cart(123, 2);

// Add another item to the cart with quantity 1
add_item_to_cart(456, 1);

// Update the quantity of the first item in the cart
update_item_quantity(123, 3);

// Print the contents of the cart
print_cart_contents();

// Remove an item from the cart
remove_item_from_cart(456);


// cart.php

session_start();

// Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASSWORD', 'your_database_password');

// Connect to database using PDO
$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

// Function to add product to cart
function add_product_to_cart($product_id) {
  // Check if product exists in session cart
  $cart = $_SESSION['cart'] ?? [];
  
  // If not, create a new cart array with the product
  if (!isset($cart[$product_id])) {
    $cart[$product_id] = [
      'quantity' => 1,
      'price' => (string) get_product_price($product_id)
    ];
    
    $_SESSION['cart'] = $cart;
  } else {
    // If product already exists, increment quantity
    $cart[$product_id]['quantity']++;
  }
}

// Function to update product price in cart
function update_cart_prices() {
  foreach ($_SESSION['cart'] as &$product) {
    $product['price'] = (string) get_product_price($product['id']);
  }
}

// Function to retrieve product price from database
function get_product_price($product_id) {
  $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :id");
  $stmt->bindParam(':id', $product_id);
  $stmt->execute();
  
  return $stmt->fetchColumn();
}

// Function to display cart contents
function display_cart() {
  echo "<h2>Cart Contents:</h2>";
  
  foreach ($_SESSION['cart'] as $product) {
    echo "Product: " . get_product_name($product['id']) . ", Quantity: " . $product['quantity'] . ", Price: $" . number_format((float)$product['price'], 2);
    echo "<br><hr>";
  }
}

// Function to retrieve product name from database
function get_product_name($product_id) {
  $stmt = $pdo->prepare("SELECT name FROM products WHERE id = :id");
  $stmt->bindParam(':id', $product_id);
  $stmt->execute();
  
  return $stmt->fetchColumn();
}

// Add example products to cart
add_product_to_cart(1); // Product ID 1
add_product_to_cart(2); // Product ID 2

// Display cart contents
display_cart();

// Update prices in cart (optional)
update_cart_prices();

// Display updated cart contents
display_cart();


<?php
// Start the session
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Add item to cart
function add_to_cart($product_id, $quantity) {
  // Check if product already exists in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // Update quantity
      $item['quantity'] += $quantity;
      return;
    }
  }

  // Add new item to cart
  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'quantity' => $quantity
  );
}

// Remove item from cart
function remove_from_cart($product_id) {
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return;
    }
  }
}

// Update quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] = $new_quantity;
      return;
    }
  }
}

// Display cart contents
function display_cart() {
  echo "<h2>Cart Contents:</h2>";
  foreach ($_SESSION['cart'] as $item) {
    echo "$" . $item['id'] . " x " . $item['quantity'];
  }
}
?>


<?php
// Include the cart.php file
include 'cart.php';

// Add item to cart
add_to_cart(1, 2);

// Display cart contents
display_cart();

// Update quantity of an item in the cart
update_quantity(1, 3);

// Remove item from cart
remove_from_cart(1);
?>


session_start();


class Cart {
    public $cart;

    function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = $_SESSION['cart'];
    }

    // Add item to the cart
    function add_item($item_id, $quantity) {
        if (array_key_exists($item_id, $this->cart)) {
            // Increase quantity of existing item
            $this->cart[$item_id] += $quantity;
        } else {
            // New item added
            $this->cart[$item_id] = $quantity;
        }
    }

    // Remove item from the cart
    function remove_item($item_id) {
        if (array_key_exists($item_id, $this->cart)) {
            unset($this->cart[$item_id]);
        }
    }

    // Update quantity of an item in the cart
    function update_quantity($item_id, $new_quantity) {
        if (array_key_exists($item_id, $this->cart)) {
            $this->cart[$item_id] = $new_quantity;
        }
    }

    // Get items from the cart
    function get_items() {
        return $this->cart;
    }

    // Clear the entire cart
    function clear_cart() {
        $_SESSION['cart'] = array();
        $this->cart = $_SESSION['cart'];
    }

}


// Start a new session if not already started
session_start();

// Initialize Cart instance
$cart = new Cart();

// Add an item to the cart (e.g., when adding something to the cart)
$cart->add_item(1, 2); // Item ID: 1, Quantity: 2

// View all items in the cart
print_r($cart->get_items());

// Update quantity of an existing item
$cart->update_quantity(1, 3);

// Remove an item from the cart
$cart->remove_item(1);

// Clear the entire cart
$cart->clear_cart();


<?php
session_start();
?>


function add_item_to_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = array();
    }
    if (!in_array($product_id, $cart)) {
        $cart[] = $product_id;
    }
    $_SESSION['cart'] = $cart;
}

function remove_item_from_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $key = array_search($product_id, $cart);
        if ($key !== false) {
            unset($cart[$key]);
        }
        $_SESSION['cart'] = array_values($cart); // reset keys
    }
}

function display_cart() {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        echo "Your Cart Contents:
";
        foreach ($cart as $product_id) {
            echo "- Product ID: $product_id
";
        }
    } else {
        echo "Your cart is empty!
";
    }
}


if (isset($_POST['add'])) {
    add_item_to_cart($_POST['product_id']);
} elseif (isset($_POST['remove'])) {
    remove_item_from_cart($_POST['product_id']);
}

?>

<form action="" method="post">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <input type="submit" name="add" value="Add to Cart">
</form>

<form action="" method="post">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <input type="submit" name="remove" value="Remove from Cart">
</form>

<?php display_cart(); ?>


<?php
session_start();

function add_item_to_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = array();
    }
    if (!in_array($product_id, $cart)) {
        $cart[] = $product_id;
    }
    $_SESSION['cart'] = $cart;
}

function remove_item_from_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $key = array_search($product_id, $cart);
        if ($key !== false) {
            unset($cart[$key]);
        }
        $_SESSION['cart'] = array_values($cart); // reset keys
    }
}

function display_cart() {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        echo "Your Cart Contents:
";
        foreach ($cart as $product_id) {
            echo "- Product ID: $product_id
";
        }
    } else {
        echo "Your cart is empty!
";
    }
}

if (isset($_POST['add'])) {
    add_item_to_cart($_POST['product_id']);
} elseif (isset($_POST['remove'])) {
    remove_item_from_cart($_POST['product_id']);
}

?>

<form action="" method="post">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <input type="submit" name="add" value="Add to Cart">
</form>

<form action="" method="post">
    <label>Product ID:</label>
    <input type="text" name="product_id"><br><br>
    <input type="submit" name="remove" value="Remove from Cart">
</form>

<?php display_cart(); ?>


session_start();


<?php

// Define cart variables
$cart = array();

function getCart() {
    global $cart;
    return $cart;
}

function setCart($product_id, $quantity) {
    global $cart;
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = array('quantity' => 0);
    }
    $cart[$product_id]['quantity'] += $quantity;
}

function addProductToCart($product_id, $quantity) {
    setCart($product_id, $quantity);
}

function removeProductFromCart($product_id) {
    global $cart;
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}

function updateQuantityInCart($product_id, $new_quantity) {
    global $cart;
    setCart($product_id, $new_quantity);
}

?>


// Set cart data to session
$_SESSION['cart'] = getCart();


// Get cart data from session
$cart_data = $_SESSION['cart'];
print_r($cart_data);


<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
);


function add_item_to_cart($item_id, $quantity) {
    global $cart;
    
    // Check if the item is already in the cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }
    
    // Add new item to cart if not present
    if (!isset($cart['items'][$item_id])) {
        $cart['items'][$item_id] = array(
            'id' => $item_id,
            'price' => 10.99, // Example price
            'quantity' => $quantity
        );
        
        // Update subtotal and total prices
        $cart['subtotal'] += $cart['items'][$item_id]['price'] * $cart['items'][$item_id]['quantity'];
        $cart['total'] = $cart['subtotal'] + ($cart['subtotal'] * 0.08); // 8% tax
    }
}


add_item_to_cart(1, 2); // Add item with ID 1 and quantity 2

// Update session data
$_SESSION['cart'] = $cart;


if (isset($_SESSION['cart'])) {
    echo "Cart Contents:
";
    
    foreach ($_SESSION['cart']['items'] as $item) {
        echo "$item[id] x $item[quantity] = $" . number_format($item['price'] * $item['quantity'], 2) . "
";
    }
    
    echo "Subtotal: $" . number_format($_SESSION['cart']['subtotal'], 2) . "
";
    echo "Tax (8%): $" . number_format($_SESSION['cart']['tax'], 2) . "
";
    echo "Total: $" . number_format($_SESSION['cart']['total'], 2) . "
";
}


<?php
session_start();

$cart = array(
    'items' => array(),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0
);

function add_item_to_cart($item_id, $quantity) {
    global $cart;
    
    // Check if the item is already in the cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }
    
    // Add new item to cart if not present
    if (!isset($cart['items'][$item_id])) {
        $cart['items'][$item_id] = array(
            'id' => $item_id,
            'price' => 10.99, // Example price
            'quantity' => $quantity
        );
        
        // Update subtotal and total prices
        $cart['subtotal'] += $cart['items'][$item_id]['price'] * $cart['items'][$item_id]['quantity'];
        $cart['total'] = $cart['subtotal'] + ($cart['subtotal'] * 0.08); // 8% tax
    }
}

add_item_to_cart(1, 2); // Add item with ID 1 and quantity 2

// Update session data
$_SESSION['cart'] = $cart;

if (isset($_SESSION['cart'])) {
    echo "Cart Contents:
";
    
    foreach ($_SESSION['cart']['items'] as $item) {
        echo "$item[id] x $item[quantity] = $" . number_format($item['price'] * $item['quantity'], 2) . "
";
    }
    
    echo "Subtotal: $" . number_format($_SESSION['cart']['subtotal'], 2) . "
";
    echo "Tax (8%): $" . number_format($_SESSION['cart']['tax'], 2) . "
";
    echo "Total: $" . number_format($_SESSION['cart']['total'], 2) . "
";
}
?>


<?php
// If the cart is empty, initialize it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Update quantity of item in cart
function update_cart() {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // If product is already in the cart, update its quantity
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = max(1, min($quantity, 100)); // Limit quantity to 1-100
                break;
            }
        }

        // If product is not in the cart, add it
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => max(1, min($quantity, 100)));
        }
    }
}

// Display cart contents
function display_cart() {
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<p>Product ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '</p>';
    }
}

// Add product to cart
function add_to_cart() {
    if (isset($_POST['product_id'])) {
        $_SESSION['cart'][] = array('id' => $_POST['product_id'], 'quantity' => 1);
    }
}

// Remove item from cart
function remove_from_cart() {
    if (isset($_GET['remove'])) {
        $product_id = $_GET['remove'];
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }
}

// Update session cart
if (isset($_POST['update_cart'])) {
    update_cart();
} elseif (isset($_GET['remove'])) {
    remove_from_cart();
} else {
    add_to_cart();
}

// Display cart contents
display_cart();

?>


<?php
require 'cart.php';

?>

<!-- Form to add product to cart -->
<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="1">
    <button type="submit">Add Product 1 to Cart</button>
</form>

<!-- Form to update quantity in cart -->
<form action="cart.php" method="post">
    <input type="hidden" name="update_cart" value="true">
    <input type="text" name="product_id" value="1">
    <input type="number" name="quantity" value="2">
    <button type="submit">Update Quantity in Cart</button>
</form>

<!-- Link to remove product from cart -->
<a href="?remove=1">Remove Product 1 from Cart</a>


<?php
// Initialize the session
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, create a new empty array
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id) {
    global $cart;
    
    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity of that item
            $item['quantity'] += 1;
            return true; // Item already in cart, no need to add again
        }
    }
    
    // If not, add a new item to the cart with a quantity of 1
    $cart[] = array('id' => $product_id, 'quantity' => 1);
    return false;
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $cart;
    
    // Remove the item by id from the cart
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return true; // Item removed successfully
        }
    }
    return false; // Item not found in cart
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    global $cart;
    
    // Find the item by id and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return true; // Quantity updated successfully
        }
    }
    return false; // Item not found in cart
}

// Add a product to the cart (example usage)
add_to_cart(1);

// Print out the contents of the cart
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

?>


add_to_cart(1); // add a new item to the cart with ID 1
remove_from_cart(2); // remove an item from the cart with ID 2
update_cart_quantity(1, 3); // update the quantity of an item in the cart with ID 1 to 3


<?php
session_start();
?>


function add_to_cart($product_id) {
    // Initialize cart array if it does not exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Update or add to existing product in the cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}


function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // If the product was the last item in its ID, we can remove that empty index to clean up.
        $_SESSION['cart'] = array_filter($_SESSION['cart']);
    }
}


function get_total_cost() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Assuming you have access to product prices somehow, this is a placeholder.
            $price = get_product_price($product_id);
            $total += $price * $quantity;
        }
    }
    
    return $total;
}


<?php
session_start();

// Example products
$product_ids = ['P01', 'P02'];

function add_to_cart($product_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        $_SESSION['cart'] = array_filter($_SESSION['cart']);
    }
}

function get_product_price($product_id) { // Placeholder, actual function should return product price
    switch ($product_id) {
        case 'P01':
            return 10.99;
        case 'P02':
            return 5.99;
        default:
            return 0; // Invalid product ID
    }
}

// Add items to cart
foreach ($product_ids as $id) {
    add_to_cart($id);
}

// Remove an item from the cart
remove_from_cart('P01');

echo 'Cart Contents: ';
print_r($_SESSION['cart']);

echo "
Total Cost: " . get_total_cost();
?>


<?php
session_start();
?>


function add_to_cart($item_id) {
    // Check if the cart array exists in the session; if not, create it.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add item to the cart with a quantity of 1 unless specified otherwise
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id] += 1;
    } else {
        $_SESSION['cart'][$item_id] = 1;
    }
}


function remove_from_cart($item_id) {
    if (isset($_SESSION['cart']) && array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
        
        // If the session variable is now empty, reset it.
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}


function view_cart() {
    $contents = $_SESSION['cart'];
    
    // Example output for debugging purposes.
    echo '<pre>';
    print_r($contents);
    echo '</pre>';
}

// Usage:
view_cart();


function checkout() {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}


$_SESSION['cart'] = array();


function add_to_cart($item_id, $quantity = 1) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $item_id) {
            // Update the quantity of the existing item
            $cart_item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add the new item to the cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'name' => '', // assuming we'll get this from a database or API
        'price' => 0, // assuming we'll get this from a database or API
        'quantity' => $quantity
    );
}


function update_cart_item($item_id, $new_quantity) {
    global $_SESSION;
    
    // Find the item to be updated
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $item_id) {
            $cart_item['quantity'] = $new_quantity;
            return;
        }
    }
}


function remove_from_cart($item_id) {
    global $_SESSION;
    
    // Find the item to be removed
    foreach ($_SESSION['cart'] as $key => &$cart_item) {
        if ($cart_item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function get_cart_contents() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}


<?php
session_start();

// Initialize the cart if it doesn't exist in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add items to cart
function addToCart($id, $name, $price, $quantity) {
    global $_SESSION;
    // Check if item already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $id) {
            // If it does, increase its quantity
            $item['quantity'] += $quantity;
            return;  // Item was found and updated, no need to continue.
        }
    }
    
    // If the item is not in the cart, add it
    $_SESSION['cart'][] = array('product_id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $quantity);
}

// Function to remove items from cart
function removeFromCart($id) {
    global $_SESSION;
    // Find the index of the product in the cart and remove it if found
    foreach (array_keys($_SESSION['cart']) as $key) {
        if ($_SESSION['cart'][$key]['product_id'] == $id) {
            unset($_SESSION['cart'][$key]);
            break;  // Found and removed, no need to continue checking.
        }
    }
}

// Function to update quantity of items in cart
function updateQuantity($id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $id && $item['quantity'] != $new_quantity) {
            $item['quantity'] = $new_quantity;  // Update quantity
            break;  // Found and updated, no need to continue.
        }
    }
}

// Function to display cart contents
function displayCart() {
    global $_SESSION;
    echo "<h2>Shopping Cart:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product: " . $item['name'] . " | Price: $" . number_format($item['price'], 2) . " | Quantity: " . $item['quantity'];
        if ($item['quantity'] > 1) {
            echo "s";
        }
        echo "<br>";
    }
}

// Example usage
if (isset($_POST['add'])) {
    addToCart($_POST['id'], $_POST['name'], $_POST['price'], $_POST['quantity']);
}
?>

<form method="post">
    <label>Product ID:</label>
    <input type="text" name="id"><br><br>
    <label>Name:</label>
    <input type="text" name="name"><br><br>
    <label>Price:</label>
    <input type="number" step=".01" value="" name="price"><br><br>
    <label>Quantity:</label>
    <input type="number" min="1" value="" name="quantity"><br><br>
    <input type="submit" name="add" value="Add to Cart">
</form>

<button onclick="window.location.href='?remove_id=<?php echo $_POST['id']; ?>'">Remove from Cart</button>
<?php // For removing items directly from cart without form submission
if (isset($_GET['remove_id'])) {
    removeFromCart($_GET['remove_id']);
}
?>

<input type="button" value="Display Cart" onclick="window.location.href='?display_cart=true'">
<?php 
// Display cart on button click, redirect to same page with query string.
if (isset($_GET['display_cart']) && $_GET['display_cart'] == 'true') {
    displayCart();
}
?>


<?php
session_start();

// Check if the cart session already exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($itemId, $itemName, $itemPrice) {
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['itemId'] == $itemId) {
            $_SESSION['cart'][$key]['quantity'] += 1;
            return;
        }
    }

    // Add new item to cart
    $newItem = array('itemId' => $itemId, 'itemName' => $itemName, 'itemPrice' => $itemPrice, 'quantity' => 1);
    $_SESSION['cart'][] = $newItem;
}

// Function to remove item from cart
function removeFromCart($itemId) {
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['itemId'] == $itemId) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update quantity of item in cart
function updateQuantity($itemId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$value) {
        if ($value['itemId'] == $itemId) {
            $value['quantity'] = $newQuantity;
            break;
        }
    }
}

// Example usage:
addToCart(1, 'Item 1', 10.99);
addToCart(2, 'Item 2', 5.99);

print_r($_SESSION['cart']);

removeFromCart(1);

updateQuantity(2, 3);

print_r($_SESSION['cart']);
?>


addToCart(1, 'Item 1', 10.99);


removeFromCart(1);


updateQuantity(2, 3);


print_r($_SESSION['cart']);


// cart.php

session_start();

// ... (functions and logic come next)


function add_item($product_id, $quantity, $price = null) {
    // Check if cart is already set in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Get existing items from the session or initialize an empty array
    $items = $_SESSION['cart'];

    // Check if product already exists in the cart
    foreach ($items as &$item) {
        if ($item['product_id'] == $product_id) {
            // Update quantity if it's higher than the current one
            if ($quantity > $item['quantity']) {
                $item['quantity'] = $quantity;
            }
            return; // Item already exists, no need to add another instance
        }
    }

    // Add new item or update existing quantity
    $items[] = array(
        'product_id' => $product_id,
        'quantity' => $quantity,
        'price' => isset($price) ? $price : 0 // Set default price if not provided
    );

    // Update session with the updated cart items
    $_SESSION['cart'] = $items;
}


function remove_item($product_id) {
    if (isset($_SESSION['cart'])) {
        // Remove first occurrence of the product in the array
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['product_id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                return;
            }
        }

        // If no match found, do nothing
    }
}


function view_cart() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return array();
    }
}


// example.php
require_once 'cart.php';

// Add item to cart
add_item(1, 2); // Product ID 1 with quantity 2

// Remove item from cart
remove_item(1);

// View current cart items
print_r(view_cart());


class Cart {
  private $cart;

  public function __construct() {
    // Initialize the cart as an empty array
    $this->cart = array();
  }

  public function add_item($product_id, $quantity) {
    // Check if product is already in cart
    if (isset($this->cart[$product_id])) {
      // If it is, increment quantity
      $this->cart[$product_id]['quantity'] += $quantity;
    } else {
      // If not, add to cart with initial quantity
      $this->cart[$product_id] = array('id' => $product_id, 'quantity' => $quantity);
    }
  }

  public function remove_item($product_id) {
    // Remove product from cart
    if (isset($this->cart[$product_id])) {
      unset($this->cart[$product_id]);
    }
  }

  public function update_quantity($product_id, $new_quantity) {
    // Update quantity of product in cart
    if (isset($this->cart[$product_id])) {
      $this->cart[$product_id]['quantity'] = $new_quantity;
    }
  }

  public function get_cart() {
    // Return the entire cart array
    return $this->cart;
  }
}


class Session {
  private $session;

  public function __construct() {
    // Initialize session
    session_start();
  }

  public function set_cart($cart) {
    // Set the cart in the session
    $_SESSION['cart'] = $cart;
  }

  public function get_cart() {
    // Return the cart from the session
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
  }
}


// Create a new Cart instance
$cart = new Cart();

// Add some items to the cart
$cart->add_item(1, 2);
$cart->add_item(2, 3);

// Create a new Session instance
$session = new Session();

// Set the cart in the session
$session->set_cart($cart->get_cart());

// Get the cart from the session (should match what we added earlier)
print_r($session->get_cart());


<?php
session_start();

// Session Settings (Change these as per your need)
$_SESSION['cart'] = array();
?>


<?php
include 'config.php';

// Function to add item to cart
function addItemToCart($product_id, $quantity = 1) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        // If product is already in the cart, increment its quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Add product to the cart with quantity
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // If the product has been completely removed, we can unset the entire 'cart' key if it's empty
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}

// Function to display cart contents
function displayCart() {
    global $_SESSION;
    
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Your Cart is Empty.";
    } else {
        echo "Your Cart Contents:<br>";
        
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Assuming you have a function to get the product name from its ID
            echo "$quantity x Product #{$product_id} | ";
        }
    }
}
?>


<?php
include 'cart.php';
include 'config.php';

// Example adding product to the cart by ID. Replace with your actual logic for adding products.
$productId = 1; // Change this as per your database structure or product ID logic

// Add item to cart
addItemToCart($productId);

// Display cart contents
displayCart();
?>


class CartItem {
    public $product_id;
    public $quantity;
}


// Set the session name
session_name('user_cart');

// Start the session
session_start();

// If the cart is not already set, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_product_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item->product_id == $product_id) {
            // If it is, increment the quantity
            $item->quantity += $quantity;
            return;
        }
    }

    // If not, add a new item to the cart
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity
    );
}


function update_product_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Find the product in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item->product_id == $product_id) {
            $item->quantity = $new_quantity;
            return;
        }
    }

    // If the product is not found, throw an exception
    throw new Exception('Product not found in cart');
}


function remove_product_from_cart($product_id) {
    global $_SESSION;

    // Find the product in the cart and remove it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item->product_id == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }

    // If the product is not found, throw an exception
    throw new Exception('Product not found in cart');
}


function display_cart_contents() {
    global $_SESSION;

    // Loop through each product in the cart and display its details
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item->product_id} ({$item->quantity})";
    }
}


// Initialize the cart session
session_start();

// Add some products to the cart
add_product_to_cart(1, 2);
add_product_to_cart(3, 1);

// Update the quantity of a product in the cart
update_product_quantity(1, 3);

// Display the contents of the cart
display_cart_contents();


<?php
// Start the session
session_start();
?>


// Initialize the cart as an empty array in the session
$_SESSION['cart'] = array();


function add_to_cart($product_id, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $product_id) {
            // Update the quantity if the product already exists
            $product['quantity'] += $quantity;
            return;
        }
    }

    // Add the product to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'name' => '', 'price' => 0.00, 'quantity' => $quantity);
}


function display_cart() {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $product) {
        echo "ID: $product[id] - Name: $product[name] - Price: $" . number_format($product['price'], 2) . " x Quantity: $product[quantity]<br>";
    }
}


<?php

// Start the session
session_start();

// Initialize the cart as an empty array in the session
$_SESSION['cart'] = array();

function add_to_cart($product_id, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $product_id) {
            // Update the quantity if the product already exists
            $product['quantity'] += $quantity;
            return;
        }
    }

    // Add the product to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'name' => '', 'price' => 0.00, 'quantity' => $quantity);
}

function display_cart() {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $product) {
        echo "ID: $product[id] - Name: $product[name] - Price: $" . number_format($product['price'], 2) . " x Quantity: $product[quantity]<br>";
    }
}

// Example usage:
add_to_cart(1, 2);
add_to_cart(3, 1);

display_cart();

?>


class Cart {
    private $session;

    public function __construct() {
        // Initialize the session
        $this->session = $_SESSION;
    }

    public function addProduct($product_id, $quantity) {
        // Check if the product is already in the cart
        if (isset($this->session['cart'][$product_id])) {
            // If it is, increment the quantity
            $this->session['cart'][$product_id] += $quantity;
        } else {
            // If not, add it to the cart with the specified quantity
            $this->session['cart'][$product_id] = $quantity;
        }
    }

    public function removeProduct($product_id) {
        // Check if the product is in the cart
        if (isset($this->session['cart'][$product_id])) {
            // If it is, unset it from the cart
            unset($this->session['cart'][$product_id]);
        }
    }

    public function getProducts() {
        // Return the products in the cart
        return $this->session['cart'];
    }

    public function getTotal() {
        // Calculate the total cost of the products in the cart
        $total = 0;
        foreach ($this->session['cart'] as $product_id => $quantity) {
            $price = getPrice($product_id); // assume this function exists
            $total += $price * $quantity;
        }
        return $total;
    }
}


<?php
session_start();

// Include the Cart class
require_once 'cart.php';

// Create a new instance of the Cart class
$cart = new Cart();


$cart->addProduct(123, 2); // adds 2 of product with id 123


$cart->removeProduct(123);


$products = $cart->getProducts();
print_r($products); // outputs array containing product ids as keys


$total = $cart->getTotal();
echo "Total: $" . number_format($total);


<?php
session_start();

// If the cart is empty, set it to an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($product_id) {
    global $cart;
    if (in_array($product_id, $_SESSION['cart'])) {
        // If product is already in cart, increment its quantity
        $index = array_search($product_id, $_SESSION['cart']);
        $_SESSION['cart'][$index]++;
    } else {
        // Add new product to cart with quantity 1
        $_SESSION['cart'][] = $product_id;
    }
}

// Remove item from cart
function remove_item_from_cart($product_id) {
    global $cart;
    if (in_array($product_id, $_SESSION['cart'])) {
        // If product is in cart, remove it
        $index = array_search($product_id, $_SESSION['cart']);
        unset($_SESSION['cart'][$index]);
        // Re-index the cart to maintain correct order
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Update item quantity in cart
function update_item_quantity($product_id, $new_quantity) {
    global $cart;
    if (in_array($product_id, $_SESSION['cart'])) {
        // If product is in cart, update its quantity
        $index = array_search($product_id, $_SESSION['cart']);
        $_SESSION['cart'][$index] = $new_quantity;
    }
}

// Display the contents of the cart
function display_cart() {
    global $cart;
    echo "Your cart contains:<br>";
    foreach ($_SESSION['cart'] as $item) {
        // Assuming product ID is stored in a database or elsewhere
        $product_name = get_product_name($item);
        echo "$product_name (x" . count(array_filter($_SESSION['cart'], function($i) use ($item) { return $i == $item; })) . ")
";
    }
}

// Helper function to retrieve product name by ID
function get_product_name($product_id) {
    // Replace with your actual database or data storage retrieval logic
    return "Product Name " . rand(1, 100);
}

// Test the functions
$cart = array();
add_item_to_cart("P123");
add_item_to_cart("P456");
remove_item_from_cart("P123");
update_item_quantity("P456", 3);

display_cart();

?>


// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add items to the cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as $i => &$item) {
        if ($item['id'] == $item_id) {
            // If it's already there, update the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If it's not in the cart, add it
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}

// Remove items from the cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $i => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$i]);
            return;
        }
    }
}

// Update the quantity of an item in the cart
function update_quantity($item_id, $quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Display the cart
function display_cart() {
    global $_SESSION;
    
    echo '<h2>Cart:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo '<p>ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '</p>';
    }
}


// Start the session
session_start();

// Add items to the cart
add_item_to_cart(1, 2);
add_item_to_cart(3, 4);

// Update the quantity of an item in the cart
update_quantity(1, 5);

// Remove an item from the cart
remove_item_from_cart(3);

// Display the cart
display_cart();


<?php
session_start();

// Check if the cart is already initialized
if (!isset($_SESSION['cart'])) {
    // Initialize an empty cart array
    $_SESSION['cart'] = array();
}
?>


function addToCart($productId, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // Increase the quantity of the existing item
            $item['quantity'] += $quantity;
            return; // Exit function early
        }
    }

    // Add a new item to the cart
    $_SESSION['cart'][] = array('id' => $productId, 'quantity' => $quantity);
}


function removeFromCart($productId) {
    // Find the index of the item to be removed
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$index]);
            return; // Exit function early
        }
    }

    // If the product is not found, do nothing
}


function updateCartItem($productId, $newQuantity) {
    // Find the index of the item to be updated
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity;
            return; // Exit function early
        }
    }

    // If the product is not found, do nothing
}


// Get the cart items
$cartItems = $_SESSION['cart'];

// Loop through the cart items
foreach ($cartItems as $item) {
    // Display the product details
    echo "Product ID: " . $item['id'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br><br>";
}


addToCart(1, 2); // Add product ID 1 with quantity 2


removeFromCart(1);


updateCartItem(1, 3); // Update product ID 1 to have a quantity of 3


// Check if the cart session exists, and if not, initialize it
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
  // Check if the product is already in the cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // If it's already in the cart, increment its quantity
      $item['quantity'] += $quantity;
      return; // exit function early
    }
  }

  // If not, add a new item to the cart
  $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return; // exit function early
    }
  }
}

// Function to update the quantity of an item in the cart
function update_cart_item($product_id, $new_quantity) {
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] = $new_quantity;
      return; // exit function early
    }
  }
}

// Function to get the total cost of items in the cart
function get_cart_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $price = get_product_price($item['id']); // replace with your own function to retrieve product price
    $total += $price * $item['quantity'];
  }
  return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
  add_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
  remove_from_cart($_POST['product_id']);
} elseif (isset($_POST['update_quantity'])) {
  update_cart_item($_POST['product_id'], $_POST['new_quantity']);
}

// Display the current cart contents
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

// Display the total cost of items in the cart
echo 'Total: $' . get_cart_total();


// Create a new session or resume an existing one
session_start();

// Function to add item to cart
function addToCart($itemId, $itemName, $itemPrice) {
  // Check if cart is already set in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add item to cart array with quantity = 1
  $cartItem = array('id' => $itemId, 'name' => $itemName, 'price' => $itemPrice, 'quantity' => 1);
  $_SESSION['cart'][] = $cartItem;
}

// Function to remove item from cart
function removeFromCart($itemId) {
  // Check if cart is set in session
  if (isset($_SESSION['cart'])) {
    // Remove item by id
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $itemId) {
        unset($_SESSION['cart'][$key]);
      }
    }
  }
}

// Function to update quantity of an item in cart
function updateQuantity($itemId, $newQuantity) {
  // Check if cart is set in session
  if (isset($_SESSION['cart'])) {
    // Find item by id and update quantity
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $itemId) {
        $item['quantity'] = $newQuantity;
      }
    }
  }
}

// Function to display cart contents
function displayCart() {
  // Check if cart is set in session
  if (isset($_SESSION['cart'])) {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
      echo "ID: " . $item['id'] . ", Name: " . $item['name'] . ", Price: $" . $item['price'] . ", Quantity: " . $item['quantity'];
      echo "<br>";
    }
  } else {
    echo "Cart is empty.";
  }
}

// Example usage:
addToCart(1, 'Product A', 10.99);
addToCart(2, 'Product B', 9.99);

displayCart();

removeFromCart(1);

updateQuantity(2, 3);

displayCart();


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id, $quantity) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If the product is already in the cart, increment its quantity.
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Otherwise, add it to the cart with the specified quantity.
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


function remove_from_cart($product_id) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If the product is in the cart, unset it.
        unset($_SESSION['cart'][$product_id]);
    }
}


// Add 2 items of product ID 1 to the cart.
add_to_cart(1, 2);

// Remove all items of product ID 1 from the cart.
remove_from_cart(1);

// Print the contents of the cart.
print_r($_SESSION['cart']);


Array
(
    [1] => 2
)


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function remove_from_cart($product_id) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example usage
add_to_cart(1, 2);
remove_from_cart(1);

print_r($_SESSION['cart']);
?>


<?php session_start(); ?>


<?php
// Assuming we have a form that submits product IDs (example)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get posted data
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];

    // Check if cart already exists in session, or initialize it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add product to cart (assuming ID is unique)
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$productId] = array('name' => $productName, 'quantity' => 1);
    }
}
?>


<?php
if (isset($_SESSION['cart'])) {
    echo "Your Cart:<br>";
    foreach ($_SESSION['cart'] as $productId => $product) {
        echo "$product[name] x $product[quantity]<br>";
    }
}
?>


<?php
// Example of adding a link to remove an item
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $product) {
        echo "<a href='#' class='remove-item' data-id='$productId'>Remove</a><br>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get id of product to remove
    $productId = $_POST['id'];
    unset($_SESSION['cart'][$productId]);
}
?>


class Cart {
  private $cart;

  function __construct() {
    // Initialize empty cart array
    $this->cart = array();
  }

  function add_item($product_id, $quantity) {
    // Check if product is already in cart
    foreach ($this->cart as &$item) {
      if ($item['product_id'] == $product_id) {
        // If it is, increment quantity
        $item['quantity'] += $quantity;
        return; // Do not add duplicate item
      }
    }

    // Add new product to cart
    $this->cart[] = array('product_id' => $product_id, 'quantity' => $quantity);
  }

  function remove_item($product_id) {
    // Remove product from cart
    foreach ($this->cart as &$item) {
      if ($item['product_id'] == $product_id) {
        unset($item); // Remove item from array
        return; // Do not try to delete non-existent item
      }
    }
  }

  function update_quantity($product_id, $new_quantity) {
    // Update quantity of product in cart
    foreach ($this->cart as &$item) {
      if ($item['product_id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        return; // Do not try to update non-existent item
      }
    }
  }

  function get_cart() {
    // Return entire cart array
    return $this->cart;
  }

  function subtotal() {
    // Calculate subtotal of all items in cart
    $subtotal = 0;
    foreach ($this->cart as &$item) {
      $subtotal += $item['quantity'] * $this->get_product_price($item['product_id']);
    }
    return $subtotal;
  }

  private function get_product_price($product_id) {
    // This function would typically retrieve the price of a product from a database
    // For this example, we'll just assume it returns a hardcoded price
    if ($product_id == 1) { return 9.99; }
    if ($product_id == 2) { return 19.99; }
    // Add more products as needed...
  }
}


// Initialize cart session
$cart = new Cart();

// Start session or check if one already exists
if (!isset($_SESSION)) {
  session_start();
}

// Save cart to session
$_SESSION['cart'] = $cart;


$cart->add_item(1, 2); // Add 2 of product with ID 1
$cart->add_item(2, 3); // Add 3 of product with ID 2


$cart->update_quantity(1, 4); // Update quantity of product with ID 1 to 4


$cart->remove_item(2);


echo $cart->subtotal(); // Output: 39.97 (assuming prices are $9.99 and $19.99)


<?php
session_start();
?>


$_SESSION['cart'] = array();


function add_to_cart($item_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $product_info = get_product_info($item_id); // assuming you have a function to retrieve product info

    $cart_item = array(
        'id' => $item_id,
        'name' => $product_info['name'],
        'price' => $product_info['price'],
        'quantity' => $quantity
    );

    $_SESSION['cart'][] = $cart_item;
}


function update_quantity($item_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $item_id) {
            $cart_item['quantity'] = $new_quantity;
            return;
        }
    }
}

function remove_from_cart($item_id) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($cart_item) use ($item_id) {
        return $cart_item['id'] != $item_id;
    });
}


function display_cart() {
    echo "<h2>Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th></tr>";

    foreach ($_SESSION['cart'] as $cart_item) {
        echo "<tr>";
        echo "<td>" . $cart_item['id'] . "</td>";
        echo "<td>" . $cart_item['name'] . "</td>";
        echo "<td>$" . number_format($cart_item['price'], 2) . "</td>";
        echo "<td>" . $cart_item['quantity'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}


<?php
session_start();

$_SESSION['cart'] = array();

function get_product_info($item_id) {
    // assuming you have a database or some other way to retrieve product info
    $products = array(
        1 => array('name' => 'Product 1', 'price' => 19.99),
        2 => array('name' => 'Product 2', 'price' => 9.99)
    );
    return $products[$item_id];
}

function add_to_cart($item_id, $quantity) {
    // ...
}

function update_quantity($item_id, $new_quantity) {
    // ...
}

function remove_from_cart($item_id) {
    // ...
}

function display_cart() {
    // ...
}

// Example usage
add_to_cart(1, 2);
display_cart();

?>


<?php
session_start();
?>


$cart_key = 'cart';
$cart = $_SESSION[$cart_key] ?? [];


function addToCart($item_id, $item_name, $price) {
    global $cart_key, $cart;
    
    // Check if the item is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Update the quantity
            $item['quantity']++;
            return;
        }
    }

    // Add a new item to the cart
    $cart[] = [
        'id' => $item_id,
        'name' => $item_name,
        'price' => $price,
        'quantity' => 1
    ];
}


$_SESSION[$cart_key] = $cart;


function removeFromCart($item_id) {
    global $cart_key, $cart;
    
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($cart[$key]);
            break;
        }
    }

    $_SESSION[$cart_key] = $cart;
}


function getCartContents() {
    global $cart_key, $cart;
    
    return $_SESSION[$cart_key];
}


addToCart(1, 'Product 1', 9.99);
addToCart(2, 'Product 2', 19.99);

$cart_contents = getCartContents();
print_r($cart_contents);


Array
(
    [0] => Array
        (
            [id] => 1
            [name] => Product 1
            [price] => 9.99
            [quantity] => 1
        )

    [1] => Array
        (
            [id] => 2
            [name] => Product 2
            [price] => 19.99
            [quantity] => 1
        )
)


$_SESSION[$cart_key] = [];


<?php

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $db; // assuming you have a database connection established

    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // item already exists, update quantity
        }
    }

    // Product not in cart, add new item
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity,
        'price' => get_product_price($product_id) // assuming you have a function to retrieve product price
    );
}

// Function to update quantity of item in cart
function update_cart_item_quantity($product_id, $new_quantity) {
    global $db; // assuming you have a database connection established

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break; // found item, update quantity
        }
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $db; // assuming you have a database connection established

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break; // found item, remove from cart
        }
    }
}

// Function to calculate total cost of items in cart
function calculate_cart_total() {
    global $db; // assuming you have a database connection established

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $price = get_product_price($item['product_id']);
        $total += $price * $item['quantity'];
    }
    return $total;
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_item_to_cart($product_id, $quantity);
}

?>


<?php include 'cart.php'; ?>

<!-- Display cart contents -->
<ul>
    <?php foreach ($_SESSION['cart'] as $item) { ?>
        <li>
            Product: <?= $item['product_id'] ?>
            Quantity: <?= $item['quantity'] ?>
            Price: <?= get_product_price($item['product_id']) ?>
            Total: <?= $item['price'] * $item['quantity'] ?>
        </li>
    <?php } ?>
</ul>

<!-- Update cart quantities -->
<form action="cart.php" method="post">
    <input type="hidden" name="add_to_cart" value="1">
    <select name="product_id">
        <!-- populate select with product IDs -->
    </select>
    <input type="number" name="quantity">
    <button type="submit">Add to Cart</button>
</form>

<!-- Remove item from cart -->
<form action="cart.php" method="post">
    <input type="hidden" name="remove_from_cart" value="1">
    <select name="product_id">
        <!-- populate select with product IDs -->
    </select>
    <button type="submit">Remove From Cart</button>
</form>

<!-- Calculate total cost of items in cart -->
<p>Total: <?= calculate_cart_total() ?></p>


<?php
session_start();

// Check if the cart is empty, create it if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
session_start();

// Get the product ID and quantity from the request
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Validate input
if (empty($product_id) || empty($quantity)) {
    echo 'Invalid input';
    exit;
}

// Add the product to the cart array
$_SESSION['cart'][$product_id] = $quantity;

// Print a success message
echo 'Product added to cart!';
?>


<?php
session_start();

// Get the cart contents from the session
$cart_contents = $_SESSION['cart'];

// Display the cart contents
echo 'Cart Contents:';
foreach ($cart_contents as $product_id => $quantity) {
    echo '<br>Product ID: ' . $product_id . ', Quantity: ' . $quantity;
}
?>


<?php
session_start();

// Get the product ID to be removed
$product_id = $_POST['product_id'];

// Validate input
if (empty($product_id)) {
    echo 'Invalid input';
    exit;
}

// Remove the product from the cart array
unset($_SESSION['cart'][$product_id]);

// Print a success message
echo 'Product removed from cart!';
?>


<?php
session_start();
?>


<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
function addToCart($productID, $quantity) {
    global $_SESSION;
    
    // Increment quantity of existing product in cart
    if (array_key_exists($productID, $_SESSION['cart'])) {
        $_SESSION['cart'][$productID]['quantity'] += $quantity;
        
        // Update the cart session
        $_SESSION['cart'][$productID] = array('price' => 12.99, 'name' => 'Product Name', 'quantity' => $_SESSION['cart'][$productID]['quantity']); // This is a placeholder for product data, adjust accordingly.
    } else {
        // Add new item to cart
        $_SESSION['cart'][$productID] = array('price' => 12.99, 'name' => 'Product Name', 'quantity' => $quantity); // Placeholder data for demonstration purposes only.
    }
    
    // Calculate and update total if necessary
    calculateTotal();
}

// Example usage: 
addToCart(1, 2);
?>


<?php
function calculateTotal() {
    global $_SESSION;
    
    $total = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $price = $item['price'];
        $quantity = $item['quantity'];
        
        // Basic calculation for demonstration, in real-world use you'll likely fetch these from a database
        $total += $price * $quantity;
    }
    
    $_SESSION['cart']['total'] = $total; // Store total in session
    
    echo "Your current cart value is: $" . $_SESSION['cart']['total'];
}

// You can call this function on every page load where you want to display the current total.
?>


<?php
function emptyCart() {
    global $_SESSION;
    
    unset($_SESSION['cart']);
}
?>


session_start();


// Check if 'cart' exists in the session. If not, create an empty array.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function addToCart($id, $quantity) {
    // Retrieve product details from your database.
    // Here, I assume a function called `getProductInfo` exists for simplicity.
    
    // Check if the item is already in the cart to update its quantity or add it
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] += $quantity;
            return;  // Item found, so we can exit.
        }
    }

    // If not in the cart, add it
    $product = getProductInfo($id);  // This is a placeholder. Implement your database query here.
    
    $_SESSION['cart'][] = array('id' => $id, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity);
}

// Example usage
addToCart(1, 2); // Adds product with ID 1 in a quantity of 2 to the cart.


function displayCart() {
    echo "<h2>Your Cart</h2>";
    if (!empty($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            echo "Product Name: " . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price per item: $" . number_format($item['price'], 2) . " - Subtotal for this item: $" . number_format($subtotal, 2) . "<br>";
        }
        echo "Total: $" . number_format($total, 2) . "<hr>";
    } else {
        echo "Your cart is empty.";
    }
}

displayCart();


<?php
// Start session management
session_start();
?>


class Cart {
    private $cart;
    private $items;

    public function __construct() {
        // Initialize the cart as an empty array
        $this->cart = array();
    }

    public function addItem($item, $quantity) {
        // Add the item to the cart with a default quantity of 1
        if (!isset($this->cart[$item])) {
            $this->cart[$item] = array('quantity' => $quantity);
        } else {
            $this->cart[$item]['quantity'] += $quantity;
        }
    }

    public function removeItem($item) {
        // Remove the item from the cart
        if (isset($this->cart[$item])) {
            unset($this->cart[$item]);
        }
    }

    public function updateQuantity($item, $newQuantity) {
        // Update the quantity of an existing item in the cart
        if (isset($this->cart[$item])) {
            $this->cart[$item]['quantity'] = $newQuantity;
        }
    }

    public function getItems() {
        // Return all items in the cart as an array
        return $this->cart;
    }

    public function getTotal() {
        // Calculate and return the total cost of all items in the cart
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['quantity'] * getItemPrice($item['id']);
        }
        return $total;
    }
}


function getItemPrice($itemId) {
    // Query your database for the price of the item with the given ID
    $result = queryDatabase("SELECT price FROM items WHERE id = ?", array($itemId));
    return $result[0]['price'];
}


<?php
// Include the cart file
require_once 'cart.php';

// Create an instance of the Cart class
$cart = new Cart();

// Add some items to the cart
$cart->addItem(1, 2);
$cart->addItem(3, 1);

// Update the quantity of an item in the cart
$cart->updateQuantity(1, 3);

// Remove an item from the cart
$cart->removeItem(3);

// Print out all items in the cart
print_r($cart->getItems());

// Calculate and print out the total cost of all items in the cart
echo $cart->getTotal();
?>


// Initialize session
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    unset($_SESSION['cart'][$product_id]);
}

// Function to update quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    }
}


// Get product details from database
$prod_id = $_GET['id'];
$product_name = // retrieve name from database;
$product_price = // retrieve price from database;

// Add item to cart when "Add to Cart" button is clicked
if (isset($_POST['add_to_cart'])) {
    $quantity = $_POST['quantity'];
    add_item_to_cart($prod_id, $quantity);
}

// Display product details and form for adding to cart
echo '<h2>' . $product_name . '</h2>';
echo 'Price: ' . $product_price;
echo '<form method="post" action="">';
echo '<input type="hidden" name="add_to_cart" value="Add to Cart">';
echo '<label>Quantity:</label>';
echo '<input type="number" name="quantity" value="1">';
echo '<button type="submit">Add to Cart</button>';
echo '</form>';


// Get cart contents from session
$cart = $_SESSION['cart'];

// Display each item in the cart with its quantity and price
if (!empty($cart)) {
    echo '<h2>Cart Contents</h2>';
    foreach ($cart as $product_id => $quantity) {
        // Retrieve product details from database for display
        $product_name = // retrieve name from database;
        $product_price = // retrieve price from database;

        echo '<p>' . $product_name . ' x' . $quantity . '</p>';
    }
} else {
    echo '<h2>Your cart is empty!</h2>';
}


// ...

echo '<p>' . $product_name . ' x' . $quantity . '</p>';
echo '<a href="#" onclick="removeItem(\'' . $product_id . '\')">Remove</a>';

function removeItem($product_id) {
    global $_SESSION;
    remove_item_from_cart($product_id);
}


// ...

echo '<p>' . $product_name . ' x' . $quantity . '</p>';
echo '<form method="post" action="">';
echo '<input type="hidden" name="update_quantity" value="Update Quantity">';
echo '<label>Quantity:</label>';
echo '<input type="number" name="new_quantity" value="' . $quantity . '">';
echo '<button type="submit">Update</button>';
echo '</form>';

function updateItem($product_id, $new_quantity) {
    global $_SESSION;
    update_quantity($product_id, $new_quantity);
}


<?php
  // Initialize session
  session_start();

  // Check if cart is empty or not set yet
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add product to cart
  if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $item) {
      if ($item['id'] == $product_id) {
        $item['quantity'] += $quantity;
        break;
      }
    }

    // Add new item to cart if not found
    if (!isset($item)) {
      $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    }
  }

  // Display cart contents
  echo '<h2>Cart Contents:</h2>';
  foreach ($_SESSION['cart'] as $item) {
    echo '<p>ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '</p>';
  }

  // Remove item from cart
  if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        break;
      }
    }
  }

  // Update quantity of item in cart
  if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        break;
      }
    }
  }

  // Empty cart
  if (isset($_POST['empty_cart'])) {
    $_SESSION['cart'] = array();
  }
?>


<form action="" method="post">
  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
  <label>Quantity:</label>
  <input type="number" name="quantity">
  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>


<form action="" method="post">
  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
  <button type="submit" name="remove_from_cart">Remove from Cart</button>
</form>


<form action="" method="post">
  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
  <label>Quantity:</label>
  <input type="number" name="quantity">
  <button type="submit" name="update_quantity">Update Quantity</button>
</form>


<form action="" method="post">
  <button type="submit" name="empty_cart">Empty Cart</button>
</form>


<?php
session_start();

// Include database connection
require_once 'db_connection.php';

// Function to add item to cart
function addItemToCart($productId, $quantity = 1) {
    // Retrieve current session ID and product information
    $currentSessionId = session_id();
    $productInfo = getProductInfo($productId);

    if ($productInfo && $currentSessionId) {
        // Check if the product already exists in cart for this user
        $existingItem = getCartItemByProductIdAndSessionId($productId, $currentSessionId);
        
        if ($existingItem) {
            // Update quantity of existing item instead of creating a new one
            updateQuantityOfExistingItem($existingItem['id'], $quantity);
        } else {
            // Add new item to cart
            insertNewItemToCart($productInfo, $currentSessionId, $quantity);
        }
    }
}

// Function to get product information by ID
function getProductInfo($productId) {
    global $mysqli;
    
    $query = "SELECT * FROM products WHERE id = '$productId'";
    $result = mysqli_query($mysqli, $query);

    if ($product = mysqli_fetch_assoc($result)) {
        return $product;
    }
}

// Function to get cart item by product ID and session ID
function getCartItemByProductIdAndSessionId($productId, $session_id) {
    global $mysqli;

    $query = "SELECT * FROM cart_items WHERE product_id = '$productId' AND session_id = '$session_id'";
    $result = mysqli_query($mysqli, $query);

    if ($cartItem = mysqli_fetch_assoc($result)) {
        return $cartItem;
    }
}

// Function to update quantity of existing item in cart
function updateQuantityOfExistingItem($itemId, $quantity) {
    global $mysqli;

    $query = "UPDATE cart_items SET quantity = '$quantity' WHERE id = '$itemId'";
    mysqli_query($mysqli, $query);
}

// Function to insert new item into the cart
function insertNewItemToCart($productInfo, $session_id, $quantity) {
    global $mysqli;

    $query = "INSERT INTO cart_items (product_id, quantity, session_id)
              VALUES ('$productInfo[id]', '$quantity', '$session_id')";
    mysqli_query($mysqli, $query);
}

// Function to display the contents of the shopping cart
function displayCart() {
    global $mysqli;
    
    // Retrieve cart items for current user
    $currentSessionId = session_id();
    $cartItemsQuery = "SELECT ci.* FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.session_id = '$currentSessionId'";
    $result = mysqli_query($mysqli, $cartItemsQuery);

    if ($cartItems = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
        // Display total cost of items in the cart
        displayTotalCostOfCartContents($cartItems);
        
        // Loop through each item and display it with a remove link
        foreach ($cartItems as $item) {
            echo "<div>";
            echo "Product Name: " . $item['name'] . ", Quantity: " . $item['quantity'];
            
            // Display remove link
            echo '<a href="remove_item_from_cart.php?id=' . $item['id'] . '">Remove Item</a>';
            
            echo "</div>";
        }
    } else {
        echo "<p>Cart is empty.</p>";
    }
}

// Function to display total cost of cart contents
function displayTotalCostOfCartContents($cartItems) {
    global $mysqli;
    
    // Calculate total cost by summing up quantity * price for each item in the cart
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['quantity'] * $item['price'];
    }
        
    echo "<p>Total: $" . number_format($total, 2);
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    addItemToCart($_POST['product_id'], $_POST['quantity']);
    
} elseif (isset($_GET['remove_item'])) {
    removeItemFromCart($_GET['id']);
}

displayCart();

?>


<?php
// Initialize the session
session_start();

// Check if the cart is already in session, otherwise initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_item_to_cart($item_id) {
    global $_SESSION;
    // Increment the quantity of the item in the cart if it already exists, otherwise add it with a quantity of 1
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]++;
    } else {
        $_SESSION['cart'][$item_id] = 1;
    }
}

// Function to remove an item from the cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    // If the item exists in the cart, unset it
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to update the quantity of an item in the cart
function update_item_quantity($item_id, $quantity) {
    global $_SESSION;
    // If the item exists in the cart and the quantity is valid (i.e. greater than 0), update its quantity
    if (isset($_SESSION['cart'][$item_id]) && $quantity > 0) {
        $_SESSION['cart'][$item_id] = $quantity;
    }
}

// Function to display the contents of the cart
function display_cart() {
    global $_SESSION;
    // Loop through each item in the cart and echo its details
    foreach ($_SESSION['cart'] as $item_id => $quantity) {
        echo "Item ID: $item_id, Quantity: $quantity<br>";
    }
}

// Add an example item to the cart
add_item_to_cart(1);

// Display the contents of the cart
display_cart();
?>


<?php
require 'cart.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart Example</title>
</head>
<body>

<h1>Shopping Cart</h1>

<form action="cart.php" method="post">
    <input type="hidden" name="item_id" value="2">
    <button type="submit">Add Item 2 to Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="remove_item_id" value="1">
    <button type="submit">Remove Item 1 from Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="text" name="update_quantity" placeholder="Update quantity of item X">
    <input type="hidden" name="update_item_id" value="2">
    <button type="submit">Update Quantity</button>
</form>

<?php
// Handle form submissions
if (isset($_POST['item_id'])) {
    add_item_to_cart($_POST['item_id']);
} elseif (isset($_POST['remove_item_id'])) {
    remove_item_from_cart($_POST['remove_item_id']);
} elseif (isset($_POST['update_quantity']) && isset($_POST['update_item_id'])) {
    update_item_quantity($_POST['update_item_id'], $_POST['update_quantity']);
}
?>

<?php
display_cart();
?>
</body>
</html>


session.save_path = "/tmp"
session.auto_start = 1


<?php

class Cart {
    private $cart;

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = $_SESSION['cart'];
    }

    public function addItem($item, $quantity) {
        if (array_key_exists($item, $this->cart)) {
            $this->cart[$item] += $quantity;
        } else {
            $this->cart[$item] = $quantity;
        }
    }

    public function removeItem($item) {
        unset($this->cart[$item]);
    }

    public function getCart() {
        return $this->cart;
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->cart as $item => $quantity) {
            $total += $quantity * getPrice($item);
        }
        return $total;
    }

    private function getPrice($item) {
        // Assuming a function to get the price of an item
        // Replace with your own implementation
        return 10.99; // Example price for demonstration purposes only
    }
}

?>


<?php

require_once 'cart.php';

$cart = new Cart();

// Add an item to the cart
$cart->addItem('apple', 2);

// Remove an item from the cart
$cart->removeItem('banana');

// Get the total cost of the cart
$total = $cart->getTotal();

echo "Cart Contents: ";
print_r($cart->getCart());
echo "<br>Total: $" . number_format($total, 2);
?>


// Initialize an empty cart array
$cart = array();


// Start the session
session_start();


// Set the cart in the session
$_SESSION['cart'] = $cart;


function add_to_cart($product_id, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $product_id) {
            // Update the quantity of the existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add a new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


function update_quantity($product_id, $new_quantity) {
    // Find the index of the product in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // If the product is not found, throw an exception
    throw new Exception('Product not found in cart');
}


function remove_from_cart($product_id) {
    // Find the index of the product in the cart and remove it
    $index = array_search(array('id' => $product_id), $_SESSION['cart']);
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
    }
}


// Add a product to the cart with quantity 2
add_to_cart(1, 2);

// Update the quantity of the existing item to 3
update_quantity(1, 3);

// Remove the item from the cart
remove_from_cart(1);


<?php
session_start();

// Set default values for cart variables
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Update existing item quantity
        }
    }

    // Add new product to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function update_cart_item($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find the item in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return; // Update existing item quantity
        }
    }
}

function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find the item in the cart and remove it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return; // Remove existing item from cart
        }
    }
}

function get_cart_contents() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}
?>


// Add a product to the cart with quantity 2
add_to_cart(123, 2);

// Update the quantity of an existing item in the cart
update_cart_item(123, 3);

// Remove an item from the cart
remove_from_cart(456);

// Get the contents of the cart
$cart_contents = get_cart_contents();

// Print out the cart contents
echo "Cart Contents:
";
foreach ($cart_contents as $item) {
    echo "ID: {$item['id']} Quantity: {$item['quantity']}
";
}


class Cart {
  private $cart;

  public function __construct() {
    $this->cart = array();
  }

  // Add item to cart
  public function add($item, $quantity) {
    if (!isset($this->cart[$item])) {
      $this->cart[$item] = array('quantity' => $quantity);
    } else {
      $this->cart[$item]['quantity'] += $quantity;
    }
  }

  // Remove item from cart
  public function remove($item) {
    if (isset($this->cart[$item])) {
      unset($this->cart[$item]);
    }
  }

  // Update quantity of item in cart
  public function update($item, $new_quantity) {
    if (isset($this->cart[$item])) {
      $this->cart[$item]['quantity'] = $new_quantity;
    }
  }

  // Get items in cart
  public function get_items() {
    return $this->cart;
  }

  // Get total quantity of all items in cart
  public function get_total_quantity() {
    $total_quantity = 0;
    foreach ($this->cart as $item) {
      $total_quantity += $item['quantity'];
    }
    return $total_quantity;
  }

  // Get subtotal cost of all items in cart
  public function get_subtotal_cost($prices) {
    $subtotal = 0;
    foreach ($this->cart as $item => $info) {
      if (isset($prices[$item])) {
        $subtotal += $prices[$item] * $info['quantity'];
      }
    }
    return $subtotal;
  }

  // Clear cart
  public function clear() {
    $this->cart = array();
  }
}


// Initialize session
session_start();

// Create a new instance of the Cart class
$cart = new Cart();

// Add items to cart (example)
$prices = array(
  'apple' => 1.99,
  'banana' => 0.99,
  'orange' => 2.49
);
$cart->add('apple', 2);
$cart->add('banana', 3);

// Display cart contents
print_r($cart->get_items());

// Update quantity of an item in the cart (example)
$cart->update('apple', 1);

// Display updated cart contents
print_r($cart->get_items());

// Get subtotal cost of all items in the cart (example)
echo "Subtotal: $" . $cart->get_subtotal_cost($prices) . "
";

// Clear cart (example)
$cart->clear();


<?php
session_start();

// Set the cart as an empty array by default
$_SESSION['cart'] = array();

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
  global $_SESSION;
  if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id] += $quantity;
  } else {
    $_SESSION['cart'][$item_id] = $quantity;
  }
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
  global $_SESSION;
  unset($_SESSION['cart'][$item_id]);
}

// Function to update quantity of an item in the cart
function update_quantity_in_cart($item_id, $new_quantity) {
  global $_SESSION;
  if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id] = $new_quantity;
  }
}

// Function to get the contents of the cart
function get_cart_contents() {
  global $_SESSION;
  return $_SESSION['cart'];
}

// Example usage:
add_item_to_cart(1, 2); // Add item with id 1 to cart with quantity 2
add_item_to_cart(2, 3); // Add item with id 2 to cart with quantity 3

// Print the contents of the cart
print_r(get_cart_contents());


<?php
session_start();

// Check if cart session exists, create one if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function addToCart($itemID, $quantity) {
    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemID) {
            // If item exists, update quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If item doesn't exist, add it to the cart
    $_SESSION['cart'][] = array('id' => $itemID, 'quantity' => $quantity);
}

// Example usage:
addToCart(1, 2); // Add 2 items with ID 1
addToCart(3, 1); // Add 1 item with ID 3


function displayCart() {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[id] x $item[quantity]<br>";
    }
}

// Example usage:
displayCart();


function removeFromCart($itemID) {
    // Find index of item to be removed
    $index = null;
    foreach ($_SESSION['cart'] as $i => $item) {
        if ($item['id'] == $itemID) {
            $index = $i;
            break;
        }
    }

    // If item exists, remove it from the cart
    if ($index !== null) {
        unset($_SESSION['cart'][$index]);
    }
}

// Example usage:
removeFromCart(1); // Remove items with ID 1


function updateQuantity($itemID, $newQuantity) {
    // Find index of item to be updated
    $index = null;
    foreach ($_SESSION['cart'] as $i => $item) {
        if ($item['id'] == $itemID) {
            $index = $i;
            break;
        }
    }

    // If item exists, update its quantity
    if ($index !== null) {
        $_SESSION['cart'][$index]['quantity'] = $newQuantity;
    }
}

// Example usage:
updateQuantity(1, 3); // Update quantity of items with ID 1 to 3


<?php
session_start();

// Set session name
$_SESSION['cart_name'] = 'user_cart';

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already exists in cart, update quantity
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'quantity' => $quantity
    );
}

// Example usage:
add_to_cart(1, 2); // Add product with id 1 in quantity of 2


function remove_from_cart($product_id) {
    global $_SESSION;

    // Find and delete item from cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break; // Remove first occurrence of product id
        }
    }
}

// Example usage:
remove_from_cart(1); // Remove product with id 1 from cart


function update_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Find and update item quantity in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break; // Update first occurrence of product id
        }
    }
}

// Example usage:
update_quantity(1, 3); // Update quantity of product with id 1 to 3


function get_cart_contents() {
    global $_SESSION;

    return $_SESSION['cart'];
}

// Example usage:
$cart_contents = get_cart_contents();
print_r($cart_contents);


<?php
// Starting the session
session_start();
?>


// Define an item in the cart as an associative array
function add_item_to_cart($item_id, $item_name, $quantity) {
    // Check if a 'cart' session already exists or initialize it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if the item is already in cart to update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // Update existing item's quantity
            $item['quantity'] += $quantity;
            return; // Exit function early
        }
    }
    
    // Item not found, add it to the cart
    $_SESSION['cart'][] = array('id' => $item_id, 'name' => $item_name, 'quantity' => $quantity);
}

// Remove an item from the cart by ID
function remove_item_from_cart($item_id) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['id'] == $item_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        
        // If the cart is now empty, remove it from session
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}

// Display items in the cart
function display_cart() {
    echo "Your Cart:<br>";
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            echo "ID: $item[id], Name: $item[name], Quantity: $item[quantity]<br>";
        }
    } else {
        echo "Cart is empty.";
    }
}


// Starting the session (assuming already done as per Step 1)
session_start();

// Sample data for adding items
$item_id_1 = 123;
$item_name_1 = "Product A";
$quantity_1 = 2;

add_item_to_cart($item_id_1, $item_name_1, $quantity_1);

$item_id_2 = 456;
$item_name_2 = "Product B";
$quantity_2 = 3;

add_item_to_cart($item_id_2, $item_name_2, $quantity_2);

// Display the cart
display_cart();


<?php

// Start the Session
session_start();

// If there's no 'cart' key in session, create it and initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function update_cart() {
    global $product_name;
    global $quantity;
    global $price;

    // Set product name, quantity, and price for the current item being added to cart
    if (isset($_POST['add_to_cart'])) {
        $product_name = $_POST['product_name'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        // Add to session array if not already there
        if (!in_array($product_name, $_SESSION['cart'])) {
            // If product is not in the cart, add it with quantity
            array_push($_SESSION['cart'], array('name' => $product_name, 'quantity' => $quantity, 'price' => $price));
        } else {
            // If product already exists, update its quantity
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['name'] == $product_name) {
                    $item['quantity'] += $quantity;
                    break;
                }
            }
        }

    }

}

function view_cart() {
    global $_SESSION;

    // Create HTML for displaying cart items
    echo '<h2>Cart Contents:</h2>';
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total = $item['quantity'] * $item['price'];
            echo "$item[name] x $item[quantity] = \$" . number_format($total, 2);
            echo '<br>';
        }
    } else {
        echo 'Your cart is empty.';
    }

}

// Update cart with new item if add to cart button clicked
if (isset($_POST['add_to_cart'])) {
    update_cart();
}

?>

<form method="post">
    <input type="text" name="product_name" placeholder="Product Name">
    <input type="number" name="quantity" min="1" value="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<?php
view_cart();
?>



<?php
session_start();

// Set up default cart values if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($id, $name, $price) {
    // Check if product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            // Increment quantity if it's already in the cart
            $item['quantity'] += 1;
            return true;
        }
    }

    // Add new item to the cart
    $_SESSION['cart'][] = array(
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => 1
    );
    return false;
}

function remove_from_cart($id) {
    // Find and remove the item from the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            unset($item);
            break;
        }
    }

    // Reindex array to maintain correct keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

function update_cart_quantity($id, $quantity) {
    // Update the quantity of the item in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $quantity;
            return true;
        }
    }
    return false;
}

function get_cart_contents() {
    // Return the contents of the cart
    return $_SESSION['cart'];
}

// Example usage:
add_to_cart(1, "Product 1", 9.99);
add_to_cart(2, "Product 2", 19.99);

echo "<pre>";
print_r(get_cart_contents());
echo "</pre>";

remove_from_cart(1);
update_cart_quantity(2, 3);

echo "<pre>";
print_r(get_cart_contents());
echo "</pre>";


// Start the session
session_start();

// Define a function to add items to the cart
function addToCart($product_id, $quantity) {
    // If cart doesn't exist in the session yet, create it as an empty array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if this product is already in the cart and adjust its quantity accordingly
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the new item to the cart
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity,
            'price' => get_price_from_database($product_id)  // You'll need a function like this to retrieve prices dynamically.
        ];
    }

    // Save the changes to the session
    $_SESSION['cart'] = $_SESSION['cart'];
}

// Function to remove items from the cart
function removeFromCart($product_id) {
    if (!isset($_SESSION['cart'])) return;
    
    unset($_SESSION['cart'][$product_id]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    addToCart($product_id, $quantity);
} elseif (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Display the cart's contents
if (isset($_SESSION['cart'])) {
    echo 'Your Cart: ';
    
    foreach ($_SESSION['cart'] as $item) {
        // You could display a product description, image, or more here.
        // For simplicity, let's just show what we have in the array.
        echo "$item[quantity] x ID $item[product_id]";
    }
}


<?php
session_start();

// Check if the cart is already in session, if not set it to an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id) {
    global $cart;
    
    // Check if product is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity of existing product
            $item['quantity']++;
            return;
        }
    }

    // Product not in cart, add it
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $cart;

    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }

    // Product not in cart, do nothing
}

// Function to update item quantity in cart
function update_item_quantity($product_id, $new_quantity) {
    global $cart;

    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // Product not in cart, do nothing
}

// Function to get the total cost of items in the cart
function get_cart_total() {
    global $cart;

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Add item to cart (example)
add_item_to_cart(123);

// Print the current state of the cart
print_r($_SESSION['cart']);
?>


<?php
include 'cart.php';

// Display a form to add products to cart
?>

<form action="" method="post">
    <input type="hidden" name="product_id" value="123">
    <button type="submit">Add to Cart</button>
</form>

<!-- Display the current state of the cart -->
<p>Cart contents:</p>
<?php print_r($_SESSION['cart']); ?>

<!-- Calculate and display the total cost of items in the cart -->
<p>Total: <?php echo get_cart_total(); ?></p>


<?php
  // Initialize session
  if (!isset($_SESSION)) {
    session_start();
  }

  // If cart is empty, create a new one
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }
?>

<!-- HTML code for your webpage goes here -->
<form action="add_to_cart.php" method="post">
  <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
  <input type="submit" value="Add to Cart">
</form>

<div class="cart">
  <?php
    // Display cart contents
    if (count($_SESSION['cart']) > 0) {
      echo "<h2>Cart Contents:</h2>";
      foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = get_product($product_id); // assume this function exists to retrieve product details
        echo "$product->name (x$quantity)<br>";
      }
    } else {
      echo "<p>No items in cart.</p>";
    }
  ?>
</div>


<?php
  // Initialize session
  if (!isset($_SESSION)) {
    session_start();
  }

  // Get product ID from form data
  $product_id = $_POST['product_id'];

  // Check if product is already in cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment quantity
    $_SESSION['cart'][$product_id]++;
  } else {
    // Otherwise, add new item to cart with quantity of 1
    $_SESSION['cart'][$product_id] = 1;
  }

  // Redirect back to index.php
  header("Location: index.php");
  exit;
?>


<?php
  // Initialize session
  if (!isset($_SESSION)) {
    session_start();
  }

  // Get product ID from form data
  $product_id = $_POST['product_id'];

  // Check if product exists in cart and remove it if so
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }

  // Redirect back to index.php
  header("Location: index.php");
  exit;
?>


<?php
// Start the session
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart (example: adding a product with ID 1)
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

// Display the cart contents
?>

<h2>My Cart</h2>
<ul>
    <?php foreach ($_SESSION['cart'] as $product_id): ?>
        <li>Product <?php echo $product_id; ?></li>
    <?php endforeach; ?>
</ul>

<a href="?add_to_cart=1">Add Product 1 to cart</a>


<?php
// Update the quantity of a specific item in the cart

// Check if the product ID is set
if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = $_GET['product_id'];
    $new_quantity = $_GET['quantity'];

    // Validate input quantities
    if ($new_quantity < 0) {
        die('Invalid quantity');
    }

    // Update the cart array with the new quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item == $product_id) {
            $item = array($product_id, $new_quantity);
            break;
        }
    }
}

// Redirect back to index.php with the updated cart
header('Location: index.php');
exit();
?>


<?php
// Remove an item from the cart

// Check if the product ID is set
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Find and remove the product ID from the cart array
    $keys_to_remove = array_search($product_id, $_SESSION['cart']);
    unset($_SESSION['cart'][$keys_to_remove]);
}

// Redirect back to index.php with the updated cart
header('Location: index.php');
exit();
?>


<?php
// Start or resume existing session
session_start();

function createCart() {
    // If the cart doesn't exist, initialize it as an empty array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
}

// Function to add item to cart
function addItemToCart($productId, $name, $price) {
    // Check if product already exists in the cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // If it does, increment its quantity
        $_SESSION['cart'][$productId]['quantity'] += 1;
    } else {
        // If not, add it with a quantity of 1
        $_SESSION['cart'][$productId] = array('name' => $name, 'price' => $price, 'quantity' => 1);
    }
}

// Function to remove item from cart
function removeItemFromCart($productId) {
    // Check if the product exists in the cart and delete it
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to update quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    // Ensure new quantity is valid (i.e., greater than 0)
    if ($newQuantity > 0) {
        if (array_key_exists($productId, $_SESSION['cart'])) {
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        }
    } else {
        removeItemFromCart($productId);
    }
}

// Function to calculate the total cost of items in cart
function getCartTotal() {
    // Sum up all item prices multiplied by their quantities
    $total = 0;
    foreach ($_SESSION['cart'] as $product) {
        $total += ($product['price'] * $product['quantity']);
    }
    return $total;
}

// Example usage
createCart();

// Add some items to the cart
addItemToCart('P001', 'Product A', 19.99);
addItemToCart('P002', 'Product B', 29.99);

// Remove an item from the cart
removeItemFromCart('P002');

// Update quantity of another item
updateQuantity('P001', 3);

// Display what's in the cart and total cost
echo "Your Cart:
";
foreach ($_SESSION['cart'] as $product) {
    echo "$product[name] (quantity: $product[quantity]) - $" . ($product['price'] * $product['quantity']) . "
";
}
echo "
Total: $" . getCartTotal();
?>


<?php

// Start the session if it hasn't been started already.
if (!isset($_SESSION)) {
    session_start();
}

// Array to hold cart items for current user
$cart = &$_SESSION['cart'];

// If cart doesn't exist, create a new one
if (!isset($cart)) {
    $cart = array();
    $_SESSION['cart'] = &$cart;
}

function add_item_to_cart($product_id) {
    global $cart;

    // Check if product is already in cart
    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            // Increase quantity of existing item
            $cart[$key]['quantity'] += 1;
            return;
        }
    }

    // Add new item to cart
    $new_item = array(
        'id' => $product_id,
        'quantity' => 1, // Default quantity is 1
    );
    $cart[] = $new_item;

}

function remove_item_from_cart($product_id) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }
}

// Example usage
add_item_to_cart(1); // Add product with ID 1 to cart

// Update the quantity of an item in the cart
$cart[0]['quantity'] = 3;

// Print out what's currently in the cart
print_r($cart);

?>


<?php
// Start the session
session_start();

// Initialize an empty cart array if it doesn't exist already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add items to the cart
function addToCart($productId, $quantity) {
    global $_SESSION;
    
    // Check if product is already in the cart and update its quantity accordingly.
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            return; // Item was found, so no need to add it again.
        }
    }
    
    // If product is not in the cart or wasn't found, append it with its quantity.
    $_SESSION['cart'][] = ['id' => $productId, 'name' => 'Product Name', 'price' => 10.99, 'quantity' => $quantity];
}

// Function to display the contents of the cart
function displayCart() {
    global $_SESSION;
    
    echo '<h2>Your Shopping Cart</h2>';
    if (empty($_SESSION['cart'])) {
        echo '<p>Cart is empty.</p>';
    } else {
        foreach ($_SESSION['cart'] as $item) {
            echo 'Product: ' . $item['name'] . ', Quantity: ' . $item['quantity'] . ', Price: $' . number_format($item['price'], 2) . '<br>';
        }
    }
}

// Example usage:
addToCart(1, 3); // Add product with id=1 in quantity of 3
displayCart();
?>


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => 0);
    }
    
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
}


add_to_cart(1, 2); // Add 2 of product with ID 1 to cart


function update_cart_quantity($product_id, $quantity) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


update_cart_quantity(1, 3); // Update quantity of product with ID 1 to 3


function remove_from_cart($product_id) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


remove_from_cart(1); // Remove product with ID 1 from cart


function display_cart() {
    global $_SESSION;
    
    echo "Cart Contents:<br>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "Product ID: $product_id - Quantity: $item[quantity]<br>";
    }
}


display_cart();


if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    add_to_cart($product_id, $quantity);
}


display_cart();


<?php
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    global $_SESSION;
    
    // Add product to cart with quantity
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    
    // Update the session
    $_SESSION['cart'] = array_merge($_SESSION['cart'], array_diff_key($_SESSION['cart'], array_keys($_SESSION['cart'])));

}

// Function to update item in cart
function updateItemInCart($product_id, $new_quantity) {
    global $_SESSION;
    
    // Check if product is already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update quantity of the product in the cart
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        
        // Update the session
        $_SESSION['cart'] = array_merge($_SESSION['cart'], array_diff_key($_SESSION['cart'], array_keys($_SESSION['cart'])));
    }
}

// Function to remove item from cart
function removeFromCart($product_id) {
    global $_SESSION;
    
    // Check if product is in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Remove the product from the cart
        unset($_SESSION['cart'][$product_id]);
        
        // Update the session
        $_SESSION['cart'] = array_merge($_SESSION['cart'], array_diff_key($_SESSION['cart'], array_keys($_SESSION['cart'])));
    }
}

// Add example product to cart
addItemToCart(1, 2);


print_r($_SESSION['cart']);


Array
(
    [1] => Array
        (
            [quantity] => 2
        )

)


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Check if the item already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it does, update its quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If not, add a new item to the cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'name' => 'Item Name',  // Replace with actual item name from database
        'price' => 9.99,  // Replace with actual price from database
        'quantity' => $quantity
    );
}

// Function to update quantity of an item in cart
function update_item_quantity($item_id, $new_quantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to calculate total cost of items in cart
function calculate_total_cost() {
    global $_SESSION;
    
    $total = 0;
    foreach ($_SESSION['cart'] as &$item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Add item to cart (example)
add_item_to_cart(1, 2);

// Update quantity of an item in cart (example)
update_item_quantity(1, 3);

// Remove item from cart (example)
remove_item_from_cart(1);

// Display total cost of items in cart
echo 'Total: $' . calculate_total_cost() . '<br>';

// Print contents of cart
print_r($_SESSION['cart']);

?>


// Add item to cart on product page click-to-buy button
add_item_to_cart($_GET['product_id'], $_POST['quantity']);

// Update quantity of item in cart on update quantity form submission
update_item_quantity($_POST['item_id'], $_POST['new_quantity']);

// Remove item from cart on remove link click
remove_item_from_cart($_GET['item_id']);


// cart.php


<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'total' => 0
);


function add_item($item_id, $price) {
    global $cart;
    
    // Check if the item is already in the cart
    if (!in_array($item_id, $cart['items'])) {
        $cart['items'][] = $item_id;
        $cart['total'] += $price;
        
        // Update the session with the new cart data
        $_SESSION['cart'] = $cart;
    }
}


function remove_item($item_id) {
    global $cart;
    
    // Check if the item is in the cart
    if (in_array($item_id, $cart['items'])) {
        // Remove the item from the cart
        $key = array_search($item_id, $cart['items']);
        unset($cart['items'][$key]);
        
        // Update the total cost
        $cart['total'] -= array_sum(array_map(function ($id) use ($price_list) {
            return $price_list[$id];
        }, array_filter($cart['items'], function ($id) use ($item_id) {
            return $id == $item_id;
        })));
        
        // Update the session with the new cart data
        $_SESSION['cart'] = $cart;
    }
}


function get_cart() {
    global $cart;
    
    // If the session is not set, initialize it with an empty cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array(
            'items' => array(),
            'total' => 0
        );
    }
    
    return $_SESSION['cart'];
}


// Initialize the session and cart data structure
session_start();
$cart = get_cart();

// Add an item to the cart
add_item(1, 9.99);

// Remove an item from the cart
remove_item(1);

// Get the current cart contents
$cart = get_cart();

print_r($cart);


Array
(
    [items] => Array
        (
            [0] => 2
        )

    [total] => 19.98
)


<?php
session_start();
?>


function addItemToCart($productId, $productName, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item is already in cart to avoid duplicates
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            return true; // Item was found and quantity updated
        }
    }

    $_SESSION['cart'][] = [
        'id' => $productId,
        'name' => $productName,
        'quantity' => $quantity
    ];

    return true; // Item added successfully
}

// Example usage:
addItemToCart(1, "Product 1");


function removeItemFromCart($itemId) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $itemId) {
                unset($_SESSION['cart'][$key]);
                return true;
            }
        }
    }

    return false; // Item not found
}

// Example usage:
removeItemFromCart(1);


function updateQuantityInCart($itemId, $newQuantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $itemId) {
                $item['quantity'] = $newQuantity;
                return true; // Quantity updated
            }
        }
    }

    return false; // Item not found or invalid quantity provided
}

// Example usage:
updateQuantityInCart(1, 2);


if (isset($_SESSION['cart'])) {
    echo "Your cart contains:
";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[name] - Quantity: $item[quantity]
";
    }
} else {
    echo "Your cart is empty.";
}


// init.php
<?php
session_start();


// cart.php
class Cart {
    public $cart;

    function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $this->cart = $_SESSION['cart'];
    }

    // Add item to cart
    function add($product_id, $quantity) {
        if (array_key_exists($product_id, $this->cart)) {
            $this->cart[$product_id] += $quantity;
        } else {
            $this->cart[$product_id] = $quantity;
        }
    }

    // Remove item from cart
    function remove($product_id) {
        if (array_key_exists($product_id, $this->cart)) {
            unset($this->cart[$product_id]);
        }
    }

    // Empty cart
    function emptyCart() {
        $_SESSION['cart'] = array();
    }

    // Get cart contents
    function getCartContents() {
        return $this->cart;
    }
}


// index.php
require_once 'init.php';
require_once 'cart.php';

$cart = new Cart();

// Add items to cart
$cart->add(1, 2);
$cart->add(2, 3);

// Print cart contents
print_r($cart->getCartContents());

// Remove item from cart
$cart->remove(1);

// Empty cart
$cart->emptyCart();


<?php
  // Start the session
  if (!isset($_SESSION)) {
    session_start();
  }

  // Initialize the cart array if it doesn't exist
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }
?>


<?php
  // Function to add an item to the cart
  function addToCart($id, $name, $price) {
    global $_SESSION;
    $_SESSION['cart'][] = array(
      'id' => $id,
      'name' => $name,
      'price' => $price
    );
  }

  // Example usage:
  addToCart(1, "Product 1", 9.99);
  addToCart(2, "Product 2", 19.99);
?>


<?php
  // Function to display the contents of the cart
  function viewCart() {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $item) {
      echo "$item[name] x1 = £$item[price]";
      echo "<br>";
    }
  }

  // Example usage:
  viewCart();
?>


<?php
  // Function to remove an item from the cart
  function removeFromCart($id) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $id) {
        unset($_SESSION['cart'][$key]);
        return true;
      }
    }
    return false;
  }

  // Example usage:
  removeFromCart(1);
?>


<?php
session_start();
?>


<?php
// Initialize the session.
if (isset($_SESSION['shopping_cart'])) {
    // If we don't have a 'shopping_cart' session, create one with an empty array.
    $sessionCart = $_SESSION['shopping_cart'];
} else {
    $sessionCart = array();
}

// Add item to cart function
function add_to_cart($item_id) {
    global $sessionCart;
    if (array_key_exists($item_id, $sessionCart)) {
        // If it exists, increment the quantity.
        $sessionCart[$item_id] += 1;
    } else {
        // Otherwise, set its initial quantity to 1.
        $sessionCart[$item_id] = 1;
    }
}

// Remove item from cart function
function remove_from_cart($item_id) {
    global $sessionCart;
    if (array_key_exists($item_id, $sessionCart)) {
        unset($sessionCart[$item_id]);
    } else {
        echo "Item not found in the cart.";
    }
}

// View cart function
function view_cart() {
    global $sessionCart;
    return $sessionCart;
}

// Save updated session to maintain data across requests.
function save_session() {
    $_SESSION['shopping_cart'] = $GLOBALS['sessionCart'];
}
?>


<?php
require 'cart.php';
?>

<!-- Add a form to add item to cart -->
<form action="" method="post">
    <input type="hidden" name="item_id" value="1">
    <button type="submit">Add Item 1</button>
</form>

<!-- Display current cart contents -->
<h2>Cart Contents:</h2>
<pre>
<?php
// View the current state of our shopping cart.
print_r(view_cart());
?>
</pre>

<!-- Save session after updating cart -->
<?php save_session(); ?>


<?php

// Enable session support
session_start();

// Initialize cart if it doesn't exist in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example product IDs and names for demonstration purposes
$products = array(
    'P1' => array('name' => 'Product 1', 'price' => 19.99),
    'P2' => array('name' => 'Product 2', 'price' => 9.99),
    'P3' => array('name' => 'Product 3', 'price' => 29.99)
);

// Function to display cart contents
function showCart() {
    global $products, $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $details) {
            echo "Product: " . $products[$product_id]['name'] . ", Quantity: " . $details['quantity'];
            echo " (Total: $" . number_format($details['quantity'] * $products[$product_id]['price'], 2) . ")<br>";
        }
    } else {
        echo "Your cart is empty.";
    }
}

// Function to update quantity in the cart
function updateQuantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Ensure new quantity doesn't go below 1 and check product exists
    if (isset($_SESSION['cart'][$product_id])) {
        $details = &$_SESSION['cart'][$product_id];
        $details['quantity'] = max(1, $new_quantity);
    }
}

// Function to remove items from cart
function removeFromCart($product_id) {
    global $_SESSION;
    
    // Remove product if it exists in the cart
    unset($_SESSION['cart'][$product_id]);
}

?>

<!-- HTML for user interface -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Product:</label>
    <select name="product">
        <?php foreach ($products as $id => $details) { ?>
            <option value="<?php echo $id; ?>"><?php echo $details['name']; ?></option>
        <?php } ?>
    </select><br>
    
    <label>Quantity:</label>
    <input type="number" name="quantity" value="1"><br>
    
    <button type="submit">Add to Cart</button>
</form>

<?php
// Process form submission (add product to cart)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product'];
    $new_quantity = $_POST['quantity'];

    updateQuantity($product_id, $new_quantity);
}

showCart();
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="remove_product" value="1">
    
    <?php
    // Display form for removing items from cart and process removal on submission
    if (isset($_POST['remove_product'])) {
        $product_id = $_GET['id'];
        removeFromCart($product_id);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>
    
    <select name="remove">
        <?php foreach ($products as $id => $details) { if (isset($_SESSION['cart'][$id])) { ?>
            <option value="<?php echo $id; ?>"><?php echo $details['name']; ?></option>
        <?php } } ?>
    </select>
    
    <button type="submit">Remove from Cart</button>
</form>


class Cart {
  private $sessionId;
  private $userId;

  public function __construct($sessionId, $userId) {
    $this->sessionId = $sessionId;
    $this->userId = $userId;
  }

  public function addProduct($productId) {
    // Get the existing cart contents from session
    $cartContents = $this->_getCartContents();

    // Add product to cart contents array
    $cartContents[$productId] = ['quantity' => 1, 'price' => products::getPrice($productId)];

    // Update cart contents in session
    $_SESSION['cart_contents'] = $cartContents;
  }

  public function viewCart() {
    // Get the existing cart contents from session
    $cartContents = $this->_getCartContents();

    // Display cart contents
    echo '<h2>Your Cart</h2>';
    echo '<ul>';
    foreach ($cartContents as $productId => $product) {
      echo '<li>' . products::getName($productId) . ' x' . $product['quantity'] . ' = &#36;' . number_format($product['price'], 2) . '</li>';
    }
    echo '</ul>';
  }

  private function _getCartContents() {
    // Get the existing cart contents from session
    if (isset($_SESSION['cart_contents'])) {
      return $_SESSION['cart_contents'];
    } else {
      return [];
    }
  }
}


class User {
  private $userId;

  public function __construct($userId) {
    $this->userId = $userId;
  }

  public function login() {
    // Login logic here (e.g., check username/password)
    return true; // or false if login fails
  }

  public function getCart() {
    // Get the cart instance for this user
    return new Cart(session_id(), $this->userId);
  }
}


// Assume we have a logged-in user with ID 1
$user = new User(1);

// Get the cart instance for this user
$cart = $user->getCart();

// Add a product to the cart
$productId = 123; // assume product exists in database
$cart->addProduct($productId);

// View the contents of the cart
$cart->viewCart();


<?php
session_start();

// Initialize an empty array for the cart
$_SESSION['cart'] = array();
?>


<?php
function add_to_cart($item_id) {
    // Get the current session cart array
    $cart = $_SESSION['cart'];

    // Check if the item already exists in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Increment the quantity of the existing item
            $item['quantity'] += 1;
            return;
        }
    }

    // Add a new item to the cart array
    $new_item = array('id' => $item_id, 'name' => '', 'price' => 0.00, 'quantity' => 1);
    $cart[] = $new_item;

    // Update the session cart array
    $_SESSION['cart'] = $cart;
}
?>


<?php
function remove_from_cart($item_id) {
    // Get the current session cart array
    $cart = $_SESSION['cart'];

    // Check if the item exists in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $item_id) {
            // Decrement the quantity of the existing item
            if (--$item['quantity'] == 0) {
                // Remove the item from the cart array
                unset($item);
            }
            return;
        }
    }
}
?>


<?php
function get_cart() {
    // Return the current session cart array
    return $_SESSION['cart'];
}
?>


<?php
// Initialize the cart session
session_start();

// Add items to the cart
add_to_cart(1);
add_to_cart(2);

// Remove an item from the cart
remove_from_cart(2);

// View the current cart contents
$cart = get_cart();
print_r($cart);
?>


<?php
// At the top of your php file, add this line to start the session.
session_start();

// You can set a default values for cart items if needed.
$_SESSION['cart'] = array();


function addItemToCart($productId, $productName, $price) {
    global $_SESSION;
    
    // Check if the product is already in the cart.
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // If it exists, update the quantity instead of overwriting the old data.
            $item['quantity']++;
            return; // We've found it and updated its quantity.
        }
    }
    
    // If not, add it to the cart.
    $_SESSION['cart'][] = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $price,
        'quantity' => 1
    );
}


function removeFromCart($productId) {
    global $_SESSION;
    
    // Check each item to see if it matches the ID we want to remove.
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return; // Removed successfully
        }
    }
}


function displayCart() {
    global $_SESSION;
    
    echo "Your Cart:
";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        echo "- {$item['name']} x{$item['quantity']} = \$" . number_format($subtotal, 2) . "
";
        $total += $subtotal;
    }
    echo "Total: $" . number_format($total, 2);
}


// Adding items to cart.
addItemToCart(1, 'Product A', 9.99);
addItemToCart(1, 'Product A', 9.99); // Same product, increments quantity.

// Displaying the cart.
displayCart();


class Cart {
  private $cart;

  public function __construct() {
    $this->cart = [];
  }

  /**
   * Add an item to the cart.
   *
   * @param string $product_id Product ID of the item to add.
   * @param int $quantity Quantity of the item to add.
   */
  public function add($product_id, $quantity) {
    if (array_key_exists($product_id, $this->cart)) {
      $this->cart[$product_id] += $quantity;
    } else {
      $this->cart[$product_id] = $quantity;
    }
  }

  /**
   * Remove an item from the cart.
   *
   * @param string $product_id Product ID of the item to remove.
   */
  public function remove($product_id) {
    if (array_key_exists($product_id, $this->cart)) {
      unset($this->cart[$product_id]);
    }
  }

  /**
   * Update an item's quantity in the cart.
   *
   * @param string $product_id Product ID of the item to update.
   * @param int $quantity New quantity of the item.
   */
  public function update($product_id, $quantity) {
    if (array_key_exists($product_id, $this->cart)) {
      $this->cart[$product_id] = $quantity;
    }
  }

  /**
   * Get all items in the cart.
   *
   * @return array
   */
  public function getCart() {
    return $this->cart;
  }
}


// Start the session
session_start();

// Create a new Cart instance
$cart = new Cart();


// Add an item to the cart
$cart->add('product-123', 2);


// Update an item's quantity
$cart->update('product-123', 3);


// Remove an item from the cart
$cart->remove('product-123');


// Save the cart session
$_SESSION['cart'] = $cart->getCart();


// Get the Cart instance from the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : new Cart();

// Add some items to the cart
$cart->add('product-123', 2);
$cart->add('product-456', 1);

// Save the cart session
$_SESSION['cart'] = $cart->getCart();


<?php
session_start();
?>


<?php
function update_cart($product_id, $quantity) {
    $_SESSION['cart'][$product_id] = $quantity;
}

function display_cart() {
    echo '<h2>Cart Contents:</h2>';
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $qnt) {
            echo "$id: $qnt<br>";
        }
    } else {
        echo "Your cart is empty.";
    }
}
?>


<?php
// Assuming $id is the ID of the clicked product, and $qnt is its quantity.
$update_button = '<button onclick="update_cart(' . $id . ', ' . $qnt . ')">Add to Cart</button>';

display_cart();
?>


<?php
session_start();

function update_cart($product_id, $quantity) {
    $_SESSION['cart'][$product_id] = $quantity;
}

function display_cart() {
    echo '<h2>Cart Contents:</h2>';
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $qnt) {
            echo "$id: $qnt<br>";
        }
    } else {
        echo "Your cart is empty.";
    }
}

// Example usage
$pid = 1; // Product ID example, replace with real data.
$qnt = 2; // Quantity example.

$update_button = '<button onclick="update_cart(' . $pid . ', ' . $qnt . ')">Add to Cart</button>';

display_cart();
?>


<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add a product to the cart
function add_to_cart($product_id, $quantity) {
    // Get the current products in the cart
    $current_products = $_SESSION['cart'];

    // Check if the product is already in the cart
    foreach ($current_products as $key => $value) {
        if ($value['id'] == $product_id) {
            // If it's already there, increment the quantity
            $current_products[$key]['quantity'] += $quantity;
            break;
        }
    }

    // Add the product to the cart if it's not already there
    if (!isset($current_products[$product_id])) {
        $current_products[$product_id] = array('id' => $product_id, 'quantity' => $quantity);
    }

    // Update the session with the new products in the cart
    $_SESSION['cart'] = $current_products;

    // Calculate the total price of the products in the cart
    $_SESSION['total_price'] = calculate_total_price();

    return true;
}

// Remove a product from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['total_price'] = calculate_total_price();
    }
    return true;
}

// Update the quantity of a product in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        $_SESSION['total_price'] = calculate_total_price();
    }
    return true;
}

// Calculate the total price of all products in the cart
function calculate_total_price() {
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product) {
        $total_price += $product['quantity'] * get_product_price($product['id']);
    }
    return $total_price;
}

// Get the price of a product (example: assumed to be stored in an array)
function get_product_price($product_id) {
    // Replace this with your actual database or data storage
    $products = array(
        1 => 9.99,
        2 => 14.99,
        3 => 19.99
    );
    return isset($products[$product_id]) ? $products[$product_id] : 0;
}

// Example usage:
$product_id = 1; // Replace with actual product ID
$quantity = 2;

if (add_to_cart($product_id, $quantity)) {
    echo 'Product added to cart!';
} else {
    echo 'Error adding product to cart.';
}

echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

echo 'Total price: ' . $_SESSION['total_price'];
?>


<?php
session_start();

// Function to add item to cart
function addItemToCart($item_id, $quantity) {
    // Check if cart is already set in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Add item to cart with quantity
    $_SESSION['cart'][$item_id] = array('quantity' => $quantity);
}

// Function to remove item from cart
function removeItemFromCart($item_id) {
    // Check if cart is set in session
    if (isset($_SESSION['cart'])) {
        // Remove item from cart
        unset($_SESSION['cart'][$item_id]);
        
        // Re-index array after removing item
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to update quantity of item in cart
function updateQuantity($item_id, $new_quantity) {
    // Check if cart is set in session
    if (isset($_SESSION['cart'][$item_id])) {
        // Update quantity of item
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}

// Function to display cart contents
function displayCart() {
    echo "Cart Contents:<br>";
    
    // Check if cart is set in session
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item_id => $item) {
            echo "Item ID: $item_id, Quantity: {$item['quantity']}<br>";
        }
    } else {
        echo "Your cart is empty.";
    }
}

// Example usage
addItemToCart(1, 2); // Add item with ID 1 and quantity 2 to cart
displayCart(); // Display cart contents

removeItemFromCart(1); // Remove item with ID 1 from cart
displayCart(); // Display updated cart contents

updateQuantity(1, 3); // Update quantity of item with ID 1 in cart
displayCart(); // Display updated cart contents
?>


<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get the current cart contents
$cart = $_SESSION['cart'];

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $cart;
    
    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If not, add a new item to the cart
    $cart[] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $cart;
    
    // Find the item in the cart and remove it
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function update_cart_item($product_id, $new_quantity) {
    global $cart;
    
    // Find the item in the cart and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to display the contents of the cart
function display_cart() {
    global $cart;
    
    echo '<h2>Cart Contents:</h2>';
    foreach ($cart as $item) {
        echo 'Product ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}
?>


<?php
require_once 'cart.php';

// Add some products to the cart
add_to_cart(1, 2);
add_to_cart(2, 3);

// Display the contents of the cart
display_cart();

// Update the quantity of an item in the cart
update_cart_item(1, 4);

// Remove an item from the cart
remove_from_cart(2);

// Display the updated contents of the cart
display_cart();
?>


class Cart {
    private $session;

    public function __construct() {
        $this->session = $_SESSION;
    }

    /**
     * Add product to cart
     *
     * @param int   $id  Product ID
     * @param string $name Product Name
     * @param float  $price Product Price
     */
    public function addProduct($id, $name, $price) {
        if (!isset($this->session['cart'])) {
            $this->session['cart'] = array();
        }

        // Check if product already exists in cart
        foreach ($this->session['cart'] as &$product) {
            if ($product['id'] == $id) {
                // Increment quantity if product already exists
                $product['quantity'] += 1;
                return;
            }
        }

        // Add new product to cart
        $this->session['cart'][] = array(
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    }

    /**
     * Remove product from cart
     *
     * @param int $id Product ID
     */
    public function removeProduct($id) {
        if (isset($this->session['cart'])) {
            foreach ($this->session['cart'] as &$product) {
                if ($product['id'] == $id) {
                    unset($product);
                    break;
                }
            }

            // Remove empty products from cart
            $this->session['cart'] = array_filter($this->session['cart']);
        }
    }

    /**
     * Get total cart value
     *
     * @return float Total value of cart
     */
    public function getTotal() {
        if (isset($this->session['cart'])) {
            return array_sum(array_map(function ($product) { return $product['price'] * $product['quantity']; }, $this->session['cart']));
        }

        return 0;
    }
}


require_once 'Cart.php';

// Start session
session_start();

$cart = new Cart();

// Add products to cart
$cart->addProduct(1, "Apple Watch", 299.99);
$cart->addProduct(2, "Samsung TV", 999.99);

// Remove product from cart
$cart->removeProduct(1);

// Get total cart value
echo "Total: $" . $cart->getTotal();


<?php
session_start();
?>


// Initialize cart array
$_SESSION['cart'] = array();

function add_to_cart($product_id, $product_name, $price) {
  // Check if product already exists in cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      // If item is found, increment quantity
      $item['quantity']++;
      return;
    }
  }

  // Add new item to cart
  $_SESSION['cart'][] = array('id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => 1);
}


add_to_cart(1, "Product A", 10.99);
add_to_cart(2, "Product B", 5.99);
add_to_cart(1, "Product A", 10.99); // Product A quantity is now 2


// Display cart contents
echo "<h2>Cart Contents:</h2>";
foreach ($_SESSION['cart'] as $item) {
  echo "$" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
}

// Calculate total cost
$total_cost = 0;
foreach ($_SESSION['cart'] as $item) {
  $total_cost += $item['price'] * $item['quantity'];
}
echo "Total: $" . number_format($total_cost, 2);


function remove_from_cart($product_id) {
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return;
    }
  }
}


remove_from_cart(1); // Remove Product A from cart


<?php

session_start();

// Initialize cart array
$_SESSION['cart'] = array();

function add_to_cart($product_id, $product_name, $price) {
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity']++;
      return;
    }
  }

  $_SESSION['cart'][] = array('id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => 1);
}

function remove_from_cart($product_id) {
  foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return;
    }
  }
}

// Example usage
add_to_cart(1, "Product A", 10.99);
add_to_cart(2, "Product B", 5.99);
add_to_cart(1, "Product A", 10.99); // Product A quantity is now 2

echo "<h2>Cart Contents:</h2>";
foreach ($_SESSION['cart'] as $item) {
  echo "$" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
}

$total_cost = 0;
foreach ($_SESSION['cart'] as $item) {
  $total_cost += $item['price'] * $item['quantity'];
}
echo "Total: $" . number_format($total_cost, 2);

remove_from_cart(1); // Remove Product A from cart

echo "<h2>Cart Contents after removal:</h2>";
foreach ($_SESSION['cart'] as $item) {
  echo "$" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
}

$total_cost = 0;
foreach ($_SESSION['cart'] as $item) {
  $total_cost += $item['price'] * $item['quantity'];
}
echo "Total: $" . number_format($total_cost, 2);

?>


<?php
// Start the session
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If the product is already in the cart, increment its quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If not, add it to the cart with a new entry
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => '', // assume we're storing product names elsewhere
            'price' => 0.00, // assume we're storing prices elsewhere
            'quantity' => $quantity
        );
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If the product is in the cart, unset it
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update quantity of item in cart
function update_quantity_in_cart($product_id, $new_quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If the product is in the cart, update its quantity
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_item_to_cart($product_id, $quantity);
} elseif (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_item_from_cart($product_id);
} elseif (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    update_quantity_in_cart($product_id, $new_quantity);
}

// Print the cart contents
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

?>


<?php
require 'cart.php';

// Example form to add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    // Add the product to the cart using the `add_item_to_cart` function
} else {
    ?>
    <form action="" method="post">
        <input type="hidden" name="product_id" value="<?php echo 'P123'; ?>">
        <input type="number" name="quantity" value="1">
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
    <?php
}

// Example form to remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    // Remove the product from the cart using the `remove_item_from_cart` function
} else {
    ?>
    <form action="" method="post">
        <input type="hidden" name="product_id" value="<?php echo 'P123'; ?>">
        <button type="submit" name="remove_from_cart">Remove from Cart</button>
    </form>
    <?php
}

// Example form to update quantity of item in cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    // Update the quantity of the product in the cart using the `update_quantity_in_cart` function
} else {
    ?>
    <form action="" method="post">
        <input type="hidden" name="product_id" value="<?php echo 'P123'; ?>">
        <input type="number" name="new_quantity" value="2">
        <button type="submit" name="update_quantity">Update Quantity</button>
    </form>
    <?php
}
?>


<?php
// Start the session
session_start();

// Check if the cart already exists in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = [];
}

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it does, increment its quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add new item to cart
    $_SESSION['cart'][] = ['product_id' => $product_id, 'quantity' => $quantity];
}

// Function to remove item from cart
function removeFromCart($product_id) {
    global $_SESSION;
    
    // Check if product exists in cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Check if product exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    addItemToCart($product_id, $quantity);
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    
    removeFromCart($product_id);
}

if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    
    updateQuantity($product_id, $new_quantity);
}
?>


<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="12345">
    <input type="number" name="quantity" value="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>


<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="12345">
    <button type="submit" name="remove_from_cart">Remove from Cart</button>
</form>

<form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="12345">
    <input type="number" name="new_quantity" value="2">
    <button type="submit" name="update_quantity">Update Quantity</button>
</form>


<?php
session_start();
?>


<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
function addToCart($itemID, $quantity = 1) {
    global $_SESSION;
    
    if (array_key_exists($itemID, $_SESSION['cart'])) {
        $_SESSION['cart'][$itemID]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$itemID] = array('quantity' => $quantity);
    }
}
?>


<?php
function displayCart() {
    global $_SESSION;
    
    echo "Your Cart:
";
    foreach ($_SESSION['cart'] as $itemID => $details) {
        echo "$itemID x $details[quantity]
";
    }
}
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

addToCart(1, 2); // Add an item with ID 1 and quantity 2
addToCart(2, 3); // Add another item with ID 2 and quantity 3

displayCart(); // Display the contents of the cart
?>


<?php
session_start();
?>


function set_item_in_cart($item_name, $quantity) {
    if (isset($_SESSION['cart'])) {
        // Item is already in cart, increment quantity or update details
        $index = array_search($item_name, $_SESSION['cart']);
        if ($index !== false) {
            if (array_key_exists('quantity', $_SESSION['cart'][$index])) {
                $_SESSION['cart'][$index]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$index]['quantity'] = $quantity;
            }
        } else {
            // Item not found, add it to the cart
            $_SESSION['cart'][] = array(
                'name' => $item_name,
                'quantity' => $quantity
            );
        }
    } else {
        // Cart is empty, initialize it with this item
        $_SESSION['cart'] = array(array('name' => $item_name, 'quantity' => $quantity));
    }
}


function remove_item_from_cart($item_name) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] == $item_name) {
                unset($item);
                break;
            }
        }
        
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}


function update_item_quantity($item_name, $new_quantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] == $item_name) {
                $item['quantity'] = $new_quantity;
                break;
            }
        }
    }
}


<?php
session_start();

// Set item in cart with quantity 2
set_item_in_cart('Apple', 2);

// Add another item, same product, different quantity
set_item_in_cart('Apple', 3); // Quantity will be 5

// Remove an item from the cart
remove_item_from_cart('Apple');

// Update quantity of remaining items (none in this case but for demonstration)
update_item_quantity('Apple', 4);

print_r($_SESSION['cart']);
?>


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create database connection
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch(PDOException $e) {
  die("ERROR: Could not connect. " . $e->getMessage());
}

class Cart {
  private $session;

  function __construct() {
    if (isset($_SESSION['cart'])) {
      $this->session = $_SESSION['cart'];
    } else {
      $this->session = array();
      $_SESSION['cart'] = $this->session;
    }
  }

  function addProduct($id, $name, $price) {
    // Check if product is already in cart
    foreach ($this->session as &$product) {
      if ($product['id'] == $id) {
        // Increase quantity if product is already in cart
        $product['quantity']++;
        return;
      }
    }

    // Add new product to cart
    $newProduct = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
    $this->session[] = $newProduct;
  }

  function removeProduct($id) {
    // Remove product from cart
    foreach ($this->session as &$product) {
      if ($product['id'] == $id) {
        unset($product);
      }
    }

    // Reindex array after removing product
    $this->session = array_values($this->session);
  }

  function updateQuantity($id, $newQuantity) {
    // Update quantity of product in cart
    foreach ($this->session as &$product) {
      if ($product['id'] == $id) {
        $product['quantity'] = $newQuantity;
      }
    }
  }

  function getCart() {
    return $this->session;
  }
}

// Example usage:
$cart = new Cart();

// Add products to cart
$cart->addProduct(1, 'Product 1', 9.99);
$cart->addProduct(2, 'Product 2', 19.99);

// Update quantity of product in cart
$cart->updateQuantity(1, 3);

// Remove product from cart
$cart->removeProduct(2);

// Get cart contents
print_r($cart->getCart());

?>


class Cart {
    private $session_name;
    private $cart_contents;

    public function __construct() {
        $this->session_name = 'cart';
        $this->cart_contents = array();
    }

    public function add_item($item_id, $quantity) {
        if (isset($_SESSION[$this->session_name])) {
            $_SESSION[$this->session_name][] = array('item_id' => $item_id, 'quantity' => $quantity);
        } else {
            $_SESSION[$this->session_name] = array(array('item_id' => $item_id, 'quantity' => $quantity));
        }
    }

    public function remove_item($item_id) {
        if (isset($_SESSION[$this->session_name])) {
            foreach ($_SESSION[$this->session_name] as &$item) {
                if ($item['item_id'] == $item_id) {
                    unset($item);
                }
            }
            $_SESSION[$this->session_name] = array_values($_SESSION[$this->session_name]);
        }
    }

    public function update_quantity($item_id, $new_quantity) {
        if (isset($_SESSION[$this->session_name])) {
            foreach ($_SESSION[$this->session_name] as &$item) {
                if ($item['item_id'] == $item_id) {
                    $item['quantity'] = $new_quantity;
                }
            }
        }
    }

    public function get_contents() {
        return $_SESSION[$this->session_name];
    }
}


$cart = new Cart();


$cart->add_item(1, 2); // Add item with ID 1 and quantity 2
$cart->add_item(3, 4); // Add item with ID 3 and quantity 4


$cart->remove_item(1);


$cart->update_quantity(3, 5); // Update quantity of item with ID 3 to 5


print_r($cart->get_contents());


Array
(
    [0] => Array
        (
            [item_id] => 1
            [quantity] => 2
        )

    [1] => Array
        (
            [item_id] => 3
            [quantity] => 5
        )

)


if ($cart !== null) {
    // Use the $cart object here
} else {
    // Handle error case where $cart is null
}


<?php
session_start();
?>


function initCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
}


function addToCart($item, $quantity) {
    global $cart;
    if (!isset($_SESSION['cart'][$item])) {
        $_SESSION['cart'][$item] = array('quantity' => $quantity);
    } else {
        $_SESSION['cart'][$item]['quantity'] += $quantity;
    }
}


function removeFromCart($item) {
    global $cart;
    if (isset($_SESSION['cart'][$item])) {
        unset($_SESSION['cart'][$item]);
    }
}


function showCart() {
    echo "<h2>Your Shopping Cart</h2>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item => $details) {
        list($product_id, $price) = explode(":", $item);
        $quantity = $details['quantity'];
        echo "Product: $product_id - Price: \$" . number_format($price, 2) . " x $quantity<br>";
        $total += ($price * $quantity);
    }
    echo "<br>Total: $" . number_format($total, 2);
}


<?php
initCart();

// Example of adding items
addToCart("Product1:10.99", 3);
addToCart("Product2:5.49", 4);

showCart();
?>


// Initialize the session
session_start();


// Function to add products to cart
function add_to_cart($product_id, $quantity) {
    // Get the cart array from the session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Add the product to the cart array
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
}


// Function to update cart quantities
function update_cart($product_id, $quantity) {
    // Get the cart array from the session
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Function to remove products from cart
function remove_from_cart($product_id) {
    // Get the cart array from the session
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


// Initialize the session
session_start();

// Add products to cart
add_to_cart(1, 2);
add_to_cart(2, 3);

// Update cart quantities
update_cart(1, 4);

// Remove product from cart
remove_from_cart(2);

// Display cart contents
print_r($_SESSION['cart']);


<?php

// Initialize the session
session_start();

// Function to add products to cart
function add_to_cart($product_id, $quantity) {
    // Get the cart array from the session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Add the product to the cart array
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
}

// Function to update cart quantities
function update_cart($product_id, $quantity) {
    // Get the cart array from the session
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove products from cart
function remove_from_cart($product_id) {
    // Get the cart array from the session
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example usage
add_to_cart(1, 2);
add_to_cart(2, 3);

update_cart(1, 4);

remove_from_cart(2);

print_r($_SESSION['cart']);

?>


class Cart {
    private $cartId;
    private $userId;

    public function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->cartId = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;
        $this->userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    public function addProduct($productId, $quantity) {
        if ($this->cartId === null) {
            // Create a new cart
            $this->createCart();
        }
        $existingProduct = $this->_getExistingProduct($productId);
        if ($existingProduct !== false) {
            // Update existing product quantity
            $this->_updateQuantity($existingProduct['id'], $quantity);
        } else {
            // Add a new product to the cart
            $this->_addProductToCart($productId, $quantity);
        }
    }

    public function removeProduct($productId) {
        if ($this->cartId === null) {
            return;
        }
        $existingProduct = $this->_getExistingProduct($productId);
        if ($existingProduct !== false) {
            // Remove product from cart
            $this->_removeProductFromCart($existingProduct['id']);
        }
    }

    public function updateQuantity($productId, $newQuantity) {
        $existingProduct = $this->_getExistingProduct($productId);
        if ($existingProduct !== false) {
            // Update existing product quantity
            $this->_updateQuantity($existingProduct['id'], $newQuantity);
        }
    }

    public function getCartContents() {
        if ($this->cartId === null) {
            return array();
        }
        $query = "SELECT p.name, p.price, c.quantity FROM products p JOIN cart c ON p.id = c.product_id WHERE c.cart_id = :cart_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':cart_id', $this->cartId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function createCart() {
        // Create a new cart
        $query = "INSERT INTO cart (user_id) VALUES (:user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $this->cartId = $pdo->lastInsertId();
        $_SESSION['cart_id'] = $this->cartId;
    }

    private function _addProductToCart($productId, $quantity) {
        // Add a new product to the cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
    }

    private function _updateQuantity($cartId, $newQuantity) {
        // Update existing product quantity
        $query = "UPDATE cart SET quantity = :new_quantity WHERE id = :cart_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':new_quantity', $newQuantity);
        $stmt->bindParam(':cart_id', $cartId);
        $stmt->execute();
    }

    private function _removeProductFromCart($cartId) {
        // Remove product from cart
        $query = "DELETE FROM cart WHERE id = :cart_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':cart_id', $cartId);
        $stmt->execute();
    }

    private function _getExistingProduct($productId) {
        // Get existing product from cart
        $query = "SELECT * FROM cart WHERE product_id = :product_id AND user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


$cart = new Cart();

// Add a product to cart
$cart->addProduct(1, 2);

// Remove a product from cart
$cart->removeProduct(1);

// Update quantity of existing product in cart
$cart->updateQuantity(1, 3);

// Get contents of cart
$cartContents = $cart->getCartContents();
print_r($cartContents);


session_start();

// Check if cart is already initialized
if (!isset($_SESSION['cart'])) {
    // Initialize an empty array for the cart
    $_SESSION['cart'] = array();
}


// Get the product ID and quantity
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if the product is already in the cart
if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity of the product
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
} else {
    // Add the product to the cart with initial quantity
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
}

// Save the changes to the session
session_write_close();


// Get the cart contents from the session
$cart_contents = $_SESSION['cart'];

// Loop through the cart contents and display each product
foreach ($cart_contents as $product_id => $product) {
    // Display the product details
    echo "Product ID: $product_id";
    echo "Quantity: $product[quantity]";
    echo "<br>";
}


<?php

// Check if session is not started
if (!isset($_SESSION)) {
    session_start();
}

// Set default cart values
$_SESSION['cart'] = array();

?>


<?php

// Get product ID and quantity from request
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Validate input data
if (!$product_id || !$quantity) {
    exit('Error: Invalid product ID or quantity');
}

// Check if product is already in cart
foreach ($_SESSION['cart'] as $item) {
    if ($item['id'] == $product_id) {
        // Increase quantity of existing item
        $item['quantity'] += $quantity;
        break;
    }
}

// Add new item to cart if not present
if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array('id' => $product_id, 'quantity' => $quantity);
}

?>


<?php

// Display cart contents
echo '<h2>Cart Contents:</h2>';
foreach ($_SESSION['cart'] as $item) {
    echo 'Product ID: ' . $item['id'];
    echo ', Quantity: ' . $item['quantity'];
    echo '<br />';
}

?>


<?php
session_start();
var_dump(session_status());
?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
function add_to_cart($item_id, $name, $price) {
    global $_SESSION;
    $found = false;

    // Check if item is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    // Add new item to cart if not found
    if (!$found) {
        $_SESSION['cart'][] = array(
            'id' => $item_id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        );
    }
}
?>


<?php
function update_quantity($item_id, $new_quantity) {
    global $_SESSION;

    // Find item in cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}
?>


<?php
function remove_from_cart($item_id) {
    global $_SESSION;

    // Find item in cart and remove it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // If item is removed, update session data to reflect changes
    $_SESSION = array_diff_key($_SESSION, array_flip(array_keys($_SESSION)));
}
?>


<?php
function display_cart() {
    global $_SESSION;

    // Get items in cart and calculate total cost
    $total_cost = 0;
    foreach ($_SESSION['cart'] as &$item) {
        echo "Item: " . $item['name'] . " (x" . $item['quantity'] . ") - $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        $total_cost += ($item['price'] * $item['quantity']);
    }

    // Display total cost
    echo "Total Cost: $" . number_format($total_cost, 2);
}
?>


<?php
session_start();

// Add item to cart
add_to_cart(1, 'Product A', 9.99);

// Add another item to cart
add_to_cart(2, 'Product B', 19.99);

// Update quantity of first item
update_quantity(1, 3);

// Remove second item from cart
remove_from_cart(2);

// Display cart contents
display_cart();
?>


<?php
session_start();

// Function to add item to cart
function addToCart($item_id, $quantity) {
    global $cart;
    
    // If session isn't set yet, initialize it with an empty array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
        
    // Check if the item already exists in cart.
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        // If it does, increment its quantity by 1
        $new_quantity = $_SESSION['cart'][$item_id] + $quantity;
        $_SESSION['cart'][$item_id] = $new_quantity;
    } else {
        // Otherwise, add the item to cart with given quantity.
        $_SESSION['cart'][$item_id] = $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($item_id) {
    global $cart;
    
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
    } else {
        // If the item doesn't exist in cart, do nothing.
        echo "Item is not in your cart.";
    }
}

// Function to display items in the cart
function displayCart() {
    global $cart;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item_id => $quantity) {
            // You would usually query your database here to get item details
            // For simplicity, let's just echo what we have.
            echo "Item ID: $item_id, Quantity: $quantity<br>";
        }
    } else {
        echo 'Your cart is empty.';
    }
}

// Example usage:
if (isset($_POST['add'])) {
    addToCart($_POST['item_id'], $_POST['quantity']);
} elseif (isset($_POST['remove'])) {
    removeFromCart($_POST['item_id']);
}

// Display the items in your cart
displayCart();
?>

<form action="" method="post">
    <input type="hidden" name="add" value="true">
    Item ID: <input type="number" name="item_id"><br>
    Quantity: <input type="number" name="quantity"><br>
    <input type="submit" value="Add to Cart">
</form>

<form action="" method="post">
    <input type="hidden" name="remove" value="true">
    Item ID: <input type="number" name="item_id"><br>
    <input type="submit" value="Remove from Cart">
</form>


<?php
session_start();
?>


// Example function to add item to cart:
function addToCart($itemId, $itemName, $itemQuantity) {
    if (isset($_SESSION['cart'])) {
        // If cart is not empty, append new item.
        $_SESSION['cart'][] = array('id' => $itemId, 'name' => $itemName, 'quantity' => $itemQuantity);
    } else {
        // First time user adds an item to their cart.
        $_SESSION['cart'] = array(array('id' => $itemId, 'name' => $itemName, 'quantity' => $itemQuantity));
    }
}


// Example function to view and print cart contents:
function printCart() {
    if (isset($_SESSION['cart'])) {
        echo "Your Cart:
";
        foreach ($_SESSION['cart'] as $item) {
            echo $item['name'] . " x" . $item['quantity'] . "
";
        }
    } else {
        echo "Your cart is empty.
";
    }
}


// Example function to remove item from cart:
function removeFromCart($itemId) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $itemId) {
                unset($_SESSION['cart'][$key]);
                return true; // Item removed
            }
        }
    }
    return false; // Item not found
}


<?php
session_start();

// Adding items to cart
addToCart(1, "Product 1", 2);
addToCart(2, "Product 2", 3);

// Viewing cart contents
printCart();

// Removing an item from the cart
removeFromCart(2);

// Viewing updated cart contents
printCart();
?>


<?php

// Initialize session
session_start();

// Check if cart exists in session or create a new one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($item) {
    global $smarty; // Assuming you're using Smarty template engine
    
    // Check if item already exists in cart
    foreach ($_SESSION['cart'] as &$existingItem) {
        if ($existingItem['id'] == $item['id']) {
            $existingItem['quantity']++;
            return;
        }
    }
    
    // Generate unique ID for the item
    $itemId = uniqid();
    
    // Add item to cart
    $_SESSION['cart'][] = array(
        'id' => $itemId,
        'name' => $item['name'],
        'price' => $item['price'],
        'quantity' => 1
    );
}

// Function to update the quantity of an item in the cart
function updateItemQuantity($itemId, $newQuantity) {
    global $_SESSION;
    
    // Find the item with the given ID and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] = $newQuantity;
            return;
        }
    }
}

// Function to remove an item from the cart
function removeFromCart($itemId) {
    global $_SESSION;
    
    // Find the item with the given ID and remove it from the cart
    foreach ($_SESSION['cart'] as &$item, $key => $value) {
        if ($item['id'] == $itemId) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Example usage:
$items = array(
    array('id' => 1, 'name' => 'Product A', 'price' => 10.99),
    array('id' => 2, 'name' => 'Product B', 'price' => 9.99)
);

foreach ($items as $item) {
    addItemToCart($item);
}

// Output the cart
echo "Cart Contents:
";
foreach ($_SESSION['cart'] as $item) {
    echo "ID: {$item['id']} - Name: {$item['name']} - Price: {$item['price']} - Quantity: {$item['quantity']}
";
}


<?php
// Start the session
session_start();

// Define some constants for the cart items
define('CART_ITEM_NAME', 'item_name');
define('CART_ITEM_PRICE', 'item_price');
define('CART_ITEM_QUANTITY', 'item_quantity');

// Function to add an item to the cart
function addItemToCart($itemName, $itemPrice) {
  // Check if the item is already in the cart
  foreach ($_SESSION['cart'] as &$item) {
    if ($item[CART_ITEM_NAME] == $itemName) {
      // If it's already in the cart, increment its quantity
      $item[CART_ITEM_QUANTITY]++;
      return;
    }
  }

  // If not, add a new item to the cart
  $_SESSION['cart'][] = [
    CART_ITEM_NAME => $itemName,
    CART_ITEM_PRICE => $itemPrice,
    CART_ITEM_QUANTITY => 1
  ];
}

// Function to remove an item from the cart
function removeItemFromCart($itemName) {
  // Loop through the cart and find the item to remove
  foreach ($_SESSION['cart'] as &$item) {
    if ($item[CART_ITEM_NAME] == $itemName) {
      // Remove the item from the cart
      unset($item);
      break;
    }
  }

  // Rebuild the session cart array to remove any empty items
  $_SESSION['cart'] = array_filter($_SESSION['cart']);
}

// Function to update an item's quantity in the cart
function updateItemQuantity($itemName, $newQuantity) {
  // Loop through the cart and find the item to update
  foreach ($_SESSION['cart'] as &$item) {
    if ($item[CART_ITEM_NAME] == $itemName) {
      // Update the item's quantity
      $item[CART_ITEM_QUANTITY] = $newQuantity;
      break;
    }
  }
}

// Add some items to the cart example
addItemToCart('Apple', 0.99);
addItemToCart('Banana', 0.49);

// Print out the contents of the cart
echo '<h1>Cart Contents:</h1>';
echo '<ul>';
foreach ($_SESSION['cart'] as $item) {
  echo '<li>' . $item[CART_ITEM_NAME] . ' x' . $item[CART_ITEM_QUANTITY] . ' = ' . $item[CART_ITEM_PRICE] * $item[CART_ITEM_QUANTITY] . '</li>';
}
echo '</ul>';

// Remove an item from the cart example
removeItemFromCart('Banana');

// Print out the updated contents of the cart
echo '<h1>Updated Cart Contents:</h1>';
echo '<ul>';
foreach ($_SESSION['cart'] as $item) {
  echo '<li>' . $item[CART_ITEM_NAME] . ' x' . $item[CART_ITEM_QUANTITY] . ' = ' . $item[CART_ITEM_PRICE] * $item[CART_ITEM_QUANTITY] . '</li>';
}
echo '</ul>';

// Update an item's quantity in the cart example
updateItemQuantity('Apple', 2);

// Print out the updated contents of the cart
echo '<h1>Updated Cart Contents:</h1>';
echo '<ul>';
foreach ($_SESSION['cart'] as $item) {
  echo '<li>' . $item[CART_ITEM_NAME] . ' x' . $item[CART_ITEM_QUANTITY] . ' = ' . $item[CART_ITEM_PRICE] * $item[CART_ITEM_QUANTITY] . '</li>';
}
echo '</ul>';

?>


<?php
// Initialize the session
session_start();

// Function to add product to cart
function add_to_cart($product_id, $quantity) {
    // Get the existing products in the cart from session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increase quantity of existing product
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new product to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove product from cart
function remove_from_cart($product_id) {
    // Get the products in the cart from session
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                return;
            }
        }
    }
}

// Function to update quantity of product in cart
function update_quantity($product_id, $new_quantity) {
    // Get the products in the cart from session
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $new_quantity;
                return;
            }
        }
    }
}

// Function to display cart contents
function display_cart() {
    // Get the products in the cart from session
    if (isset($_SESSION['cart'])) {
        echo "<h2>Cart Contents:</h2>";
        foreach ($_SESSION['cart'] as $item) {
            echo "Product ID: " . $item['id'] . ", Quantity: " . $item['quantity'] . "<br>";
        }
    } else {
        echo "<p>No products in cart.</p>";
    }
}
?>


<?php include 'cart.php'; ?>

<!-- Add product to cart -->
<button onclick="add_to_cart(1, 2);">Add Product 1 to Cart</button>
<button onclick="add_to_cart(2, 3);">Add Product 2 to Cart</button>

<!-- Display cart contents -->
<div id="cart-contents">
    <?php display_cart(); ?>
</div>


<?php include 'cart.php'; ?>

// Add product to cart using PHP function
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
}


<?php
session_start();
?>


<?php
// Assuming you have these functions defined somewhere in your codebase

function add_to_cart($product_id, $quantity = 1) {
    if (isset($_SESSION['cart'])) {
        // If product already exists, update its quantity.
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity;
                return; // Exit early since we updated an existing item
            }
        }
    }

    // If product is not in the cart or you're adding a new item, append it.
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);

    // Optionally update session data to persist changes.
    session_write_close(); // Ensure write operation is closed
}

function update_cart_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id && $new_quantity != 0) {
                $item['quantity'] = $new_quantity;
                return; // Exit early since we updated an existing item
            }
        }
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                return; // Exit early since we removed an item
            }
        }
    }
}

function display_cart() {
    global $_SESSION;
    if (isset($_SESSION['cart'])) {
        echo "<h2>Your Cart:</h2>";
        foreach ($_SESSION['cart'] as $item) {
            echo $item['id'] . " x " . $item['quantity'] . "<br/>";
        }
    } else {
        echo "<p>No items in cart.</p>";
    }
}

// Usage example:
add_to_cart(1, 2); // Add 2 of product ID 1 to the cart
update_cart_quantity(1, 3); // Update quantity of product ID 1 to 3
remove_from_cart(1); // Remove product ID 1 from the cart

display_cart(); // Displays your current cart contents
?>


<?php

// Start the session if it has not been started
if (!isset($_SESSION)) {
    session_start();
}

function add_to_cart($product_id, $quantity) {
    global $_SESSION;

    // If product is already in the cart, increment its quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Otherwise, add it to the cart with its details and quantity
        $_SESSION['cart'][$product_id] = array(
            'name' => 'Product ' . $product_id,
            'price' => 9.99 * $product_id, // Sample price calculation
            'quantity' => $quantity
        );
    }
}

function view_cart() {
    global $_SESSION;

    echo "Your Cart:
";
    foreach ($_SESSION['cart'] as $product_id => $product) {
        echo "Product: {$product['name']} - Quantity: {$product['quantity']} - Price: {$product['price']} each
";
        echo "Total for this product: " . ($product['price'] * $product['quantity']) . "
";
    }
    echo "Subtotal: " . array_sum(array_column($_SESSION['cart'], 'price') * array_column($_SESSION['cart'], 'quantity')) . "
";
}

function empty_cart() {
    global $_SESSION;

    // Clear the cart
    unset($_SESSION['cart']);
}

// Example usage:
if (isset($_POST['add'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_GET['empty'])) {
    empty_cart();
}

view_cart();

?>


<?php
    session_start();
?>


// Example: Adding a product to the cart
$product_id = 1; // ID of the product being added
$quantity = 2;   // Quantity of the product

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
$_SESSION['cart'][$product_id] = $quantity;


// Example: Displaying the contents of the cart
if (isset($_SESSION['cart'])) {
    echo "Your Cart:
";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Retrieve product name from database based on $product_id
        $productName = getProductNameFromDB($product_id);
        echo "$productName - Quantity: $quantity
";
    }
} else {
    echo "Your cart is empty.";
}


// Example: Updating a product's quantity in the cart
$product_id = 1;
$new_quantity = 3;

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $new_quantity;
}


// Example: Removing a product from the cart
$product_id = 1;

if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}


// Example: Saving the cart to a database
if (isset($_SESSION['cart'])) {
    // Retrieve cart ID from database if exists, or insert new record
    $cart_id = getCartIdFromDB();
    
    // Update existing records in the 'cart_item' table for this user and cart
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        updateCartItemInDB($cart_id, $product_id, $quantity);
    }
}


// Example: Initializing the cart from a database record
$cart_id = 1; // ID of the user's current cart
    
if (isset($_SESSION['cart'])) {
    // Retrieve products in the 'cart_item' table for this user and cart
    $productsInCart = getProductsInCartFromDB($cart_id);
    
    // Initialize session cart with retrieved data
    $_SESSION['cart'] = array();
    foreach ($productsInCart as $product) {
        $_SESSION['cart'][$product->id] = $product->quantity;
    }
}


// Check if there's an existing cart in session. If not, initialize a new one.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product is already in the cart, increment its quantity.
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Otherwise, add the product to the cart with the specified quantity.
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


function remove_from_cart($product_id) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // Sort the cart array by product IDs to maintain a consistent order.
        ksort($_SESSION['cart']);
    }
}


function view_cart() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}


// Example usage
add_to_cart(1, 2); // Add item with ID 1 to the cart with quantity of 2.
add_to_cart(3, 1); // Add item with ID 3 to the cart with quantity of 1.

print_r(view_cart()); // Output: Array ( [1] => 2 [3] => 1 )

remove_from_cart(3); // Remove item with ID 3 from the cart.
print_r(view_cart()); // Output: Array ( [1] => 2 )


// Initialize the cart session
session_start();

// Check if the cart is already set in the session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Function to add items to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update the quantity of the item
            $item['quantity'] += $quantity;
            return true; // Item already in cart, updated successfully
        }
    }
    
    // Add new product to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    return false; // New item added to cart
}


// Function to update quantity of an item in the cart
function update_cart($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find the item in the cart and update its quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true; // Item found and updated successfully
        }
    }
    
    // If the item is not in the cart, add it with the new quantity
    if (!$product_id_found) {
        $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $new_quantity);
        return false; // New item added to cart
    }
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find the item in the cart and remove it
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true; // Item found and removed successfully
        }
    }
    
    return false; // Item not in cart, cannot be removed
}


// Function to display cart contents
function display_cart() {
    global $_SESSION;
    
    // Loop through each item in the cart and display its details
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product ID: {$item['id']} - Quantity: {$item['quantity']}";
        echo "<br><hr>";
    }
}


// Add an item to the cart
add_to_cart(1, 2);

// Update quantity of an existing item in the cart
update_cart(1, 3);

// Remove an item from the cart
remove_from_cart(1);

// Display cart contents
display_cart();


// cart.php

class Cart {
    private $items = array();

    public function add_item($product_id, $quantity) {
        if (!isset($this->items[$product_id])) {
            $this->items[$product_id] = array('quantity' => 0);
        }
        $this->items[$product_id]['quantity'] += $quantity;
    }

    public function remove_item($product_id) {
        if (isset($this->items[$product_id])) {
            unset($this->items[$product_id]);
        }
    }

    public function update_quantity($product_id, $new_quantity) {
        if (isset($this->items[$product_id])) {
            $this->items[$product_id]['quantity'] = $new_quantity;
        }
    }

    public function get_total() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }

    public function get_cart_contents() {
        $cart_contents = array();
        foreach ($this->items as $product_id => $item) {
            $cart_contents[] = array(
                'product_id' => $product_id,
                'quantity' => $item['quantity']
            );
        }
        return $cart_contents;
    }

    public function save() {
        // Save cart contents to session
        $_SESSION['cart'] = serialize($this->items);
    }

    public function load() {
        // Load cart contents from session
        if (isset($_SESSION['cart'])) {
            $this->items = unserialize($_SESSION['cart']);
        }
    }
}


// index.php

require_once 'cart.php';

if (!isset($_SESSION['cart'])) {
    $cart = new Cart();
    $_SESSION['cart'] = serialize($cart->items);
}

$cart = unserialize($_SESSION['cart']);


// index.php

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if ($cart->items[$product_id]) {
        $cart->update_quantity($product_id, $quantity);
    } else {
        $cart->add_item($product_id, $quantity);
    }

    $cart->save();
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    $cart->remove_item($product_id);

    $cart->save();
}


// index.php

$cart_contents = $cart->get_cart_contents();
$total = $cart->get_total();

echo '<h2>Cart Contents:</h2>';
echo '<ul>';
foreach ($cart_contents as $item) {
    echo '<li>' . $item['product_id'] . ' x ' . $item['quantity'] . '</li>';
}
echo '</ul>';

echo '<p>Total: $' . number_format($total, 2) . '</p>';


<?php
session_start();
?>


$cart = array(
    'products' => array(),
    'total_price' => 0.00,
    'tax_rate' => 0.08 // default tax rate (8%)
);


function add_to_cart($product_id, $quantity) {
    global $cart;
    
    // Check if product exists in cart
    foreach ($cart['products'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // product already exists, update quantity
        }
    }
    
    // Add new product to cart
    $cart['products'][] = array(
        'id' => $product_id,
        'name' => '', // assuming name is stored in a database or elsewhere
        'price' => 0.00, // assuming price is stored in a database or elsewhere
        'quantity' => $quantity
    );
    
    update_total_price();
}

function update_total_price() {
    global $cart;
    
    $total = 0;
    foreach ($cart['products'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    $cart['total_price'] = round($total, 2);
}


function view_cart() {
    global $cart;
    
    echo '<h2>Your Cart</h2>';
    echo '<table border="1">';
    echo '<tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>';
    foreach ($cart['products'] as $item) {
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '<td>$' . number_format($item['price'], 2) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    echo '<p>Total: $' . number_format($cart['total_price'], 2) . '</p>';
}


<?php
session_start();

// Initialize cart data structure
$cart = array(
    'products' => array(),
    'total_price' => 0.00,
    'tax_rate' => 0.08 // default tax rate (8%)
);

// Add products to cart
add_to_cart(1, 2); // add product with id 1, quantity 2
add_to_cart(2, 3); // add product with id 2, quantity 3

// View cart contents
view_cart();

// Output:
// <h2>Your Cart</h2>
// <table border="1">
//   <tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>
//   <tr><td>Product 1</td><td>2</td><td>$10.00</td></tr>
//   <tr><td>Product 2</td><td>3</td><td>$20.00</td></tr>
// </table>
// <p>Total: $60.00</p>
?>


<?php
// Start the session
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Add a product to the cart
function add_to_cart($product_id) {
    global $_SESSION;
    $product_id = (int)$product_id; // Make sure it's an integer

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'quantity' => 1,
            'price' => get_product_price($product_id)
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity']++;
    }
}

// Remove a product from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    $product_id = (int)$product_id; // Make sure it's an integer

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of a product in the cart
function update_cart($product_id, $new_quantity) {
    global $_SESSION;
    $product_id = (int)$product_id; // Make sure it's an integer

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = (int)$new_quantity;
    }
}

// Get the total cost of the cart
function get_cart_total() {
    global $_SESSION;

    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $details) {
        $total += $details['price'] * $details['quantity'];
    }

    return $total;
}

// Example usage:
add_to_cart(1);
add_to_cart(2);

echo "Cart total: $" . get_cart_total();

?>


// Start the session
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If product is already in the cart, increment its quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Otherwise, add it to the cart with a quantity of 1
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If product is in the cart, unset it
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update a quantity of an item in the cart
function update_cart($product_id, $quantity) {
    global $_SESSION;
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If product is in the cart, update its quantity
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Example usage:
add_to_cart(1, 2); // Add 2 of product with ID 1 to the cart
remove_from_cart(1); // Remove all instances of product with ID 1 from the cart
update_cart(1, 3); // Update quantity of product with ID 1 in the cart

// Print out the contents of the cart for debugging purposes:
print_r($_SESSION['cart']);


// Start the session (same as above)
session_start();

// Display a form to add items to the cart
?>
<form action="" method="post">
    <input type="hidden" name="product_id" value="1">
    <input type="text" name="quantity" placeholder="Quantity">
    <button type="submit">Add to Cart</button>
</form>

<?php
// Process form submission (if any)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

// Display the contents of the cart (same as above)
print_r($_SESSION['cart']);


<?php
// Initialize the session
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product already in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;

    // Find index of product in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            unset($item);
            return;
        }
    }

    // Remove item from cart
    foreach (array_keys($_SESSION['cart']) as $index) {
        if ($_SESSION['cart'][$index]['id'] == $product_id) {
            unset($_SESSION['cart'][$index]);
        }
    }
}

// Function to update quantity of item in cart
function update_item_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Find index of product in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }

    // Error: item not found in cart
}

// Add items to cart (example)
add_item_to_cart(1, 2);
add_item_to_cart(3, 1);

// Print contents of cart
print_r($_SESSION['cart']);

?>


// Get current products from database
$products = get_products_from_database();

foreach ($products as $product) {
    add_item_to_cart($product['id'], 1);
}

print_r($_SESSION['cart']);


session_unset();
session_destroy();


// Store cart contents in database
$cart_data = serialize($_SESSION['cart']);
save_cart_to_database($cart_data);

// Retrieve cart contents from database
$cart_data = retrieve_cart_from_database();
$_SESSION['cart'] = unserialize($cart_data);


<?php
// Start the session
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addToCart($itemId, $itemName, $itemPrice) {
    // Get current cart data from session
    $cart = $_SESSION['cart'];

    // Create new item array with quantity set to 1
    $newItem = array('id' => $itemId, 'name' => $itemName, 'price' => $itemPrice, 'quantity' => 1);

    // Add new item to cart array
    if (isset($cart[$itemId])) {
        // If item already exists in cart, increment quantity
        $cart[$itemId]['quantity']++;
    } else {
        // Add new item to cart
        $cart[$itemId] = $newItem;
    }

    // Update session with new cart data
    $_SESSION['cart'] = $cart;
}


function removeFromCart($itemId) {
    // Get current cart data from session
    $cart = $_SESSION['cart'];

    // If item exists in cart, delete it
    if (isset($cart[$itemId])) {
        unset($cart[$itemId]);
    }

    // Update session with new cart data
    $_SESSION['cart'] = $cart;
}


function displayCart() {
    // Get current cart data from session
    $cart = $_SESSION['cart'];

    // Calculate total cost
    $totalCost = 0;
    foreach ($cart as $item) {
        $totalCost += $item['price'] * $item['quantity'];
    }

    // Display cart contents and total cost
    echo "Cart Contents:<br>";
    foreach ($cart as $item) {
        echo $item['name'] . " x" . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity']) . "<br>";
    }
    echo "Total Cost: $" . number_format($totalCost);
}


// Add item to cart
addToCart(1, "Apple", 0.99);

// Remove item from cart
removeFromCart(1);

// Display cart contents
displayCart();


<?php
session_start();
?>


$cart = array(
    'items' => array(),
    'total' => 0
);


function addToCart($item_id, $quantity) {
    global $cart;
    
    // Check if the item is already in the cart
    foreach ($cart['items'] as &$item) {
        if ($item['id'] == $item_id) {
            // Update the quantity of the existing item
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add a new item to the cart
    $cart['items'][] = array(
        'id' => $item_id,
        'name' => 'Item Name', // You'll need to replace this with your actual item name
        'price' => 19.99, // You'll need to replace this with your actual price
        'quantity' => $quantity
    );
    
    // Update the total cost of the cart
    $cart['total'] += ($item_id == 'example1') ? 9.99 : ($item_id == 'example2') ? 14.99 : 19.99;
}


function removeFromCart($item_id) {
    global $cart;
    
    foreach ($cart['items'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($cart['items'][$key]);
            return;
        }
    }
}


function updateCartTotal() {
    global $cart;
    
    foreach ($cart['items'] as &$item) {
        $cart['total'] += $item['price'] * $item['quantity'];
    }
}


function displayCart() {
    global $cart;
    
    echo 'Cart Total: $' . number_format($cart['total'], 2) . '<br>';
    
    foreach ($cart['items'] as &$item) {
        echo "Item Name: " . $item['name'] . ", Price: $" . number_format($item['price'], 2) . ", Quantity: " . $item['quantity'] . "<br>";
    }
}


<?php
session_start();

// Add items to the cart
addToCart('example1', 2);
addToCart('example2', 3);

// Update the cart total
updateCartTotal();

// Display the cart
displayCart();
?>


// Set the session start
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add a new item to the cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Add a product to the cart (example)
add_to_cart(1, 2);

// Print out the contents of the cart
print_r($_SESSION['cart']);


// Include the cart script
include 'cart.php';

// Display the cart contents
print_r($_SESSION['cart']);

// Add a product to the cart using a form
?>
<form action="" method="post">
    <input type="hidden" name="product_id" value="1">
    <input type="number" name="quantity" value="2">
    <button type="submit">Add to Cart</button>
</form>

<?php

// Process the form submission (example)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

// Display the cart contents again
print_r($_SESSION['cart']);


// Cart initialization (assuming you haven't already)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    global $conn; // Assuming a database connection is established

    if ($quantity < 1 || !is_numeric($quantity)) {
        echo "Invalid quantity";
        return;
    }

    // Check if the product already exists in cart to avoid duplication
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $product_id) {
            // Increase quantity instead of adding duplicate item
            $product['quantity'] += $quantity;
            break; // Exit loop since we found and updated the product
        }
    }

    // If not found, add it to cart with its initial quantity
    if (!isset($_SESSION['cart'][$product_id])) {
        $query = "SELECT * FROM products WHERE id='$product_id'";
        $result = mysqli_query($conn, $query);
        $product_data = mysqli_fetch_assoc($result);

        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => $product_data['name'],
            'price' => $product_data['price'],
            'quantity' => $quantity
        );
    }
}

function view_cart() {
    global $conn;

    // Simple display of cart contents for demonstration
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo "Product: " . $item['name'] . ", Quantity: " . $item['quantity'];
        echo ", Price per item: $" . $item['price'] . " (Total: $" . ($item['quantity'] * $item['price']) . ")<br>";
    }
}

// Example usage
add_to_cart(1, 2); // Add product with ID 1 in quantity of 2 to cart
view_cart();


<?php
session_start();

// Define the products array
$products = array(
    "product1" => array("name" => "Product 1", "price" => 9.99),
    "product2" => array("name" => "Product 2", "price" => 19.99),
    "product3" => array("name" => "Product 3", "price" => 29.99)
);

// If the cart is not set in the session, create it
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

// Add a product to the cart
function add_to_cart($product_id) {
    global $products;
    global $_SESSION;

    if (in_array($product_id, $_SESSION["cart"])) {
        return false; // Product is already in the cart
    }

    $product = $products[$product_id];
    array_push($_SESSION["cart"], array("id" => $product_id, "name" => $product["name"], "price" => $product["price"]));

    return true;
}

// Remove a product from the cart
function remove_from_cart($product_id) {
    global $_SESSION;

    foreach ($_SESSION["cart"] as $key => $value) {
        if ($value["id"] == $product_id) {
            unset($_SESSION["cart"][$key]);
            return true; // Product removed successfully
        }
    }

    return false;
}

// Display the cart contents
function display_cart() {
    global $_SESSION;

    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION["cart"] as $item) {
        echo "Product: " . $item["name"] . ", Price: $" . $item["price"];
        echo " <a href='remove.php?product_id=" . $item["id"] . "'>(Remove)</a><br>";
    }
}

// Display the cart total
function display_total() {
    global $_SESSION;

    $total = 0;
    foreach ($_SESSION["cart"] as $item) {
        $total += $item["price"];
    }

    echo "<h2>Total: $" . number_format($total, 2) . "</h2>";
}
?>


<?php
session_start();
include "cart.php";

$product_id = $_GET["product_id"];

if (remove_from_cart($product_id)) {
    header("Location: cart.php");
    exit;
}

echo "Error removing product from cart.";
?>


<?php
include "cart.php";

// Add products to the cart
add_to_cart("product1");
add_to_cart("product2");

display_cart();
display_total();

// Remove a product from the cart
remove_from_cart("product1");

display_cart();
display_total();
?>


session_start();


$cart = array();


if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product is already in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // If it's there, increment quantity
            $item['quantity'] += $quantity;
            break;  // Stop looking once found
        }
    }

    // If not found, add new item to cart
    else {
        $cart[] = array(
            'id' => $product_id,
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'quantity' => $quantity
        );
    }
}


// Sample loop for displaying cart items:
foreach ($cart as $item) {
    echo "Product ID: $item[id], Name: $item[name], Price: $" . number_format($item['price'], 2) . ", Quantity: $item[quantity]<br>";
}


session_start();

$cart = array();

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            break;  
        }
    }

    else {
        $cart[] = array(
            'id' => $product_id,
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'quantity' => $quantity
        );
    }
}

// Displaying cart contents:
echo "Your Cart:<br>";
foreach ($cart as $item) {
    echo "Product ID: $item[id], Name: $item[name], Price: $" . number_format($item['price'], 2) . ", Quantity: $item[quantity]<br>";
}


// Start session management
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


<form action="" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>


if (isset($_POST['add_to_cart'])) {
    // Get product ID from form data
    $product_id = $_POST['product_id'];
    
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity of existing item
            $item['quantity']++;
            break;
        }
    }
    
    // If product doesn't exist, add it to the cart with a quantity of 1
    if (!isset($item)) {
        $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
    }
}


<table>
    <tr>
        <th>Product ID</th>
        <th>Quantity</th>
    </tr>
    <?php foreach ($_SESSION['cart'] as $item) : ?>
    <tr>
        <td><?php echo $item['id']; ?></td>
        <td><?php echo $item['quantity']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>


<?php
// Start session management
session_start();

// Check if cart is already set in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['add_to_cart'])) {
    // Get product ID from form data
    $product_id = $_POST['product_id'];
    
    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment quantity of existing item
            $item['quantity']++;
            break;
        }
    }
    
    // If product doesn't exist, add it to the cart with a quantity of 1
    if (!isset($item)) {
        $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
    }
}
?>

<form action="" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

<table>
    <tr>
        <th>Product ID</th>
        <th>Quantity</th>
    </tr>
    <?php foreach ($_SESSION['cart'] as $item) : ?>
    <tr>
        <td><?php echo $item['id']; ?></td>
        <td><?php echo $item['quantity']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>


// Start the session if it hasn't been started yet.
if (!isset($_SESSION)) {
    session_start();
}


// Initialize session variables for the cart if they don't exist.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (!isset($_SESSION['total_items'])) {
    $_SESSION['total_items'] = 0;
}

if (!isset($_SESSION['total_cost'])) {
    $_SESSION['total_cost'] = 0.00; // Initialize with a float value.
}


function addItemToCart($id, $name, $price) {
    global $_SESSION;
    
    // Check if the product is already in the cart.
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity']++;
            return; // Product already exists, increment its quantity.
        }
    }
    
    // Add new item to the cart.
    $_SESSION['cart'][] = array(
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => 1
    );
    
    // Update total items and cost.
    $_SESSION['total_items']++;
    $_SESSION['total_cost'] += $price;
}


// Example usage
addItemToCart(1, "Product A", 10.99);
addItemToCart(2, "Product B", 5.49);

echo "Total Items: " . $_SESSION['total_items'];
echo "
";
echo "Total Cost: $" . number_format($_SESSION['total_cost'], 2); // Display total cost with two decimal places.


function removeItemFromCart($id) {
    global $_SESSION;
    
    // Find the index of the product in the cart array.
    $index = -1; // Default if not found
    foreach ($_SESSION['cart'] as $k => $item) {
        if ($item['id'] == $id) {
            $index = $k;
            break;
        }
    }
    
    // If the product exists, remove it from the cart.
    if ($index != -1) {
        unset($_SESSION['cart'][$index]);
        
        // Update total items and cost.
        $_SESSION['total_items']--;
        $_SESSION['total_cost'] -= $_SESSION['cart'][$index]['price'];
        
        // Since we use array_key_exists to remove, if no keys were removed (ie. the product was not found), there's nothing left in cart that could affect total_cost and items so no further code is needed
    }
}


function updateItemQuantity($id, $newQuantity) {
    global $_SESSION;
    
    // Find the product in the cart and update its quantity.
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $newQuantity;
            
            // Update total cost based on new quantity.
            $_SESSION['total_cost'] -= $item['price'] * ($newQuantity - 1);
            $_SESSION['total_items'] += $newQuantity - 1;
            break;
        }
    }
}


function displayCart() {
    global $_SESSION;
    
    echo "Your Cart:
";
    foreach ($_SESSION['cart'] as $item) {
        echo "ID: {$item['id']} - Name: {$item['name']} - Price: \$${$item['price']} x {$item['quantity']} = \$${(float)$item['price'] * (int)$item['quantity']}
";
    }
    
    echo "
Total Items: ".$_SESSION['total_items'];
    echo "
Total Cost: $" . number_format($_SESSION['total_cost'], 2);
}


<?php

// Starting the Session
session_start();

// Cart is not initialized yet. Let's create it.
if (!isset($_SESSION['cart'])) {
    // Initialize an empty array to hold products in the cart
    $_SESSION['cart'] = array();
}

// Example functions for managing your cart

function addProductToCart($productId, $quantity) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function removeProductFromCart($productId) {
    global $_SESSION;
    unset($_SESSION['cart'][$productId]);
}

// Example usage:
addProductToCart(1, 3); // Adds product with ID 1 to cart in quantity of 3.
removeProductFromCart(2); // Removes product with ID 2 from the cart.

// Displaying Cart Contents
function displayCartContents() {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        echo "Product ID: $productId, Quantity: $quantity<br>";
    }
}

displayCartContents();

?>


session_start();


// Initialize session if not already started
if (!isset($_SESSION)) {
    session_start();
}

function addItemToCart($productId, $productName, $quantity) {
    // Check if product is already in cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // If yes, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // If not, add it to the cart with given details
        $_SESSION['cart'][$productId] = array('name' => $productName, 'price' => 0, 'quantity' => $quantity);
    }
}

function removeItemFromCart($id) {
    if (array_key_exists($id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$id]);
    } else {
        // You might want to handle this case based on your application logic
    }
}

function updateCartItemQuantity($id, $newQuantity) {
    if (array_key_exists($id, $_SESSION['cart'])) {
        $_SESSION['cart'][$id]['quantity'] = $newQuantity;
    }
}

function emptyCart() {
    unset($_SESSION['cart']);
}

// Example usage:
if (isset($_POST['add_to_cart'])) {
    // Assuming POST is used to send product details
    addItemToCart($_POST['product_id'], $_POST['product_name'], 1);
} elseif (isset($_POST['remove_from_cart'])) {
    removeItemFromCart($_POST['id']);
} elseif (isset($_POST['update_quantity'])) {
    updateCartItemQuantity($_POST['id'], $_POST['new_quantity']);
}

// Display cart
if (!empty($_SESSION['cart'])) {
    echo '<h2>Shopping Cart</h2>';
    foreach ($_SESSION['cart'] as $id => $item) {
        echo 'Item: <strong>' . $item['name'] . '</strong>, Quantity: ' . $item['quantity'];
        if (array_key_exists('price', $_SESSION['cart'][$id])) {
            echo ', Price: <strong>€' . number_format($_SESSION['cart'][$id]['price'], 2) . '</strong>';
        }
        echo '<br>';
    }
} else {
    echo 'Your cart is empty.';
}


<?php
session_start();

// Check if the cart is already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($item_id, $quantity) {
    global $_SESSION;
    
    // Ensure the item ID and quantity are valid
    if ($item_id && is_numeric($quantity)) {
        // Get the current cart items
        $items_in_cart = $_SESSION['cart'];
        
        // Check if the item is already in the cart
        foreach ($items_in_cart as $key => $value) {
            if ($value['id'] == $item_id) {
                // If it's already in the cart, increment its quantity
                $items_in_cart[$key]['quantity'] += $quantity;
                
                // Store the updated cart back into the session
                $_SESSION['cart'] = $items_in_cart;
                
                return true; // Item added successfully
            }
        }
        
        // If it's not in the cart, add a new item
        $new_item = array(
            'id' => $item_id,
            'quantity' => $quantity
        );
        
        // Add the new item to the cart and store it back into the session
        $_SESSION['cart'][] = $new_item;
    }
    
    return false; // Item not added successfully
}

// Remove item from cart
function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    // Ensure the item ID is valid
    if ($item_id && is_numeric($item_id)) {
        // Get the current cart items
        $items_in_cart = $_SESSION['cart'];
        
        // Find and remove the item from the cart
        foreach ($items_in_cart as $key => $value) {
            if ($value['id'] == $item_id) {
                unset($items_in_cart[$key]);
                
                // Store the updated cart back into the session
                $_SESSION['cart'] = $items_in_cart;
                
                return true; // Item removed successfully
            }
        }
    }
    
    return false; // Item not found in cart
}

// Display current items in cart
function display_items_in_cart() {
    global $_SESSION;
    
    echo '<h2>Cart Contents:</h2>';
    
    foreach ($_SESSION['cart'] as $item) {
        echo 'Item ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}

// Example usage:
add_item_to_cart(1, 2); // Add item with ID 1 and quantity 2
display_items_in_cart(); // Display current items in cart

?>


<?php require_once 'cart.php'; ?>


<?php

// Initialize the cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>


function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Update the existing item's quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add a new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Find the index of the item to be removed
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function update_cart_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find the index of the item to be updated
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}


<?php

require 'cart.php';

// Add products to cart
add_to_cart(1, 2); // Product ID 1 with quantity 2
add_to_cart(2, 3); // Product ID 2 with quantity 3

// Remove a product from cart
remove_from_cart(1);

// Update the quantity of a product in cart
update_cart_quantity(2, 4);

// Print the cart contents
print_r($_SESSION['cart']);

?>


// cart.php

session_start(); // Start the session


// add_to_cart.php

require 'cart.php';

// Get the item ID and quantity from the request data
$item_id = $_POST['item_id'];
$quantity = $_POST['quantity'];

// Check if the item is already in the cart
if (isset($_SESSION['cart'][$item_id])) {
    // Increment the quantity
    $_SESSION['cart'][$item_id] += $quantity;
} else {
    // Add the item to the cart with its quantity
    $_SESSION['cart'][$item_id] = $quantity;
}

// Redirect to the cart page or update the cart HTML
header('Location: cart.php');
exit();


// cart.php

require 'cart.php';

// Get the cart items from the session
$cart = $_SESSION['cart'];

// Calculate the total cost
$total_cost = 0;
foreach ($cart as $item_id => $quantity) {
    // Assume we have a function to get the item price
    $price = getItemPrice($item_id);
    $total_cost += $price * $quantity;
}

?>

<!-- Display the cart items -->
<h1>Cart</h1>
<ul>
    <?php foreach ($cart as $item_id => $quantity): ?>
        <li><?= getItemName($item_id) ?> x <?= $quantity ?></li>
    <?php endforeach; ?>
</ul>

<!-- Display the total cost -->
<p>Total: $<?= $total_cost ?></p>

<!-- Add a button to clear the cart -->
<button onclick="clearCart()">Clear Cart</button>

<script>
function clearCart() {
    // Clear the cart using AJAX
    $.ajax({
        type: 'POST',
        url: 'clear_cart.php',
        success: function () {
            window.location.reload();
        }
    });
}
</script>


// clear_cart.php

require 'cart.php';

// Remove all items from the cart
unset($_SESSION['cart']);

// Redirect to the cart page
header('Location: cart.php');
exit();


<?php
session_start();

// Check if the cart session exists, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
// Define the item details (product ID, name, price)
$item_id = 1;
$item_name = 'Product A';
$item_price = 19.99;

// Add the item to the cart session array
$_SESSION['cart'][$item_id] = array(
    'name' => $item_name,
    'price' => $item_price,
    'quantity' => 1 // default quantity is 1
);

// You can also update the quantity of an existing item in the cart
// $_SESSION['cart'][$item_id]['quantity'] += 1;
?>


<?php
// Loop through the cart items
foreach ($_SESSION['cart'] as $item_id => $item) {
    echo "Item ID: $item_id";
    echo "Name: $item[name]";
    echo "Price: $" . number_format($item['price'], 2);
    echo "Quantity: $item[quantity]<br>";
}
?>


<?php
// Remove an item from the cart
unset($_SESSION['cart'][$item_id]);

// Update the quantity of an existing item in the cart
$_SESSION['cart'][$item_id]['quantity'] += 1;

// You can also add new items to the cart
// _SESSION['cart'][$new_item_id] = array('name' => 'Product B', 'price' => 9.99, 'quantity' => 2);
?>


<?php
session_start();


   // Assuming $product_id is the ID of the item being added, 
   // $product_name is its name, and $price is its price.
   if (isset($_POST['add_to_cart'])) {
       if (!isset($_SESSION['cart'])) {
           $_SESSION['cart'] = array();
       }
       // Check if the product is already in the cart to avoid duplicates
       foreach ($_SESSION['cart'] as $item) {
           if ($item['product_id'] == $product_id) {
               $index = array_search($product_id, array_column($_SESSION['cart'], 'product_id'));
               $_SESSION['cart'][$index]['quantity']++;
               break;
           }
       }

       // If the product is not in the cart or was just added
       if (!isset($index)) {
           $_SESSION['cart'][] = array(
               'product_id' => $product_id,
               'name' => $product_name,
               'price' => $price,
               'quantity' => 1 // Default quantity
           );
       }
   }
   

2. **Viewing Cart Contents**

   To display the items in the cart, you can loop through `$_SESSION['cart']` and echo out each item's details:

   
   if (isset($_SESSION['cart'])) {
       foreach ($_SESSION['cart'] as $item) {
           echo "Product ID: {$item['product_id']} | Name: {$item['name']} | Price: {$item['price']} | Quantity: {$item['quantity']}<br>";
       }
   } else {
       echo "Your cart is empty.";
   }
   

3. **Removing Items from Cart**

   For simplicity, let's add a button to remove an item:

   
   if (isset($_POST['remove'])) {
       $id = $_POST['product_id'];
       foreach ($_SESSION['cart'] as $index => $item) {
           if ($item['product_id'] == $id) {
               unset($_SESSION['cart'][$index]);
               break;
           }
       }

       // Re-index the cart array after removing an item
       $_SESSION['cart'] = array_values($_SESSION['cart']);
   }
   

4. **Updating Cart Quantity**

   And, to update the quantity of a product:

   
   if (isset($_POST['update'])) {
       $id = $_POST['product_id'];
       foreach ($_SESSION['cart'] as &$item) {
           if ($item['product_id'] == $id) {
               // Ensure we don't go below 0
               $quantity = max(1, $_POST['new_quantity']);
               $item['quantity'] = $quantity;
               break;
           }
       }
   }
   

This is a basic example of how you can implement a user cart session using PHP. Depending on your application's needs, you may want to include more features (like total cost calculation, form validation for quantity updates, etc.).

Here's a complete example that integrates all the above code:



<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Increment the quantity of the product
        $_SESSION['cart'][$product_id]['quantity']++;
        $_SESSION['cart'][$product_id]['total_price'] += $product_price;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1,
            'total_price' => $product_price
        );
    }
}
?>


<?php
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Update quantity and total price
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['price'] * $new_quantity;
    }
}
?>


<?php
foreach ($_SESSION['cart'] as $item) {
    echo $item['name'] . ' x ' . $item['quantity'] . ' = ' . $item['total_price'] . '<br>';
}
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add items to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Increment the quantity of the product
        $_SESSION['cart'][$product_id]['quantity']++;
        $_SESSION['cart'][$product_id]['total_price'] += $product_price;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1,
            'total_price' => $product_price
        );
    }
}

// Update cart session on quantity changes
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Update quantity and total price
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['price'] * $new_quantity;
    }
}

// Display cart contents
foreach ($_SESSION['cart'] as $item) {
    echo $item['name'] . ' x ' . $item['quantity'] . ' = ' . $item['total_price'] . '<br>';
}
?>


<?php
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add an item to the cart
function add_item($item_id, $quantity) {
    global $_SESSION;
    if (in_array($item_id, $_SESSION['cart']['items'])) {
        // Item already in cart, increment quantity
        foreach ($_SESSION['cart']['items'] as &$item) {
            if ($item['id'] == $item_id) {
                $item['quantity'] += $quantity;
                break;
            }
        }
    } else {
        // New item, add to cart
        $_SESSION['cart']['items'][] = array('id' => $item_id, 'quantity' => $quantity);
    }
}

// Remove an item from the cart
function remove_item($item_id) {
    global $_SESSION;
    foreach ($_SESSION['cart']['items'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart']['items'][$key]);
            break;
        }
    }
}

// Update quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart']['items'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

// Display the cart contents
function display_cart() {
    global $_SESSION;
    echo "Your Cart Contents:<br>";
    foreach ($_SESSION['cart']['items'] as $item) {
        echo "$item[quantity] x Item #{$item[id]}<br>";
    }
    echo "<p>Subtotal: " . calculate_subtotal() . "</p>";
}

// Calculate the subtotal of the cart
function calculate_subtotal() {
    global $_SESSION;
    $subtotal = 0;
    foreach ($_SESSION['cart']['items'] as $item) {
        $subtotal += $item['quantity'];
    }
    return $subtotal;
}
?>


<?php
require_once 'cart.php';

// Start the session
session_start();

// Add some items to the cart
add_item(1, 2);
add_item(2, 3);

// Display the cart contents
display_cart();
?>


session_start();


function addToCart($itemId, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($itemId, $_SESSION['cart'])) {
        $_SESSION['cart'][$itemId] += $quantity;
    } else {
        $_SESSION['cart'][$itemId] = $quantity;
    }
}


function removeFromCart($itemId) {
    if (array_key_exists($itemId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$itemId]);
    }
}


function updateCartQuantity($itemId, $quantity) {
    if (array_key_exists($itemId, $_SESSION['cart'])) {
        $_SESSION['cart'][$itemId] = $quantity;
    }
}


function displayCart() {
    if (isset($_SESSION['cart'])) {
        echo '<h2>Cart Contents:</h2>';
        foreach ($_SESSION['cart'] as $itemId => $quantity) {
            echo "Item ID: $itemId, Quantity: $quantity<br>";
        }
    } else {
        echo 'Your cart is empty!';
    }
}


// Add items to cart
addToCart(1, 2); // Item ID 1, quantity 2
addToCart(2, 3); // Item ID 2, quantity 3

// Display cart contents
displayCart();

// Remove item from cart
removeFromCart(2);

// Update item quantity
updateCartQuantity(1, 5);


<?php
session_start();

function addToCart($itemId, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if (array_key_exists($itemId, $_SESSION['cart'])) {
        $_SESSION['cart'][$itemId] += $quantity;
    } else {
        $_SESSION['cart'][$itemId] = $quantity;
    }
}

function removeFromCart($itemId) {
    if (array_key_exists($itemId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$itemId]);
    }
}

function updateCartQuantity($itemId, $quantity) {
    if (array_key_exists($itemId, $_SESSION['cart'])) {
        $_SESSION['cart'][$itemId] = $quantity;
    }
}

function displayCart() {
    if (isset($_SESSION['cart'])) {
        echo '<h2>Cart Contents:</h2>';
        foreach ($_SESSION['cart'] as $itemId => $quantity) {
            echo "Item ID: $itemId, Quantity: $quantity<br>";
        }
    } else {
        echo 'Your cart is empty!';
    }
}

// Example usage
addToCart(1, 2); // Item ID 1, quantity 2
addToCart(2, 3); // Item ID 2, quantity 3

displayCart();

removeFromCart(2);

updateCartQuantity(1, 5);
?>


<?php
session_start();
?>


function addToCart($itemId, $quantity) {
    if (isset($_SESSION['cart'])) {
        // If cart is already set, append new item
        $_SESSION['cart'][] = array('id' => $itemId, 'quantity' => $quantity);
    } else {
        // If cart doesn't exist, create it with the new item
        $_SESSION['cart'] = array(array('id' => $itemId, 'quantity' => $quantity));
    }
}


function getCartContents() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return array();
    }
}


function updateCartQuantity($itemId, $newQuantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $itemId) {
                $item['quantity'] = $newQuantity;
                break;
            }
        }
    } else {
        echo "Cart is empty";
    }
}


// Assuming we have a database or data source with product information
$productIds = array(1, 2, 3);

foreach ($productIds as $productId) {
    addToCart($productId, 1);
}

$cartContents = getCartContents();
print_r($cartContents); // Output: Array ( [0] => Array ( [id] => 1 [quantity] => 1 ) [1] => Array ( [id] => 2 [quantity] => 1 ) [2] => Array ( [id] => 3 [quantity] => 1 ) )

updateCartQuantity(1, 5);
$cartContents = getCartContents();
print_r($cartContents); // Output: Array ( [0] => Array ( [id] => 1 [quantity] => 5 ) [1] => Array ( [id] => 2 [quantity] => 1 ) [2] => Array ( [id] => 3 [quantity] => 1 ) )


<?php
session_start();
?>


// Initialize an empty array for the cart products
$cart = array();

// Initialize session variable for cart
$_SESSION['cart'] = $cart;


function add_to_cart($product_id, $quantity) {
    global $_SESSION;

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item[0] == $product_id) {
            // If it's already in the cart, increment its quantity
            $item[1] += $quantity;
            return true; // Product was found and updated
        }
    }

    // If not, add it to the cart
    $_SESSION['cart'][] = array($product_id, $quantity);
    return true; // Product added successfully
}


function remove_from_cart($product_id) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item[0] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true; // Product was removed successfully
        }
    }

    return false; // Product not found in cart
}


function update_quantity($product_id, $new_quantity) {
    global $_SESSION;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item[0] == $product_id) {
            $item[1] = $new_quantity;
            return true; // Quantity updated successfully
        }
    }

    return false; // Product not found in cart
}


function display_cart() {
    global $_SESSION;

    $output = '<h2>Cart</h2><ul>';
    foreach ($_SESSION['cart'] as $item) {
        $output .= '<li>' . $item[0] . ' x ' . $item[1] . '</li>';
    }
    $output .= '</ul>';

    return $output;
}


<?php

// Start session
session_start();

// Define cart variables
$cart = array();
$_SESSION['cart'] = $cart;

// Add products to cart
add_to_cart(1, 2); // Product ID 1 x 2
add_to_cart(2, 3); // Product ID 2 x 3

// Display cart
echo display_cart();

?>


<?php
session_start();

// If cart is empty, create an array to store items in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function addToCart($product_id, $quantity) {
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increase quantity of existing item
            $item['quantity'] += $quantity;
            return; // Product already exists, no need to add again
        }
    }

    // Add new product to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}


function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        return;
    }

    echo "<h2>Cart Contents:</h2><table border='1'>";
    echo "<tr><th>Product ID</th><th>Quantity</th></tr>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>$item[id]</td>";
        echo "<td>$item[quantity]</td>";
        // Add a button to update the quantity here, or use an AJAX request
        echo "</tr>";
    }

    echo "</table>";

    // For demonstration purposes, we'll add a simple update quantity form
    foreach ($_SESSION['cart'] as $i => &$item) {
        ?>
        <form action="" method="post">
            <input type="hidden" name="product_id[]" value="<?php echo $item['id']; ?>">
            <label>Quantity:</label>
            <select name="quantity[]">
                <?php
                for ($j = 1; $j <= 10; $j++) {
                    ?>
                    <option value="<?php echo $j; ?>" <?php if ($j == $item['quantity']) echo "selected"; ?>>
                        <?php echo $j; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
            <input type="submit" name="update_cart" value="Update Quantity">
        </form>
        <?php
    }
}


if (isset($_POST['update_cart'])) {
    // Handle update request here (e.g., update session 'cart' array)
    foreach ($_POST['product_id'] as $i => $pid) {
        $_SESSION['cart'][$i]['quantity'] = $_POST['quantity'][$i];
        echo "Updated quantity of product $pid to ".$_POST['quantity'][$i].".";
    }
}


<?php require_once('path/to/your/script.php'); ?>

<!-- Form to add products to cart -->
<form action="" method="post">
    <input type="hidden" name="product_id[]" value="<?php echo 1; ?>">
    <label>Quantity:</label>
    <select name="quantity[]">
        <?php
        for ($j = 1; $j <= 10; $j++) {
            ?>
            <option value="<?php echo $j; ?>"><?php echo $j; ?></option>
            <?php
        }
        ?>
    </select>
    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

<?php displayCart(); ?>

<?php if (isset($_POST['update_cart'])) {
    // Update cart logic here
}
?>


// Set up the basic structure for the cart
session_start(); // Start or continue the session

function init_cart() {
    global $cart;
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
}

function add_to_cart($product_id, $quantity) {
    global $cart;

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

function view_cart() {
    global $cart;

    // For the sake of simplicity, we'll just echo out what's in the cart
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $product_id => $details) {
        echo 'Product ID: ' . $product_id . ', Quantity: ' . $details['quantity'] . '<br>';
    }
}

init_cart(); // Initialize the cart

// Example of adding items to the cart
if (isset($_POST['add'])) {
    add_to_cart($_POST['product_id'], $_POST['quantity']);
}

?>

<form action="" method="post">
    <input type="hidden" name="product_id" value="12345"> <!-- Example product ID -->
    <input type="text" name="quantity" placeholder="Quantity">
    <button type="submit" name="add">Add to Cart</button>
</form>

<?php
view_cart(); // Display the cart contents
?>


<?php

// Start the session
session_start();

// Function to add item to cart
function addToCart($itemId, $itemName, $itemPrice) {
    global $cart;

    // If the item is not in the cart, add it with a quantity of 1.
    if (!isset($GLOBALS['cart'][$itemId])) {
        $GLOBALS['cart'][$itemId] = array('name' => $itemName, 'price' => $itemPrice, 'quantity' => 1);
    } else {
        // If the item is already in the cart, increment its quantity.
        $GLOBALS['cart'][$itemId]['quantity']++;
    }
}

// Function to update a specific item's quantity
function updateQuantity($itemId, $newQuantity) {
    global $cart;

    if (isset($cart[$itemId])) {
        $cart[$itemId]['quantity'] = $newQuantity;
    } else {
        echo "Item not found in cart.";
    }
}

// Function to remove an item from the cart
function removeFromCart($itemId) {
    global $cart;

    if (isset($cart[$itemId])) {
        unset($cart[$itemId]);
    }
}

// Function to empty the cart
function emptyCart() {
    global $cart;
    
    $cart = array();
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming this is an AJAX request or a form post for adding items to the cart
    
    if (isset($_POST['action']) && $_POST['action'] == "add") {
        addToCart($_POST['itemId'], $_POST['itemName'], $_POST['itemPrice']);
        
        // You might want to save this session data at some point, especially when a user clicks 'checkout'.
    } elseif ($_POST['action'] == "update") {
        updateQuantity($_POST['itemId'], $_POST['newQuantity']);
    } elseif ($_POST['action'] == "remove") {
        removeFromCart($_POST['itemId']);
    }
}

// Display cart
?>
<ul>
<?php foreach ($cart as $item => $details): ?>
    <li>Item: <?= $details['name']; ?>, Price: <?= $details['price']; ?>, Quantity: <?= $details['quantity']; ?></li>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="itemId" value="<?= $item; ?>">
        <input type="number" name="newQuantity" value="<?= $details['quantity']; ?>">
        <button type="submit">Update Quantity</button>
    </form>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="action" value="remove">
        <input type="hidden" name="itemId" value="<?= $item; ?>">
        <button type="submit">Remove from Cart</button>
    </form>
<?php endforeach; ?>
</ul>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="action" value="add">
    <input type="text" name="itemName" placeholder="Item Name">
    <input type="number" step="0.01" name="itemPrice" placeholder="Item Price">
    <button type="submit">Add to Cart</button>
</form>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="action" value="emptyCart">
    <button type="submit">Empty Cart</button>
</form>


<?php
session_start();
?>


<?php
// Assuming $product_id and $quantity are set from your form submission
if (isset($_POST['submit'])) {
    // Retrieve product information
    // For simplicity, we'll assume products exist in an array ($products)
    if (isset($products[$_POST['product_id']])) {
        // Update quantity in session cart or add item if it doesn't exist yet
        if (!isset($_SESSION['cart'][$_POST['product_id']])) {
            $_SESSION['cart'][$_POST['product_id']] = array('quantity' => $_POST['quantity'], 'price' => $products[$_POST['product_id']]['price']);
        } else {
            $_SESSION['cart'][$_POST['product_id']]['quantity'] += $_POST['quantity'];
        }
    }
}
?>


<?php
// Example of displaying the current session cart
echo "<h2>Your Shopping Cart</h2>";
if (isset($_SESSION['cart'])) {
    $total = 0;
    echo "<table border='1'>";
    foreach ($_SESSION['cart'] as $id => $item) {
        $subtotal = $item['quantity'] * $products[$id]['price'];
        echo "
          <tr>
            <td>$id</td>
            <td>$product_names[$id]</td>
            <td>" . $_SESSION['cart'][$id]['quantity'] . "</td>
            <td>$". number_format($subtotal, 2) ."</td>
          </tr>";
        $total += $subtotal;
    }
    echo "
      <tr>
        <td colspan='3'>Total:</td>
        <td>$". number_format($total, 2) ."</td>
      </tr>";
    echo "</table>";
}
?>


<?php
session_start();

// Sample product data for demonstration purposes.
$products = array(
    1 => array('name' => 'Product A', 'price' => 15.99),
    2 => array('name' => 'Product B', 'price' => 9.99)
);

// Assuming you have a form with inputs named product_id and quantity
if (isset($_POST['submit'])) {
    // Code for adding to cart as shown above
}

// Display products in the HTML form
?>
<html>
<head>
    <title>Shopping Cart Example</title>
</head>
<body>
    <h2>Select Products:</h2>
    <?php foreach ($products as $id => $product): ?>
        <form action="" method="post">
            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
            Quantity: <input type="number" name="quantity"><br>
            <button type="submit">Add to Cart</button><br>
        </form>
    <?php endforeach; ?>
    
    <!-- Display the current session cart -->
    <?php
    // Example of displaying the current session cart
    echo "<h2>Your Shopping Cart</h2>";
    if (isset($_SESSION['cart'])) {
        $total = 0;
        echo "<table border='1'>";
        foreach ($_SESSION['cart'] as $id => $item) {
            $subtotal = $item['quantity'] * $products[$id]['price'];
            echo "
              <tr>
                <td>$id</td>
                <td>$product_names[$id]</td>
                <td>" . $_SESSION['cart'][$id]['quantity'] . "</td>
                <td>$". number_format($subtotal, 2) ."</td>
              </tr>";
            $total += $subtotal;
        }
        echo "
          <tr>
            <td colspan='3'>Total:</td>
            <td>$". number_format($total, 2) ."</td>
          </tr>";
        echo "</table>";
    }
    ?>
</body>
</html>


<?php
session_start();

// Check if the cart session already exists
if (!isset($_SESSION['cart'])) {
    // If not, create a new cart session array
    $_SESSION['cart'] = array();
}

// Add an item to the cart
function add_item_to_cart($product_id) {
    global $db;
    
    // Get the product details from the database
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db, $query);
    $product = mysqli_fetch_assoc($result);
    
    // Add the product to the cart session array
    $_SESSION['cart'][$product_id] = array(
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    );
}

// Display the cart contents
function display_cart_contents() {
    global $_SESSION;
    
    // Get the product IDs from the cart session array
    $product_ids = array_keys($_SESSION['cart']);
    
    // Loop through each product ID and display its details
    foreach ($product_ids as $product_id) {
        echo "Product Name: {$_SESSION['cart'][$product_id]['name']} <br>";
        echo "Price: {$_SESSION['cart'][$product_id]['price']} <br>";
        echo "Quantity: {$_SESSION['cart'][$product_id]['quantity']} <br><hr>";
    }
}

// Add an item to the cart
add_item_to_cart(1);

// Display the cart contents
display_cart_contents();

?>


<?php
session_start();
require_once 'config.php';

// Get the product ID and quantity from the form data
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Update the cart session array with the new quantity
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
}

header('Location: index.php');
exit();
?>


<?php
session_start();

// Get the product ID from the URL parameter
$product_id = $_GET['id'];

// Remove the product from the cart session array
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

header('Location: index.php');
exit();
?>


<?php
$db = mysqli_connect('localhost', 'username', 'password', 'database_name');

// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


// session_start() must be called before outputting anything to the browser.
session_start();

if (!isset($_SESSION['cart'])) {
    // If there is no 'cart' key in the $_SESSION array, we create it as an empty array.
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addToCart($product_id, $quantity) {
    global $_SESSION;
    
    if (!isset($_SESSION['cart'][$product_id])) {
        // If product is not in the cart yet, we start with a quantity of 0.
        $_SESSION['cart'][$product_id] = 0;
    }
    
    // We update the quantity of the item in the cart.
    $_SESSION['cart'][$product_id] += $quantity;
}

// Function to remove item from cart
function removeFromCart($product_id) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        // Remove the key from the session array. 
        unset($_SESSION['cart'][$product_id]);
        
        // We sort and display the updated cart.
        sort($_SESSION['cart']);
    }
}

// Function to update item quantity in cart
function updateQuantity($product_id, $new_quantity) {
    global $_SESSION;
    
    if (isset($_SESSION['cart'][$product_id])) {
        // Update the quantity of the specified product.
        $_SESSION['cart'][$product_id] = $new_quantity;
        
        // We sort and display the updated cart.
        sort($_SESSION['cart']);
    }
}

// Display cart content
function showCart() {
    global $_SESSION;
    
    echo "<pre>";
    print_r($_SESSION['cart']);
    echo "</pre>";
}

// Example usage:
if (isset($_POST['addToCart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
} elseif (isset($_POST['removeFromCart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
} elseif (isset($_POST['updateQuantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    updateQuantity($product_id, $new_quantity);
}

showCart();


<?php
session_start();


$cart_data = array(
    'items' => array(),
    'subtotal' => 0,
    'total_tax' => 0,
    'total_discount' => 0,
    'total_amount' => 0
);


function add_item_to_cart($product_id, $quantity) {
    global $cart_data;
    
    // Check if product is already in cart
    foreach ($cart_data['items'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Product already in cart, update quantity only
        }
    }
    
    // Add new item to cart
    $cart_data['items'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity,
        'price' => get_product_price($product_id) // Function to retrieve product price from database
    );
    
    // Update subtotal, total tax, total discount, and total amount
    update_cart_totals();
}

function update_cart_totals() {
    global $cart_data;
    
    $subtotal = 0;
    $total_tax = 0;
    $total_discount = 0;
    $total_amount = 0;
    
    foreach ($cart_data['items'] as &$item) {
        $subtotal += $item['price'] * $item['quantity'];
        // Calculate tax and discount for each item (not shown here)
    }
    
    $cart_data['subtotal'] = $subtotal;
    $cart_data['total_tax'] = $total_tax;
    $cart_data['total_discount'] = $total_discount;
    $cart_data['total_amount'] = $subtotal + $total_tax - $total_discount;
}


function update_cart_in_session() {
    global $cart_data;
    
    $_SESSION['cart'] = $cart_data;
}


function get_cart_from_session() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        // Initialize empty cart if not set in session
        $cart_data = array(
            'items' => array(),
            'subtotal' => 0,
            'total_tax' => 0,
            'total_discount' => 0,
            'total_amount' => 0
        );
        return $cart_data;
    }
}


add_item_to_cart(1, 2); // Add 2 units of product with ID 1 to cart
update_cart_in_session(); // Update cart in session


$cart = get_cart_from_session();
print_r($cart); // Display current cart data


<?php
// Start the session
session_start();

// Check if the cart is already set in the session
if (!isset($_SESSION['cart'])) {
    // If not, create a new cart array and store it in the session
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it is, increment the quantity of the existing item
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // If not, add a new item to the cart
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            // If it is, remove the item from the cart
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function update_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if the product is in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // If it is, update the quantity of the item
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Example usage:
if (isset($_GET['add_to_cart'])) {
    add_to_cart($_GET['product_id'], $_GET['quantity']);
} elseif (isset($_GET['remove_from_cart'])) {
    remove_from_cart($_GET['product_id']);
} elseif (isset($_GET['update_cart'])) {
    update_cart($_GET['product_id'], $_GET['quantity']);
}

// Print out the contents of the cart
echo "Cart Contents:<br>";
foreach ($_SESSION['cart'] as $item) {
    echo "$" . $item['product_id'] . " x $" . $item['quantity'] . "<br>";
}
?>


<?php
session_start();
?>


// Sample products array
$products = [
    "1" => ["name" => "Product A", "price" => 10],
    "2" => ["name" => "Product B", "price" => 20],
    "3" => ["name" => "Product C", "price" => 30]
];

function add_to_cart($product_id, $quantity = 1) {
    global $products;
    
    // Check if the product exists
    if (array_key_exists($product_id, $products)) {
        // If the cart session doesn't exist, create it with an empty array
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }
        
        // Add or update item in cart
        if (array_key_exists($product_id, $_SESSION["cart"])) {
            $_SESSION["cart"][$product_id] += $quantity;
        } else {
            $_SESSION["cart"][$product_id] = $quantity;
        }
    }
}

function display_cart() {
    global $products;
    
    echo "<h2>Cart Contents:</h2>";
    
    // Show the items in cart
    if (isset($_SESSION["cart"])) {
        foreach ($_SESSION["cart"] as $item_id => $quantity) {
            $product = $products[$item_id];
            echo "Product: $product[name] - Quantity: $quantity<br>";
            echo "Total for this item: $" . ($product['price'] * $quantity) . "<br><hr>";
        }
    } else {
        echo "Your cart is empty.";
    }
}

// Example usage
add_to_cart("1", 3); // Add 3 of product A to the cart
add_to_cart("2"); // Add 1 of product B to the cart

display_cart();
?>


<?php

// Initialize cart array
$cart = array();

// Function to add item to cart
function add_to_cart($item_id, $quantity) {
  global $cart;
  
  // Check if item already exists in cart
  foreach ($cart as &$item) {
    if ($item['id'] == $item_id) {
      $item['quantity'] += $quantity;
      return;
    }
  }
  
  // Add new item to cart
  $cart[] = array(
    'id' => $item_id,
    'quantity' => $quantity
  );
}

// Function to update quantity of an item in the cart
function update_cart_item($item_id, $new_quantity) {
  global $cart;
  
  // Find the item in the cart and update its quantity
  foreach ($cart as &$item) {
    if ($item['id'] == $item_id) {
      $item['quantity'] = $new_quantity;
      return;
    }
  }
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;
  
  // Find and remove the item from the cart
  foreach ($cart as &$item) {
    if ($item['id'] == $item_id) {
      unset($item);
      break;
    }
  }
}

// Function to get the total cost of items in the cart
function get_total_cost() {
  global $cart;
  
  // Calculate the total cost by multiplying quantity and price of each item
  $total_cost = 0;
  foreach ($cart as $item) {
    // Assuming prices are stored in a database or external resource
    $price = getPrice($item['id']); // Replace with actual price retrieval logic
    $total_cost += $price * $item['quantity'];
  }
  
  return $total_cost;
}

?>


<?php

// Include cart functionality file
require_once 'cart.php';

// Initialize session
session_start();

// Set up some example items for demonstration purposes
$items = array(
  array('id' => 1, 'name' => 'Item 1', 'price' => 10.99),
  array('id' => 2, 'name' => 'Item 2', 'price' => 5.99)
);

// Add items to cart
add_to_cart(1, 2);
add_to_cart(2, 3);

// Display cart contents
echo "Cart Contents:
";
foreach ($cart as $item) {
  echo "$item[id] x $item[quantity]:\t\t\$" . getPrice($item['id']) * $item['quantity'] . "
";
}

// Update item quantity in cart
update_cart_item(1, 4);

// Display updated cart contents
echo "Updated Cart Contents:
";
foreach ($cart as $item) {
  echo "$item[id] x $item[quantity]:\t\t\$" . getPrice($item['id']) * $item['quantity'] . "
";
}

// Remove item from cart
remove_from_cart(2);

// Display updated cart contents after removal
echo "Updated Cart Contents After Removal:
";
foreach ($cart as $item) {
  echo "$item[id] x $item[quantity]:\t\t\$" . getPrice($item['id']) * $item['quantity'] . "
";
}

?>


<?php
session_start();

// Check if the cart array already exists in the session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get the current items in the cart
$cart = $_SESSION['cart'];

// Add item to the cart (example: product id 1)
function add_to_cart($product_id) {
    global $cart;
    
    // Check if the item is already in the cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            // If it exists, increment the quantity
            $item['quantity']++;
            return;
        }
    }
    
    // Add new item to the cart with quantity 1
    $cart[] = array('id' => $product_id, 'quantity' => 1);
}

// Remove item from the cart (example: product id 1)
function remove_from_cart($product_id) {
    global $cart;
    
    // Find the item in the cart and delete it if found
    foreach ($cart as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return;
        }
    }
}

// Display the contents of the cart
function display_cart() {
    global $cart;
    
    echo "<h2>Cart Contents:</h2>";
    foreach ($cart as $item) {
        echo "Product ID: $item[id] - Quantity: $item[quantity]<br>";
    }
}

// Example usage:
add_to_cart(1);
display_cart();

?>


<?php
session_start();

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
}

// Function to update quantity of an existing item in the cart
function updateQuantityInCart($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Function to display the contents of the cart
function showCartContents() {
    if (isset($_SESSION['cart'])) {
        echo "<h2>Your Cart Contents:</h2>";
        foreach ($_SESSION['cart'] as $product_id => $item) {
            echo "Product ID: $product_id, Quantity: $item[quantity]<br>";
        }
    } else {
        echo "Your cart is empty.";
    }
}
?>


<?php
// Start a new PHP script to demonstrate usage
session_start();

// Add some items to the cart
addItemToCart(1, 2);
addItemToCart(3, 4);

// Update the quantity of an existing item
updateQuantityInCart(1, 5);

// Remove an item from the cart
removeItemFromCart(3);

// Display the contents of the cart
showCartContents();
?>


<?php
session_start();
// Check if the session is new and create it if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


<?php
if (isset($_POST['addToCart'])) {
    $itemId = $_POST['item_id'];
    $itemQuantity = $_POST['quantity'];

    // Add the item to the cart array if it doesn't already exist
    if (!in_array($itemId, array_keys($_SESSION['cart']))) {
        $_SESSION['cart'][$itemId] = array('quantity' => $itemQuantity);
    } else {
        // Increment the quantity of an existing item
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $itemId) {
                $item['quantity'] += $itemQuantity;
                break;
            }
        }
    }

    header('Location: cart.php');
    exit();
}
?>


<?php
// Retrieve the cart items from the session
$cart = $_SESSION['cart'];

// Calculate the total cost
$totalCost = 0;
foreach ($cart as $item) {
    // Assume item prices are stored in an array (e.g., `$itemPrices`)
    $totalCost += $itemPrices[$item['id']] * $item['quantity'];
}

// Display cart contents
?>
<table>
    <tr>
        <th>Item ID</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>
    <?php foreach ($cart as $item) { ?>
    <tr>
        <td><?php echo $item['id']; ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo $itemPrices[$item['id']]; ?></td>
    </tr>
    <?php } ?>
</table>

<p>Total Cost: <?php echo $totalCost; ?></p>


<form action="cart.php" method="post">
    <input type="hidden" name="addToCart" value="1">
    <label>Item ID:</label>
    <input type="text" name="item_id" value="<?php echo $itemId; ?>">
    <br>
    <label>Quantity:</label>
    <input type="text" name="quantity" value="<?php echo $itemQuantity; ?>">
    <br>
    <input type="submit" value="Add to Cart">
</form>


<?php

// Start the session
session_start();

// Define a function to add an item to the cart
function add_to_cart($product_id) {
  // Get the current user ID (assuming it's stored in the $_SESSION variable)
  $user_id = $_SESSION['user_id'];

  // Retrieve the product details from the database
  $query = "SELECT * FROM products WHERE id = '$product_id'";
  $result = mysqli_query($db, $query);
  $product = mysqli_fetch_assoc($result);

  // Get the current cart contents
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][] = array(
      'id' => $product['id'],
      'name' => $product['name'],
      'price' => $product['price']
    );
  } else {
    $_SESSION['cart'] = array(array(
      'id' => $product['id'],
      'name' => $product['name'],
      'price' => $product['price']
    ));
  }
}

// Define a function to view the cart contents
function view_cart() {
  if (isset($_SESSION['cart'])) {
    echo "Cart Contents:
";
    foreach ($_SESSION['cart'] as $item) {
      echo "$item[name] - \$" . number_format($item['price'], 2) . "
";
    }
  } else {
    echo "Your cart is empty.
";
  }
}

// Define a function to remove an item from the cart
function remove_from_cart($product_id) {
  // Get the current user ID (assuming it's stored in the $_SESSION variable)
  $user_id = $_SESSION['user_id'];

  // Remove the product from the cart
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
    }
  }
}

// Example usage:
add_to_cart(1); // Add an Apple to the cart
view_cart(); // View the cart contents

remove_from_cart(2); // Remove a Banana from the cart
view_cart();

?>


// Initialize the cart session
session_start();

// Create a new cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Function to add an item to the cart
function add_item_to_cart($item_id, $quantity) {
    // Check if the item is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            // If it is, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add a new item to the cart
    $_SESSION['cart'][] = array(
        'id' => $item_id,
        'quantity' => $quantity
    );
}


// Function to display the cart contents
function view_cart() {
    // Get the current cart items
    $cart_items = $_SESSION['cart'];

    // Display each item in the cart
    echo "<h2>Cart Contents:</h2>";
    foreach ($cart_items as $item) {
        echo "Item ID: $item[id] - Quantity: $item[quantity]<br>";
    }
}


// Initialize the cart session
session_start();

// Add some items to the cart
add_item_to_cart(1, 2); // Add 2 of item ID 1
add_item_to_cart(3, 1); // Add 1 of item ID 3

// View the cart contents
view_cart();


<?php

// Starting or resuming an existing session
session_start();

// If cart does not exist in session, create it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Check if product is already in cart to avoid duplicates
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return true; // Product quantity updated, no need to add it again
        }
    }
    
    // If not in cart, add the product with its quantity
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
    
    return false; // New product added
}

function update_cart($product_id) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Remove the item from its current index and reassign it to the end to maintain order
            array_splice($_SESSION['cart'], array_search($item, $_SESSION['cart']), 1);
            return array_push($_SESSION['cart'], $item); // Return whether the addition was successful or not
        }
    }
    
    // If product ID does not exist in cart, simply append it
    array_push($_SESSION['cart'], ['id' => $product_id, 'quantity' => 1]);
}

function remove_from_cart($product_id) {
    global $_SESSION;
    foreach ($GLOBALS['_SESSION']['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]); // Remove item by index
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reset indices
            return true;
        }
    }
    
    return false; // Item not found in cart
}

function empty_cart() {
    global $_SESSION;
    session_unset();
    session_destroy(); // Destroy the session to remove all data
}

// Example usage:
if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    add_to_cart($product_id, $quantity);
} elseif (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    
    remove_from_cart($product_id);
}

// To display cart content
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

?>


// cart.php

<?php

// Start the session
session_start();

// Define the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

?>


// add_to_cart.php

<?php

// Include the cart session file
require_once 'cart.php';

// Get the product ID and quantity from the request
$product_id = $_POST['product_id'];
$quantity = (int) $_POST['quantity'];

// Validate the input data
if ($product_id && $quantity > 0) {
    // Add the product to the cart array
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['quantity' => 1, 'subtotal' => 0];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    }

    // Update the subtotal for the product
    $price = get_product_price($product_id);
    $_SESSION['cart'][$product_id]['subtotal'] = $price * $_SESSION['cart'][$product_id]['quantity'];

    // Recalculate the total cart value
    $_SESSION['cart']['total'] = array_sum(array_column($_SESSION['cart'], 'subtotal'));
}

?>


// remove_from_cart.php

<?php

// Include the cart session file
require_once 'cart.php';

// Get the product ID from the request
$product_id = $_POST['product_id'];

// Validate the input data
if ($product_id) {
    // Remove the product from the cart array
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    // Recalculate the total cart value
    $_SESSION['cart']['total'] = array_sum(array_column($_SESSION['cart'], 'subtotal'));
}

?>


// display_cart.php

<?php

// Include the cart session file
require_once 'cart.php';

?>

<div>
    <h2>Cart Contents:</h2>
    <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
        <div>
            <p><?= get_product_name($product_id) ?> x <?= $item['quantity'] ?></p>
            <p>$<?= $item['subtotal'] ?></p>
        </div>
    <?php endforeach; ?>

    <h2>Total:</h2>
    <p>$<?= $_SESSION['cart']['total'] ?></p>
</div>

<?php

// Define a function to retrieve the product name based on its ID
function get_product_name($product_id) {
    // Assume you have a database or array of products with their IDs and names
    $products = [
        1 => 'Product A',
        2 => 'Product B',
        3 => 'Product C'
    ];
    return $products[$product_id] ?? 'Unknown Product';
}
?>


<?php
session_start();
?>


<?php
// Assume $product_id and $quantity are variables containing the product ID and quantity respectively.
$_SESSION['cart'] = array();
$_SESSION['cart'][$product_id] = $quantity;
?>


<?php
function update_cart() {
    global $_SESSION;
    if (isset($_POST['add_to_cart'])) {
        // Get product ID and quantity from form submission.
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Check if product already exists in cart. If yes, increment quantity. Otherwise add it to cart.
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
}
?>


<?php
// Function to display cart items.
function show_cart() {
    global $_SESSION;
    echo "<h2>My Cart</h2>";
    if (isset($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Assuming we have a function to get product name and price.
            $product_name = get_product_name($product_id);
            $price = get_product_price($product_id);
            $subtotal = $quantity * $price;
            echo "<p>$product_name (x$quantity) = $${$subtotal}</p>";
            $total += $subtotal;
        }
        echo "<h2>Total: \$$total</h2>";
    } else {
        echo "Your cart is empty.";
    }
}
?>


<?php
// Function to get product name.
function get_product_name($product_id) {
    // Connect to database here...
    $query = "SELECT name FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db_connection, $query);
    return mysqli_fetch_assoc($result)['name'];
}

// Function to get product price.
function get_product_price($product_id) {
    // Connect to database here...
    $query = "SELECT price FROM products WHERE id = '$product_id'";
    $result = mysqli_query($db_connection, $query);
    return mysqli_fetch_assoc($result)['price'];
}
?>


<?php
session_start();
?>


// Example of initializing or updating an existing cart
$cart = $_SESSION['cart'] ?? [];


function addItemToCart($id, $name, $price) {
    global $cart;
    $itemExist = false;

    foreach ($cart as &$item) {
        if ($item['id'] == $id) {
            // Update existing item's quantity
            $item['quantity'] += 1;
            $itemExist = true;
            break;
        }
    }

    if (!$itemExist) {
        // Add new item to cart
        $cart[] = ['id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1];
    }

    $_SESSION['cart'] = $cart;
}


function removeItemFromCart($id) {
    global $cart;

    // Find index of item with matching ID
    $index = array_search(['id' => $id], $cart);

    if ($index !== false) {
        unset($cart[$index]);
    }

    $_SESSION['cart'] = $cart;
}


function calculateCartTotal() {
    global $cart;

    if (empty($cart)) {
        return 0; // Empty cart, return 0 as default.
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}


<?php
session_start();

$cart = $_SESSION['cart'] ?? [];

function addItemToCart($id, $name, $price) {
    global $cart;
    
    // Logic as described earlier...
}

function removeItemFromCart($id) {
    global $cart;

    // Logic as described earlier...
}

// Example item IDs and prices for demonstration
$items = [
    ['id' => 1, 'name' => 'Product A', 'price' => 10.99],
    ['id' => 2, 'name' => 'Product B', 'price' => 5.99],
];

?>

<form>
    <?php foreach ($items as $item) : ?>
        <input type="button" value="<?= $item['name'] ?>" onclick="addItemToCart(<?= $item['id'] ?>, <?= $item['name'] ?>, <?= $item['price'] ?>)">
    <?php endforeach; ?>

    <hr>

    <h2>Current Cart:</h2>
    <ul>
        <?php foreach ($cart as $item) : ?>
            <li><?= $item['name'] ?> (<?= $item['quantity'] ?> x <?= $item['price'] ?>) = <?= $item['price'] * $item['quantity'] ?></li>
        <?php endforeach; ?>
    </ul>

    Total: <?= calculateCartTotal() ?>

</form>

<script>
function addItemToCart(id, name, price) {
    // This function is currently not defined in this example. You would need to implement AJAX or similar to call your PHP function from here.
}
</script>


<?php
session_start();

// Check if the cart session already exists
if (!isset($_SESSION['cart'])) {
    // If not, create a new cart session array
    $_SESSION['cart'] = array();
}

// Get the current cart contents
$cart = $_SESSION['cart'];

// Example function to add an item to the cart
function addToCart($productId, $quantity) {
    global $cart;
    if (isset($cart[$productId])) {
        // If the product is already in the cart, increment its quantity
        $cart[$productId]['quantity'] += $quantity;
    } else {
        // Otherwise, add it to the cart with a new entry
        $cart[$productId] = array('price' => get_product_price($productId), 'quantity' => $quantity);
    }
}

// Example function to remove an item from the cart
function removeFromCart($productId) {
    global $cart;
    unset($cart[$productId]);
}

// Example function to update a product's quantity in the cart
function updateQuantity($productId, $newQuantity) {
    global $cart;
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $newQuantity;
    }
}

// Add an item to the cart
addToCart(1, 2);

// Get the current cart contents
$cartContents = $_SESSION['cart'];
print_r($cartContents); // Output: Array ( [1] => Array ( [price] => 10.99 [quantity] => 2 ) )

// Remove an item from the cart
removeFromCart(1);

// Get the updated cart contents
$updatedCartContents = $_SESSION['cart'];
print_r($updatedCartContents); // Output: Array ()


<?php
function get_product_price($productId) {
    // Replace this with your actual product pricing logic
    $products = array(
        1 => array('name' => 'Product 1', 'price' => 10.99),
        2 => array('name' => 'Product 2', 'price' => 9.99)
    );
    return $products[$productId]['price'];
}


session_start();


// Check if the cart is set; otherwise, create an empty array.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_item_to_cart($item_id) {
    global $_SESSION; // Accessing the session array globally
    
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id] += 1;
    } else {
        $_SESSION['cart'][$item_id] = 1;
    }
}


function remove_item_from_cart($item_id) {
    global $_SESSION;
    
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
        
        // If the cart array is empty after removing an item, reset it.
        if (count($_SESSION['cart']) == 0) {
            $_SESSION['cart'] = array();
        }
    } else {
        echo "Item not found in cart.";
    }
}


function update_item_quantity($item_id, $quantity) {
    global $_SESSION;
    
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id] = max(1, min($quantity, 100)); // Limit quantity between 1 and 100 for simplicity
    } else {
        echo "Item not found in cart.";
    }
}


function display_cart() {
    global $_SESSION;
    
    if (count($_SESSION['cart']) > 0) {
        echo "<h2>Cart:</h2>";
        
        foreach ($_SESSION['cart'] as $item_id => $quantity) {
            echo "$item_id: $quantity<br>";
        }
    } else {
        echo "Your cart is empty.";
    }
}


session_start();

// Sample data, replace with your own item IDs and quantities
$items = array(
    '1' => 10,
    '2' => 5,
    '3' => 20
);

if (isset($_POST['add_item'])) {
    add_item_to_cart($_POST['item_id']);
} elseif (isset($_POST['remove_item'])) {
    remove_item_from_cart($_POST['item_id']);
} elseif (isset($_POST['update_quantity'])) {
    update_item_quantity($_POST['item_id'], $_POST['quantity']);
}

display_cart();

// Display form to add items
echo "<h2>Add Item:</h2>";
foreach ($items as $item_id => $name) {
    echo "Item ID: $item_id - Name: $name<br>";
}


<?php
session_start();
?>


class Cart {
    private $items;

    public function __construct() {
        $this->items = array();
    }

    public function addItem($product_id, $quantity) {
        if (isset($this->items[$product_id])) {
            $this->items[$product_id] += $quantity;
        } else {
            $this->items[$product_id] = $quantity;
        }
    }

    public function removeItem($product_id) {
        unset($this->items[$product_id]);
    }

    public function updateItem($product_id, $new_quantity) {
        if (isset($this->items[$product_id])) {
            $this->items[$product_id] = $new_quantity;
        }
    }

    public function getItems() {
        return $this->items;
    }
}


<?php
session_start();

$cart = new Cart();
?>


// Assume we have a product ID and quantity variable...
$product_id = '12345';
$quantity = 2;

if (isset($_SESSION['cart'])) {
    $cart->items = $_SESSION['cart'];
}

$cart->addItem($product_id, $quantity);

$_SESSION['cart'] = $cart->getItems();


// Assume we have a product ID variable...
$product_id = '12345';

$cart->removeItem($product_id);

$_SESSION['cart'] = $cart->getItems();


// Assume we have a product ID and new quantity variable...
$product_id = '12345';
$new_quantity = 3;

$cart->updateItem($product_id, $new_quantity);

$_SESSION['cart'] = $cart->getItems();


<?php
session_start();

$cart = new Cart();

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $cart->addItem($product_id, $quantity);

    $_SESSION['cart'] = $cart->getItems();
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    $cart->removeItem($product_id);

    $_SESSION['cart'] = $cart->getItems();
}

if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    $cart->updateItem($product_id, $new_quantity);

    $_SESSION['cart'] = $cart->getItems();
}
?>

<form action="" method="post">
    <input type="hidden" name="product_id" value="12345">
    <input type="number" name="quantity" value="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<?php
if (isset($cart->items)) {
    foreach ($cart->items as $product_id => $quantity) {
        echo "Product ID: $product_id, Quantity: $quantity<br>";
    }
}
?>


<?php
session_start();

// Check if the session is not set, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($item_id, $quantity = 1) {
    // Check if the item already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $quantity;
            return true;
        }
    }

    // Add new item to the cart
    $_SESSION['cart'][] = array('id' => $item_id, 'quantity' => $quantity);
}


function show_cart() {
    echo '<h2>Your Cart</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Item ID: ' . $item['id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}


function remove_from_cart($item_id) {
    // Find the item in the cart
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }

    return false;
}


function clear_cart() {
    $_SESSION['cart'] = array();
}


// Add item 1 with quantity 2
add_to_cart(1, 2);

// Display cart contents
show_cart();

// Remove item 1
remove_from_cart(1);

// Clear cart
clear_cart();


<?php
// Starting the session
session_start();

// Initializing the cart variables if they don't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($productId, $name, $quantity) {
    global $_SESSION;

    // Adding the product details to the session cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = array('name' => $name, 'quantity' => $quantity);
    }
}

// Function to update the quantity of an item in the cart
function updateItemQuantity($productId, $newQuantity) {
    global $_SESSION;

    // Updating the product details in the session cart if it exists
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
    global $_SESSION;

    // Removing the product details from the session cart if it exists
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Example usage:

// Adding an item to the cart
addItemToCart(1, "Product A", 2);

// Updating the quantity of an existing item
updateItemQuantity(1, 3);

// Removing an item from the cart
removeItemFromCart(1);

print_r($_SESSION['cart']);
?>


<?php
session_start();

// Check if the session is set, otherwise create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($itemId) {
    global $_SESSION;
    $itemExists = false;

    // Check if the item already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            $itemExists = true;
            break;
        }
    }

    // If the item doesn't exist, add it to the cart
    if (!$itemExists) {
        $_SESSION['cart'][] = array('id' => $itemId);
    } else {
        // Increment the quantity of the existing item
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $itemId) {
                $item['quantity']++;
                break;
            }
        }
    }

    return $_SESSION['cart'];
}

// Function to remove an item from the cart
function removeFromCart($itemId) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            unset($item);
            break;
        }
    }

    return $_SESSION['cart'];
}

// Function to update the quantity of an item in the cart
function updateQuantity($itemId, $quantity) {
    global $_SESSION;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] = $quantity;
            break;
        }
    }

    return $_SESSION['cart'];
}

// Function to display the cart contents
function displayCart() {
    global $_SESSION;
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as &$item) {
        echo "Item ID: $item[id] | Quantity: $item[quantity]<br>";
    }
}
?>


<?php include 'cart.php'; ?>

<h1>Cart System</h1>

<form action="" method="post">
    <input type="hidden" name="itemId" value="1">
    <button type="submit">Add Item 1 to Cart</button>
</form>

<form action="" method="post">
    <input type="hidden" name="itemId" value="2">
    <button type="submit">Add Item 2 to Cart</button>
</form>

<?php
if (isset($_POST['itemId'])) {
    addToCart($_POST['itemId']);
}

displayCart();
?>


<?php

// Start the session
session_start();

// Check if cart is already in session
if (!isset($_SESSION['cart'])) {
    // Initialize an empty cart array
    $_SESSION['cart'] = array();
}

?>


function add_to_cart($product_id, $quantity) {
    // Get current user's id (assuming you have a logged-in user)
    $user_id = $_SESSION['user']['id'];

    // Check if product already exists in cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            // Update quantity of existing item
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Add new item to cart
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity,
        'user_id' => $user_id
    );
}


function remove_from_cart($product_id) {
    // Get current user's id (assuming you have a logged-in user)
    $user_id = $_SESSION['user']['id'];

    // Find and remove item from cart array
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id && $item['user_id'] == $user_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}


function display_cart() {
    // Get current user's id (assuming you have a logged-in user)
    $user_id = $_SESSION['user']['id'];

    // Filter cart items for current user
    $cart_items = array_filter($_SESSION['cart'], function($item) use ($user_id) {
        return $item['user_id'] == $user_id;
    });

    // Display cart contents (example output)
    echo '<h2>Cart Contents:</h2>';
    foreach ($cart_items as $item) {
        echo 'Product ID: ' . $item['product_id'] . ', Quantity: ' . $item['quantity'] . '<br>';
    }
}


// Start session
session_start();

// Add product to cart
add_to_cart(1, 2);

// Display cart contents
display_cart();


<?php
session_start();

// Check if cart session already exists, if not create it
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Add product to cart (example: when user clicks "Add to Cart")
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Check if product already exists in cart, if so update quantity
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] += $quantity;
      break; // exit loop
    }
  }

  // If product doesn't exist, add it to cart
  if (!isset($item)) {
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
  }
}

// Display cart contents
?>
<div class="cart">
  <h2>Shopping Cart</h2>
  <?php foreach ($_SESSION['cart'] as $item): ?>
    <div class="item">
      <span><?php echo $item['id']; ?></span>
      <span>Quantity: <?php echo $item['quantity']; ?></span>
    </div>
  <?php endforeach; ?>
</div>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="product_id" value="<?php echo isset($_POST['product_id']) ? $_POST['product_id'] : ''; ?>">
  <input type="number" name="quantity" value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : ''; ?>">
  <button type="submit">Add to Cart</button>
</form>


// api/cart.php
<?php
session_start();

header('Content-Type: application/json');

echo json_encode($_SESSION['cart']);


<?php
// Start the session
session_start();

// Initialize the cart array
$cart = array();


// Function to add an item to the cart
function add_to_cart($item_id, $quantity) {
  global $cart;

  // Check if the item already exists in the cart
  foreach ($cart as &$item) {
    if ($item['id'] == $item_id) {
      $item['quantity'] += $quantity;
      return;
    }
  }

  // If not, add a new entry for that item
  $cart[] = array(
    'id' => $item_id,
    'quantity' => $quantity,
    'price' => 0, // This will be populated later
  );
}


// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;

  // Find the item to remove
  foreach ($cart as $key => &$item) {
    if ($item['id'] == $item_id) {
      unset($cart[$key]);
      return;
    }
  }
}


// Function to update the quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
  global $cart;

  // Find the item to update
  foreach ($cart as &$item) {
    if ($item['id'] == $item_id) {
      $item['quantity'] = $new_quantity;
      return;
    }
  }
}


// Function to get the contents of the cart
function get_cart_contents() {
  global $cart;

  return $cart;
}


<?php
session_start();

$cart = array();

add_to_cart(1, 2); // Add two items with ID 1 to the cart
add_to_cart(2, 3); // Add three items with ID 2 to the cart

echo "Cart contents:
";
print_r(get_cart_contents());

remove_from_cart(1);

echo "Cart contents after removing item with ID 1:
";
print_r(get_cart_contents());

update_quantity(2, 4);

echo "Cart contents after updating quantity of item with ID 2:
";
print_r(get_cart_contents());
?>


<?php
session_start();

// Function to add item to cart
function addToCart($productId, $quantity) {
    global $_SESSION;
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Check if product is already in the cart and update quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] += $quantity;
            return;
        }
    }
    
    // Add new item to the cart
    $_SESSION['cart'][] = array('product_id' => $productId, 'quantity' => $quantity);
}

// Function to update quantity of an item in the cart
function updateCart($productId, $newQuantity) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] = $newQuantity;
            return true; // Item updated successfully
        }
    }
    
    return false; // Product not found in the cart
}

// Function to remove an item from the cart
function removeFromCart($productId) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]);
            return; // Item removed successfully
        }
    }
}

// Function to calculate total cost of items in the cart (example for simplicity)
function calculateTotalCost() {
    global $_SESSION;
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $price = getPrice($item['product_id']); // Assume a function exists to get product price
        $total += $price * $item['quantity'];
    }
    return $total;
}

// Example: Get price of a product (you should replace this with actual database query or API call)
function getPrice($productId) {
    // For simplicity, let's assume we're getting the price from a variable
    return 10.99; // Replace with real logic to get product price from DB/API
}

// Example usage:
addToCart(1, 2); // Add product ID 1 in quantity of 2
updateCart(1, 3); // Update quantity of product ID 1 to 3
removeFromCart(1); // Remove product ID 1 from the cart

echo "Total Cost: " . calculateTotalCost();


<?php
session_start();
?>


<?php
session_start();

// Check if there's already a cart in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // Initialize an empty array for the cart
}
?>


<?php
function add_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Ensure product_id and quantity are integers
    $product_id = (int)$product_id;
    $quantity = (int)$quantity;

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array('quantity' => 0);
    }
    
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
}

// Example usage
add_to_cart(1, 2); // Adds product with ID 1 to cart in quantity of 2
?>


<?php
function update_cart_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Ensure product_id and new_quantity are integers
    $product_id = (int)$product_id;
    $new_quantity = (int)$new_quantity;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example usage
update_cart_quantity(1, 3); // Changes quantity of product with ID 1 to 3 in cart
?>


<?php
function remove_from_cart($product_id) {
    global $_SESSION;
    
    // Ensure product_id is an integer
    $product_id = (int)$product_id;

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example usage
remove_from_cart(1); // Removes product with ID 1 from cart
?>


<?php
$cart = $_SESSION['cart'];

echo "Your Cart:
";

$totalCost = 0;
foreach ($cart as $product_id => $item) {
    // Fetch product details from database or cache to display in cart view
    // For example:
    $productPrice = fetchProductDetails($product_id)['price'];
    
    echo "- " . getProductName($product_id) . " x" . $item['quantity'] . ": $" . number_format($productPrice * $item['quantity'], 2) . "
";
    $totalCost += ($productPrice * $item['quantity']);
}

echo "Total: $" . number_format($totalCost, 2);
?>


<?php
// Check if the cart session is set
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = [];
}


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the product ID and quantity from the POST request
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // If it's already in the cart, update the quantity
            $item['quantity'] += $quantity;
            break;
        }
    }

    // If not, add a new item to the cart
    if (!isset($item)) {
        $_SESSION['cart'][] = ['id' => $productId, 'quantity' => $quantity];
    }
}


<?php foreach ($_SESSION['cart'] as $item) : ?>
    <li>
        <?php echo $item['id']; ?> x <?php echo $item['quantity']; ?>
    </li>
<?php endforeach; ?>


<?php
// Check if the cart session is set
if (!isset($_SESSION['cart'])) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = [];
}

// Add items to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            break;
        }
    }

    if (!isset($item)) {
        $_SESSION['cart'][] = ['id' => $productId, 'quantity' => $quantity];
    }
}

// Display the cart contents
?>
<ul>
    <?php foreach ($_SESSION['cart'] as $item) : ?>
        <li>
            <?php echo $item['id']; ?> x <?php echo $item['quantity']; ?>
        </li>
    <?php endforeach; ?>
</ul>


session_start();


$cart = array();
$_SESSION['cart'] = $cart;


function add_to_cart($name, $price, $quantity = 1) {
    global $_SESSION;
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    $_SESSION['cart'][] = array(
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    );
}


function view_cart() {
    global $_SESSION;
    return $_SESSION['cart'];
}


// Start a new PHP file (e.g., `index.php`)
<?php
session_start();
$cart = array();

// Add some items to the cart
add_to_cart('Apple', 0.99);
add_to_cart('Banana', 0.49, 2);

// View the contents of the cart
print_r(view_cart());

// Output:
// Array
// (
//     [0] => Array
//         (
//             [name] => Apple
//             [price] => 0.99
//             [quantity] => 1
//         )

//     [1] => Array
//         (
//             [name] => Banana
//             [price] => 0.49
//             [quantity] => 2
//         )

// )


<?php
session_start();

// Check if cart is already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
$_SESSION['cart'][] = array(
    'product_id' => 1,
    'quantity' => 2,
);

print_r($_SESSION['cart']);
?>


<?php
session_start();

if (!isset($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    print_r($_SESSION['cart']);
}
?>


<?php
session_start();

// Check if product ID exists in cart
$product_id = $_GET['product_id'];
$quantity = $_GET['quantity'];

if (in_array(array('product_id' => $product_id), $_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
        }
    }
} else {
    // If product ID does not exist in cart, add it
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'quantity' => $quantity,
    );
}

print_r($_SESSION['cart']);
?>


<?php
session_start();

// Check if product ID exists in cart
$product_id = $_GET['product_id'];

if (in_array(array('product_id' => $product_id), $_SESSION['cart'])) {
    unset($_SESSION['cart'][array_search(array('product_id' => $product_id), $_SESSION['cart'])]);
}

print_r($_SESSION['cart']);
?>


session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function addItemToCart($productId, $productName, $price, $quantity = 1) {
    global $_SESSION;
    
    // Check if product is already in cart
    if (isset($_SESSION['cart'][$productId])) {
        // If it's there, increase quantity by 1
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Otherwise, add it to the cart with initial quantity
        $_SESSION['cart'][$productId] = array(
            'product_id' => $productId,
            'name' => $productName,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}


function viewCart() {
    global $_SESSION;
    
    echo "<h2>Shopping Cart</h2>";
    if (count($_SESSION['cart']) > 0) {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            echo "Product: " . $item['name'] . ", Quantity: " . $item['quantity'] . ", Price: $" . number_format($item['price'], 2);
            $total += $item['price'] * $item['quantity'];
            echo "<br>";
        }
        echo "Total: $" . number_format($total, 2) . "<br>";
    } else {
        echo "Your cart is empty.";
    }
}


session_start();

// Add some products to the cart example
addItemToCart(1, 'Product A', 9.99);
addItemToCart(2, 'Product B', 19.99, 2); // Note the extra quantity parameter

viewCart();


<?php
session_start();
?>


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = array();
}


function add_item($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = $quantity;
    }
}


function remove_item($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


function update_quantity($item_id, $new_quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = $new_quantity;
    }
}


function display_cart() {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['items'] as $item_id => $item) {
        echo "$item[0] x $item[1] = $" . $item[1] * $_SESSION['cart'][$item_id];
    }
}


<?php
require_once 'cart.php';

// Add an item to the cart
add_item(1, 2);

// Display the cart contents
display_cart();

// Remove an item from the cart
remove_item(1);

// Update the quantity of an item in the cart
update_quantity(1, 3);

// Display the updated cart contents
display_cart();
?>


<?php
// Session start required
if (!isset($_SESSION)) {
    session_start();
}

// Initialize cart array if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!-- Function to update quantity in the cart -->
function updateQuantity($itemId, $quantity) {
    global $cart;
    
    // Find the item and update its quantity or add it if not found
    foreach ($cart as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] = $quantity;
            return;
        }
    }
    
    // If the item is new, append to the cart array
    $_SESSION['cart'][] = ['id' => $itemId, 'name' => '', 'price' => 0.00, 'quantity' => $quantity];
}

// Function to remove an item from the cart
function removeFromCart($itemId) {
    global $cart;
    
    // Filter out items not matching the ID
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($itemId) {
        return $item['id'] != $itemId;
    });
}

// Function to display cart contents
function displayCart() {
    global $cart;
    
    // Extract and format items
    foreach ($_SESSION['cart'] as $item) {
        echo "Item: {$item['name']} (Quantity: {$item['quantity']}) - Price: £{$item['price']}<br>";
    }
}

// Display cart
displayCart();
?>


<?php
// Assume this is linked from your product pages or where you want to add items

// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}
?>

<!-- Example adding item with id 1 (you'd replace this with your product ID) -->
<?php
updateQuantity(1, 2); // Add item 1 to cart with quantity 2
?>

<!-- Optionally display cart contents after adding items -->
<?php include 'cart.php'; ?>


class Cart {
    private $cart = array();

    public function __construct() {
        // Initialize cart session if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }

    public function add($product_id, $quantity) {
        // Check if product is already in cart
        if (in_array($product_id, array_column($this->cart, 'id'))) {
            // Increment quantity
            foreach ($this->cart as &$item) {
                if ($item['id'] == $product_id) {
                    $item['quantity'] += $quantity;
                    break;
                }
            }
        } else {
            // Add new product to cart
            $this->cart[] = array('id' => $product_id, 'quantity' => $quantity);
        }

        $_SESSION['cart'] = $this->cart;

        return true; // Product added successfully
    }

    public function remove($product_id) {
        // Remove product from cart
        foreach ($this->cart as $key => &$item) {
            if ($item['id'] == $product_id) {
                unset($this->cart[$key]);
                break;
            }
        }

        $_SESSION['cart'] = $this->cart;

        return true; // Product removed successfully
    }

    public function view() {
        // Return cart contents as an array
        return $_SESSION['cart'];
    }

    public function count() {
        // Return total quantity of items in cart
        return array_sum(array_column($this->view(), 'quantity'));
    }
}


require_once 'Cart.php';

$cart = new Cart();


$cart->add(1, 2); // Add product with ID 1 in quantity of 2


$cart->remove(1);


$cartContents = $cart->view();
print_r($cartContents);
// Output: Array ( [0] => Array ( [id] => 1 [quantity] => 2 ) )


$totalQuantity = $cart->count();
echo "Total quantity: $totalQuantity";
// Output: Total quantity: 2


<?php

// Start or resume existing session
session_start();

// If 'cart' array does not exist, create it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example function to add item to cart
function add_item_to_cart($item_id) {
    global $item_name, $price; // Assuming these variables are defined elsewhere

    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$item_id] = array(
            'name' => $item_name,
            'price' => $price,
            'quantity' => 1
        );
    }
}

// Example function to remove item from cart
function remove_item_from_cart($item_id) {
    global $item_name, $price;

    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
    } else {
        // If the key does not exist, do nothing to prevent errors
    }
}

// Example function to update quantity of item in cart
function update_quantity_of_item_in_cart($item_id) {
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $newQuantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
        if ($newQuantity !== false && $newQuantity > 0) {
            $_SESSION['cart'][$item_id]['quantity'] = $newQuantity;
        } else {
            echo "Invalid quantity";
        }
    } else {
        echo "Item not found in cart.";
    }
}

// Example usage: Adding an item to the cart
if (isset($_POST['add_item'])) {
    add_item_to_cart($_POST['item_id']);
}

// Example usage: Removing an item from the cart
if (isset($_POST['remove_item'])) {
    remove_item_from_cart($_POST['item_id']);
}

// Example usage: Updating quantity of an item in the cart
if (isset($_POST['update_quantity'])) {
    update_quantity_of_item_in_cart($_POST['item_id']);
}

// Displaying items in the cart
echo "Your Cart:</br>";
foreach ($_SESSION['cart'] as $item_id => $item) {
    echo 'Item Name: ' . $item['name'] . "</br>";
    echo 'Price per Unit: ' . $item['price'] . "</br>";
    echo 'Quantity: ' . $item['quantity'] . "</br>";
}

?>


<?php

// Initialize session
session_start();

// Function to add item to cart
function add_item_to_cart($product_id, $quantity = 1) {
    // Check if product is already in cart
    $item_in_cart = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item_in_cart = true;
            $item['quantity'] += $quantity;
            break;
        }
    }

    // Add item to cart if not already present
    if (!$item_in_cart) {
        $_SESSION['cart'][] = array(
            'product_id' => $product_id,
            'quantity' => $quantity
        );
    }
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Remove product from database (if using)
}

// Function to update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

// Function to display cart contents
function display_cart() {
    echo "<h2>Cart Contents</h2>";
    foreach ($_SESSION['cart'] as &$item) {
        echo "Product ID: $item[product_id] - Quantity: $item[quantity]<br>";
    }

    // Display total quantity and price (optional)
}

?>


<?php include 'cart.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>User Cart</title>
</head>
<body>

    <!-- Add item button -->
    <button onclick="add_item_to_cart(1)">Add Item 1</button>
    <button onclick="add_item_to_cart(2, 3)">Add Item 2 with quantity 3</button>

    <!-- Remove item button -->
    <button onclick="remove_item_from_cart(1)">Remove Item 1</button>

    <!-- Update quantity input field -->
    <input type="number" id="update_quantity_input">
    <button onclick="update_quantity(1, document.getElementById('update_quantity_input').value)">Update Quantity of Item 1</button>

    <?php display_cart(); ?>

    <script>
        function add_item_to_cart(product_id) {
            <?php add_item_to_cart($product_id); ?>
        }

        function remove_item_from_cart(product_id) {
            <?php remove_item_from_cart($product_id); ?>
        }

        function update_quantity(product_id, new_quantity) {
            <?php update_quantity($product_id, $new_quantity); ?>
        }
    </script>

</body>
</html>


<?php
session_start();


$cart = array();
$_SESSION['cart'] = $cart;


function addToCart($id, $name, $price) {
    global $_SESSION;
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = array('quantity' => 1, 'name' => $name, 'price' => $price);
    } else {
        $_SESSION['cart'][$id]['quantity']++;
    }
}


function viewCart() {
    global $_SESSION;
    foreach ($_SESSION['cart'] as $item) {
        echo "Item: {$item['name']} (Quantity: {$item['quantity']}, Price: {$item['price']})<br>";
    }
}


function removeFromCart($id) {
    global $_SESSION;
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}


<?php
session_start();

$cart = array();
$_SESSION['cart'] = $cart;

// Add some items to the cart
addToCart(1, 'Apple', 0.99);
addToCart(2, 'Banana', 0.49);

// View the contents of the cart
viewCart();

// Remove an item from the cart
removeFromCart(2);

// View the updated cart
viewCart();
?>


// cart.php
<?php
  // Start session management
  if (!isset($_SESSION)) {
    session_start();
  }
?>


// cart.php (continued)
function add_to_cart($product_id) {
  // Check if product is already in cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]++;
  } else {
    $_SESSION['cart'][$product_id] = 1;
  }
}

function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id]) && $_SESSION['cart'][$product_id] > 0) {
    $_SESSION['cart'][$product_id]--;
    if ($_SESSION['cart'][$product_id] == 0) {
      unset($_SESSION['cart'][$product_id]);
    }
  }
}

function view_cart() {
  return $_SESSION['cart'];
}


<!-- index.php -->
<?php require 'cart.php'; ?>
<html>
<head>
  <title>Cart Example</title>
</head>
<body>
  <h1>Product List:</h1>
  <ul>
    <!-- Assuming we have some products in a database or array -->
    <?php foreach ($products as $product): ?>
      <li>
        <?= $product['name'] ?> (<?= $product['price'] ?>)
        <form action="" method="post">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button type="submit" name="action" value="add">Add to Cart</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <h1>Cart:</h1>
  <ul>
    <?php if (!empty($_SESSION['cart'])): ?>
      <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
        <li><?= $products[$product_id]['name'] ?> (<?= $products[$product_id]['price'] ?>) x <?= $quantity ?></li>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No products in cart.</p>
    <?php endif; ?>
  </ul>

  <?php if (isset($_POST['action']) && $_POST['action'] == 'add'): ?>
    add_to_cart($_POST['product_id']);
    echo "Product added to cart.";
  <?php endif; ?>
</body>
</html>


<?php
// Start the session
session_start();

// Define a function to add an item to the cart
function add_item_to_cart($product_id, $quantity) {
    // Check if the product exists in the cart already
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If it does, increment the quantity
            $item['quantity'] += $quantity;
            return;
        }
    }

    // If not, add a new item to the cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

// Define a function to remove an item from the cart
function remove_item_from_cart($product_id) {
    // Loop through the cart and find the item to remove
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

// Define a function to update an item's quantity in the cart
function update_item_quantity($product_id, $new_quantity) {
    // Loop through the cart and find the item to update
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Example usage: add an item to the cart
add_item_to_cart(1, 2); // Product ID 1 with quantity 2

// Example usage: remove an item from the cart
remove_item_from_cart(1);

// Example usage: update an item's quantity in the cart
update_item_quantity(1, 3);


<?php

// Start the session or continue an existing one.
session_start();

// Define a function to save items in the session array.
function add_item($item_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already exists in cart.
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = ['quantity' => $quantity];
    }
}

// Function to remove item from the session array.
function remove_item($item_id) {
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$item_id]);
    }

    // Save the updated cart.
    $_SESSION['cart'] = array_filter($_SESSION['cart']);
}

// Function to update quantity of an item in the session array.
function update_quantity($item_id, $new_quantity) {
    if (array_key_exists($item_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}

// Example: Add items to cart
if (isset($_GET['add_item'])) {
    add_item($_GET['add_item'], 1);
} elseif (isset($_POST['update_quantity'])) {
    update_quantity($_POST['id'], $_POST['new_quantity']);
} elseif (isset($_GET['remove_item'])) {
    remove_item($_GET['remove_item']);
}

// Example: Display cart contents
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<h2>Cart Contents:</h2>";
    foreach ($_SESSION['cart'] as $item_id => $details) {
        echo "Item ID: $item_id, Quantity: {$details['quantity']}<br>";
    }
}

// Example form to add items and update quantities.
?>

<form action="" method="get">
    <input type="hidden" name="add_item" value="<?php echo isset($_GET['add_item']) ? $_GET['add_item'] : ''; ?>">
    Add item: <select name="add_item">
        <!-- Your list of items to add here -->
        <?php
        // Example - replace with your own data.
        $items = [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
            ['id' => 3, 'name' => 'Item 3']
        ];
        foreach ($items as $item) {
            echo "<option value='{$item['id']}'>{$item['name']}</option>";
        }
        ?>
    </select>
    <button type="submit">Add to Cart</button>
</form>

<form action="" method="post">
    <?php
    // Display items in cart for quantity updates.
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item_id => $details) {
            echo "
                <div>
                    Item ID: $item_id, Quantity: {$details['quantity']}
                    <input type='hidden' name='id' value='$item_id'>
                    <input type='text' name='new_quantity' value='{$details['quantity']}' placeholder='Update quantity here...'>
                    <button type='submit'>Update</button>
                </div>
            ";
        }
    } else {
        echo "<p>No items in cart.</p>";
    }
    ?>
</form>

<?php
// Example: Remove an item from the session array.
?>
<form action="" method="get">
    <input type="hidden" name="remove_item" value="<?php echo isset($_GET['remove_item']) ? $_GET['remove_item'] : ''; ?>">
    <button type="submit">Remove Item</button>
</form>

<?php
// Save the session array to maintain cart state across pages.
session_write_close();
?>


<?php
// Initialize the session
session_start();

// If the cart isn't already in the session, add it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id) {
    // Add product to cart if it doesn't exist or increment quantity if it does
    if (in_array($product_id, $_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value == $product_id) {
                $_SESSION['cart'][$key] += 1;
                break;
            }
        }
    } else {
        $_SESSION['cart'][] = $product_id;
    }

    // Save the updated cart session
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

function remove_from_cart($product_id) {
    // Remove product from cart
    if (in_array($product_id, $_SESSION['cart'])) {
        $key = array_search($product_id, $_SESSION['cart']);
        unset($_SESSION['cart'][$key]);
        sort($_SESSION['cart']);
    }

    // Save the updated cart session
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

function view_cart() {
    return $_SESSION['cart'];
}

function empty_cart() {
    // Clear the cart and reset the counter
    $_SESSION['cart'] = array();
    $_SESSION['cart_count'] = 0;
}
?>


// Example usage
session_start();

// Add two products with id 1 and 2, respectively
add_to_cart(1);
add_to_cart(2);

// Remove product with id 1 from cart
remove_from_cart(1);

// View the current contents of the cart
print_r(view_cart());

// Empty the cart
empty_cart();


<?php
session_start();

// Check if the cart session exists, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id) {
    global $_SESSION;
    $cart = &$_SESSION['cart'];
    
    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity']++;
            return;
        }
    }
    
    // Add new item to cart
    $cart[] = array('product_id' => $product_id, 'quantity' => 1);
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    $cart = &$_SESSION['cart'];
    
    // Remove item from cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            unset($item);
            return;
        }
    }
}

// Function to update quantity of an item in the cart
function update_item_quantity($product_id, $new_quantity) {
    global $_SESSION;
    $cart = &$_SESSION['cart'];
    
    // Find the item and update its quantity
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return;
        }
    }
}

// Function to display cart contents
function display_cart() {
    global $_SESSION;
    $cart = &$_SESSION['cart'];
    
    // Display cart contents
    echo "<h2>Your Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Quantity</th></tr>";
    foreach ($cart as &$item) {
        echo "<tr><td>$item[product_id]</td><td>$item[quantity]</td></tr>";
    }
    echo "</table>";
}
?>


<?php
require 'cart.php';

// Add item to cart on button click
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['product_id'];
    add_item_to_cart($product_id);
}

// Remove item from cart on button click
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['product_id'];
    remove_item_from_cart($product_id);
}

// Update quantity of an item in the cart on button click
if (isset($_GET['update_quantity'])) {
    $product_id = $_GET['product_id'];
    $new_quantity = $_GET['quantity'];
    update_item_quantity($product_id, $new_quantity);
}

// Display cart contents
display_cart();

?>


<?php
// Initialize session
session_start();

// Function to get products from database (optional)
function getProducts() {
  $conn = mysqli_connect("localhost", "username", "password", "database");
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $query = "SELECT * FROM products";
  $result = mysqli_query($conn, $query);
  if (!$result) {
    die("Query failed: " . mysqli_error($conn));
  }

  $products = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'price' => $row['price']
    );
  }
  return $products;
}

// Function to add item to cart
function addToCart($product_id, $quantity) {
  // Check if product exists in cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array(
      'id' => $product_id,
      'name' => '',
      'price' => '',
      'quantity' => $quantity
    );
  }
}

// Function to update cart item quantity
function updateCart($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove item from cart
function removeFromCart($product_id) {
  unset($_SESSION['cart'][$product_id]);
}

// Display cart contents
function displayCart() {
  echo '<h2>Cart Contents:</h2>';
  foreach ($_SESSION['cart'] as $item) {
    echo '<p>' . $item['name'] . ' x ' . $item['quantity'] . ' = $' . number_format($item['price'] * $item['quantity'], 2) . '</p>';
  }
}

// Example usage
if (isset($_POST['add_to_cart'])) {
  addToCart($_POST['product_id'], $_POST['quantity']);
} elseif (isset($_POST['update_quantity'])) {
  updateCart($_POST['product_id'], $_POST['new_quantity']);
} elseif (isset($_POST['remove_from_cart'])) {
  removeFromCart($_POST['product_id']);
}

// Display cart contents
displayCart();
?>


<?php
// Start the session
session_start();

// Check if the cart is empty, if so, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get the current cart contents
$cart = $_SESSION['cart'];

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $cart;
    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $cart;
    if (array_key_exists($product_id, $cart)) {
        unset($cart[$product_id]);
    }
}

// Function to update a quantity in the cart
function update_quantity($product_id, $new_quantity) {
    global $cart;
    if (array_key_exists($product_id, $cart)) {
        $cart[$product_id] = $new_quantity;
    }
}

// Add an item to the cart
add_to_cart(1, 2); // Product ID 1, Quantity 2

// Display the current cart contents
echo "Current Cart Contents:<br>";
print_r($cart);

?>


<?php
session_start();

$cart = $_SESSION['cart'];

if (isset($_GET['product_id']) && isset($_GET['action'])) {
    $product_id = $_GET['product_id'];
    $action = $_GET['action'];

    if ($action == 'add') {
        add_to_cart($product_id, 1);
    } elseif ($action == 'remove') {
        remove_from_cart($product_id);
    } elseif ($action == 'update') {
        $new_quantity = $_GET['quantity'];
        update_quantity($product_id, $new_quantity);
    }
}

// Redirect to the cart page
header('Location: cart.php');
exit;
?>


<?php
session_start();

$cart = $_SESSION['cart'];

if (isset($_GET['product_id'])) {
    remove_from_cart($_GET['product_id']);
}

// Redirect to the cart page
header('Location: cart.php');
exit;
?>


<?php

// Start the session
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add a product to the cart
function addProductToCart($productId, $quantity) {
    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['id'] == $productId) {
            // If it does, increment its quantity
            $product['quantity'] += $quantity;
            return;
        }
    }

    // If not, add it to the cart
    $_SESSION['cart'][] = ['id' => $productId, 'quantity' => $quantity];
}

// Function to display the cart contents
function displayCart() {
    echo "Cart Contents:
";
    foreach ($_SESSION['cart'] as $product) {
        echo "- Product ID: {$product['id']} (Quantity: {$product['quantity']})
";
    }
}


<?php

// Include the cart functions
require_once 'cart.php';

// Add a product to the cart
addProductToCart(1, 2);

// Display the cart contents
displayCart();

?>


addProductToCart(1, 2); // Add 2 products with ID 1


displayCart(); // Output: Cart Contents: - Product ID: 1 (Quantity: 2)


<?php
// Start the session
session_start();

// If the cart is empty, set it as an empty array
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Add item to cart
function add_item_to_cart($item_id) {
  // Get the current cart items
  $cart_items = $_SESSION['cart'];

  // Check if the item is already in the cart
  foreach ($cart_items as $key => $value) {
    if ($value == $item_id) {
      // If it's already in the cart, increment its quantity
      $cart_items[$key] += 1;
      return;
    }
  }

  // If not in the cart, add it with a quantity of 1
  $cart_items[] = $item_id;
  $_SESSION['cart'] = $cart_items;
}

// Add an item to the cart
add_item_to_cart(123); // Replace with actual item ID

// Print out the current cart items
echo "Current Cart Items: ";
print_r($_SESSION['cart']);

?>


<?php
// Start the session
session_start();

// Update quantity of an item in the cart
function update_item_in_cart($item_id, $quantity) {
  // Get the current cart items
  $cart_items = $_SESSION['cart'];

  // Find the index of the item
  foreach ($cart_items as $key => $value) {
    if ($value == $item_id) {
      // Update its quantity
      $cart_items[$key] = $quantity;
      return;
    }
  }

  // If not found, do nothing (i.e. don't modify the cart)
}

// Update an item in the cart
update_item_in_cart(123, 2); // Replace with actual item ID and new quantity

// Print out the updated cart items
echo "Updated Cart Items: ";
print_r($_SESSION['cart']);

?>


<?php
// Start the session
session_start();

// Remove an item from the cart
function remove_item_from_cart($item_id) {
  // Get the current cart items
  $cart_items = $_SESSION['cart'];

  // Find and remove the item
  foreach ($cart_items as $key => $value) {
    if ($value == $item_id) {
      unset($cart_items[$key]);
      return;
    }
  }

  // If not found, do nothing (i.e. don't modify the cart)
}

// Remove an item from the cart
remove_item_from_cart(123); // Replace with actual item ID

// Print out the updated cart items
echo "Updated Cart Items: ";
print_r($_SESSION['cart']);

?>


<?php
// Start the session
session_start();

// Clear the entire cart
$_SESSION['cart'] = array();

// Print out the updated cart items (should be empty now)
echo "Clearing Cart...";
echo "Updated Cart Items: ";
print_r($_SESSION['cart']);

?>


<?php
session_start();

// Check if cart is set in session, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function add_item_to_cart($product_id, $quantity) {
    global $_SESSION;
    
    // Create a new product entry in the cart array
    $_SESSION['cart'][] = array('product_id' => $product_id, 'quantity' => $quantity);
    
    // Save changes to session
    $_SESSION['changed'] = true;
}

// Function to remove item from cart
function remove_item_from_cart($product_id) {
    global $_SESSION;
    
    // Find the index of the product in the cart array
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    
    // Save changes to session
    $_SESSION['changed'] = true;
}

// Function to update quantity of item in cart
function update_item_quantity($product_id, $new_quantity) {
    global $_SESSION;
    
    // Find the index of the product in the cart array
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
    
    // Save changes to session
    $_SESSION['changed'] = true;
}

// Function to get cart contents
function get_cart_contents() {
    global $_SESSION;
    
    return $_SESSION['cart'];
}

// Example usage:
add_item_to_cart(1, 2); // Add product with id 1 and quantity of 2
print_r(get_cart_contents()); // Output: Array ( [0] => Array ( [product_id] => 1 [quantity] => 2 ) )
remove_item_from_cart(1);
print_r(get_cart_contents()); // Output: Array ()
update_item_quantity(1, 3);
print_r(get_cart_contents()); // Output: Array ( [0] => Array ( [product_id] => 1 [quantity] => 3 ) )

// Display cart contents on page
if (!empty($_SESSION['cart'])) {
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo 'Product: ' . $item['product_id'] . ', Quantity: ' . $item['quantity'] . '<br />';
    }
} else {
    echo '<p>No items in cart.</p>';
}


// Start a new session or resume an existing one
session_start();


// Initialize the cart array
$_SESSION['cart'] = array();


// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    // Check if the product is already in the cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // If it's already in the cart, increment its quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If not, add it to the cart with a new entry
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $quantity
        );
    }
}

// Example usage:
$product_id = 123;
$product_name = "Product Name";
$product_price = 19.99;
$quantity = 2;

add_to_cart($product_id, $quantity);


// Function to display the cart
function display_cart() {
    echo "<h2>Your Cart:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr>";

    // Loop through each item in the cart
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Example usage:
display_cart();


<?php
session_start();

// Initialize the cart array
$_SESSION['cart'] = array();

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array(
            'name' => "Product Name",
            'price' => 19.99,
            'quantity' => $quantity
        );
    }
}

// Function to display the cart
function display_cart() {
    echo "<h2>Your Cart:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr>";

    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Example usage:
add_to_cart(123, 2);
display_cart();
?>


<?php
// Start the session if not already started
session_start();

// Initialize cart as an array in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_item_to_cart($product_id, $quantity) {
    // Check if product is already in the cart and update its quantity if so
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Item already exists
        }
    }
    
    // Add new item to cart
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => $quantity);
}

function remove_item_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return;
        }
    }
}

function update_quantity($product_id, $new_quantity) {
    // This is a simple example; in real scenarios, you might want to validate input
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
        }
    }
}

function display_cart() {
    echo "Cart Contents:
";
    foreach ($_SESSION['cart'] as $item) {
        echo "$item[id] x $item[quantity]
";
    }
    return implode(', ', array_keys($_SESSION['cart'])); // Return cart keys for reference
}

// Example usage:

// Add items to the cart
add_item_to_cart(1, 2);
add_item_to_cart(3, 4);

// Display cart contents and keys (for reference)
display_cart();

// Update quantity of an item in the cart
update_quantity(1, 3);

// Remove an item from the cart
remove_item_from_cart(3);

// Final display of updated cart contents
display_cart();
?>


session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id) {
    global $_SESSION;
    
    // Check if product is already in cart
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $product_id) {
            // Increase quantity of existing item
            $item['quantity']++;
            return;
        }
    }
    
    // If not found, add new item to cart with default quantity
    $_SESSION['cart'][] = array('id' => $product_id, 'quantity' => 1);
}


function display_cart() {
    global $_SESSION;
    
    echo "Your Cart:
";
    
    if (!empty($_SESSION['cart'])) {
        $total = 0; // For displaying total cost
        
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            
            // Assuming products are listed with id in your database
            // Fetch product details and display them here.
            echo "ID: $product_id, Quantity: $quantity
";
            
            // Update total cost
            // For simplicity, let's assume price is stored along with the product ID
        }
        
        echo "Total: $" . number_format($total) . "
"; // Display total cost
    } else {
        echo "Your cart is empty.
";
    }
}


function update_cart_item($product_id, $action) {
    global $_SESSION;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            switch ($action) {
                case 'remove':
                    unset($item);
                break;
                case 'update':
                    // Update quantity
                    $item['quantity']++;
                    break;
            }
        }
    }
}


session_start();


function addToCart($productId, $quantity) {
    if (isset($_SESSION['cart'])) {
        // If cart is not empty, we'll append new product to it.
        $_SESSION['cart'][] = array('product_id' => $productId, 'quantity' => $quantity);
    } else {
        // Otherwise, initialize the cart with a single item.
        $_SESSION['cart'] = array(array('product_id' => $productId, 'quantity' => $quantity));
    }
}


function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$product) {
            if ($product['product_id'] == $productId) {
                $product['quantity'] = $newQuantity;
                break;
            }
        }
    } else {
        // Handle the case where cart is empty
    }
}


function viewCart() {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product) {
            echo "Product ID: {$product['product_id']}, Quantity: {$product['quantity']} <br>";
        }
    } else {
        echo 'Your cart is empty.';
    }
}


// Start the session
session_start();

// Add a product to the cart with quantity 2.
addToCart(1, 2);

// Update the quantity of a product in the cart.
updateQuantity(1, 3);

// View the contents of the cart.
viewCart();


<?php
    session_start();
?>


// If the user has added an item to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Display cart contents
if (isset($_SESSION['cart'])) {
    echo "You have the following items in your cart:<br>";
    foreach ($_SESSION['cart'] as $id => $quantity) {
        // Assume you have a function or database query to get product details by ID.
        $product_name = 'Product ' . $id; // Replace with actual product name
        echo "$quantity x $product_name<br>";
    }
} else {
    echo "Your cart is empty.";
}


// Example function to calculate total from sessions
function calculateTotal() {
    global $_SESSION;
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $quantity) {
            // Assume you have a function or database query to get product price by ID.
            $price = getPrice($id); // Replace with actual logic
            $total += $price * $quantity;
        }
    }
    return $total;
}

echo "Your total: $" . calculateTotal();


<?php
session_start();

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function getPrice($id) {
    // Replace with actual database logic to retrieve product price
    return 19.99; // Example price for simplicity
}

if (isset($_SESSION['cart'])) {
    echo "You have the following items in your cart:<br>";
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $product_name = 'Product ' . $id;
        echo "$quantity x $product_name<br>";
    }
} else {
    echo "Your cart is empty.";
}

echo "<form action='' method='post'>";
echo "<input type='hidden' name='add_to_cart' value='1'>";
echo "<select name='product_id'>";
// Example products
echo "<option value='1'>Product 1</option>";
echo "<option value='2'>Product 2</option>";
echo "</select>";
echo "Quantity: <input type='number' name='quantity' value='1'>";
echo "<input type='submit' value='Add to Cart'>";
echo "</form>";

function calculateTotal() {
    global $_SESSION;
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $price = getPrice($id);
            $total += $price * $quantity;
        }
    }
    return $total;
}

echo "Your total: $" . calculateTotal();
?>


// add_item.php

<?php
session_start();

// Check if the cart is already set in the session, otherwise initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get the item ID and quantity from the request
$item_id = $_POST['item_id'];
$quantity = $_POST['quantity'];

// Add the item to the cart with the specified quantity
$_SESSION['cart'][$item_id] = $quantity;

// Print a success message or redirect to the next page
echo "Item added to cart successfully!";
?>


// view_cart.php

<?php
session_start();

// Check if the cart is set in the session, otherwise initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Print the cart contents
echo "Cart Contents:<br>";
foreach ($_SESSION['cart'] as $item_id => $quantity) {
    echo "$item_id: $quantity<br>";
}
?>


// update_quantity.php

<?php
session_start();

// Get the item ID and new quantity from the request
$item_id = $_POST['item_id'];
$new_quantity = $_POST['new_quantity'];

// Update the quantity of the specified item in the cart
if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id] = $new_quantity;
} else {
    echo "Item not found in cart.";
}

// Print a success message or redirect to the next page
echo "Quantity updated successfully!";
?>


// remove_item.php

<?php
session_start();

// Get the item ID from the request
$item_id = $_POST['item_id'];

// Remove the specified item from the cart
if (isset($_SESSION['cart'][$item_id])) {
    unset($_SESSION['cart'][$item_id]);
} else {
    echo "Item not found in cart.";
}

// Print a success message or redirect to the next page
echo "Item removed from cart successfully!";
?>


// empty_cart.php

<?php
session_start();

// Remove all items from the cart
$_SESSION['cart'] = array();

// Print a success message or redirect to the next page
echo "Cart emptied successfully!";
?>


// Initialize the database connection
$conn = new mysqli("localhost", "username", "password", "database");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Get the current cart session for the user
$user_id = $_SESSION['user_id'];
$cart_session = array();

// Retrieve existing cart items from database
$query = "SELECT * FROM cart WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $cart_session[$row['product_id']] = array('quantity' => $row['quantity']);
}

// Process form submission (e.g., add product to cart)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the product ID and quantity from the form
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product is already in cart
    if (isset($cart_session[$product_id])) {
        // Update existing cart item
        $cart_session[$product_id]['quantity'] += $quantity;
    } else {
        // Add new cart item
        $cart_session[$product_id] = array('quantity' => $quantity);
    }
}

// Save the updated cart session to database
foreach ($cart_session as $product_id => $item) {
    if (!isset($item['quantity'])) {
        continue;
    }

    $query = "INSERT INTO cart (user_id, product_id, quantity)
              VALUES ($user_id, $product_id, $item[quantity])";
    mysqli_query($conn, $query);
}

// Display the current cart contents
$query = "SELECT p.name, c.quantity FROM products p
          JOIN cart c ON p.id = c.product_id
          WHERE c.user_id = $user_id";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "$row[name] x $row[quantity]<br>";
}

// Close the database connection
mysqli_close($conn);


session_start();


// Example function to add items to cart
function addToCart($productId, $productName, $price) {
    // Check if 'cart' exists in $_SESSION, and initialize it if not
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Create a new item for the product
    $item = array(
        "id" => $productId,
        "name" => $productName,
        "price" => $price,
        "quantity" => 1 // Default quantity, can be updated later
    );

    // Add the item to the cart, or increment its quantity if it already exists
    $itemExists = false;
    foreach ($_SESSION['cart'] as &$existingItem) {
        if ($existingItem["id"] == $productId) {
            $existingItem["quantity"]++;
            $itemExists = true;
            break;
        }
    }

    if (!$itemExists) {
        $_SESSION['cart'][] = $item;
    }
}

// Example function to view the cart
function viewCart() {
    echo "Your Cart:
";
    
    // Display each item in the cart, along with its total cost and quantity
    foreach ($_SESSION['cart'] as $item) {
        echo $item["name"] . ": $" . ($item["price"] * $item["quantity"]) . ", Quantity: " . $item["quantity"] . "
";
    }
    
    // Calculate the total cost of all items in the cart
    $totalCost = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalCost += ($item["price"] * $item["quantity"]);
    }
    
    echo "Total Cost: $" . $totalCost . "
";
}


// Example function to update a product's quantity in the cart
function updateCart($productId, $newQuantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item["id"] == $productId) {
            $item["quantity"] = $newQuantity;
            break;
        }
    }
}


// Example function to remove a product from the cart
function removeFromCart($productId) {
    // Create a copy of the original 'cart' array so we can safely unset items without affecting $_SESSION['cart']
    $cartCopy = $_SESSION['cart'];
    
    foreach ($cartCopy as &$item) {
        if ($item["id"] == $productId) {
            unset($item);
            break;
        }
    }
    
    // Remove empty values from the array
    $_SESSION['cart'] = array_filter($_SESSION['cart']);
}


function emptyCart() {
    $_SESSION['cart'] = array();
}


// Set up session variables
session_start();

function getCart() {
  // Retrieve cart from session or initialize a new one if it doesn't exist
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }
  
  return $_SESSION['cart'];
}

function addItem($id, $quantity) {
  // Get the current cart items
  $cart = getCart();

  // Check if product already exists in cart
  foreach ($cart as &$item) {
    if ($item['id'] == $id) {
      // Update quantity of existing item
      $item['quantity'] += $quantity;
      break;
    }
  }

  // Add new item to cart
  if (!isset($item)) {
    $cart[] = array('id' => $id, 'quantity' => $quantity);
  }

  $_SESSION['cart'] = $cart;
}

function removeItem($id) {
  // Get the current cart items
  $cart = getCart();

  // Remove item from cart
  foreach ($cart as $key => &$item) {
    if ($item['id'] == $id) {
      unset($cart[$key]);
      break;
    }
  }

  $_SESSION['cart'] = array_values($cart);
}

function updateQuantity($id, $quantity) {
  // Get the current cart items
  $cart = getCart();

  // Update quantity of existing item
  foreach ($cart as &$item) {
    if ($item['id'] == $id) {
      $item['quantity'] = $quantity;
      break;
    }
  }

  $_SESSION['cart'] = $cart;
}

// Usage example:
addItem(1, 2); // Add product with ID 1 to cart with quantity 2
removeItem(1); // Remove product with ID 1 from cart
updateQuantity(1, 3); // Update quantity of product with ID 1 in cart

print_r(getCart()); // Print the current state of the cart


<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>


function add_to_cart($product_id, $quantity) {
    // Check if the product already exists in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Increment the quantity of the existing item
            $item['quantity'] += $quantity;
            return true; // Product already in cart, updated successfully
        }
    }

    // Add new product to cart if it doesn't exist
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => 'Product Name', // Assume product name is available somehow
        'price' => 9.99, // Assume product price is available somehow
        'quantity' => $quantity
    );

    return true; // Product added to cart successfully
}


function update_cart_item($product_id, $new_quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            return true; // Quantity updated successfully
        }
    }

    return false; // Product not found in cart
}


function remove_from_cart($product_id) {
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true; // Product removed successfully
        }
    }

    return false; // Product not found in cart
}


function display_cart() {
    echo '<h2>Cart Contents:</h2>';
    foreach ($_SESSION['cart'] as $item) {
        echo $item['name'] . ' x ' . $item['quantity'] . ' = ' . ($item['price'] * $item['quantity']) . '<br>';
    }
}


<?php

// Start session
session_start();

// Check if cart is empty, initialize it if necessary
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add a product to the cart
add_to_cart(1, 2); // Product ID: 1, Quantity: 2

// Display the cart contents
display_cart();

// Update the quantity of an existing product in the cart
update_cart_item(1, 3);

// Remove a product from the cart
remove_from_cart(1);

?>


<?php

// Enable session
session_start();

// Function to add item to cart
function addToCart($product_id, $quantity) {
    // Get existing cart items from session or create new array if none exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Add product to cart with quantity
    $_SESSION['cart'][$product_id] = $quantity;
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    }
}

// Function to remove item from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to view the current state of the cart
function viewCart() {
    echo "Your Cart:
";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // You would ideally retrieve product info from a database using this ID
        echo "$product_id - $quantity
";
    }
}

// Example usage:
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            addToCart($_POST['product_id'], $_POST['quantity']);
            break;
        case 'update':
            updateQuantity($_POST['product_id'], $_POST['new_quantity']);
            break;
        case 'remove':
            removeFromCart($_POST['product_id']);
            break;
    }
}

viewCart();

?>


<?php
// Start the session
session_start();

// Define the cart key
$cartKey = 'cart';

// Function to add item to cart
function add_item_to_cart($id, $name, $price) {
    global $cartKey;
    
    // Get the current cart data from the session
    if (!isset($_SESSION[$cartKey])) {
        $_SESSION[$cartKey] = array();
    }
    $cart = $_SESSION[$cartKey];
    
    // Check if item is already in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $id) {
            $item['quantity']++;
            return;
        }
    }
    
    // Add new item to cart
    $cart[] = array('id' => $id, 'name' => $name, 'price' => $price, 'quantity' => 1);
    
    // Update the session with the updated cart data
    $_SESSION[$cartKey] = $cart;
}

// Function to remove item from cart
function remove_item_from_cart($id) {
    global $cartKey;
    
    // Get the current cart data from the session
    if (!isset($_SESSION[$cartKey])) {
        return false;
    }
    $cart = $_SESSION[$cartKey];
    
    // Remove the item from cart
    foreach ($cart as &$item) {
        if ($item['id'] == $id) {
            unset($item);
            return true;
        }
    }
    
    return false;
}

// Function to update quantity of an item in cart
function update_quantity_of_item_in_cart($id, $quantity) {
    global $cartKey;
    
    // Get the current cart data from the session
    if (!isset($_SESSION[$cartKey])) {
        return false;
    }
    $cart = $_SESSION[$cartKey];
    
    // Find the item in cart and update its quantity
    foreach ($cart as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] = $quantity;
            return true;
        }
    }
    
    return false;
}

// Function to get total cost of items in cart
function get_total_cost_of_items_in_cart() {
    global $cartKey;
    
    // Get the current cart data from the session
    if (!isset($_SESSION[$cartKey])) {
        return 0;
    }
    $cart = $_SESSION[$cartKey];
    
    // Calculate total cost
    $totalCost = 0;
    foreach ($cart as &$item) {
        $totalCost += $item['price'] * $item['quantity'];
    }
    
    return $totalCost;
}

// Example usage:
add_item_to_cart(1, 'Item 1', 10.99);
add_item_to_cart(2, 'Item 2', 9.99);
remove_item_from_cart(1);

print_r($_SESSION[$cartKey]);

echo get_total_cost_of_items_in_cart();
?>


<?php
session_start();

// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add item to cart
function addItemToCart($productId, $quantity) {
    global $_SESSION;
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    global $_SESSION;
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to update quantity of item in cart
function updateQuantityInCart($productId, $newQuantity) {
    global $_SESSION;
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $newQuantity;
    }
}

// Function to get total cost of items in cart
function getTotalCost() {
    global $_SESSION;
    $totalCost = 0;
    foreach ($_SESSION['cart'] as $product => $quantity) {
        // assuming product prices are stored in an array $productPrices
        $price = $productPrices[$product];
        $totalCost += $price * $quantity;
    }
    return $totalCost;
}

// Add item to cart
$productId = 1; // replace with actual product ID
$quantity = 2; // replace with actual quantity
addItemToCart($productId, $quantity);

// Remove item from cart
removeFromCart(1);

// Update quantity of item in cart
updateQuantityInCart(1, 3);

// Get total cost of items in cart
$totalCost = getTotalCost();
echo "Total Cost: $" . number_format($totalCost, 2);
?>


// Include the config file for session settings
include 'config.php';

// Start the session
session_start();

// Initialize an empty cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


function add_to_cart($product_id) {
    // Get the current product quantity from the session or set it to 1 if not set
    $quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
    
    // Increment the quantity by 1 and update the session cart array
    $_SESSION['cart'][$product_id] = $quantity + 1;
}

function remove_from_cart($product_id) {
    // Remove the product from the session cart array if it exists
    unset($_SESSION['cart'][$product_id]);
    
    // Reset the quantity to 0 for the product
    $_SESSION['cart'][$product_id] = 0;
}

function update_quantity($product_id, $new_quantity) {
    // Update the quantity of the product in the session cart array
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    }
}


// Example usage:
add_to_cart(1); // Add product with ID 1 to the cart
remove_from_cart(2); // Remove product with ID 2 from the cart
update_quantity(1, 5); // Update quantity of product with ID 1 in the cart

// Print the current cart contents for debugging purposes:
print_r($_SESSION['cart']);


<?php include 'cart.php'; ?>

<!-- HTML to display the cart contents -->
<h2>Cart Contents:</h2>
<ul>
    <?php foreach ($_SESSION['cart'] as $product_id => $quantity) { ?>
        <li><?php echo "Product $product_id: Quantity = $quantity"; ?></li>
    <?php } ?>
</ul>

<!-- Forms to add and remove products from the cart -->
<form action="" method="post">
    <input type="hidden" name="add_product" value="<?php echo 3; ?>">
    <button type="submit">Add Product 3 to Cart</button>
</form>

<form action="" method="post">
    <input type="hidden" name="remove_product" value="<?php echo 2; ?>">
    <button type="submit">Remove Product 2 from Cart</button>
</form>


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function add_to_cart($product_id) {
    // Add product to cart with quantity 1 by default
    $product_id = (int) $product_id;
    $_SESSION['cart'][$product_id] = 1;

    // Update the session variable
    $_SESSION['cart'] = serialize($_SESSION['cart']);
}

function update_cart($product_id, $quantity) {
    // Check if product exists in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update quantity
        $_SESSION['cart'][$product_id] = (int) $quantity;

        // Update the session variable
        $_SESSION['cart'] = serialize($_SESSION['cart']);
    }
}

function remove_from_cart($product_id) {
    // Check if product exists in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Remove product from cart
        unset($_SESSION['cart'][$product_id]);

        // Update the session variable
        $_SESSION['cart'] = serialize($_SESSION['cart']);
    }
}

function display_cart() {
    echo "Cart Contents:<br>";
    $cart_contents = unserialize($_SESSION['cart']);
    foreach ($cart_contents as $product_id => $quantity) {
        // Assume we have a function to get product details by ID
        $product_details = get_product_details($product_id);
        echo "$product_details[name] x $quantity<br>";
    }
}

function calculate_total() {
    $total = 0;
    $cart_contents = unserialize($_SESSION['cart']);
    foreach ($cart_contents as $product_id => $quantity) {
        // Assume we have a function to get product price by ID
        $product_price = get_product_price($product_id);
        $total += (float) $product_price * $quantity;
    }
    return $total;
}

// Example usage:
add_to_cart(1);  // Add product with ID 1 to cart
update_cart(1, 2);  // Update quantity of product with ID 1 in cart
remove_from_cart(1);  // Remove product with ID 1 from cart

display_cart();  // Display contents of cart
echo "Total: $" . calculate_total();
?>


// Initialize the session
session_start();


// Get or initialize the cart array from the session
$cart = &$_SESSION['cart'] ?: [];


// Function to add an item to the cart
function add_item_to_cart($item_id) {
    global $cart;

    // Check if item is already in cart
    if (isset($cart[$item_id])) {
        $cart[$item_id]['quantity']++;
    } else {
        $cart[$item_id] = [
            'name' => '', // Item name
            'price' => 0, // Item price
            'quantity' => 1,
        ];
    }
}


// Function to update an item's quantity in the cart
function update_cart_quantity($item_id, $action) {
    global $cart;

    // Check if item is in cart
    if (isset($cart[$item_id])) {
        switch ($action) {
            case 'increment':
                $cart[$item_id]['quantity']++;
                break;
            case 'decrement':
                if ($cart[$item_id]['quantity'] > 1) {
                    $cart[$item_id]['quantity']--;
                }
                break;
        }
    }
}


// Function to remove an item from the cart
function remove_item_from_cart($item_id) {
    global $cart;

    // Check if item is in cart
    unset($cart[$item_id]);
}


// Add items to the cart
add_item_to_cart(1); // Item 1: Phone (price: $500)
add_item_to_cart(2); // Item 2: Laptop (price: $1000)

// Update item quantities
update_cart_quantity(1, 'increment'); // Increment phone quantity
update_cart_quantity(2, 'decrement'); // Decrement laptop quantity

// Remove an item from the cart
remove_item_from_cart(1);


// config.php - contains database connection settings
<?php

define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'shop');

function getCartItems() {
    // Get cart items for the current user
    $query = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user']['id']."'";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = array(
            'product_id' => $row['product_id'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        );
    }
    
    return $items;
}

function updateQuantity($item_id, $new_quantity) {
    // Update the quantity of an item in the cart
    $query = "UPDATE cart SET quantity = '$new_quantity' WHERE id = '$item_id'";
    mysqli_query($conn, $query);
}

function addProductToCart($product_id, $quantity) {
    // Add a product to the user's cart
    $query = "INSERT INTO cart (user_id, product_id, quantity, price) VALUES ('".$_SESSION['user']['id']."', '$product_id', '$quantity', '".$_SESSION['products'][$product_id]['price']."')";
    
    if (!mysqli_query($conn, $query)) {
        echo 'Error adding product to cart';
    }
}

function removeProductFromCart($item_id) {
    // Remove a product from the user's cart
    $query = "DELETE FROM cart WHERE id = '$item_id'";
    mysqli_query($conn, $query);
}
?>


<?php

// Initialize session and database connection
session_start();
include 'config.php';

// Add product to cart (example)
addProductToCart(1, 2);

// View all items in the cart
$items = getCartItems();

foreach ($items as $item) {
    echo "ID: $item[product_id], Quantity: $item[quantity], Price: $item[price]";
}

?>

