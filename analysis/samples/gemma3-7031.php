
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase System</title>
</head>
<body>

    <h1>Our Products</h1>
    <form method="post">
        <?php
        foreach ($products as $id => $product) {
            echo "<label for=\"product_" . $id . "\">" . $product['name'] . " - $" . $product['price'] . "<br>";
            echo "<input type=\"radio\" id=\"product_" . $id . "\" name=\"product_id\" value=\"" . $id . "\"> </label>";
        }
        ?>
