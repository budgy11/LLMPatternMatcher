
<!DOCTYPE html>
<html>
<head>
    <title>Online Store</title>
</head>
<body>

<h1>Online Store</h1>

<h2>Products</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - Price: $" . $product['price'] . "</li>";
    }
    ?>
