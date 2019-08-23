<?php
 echo "Hi";
if(isset($_POST['submit'])){

 include_once 'main-db-connection.php';

  $uid=mysqli_real_escape_string($conn, $_POST['username']);
  $changePassword= mysqli_real_escape_string($conn, $_POST['change_password']);
  $first= mysqli_real_escape_string($conn, $_POST['first_name']);
  $last= mysqli_real_escape_string($conn, $_POST['last_name']);
  $email= mysqli_real_escape_string($conn, $_POST['email']);
  $country= mysqli_real_escape_string($conn, $_POST['country']);
  $countryCode= mysqli_real_escape_string($conn, $_POST['country_code']);
  
   	 $sql="SELECT * from user WHERE username=?";
     $stmt = mysqli_stmt_init($conn);
       if(!mysqli_stmt_prepare($stmt, $sql)){
    			echo "SQL error!";
        } else {
     	    $data = Array();
    		mysqli_stmt_bind_param($stmt, "s", $uid);
    		mysqli_stmt_execute($stmt);
    		$result=mysqli_stmt_get_result($stmt);
    		while($row=mysqli_fetch_assoc($result)){
    			$data[] = $row;
    		}
            if(0==count($data)){
    		    header("Location: settings.php?settings=userdoesnotexist");
    		    exit();
            }else{
                if(!empty($changePassword)){
                $hashedPwd=password_hash($changePassword, PASSWORD_DEFAULT);
  	            $sql= "UPDATE user SET password=? WHERE username=?";
    		           $stmt = mysqli_stmt_init($conn);
    		           if(!mysqli_stmt_prepare($stmt, $sql)){
    			           echo "SQL error!";
    		            } else{
    			            mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $uid);
    			            mysqli_stmt_execute($stmt);
    			            header("Location: settings.php?settings=changesuccess");
    			        }
                      }
               if(!empty($first)){
               	  if(!preg_match("/^[a-zA-Z]*$/", $first)){
                     header("Location: settings.php?settings=invalidFirst");
	                 exit();
                  }else{
  	                   $sql= "UPDATE user SET first_name=? WHERE username=?";
    		           $stmt = mysqli_stmt_init($conn);
    		           if(!mysqli_stmt_prepare($stmt, $sql)){
    			           echo "SQL error!";
    		            } else{
    			              mysqli_stmt_bind_param($stmt, "ss", $first, $uid);
    			              mysqli_stmt_execute($stmt);
    			              header("Location: settings.php?settings=changesuccess");
    			           }
                         }
                     }
                if(!empty($last)){
                	if(!preg_match("/^[a-zA-Z]*$/", $last)){
                     header("Location: settings.php?settings=invalidLast");
	                 exit();
                  }else{
  	                   $sql= "UPDATE user SET last_name=? WHERE username=?";
    		           $stmt = mysqli_stmt_init($conn);
    		           if(!mysqli_stmt_prepare($stmt, $sql)){
    			       echo "SQL error!";
    		            } else{
    			             mysqli_stmt_bind_param($stmt, "ss", $last, $uid);
    			             mysqli_stmt_execute($stmt);
    			             header("Location: settings.php?settings=changesuccess");
    			           }
    			         }
                       }
               if(!empty($email)){
               	 if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                     header("Location: settings.php?settings=email");
	                 exit();
    	           }else{
  	                  $sql= "UPDATE user SET email=? WHERE username=?";
    		          $stmt = mysqli_stmt_init($conn);
    		            if(!mysqli_stmt_prepare($stmt, $sql)){
    		            	echo "SQL error!";
    		              } else{
    			             mysqli_stmt_bind_param($stmt, "ss", $email, $uid);
    			             mysqli_stmt_execute($stmt);
    			             header("Location: settings.php?settings=changesuccess");
    			           }
                         }
                      }
              if(!empty($country)){
  	          $sql= "UPDATE user SET country=? WHERE username=?";
    		       $stmt = mysqli_stmt_init($conn);
    		       if(!mysqli_stmt_prepare($stmt, $sql)){
    			       echo "SQL error!";
    		       } else{
    			        mysqli_stmt_bind_param($stmt, "ss", $country, $uid);
    			        mysqli_stmt_execute($stmt);
    			        header("Location: settings.php?settings=changesuccess");
    			    }
                  }
            if(!empty($countryCode)){
  	         $sql= "UPDATE user SET country_code=? WHERE username=?";
    		        $stmt = mysqli_stmt_init($conn);
    		       if(!mysqli_stmt_prepare($stmt, $sql)){
    			       echo "SQL error!";
    		       } else{
    			        mysqli_stmt_bind_param($stmt, "ss", $countryCode, $uid);
    			        mysqli_stmt_execute($stmt);
    			        header("Location: settings.php?settings=changesuccess");
    			    }
                  }
                }              
              }
           } else{
	        header("Location: settings.php?settings=error");
	        exit();
          }