```php
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
```