        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <?php if (isset($error)) {
        echo "<p style='color: red;'>Error: " . $error . "</p>";
    } ?>
