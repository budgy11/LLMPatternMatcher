        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

function registerUser($username, $password, $email, $db) {
  // Sanitize inputs - VERY IMPORTANT!  Prevent SQL injection and other security issues.
  $username = mysqli_real_escape_string($db, $username);
  $password = mysqli_real_escape_string($db, $password);
  $email = mysqli_real_escape_string($db, $email);

  //  Use prepared statements for security.  This is the *recommended* approach.
  //  This example demonstrates the basic principle.  You'll likely need to adapt
  //  the prepared statement to your specific database library.
  /*
  $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $password, $email);
  $result = $stmt->execute();
  $stmt->close();
  */


  // Alternative - If you can't use prepared statements (less secure, avoid if possible)
  // Check if the username already exists
  $result = mysqli_query($db, "SELECT id FROM users WHERE username = '$username'");
  if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
      // Username already exists - handle the error appropriately (e.g., display an error message)
      echo "Username already exists!";
      return false;
    }
  }

  // Hash the password - NEVER store passwords in plain text!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the user into the database
  $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
  $result = mysqli_query($db, $query);

  if ($result) {
    return true; // Registration successful
  } else {
    // Handle database error - log the error, display an error message to the user.
    echo "Error registering user.  Database error: " . mysqli_error($db);
    return false;
  }
}


// Example Usage (for demonstration purposes -  do NOT use this directly in a web application!)
// This is just to show how the function is called.

//  Simulating database connection (replace with your actual database connection)
$db = mysqli_connect("localhost", "your_username", "your_password", "your_database");

if (!$db) {
    die('Could not connect to database: ' . mysqli_error($db));
}


// Simulate form data (in a real application, this would come from a form)
$username = "testuser";
$password = "password123";
$email = "testuser@example.com";

// Call the registration function
if (registerUser($username, $password, $email, $db)) {
  echo "User registered successfully!";
} else {
  echo "User registration failed.";
}

mysqli_close($db); // Close the database connection
?>
