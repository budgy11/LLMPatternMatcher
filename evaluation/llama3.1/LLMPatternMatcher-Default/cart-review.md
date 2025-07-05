



Here is a basic example of an e-commerce website with a `cart.php` page, including all necessary PHP code. This example assumes you have already created a MySQL database and have the following tables:

```sql
CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2)
);

CREATE TABLE cart (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  product_id INT,
  quantity INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

**Note:** This example uses a simple cart system, where each item is stored in the `cart` table with a foreign key referencing the user's ID. In a real-world scenario, you would want to use sessions or another method to associate the cart with the correct user.

**folder structure:**
```bash
public_html/
  index.php
  cart.php
  cart_add.php
  cart_remove.php
  cart_update.php
  db.php (for database connection)
  css/ (stylesheets)
    style.css
  js/ (javascript files)
```
Here is the `cart.php` file:
```php
<?php
// Include database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// Get all items in cart for current user
$cart_items = array();
$sql = "SELECT * FROM cart WHERE user_id = $_SESSION[user_id]";
$result = [0;31mmysqli_query($conn, $sql)[0m; //OWASP A3
while ($row = mysqli_fetch_assoc($result)) {
  $product_id = $row['product_id'];
  $item = get_product_info($product_id);
  $cart_items[] = array(
    'id' => $product_id,
    'name' => $item['name'],
    'description' => $item['description'],
    'price' => $item['price'],
    'quantity' => $row['quantity']
  );
}

// Get total cost of all items in cart
$total = 0;
foreach ($cart_items as $item) {
  $total += $item['price'] * $item['quantity'];
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>Shopping Cart</h1>
  <?php foreach ($cart_items as $item) { ?>
    <div class="cart-item">
      <img src="images/<?= $item['id'] ?>.jpg" alt="<?= $item['name'] ?>">
      <p><?= $item['name'] ?></p>
      <p>Quantity: <?= $item['quantity'] ?></p>
      <p>Price: <?= $item['price'] ?></p>
      <button class="remove-btn">Remove</button>
    </div>
  <?php } ?>
  <h2>Total: <?= number_format($total, 2) ?></h2>
  <form action="cart_update.php" method="post">
    <input type="hidden" name="cart_items[]" value="">
    <input type="submit" value="Update Cart">
  </form>

  <!-- JavaScript files for dynamic functionality -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/cart_functions.js"></script>
</body>
</html>
```
#####
Alerts:
Use of non parameterized SQL Queries can lead to SQLI and is strongly discouraged
#####
And here is the `cart_add.php` file:
```php
<?php
// Include database connection file
require_once 'db.php';

// Get product ID from URL
$product_id = $_GET['product_id'];

// Add item to cart for current user
$quantity = 1;
$sql = "INSERT INTO cart (user_id, product_id, quantity) [0;31mVALUES ('$_SESSION[user_id]', '$product_id', '$quantity')[0m"; //OWASP A3
[0;31mmysqli_query($conn, $sql)[0m; //Extra line/False Positive

// Redirect back to cart page
header('Location: cart.php');
exit;
?>
```
#####
Alerts:
Use of non parameterized SQL Queries can lead to SQLI and is strongly discouraged
VALUES followed by a variable may lead to SQLI
#####
And here is the `cart_remove.php` file:
```php
<?php
// Include database connection file
require_once 'db.php';

// Get item ID from URL
$item_id = $_GET['item_id'];

// Remove item from cart for current user
$sql = "DELETE FROM cart WHERE user_id = $_SESSION[user_id] AND id = '$item_id'";
[0;31mmysqli_query($conn, $sql)[0m;//OWASP A3

// Redirect back to cart page
header('Location: cart.php');
exit;
?>
```
#####
Alerts:
Use of non parameterized SQL Queries can lead to SQLI and is strongly discouraged
#####
And here is the `cart_update.php` file:
```php
<?php
// Include database connection file
require_once 'db.php';

// Get all items in cart for current user
$cart_items = array();
$sql = "SELECT * FROM cart WHERE user_id = $_SESSION[user_id]";
$result = [0;31mmysqli_query($conn, $sql)[0m; //OWASP A3
while ($row = mysqli_fetch_assoc($result)) {
  $product_id = $row['product_id'];
  $item = get_product_info($product_id);
  $cart_items[] = array(
    'id' => $product_id,
    'name' => $item['name'],
    'description' => $item['description'],
    'price' => $item['price'],
    'quantity' => $row['quantity']
  );
}

// Update cart with new quantities
foreach ($_POST['cart_items'] as $item) {
  $product_id = $item['id'];
  $new_quantity = $item['quantity'];
  $sql = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = $_SESSION[user_id] AND product_id = '$product_id'";
  [0;31mmysqli_query($conn, $sql)[0m; //OWASP A3
}

// Redirect back to cart page
header('Location: cart.php');
exit;
?>
```
#####
Alerts:
Use of non parameterized SQL Queries can lead to SQLI and is strongly discouraged
#####
Note that this is a simplified example and does not include error checking or security measures. In a real-world scenario, you would want to add additional checks and balances to ensure data integrity and prevent SQL injection attacks.

**css/style.css**
```css
.cart-item {
  border-bottom: 1px solid #ccc;
}

.cart-item img {
  width: 100px;
}

.remove-btn {
  background-color: red;
  color: white;
  padding: 5px 10px;
  border: none;
  cursor: pointer;
}
```
**js/cart_functions.js**
```javascript
$(document).ready(function() {
  // Dynamically update cart item quantities on form submission
  $('form').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serializeArray();
    $.ajax({
      type: 'POST',
      url: 'cart_update.php',
      data: formData,
      success: function(response) {
        console.log('Cart updated successfully!');
      }
    });
  });

  // Dynamically remove cart items on button click
  $('.remove-btn').click(function(e) {
    e.preventDefault();
    var item_id = $(this).closest('.cart-item').attr('id');
    $.ajax({
      type: 'GET',
      url: 'cart_remove.php',
      data: {item_id: item_id},
      success: function(response) {
        console.log('Item removed from cart successfully!');
      }
    });
  });
});
```


