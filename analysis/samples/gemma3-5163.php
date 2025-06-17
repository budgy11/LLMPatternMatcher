    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Replace with your actual database credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ... (rest of the code from above) ...
?>
