    <br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" value="<?php if(isset($_POST['price'])) echo floatval($_POST['price']); ?>">
