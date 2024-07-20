<?php
include "creation.php";

if(isset($_POST['submit']))
{
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['no'];
    $sql="INSERT INTO t1 (name, email, phone) VALUES ('$name', '$email', '$phone')"; 
    
    if(mysqli_query($conn,$sql))
    {
        echo "New Record Inserted.";
    }
    else
    {
        echo "Error: ".$sql.mysqli_error($conn);
    }
}
?>

<!-- <html>
<form method='post'>
Enter name<input type='text' name='name'></br></br>
Enter email<input type='text' name='email'></br></br>
Enter mobile<input type='text' name='no'></br></br>
<input type='submit' value='submit' name='submit'></br></br>
</form>
</html> -->

<!DOCTYPE html>
<html>
<head>
    <title>Insert Data</title>
</head>
<body>
    <form method="post">
        Enter name: <input type="text" name="name"><br><br>
        Enter email: <input type="text" name="email"><br><br>
        Enter mobile: <input type="text" name="no"><br><br>
        <input type="submit" value="Submit" name="submit"><br><br>
    </form>
</body>
</html>