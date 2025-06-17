    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Forgot Password</button>
    </form>
    <?php
}

?>
