        </div>

        <form method="post">
            <label for="product_id">Select Product to Remove:</label>
            <select name="product_id" id="product_id">
                <?php if (empty($_SESSION['cart'])) { ?>
