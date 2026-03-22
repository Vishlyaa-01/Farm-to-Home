<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include 'db.php';
    $username = $_POST['input_Busername'];
    $password = $_POST['input_Bpassword'];
    
    $sql = "SELECT * FROM `buyer` WHERE `buyer_username`='$username' AND `buyer_password`='$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if($num == 1){
        while($row = mysqli_fetch_assoc($result)){
            //if(password_verify($password,$row['buyer_password'])){
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("location: buyer.php");
            /*} 
             else {
                echo "<script>alert('Invalid username or password, Please login again.!'); window.location='index.php';</script>".mysqli_error($conn);
            } */
        }  
    }
    else {
        echo "<script>alert('Invalid username or password, Please login again.!'); window.location='index.php';</script>";
    }
}
?>