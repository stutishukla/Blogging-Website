<?php
 
if(isset($_POST['submit'])){

 include_once 'main-db-connection.php';
 //echo json_encode($_POST);
 //print_r($_POST);
 $uid=mysqli_real_escape_string($conn, $_POST['username']);
 $first= mysqli_real_escape_string($conn, $_POST['first_name']);
 $last= mysqli_real_escape_string($conn, $_POST['last_name']);
 $dob= mysqli_real_escape_string($conn, $_POST['dob']);
 $countryCode= mysqli_real_escape_string($conn, $_POST['country_code']);

 if(empty($uid) || empty($first) || empty($last) || empty($dob) || empty($countryCode)){
    header("Location: new-user.php?new-user=empty");
	exit();
 }else{
 	 //using prepared statements
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
        //error handling
            if(0==count($data)){
    		    header("Location: new-user.php?new-user=userdoesnotexist");
    		    exit();
            }else{
               if(!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)){
               header("Location: ../../html/new-user.html?new-user=invalid");
	           exit();
           }else{
    	         list($m, $d, $y) = explode('/', $dob);
    	         if(!checkdate($m, $d, $y)){
    		     header("Location: new-user.php?new-user=invalidDOB");
	             exit();
    	     }else{
    		      //using prepared statements
    		       $sql= "UPDATE user SET first_name=?, last_name=?, dob=?, country_code=? WHERE username=?";
    		       $stmt = mysqli_stmt_init($conn);
    		       if(!mysqli_stmt_prepare($stmt, $sql)){
    			       echo "SQL error!";
    		       } else{
    			        mysqli_stmt_bind_param($stmt, "sssss", $first, $last, $dob, $countryCode, $uid);
    			        mysqli_stmt_execute($stmt);
    			        header("Location: ../../html/newsFeed-new.html?new-user=success");

    	         }
              }
            }
          }
        }
      }
    }
else{
	header("Location: new-user.php?new-user=error");
	exit();
}

