<?php 

$connections = array();

//Used to connect to the wandika database.
//Based on code provided by Dr. Alan Hunt
function connect(){
    $servername = "webdev.cse.buffalo.edu";
    $username = "wandika_user";
    $password = "wandika19!";
    $dbname = "wandika";
    global $connections;

    error_log("Connect to ".$dbname." as user ".$username, 0);

    $conn = null;

    //Create the connection
	try{
        $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        array_push($connections, $conn);
        return $conn;
    }catch(Exception $e){
        echo "connection error ".$servername;
        error_log("Error Connecting to ".$dbname." as user ".$username, 0);
    }   
}

function connect_test(){
    $servername = "webdev.cse.buffalo.edu";
    $username = "wandika_user";
    $password = "wandika19!";
    $dbname = "wandika";
    global $connections;

    error_log("Connect to ".$dbname." as user ".$username, 0);

    $conn = null;

    //Create the connection
	try{
        $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        array_push($connections, $conn);
        return $conn;
    }catch(Exception $e){
        echo "connection error ".$servername;
        error_log("Error Connecting to ".$dbname." as user ".$username, 0);
    }   
}

//used to close the connection
//Based on code provided by Dr. Alan Hunt
function close_connection(){
    global $connections;
    foreach($connections as $conn){
        $conn = null;
    }
}
 ?>