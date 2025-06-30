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
