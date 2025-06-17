

<?php
session_start(); // Start the session

/**
 * Handles user login.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login was successful, false otherwise.
 */
function loginUser($username, $password) {
  // **IMPORTANT:  Never store passwords directly in the code. This is for demonstration only.**
  //  In a real application, you MUST hash and salt passwords before storing them.
  //  Using a library like Sodium or Blowfish is highly recommended.

  // Example: Check against hardcoded credentials (REMOVE THIS FOR PRODUCTION!)
  $validUsers = array(
    'john.doe' => 'password123',
    'jane.smith' => 'securepass',
  );

  if (isset($validUsers[$username])) {
    if (hash_equals($validUsers[$username], password_hash($password, PASSWORD_DEFAULT))) {
      $_SESSION['username'] = $username; // Store the username in the session
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

/**
 * Handles user logout.
 *
 * @return void
 */
function logoutUser() {
  unset($_SESSION['username']); // Remove the username from the session
  session_destroy(); // Destroy the session
}

// **Example Usage (Illustrative - Do not use this directly in a website)**
// Assume a form is submitted with username and password.

//  If form data is submitted...
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  Welcome, " . $username;
    // Redirect the user to a protected page or display a welcome message.
    // header("Location: /protected_page.php"); // Example: Redirect
  } else {
    echo "Invalid username or password.";
  }
}

// Example Logout Button (In your HTML)
// <form method="post" action="login.php"> ... </form>
// <button type="button" onclick="logoutUser()">Logout</button>
?>
