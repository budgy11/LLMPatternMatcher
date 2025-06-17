
<hr>

<h2>Purchase Form</h2>
<form method="post">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"><br><br>
