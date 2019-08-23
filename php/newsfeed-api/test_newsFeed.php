<?php
    require 'database.php';
    require 'validity.php';
   
    // the response will be a JSON object
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $json = array();

    $conn = connect_test();
        
        //prepare statement
        // $sql = "INSERT INTO posts_table (content, date_posted, post_type)
        // VALUES(:content, :date, :post_type)";
        // $conn->prepare($sql);
        // $conn->bindParam(':content', $written_post); //from text box
        // $conn->bindParam(':date_posted', $date); //dont worry about this. From php date function
        // $conn->bindParam(':post_type', $type_of_post);
        //collect data

        $written_post = $_POST['blog-post'];
        
        //insert date into date field using php DATE
        $date = date('l jS \of F Y h:i:s A');

        $type_of_post = "Blog";
        $mysql = "INSERT INTO `post_table`(post_id, content, date_posted, post_type) VALUES (5, '$written_post', '$date', '$type_of_post')";      
        $conn->query($mysql);
        $lastID = $conn->lastInsertId();
        if($lastID > 0){
            $json['ID'] = $lastID;
        }
        else{
            $json['ID'] = 189;
        }
        echo $json['ID'];
        echo "New post created successfully.";
    
        close_connection();
        return json_encode($json);