<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include 'db.php';
    $username = $_POST['input_username'];
    $password = $_POST['input_password'];
    
    $sql = "SELECT * FROM `farmer` WHERE `farmer_username`='$username'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if($num == 1){
        while($row = mysqli_fetch_assoc($result)){
            if(password_verify($password,$row['farmer_password'])){
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("location: farmer.php");
            }
            else {
                echo "<script>alert('Invalid username or password, Please login again.!'); window.location='index.php';</script>";
            } 
        }  
    }
    else {
        echo "<script>alert('Invalid username or password, Please login again.!'); window.location='index.php';</script>";
    }
}
?>