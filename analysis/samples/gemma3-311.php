
<!-- HTML Form for Login -->
<form method="post" action="">
    Username: <input type="text" name="login_username" value="<?php if(isset($_POST['login_username'])) echo $_POST['login_username']; ?>">
