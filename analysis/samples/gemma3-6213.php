
<h1>Add to Cart</h1>

<form method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" value="<?php if(isset($_POST['product_id'])) echo $_POST['product_id']; ?>">
