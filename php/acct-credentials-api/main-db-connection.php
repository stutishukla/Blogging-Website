<?php
/*when you have a pure PHP file, you don't need to close the tag of PHP. Preferred practice.*/

$dbServername= "webdev.cse.buffalo.edu";
$dbUsername= "wandika_user";
$dbPassword= "wandika19!"; //leaving it empty cuz XAMPP does not have a password by default.
$dbName= "wandika";

$conn=mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);