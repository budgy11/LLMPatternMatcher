    </ul>

    <h2>Add to Cart</h2>
    <form method="post">
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <?php
            foreach ($products as $id => $product) {
                echo "<option value='" . $id . "'>" . $id . "</option>";
            }
            ?>
