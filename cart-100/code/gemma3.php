
<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulated Product Data - Replace with your actual data) ---

$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15],
];


// 1. Add an item to the cart
addToCart(1, 'T-Shirt', 20, 2);
echo "Added 2 T-Shirts to the cart.
";

// 2. Add another item
addToCart(2, 'Jeans', 50, 1);
echo "Added 1 Jeans to the cart.
";

// 3. Update the quantity of the T-Shirt
updateQuantity(1, 5); // Increase the quantity of T-Shirts to 5
echo "Updated T-Shirt quantity to 5.
";

// 4. Get the cart contents
$cart = getCartContents();
echo "Current Cart Contents:
";
print_r($cart);


// 5.  Remove an item
removeCartItem(2); // Remove the Jeans
echo "Removed Jeans from the cart.
";

// Display updated cart contents
$cart = getCartContents();
print_r($cart);

?>


<?php

session_start();

// Cart array to store items
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $cart[$product_id]['quantity'] += $quantity;
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    unset($cart[$product_id]);
}

// Function to get the cart contents
function get_cart_contents() {
    return $cart;
}

// Example Usage - Simulated Products
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Shoes', 'price' => 80],
];

// ---  Example User Interaction (Simulated) ---

// 1. User adds a T-Shirt to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
echo "T-Shirt added to cart (quantity: 2) <br>";

// 2. User updates the quantity of Jeans to 3
update_cart_quantity(2, 3);
echo "Jeans quantity updated to 3 <br>";

// 3. User removes Shoes from the cart
remove_from_cart(3);
echo "Shoes removed from cart <br>";

// 4. Display the cart contents
$cart_contents = get_cart_contents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// -----------------------  Session Management  -----------------------
// Add Cart Data to Session
$_SESSION['cart'] = $cart;

// Display Cart Contents from Session (for demonstration)
echo "<h2>Cart Contents from Session:</h2>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}


?>


<?php

// Session Start
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the product to the cart array
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


// Example Usage (Simulating user actions)

// 1. Add an item to the cart
addToCart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, Price $1200, Quantity 1
addToCart(2, "Mouse", 25, 2); // Product ID 2, Mouse, Price $25, Quantity 2

// 2. Update the quantity of an item
updateQuantity(1, 3);  // Change the quantity of Laptop to 3

// 3. Get the cart contents
$cart_contents = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart_contents);
echo "</pre>";

// 4. Calculate the total
$total = calculateTotal();
echo "<p>Total Cart Value: $" . $total . "</p>";

// 5. Remove an item from the cart
removeCartItem(2); // Remove the Mouse
?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize an empty cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Handle the case where the product isn't in the cart (e.g., remove from cart)
    // You could also add it with a quantity of 1 if desired
    // For simplicity, we'll just do nothing here.  A better solution would be to return
    // an error message or use an appropriate flag.
    echo "Product " . $product_id . " not found in cart."; // or handle differently
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];
    $total_item = $price * $quantity;
    $total += $total_item;

    echo "<tr>";
    echo "<td>" . $name . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total_item, 2) . "</td>";
    echo "<td><a href='cart.php?remove=" . $product_id . "'>Remove</a></td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<p><strong>Total: $" . number_format($total, 2) . "</p>";
}

// Example Usage (simulated product data)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200],
  2 => ['name' => 'Mouse', 'price' => 25],
  3 => ['name' => 'Keyboard', 'price' => 75]
];

// Simulate adding items to the cart (you would typically get this from a form submission)
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id'];  // Important: Cast to integer
  $quantity = (int)$_POST['quantity']; // Cast to integer
  addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
}

if (isset($_POST['update_quantity'])) {
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];
  updateQuantity($product_id, $quantity);
}

// Display the cart contents
displayCart();

?>

<!-- HTML form to add items to the cart -->
<form method="post" action="">
  <h2>Add to Cart</h2>
  <?php
  foreach ($products as $id => $product) {
    echo "<label for='" . $id . "'>" . $product['name'] . " ($" . number_format($product['price'], 2) . ")</label><br>";
    echo "<input type='number' id='" . $id . "' name='product_id' value='" . $id . "' min='1' max='10'><br>"; // Added min and max for input validation
  }
  ?>
  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<!-- HTML form to update the quantity of items -->
<form method="post" action="">
  <h2>Update Quantity</h2>
  <?php
  foreach ($products as $id => $product) {
    echo "<label for='" . $id . "'>" . $product['name'] . "</label><br>";
    echo "<input type='number' id='" . $id . "' name='product_id' value='" . $id . "' min='1' max='10'><br>"; // Added min and max
  }
  ?>
  <button type="submit" name="update_quantity">Update Quantity</button>
</form>


<?php
session_start();

// --- Cart Operations ---

// 1. Add item to cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][$product_id] = [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    ];
}

// 2. Update item quantity in cart
function update_cart_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// 3. Remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// 4. Get cart items
function get_cart_items() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return [];
    }
}

// 5. Calculate total cart value
function calculate_cart_total() {
    $total = 0;
    $cart_items = get_cart_items();

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// --- Example Usage (Simulated Product Data) ---

// Sample product data (replace with your actual database)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];

// ---  Session Management and User Interaction (Simulated) ---
// For demonstration purposes, we'll simulate a user adding an item.
// In a real application, you'd get this from a form or other user input.

// 1. Add a Laptop to the cart (Product ID 1)
add_to_cart(1, $products[1]['name'], $products[1]['price'], 1);

// 2.  Update the quantity of the Laptop to 2
update_cart_quantity(1, 2);

// 3. Get the cart contents
$cart_items = get_cart_items();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// 4. Calculate and display the total
$total = calculate_cart_total();
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";

// 5. Remove the Mouse from the cart
remove_from_cart(2);

// Display updated cart
echo "<p><strong>Updated Cart Items:</strong></p>";
$cart_items = get_cart_items();

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}
?>


<?php
session_start();

// Function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Example Usage:  Let's say we have products with IDs 1, 2, and 3
// You'll typically get this data from a database or another source

// Add a product to the cart
addToCart(1, "T-Shirt", 20.00, 2); // Product ID 1, "T-Shirt", $20.00, quantity 2
addToCart(2, "Jeans", 50.00, 1); // Product ID 2, "Jeans", $50.00, quantity 1


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example of removing an item
// removeFromCart(1); // Remove the T-Shirt from the cart


// Example of updating quantity
// updateQuantity(2, 3); // Update the Jeans quantity to 3
?>


<?php

// 1. Initialize the session if it doesn't exist
session_start();

// 2. Define a function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart session variable exists. If not, initialize it as an array.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart.
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already in the cart - increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in the cart - add it
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// 3. Example usage:  Let's say we have product IDs 1, 2, and 3

// Add product 1 to the cart - quantity 2
addToCart(1, "Awesome T-Shirt", 20, 2);

// Add product 2 to the cart - quantity 1
addToCart(2, "Cool Hat", 15, 1);

// Add product 1 again - quantity 1 (to demonstrate increasing quantity)
addToCart(1, "Awesome T-Shirt", 20, 1);

// 4. Display the cart contents (for demonstration purposes)
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

echo "<p><strong>Total Cost:</strong> $" .  totalCartCost() . "</p>";
?>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// -------------------- Functions for Cart Management --------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0;  // Initialize count if not already present
    }
    $_SESSION['cart'][$product_id] += $quantity;
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets all items in the cart.
 *
 * @return array  An array containing the items in the cart.
 */
function get_cart_items() {
    return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int
 */
function cart_total() {
    $total = 0;
    foreach($_SESSION['cart'] as $quantity => $qty) {
        $total = $total + $qty;
    }
    return $total;
}



// -------------------- Example Usage (Simulated Product Add/Remove) --------------------

// Example 1: Add a product to the cart
add_to_cart(123, 2); // Add 2 units of product with ID 123

// Example 2: Add another unit of the same product
add_to_cart(123, 1);

// Example 3:  Remove a product
// remove_from_cart(123);

// Example 4: Update the quantity of a product
// update_cart_quantity(123, 3);


// -------------------- Displaying the Cart --------------------

// Get the cart items
$cart_items = get_cart_items();

// Print the cart items
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $quantity) {
        //  In a real application, you'd retrieve product details here 
        //  based on the $product_id
        echo "<li>Product ID: $product_id, Quantity: $quantity</li>";
    }
    echo "</ul>";
    echo "<p>Cart Total: " . cart_total() . "</p>";
}


//  You would typically:
// 1.  Replace the product ID's with actual product IDs from your database.
// 2.  Retrieve product details (name, price, etc.) for each product in the cart.
// 3.  Implement a mechanism for users to view and modify the cart contents.
// 4.  Integrate this cart functionality with your shopping cart system.

?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    
    // Optional:  If you want to remove items with 0 quantity
    // if (empty($_SESSION['cart'])) {
    //   $_SESSION['cart'] = array();
    // }
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart items
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return array(); // Return an empty array if the cart is empty
}

// Example Usage (Simulating a product listing and a user action)
// In a real application, you'd get this data from a database or other source.

$products = array(
  1 => array('name' => 'Shirt', 'price' => 20),
  2 => array('name' => 'Pants', 'price' => 30),
  3 => array('name' => 'Shoes', 'price' => 50)
);

// 1. Add items to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Validate the product ID
  if (array_key_exists($product_id, $products)) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  }
}

// 2. Remove an item
if (isset($_GET['remove_item'])) {
  $product_id = $_GET['remove_item'];
  removeFromCart($product_id);
}


// 3. Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($product_id, $new_quantity);
}

// 4. Display the cart items
$cart_items = getCartItems();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
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
          <th>Quantity</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item_id => $product): ?>
          <tr>
            <td><?php echo $product['name']; ?></td>
            <td>$<?php echo number_format($product['price'], 2); ?></td>
            <td><?php echo $product['quantity']; ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button type="submit">Remove</button>
              </form>
              <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>">
                <button type="submit">Update</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p>Total: $<?php
      $total = 0;
      foreach ($cart_items as $product_id => $product) {
        $total += $product['price'] * $product['quantity'];
      }
      echo number_format($total, 2);
    ?>
    </p>
  <?php endif; ?>

  <hr>
  <p>Add Items:</p>
  <form method="post">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <?php
      foreach ($products as $id => $product): ?>
        <option value="<?php echo $id; ?>"><?php echo $id; ?></option>
      <?php endforeach; ?>
    </select>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

</body>
</html>


<?php

// Start a session (if not already started)
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// ---  Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price per unit of the product.
 */
function addToCart(int $product_id, string $product_name, int $quantity, float $price) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already exists, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        );
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function updateQuantity(int $product_id, int $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart(int $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents.
 */
function getCartContents() {
    return $_SESSION['cart'];
}


// --- Example Usage  ---

// 1. Adding items to the cart
addToCart(1, "T-Shirt", 2, 20.00);
addToCart(2, "Jeans", 1, 50.00);

// 2. Updating the quantity of an item
updateQuantity(1, 5); // Increase the quantity of T-Shirt to 5

// 3. Removing an item from the cart
removeFromCart(2); // Remove Jeans

// 4. Getting the current cart contents
$cart = getCartContents();

// Print the cart contents to the browser
echo "<h2>Your Shopping Cart:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_data) {
        echo "<li>";
        echo "<strong>Product Name:</strong> " . $product_data['name'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
        echo "<strong>Price per unit:</strong> " . $product_data['price'] . "<br>";
        echo "<strong>Total Price for this item:</strong> " . $product_data['quantity'] * $product_data['price'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

?>


<?php
session_start(); // Start the session

// --- Cart Functions ---

// Add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][] = [
    'id' => $item_id,
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Update quantity of an existing item
function updateQuantity($item_id, $quantity) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use &$item to modify the original item
      if ($item['id'] == $item_id) {
        $item['quantity'] = $quantity;
        break;
      }
    }
  }
}

// Remove an item from the cart
function removeFromCart($item_id) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    foreach ($cart as $key => $item) {
      if ($item['id'] == $item_id) {
        unset($cart[$key]); // Remove the item from the array
        break;
      }
    }
  }
}

// Get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return [];
}

// Calculate the cart total
function calculateCartTotal() {
    $total = 0;
    $cart = getCartContents();

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Example Usage (Demonstration) ---

// Example: Add an item to the cart
addToCart(1, "Awesome T-Shirt", 25.00, 2);
addToCart(2, "Cool Mug", 10.00, 1);

// Example: Update the quantity of an item
updateQuantity(1, 3); // Increase quantity of Awesome T-Shirt to 3

// Example: Remove an item from the cart
// removeFromCart(2); // Remove Cool Mug

// Get the current cart contents
$cart = getCartContents();
echo "<pre>";
print_r($cart);
echo "</pre>";

// Calculate and display the total
$total = calculateCartTotal();
echo "Total Cart Value: $" . number_format($total, 2) . "<br>";
?>


<?php

// Start a session if it doesn't exist
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add the item to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example usage:
// Add some items to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);

// Update the quantity of an item
updateQuantity(1, 3);

// Remove an item from the cart
// removeFromCart(2);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
    }
    echo "</ul>";
}

?>


<?php

session_start();

// Session variables for the cart
$cart = array();

// Helper functions for cart operations

/**
 * Add an item to the cart.
 *
 * @param string $product_id   The ID of the product being added.
 * @param string $name         The name of the product.
 * @param int    $quantity   The quantity of the product to add.
 * @param float  $price       The price of a single unit of the product.
 */
function addToCart(string $product_id, string $name, int $quantity, float $price) {
  $product = array(
    'id' => $product_id,
    'name' => $name,
    'quantity' => $quantity,
    'price' => $price
  );

  // Check if the product is already in the cart
  foreach ($cart as $key => $item) {
    if ($item['id'] == $product['id']) {
      // Update the quantity if the product already exists
      $cart[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If the product is not in the cart, add it
  $cart[$product['id']] = $product;
}


/**
 * Get the total price of the cart.
 *
 * @return float  The total price.
 */
function getCartTotal() {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return round($total, 2); // Round to 2 decimal places
}

// Example usage:  Simulating a user adding items to the cart
addToCart('product1', 'T-Shirt', 2, 20.00);
addToCart('product2', 'Jeans', 1, 50.00);
addToCart('product3', 'Shoes', 1, 80.00);



// Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
  echo "<p>Total: $" . getCartTotal() . "</p>";
}


// Example:  Clearing the cart (for demonstration purposes)
//session_unset($_SESSION['cart']);
//session_destroy();

?>


<?php

session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example Usage (simulating a product add to cart)
// This would typically be triggered by a form submission

// // Let's assume the product ID is 1, product name is "T-Shirt", price is $20
// addToCart(1, "T-Shirt", 20, 2);

// // Let's assume the product ID is 2, product name is "Jeans", price is $50
// addToCart(2, "Jeans", 50, 1);

// // Update the quantity of the "T-Shirt" to 3
// updateQuantity(1, 3);


// Display the cart contents
$cart = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example to remove a cart item (uncomment to use)
//removeCartItem(1);
?>


<?php

session_start();

// Cart data (in a real application, this would likely be stored in a database)
$cart = [];

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $item_price, $quantity = 1) {
  global $cart;

  // Check if the item is already in the cart
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    $cart[$item_id] = [
      'name' => $item_name,
      'price' => $item_price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;
  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $quantity) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $quantity;
  }
}

// Function to get the cart total
function get_cart_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Function to display the cart contents
function display_cart() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . number_format($item['price'], 2) . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Price per item: $" . number_format($item['price'], 2) . "<br>";
    echo "Subtotal: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
    echo "Remove: <a href='cart.php?action=remove&id=" . $item_id . "'>Remove</a><br>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . number_format(get_cart_total(), 2) . "</p>";
}


// Handle user actions (e.g., adding to cart, removing from cart)
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
  $item_id = $_GET['id'];
  remove_from_cart($item_id);
  // Redirect back to the cart page
  header("Location: cart.php");
  exit;
}

if (isset($_GET['update']) && $_GET['update'] == 'quantity') {
    $item_id = $_GET['id'];
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer
    update_quantity($item_id, $quantity);
    header("Location: cart.php");
    exit;
}

// Example product data (replace with your actual product data)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Shoes', 'price' => 80.00]
];



?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Product Listing (replace with your product listing logic) -->
  <h2>Available Products</h2>
  <ul>
    <?php
    foreach ($products as $item_id => $product) {
      echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "<br>";
      echo "<form method='post' action='cart.php'>
                <label for='quantity_" . $item_id . "'>Quantity:</label>
                <input type='number' id='quantity_" . $item_id . "' name='quantity' value='1' min='1'>
                <input type='hidden' name='id' value='" . $item_id . "'>
                <input type='submit' value='Add to Cart'>
              </form>
            </li>";
    }
    ?>
  </ul>

  <?php display_cart(); ?>

</body>
</html>


<?php
session_start(); // Start the session

// Cart initialization - Initialize an empty cart in the session.
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If yes, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If no, add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example Usage (Simulated Product Data)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];

//  Simulate user actions (Example)
// Add a laptop to the cart
addToCart(1, 'Laptop', $products[1]['price']);

// Add a mouse to the cart
addToCart(2, 'Mouse', $products[2]['price'], 2); // Add two mice

//  Add a keyboard to the cart
addToCart(3, 'Keyboard', $products[3]['price']);

// Update the quantity of the mouse
updateCartQuantity(2, 5);

// Remove the keyboard from the cart
removeCartItem(3);

// Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " - Total: $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>


<?php
session_start();

// --- Cart Functions ---

// Add item to cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][$product_id] = [
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Update quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // Product doesn't exist in cart, create a new item
        add_to_cart($product_id, $product_name, $price, $quantity); // Use the updated quantity
    }
}

// Remove item from cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Get cart items
function get_cart_items() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return [];
  }
}

// Calculate cart total
function calculate_cart_total() {
    $total = 0;
    $cart_items = get_cart_items();

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// --- Example Usage (In a Web Page - e.g., index.php) ---

// Example product data (simulated)
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80]
];

// --- Handle Add to Cart Request (e.g., from a form submission) ---

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart_id']) && isset($_POST['quantity'])) {
        $product_id = (int)$_POST['add_to_cart_id']; // Cast to integer
        $quantity = (int)$_POST['quantity'];
        add_to_cart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        update_cart_quantity($product_id, $quantity);
    } elseif (isset($_POST['remove_from_cart_id'])) {
        $product_id = (int)$_POST['remove_from_cart_id'];
        remove_from_cart($product_id);
    }
}

// --- Display Cart Contents ---

$cart_items = get_cart_items();
$cart_total = calculate_cart_total();

echo "<h1>Your Shopping Cart</h1>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
}

?>


<?php
session_start();

// ---------------------------------------------------
// Function to Add Item to Cart
// ---------------------------------------------------
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Add the product to the cart
  $_SESSION['cart'][] = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // You can optionally update the quantity if the item already exists
  updateCartQuantity($product_id, $quantity);
}

// ---------------------------------------------------
// Function to Update Cart Quantity
// ---------------------------------------------------
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use &$item to modify the original array
      if ($item['id'] == $product_id) {
        $item['quantity'] = $quantity;
        break;
      }
    }
  }
}

// ---------------------------------------------------
// Function to Get Cart Items
// ---------------------------------------------------
function getCartItems() {
  return $_SESSION['cart'] ?? []; // Returns the cart, or an empty array if it doesn't exist
}

// ---------------------------------------------------
// Function to Remove Item From Cart
// ---------------------------------------------------
function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                // Optional:  Re-index the array to avoid gaps
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
}


// ---------------------------------------------------
// Example Usage (Demonstration)
// ---------------------------------------------------

// Add some products to the cart
addToCart(1, "Shirt", 25.00, 2);
addToCart(2, "Shoes", 75.00, 1);
addToCart(1, "Shirt", 25.00, 3); // Add more of the shirt

// Display the cart items
$cart_items = getCartItems();
echo "<h2>Your Cart:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Remove an item
removeItemFromCart(2);

// Display the cart items again
$cart_items = getCartItems();
echo "<h2>Your Cart (After Removal):</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>


<?php

session_start();

// Check if the cart exists, if not, initialize it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ------------------------------------------------------------------
// Example Functions (You'll need to implement these)
// ------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product being added.
 * @param string $product_name The name of the product.  (Optional, for display)
 * @param int $quantity The quantity of the product to add. Defaults to 1.
 * @return void
 */
function add_to_cart(string $product_id, string $product_name = '', int $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['name' => $product_name, 'quantity' => $quantity, 'price' => 0]; // Initialize with price
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product being updated.
 * @param int $new_quantity The new quantity for the product.
 * @return void
 */
function update_cart_quantity(string $product_id, int $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total price of items in the cart.
 *
 * @return float The total price.
 */
function calculate_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}


/**
 * Returns the cart contents
 * @return array  The cart array
 */
function get_cart() : array{
    return $_SESSION['cart'];
}


// ------------------------------------------------------------------
// Example Usage (Illustrative - you'll integrate this into your form/logic)
// ------------------------------------------------------------------

// Example: Adding an item to the cart
add_to_cart('product1', 'Awesome T-Shirt', 2);
add_to_cart('product2', 'Cool Mug', 1);

// Example: Updating the quantity
update_cart_quantity('product1', 5);


// Example: Getting and displaying the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculate_cart_total() . "</p>";
}

// Example: Removing an item
//remove_from_cart('product1');


// ------------------------------------------------------------------
// Important Considerations and Next Steps
// ------------------------------------------------------------------

// 1.  Database Integration:  This example uses an in-memory session.  You'll *absolutely* need to integrate this with your database to store cart data persistently.  You'll need a table to store cart items, and code to read/write to that table.

// 2.  Product Information:  You'll want to fetch product details (name, price, etc.) from your database based on the product ID. This is crucial for displaying accurate information in the cart.

// 3.  User Authentication:  In a real application, you'll need user authentication to associate carts with specific users.

// 4.  Error Handling: Add error handling (e.g., checking for invalid product IDs, handling database errors).

// 5.  Quantity Validation:  Validate the quantity being added/updated to prevent negative quantities or extremely large numbers.

// 6.  Security:  Protect your session data from unauthorized access and modification.

// 7.  Testing:  Thoroughly test your cart implementation with various scenarios (adding, removing, updating, calculating total).
?>


<?php
session_start();

// Initialize the cart (an array to hold items)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Item exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to display the cart contents
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $item_id => $item_details) {
            echo "<li>";
            echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " x " . $item_details['quantity'];
            echo "<br>";
            echo "<form method='post'>";
            echo "<label for='quantity_" . $item_id . "'>Quantity:</label>";
            echo "<input type='number' id='quantity_" . $item_id . "' value='" . $item_details['quantity'] . "' min='1' max='" . $item_details['quantity'] . "' name='quantity_" . $item_id . "' >";
            echo "<button type='submit' name='update_quantity_" . $item_id . "'>Update</button>";
            echo "</form>";

            echo "<br>";
            echo "<form method='post'>";
            echo "<button type='submit' name='remove_" . $item_id . "'>Remove</button>";
            echo "</form>";
            echo "<br>";
        }
        echo "</ul>";

        // Calculate the total price
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item_id => $item_details) {
            $total_price += $item_details['price'] * $item_details['quantity'];
        }
        echo "<p><strong>Total: $" . $total_price . "</strong></p>";
    }
}

// Handle form submissions
if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['update_quantity'];
    $new_quantity = intval($_POST['quantity_' . $item_id]); // Make sure it's an integer
    updateQuantity($item_id, $new_quantity);
}

if (isset($_POST['remove'])) {
    $item_id = $_POST['remove'];
    removeFromCart($item_id);
}

// Display the cart
displayCart();

?>


<?php

// Start a session to store cart data
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if it exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Example usage:  These are just examples - you would typically get this data from
// a form submission or other user input.

// Add some items to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);
addToCart(1, 'T-Shirt', 20, 3); // Add more of the existing item


// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>" . $product_data['name'] . "</strong> - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item
//removeFromCart(2);

// Example: Update Quantity
// updateQuantity(1, 5); // Change the quantity of T-Shirt to 5
//echo "<h2>Your Cart (After Update)</h2>";
//displayCart();

?>


<?php
session_start();

// Session variables to store cart items
$cart = [];

// --- Functions to handle cart operations ---

/**
 * Add an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart(int $productId, int $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array if it doesn't exist
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If the product exists, increase the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If the product doesn't exist, add it to the cart with quantity 1
    $_SESSION['cart'][$productId] = ['quantity' => $quantity];
  }
}


/**
 * Update the quantity of an item in the cart.
 *
 * @param int $productId The ID of the product to update.
 * @param int $newQuantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity(int $productId, int $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}


/**
 * Remove an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function remove_from_cart(int $productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}


/**
 * Get the contents of the cart.
 *
 * @return array The cart array.
 */
function get_cart() {
  return $_SESSION['cart'];
}



// --- Example Usage (Demonstration) ---

// 1. Add an item to the cart
add_to_cart(123, 2); // Add product ID 123 with quantity 2

// 2. Add another item to the cart
add_to_cart(456, 1);

// 3. Update the quantity of product 123 to 5
update_cart_quantity(123, 5);


// 4. Remove product 456 from the cart
remove_from_cart(456);



// 5. Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = get_cart();

if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $productId => $item) {
        echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
    }
}
echo "</ul>";


// ---  Important Notes: ---

// 1.  Session Start: `session_start()` must be called at the beginning of every PHP script that uses sessions.  It initializes the session.

// 2. Persistence:  Session data (like the cart) is stored on the server.  When a user closes their browser, the session data is typically lost unless you use a persistent storage mechanism like a database or a cookie.

// 3.  Security:  Sessions should be used carefully for sensitive data.  Protect your session IDs using HTTPS and consider using encryption for data stored in sessions.

// 4. Data Validation:  Always validate user input (product IDs, quantities, etc.) to prevent errors and security vulnerabilities.

// 5.  Cart Structure: This example uses an associative array to represent the cart. The keys of the array are the product IDs, and the values are associative arrays containing the 'quantity' of each product.  You can adapt the cart structure to fit your specific needs.

// 6. Error Handling: In a real application, you would add more robust error handling and logging.

// 7.  Integration with your website: This is just a basic example.  You'll need to integrate this code into your website's logic, likely with form submissions to add items to the cart.
?>


<?php
session_start();

// 1.  Handle Adding Items to the Cart
function addItemToCart($productId, $quantity = 1, $productName = null) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$productId] = array(
      'quantity' => $quantity,
      'name' => $productName ?? $productId, // Use productName if available, otherwise use product ID
    );
  }
}

// Example: Adding a product to the cart
addItemToCart(123, 2); // Add product with ID 123, quantity 2
addItemToCart(456, 1, 'Awesome T-Shirt'); // Add product with ID 456, quantity 1, product name 'Awesome T-Shirt'


// 2. Handle Removing Items from the Cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Example: Remove an item from the cart
removeItemFromCart(123);

// 3. Handle Updating Quantity of an Item in the Cart
function updateQuantity($productId, $newQuantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
  }
}

// Example: Update the quantity of product with ID 456 to 3
updateQuantity(456, 3);



// 4.  Display the Cart Contents (for demonstration)
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $id => $item) {
    echo "<li>";
    echo "Product ID: " . $id . "<br>";
    echo "Product Name: " . $item['name'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Subtotal: $" . $item['quantity'] * 10  . "<br>"; // Assuming price is $10 per item
    echo "</li>";
  }
  echo "</ul>";
}

// 5. Session Management - Important Considerations:
//    - Session starts:  session_start() must be called at the beginning of each script
//    - Session Destroy:  You should destroy the session when the user logs out or leaves the site.
//      Example: session_destroy();
?>


<?php

// Start session
session_start();

// Cart data (in a real application, this would be stored in a database)
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = [
      'product_id' => $product_id,
      'product_name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}

// Function to display the cart
function displayCart() {
  echo "<h2>Your Cart</h2>";

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($cart as $product_id => $product) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product['product_name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product['quantity'] . "<br>";
    echo "<form method='post'>";
    echo "<label for='quantity'>" . $product_id . " Quantity:</label>";
    echo "<input type='number' id='quantity' name='quantity" . $product_id . "' value='" . $product['quantity'] . "' min='1'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total
  $total = 0;
  foreach ($cart as $product_id => $product) {
      $total += $product['price'] * $product['quantity'];
  }

  echo "<p><strong>Total:</strong> $" . $total . "</p>";
}


// --- Example Usage ---

// Add some items to the cart
addToCart(1, "T-Shirt", 20, 2);
addToCart(2, "Jeans", 50, 1);
addToCart(1, "T-Shirt", 20, 3); // Add more of the same item

// Display the cart
displayCart();

// Update the quantity of a product
if (isset($_POST['quantity1'])) {
    updateQuantity(1, $_POST['quantity1']); // Update T-Shirt quantity
}
// Display the cart again after update
displayCart();


// --- Cleanup (Optional - for demonstration only) ---
// To clear the cart for the next session:
// session_destroy();
// session_start();
?>


<?php

session_start(); // Start the PHP session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Example Functions to Add/Remove Items ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int    $quantity   The quantity of the product to add.
 * @param float  $price      The price of the product.
 *
 * @return void
 */
function add_to_cart(string $product_id, string $product_name, int $quantity, float $price) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product exists, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        );
    }
}


/**
 * Removes a product from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 *
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $newQuantity The new quantity.
 *
 * @return void
 */
function update_cart_quantity(string $product_id, int $newQuantity) {
  if(isset($_SESSION['cart'][$product_id])){
    $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
  }
}

// --- Example Usage (Simulating a Product Purchase) ---

// Add a product to the cart
add_to_cart('product1', 'Awesome T-Shirt', 2, 25.00);

// Add another product
add_to_cart('product2', 'Cool Hat', 1, 15.00);

// Update the quantity of product1 to 5
update_cart_quantity('product1', 5);


// Remove a product from the cart
remove_from_cart('product2');



// --- Displaying the Cart Contents (for demonstration) ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        echo "<li>";
        echo "<strong>" . $product_data['name'] . "</strong> - Quantity: " . $product_data['quantity'] . " - Price: $" . $product_data['price'] . "</li>";
    }
    echo "</ul>";
}


// ---  End of the Session  (Important!) ---
// In a real application, you'd likely have a logout or session expiration mechanism
// to properly terminate the session.
?>


<?php

// Initialize the session if it doesn't exist
session_start();

// **1. Cart Session Variables**

// Define the session variables to store the cart data.
// These are standard keys, but you can customize them.

//   - 'cart': An array to store the products in the cart.
//   - 'total_items': The total number of items in the cart.
//   - 'total_price': The total price of the cart.

$_SESSION['cart'] = array();
$_SESSION['total_items'] = 0;
$_SESSION['total_price'] = 0.00;


// **2. Helper Functions**

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    global $_SESSION; // Access the session variables

    $product_array = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );

    // Check if the product is already in the cart
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            // Update the quantity if the product exists
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            return;
        }
    }

    // If the product is not in the cart, add it.
    $_SESSION['cart'][] = $product_array;

    // Update the cart totals
    $_SESSION['total_items'] += $quantity;
    $_SESSION['total_price'] += ($price * $quantity);
}

// Function to remove an item from the cart by ID
function remove_from_cart($product_id) {
    global $_SESSION;

    // Iterate through the cart and remove the product by ID
    $keys_to_remove = array();
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            $keys_to_remove[] = $key;
        }
    }

    // Remove the items from the array
    foreach ($keys_to_remove as $key) {
        unset($_SESSION['cart'][$key]);
    }

    // Update cart totals
    $_SESSION['total_items'] -= $_SESSION['cart'][count($_SESSION['cart']) - 1]['quantity'];
    $_SESSION['total_price'] -= ($_SESSION['cart'][count($_SESSION['cart']) - 1]['price'] * $_SESSION['cart'][count($_SESSION['cart']) - 1]['quantity']);
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $_SESSION;

    // Find the product in the cart
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            // Update the quantity
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;

            // Update the cart totals
            $_SESSION['total_items'] += $new_quantity - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION['cart'][$key]['quantity'] - ($_SESSION']['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION']['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION']['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION']['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION']['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION['cart']['quantity'] - ($_SESSION'][$

print("Correct answer:", length)


<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.  Defaults to 1.
 * @param float $price The price of a single unit of the product.
 *
 * @return bool True if the product was added, false otherwise (e.g., invalid quantity).
 */
function addToCart(string $product_id, string $product_name, int $quantity = 1, float $price) {
    // Validate quantity -  important to prevent issues
    if ($quantity <= 0) {
        return false; // or you could log an error
    }

    // If cart doesn't exist, initialize it.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already exists, update quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product not in cart, add it.  Store other information too.
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        ];
    }

    return true;
}

/**
 * Retrieves the items in the cart.
 *
 * @return array An array containing the items in the cart.  Empty if the cart is empty.
 */
function getCartItems() {
    return $_SESSION['cart'] ?? []; // Use null coalesce operator for empty cart check.
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return bool True if the quantity was updated, false otherwise (e.g., invalid quantity).
 */
function updateCartItem(string $product_id, int $new_quantity) {
    if ($new_quantity <= 0) {
        return false; // Or log an error
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        return true;
    } else {
        return false; // Product not in cart
    }
}

/**
 * Removes a product from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return bool True if the product was removed, false otherwise (e.g., product not in cart).
 */
function removeCartItem(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    } else {
        return false; // Product not in cart
    }
}

/**
 * Calculates the total price of the cart.
 *
 * @return float The total price of the cart.
 */
function calculateCartTotal() {
    $total = 0.0;
    $cart_items = getCartItems();

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// --- Example Usage ---

// Add an item to the cart
addToCart('product1', 'Awesome T-Shirt', 2, 25.00);
addToCart('product2', 'Cool Mug', 1, 10.00);

// Display cart items
$cart_items = getCartItems();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// Calculate and display the total
$total = calculateCartTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";

// Update quantity of an item
updateCartItem('product1', 5);

// Display updated cart
$cart_items = getCartItems();
echo "<h2>Updated Cart Items:</h2>";
foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}

// Remove an item
removeCartItem('product2');

echo "<p>Cart after removing item.</p>";
$cart_items = getCartItems();
foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}
?>


<?php
session_start();

// Array to store cart items
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $cart = [
      $product_id => [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      ]
    ];
  } else {
    // Check if the item is already in the cart
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] += $quantity;
    } else {
      $cart[$product_id] = [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      ];
    }
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart contents
function get_cart_contents() {
  return $cart;
}


// Example Usage (Illustrative - This would be in a form submission handler)

//Simulate a form submission (replace with actual form handling)
if (isset($_POST['action'])) {
  if ($_POST['action'] == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    add_to_cart($product_id, $product_name, $price, $quantity);
    // You'd likely redirect the user here or display a success message
  } elseif ($_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
  } elseif ($_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($product_id, $new_quantity);
  }
}


// Output the cart contents (for demonstration)
if (!empty($_SESSION['cart'])) {
  echo "<h2>Your Cart</h2>";
  foreach ($cart as $product_id => $item) {
    echo "<h3>" . $item['name'] . "</h3>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
  }
} else {
  echo "<p>Your cart is empty.</p>";
}
?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $productName = null) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = [
      'quantity' => $quantity,
      'name' => $productName ?? $productId, // Use product name if available, otherwise product ID
    ];
  }
}

// Function to remove an item from the cart
function removeCartItem($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Price:</strong>  (Assume price is stored in a database - e.g.,  $10.00)"; // Replace with your actual price retrieval
    echo "<form action='update_cart.php' method='post'>";
    echo "<input type='hidden' name='productId' value='" . $productId . "'>";
    echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'>";
    echo "<input type='submit' value='Update Quantity'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total price (replace with your actual price retrieval)
  $total = 0;
  foreach ($_SESSION['cart'] as $productId => $item) {
    $total += $item['quantity'] * 10.00;  // Example price, change to your actual price
  }

  echo "<p><strong>Total:</strong> $" . $total . "</p>";
}

// Example Usage (to add an item to the cart)
// addToCart(123, 2);  // Add product with ID 123, quantity 2

// Example Usage (to remove an item from the cart)
// removeCartItem(123);

// Example Usage (to update the quantity of an item in the cart)
// updateCartItemQuantity(123, 3);

//  To display the cart contents
displayCart();

?>


<?php
session_start();

// Function to add item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove item from the cart
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return array();
  }
}

// --- Example Usage (simulated product data - Replace with your actual product data) ---

$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);

// ---  Handling Cart Actions (Example - Replace with your actual form handling) ---

// 1. Add to Cart (Simulated)
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
  $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer
  addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  echo "Product '$products[$product_id]['name']' added to cart.<br>";
}

// 2. Update Quantity (Simulated)
if (isset($_POST['update_quantity'])) {
  $product_id = (int)$_POST['product_id'];
  $new_quantity = (int)$_POST['quantity'];
  updateQuantity($product_id, $new_quantity);
  echo "Quantity of Product '$products[$product_id]['name']' updated to $new_quantity.<br>";
}

// 3. Remove Item from Cart (Simulated)
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeItemFromCart($product_id);
    echo "Product '$products[$product_id]['name']' removed from cart.<br>";
}

// --- Displaying the Cart Contents (for demonstration) ---
$cart_contents = getCartContents();

if (!empty($cart_contents)) {
  echo "<h2>Cart Contents:</h2>";
  echo "<ul>";
  foreach ($cart_contents as $product_id => $item) {
    echo "<li>Product: " . $item['name'] . ", Price: $" . $item['price'] . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>


<?php
session_start(); // Start the session

// --- Cart Variables ---
$cart = [];  // Array to store items in the cart
$item_id = 1; // Unique ID for each item (for tracking)
$item_name = "Product A";
$item_price = 10.00;
$item_quantity = 1;

// --- Add to Cart Function ---
function add_to_cart($item_id, $item_name, $item_price, $item_quantity) {
    global $cart;

    // Check if the item is already in the cart
    if (isset($cart[$item_id])) {
        $cart[$item_id]['quantity'] += $item_quantity;
    } else {
        // Item not in cart, add it
        $cart[$item_id] = [
            'id' => $item_id,
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => $item_quantity
        ];
    }
}


// --- Example Usage (Add items to the cart) ---
add_to_cart($item_id, $item_name, $item_price, 2);
add_to_cart($item_id, $item_name, $item_price, 1);
add_to_cart($item_id, "Another Item", 25.00, 1);


// --- Display the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// --- Calculate Total Cart Value ---
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";

?>


<?php
session_start();

// Define some product information (for demonstration)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20, 'quantity' => 1],
    2 => ['name' => 'Jeans', 'price' => 50, 'quantity' => 2],
    3 => ['name' => 'Hat', 'price' => 15, 'quantity' => 1],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'][$productId])) {
        // Item already in cart, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it
        $_SESSION['cart'][$productId] = [
            'name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to get the cart total
function calculateCartTotal()
{
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Example Usage (Simulating User Interactions) ---

// 1. User adds a T-Shirt
addToCart(1);

// 2. User adds 2 Jeans
addToCart(2, 2);

// 3. User removes the Hat (product ID 3)
removeFromCart(3);

// 4. Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $itemId => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

echo "<p><strong>Total:</strong> $" . calculateCartTotal() . "</p>";

?>


<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions to Manage the Cart ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add. Defaults to 1.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


/**
 * Gets all items in the cart.
 *
 * @return array An array containing the products in the cart.
 */
function get_cart_items() {
  return $_SESSION['cart'];
}


/**
 * Calculates the total price of the cart.
 * 
 * @param array $cart_items The cart items to use for the calculation.
 * @return float The total price.
 */
function calculate_total(array $cart_items) {
    $total = 0;
    foreach ($cart_items as $product_id => $quantity) {
        // Assuming you have a product database/data to get the price
        // Replace this with your actual price retrieval logic
        $product_price = get_product_price($product_id);  
        $total_price_for_item = $product_price * $quantity;
        $total += $total_price_for_item;
    }
    return $total;
}


// --- Example Usage (Simulated) ---

// Add some products to the cart
add_to_cart(1, 2); // Product ID 1, quantity 2
add_to_cart(2, 1); // Product ID 2, quantity 1
add_to_cart(1, 3); // Product ID 1, quantity 3

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = get_cart_items();

foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";


// Calculate and display the total
$total = calculate_total($cart_items);
echo "<p><strong>Total Price: $" . $total . "</strong></p>";


// Remove a product from the cart
remove_from_cart(2);

// Display the cart contents after removal
echo "<p><strong>Cart Contents after removing product 2:</strong></p>";
$cart_items = get_cart_items();

foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}

// Update quantity of product 1
update_cart_quantity(1, 5);
echo "<p><strong>Cart Contents after updating quantity of product 1 to 5:</strong></p>";

$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}

?>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
      'total' => $price * $quantity
    );
  } else {
    // Item already in cart - increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}


// Example usage (for demonstration)
// You would typically get this data from your database or product catalog
$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);

// Add items to the cart
add_to_cart(1, 'Laptop', $products[1]['price']);
add_to_cart(2, 'Mouse', $products[2]['price'], 2); // Add 2 mice
add_to_cart(3, 'Keyboard', $products[3]['price']);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['total'] . "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item
// remove_from_cart(2);

// Example: Update quantity
// update_quantity(1, 3); // Change the quantity of Laptop to 3

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------------------------------------
// Helper Functions
// --------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array An array representing the cart contents.
 */
function getCartContents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cartItems An array of item details (product_id => price).
 * @return float The total price.
 */
function calculateTotalPrice($cartItems) {
  $total = 0;
  foreach ($cartItems as $product_id => $quantity) {
    // Assuming you have a product database or data source to get the price
    $price = getProductPrice($product_id); // Replace with your price retrieval method
    $totalPriceForProduct = $price * $quantity;
    $total += $totalPriceForProduct;
  }
  return $total;
}

// --------------------------------------------------
// Example Usage (Simulated)
// --------------------------------------------------

// Example: Add an item to the cart
addToCart(123, 2); // Add 2 of product ID 123
addToCart(456, 1); // Add 1 of product ID 456

// Example: Update the quantity of an item
updateCartQuantity(123, 5);

// Example: Remove an item from the cart
removeFromCart(456);

// Get the cart contents
$cart = getCartContents();
echo "Cart Contents: <br>";
print_r($cart);

// Calculate the total price
$productPrices = [
  123 => 10.00,  //Price for product 123
  456 => 25.00   //Price for product 456
];
$total = calculateTotalPrice($productPrices, $cart);
echo "Total Price: $" . $total . "<br>";
?>


<?php
session_start(); // Start the session

// --- Add to Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return bool True if the product was successfully added, false otherwise.
 */
function addToCart(int $productId, int $quantity) {
  // Check if the cart already exists.  If not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // Product already exists, update the quantity
    $_SESSION['cart'][$productId] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$productId] = $quantity;
  }

  return true;
}


/**
 * Removes an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return bool True if the product was successfully removed, false otherwise.
 */
function removeFromCart(int $productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
    return true;
  }
  return false;
}



/**
 * Gets all items in the cart.
 *
 * @return array An array of items in the cart.
 */
function getCart() {
  return $_SESSION['cart'];
}

// --- Example Usage & Demonstration ---

// 1. Adding items to the cart
addToCart(1, 2); // Add 2 of product with ID 1
addToCart(2, 1); // Add 1 of product with ID 2
addToCart(1, 3); // Add 3 more of product with ID 1


// 2. Displaying the cart contents
echo "<h2>Your Cart:</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $quantity) {
    // You'll need to fetch product details based on $productId here
    // For example:
    $product = getProductDetails($productId); // Replace with your function
    if ($product) {
      echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    } else {
      echo "<li>Product ID: " . $productId . " - Quantity: " . $quantity . " - (Product details not found)</li>";
    }

  }
  echo "</ul>";
}


// 3. Removing an item from the cart (example)
removeFromCart(2);

// 4. Displaying the cart contents again after removal
echo "<h2>Your Cart (after removal):</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $quantity) {
    // You'll need to fetch product details based on $productId here
    $product = getProductDetails($productId); // Replace with your function
    if ($product) {
      echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    } else {
      echo "<li>Product ID: " . $productId . " - Quantity: " . $quantity . " - (Product details not found)</li>";
    }
  }
  echo "</ul>";
}


// --- Helper Function (Replace with your actual product retrieval logic) ---
/**
 *  Placeholder function to simulate getting product details based on ID.
 *  In a real application, this would query your database.
 *
 * @param int $productId The product ID.
 * @return array|null  An array containing product details, or null if not found.
 */
function getProductDetails(int $productId) {
  //  Replace this with your actual product retrieval logic from a database.
  // Example:
  $products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
  ];

  if (isset($products[$productId])) {
    return $products[$productId];
  }
  return null;
}

?>


<?php

session_start();

// --- Function to Add to Cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Add the product to the cart
  $_SESSION['cart'][$product_id] = [
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// --- Example Usage (Simulating a product) ---
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80]
];

// 1. User adds a T-Shirt
addToCart(1, 'T-Shirt', $products[1]['price']);

// 2. User adds two Jeans
addToCart(2, 'Jeans', $products[2]['price'], 2);

// 3. User adds one pair of Shoes
addToCart(3, 'Shoes', $products[3]['price']);

// --- Display the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>


<?php
session_start();

// --- Cart Session Variables ---

// Session to store the cart data (array of product IDs and quantities)
$cart = [];

// Session to store the total cart value
$_SESSION['cart_total'] = 0;

// Session to store the number of items in the cart
$_SESSION['cart_item_count'] = 0;



// --- Helper Functions ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize the cart if it doesn't exist
    }

    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart, increase quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product not in cart, add it
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity,
            'price'    => 0 // You'll need to store the product price here (e.g., from a database)
        ];
    }

    // Update cart total
    $_SESSION['cart_total'] += $_SESSION['cart'][$product_id]['quantity'] * 0; // 0 price (for now, price comes from database)

    // Update item count
    $_SESSION['cart_item_count']++;
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    // Update cart total
    $_SESSION['cart_total'] -= $_SESSION['cart'][$product_id]['quantity'] * 0;

    // Update item count
    $_SESSION['cart_item_count']--;
}


/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateQuantity($product_id, $quantity)
{
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
    // Update cart total
    $_SESSION['cart_total'] -= $_SESSION['cart'][$product_id]['quantity'] * 0;
    $_SESSION['cart_total'] += $quantity * 0;  // Price is assumed to be 0 (for this example)
    // Update item count
    $_SESSION['cart_item_count'] = 0;
    foreach ($_SESSION['cart'] as $item) {
      $_SESSION['cart_item_count']++;
    }
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart()
{
    unset($_SESSION['cart']);
    $_SESSION['cart_total'] = 0;
    $_SESSION['cart_item_count'] = 0;
}


// --- Example Usage (Illustrative -  This would normally be handled by your website's front-end) ---

// Add a product to the cart
addToCart(1, 2); // Add 2 of product with ID 1

// Add another product to the cart
addToCart(3, 1);

// Display the cart contents (for demonstration)
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Cart Total: $" . number_format($_SESSION['cart_total'], 2) . "</p>";
    echo "<p>Total Items in Cart: " . $_SESSION['cart_item_count'] . "</p>";
}

// Remove a product from the cart
removeFromCart(1);

// Update the quantity of a product
updateQuantity(3, 3);

// Clear the cart
//clearCart();
?>


<?php
session_start(); // Start the session

// Check if the 'cart' session variable exists
if (!isset($_SESSION['cart'])) {
    // Initialize the cart array if it doesn't exist
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it's already in the cart, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Otherwise, add the product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Example usage -  Illustrative, you'll likely get product data from a database/API

// Add some items to the cart
addToCart('product1', 'Awesome T-Shirt', 20, 2);
addToCart('product2', 'Cool Mug', 10, 3);
addToCart('product1', 'Awesome T-Shirt', 20, 1); // Add more of the existing item


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
        echo "<button onclick='removeFromCart(" . $product_id . ")'>Remove</button>";  //Example of how you might display the remove button.  JavaScript would handle the call to removeFromCart.
    }
    echo "</ul>";
}



// Example of removing an item
// removeFromCart('product2');

// Example of updating quantity
// updateQuantity('product1', 5);

?>

<!-- JavaScript for removing items (simplified example) -->
<script>
    function removeFromCart(product_id) {
        // This is a placeholder.  In a real application, this function would
        // send a request to the server (e.g., using AJAX) to remove the item
        // from the session.

        // For demonstration purposes, we'll simply display a confirmation message.
        alert("Item " + product_id + " removed (simulated).");
    }
</script>


<?php
session_start();

// --- Cart Session Variables ---
// Define session variables to store cart data.
// These keys are just examples; you can adjust them as needed.
define('CART_KEY', 'shopping_cart');

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name = null) {
    if (!isset($_SESSION[CART_KEY])) {
        $_SESSION[CART_KEY] = []; // Initialize the cart array
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION[CART_KEY][$product_id])) {
        // If it exists, increase the quantity
        $_SESSION[CART_KEY][$product_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add it to the cart
        $_SESSION[CART_KEY][$product_id] = [
            'quantity' => $quantity,
            'name' => $product_name ?? $product_id // Using product_name if available, otherwise product_id
        ];
    }
}

// Function to get the cart contents
function getCartContents() {
    if (isset($_SESSION[CART_KEY])) {
        return $_SESSION[CART_KEY];
    } else {
        return []; // Return an empty array if the cart is empty
    }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $new_quantity) {
    if (isset($_SESSION[CART_KEY][$product_id])) {
        $_SESSION[CART_KEY][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    if (isset($_SESSION[CART_KEY][$product_id])) {
        unset($_SESSION[CART_KEY][$product_id]);
    }
}

// Function to clear the entire cart
function clearCart() {
    unset($_SESSION[CART_KEY]);
}

// --- Example Usage (Demonstration) ---
// Add some items to the cart
addToCart(1, 2); // Product ID 1, Quantity 2
addToCart(2, 1); // Product ID 2, Quantity 1
addToCart(1, 3); // Product ID 1, Quantity 3

// Display the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['name'] . "</li>";
    }
    echo "</ul>";
}


// Example: Update the quantity of product 1 to 5
updateCartItemQuantity(1, 5);

echo "<hr>";
echo "<h2>Cart Contents After Update:</h2>";
$cart = getCartContents();
foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['name'] . "</li>";
}



// Example: Remove product 2 from the cart
removeItemFromCart(2);

// Example: Clear the entire cart
// clearCart();


?>


<?php

session_start();

// --- Cart Data (In a real application, this would likely come from a database) ---
$cart = [];

// Helper function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($cart[$product_id])) {
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
    ];
  } else {
    $cart[$product_id]['quantity'] += $quantity;
  }
}

// Helper function to remove an item from the cart
function removeFromCart($product_id) {
    unset($cart[$product_id]);
}

// Helper function to update the quantity of an item in the cart
function updateQuantity($product_id, $newQuantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $newQuantity;
    }
}

// --- Example Product Data (Replace with your actual data) ---
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];

// --- User Cart Session Management ---

// Add an item to the cart when a user adds an item (e.g., from a product page)
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1 if quantity is not provided

  if (isset($products[$product_id])) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  } else {
    // Handle the case where the product ID is not found
    echo "Product ID " . $product_id . " not found.";
  }
}

// Remove an item from the cart
if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Update quantity of an item
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $newQuantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    updateQuantity($product_id, $newQuantity);
}


// --- Display the Cart Contents ---

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
  }
  echo "</ul>";

  // Calculate the total cost
  $total = 0;
  foreach ($cart as $product_id => $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart with quantity 1
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Example usage:

// Add an item to the cart
add_to_cart(1, 'Laptop', 1200, 1); // Product ID 1, Laptop, Price $1200, Quantity 1
add_to_cart(2, 'Mouse', 25, 2); // Product ID 2, Mouse, Price $25, Quantity 2


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";

    // Add a button to remove this item
    echo "<a href='cart.php?remove=" . $product_id . "'>Remove</a><br>";

    echo "</li>";
  }
  echo "</ul>";
}

// Example of updating the quantity
// echo "<br>Update Quantity for Product 1: <input type='number' value='2'>";
// if (isset($_POST['update_quantity'])) {
//   $new_quantity = $_POST['update_quantity'];
//   update_quantity(1, $new_quantity);
//   echo "<p>Quantity updated to " . $new_quantity . "</p>";
// }

?>


<?php
session_start();

// Example Product Data (replace with your actual product information)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0; // Initialize if not already present
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] + $quantity);
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to get the cart items
function getCartItems() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return [];
    }
}

// Function to remove an item from the cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        // Also remove from the session for consistency. Important for future operations.
        unset($_SESSION['cart'][$productId]);
    }
}


// --- Example Usage (Demonstration - can be replaced with your UI logic) ---

// Example: Adding an item to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Convert to integer for safety
    $quantity = (int)$_POST['quantity'];

    if (isset($products[$productId])) {
        addToCart($productId, $quantity);
    }
}

// Example: Removing an item from the cart
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];
    removeFromCart($productId);
}


// --- Displaying the Cart ---

// Get the cart items
$cartItems = getCartItems();

// Calculate the total price
$totalPrice = 0;
foreach ($cartItems as $productId => $quantity) {
    if (isset($products[$productId])) {
        $totalPrice += $products[$productId]['price'] * $quantity;
    }
}

echo "<h2>Your Cart</h2>";

if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $productId => $quantity) {
        if (isset($products[$productId])) {
            echo "<li>" . $products[$productId]['name'] . " - Quantity: " . $quantity . " - Price: $" . $products[$productId]['price'] . "</li>";
        }
    }
    echo "</ul>";
    echo "<p>Total: $" . number_format($totalPrice, 2) . "</p>";
}

?>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it is, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it isn't, add the product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function get_cart_contents() {
    return $_SESSION['cart'];
}

// Example usage:  Simulating a product ID and adding to the cart
// $product_id = 1;
// $product_name = "Awesome T-Shirt";
// $price = 25.00;
// add_to_cart($product_id, $product_name, $price);

// Example usage:  Adding multiple items
// add_to_cart(1, "Awesome T-Shirt", 25.00, 2);
// add_to_cart(2, "Cool Mug", 10.00, 3);

// Example usage: Removing an item
// remove_from_cart(2);

// Example usage:  Updating quantity
// update_cart_quantity(1, 5);


// Display the cart contents
$cart_contents = get_cart_contents();

echo "<h2>Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . "<button onclick=\"removeItem('" . $product_id . "')\">Remove</button></li>";
    }
    echo "</ul>";
}


?>

<!-- JavaScript to handle removing items from the cart (this would be in a separate HTML file) -->
<script>
function removeItem(productId) {
    // You would likely send a request to the server to remove the item
    // from the session.  For this example, we'll just simulate it.
    alert("Removed product with ID: " + productId); //Replace with actual server-side removal
}
</script>


<?php
session_start();

// Initialize an empty cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Function to add an item to the cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Validate that quantity is a number
  if (!is_numeric($quantity) || $quantity <= 0) {
    return false; // Invalid quantity
  }

  // Add the item to the cart array
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  return true;
}

// --- Example Usage (Simulated Product Data) ---
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Shoes', 'price' => 80)
);


// --- Handling User Actions (Simulated) ---

// 1. Add a T-Shirt to the cart (quantity = 2)
if (isset($_POST['add_tshirt'])) {
  addToCart(1, 'T-Shirt', 20, 2);
  $message = "T-Shirt added to cart!";
}

// 2. Add Jeans to the cart (quantity = 1)
if (isset($_POST['add_jeans'])) {
  addToCart(2, 'Jeans', 50, 1);
  $message = "Jeans added to cart!";
}


// --- Displaying the Cart ---

echo "<!DOCTYPE html>
<html>
<head>
<title>Shopping Cart</title>
</head>
<body>";

echo "<h1>Shopping Cart</h1>";

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  // Loop through the cart and display the items
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_name = $item['name'];
    $price = $item['price'];
    $quantity = $item['quantity'];

    echo "<li>" . $product_name . " - $" . $price . " x " . $quantity . " = $" . ($price * $quantity) . "</li>";
  }
  echo "</ul>";
}

echo "<br>";

echo "<form method='post'>
        <label for='add_tshirt'>Add T-Shirt (Quantity):</label>
        <input type='number' name='add_tshirt' value='1' min='1' >
        <input type='submit' name='add_tshirt' value='Add to Cart'>
      </form>
      <br>
      <form method='post'>
        <label for='add_jeans'>Add Jeans (Quantity):</label>
        <input type='number' name='add_jeans' value='1' min='1'>
        <input type='submit' name='add_jeans' value='Add to Cart'>
      </form>";

echo "</body>
</html>";
?>


<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id      The ID of the product to add.
 * @param string $product_name    The name of the product.
 * @param int    $quantity       The quantity to add (default: 1).
 * @param float  $price          The price of the product.
 *
 * @return bool True on success, false on failure (e.g., invalid quantity).
 */
function addToCart(string $product_id, string $product_name, int $quantity = 1, float $price) {
  // Validate quantity
  $quantity = max(1, (int)$quantity); // Ensure quantity is at least 1

  if ($quantity <= 0) {
    return false;
  }

  // Check if the cart exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new product to cart
    $_SESSION['cart'][$product_id] = [
      'name'    => $product_name,
      'quantity' => $quantity,
      'price'   => $price
    ];
  }

  return true;
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity  The new quantity for the product.
 *
 * @return bool True on success, false on failure (e.g., product not found).
 */
function updateCartItem(string $product_id, int $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        return false;
    }

    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    return true;
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 *
 * @return bool True on success, false if the product is not in the cart.
 */
function removeFromCart(string $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    return true;
  }
  return false;
}

/**
 * Gets the items in the cart.
 *
 * @return array The cart contents as an associative array.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart doesn't exist
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateCartTotal() {
  $total = 0;
  $cart = getCartContents();
  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}


// --- Example Usage ---

// Add some items to the cart
addToCart('product1', 'Awesome T-Shirt', 2, 25.00);
addToCart('product2', 'Cool Mug', 1, 10.00);
addToCart('product3', 'Fancy Hat', 3, 15.00);

// Display the cart contents
$cartContents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}

echo "<p><strong>Total Cart Value:</strong> $" . calculateCartTotal() . "</p>";

// Update quantity
updateCartItem('product1', 3);

echo "<p><strong>Updated Cart Value:</strong> $" . calculateCartTotal() . "</p>";


// Remove an item
removeFromCart('product2');

echo "<p><strong>Updated Cart Value:</strong> $" . calculateCartTotal() . "</p>";

// Display the cart contents after removal
$cartContents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}
?>


<?php
session_start();

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id  The ID of the product being added.
 * @param int    $quantity   The quantity of the product to add.
 * @return void
 */
function add_to_cart(string $product_id, int $quantity) {
  // Check if the cart already exists.  If not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart.
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart with a quantity of 1.
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity The new quantity.
 * @return void
 */
function update_cart_quantity(string $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clear_cart() {
    unset($_SESSION['cart']);
}


// --- Example Usage (Demonstration) ---

// Add some items to the cart
add_to_cart('product1', 2);
add_to_cart('product2', 1);
add_to_cart('product1', 3); // Add more of product1


// Display the contents of the cart
echo "<h2>Your Cart:</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// Update the quantity of product1 to 5
update_cart_quantity('product1', 5);

// Display the updated cart
echo "<h2>Your Cart (Updated):</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// Remove product2 from the cart
remove_from_cart('product2');

// Display the updated cart (after removing an item)
echo "<h2>Your Cart (After Removal):</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}


// Clear the cart
// clear_cart();

// Display the final empty cart
// echo "<h2>Your Cart (Empty):</h2>";
// if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
//     echo "<ul>";
//     foreach ($_SESSION['cart'] as $product_id => $item) {
//         echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "<p>Your cart is empty.</p>";
// }

?>


<?php

// Start a session (if not already started)
session_start();

// --------------------------------------------------------------------
//  Function to add an item to the cart
// --------------------------------------------------------------------
function addToCart($product_id, $quantity = 1, $product_name = null) {
  // Check if the cart already exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize an empty cart array
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already exists in the cart, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist in the cart, add it with the given quantity
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name ?? $product_id, // Use product name if provided, otherwise use ID
    ];
  }
}

// --------------------------------------------------------------------
//  Example Usage (for demonstration purposes)
// --------------------------------------------------------------------

// Add a product to the cart
addToCart(123, 2);  // Add 2 units of product with ID 123

// Add another product to the cart
addToCart(456, 1, "Awesome T-Shirt"); // Add 1 unit of product with ID 456 and name "Awesome T-Shirt"


// --------------------------------------------------------------------
//  Displaying the Cart Contents (for demonstration)
// --------------------------------------------------------------------

echo "<h2>Your Shopping Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "Product ID: " . $product_id . "<br>";
    echo "Product Name: " . $product_details['name'] . "<br>";
    echo "Quantity: " . $product_details['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


?>


<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists, create it if not
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use &$item for modification
      if ($item['product_id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        break;
      }
    }
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $key = array_search(
      array('product_id' => $product_id),
      $cart
    );

    if ($key !== false) {
      unset($cart[$key]);
    }
  }
}

// Function to get the cart contents
function get_cart_contents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return array(); // Return an empty array if the cart is empty
  }
}

// Example usage (Simulated product data - replace with your database or product list)

$products = array(
  1 => array('product_id' => 1, 'product_name' => 'Laptop', 'price' => 1200),
  2 => array('product_id' => 2, 'product_name' => 'Mouse', 'price' => 25),
  3 => array('product_id' => 3, 'product_name' => 'Keyboard', 'price' => 75)
);


// Simulate a user adding items to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);
add_to_cart(3, 'Keyboard', 75, 1);

// Simulate updating the quantity of the mouse
update_cart_quantity(2, 3);

// Get the cart contents
$cart_items = get_cart_contents();

// Display the cart items
echo "<h2>Cart Contents:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total_price = 0;
  foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
  }

  echo "<p><strong>Total Price: $" . number_format($total_price, 2) . "</strong></p>";
}


// Example of removing an item from the cart
// remove_from_cart(2); // Remove the mouse

// Get the cart contents after removal
// $cart_items = get_cart_contents();
// print_r($cart_items);

?>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product is already in the cart
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage (Demonstration - Replace with actual product data and form handling)
// Let's assume we have products with IDs 1, 2, and 3
// In a real application, you would retrieve this data from a database.

// Add a product to the cart
addToCart(1, 'Shirt', 20, 2);
addToCart(2, 'Shoes', 50, 1);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example: Remove an item
// removeCartItem(1);

// Example: Update quantity of product 2 to 3
// updateQuantity(2, 3);

?>


<?php

session_start();

// ---------------------------------------------------------------------
// Example Product Data (Replace with your actual product data)
// ---------------------------------------------------------------------

$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];


// ---------------------------------------------------------------------
// Add to Cart Function
// ---------------------------------------------------------------------

function addToCart($product_id, $quantity = 1)
{
  global $products;

  if (isset($products[$product_id])) {
    if (isset($_SESSION['cart'])) {
      $_SESSION['cart'][$product_id] += $quantity;
    } else {
      $_SESSION['cart'] = [
        $product_id => $quantity
      ];
    }
  } else {
    // Product not found - you might want to handle this differently
    echo "Product ID " . $product_id . " not found.";
  }
}


// ---------------------------------------------------------------------
// Example Usage:  Adding items to the cart
// ---------------------------------------------------------------------

// Add one T-shirt
addToCart(1);

// Add two pairs of Jeans
addToCart(2, 2);

// Add one pair of Shoes
addToCart(3);


// ---------------------------------------------------------------------
// Display Cart Contents
// ---------------------------------------------------------------------

echo "<h2>Your Shopping Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = $products[$product_id];
    echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
  }
  echo "</ul>";

  // Calculate total
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = $products[$product_id];
    $total += $product['price'] * $quantity;
  }
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";

} else {
  echo "<p>Your cart is empty.</p>";
}

// ---------------------------------------------------------------------
//  Example: Removing an item from the cart (optional)
// ---------------------------------------------------------------------
//
//  You would need a function like this:
//  function removeFromCart($product_id) {
//    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
//      unset($_SESSION['cart'][$product_id]);
//      if (count($_SESSION['cart']) == 0) {
//        unset($_SESSION['cart']); // Clear the entire cart if it's empty
//      }
//    }
//  }
//
//  // Example: Remove one T-shirt
//  // removeFromCart(1);
//
?>


<?php

session_start();

// --- Cart Session Variables ---

// Define keys for cart items
$cartKeys = ['item_id', 'item_name', 'quantity', 'price'];

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $quantity, $price) {
  global $cartKeys;

  // Check if cart exists.  If not, initialize it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Add the item to the cart
  $_SESSION['cart'][] = [
    'item_id' => $item_id,
    'item_name' => $item_name,
    'quantity' => $quantity,
    'price' => $price
  ];

  // You might want to update quantity if the item already exists in the cart
  update_cart_item($item_id, $quantity);

}


// Function to update quantity of an item in the cart
function update_cart_item($item_id, $quantity) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) { // Use &$item for in-place modification
            if ($item['item_id'] == $item_id) {
                $item['quantity'] = $quantity;
                break; // Exit the loop once the item is updated
            }
        }
    }
}


// Function to remove an item from the cart
function remove_from_cart($item_id) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['item_id'] == $item_id) {
        unset($_SESSION['cart'][$key]);
        // Optional:  If you want to re-index the array after removing
        // $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true; // Indicate successful removal
      }
    }
    return false; // Indicate item not found
  }
  return false; // Cart doesn't exist
}


// Function to get the cart total
function get_cart_total() {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price'];
    }
    return $total;
  }
  return 0;
}

// Function to display the cart contents
function display_cart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";

  $cart_total = get_cart_total();

  foreach ($_SESSION['cart'] as $item) {
    $item_total = $item['quantity'] * $item['price'];
    echo "<tr>";
    echo "<td>" . $item['item_name'] . "</td>";
    echo "<td>" . $item['quantity'] . "</td>";
    echo "<td>" . $item['price'] . "</td>";
    echo "<td>" . $item_total . "</td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
}

// --- Example Usage (Demonstration) ---

// Add some items to the cart
add_to_cart(1, 'Laptop', 1, 1200);
add_to_cart(2, 'Mouse', 1, 25);
add_to_cart(1, 'Laptop', 2, 1200); // Add another laptop

// Display the cart
display_cart();

// Remove an item
remove_from_cart(2);

// Display the cart again
display_cart();

?>


<?php

// Start the session (if it doesn't exist)
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the item is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Item exists, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage:

// Add a product to the cart
addToCart(1, "T-Shirt", 20.00, 2); // Product ID 1, T-Shirt, price $20.00, quantity 2
addToCart(2, "Jeans", 50.00, 1);   // Product ID 2, Jeans, price $50.00, quantity 1

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total for Product:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example to remove an item from the cart
// removeFromCart(1);

// Example to update the quantity of an item
// updateQuantity(1, 3);

// After updating, display the cart contents again:
echo "<hr>";
echo "<h2>Shopping Cart (After Update)</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total for Product:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

//  Example Usage (simulated product data - replace with your actual database)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];

// Simulate a user adding items to the cart
addToCart(1, 'Laptop', $products[1]['price'], 1);
addToCart(2, 'Mouse', $products[2]['price']);
addToCart(1, 'Laptop', $products[1]['price'], 2); // Add more laptops
addToCart(3, 'Keyboard', $products[3]['price']);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Example: Remove an item
// removeCartItem(2);

// Example: Update quantity
// updateCartItemQuantity(1, 3);


//  Simulated checkout (Just an example - integrate with your payment processing)
if (isset($_POST['checkout'])) {
    echo "<p>Thank you for your order!</p>";
    // Clear the cart after checkout
    $_SESSION['cart'] = array();
}
?>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if product already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example usage (add some items to the cart)
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 1); // Add another laptop to increase quantity


// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example usage (remove an item)
removeCartItem(2);

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

//Example Usage (Update the quantity of Laptop)
updateCartItemQuantity(1, 3); //Change the quantity of laptop to 3.

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_info) {
    echo "<li>";
    echo "<strong>" . $product_info['name'] . "</strong> - $" . $product_info['price'] . " x " . $product_info['quantity'] . " = $" . ($product_info['price'] * $product_info['quantity']) . "</li>";
  }
  echo "</ul>";
}

// You can also calculate the total cost of the cart here.
echo "<p><strong>Total Cost:</strong> $" . number_format(calculateTotal(), 2) . "</p>";

//Helper function to calculate total cost
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $product_id => $product_info){
            $total += ($product_info['price'] * $product_info['quantity']);
        }
    }
    return $total;
}
?>


<?php
session_start();

// Array to hold the cart items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  global $cart;

  // Check if the item is already in the cart
  if (isset($cart[$product_id])) {
    // Item exists, increment the quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, create a new entry
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an existing item
function updateQuantity($product_id, $new_quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  } else {
    // If the product doesn't exist, you might want to handle it,
    // such as adding it with the new quantity.  Alternatively, you could
    // return an error or do nothing.  This example adds it.
    addToCart($product_id, "Product - " . $product_id, 0, $new_quantity); // Use the add to cart function instead
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $cart;
}

// Function to clear the cart
function clearCart() {
  global $cart;
  $cart = array();
}

// --- Example Usage / Controller Part ---

// 1.  Add an item to the cart
$product_id = 1;
$product_name = "T-Shirt";
$price = 20.00;
$quantity = 2;

addToCart($product_id, $product_name, $price, $quantity);

// 2. Update the quantity of an item
updateQuantity($product_id, 5);

// 3.  Remove an item
//removeCartItem($product_id);

// 4.  Get cart contents
$cart_contents = getCartContents();
print_r($cart_contents);

// 5.  Clear the cart
//clearCart();
?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array if it doesn't exist
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return cart contents or an empty array if it doesn't exist
}


// Example Usage (Simulating adding items to the cart)

// Add an item to the cart
addToCart(1, 'T-Shirt', 20.00, 2);

// Add another item to the cart
addToCart(2, 'Jeans', 50.00, 1);

// Get the current cart contents
$cart = getCartContents();

// Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_info) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_info['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_info['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_info['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item from the cart
removeFromCart(1);

// Get the cart contents after removing an item
$cart = getCartContents();

// Display the cart contents after removing an item
echo "<h2>Cart Contents After Removal</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_info) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_info['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($product_info['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $product_info['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example: Update the quantity of an item
updateQuantity(2, 3);

//Get the cart contents after updating quantity
$cart = getCartContents();
echo "<h2>Cart Contents After Update</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_info) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_info['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_info['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_info['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}
?>


<?php
session_start();

// Session variables to store items in the cart
$cart = array(); 

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already in cart, increase quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart contents
function get_cart_contents() {
  return $cart;
}

// Example usage (simulated products)

// Add some items to the cart
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);
add_to_cart(1, "Laptop", 1200, 1);  // Add another laptop
add_to_cart(3, "Keyboard", 75, 1);

// Display the cart contents
$cart_items = get_cart_contents();

echo "<h2>Your Shopping Cart</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Price: $" . $item['price'] . " - Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

//Example of removing an item
//remove_from_cart(2);

//Example of updating the quantity of an item
//update_quantity(1, 3);


?>


<?php
session_start(); // Start the session

// --- Example Cart Logic ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int $price The price of the product.
 * @param int $quantity The quantity to add (default: 1).
 */
function addToCart($product_id, $name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $newQuantity The new quantity.
 */
function updateQuantity($product_id, $newQuantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
    }
}


/**
 * Gets the items in the cart.
 *
 * @return array An array of items in the cart.
 */
function getCartItems() {
    return $_SESSION['cart'];
}

/**
 * Calculates the total price of the items in the cart.
 *
 * @return float The total price.
 */
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total = $total + ($item['price'] * $item['quantity']);
    }
    return $total;
}

// --- Example Usage (Simulating a user adding items) ---

// Add some items to the cart
addToCart('product1', 'Laptop', 1200, 1);
addToCart('product2', 'Mouse', 25, 2);
addToCart('product3', 'Keyboard', 75, 1);

// Display the cart items
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (getCartItems() as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculateTotal() . "</p>";
}

// --- Example Removing an Item ---
//removeFromCart('product2');

// --- Example Updating Quantity ---
//updateQuantity('product1', 3);
//echo "<p>Updated quantity of product1 to 3.</p>";

?>


<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  // Add the item to the cart array
  $_SESSION['cart'][] = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // Optional:  Update quantity if item already exists
  updateCartQuantity($product_id, $quantity);
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  // Iterate through the cart array
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] = $quantity;
      return; // Exit the loop once the item is updated
    }
  }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      // Re-index the array after removing an element
      $_SESSION['cart'] = array_values($_SESSION['cart']);
      return;
    }
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// --- Example Usage (Illustrative - You'd likely integrate this into a larger application) ---

// 1. Add an item to the cart
// $productId = 123;
// $productName = "Awesome T-Shirt";
// $price = 25.00;
// $quantity = 2;
// addToCart($productId, $productName, $price, $quantity);

// 2. Display the cart contents
// $cartContents = getCartContents();
// if (!empty($cartContents)) {
//   echo "<h2>Your Shopping Cart</h2>";
//   echo "<ul>";
//   foreach ($cartContents as $item) {
//     echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
//   }
//   echo "</ul>";
// } else {
//   echo "<p>Your cart is empty.</p>";
// }

// 3. Remove an item from the cart
// removeItemFromCart(123);

// 4. Get Cart Contents again after removing
// $cartContents = getCartContents();
// if (!empty($cartContents)) {
//   echo "<h2>Your Shopping Cart</h2>";
//   echo "<ul>";
//   foreach ($cartContents as $item) {
//     echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
//   }
//   echo "</ul>";
// } else {
//   echo "<p>Your cart is empty.</p>";
// }


?>


<?php

session_start();

// Initialize an empty cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add a new entry to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<h2>Your Cart is Empty</h2>";
  } else {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    $total = 0;
    foreach ($_SESSION['cart'] as $item_id => $item_details) {
      $name = $item_details['name'];
      $price = $item_details['price'];
      $quantity = $item_details['quantity'];
      $total_item = $price * $quantity;
      $total += $total_item;

      echo "<tr>";
      echo "<td>" . $name . "</td>";
      echo "<td>$" . number_format($price, 2) . "</td>";
      echo "<td>" . $quantity . "</td>";
      echo "<td>$" . number_format($total_item, 2) . "</td>";
      echo "<td><a href='cart.php?action=remove&product_id=" . $product_id . "'>Remove</a></td>";
      echo "</tr>";
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
  }
}

// --- Example Usage (Illustrative - Adapt to your application's needs) ---

// Adding items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 2); // Adding another laptop

// Displaying the cart contents
displayCart();

// Removing an item
// removeCartItem(2); // Uncomment to remove the mouse
// displayCart(); // Display cart again to see the changes

// Updating the quantity of an item
// updateCartQuantity(1, 3); //Change Laptop quantity to 3
// displayCart();

?>


<?php
// product_list.php
session_start();

echo "<h2>Available Products</h2>";
echo "<form method='post' action='cart.php'>";
echo "<ul>";
// Replace these with your product details
echo "<li><input type='hidden' name='product_id' value='1'> <img src='laptop.jpg' width='100'><br> Laptop - $" . number_format(1200, 2) . "<br><input type='submit' value='Add to Cart'></li>";
echo "<li><input type='hidden' name='product_id' value='2'> <img src='mouse.jpg' width='100'><br> Mouse - $" . number_format(25, 2) . "<br><input type='submit' value='Add to Cart'></li>";

echo "</ul>";
echo "<input type='hidden' name='action' value='add'>";
echo "</form>";
?>


<?php
session_start();

// Session variables for the cart
$_SESSION['cart'] = array(); // Initialize the cart as an empty array

// Helper functions for cart operations (can be moved to a separate file)
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

function get_cart_items() {
  return $_SESSION['cart'];
}

function update_quantity($product_id, $quantity) {
  // Iterate through the cart items
  foreach ($_SESSION['cart'] as &$item) { // Use &$item for reference to modify the original array
    if ($item['id'] == $product_id) {
      $item['quantity'] = $quantity;
      return;
    }
  }
}

function remove_from_cart($product_id) {
  // Iterate through the cart and remove the item
  $keys = array_keys($_SESSION['cart']);
  foreach ($keys as $key) {
    if ($_SESSION['cart'][$key]['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      break;
    }
  }
}

function calculate_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Example Usage (demonstration - this would be part of a web form)

// Add a product to the cart
add_to_cart(1, 'T-Shirt', 20, 2); // Product ID 1, T-Shirt, price $20, quantity 2
add_to_cart(2, 'Jeans', 50, 1); // Product ID 2, Jeans, price $50, quantity 1

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}


// Example of updating the quantity
update_quantity(1, 3); // Increase the quantity of T-Shirt to 3

// Display the updated cart
echo "<h2>Your Updated Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}

// Example of removing an item
remove_from_cart(2);

//Display the cart after removing an item
echo "<h2>Your Updated Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}
?>


<?php

session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the contents of the cart
function getCart() {
    return $_SESSION['cart'];
}

// Example Usage (Demonstration)

// Add some items to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 3); //Adding more of the same item

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = getCart();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }
}
echo "</ul>";

// Example of updating a quantity
updateQuantity(2, 5);

// Display the updated cart contents
echo "<br><h2>Cart Contents (After Update):</h2>";
$cart_items = getCart();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }
}
echo "</ul>";


// Example of removing an item from the cart
removeCartItem(2);
echo "<br><h2>Cart Contents (After Removal):</h2>";
$cart_items = getCart();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }
}
echo "</ul>";


?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// ---------------------  Helper Functions ---------------------
/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

/**
 * Removes an item from the shopping cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function update_cart_item($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets the items in the shopping cart.
 *
 * @return array The shopping cart contents.
 */
function get_cart_items() {
    return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int
 */
function cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $quantity) {
        $total += $quantity;
    }
    return $total;
}


// ---------------------  Example Usage (Simulated Products) ---------------------

// Let's assume we have some products with IDs 1, 2, and 3.

// Add some items to the cart
add_to_cart(1, 2);  // Add 2 of product 1
add_to_cart(2, 1);  // Add 1 of product 2
add_to_cart(1, 3);  // Add 3 of product 1
add_to_cart(3, 1);

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Simulate retrieving product details (replace with actual database lookup)
        $product_name = "Product " . $product_id;  // Dummy product name

        echo "<li>$product_name - Quantity: $quantity</li>";
    }
    echo "</ul>";
    echo "<p>Total items in cart: " . cart_total() . "</p>";
}


// Example of removing an item
//remove_from_cart(2);

//Example of updating an item
//update_cart_item(1, 5);

?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If product exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// --- Example Usage ---

// 1. Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 3); //Add to existing

// 2. Display the cart contents
$cart = getCartContents();
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// 3. Remove an item from the cart (example)
// removeCartItem(2);

// 4. Update quantity (example)
// updateCartQuantity(1, 5);


//  Example to show cart calculation:
$cart = getCartContents();

if(!empty($cart)){
  $total = 0;
  foreach($cart as $product_id => $product_data){
    $total += ($product_data['price'] * $product_data['quantity']);
  }
  echo "<p>Total Cart Value: $" . $total . "</p>";
}
?>


<?php
session_start(); // Start the session

// Cart array to store items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $cart = array();
    }

    $cart[] = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );
    $_SESSION['cart'] = $cart;
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($cart[$key]);
                break; // Stop after removing the item
            }
        }
        $_SESSION['cart'] = $cart;
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $key => $item) {
            if ($item['id'] == $product_id) {
                $cart[$key]['quantity'] = $new_quantity;
                break;
            }
        }
        $_SESSION['cart'] = $cart;
    }
}


// Example Usage (Simulated form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        addToCart($product_id, $product_name, $price, $quantity);
    } elseif (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id_to_remove'];
        removeCartItem($product_id_to_remove);
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id_to_update'];
        $new_quantity = $_POST['new_quantity'];
        updateCartQuantity($product_id_to_update, $new_quantity);
    }
}

// Display the cart contents
if (isset($_SESSION['cart'])) {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
        $total += ($item['price'] * $item['quantity']);
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
} else {
    echo "<p>Your cart is empty.</p>";
}

?>


<?php

// Start a session (if not already started)
session_start();

// Cart data (This would typically come from a database or file)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  unset($cart[$product_id]);
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}


// Example usage (Simulating user interaction)

// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
add_to_cart(2, 'Jeans', 50, 1);
add_to_cart(1, 'T-Shirt', 20, 3); // Add more of the T-Shirt

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example: Remove an item
// remove_from_cart(2);

// Example: Update quantity
// update_quantity(1, 5);

//  (You would typically have logic here to process the cart data,
//   e.g., calculate the total price, store the cart data in a session,
//   or pass it to a separate processing script.)


//  To persist the cart data across multiple pages, you'd need to:
//  1. Store the cart data in a session.
//  2. Pass the session ID to each page that needs to access the cart.
//  3.  On each page, use session_start() to start the session.
//     Then, use session_id() to get the session ID and session_start($session_id).
//     You can then access the cart data using session_get_cookie_params() and session_regenerate_id() to ensure security.
//   This is outside the scope of this basic example.

?>


<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize empty cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// *** Example Usage - Simulate adding an item to the cart ***
// Assuming we have product data (replace with your actual data source)

$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80]
];

// Example 1: Add a T-Shirt to the cart
addToCart(1, $products[1]['name'], $products[1]['price'], 2);

// Example 2: Add Jeans to the cart
addToCart(2, $products[2]['name'], $products[2]['price']);

// Example 3: Update the quantity of the T-Shirt to 3
updateQuantity(1, 3);


// Display the cart contents
$cart = getCartContents();

echo "<h2>Your Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>


<?php
session_start(); // Start the session

// ---  Example Cart Logic ---

$cart = []; // Initialize an empty cart (array)

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $cart = [['id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => $quantity]];
    } else {
        // Check if the item already exists in the cart
        $item_exists = false;
        foreach ($cart as $index => $item) {
            if ($item['id'] == $product_id) {
                // Update quantity if item exists
                $cart[$index]['quantity'] += $quantity;
                $item_exists = true;
                break;
            }
        }
        // If the item doesn't exist, add it
        if (!$item_exists) {
            $cart[] = ['id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => $quantity];
        }
    }
}

// Function to remove an item from the cart by ID
function remove_from_cart($product_id) {
    global $cart; // Access the global $cart array

    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            $cart = array_values($cart); // Re-index the array after deleting
            return true;
        }
    }
    return false;
}

// Function to get the cart contents
function get_cart_contents() {
    return $cart;
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            $cart[$key]['quantity'] = $quantity;
            return true;
        }
    }
    return false;
}



// ---  Example Usage (Adding items to the cart )---

// Example: Add a product to the cart
add_to_cart(1, "T-Shirt", 20.00, 2);
add_to_cart(2, "Jeans", 50.00, 1);

// Example: Remove an item from the cart
// remove_from_cart(2);

// Example: Update the quantity of a product
// update_quantity(1, 3); // Increase the quantity of T-Shirt to 3

// ---  Display the Cart Contents (for demonstration) ---

// Get the cart contents
$cart_contents = get_cart_contents();

// Display the cart
echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}


?>


<?php
session_start();

// --- Session Management Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return void
 */
function addToCart(int $productId, int $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize the cart if it doesn't exist
    }

    if (isset($_SESSION['cart'][$productId])) {
        // Item already in cart, increase quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it with quantity 1
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }
}

/**
 * Update the quantity of an item in the cart.
 *
 * @param int $productId The ID of the product to update.
 * @param int $newQuantity The new quantity for the product.
 * @return void
 */
function updateCartItem(int $productId, int $newQuantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

/**
 * Remove an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function removeCartItem(int $productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

/**
 * Get the items in the cart.
 *
 * @return array An array representing the cart contents.
 */
function getCart()
{
    return $_SESSION['cart'] ?? []; // Return an empty array if cart isn't initialized
}

/**
 * Clear the entire cart.
 *
 * @return void
 */
function clearCart()
{
    unset($_SESSION['cart']);
}

// --- Example Usage (Illustrative - Replace with your actual logic) ---

// 1. Add an item to the cart:
addToCart(123); // Add product ID 123 to the cart with quantity 1
addToCart(456, 2); // Add product ID 456 to the cart with quantity 2

// 2. Update an item's quantity:
updateCartItem(123, 5); // Increase quantity of product 123 to 5

// 3. Get the cart contents:
$cart = getCart();
print_r($cart); // This will show you the contents of the cart (e.g., [123 => ['quantity' => 5], 456 => ['quantity' => 2]])

// 4. Remove an item:
removeCartItem(456);

// 5. Get the cart contents after removal:
$cart = getCart();
print_r($cart); // Now it should only contain [123 => ['quantity' => 5]]

// 6. Clear the cart:
clearCart();
print_r($cart); // It will be an empty array [].

?>


<?php

session_start();

// --- Cart Session Variables ---
$cart = array();
$cart_id = "shopping_cart";

// --- Helper Functions ---

/**
 * Add an item to the cart.
 *
 * @param string $product_id The unique ID of the product.
 * @param string $name The name of the product.
 * @param int    $price  The price of the product.
 * @param int    $quantity  The quantity to add.
 */
function add_to_cart(string $product_id, string $name, float $price, int $quantity = 1)
{
    if (!isset($_SESSION[$cart_id][$product_id])) {
        $_SESSION[$cart_id][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity
        );
    } else {
        // Item already in cart, update quantity
        $_SESSION[$cart_id][$product_id]['quantity'] += $quantity;
        $_SESSION[$cart_id][$product_id]['total'] = $_SESSION[$cart_id][$product_id]['price'] * $_SESSION[$cart_id][$product_id]['quantity'];
    }
}


/**
 * Update the quantity of an item in the cart.
 *
 * @param string $product_id The unique ID of the product.
 * @param int    $new_quantity The new quantity for the product.
 */
function update_cart_quantity(string $product_id, int $new_quantity)
{
    if (isset($_SESSION[$cart_id][$product_id])) {
        $_SESSION[$cart_id][$product_id]['quantity'] = $new_quantity;
        $_SESSION[$cart_id][$product_id]['total'] = $_SESSION[$cart_id][$product_id]['price'] * $_SESSION[$cart_id][$product_id]['quantity'];
    }
}


/**
 * Remove an item from the cart.
 *
 * @param string $product_id The unique ID of the product to remove.
 */
function remove_from_cart(string $product_id)
{
    unset($_SESSION[$cart_id][$product_id]);
}

/**
 * Get the contents of the cart.
 *
 * @return array The cart array.
 */
function get_cart_contents()
{
    return $_SESSION[$cart_id];
}

/**
 * Calculate the total cost of the cart.
 *
 * @return float The total cost.
 */
function calculate_cart_total()
{
    $total = 0;
    if (isset($_SESSION[$cart_id])) {
        foreach ($_SESSION[$cart_id] as $item) {
            $total_item = $item['price'] * $item['quantity'];
            $total += $total_item;
        }
    }
    return $total;
}

/**
 * Clear the entire cart.
 */
function clear_cart() {
    unset($_SESSION[$cart_id]);
}

// --- Example Usage ---

// 1. Add some items to the cart
add_to_cart("product_1", "T-Shirt", 20.00, 2);
add_to_cart("product_2", "Jeans", 50.00);
add_to_cart("product_3", "Hat", 15.00, 1);

// 2. Update the quantity of an item
update_cart_quantity("product_1", 3);

// 3. Remove an item from the cart
// remove_from_cart("product_2");

// 4. Get the cart contents
$cart_contents = get_cart_contents();
print_r($cart_contents);

// 5. Calculate the total
$total = calculate_cart_total();
echo "Total cart value: $" . number_format($total, 2) . "<br>";

// 6. Clear the cart
// clear_cart();

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage (Simulated Product Data - Replace with your actual data)
$products = array(
    1 => array('name' => 'Laptop', 'price' => 1200),
    2 => array('name' => 'Mouse', 'price' => 25),
    3 => array('name' => 'Keyboard', 'price' => 75)
);

// Example: Adding products to the cart
add_to_cart(1, $products[1]['name'], $products[1]['price'], 1);
add_to_cart(2, $products[2]['name'], $products[2]['price'], 2);
add_to_cart(1, $products[1]['name'], $products[1]['price'], 2); // Adding more of the laptop

// Example: Removing an item
//remove_from_cart(2);

// Example: Updating the quantity
//update_quantity(1, 3);

// Display the Cart Contents
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'];
        echo "</li>";
    }
    echo "</ul>";

    // Calculate the total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        $total_price += $product_details['price'] * $product_details['quantity'];
    }
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}

?>


<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Example Function to Add Items to the Cart ---

function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// --- Example Usage (Simulate Adding Items) ---

// Add a product
addToCart(1, 'Laptop', 1200, 1);

// Add another item
addToCart(2, 'Mouse', 25, 2);

// Add yet another item
addToCart(1, 'Laptop', 1200, 3); //Adding more of the Laptop

// --- Display the Cart Contents ---

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for Product:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// --- Example Function to Remove an Item from the Cart ---

function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example usage (Simulate removing an item)
// removeItemFromCart(2); // Remove the mouse

// --- Example Function to Update the Quantity of an Item in the Cart ---
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example usage
// updateQuantity(1, 5); //Update the quantity of the laptop to 5

// ---  Clear the Cart ---
//session_destroy(); //This will erase the entire session

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets the items in the cart.
 *
 * @return array The cart contents.
 */
function getCartContents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of items in the cart.
 *
 * @return float The total price.
 */
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // In a real application, you'd fetch the product price here.
    // This is just a placeholder.
    $product_price = 10;  // Example price
    $total_for_item = $product_price * $quantity;
    $total += $total_for_item;
  }
  return $total;
}



// --- Example Usage (Demonstration) ---

// Add a product to the cart
addToCart(123, 2); // Product ID 123, quantity 2
addToCart(456, 1); // Product ID 456, quantity 1

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = getCartContents();

if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
}
echo "</ul>";

// Calculate and display the total
$total = calculateTotal();
echo "<p>Total Price: $" . $total . "</p>";


// Remove an item from the cart
removeFromCart(456);

// Update the quantity of an item
updateQuantity(123, 3);

// Display the updated cart contents
echo "<p>Updated Cart Contents:</p>";
$cart_items = getCartContents();

if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
}

echo "<p>Total Price: $" . calculateTotal() . "</p>";
?>


<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example usage:
// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);  // Product ID 1, Laptop, price 1200, quantity 1
addToCart(2, "Mouse", 25, 2);    // Product ID 2, Mouse, price 25, quantity 2

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    echo "<li>";
    echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Function to remove an item from the cart (optional)
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example of removing an item (optional)
// removeFromCart(2);

?>


<?php
session_start();

// ... (Rest of the code from the previous example) ...

// Display a form to add items to the cart
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
echo "<label for='product_id'>Product ID:</label>";
echo "<select id='product_id' name='product_id'>";
echo "<option value='1'>Laptop</option>";
echo "<option value='2'>Mouse</option>";
echo "<option value='3'>Keyboard</option>";
echo "</select><br>";

echo "<label for='quantity'>Quantity:</label>";
echo "<input type='number' id='quantity' name='quantity' value='1' min='1'>";
echo "<br><br>";
echo "<input type='submit' value='Add to Cart'>";
echo "</form>";

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  addToCart($product_id, "Product Name", 0, $quantity); // Assuming price is dynamic
}
?>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($product_id, $product_name, $price, $quantity) {
  global $session; // Use global variable for session access
  $session = $_SESSION; // Access the session variable directly
  $session['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
  // Optionally, you can sort the cart items after adding an item
  // sort($session['cart']);
}


// Example Usage:

// Simulate a product adding event (e.g., from a form submission)
//  Assuming this data comes from a form on your webpage

$product_id = 1;
$product_name = "Awesome T-Shirt";
$price = 25.00;
$quantity = 2;

// Add the item to the cart
addItemToCart($product_id, $product_name, $price, $quantity);

// Display the contents of the cart
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
// To remove an item, you can loop through the cart and find the product_id.
// Then, you would unset($_SESSION['cart'][$product_id]); // Remove by ID
// Or, you could build a remove action form.
?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Example Usage (Simulating a product)
$product1_id = 1;
$product1_name = "T-Shirt";
$product1_price = 20.00;

$product2_id = 2;
$product2_name = "Jeans";
$product2_price = 50.00;

// --- Cart Operations ---

// 1. Add an item to the cart
addToCart($product1_id, $product1_name, $product1_price);
addToCart($product2_id, $product2_name, $product2_price, 2); // Add 2 pairs of Jeans

// 2. Display the cart contents (for demonstration)
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
    }
    echo "</ul>";
}



// 3. Remove an item
removeFromCart($product1_id);

// 4. Update quantity
updateQuantity($product2_id, 3);

// Display updated cart
echo "<h2>Shopping Cart (Updated)</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
    }
    echo "</ul>";
}

?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart already exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If product doesn't exist, add it to the cart with quantity 1
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to remove a product from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartItem($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// ------------------  Example Usage (Demonstration) ------------------

// Add some products to the cart
addToCart(1, 2); // Product ID 1, Quantity 2
addToCart(2, 1); // Product ID 2, Quantity 1
addToCart(1, 3); // Product ID 1, Quantity 3

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example of updating a quantity
updateCartItem(1, 5); //Update quantity of Product 1 to 5

echo "<br>";
echo "<h2>Cart Contents After Update:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example of removing an item from the cart
removeCartItem(2);

echo "<br>";
echo "<h2>Cart Contents After Removal:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example of clearing the cart
clearCart();

echo "<br>";
echo "<h2>Cart Contents After Clearing:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}
?>


<?php
session_start(); // Start the session

// --- Example Cart Logic ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// -----------------------------------------------------

// 1. Add Item to Cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increase the quantity if the product already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart with initial quantity of 1
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example usage:
add_to_cart(1, 'Awesome T-Shirt', 25.00, 2); // Add 2 t-shirts
add_to_cart(2, 'Cool Mug', 10.00, 1); // Add 1 mug
add_to_cart(1, 'Awesome T-Shirt', 25.00, 1); // Add 1 more t-shirt


// 2. Update Quantity
function update_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    // Handle the case where the product is not in the cart (e.g., remove it)
    // You might want to log this or display an error.
    // For example:
    // error_log("Product $product_id not in cart.");
  }
}

// Example usage:
update_quantity(1, 3); // Update quantity of Awesome T-Shirt to 3

// 3. Remove Item from Cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example usage:
remove_from_cart(2); // Remove the Cool Mug

// 4. Display Cart Contents (for debugging/preview)
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// 5. Calculate Total Cart Value (Example)
$total = 0;
foreach ($_SESSION['cart'] as $id => $item) {
    $total += $item['price'] * $item['quantity'];
}
echo "<p><strong>Total Cart Value: $" . number_format($total, 2) . "</strong></p>";


// --- End of Cart Logic ---

?>


<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Example Usage (Simulating a shopping cart interaction)

// Add a product to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (!empty($_SESSION['cart'])) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) . "</p>";
} else {
  echo "<p>Your cart is empty.</p>";
}

// Remove an item from the cart
remove_from_cart(2);

// Update the quantity of an item
update_cart_quantity(1, 3);

// Display the cart contents again
echo "<h2>Shopping Cart (Updated)</h2>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) . "</p>";
} else {
  echo "<p>Your cart is empty.</p>";
}


?>


<?php

// Start a session
session_start();

// Array to store cart items (name, quantity, price)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
  $item = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // Check if the item is already in the cart
  foreach ($cart as &$item_in_cart) {
    if ($item_in_cart['id'] == $item['id']) {
      $item_in_cart['quantity'] += $item['quantity'];
      break;
    }
  }

  // If the item is not in the cart, add it
  else {
    $cart[] = $item;
  }
}

// Function to get the cart total
function get_cart_total() {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Function to display the cart contents
function display_cart() {
  echo "<h2>Your Shopping Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart as $index => $item) {
      echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . number_format(get_cart_total(), 2) . "</strong></p>";
  }
}

// Example Usage:

// Add some items to the cart
add_to_cart(1, "T-Shirt", 20.00, 2);
add_to_cart(2, "Jeans", 50.00, 1);
add_to_cart(1, "T-Shirt", 20.00, 1); // Add another T-Shirt

// Display the cart contents
display_cart();

//  Simulate a user removing an item (Example) -  You would likely have a form for this
// $remove_item_id = 1;
// remove_from_cart($remove_item_id); //  Would need a remove_from_cart function (implementation not provided)

?>


<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If the product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'quantity' => $quantity,
            'name' => $product_name,
            'price' => $price
        );
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart items
function getCartItems() {
    return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// --- Example Usage (Simulating a product ID, quantity, etc.) ---

// Add a product to the cart
addToCart(123, 2, "Awesome T-Shirt", 20); // Product ID 123, quantity 2, name "Awesome T-Shirt", price 20

// Update the quantity of product 123 to 5
updateCartQuantity(123, 5);

// Get the cart items
$cart_items = getCartItems();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// Calculate and display the total cart value
$total = calculateCartTotal();
echo "<p>Total Cart Value: $" . $total . "</p>";

// Remove product 123
removeCartItem(123);

// Get and display cart items after removing an item
$cart_items = getCartItems();
echo "<h2>Cart Items After Removal:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}
?>


<?php
session_start();

// Function to add items to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart already exists in the session
  if (isset($_SESSION['cart']) === false) {
    // If not, initialize an empty cart array
    $_SESSION['cart'] = array();
  }

  // Get the existing cart items
  $cartItems = $_SESSION['cart'];

  // Check if the product is already in the cart
  if (isset($cartItems[$product_id])) {
    // If it exists, increment the quantity
    $cartItems[$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart with quantity 1
    $cartItems[$product_id] = array('quantity' => $quantity);
  }

  // Update the cart in the session
  $_SESSION['cart'] = $cartItems;
}

// Function to update the quantity of a product in the cart
function updateQuantity($product_id, $quantity) {
    // Check if the cart exists
    if (isset($_SESSION['cart']) === false) {
        return false; // Cart doesn't exist, can't update
    }

    $cartItems = $_SESSION['cart'];

    if (isset($cartItems[$product_id])) {
        // Update the quantity
        $cartItems[$product_id]['quantity'] = $quantity;
        $_SESSION['cart'] = $cartItems;
        return true;
    } else {
        return false; // Product not found in cart
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  // Check if the cart exists
  if (isset($_SESSION['cart']) === false) {
    return false; // Cart doesn't exist, can't remove
  }

  $cartItems = $_SESSION['cart'];

  if (isset($cartItems[$product_id])) {
    unset($cartItems[$product_id]);
    $_SESSION['cart'] = $cartItems;
    return true;
  } else {
    return false; // Product not found in cart
  }
}

// Function to get the cart items
function getCartItems() {
  if (isset($_SESSION['cart']) === false) {
    return array();
  }

  return $_SESSION['cart'];
}

// Example Usage:

// 1. Add an item to the cart
// Suppose $product_id = 123 and $quantity = 2
// addToCart(123, 2);

// 2. Update the quantity of a product (e.g., increase the quantity of product 123 by 1)
// updateQuantity(123, 1);

// 3. Remove an item from the cart
// removeCartItem(123);

// 4. Get the current cart items
$cart = getCartItems();
echo "Cart Items: ";
print_r($cart);

// To display the cart items on a webpage (example):
// You'd typically loop through the $cart array and display the product name, image, quantity, and price.

?>


<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize the cart array if it doesn't exist
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? [];  // Returns the cart array or an empty array if it doesn't exist
}

// Example Usage (Simulated Product Data)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200],
  2 => ['name' => 'Mouse', 'price' => 25],
  3 => ['name' => 'Keyboard', 'price' => 75]
];

// ---  Handling Add to Cart Request (Simulated) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
  $product_id = (int)$_POST['product_id']; // Ensure it's an integer
  $quantity = (int)$_POST['quantity'] ?? 1; // Default quantity is 1

  if (isset($products[$product_id])) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    echo "Item added to cart! (Product ID: " . $product_id . ")";
  } else {
    echo "Product not found.";
  }
}


// --- Displaying the Cart (for demonstration purposes) ---
if (isset($_SESSION['cart'])) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_name = $item['name'];
    $product_price = $item['price'];
    $quantity = $item['quantity'];

    echo "<li>" . $product_name . " - $" . $product_price . " x " . $quantity . " = $" . ($product_price * $quantity) . "</li>";
  }
  echo "</ul>";

  //  Example: Update quantity (simulated)
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product_id']) && isset($_POST['new_quantity'])) {
    $product_id = (int)$_POST['update_product_id'];
    $new_quantity = (int)$_POST['new_quantity'];
    updateQuantity($product_id, $new_quantity);
    echo "<p>Quantity updated to: " . $new_quantity . "</p>";
  }

  // Example: Remove item from cart (simulated)
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_product_id'])) {
    $product_id = (int)$_POST['remove_product_id'];
    removeItem($product_id);
    echo "<p>Item removed from cart.</p>";
  }


} else {
  echo "<p>Your cart is empty.</p>";
}
?>


<?php

// Start a session if it doesn't exist
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Product Information (For Demonstration) ---
// In a real application, you would fetch this from a database.
$products = array(
    array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
    array('id' => 2, 'name' => 'Mouse', 'price' => 25),
    array('id' => 3, 'name' => 'Keyboard', 'price' => 75),
);

// --- Functions to Handle Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 */
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        // If the product isn't in the cart yet, add it with the specified quantity
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity, 'price' => $product_id);  // Use product ID as the key, assuming prices are associated with the ID
    } else {
        // If the product is already in the cart, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

/**
 * Gets the items in the cart.
 *
 * @return array An array of items in the cart.
 */
function getCartItems() {
    return $_SESSION['cart'];
}

// --- Example Usage / Cart Interaction ---

// 1. Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
    // Optionally, display a success message
    echo "<p>Product added to cart.</p>";
}

// 2. Remove an item from the cart (e.g., via a delete button)
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    removeFromCart($product_id);
    // Optionally, display a success message
    echo "<p>Product removed from cart.</p>";
}

// 3. Display the cart contents
echo "<h2>Cart Items:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item) {
        $product_name = 'Product ' . $item_id; // Replace with actual product name lookup

        echo "<li>" . $product_name . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}


// 4. Calculate and display the total
echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";

?>


<?php
session_start();

// Array to store items in the cart
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (empty($cart)) {
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    // Check if the product already exists in the cart
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] += $quantity;
    } else {
      $cart[$product_id] = array(
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      );
    }
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    global $cart;

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Example usage (Simulating product data)
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Hat', 'price' => 15)
);


//  Handling Add to Cart requests (e.g., from a form)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $products[$product_id]['name'];
  $price = $products[$product_id]['price'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1 if not provided

  addToCart($product_id, $product_name, $price, $quantity);

  // Redirect to a cart page (or update the current page)
  header("Location: cart.php"); // Replace 'cart.php' with the appropriate URL
  exit();
}


// Handling Remove Cart Item requests
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($product_id);

    header("Location: cart.php");
    exit();
}

// Handling Update Quantity requests
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $new_quantity);

  header("Location: cart.php");
  exit();
}

// Displaying the cart contents (only in a cart page, e.g., cart.php)
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  // Display Cart Items
  if (!empty($cart)) {
    echo "<h2>Cart Items:</h2>";
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Cart Total
    $total = 0;
    foreach ($cart as $product_id => $item) {
      $total += ($item['price'] * $item['quantity']);
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";
  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>

  <form action="" method="post">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <?php
      foreach ($products as $id => $product) {
        echo "<option value='" . $id . "'>" . $id . "</option>";
      }
      ?>
    </select><br><br>

    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

  <a href="checkout.php">Checkout</a> <!-- Example checkout link -->

</body>
</html>


<?php
session_start();

// This is a basic example, suitable for small carts. 
// For production environments, use a database-backed solution for cart persistence.

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example usage (demonstration)
// Let's say we have the following products:
// Product ID: 1, Name: "Laptop", Price: 1200
// Product ID: 2, Name: "Mouse", Price: 25
// Product ID: 3, Name: "Keyboard", Price: 75

// Add a laptop to the cart
addToCart(1, 'Laptop', 1200);

// Add a mouse to the cart
addToCart(2, 'Mouse', 25, 2); // Add 2 of the mouse

// Update the quantity of the mouse
updateCartQuantity(2, 5); // Now we have 5 mice in the cart


// Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Price: $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Remove the mouse (example)
// removeCartItem(2);
// echo "<p>Mouse removed from cart.</p>";


// Example of how to clear the cart (for demonstration)
// session_destroy();  // Comment this out to keep the cart data
// session_unset(); // Clears all session variables
?>


<?php
session_start();

// Check if the session is already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.  Defaults to 1 if not provided.
 * @return void
 */
function add_to_cart(int $product_id, int $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart_quantity(int $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(int $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the current cart contents.
 *
 * @return array The cart contents array.
 */
function get_cart_contents() {
    return $_SESSION['cart'] ?? []; // Use ?? to return an empty array if 'cart' is not set.
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cart_items  The cart items (array of product IDs and quantities).
 * @param array $product_prices An associative array where keys are product IDs and values are prices.
 * @return float The total price.
 */
function calculate_total(array $cart_items, array $product_prices) {
    $total = 0;
    foreach ($cart_items as $product_id => $quantity) {
        if (isset($product_prices[$product_id])) {
            $total += $product_prices[$product_id] * $quantity;
        }
    }
    return $total;
}


// --- Example Usage ---

// 1. Add a product to the cart
add_to_cart(123, 2); // Add 2 of product ID 123

// 2. Add another product
add_to_cart(456, 1);

// 3. Update the quantity of product 123 to 5
update_cart_quantity(123, 5);

// 4. Get the cart contents
$cart = get_cart_contents();
print_r($cart); // Output the cart array (for debugging)

// 5. Calculate the total
$product_prices = [
    123 => 10.00,
    456 => 25.00
];
$total = calculate_total($cart, $product_prices);
echo "Total price: $" . number_format($total, 2) . "<br>"; // Output the total with formatting

// 6. Remove a product
remove_from_cart(456);

// 7. Get the cart contents after removing an item
$cart_after_removal = get_cart_contents();
print_r($cart_after_removal); // Verify that item 456 is no longer in the cart.

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
    // Add item to the cart array
    $_SESSION['cart'][] = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );

    // You might want to update the cart quantity if the item already exists
    updateCartItemQuantity($product_id, $quantity);
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

// Example usage:  Simulating a product being added to the cart.
//  Replace these with your actual product data and handling.

// Add an item to the cart
addToCart(1, "Awesome T-Shirt", 20.00, 2);
addToCart(2, "Cool Mug", 10.00, 1);


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate the total cost of the cart
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// Optional:  Function to remove an item from the cart
function removeItemFromCart($product_id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            // Optionally, re-index the cart array if necessary
            // reset($_SESSION['cart']);
            return;
        }
    }
}


// Example: Remove a product from cart
// removeItemFromCart(2);
?>


<?php
session_start();

// Check if the session is empty, if so, initialize the cart
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Example Functions for Cart Operations ---

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if it exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Handle case where the item is not in the cart
    // You could either:
    // 1.  Ignore the request (do nothing)
    // 2.  Throw an error (for debugging)
    // 3.  Add the item with the specified quantity (if it doesn't exist)
    // This example will ignore the request.
    // echo "Product ID $product_id not found in cart. Request ignored.
";
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// --- Example Usage (Simulating User Interaction) ---

// 1. Adding an item
add_to_cart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, $1200, Quantity 1
add_to_cart(2, "Mouse", 25, 2);   // Product ID 2, Mouse, $25, Quantity 2

// 2. Updating quantity
update_cart_quantity(1, 3); // Increase Laptop quantity to 3

// 3. Removing an item
remove_from_cart(2); // Remove Mouse from cart

// --- Displaying the Cart Contents ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>" . $product_data['name'] . "</strong> - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// --- Important Notes ---

// 1. Session Start:  `session_start()` is crucial. It initializes the PHP session, 
//    allowing you to store and retrieve data across multiple pages.  It *must* be called 
//    before any output is sent to the browser (e.g., before `echo`, `print`, etc.).

// 2. Session Data:  The `$_SESSION` array is the PHP mechanism for storing session data.

// 3. Cart Structure: The example uses an associative array (`$_SESSION['cart']`) where the 
//    keys are product IDs and the values are associative arrays containing product details 
//    (name, price, quantity).  You can customize this structure to fit your specific needs.

// 4. Security:
//    - Always sanitize and validate user input to prevent security vulnerabilities (e.g., SQL injection, XSS).
//    - Implement proper authentication and authorization to protect your session data.

// 5. Persistence: Session data is stored on the server and persists until the session expires or is explicitly destroyed.  You can configure session lifetime (e.g., by setting the `session.gc_maxlifetime` in your php.ini file).  Consider using a database to store session data for more robust persistence.

?>


<?php
session_start();

// ---------------------  Cart Functions ---------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][] = $product_id;
  } else {
    $_SESSION['cart'] = [$product_id];
  }
}

/**
 * Retrieves the contents of the cart.
 *
 * @return array The array containing the product IDs in the cart.
 */
function getCart() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return []; // Return an empty array if the cart is empty
  }
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart'][array_search($product_id, $_SESSION['cart'])]);
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return void
 */
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'])) {
    // Find the index of the product in the cart
    $index = array_search($product_id, $_SESSION['cart']);

    // Check if the product exists in the cart
    if ($index !== false) {
      $_SESSION['cart'][$index] = $new_quantity;
    }
  }
}


// ---------------------  Example Usage (Simulated) ---------------------

// For demonstration purposes, let's simulate adding items to the cart.
// In a real application, you would typically do this based on user actions
// (e.g., clicking an "Add to Cart" button).

// Example 1: Add product 1 to cart with quantity 2
addToCart(1, 2);

// Example 2: Add product 2 to cart with default quantity (1)
addToCart(2);

// Example 3: Update quantity of product 1 to 3
updateCartQuantity(1, 3);

// ---------------------  Cart Contents (for Debugging) ---------------------

// Display the cart contents
$cart_contents = getCart();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id) {
    echo "<li>Product ID: " . $product_id . "</li>";
  }
  echo "</ul>";
}

//  Example of removing a product
// removeFromCart(1);
// echo "<h2>Cart Contents after removing product 1:</h2>";
// $cart_contents = getCart();
// if (empty($cart_contents)) {
//   echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($cart_contents as $product_id) {
//     echo "<li>Product ID: " . $product_id . "</li>";
//   }
//   echo "</ul>";
// }



?>


<?php
session_start();

// Initialize the cart (empty array if it doesn't exist)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['name' => $product_name, 'price' => $price, 'quantity' => $quantity];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Example Usage (simulated product data - replace with your database or other source)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];

// Simulated form processing (e.g., from a form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
        $product_id = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default quantity is 1

        if (isset($products[$product_id])) {
            addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
        } else {
            // Handle invalid product ID (e.g., display an error message)
            echo "Invalid product ID: " . $product_id;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
        $product_id = $_POST['product_id'];
        removeFromCart($product_id);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
        $product_id = $_POST['product_id'];
        $new_quantity = (int)$_POST['quantity'];
        updateQuantity($product_id, $new_quantity);
    }
}

// Display the cart content
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='submit' name='action' value='Remove from Cart'>";
        echo "</form>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='number' name='quantity' value='" . $product_details['quantity'] . "' min='1' max='10'>";
        echo "<input type='submit' name='action' value='Update Quantity'>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
}

?>


<?php

session_start();

// --- Functions to manage the cart ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.  Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] ?? 0;
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Remove an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Update the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Get the contents of the cart.
 *
 * @return array  An array containing the product IDs and their quantities.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? [];
}


// --- Example Usage (for demonstration) ---

// 1.  Initialization (If the cart doesn't exist, initialize it)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// 2.  Adding items to the cart
addToCart(101, 2); // Add 2 of product ID 101
addToCart(102, 1); // Add 1 of product ID 102
addToCart(101, 3); // Add 3 of product ID 101 (overwriting previous quantity)


// 3. Displaying the cart contents
echo "<h2>Your Cart:</h2>";
echo "<ul>";
$cart_contents = getCartContents();

if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $product_id => $quantity) {
    //  In a real application, you'd likely fetch product details
    //  based on the $product_id from your database.
    //  For this example, we'll just display the product ID and quantity.
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
}
echo "</ul>";

// 4. Removing an item
removeFromCart(102); // Remove product ID 102

// 5. Updating the quantity
updateCartQuantity(101, 5); // Update the quantity of product ID 101 to 5

// Display the updated cart
echo "<h2>Your Cart (Updated):</h2>";
$cart_contents = getCartContents();

if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
}
echo "</ul>";


?>


<?php
session_start();

// ------------------------------------------------------------------
//  Cart Implementation (Simplified - for demonstration)
// ------------------------------------------------------------------

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart items
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return array();
}

// ------------------------------------------------------------------
//  Example Usage (Simulated Products - Replace with your actual data)
// ------------------------------------------------------------------

// Dummy product data (replace with your database query)
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Hat', 'price' => 15)
);


// ------------------------------------------------------------------
//  Session Handling - Example Actions
// ------------------------------------------------------------------

// 1. Add an item to the cart (e.g., user adds a T-Shirt)
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
  echo "<p>Item added to cart.</p>";
}

// 2. Remove an item from the cart (e.g., user removes Jeans)
if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
  echo "<p>Item removed from cart.</p>";
}

// 3. Update quantity (e.g., user changes the quantity of a T-Shirt)
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($product_id, $new_quantity);
  echo "<p>Quantity updated in cart.</p>";
}


// ------------------------------------------------------------------
//  Displaying the Cart
// ------------------------------------------------------------------

// Get cart items
$cart_items = getCartItems();

echo "<h2>Your Cart</h2>";

if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $item) {
    $product_name = $products[$product_id]['name'];
    $product_price = $products[$product_id]['price'];
    $quantity = $item['quantity'];
    $total_price = $product_price * $quantity;

    echo "<li>" . $product_name . " - $" . $product_price . " x " . $quantity . " = $" . $total_price . "</li>";
  }
  echo "</ul>";

  // Calculate the total cart value
  $total_cart_value = 0;
  foreach ($cart_items as $product_id => $item) {
    $total_price = $products[$product_id]['price'] * $item['quantity'];
    $total_cart_value += $total_price;
  }

  echo "<p><strong>Total Cart Value: $" . $total_cart_value . "</strong></p>";
}

?>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$productId] = array(
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  }
}


// Example Usage (replace with your product data)

// Add an item to the cart
addItemToCart(1, 'Laptop', 1200, 1);
addItemToCart(2, 'Mouse', 25, 2);


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $item) {
    echo "<li>";
    echo "Product: " . $item['name'] . "<br>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Subtotal: $" . $item['price'] * $item['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Remove an item from the cart
//removeItemFromCart(2);

// Update quantity
//updateQuantity(1, 3);
?>


<?php

session_start();

// --------------------- Cart Functions ---------------------

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add. Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array An array containing all items in the cart.
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for empty array
}


/**
 * Calculates the total price of all items in the cart.
 *
 * @return float The total price.
 */
function calculateTotal() {
  $total = 0;
  $cart = getCart();

  foreach ($cart as $product_id => $item) {
    // Assuming you have a way to get the price of a product based on its ID.
    // This is just an example - you'll need to adapt this to your product data.
    $price = getProductPrice($product_id); // Implement this function
    $total_for_item = $price * $item['quantity'];
    $total = $total + $total_for_item;
  }

  return $total;
}



// --------------------- Helper Functions (Implement these) ---------------------

/**
 * Placeholder function to get the price of a product.  You need to replace this.
 * This is just an example.  You'll need to retrieve product prices from your database or other source.
 *
 * @param string $product_id The ID of the product.
 * @return float The price of the product.
 */
function getProductPrice($product_id) {
    // *** REPLACE THIS WITH YOUR ACTUAL LOGIC TO RETRIEVE THE PRODUCT PRICE ***
    // Example:
    if ($product_id == 'product1') {
        return 10.00;
    } elseif ($product_id == 'product2') {
        return 20.00;
    } else {
        return 0; // Or handle the error as appropriate.
    }
}

// --------------------- Example Usage ---------------------

// Add some products to the cart
addToCart('product1', 2);
addToCart('product2', 1);
addToCart('product1', 1); // Add more of product1

// Display the contents of the cart
echo "<h2>Cart Items</h2>";
$cart = getCart();
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Price: $" . number_format(calculateTotal(), 2) . "</p>";

// Update the quantity of a product
updateCartQuantity('product1', 3);

// Remove an item from the cart
removeFromCart('product2');

// Display the updated cart
echo "<h2>Updated Cart Items</h2>";
$cart = getCart();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Price: $" . number_format(calculateTotal(), 2) . "</p>";

?>


<?php
session_start(); // Start the session

// ---  Cart Initialization ---

// Initialize an empty cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --------------------- Helper Functions ---------------------

/**
 * Add an item to the cart.
 *
 * @param string $product_id The unique ID of the product to add.
 * @param string $name The name of the product.
 * @param float $price The price of the product.
 * @param int $quantity The quantity to add (default: 1).
 */
function addToCart($product_id, $name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

/**
 * Update the quantity of an item in the cart.
 *
 * @param string $product_id The unique ID of the product to update.
 * @param int $quantity The new quantity.
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Remove an item from the cart.
 *
 * @param string $product_id The unique ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Get all items in the cart.
 *
 * @return array The cart items.
 */
function getCartItems() {
    return $_SESSION['cart'];
}

/**
 * Calculate the total cart value.
 *
 * @return float The total value.
 */
function calculateTotal() {
    $total = 0;
    $cartItems = getCartItems();
    foreach ($cartItems as $item) {
        $totalItemValue = $item['price'] * $item['quantity'];
        $total += $totalItemValue;
    }
    return $total;
}

// --------------------- Example Usage (Simulated) ---------------------

// --- Add items to the cart based on user actions (e.g., button clicks) ---
// In a real application, this would come from form submissions or AJAX requests

// Example 1: Add a product to the cart
addToCart('product1', 'Awesome T-Shirt', 20.00, 2);

// Example 2:  Update the quantity of an existing product
updateQuantity('product1', 3); // Increase quantity of 'product1' to 3

// Example 3: Remove an item from the cart
// removeFromCart('product1');

// --------------------- Display the Cart ---------------------

// Get cart items
$cart = getCartItems();

// Calculate total
$total = calculateTotal();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $item['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Cart Value:</strong> $" . number_format($total, 2) . "</p>";
}


?>


<?php

session_start();

// --- Cart Logic ---

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to calculate the cart total
function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// --- Example Usage (Illustrative - Replace with your actual product data) ---

// Add some items to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);
addToCart(1, 'T-Shirt', 20, 3); // Add more of the T-Shirt

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateCartTotal() . "</strong></p>";
}

// Example: Update quantity of product 1
updateQuantity(1, 5);

// Example: Remove product 2 from the cart
// removeFromCart(2);

// Display updated cart
echo "<h2>Your Shopping Cart (Updated)</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateCartTotal() . "</strong></p>";
}

?>


<?php

session_start(); // Start the session

// ------------------ Cart Functions ------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}


/**
 * Removes a specific item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity for the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Gets the items in the cart.
 *
 * @return array An array representing the cart items.
 */
function getCartItems() {
  return $_SESSION['cart'];
}

/**
 * Returns the total number of items in the cart
 * @return int
 */
function cartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $product_id => $quantity) {
            $total += $quantity;
        }
    }
    return $total;
}

// ------------------ Example Usage (Demonstration) ------------------

// Add a product to the cart
addToCart(123);  // Add product with ID 123

// Add a second item to the cart
addToCart(456, 3); // Add product with ID 456 and quantity 3

// Update the quantity of the first item
updateCartQuantity(123, 5);

// Remove the second item
removeFromCart(456);

// Get the items in the cart
$cart = getCartItems();
print_r($cart); // Output: Array ( [123] => 5 )

echo "Cart Total: " . cartTotal();

// ------------------  Example HTML (for adding products to cart) ------------------

//  This is a simplified example.  In a real application, you'd likely
//  get product information (name, price, image, etc.) from a database.

// <form method="post" action="">
//   <input type="hidden" name="product_id" value="123">
//   <input type="number" name="quantity" value="1" min="1" max="10">
//   <input type="submit" value="Add to Cart">
// </form>

//  You'd need to handle the form submission to call the addToCart() function.
//  For example:

//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//      $product_id = $_POST['product_id'];
//      $quantity = $_POST['quantity'];
//      addToCart($product_id, $quantity);
//  }

?>


<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage (Simulating user actions - Replace with your actual logic)

// 1. Add an item to the cart
addToCart(1, "Awesome T-Shirt", 25.00, 2);  // product_id, product_name, price, quantity
addToCart(2, "Cool Mug", 12.50, 1);
// 2. Update the quantity of an item
updateCartQuantity(1, 5); // Increase the quantity of product ID 1 to 5

// 3. Remove an item from the cart
// removeCartItem(2);


// Displaying the cart contents (for demonstration)
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}


?>

