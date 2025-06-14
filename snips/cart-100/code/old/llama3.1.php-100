
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

