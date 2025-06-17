
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize the input data
  $username = htmlspecialchars(trim($_POST["username"]));
  $email = htmlspecialchars(trim($_POST["email"]));
  $password = htmlspecialchars(trim($_POST["password"]));

  // Validate the input data
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT - DO NOT STORE PLAIN TEXT PASSWORDS)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the data to insert into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Assuming $conn is your database connection

    if ($stmt) {
      $stmt->bind_param("sss", $username, $email, $hashed_password); // s = string
      $stmt->execute();
      $errors = "Registration successful!";
    } else {
      $errors = "Error preparing statement.";
    }

    $stmt->close();
  }

  // Display the errors
  if (!empty($errors)) {
    echo "<div class='error'>" . $errors . "</div>";
  }

  //  Redirect to the login page or other appropriate page after successful registration
  if (empty($errors)) {
    //  Implement redirect logic here, e.g.:
    // header("Location: login.php");
    // exit; // Important to stop further execution after redirect
  }
}
?>
