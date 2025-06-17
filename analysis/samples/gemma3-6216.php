    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" step="1" min="1" value="<?php if(isset($_POST['quantity'])) echo intval($_POST['quantity']); ?>">
