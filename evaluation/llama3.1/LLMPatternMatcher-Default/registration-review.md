


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
[0;31mdefine('DB_PASSWORD'[0m, 'your_password');
define('DB_NAME', 'ecommerce_database');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
}
?>
```
#####
Alerts:
Variable may contain secrets that should not be stored in code
#####

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
#####
Alerts:
#####

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
        if ([0;31mmysqli_query($conn, "SELECT * FROM users WHERE email='$email'"))[0m {
            $result = [0;31mmysqli_query($conn, "SELECT * FROM users WHERE email='$email'")[0m;
            if (mysqli_num_rows($result) == 0) {

                // Hash the password
                $hashed_password = [0;31mmd5([0m$password);

                // Insert into database
                $query = "INSERT INTO users (email, password, name, address, phone)
                           [0;31mVALUES ('$email', '$hashed_password', '$name', '$address', '$phone')[0m";
                if ([0;31mmysqli_query($conn, $query))[0m {
                    echo 'Account created successfully!';
                } else {
                    [0;31mecho 'Error creating account: ' . mysqli_error($conn);[0m
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
    <form action="<?php [0;31mecho $_SERVER['PHP_SELF'];[0m ?>" method="post">
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
#####
Alerts:
Echo may lead to XSS if passed unsanitized input
Use of non parameterized SQL Queries can lead to SQLI and is strongly discouraged
VALUES followed by a variable may lead to SQLI
md5 is a known insecure hashing algorithm and should be avoided when possible
#####

**Note:** This code uses MD5 to hash the password, which is not secure. You should use a more secure hashing algorithm like bcrypt or Argon2.

Also, this code assumes you have already created the database and tables in your MySQL server. Make sure to replace `your_username`, `your_password`, and `ecommerce_database` with your actual credentials and database name.

Please keep in mind that this is a basic example and should not be used as-is for production purposes. You should add more error handling, security measures (like validating user input), and other features to make it more robust.


