```php
<?php
// Include database connection file
require_once 'db.php';

// Get item ID from URL
$item_id = $_GET['item_id'];

// Remove item from cart for current user
$sql = "DELETE FROM cart WHERE user_id = $_SESSION[user_id] AND id = '$item_id'";
mysqli_query($conn, $sql);

// Redirect back to cart page
header('Location: cart.php');
exit;
?>
```