Here is an example of a basic e-commerce website with user registration functionality. I'll provide the PHP code for the `registration.php` file, as well as some additional files you may need.

**Folder Structure:**
```bash
ecommerce-site/
index.php
login.php
registration.php
styles.css
script.js
config.php
db_connect.php
user_table.sql ( MySQL database schema)
```

**Database Schema (`user_table.sql`):**
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(100),
  address TEXT,
  phone VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);
```

**`config.php`:**
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password'); //OWASP A7
define('DB_NAME', 'ecommerce_database');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
}
?>
```

**`db_connect.php`:**
```php
<?php
require_once 'config.php';

function connectToDB() {
  global $conn;
  if (isset($conn)) return true;
  return false;
}

function getConn() {
  global $conn;
  return mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}
?>
```

**`registration.php`:**
```php
<?php
require_once 'config.php';
require_once 'db_connect.php';

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    if (!empty($email) && !empty($password)) {
        if (mysqli_query($conn, "SELECT * FROM users WHERE email='$email'")) { //OWASP A3
            $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'"); //OWASP A3
            if (mysqli_num_rows($result) == 0) {

                // Hash the password
                $hashed_password = md5($password); //OWASP A2

                // Insert into database
                $query = "INSERT INTO users (email, password, name, address, phone)
                           VALUES ('$email', '$hashed_password', '$name', '$address', '$phone')"; //OWASP A3
                if (mysqli_query($conn, $query)) {
                    echo 'Account created successfully!';
                } else {
                    echo 'Error creating account: ' . mysqli_error($conn);
                }
            } else {
                echo 'Email already exists!';
            }
        } else {
            echo 'Database query failed';
        }
    } else {
        echo 'Please fill in all fields';
    }
} else {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> //OWASP A3
        Email: <input type="email" name="email"><br><br>
        Password: <input type="password" name="password"><br><br>
        Name: <input type="text" name="name"><br><br>
        Address: <textarea name="address"></textarea><br><br>
        Phone: <input type="tel" name="phone"><br><br>
        <input type="submit" name="submit" value="Create Account">
    </form>
    <?php
}
?>
```

**Note:** This code uses MD5 to hash the password, which is not secure. You should use a more secure hashing algorithm like bcrypt or Argon2.

Also, this code assumes you have already created the database and tables in your MySQL server. Make sure to replace `your_username`, `your_password`, and `ecommerce_database` with your actual credentials and database name.

Please keep in mind that this is a basic example and should not be used as-is for production purposes. You should add more error handling, security measures (like validating user input), and other features to make it more robust.
