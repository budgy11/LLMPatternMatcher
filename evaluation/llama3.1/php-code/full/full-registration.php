
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'ecommerce_database');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
}
?>

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
        if (mysqli_query($conn, "SELECT * FROM users WHERE email='$email'")) {
            $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
            if (mysqli_num_rows($result) == 0) {

                // Hash the password
                $hashed_password = md5($password);

                // Insert into database
                $query = "INSERT INTO users (email, password, name, address, phone)
                           VALUES ('$email', '$hashed_password', '$name', '$address', '$phone')";
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
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
