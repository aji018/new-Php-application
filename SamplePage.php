<?php include "dbinfo.inc"; ?>
<html>
<body>
<h1>Registration Page</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the USERS table exists. */
  VerifyUsersTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the USERS table. */
  $username = htmlentities($_POST['USERNAME']);
  $email = htmlentities($_POST['EMAIL']);

  if (strlen($username) || strlen($email)) {
    AddUser($connection, $username, $email);
  }
?>

<!-- Registration form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>Username</td>
      <td>Email Address</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="USERNAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="EMAIL" maxlength="90" size="60" />
      </td>
      <td>
        <input type="submit" value="Register" />
      </td>
    </tr>
  </table>
</form>

<!-- Clean up. -->
<?php
  mysqli_close($connection);
?>

</body>
</html>


<?php

/* Add a user to the table. */
function AddUser($connection, $username, $email) {
   $u = mysqli_real_escape_string($connection, $username);
   $e = mysqli_real_escape_string($connection, $email);

   $query = "INSERT INTO USERS (USERNAME, EMAIL) VALUES ('$u', '$e');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding user data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyUsersTable($connection, $dbName) {
  if(!TableExists("USERS", $connection, $dbName))
  {
     $query = "CREATE TABLE USERS (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         USERNAME VARCHAR(45),
         EMAIL VARCHAR(90)
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>

