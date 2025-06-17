
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

<h1>Online Store</h1>

<div id="product-list">
    <h2>Available Products</h2>
    <ul>
        <?php
        foreach ($products as $id => $product) {
            echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "<br>";
            echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
";
            echo "   <label for='quantity-" . $id . "'>Quantity:</label>
";
            echo "   <input type='number' id='quantity-" . $id . "' value='1' min='1' name='quantity-" . $id . "' style='width:50px;'>
";
            echo "   <input type='submit' value='Add to Cart'>
";
            echo "</form></li>";
        }
        ?>
