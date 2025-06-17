            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password"><br><br>

            <input type="submit" value="Register">
        </form>
    </body>
    </html>
    <?php
}

// Close the database connection
$conn->close();
?>
