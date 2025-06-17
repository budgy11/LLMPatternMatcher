
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

    <h2>Select a Product to Purchase</h2>
    <form method="post">
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <?php
            // Display product IDs in the select dropdown
            $stmt = $pdo->prepare("SELECT id FROM products");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['id'] . "'>" . $row['id'] . "</option>";
            }
            ?>
