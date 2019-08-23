<?php
    if(isset($_POST['submit'])){

    include_once 'main-db-connection.php';

    $uid=mysqli_real_escape_string($conn, $_POST['username']);
    $new_pwd=mysqli_real_escape_string($conn, $_POST['new_password']);
    $con_pwd=mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if(empty($uid) || empty($new_pwd) || empty($con_pwd)){
    header("Location: forgot-password.php?forgot-password=empty");
    exit();
    }else{
         $sql="SELECT * from user WHERE username=?";
         $stmt = mysqli_stmt_init($conn);
         if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL error!";
         }else{
            $data = Array();
            mysqli_stmt_bind_param($stmt, "s", $uid);
            mysqli_stmt_execute($stmt);
            $result=mysqli_stmt_get_result($stmt);
            while($row=mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            if(0==count($data)){
                header("Location: forgot-password.php?forgot-password=userdoesnotexist");
                exit();
            }else{
                  if ($new_pwd!= $con_pwd){
                      header("Location: forgot-password.php?forgot-password=passwordNotMatch");
                      exit();
                    }else{
                     $hashedPwd=password_hash($new_pwd, PASSWORD_DEFAULT);
                  //using prepared statements
                   $sql= "UPDATE user SET password=? WHERE username=?";
                   $stmt = mysqli_stmt_init($conn);
                   if(!mysqli_stmt_prepare($stmt, $sql)){
                       echo "SQL error!";
                   } else{
                        mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $uid);
                        mysqli_stmt_execute($stmt);
                        header("Location: ../../html/newsFeed-new.html?new-user=success");

                 }
              }
            }
        }
    }
}
 else{
    header("Location: forgot-password.php?forgot-password=error");
    exit();
}