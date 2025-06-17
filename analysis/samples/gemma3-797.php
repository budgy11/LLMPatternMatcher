        <br><br>
        <button type="submit">Login</button>
    </form>

    <br><br>
    <a href="register.php">Don't have an account? Register here.</a> <!-- Link to registration page -->

</body>
</html>


     <?php
     // Database connection details (replace with your actual credentials)
     $host = "localhost";
     $username = "your_db_username";
     $password = "your_db_password";
     $database = "your_db_name";

     // Check if the form has been submitted
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
         // Get the username and password from the form
         $username = $_POST["username"];
         $password = $_POST["password"];

         // Validate the input (basic validation - improve as needed)
         if (empty($username) || empty($password)) {
             $error = "Username and password cannot be empty.";
         } else {
             // Database query - Use prepared statements
             $conn = new mysqli($host, $username, $password, $database);

             if ($conn->connect_error) {
                 die("Connection failed: " . $conn->connect_error);
             }

             // Prepare the statement
             $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND password = ?");

             // Bind the parameters
             $stmt->bind_param("ss", $username, $password); // "ss" means two strings

             // Execute the statement
             $stmt->execute();

             $result = $stmt->get_result();

             if ($result->num_rows > 0) {
                 // User found, set session variables
                 session_start();
                 $_SESSION["username"] = $username;

                 // Redirect to a secure page or display a success message
                 header("Location: welcome.php");
                 exit();
             } else {
                 $error = "Invalid username or password.";
             }
         }
     }
     ?>
