<?php
    if (!isset($_SESSION)) 
    {
     session_start();
    }

    require 'database.php';
    require 'validity.php';
    date_default_timezone_set('America/New_York');
    // the response will be a JSON object
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // pull the input, which should be in the form of a JSON object

    //echo json_encode($_POST);
    $json_params = file_get_contents('php://input');
    $decoded_params = json_decode($json_params, TRUE);
    $conn = null;
    $post_id = null;   
    $content = null;
    $num = null;
    if(is_array($decoded_params) && array_key_exists('post_id', $decoded_params)){
        $post_id = $decoded_params['post_id'];
    }
    if(is_array($decoded_params) && array_key_exists('content', $decoded_params)){
        $post_id = $decoded_params['content'];
    }
    //$_SESSION['user_id'] = 99999;
    if(isset($_POST['action']) && !empty($_POST['action'])) {
        $action = $_POST['action'];
    if(isset($_POST['postid']) && !empty($_POST['postid'])) {
       $post_id = $_POST['postid'];
    }
    // if(isset($_POST['tag']) && !empty($_POST['tag'])) {
    //    $tag = $_POST['tag'];
    // }
   $user_id=$_SESSION['u_id'];
            switch($action) {
                case 'on-load': on_load_posts(); break;
                case 'blog-post': set_blog_post(); break;
                case 'get-blog-post': get_blog_post(); break;
                case 'delete-post': delete_post($post_id); break;
                case 'load-posts': get_last_three_posts(); break;
                case 'load-x-posts': get_last_x_posts($num); break;
                case 'get-title': get_title($post_id); break;
                case 'get-genre': get_genre($post_id); break;
                case 'get-date': get_date_posted($post_id); break;
                case 'get-time-to-read': get_time_read($post_id); break;
                case 'get-post-image-name': get_post_image_name($post_id); break;
                case 'set-tag': set_tag($post_id, $user_id); break;
                case 'get-tags': get_tags($post_id); break;
                case 'upload': upload_picture(); break;
                case 'save-blog': save_blog_post($user_id, $post_id); break;
                case 'get-allow-comments': get_allow_comments($post_id); break;
                case 'change-comment': change_allow_comments($post_id); break;
                case 'search': search_user(); break;
                case 'random': post_randomizer(); break;
                case 'time-ago': time_ago($post_id); break;
                case 'set-comment': set_comment($user_id, $post_id); break;

                default: echo "No such function";
            }
        
    }
            
     
   
    /**
     * Function that accepts a written post from an html form and associates it with the specified user_id.
     * The function will take the necessary parts from the post fields and input them into the sql database. Uses PDO and
     * prepared statements.
     * @Param: $user_id: the user_id associated with the post. This is a unique key.
     *         
     */
    function set_blog_post(){
        //connect to database
        $conn = connect();
        
        //prepare statement
        $sql = "INSERT INTO `posts_table` (user_id, title, content, genre, date_posted, time_posted, time_read, post_image, post_type, allow_comments)
        VALUES(:user_id, :title, :content, :genre, :date_posted, :time_posted, :time_read, :post_image, :post_type, :allow_comments)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id); //From session ID
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $written_post); //from text box
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':date_posted', $date); //dont worry about this. From php date function
        $stmt->bindParam(':time_posted', $time);
        $stmt->bindParam(':time_read', $time_to_read);
        $stmt->bindParam(':post_image', $post_image_title);
        $stmt->bindParam(':post_type', $type_of_post);
        $stmt->bindParam(':allow_comments', $allow_comments);
        
        //collect data
        $user_id = $_SESSION['u_id'];
        $written_post = $_POST['blog-post'];
        $title = $_POST['blog-title'];
        $genre = $_POST['genre'];
        
        //insert date into date field using php DATE
        $date = date('l jS \of F Y h:i:s A');
        //$time = date('U');
        $time = "1 second ago";
        $commentVal= $_POST['comments'];
        if(isset($_FILES['file-to-upload'])){
            $success = upload_picture();
            if($success != 0){
                $post_image_title = basename($_FILES['file-to-upload']['name']);
            }
            else{
            $post_image_title = null;
            }
        }
        //retrieve the estimated time to read from time_to_read form and insert into time_to_read column.
        //javascript needs to return a string.
        $time_to_read = $_POST['timeToRead'];
        $type_of_post = "Blog";
        
        //$allow_comments = ((isset($_POST['comments']) && $comments=='Allow') ? true : false);
        if ($commentVal==="true")
          {
            $allow_comments = true;
          } else {
             $allow_comments = false;
           }

        //$allow_comments=$_POST['comments'];
       // $allow_comments = true;
        //insert into table posts            
        $stmt->execute();
        $last_id = $conn->lastInsertId();
        get_last_posts_after_setting($conn);
        close_connection();
    }
    function set_blog_post_test(){
        //connect to database
        $conn = connect();
        
        //prepare statement
        $sql = "INSERT INTO posts_table (content, date_posted, post_type)
        VALUES(:content, :date_posted, :post_type)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':content', $written_post); //from text box
        $stmt->bindParam(':date_posted', $date); //dont worry about this. From php date function
        $stmt->bindParam(':post_type', $type_of_post);
        //collect data
        $written_post = $_POST['blog-post'];
      
        //insert date into date field using php DATE
        $date = date('l jS \of F Y h:i:s A');
        $type_of_post = "Blog";   
        $stmt->execute();
        //echo "New post created successfully.\n";
        $last_id = $conn->lastInsertId();
        //pull last 3 posts and return as an array
        $last_post = get_last_post_test($last_id, $conn);
        close_connection();
        echo json_encode($last_post);
    }
    function get_last_post_test($last_id, $conn){
        //$conn = connect_test();
        $sql = "SELECT content FROM posts_table WHERE post_id = $last_id";
        $last_post_content = $conn->query($sql);
        $ret_content = $last_post_content->fetch();
        return $ret_content;
    }
    //use on load to get the most current three posts from the database
    function on_load_posts(){
        $conn = connect();
        $posts_count = $conn->prepare("SELECT COUNT(*) FROM posts_table");
        $posts_count->execute();
        $posts_total = $posts_count->fetch();
        if($posts_total[0] == 0){
            return null;
        }
        if($posts_total[0] >= 1 && $posts_total[0] <= 3){
            $sql = "SELECT * FROM posts_table" ;
            $last_posts = $conn->query($sql);
            $ret_content = $last_posts->fetchAll();
            $sql = "SELECT post_id FROM posts_table BETWEEN 1 AND MAX(post_id)";
            $post_ids = $conn->query($sql);
            $post_ids = $post_ids->fetchAll();
            close_connection();
            echo json_encode($ret_content);
            //echo json_encode(time_ago($post_ids));
            //echo json_encode(get_tags($post_ids));
        }else{
            get_last_three_posts($conn);
        }    
    }
    //retrieves posts. Will retrieve 3 if 3 or more exists in the table. Used within the set_blog_post() function
    //returns json object
    function get_last_posts_after_setting($conn){
        $posts_count = $conn->query("SELECT COUNT(*) FROM posts_table");
        $posts_count = $posts_count->fetch();
        if($posts_count[0] == 0){
            return null;
        }
        $min_posts_in_database = 1;
        $amount_of_posts_before_pulling_more_than_2 = 3;
        if($posts_count >= $min_posts_in_database && $posts_count <= $amount_of_posts_before_pulling_more_than_2){
            $sql = "SELECT * FROM posts_table";
            $last_posts = $conn->query($sql);
            $ret_content = $last_posts->fetchAll();
            close_connection();
            echo json_encode($ret_content);
        }else{
            get_last_three_posts($conn);
        }    
    }
    
    //pulls last three posts from server. used in get_last_posts_after_setting(). Return json object.
    function get_last_three_posts($conn){
        $number_of_additional_posts = 2;
        $find_max_sql = "SELECT MAX(post_id) FROM posts_table";
        $max = $conn->query($find_max_sql);
        $max_id = $max->fetch();
        $start_of_post_range = $max_id[0] - $number_of_additional_posts;
        $sql = "SELECT * FROM posts_table WHERE post_id BETWEEN $start_of_post_range AND $max_id[0]";
        $last_three_posts = $conn->query($sql);
        //need to get IDS and put in array
        //need to get IDS and put in array
        $post_ids = array();
        for($i=$start_of_post_range; $i<=$max_id[0]; ++$i){
            array_push($post_ids, $i);
        }
        
        
        $sql = "SELECT post_id FROM posts_table WHERE post_id BETWEEN $start_of_post_range AND $max_id[0]";
        
        $ret_content = $last_three_posts->fetchAll();
        close_connection();
        echo json_encode($ret_content);
        //get_tags($post_ids);
       // time_ago($post_ids);
    }
    //pulls most recent $num posts from server.
    function get_last_x_posts($num){
        $conn = connect();
        $number_of_additional_posts = $num;
        $find_max_sql = "SELECT MAX(post_id) FROM posts_table";
        $max = $conn->query($find_max_sql);
        $max_id = $max->fetch();
        $start_of_post_range = $max_id[0] - $number_of_additional_posts;
        $sql = "SELECT * FROM posts_table WHERE post_id BETWEEN $start_of_post_range AND $max_id[0]";
        $last_three_posts = $conn->query($sql);
        $ret_content = $last_three_posts->fetchAll();
        close_connection();
        echo json_encode($ret_content);
    }
    //saves a blogpost to the saved_items table.
    function save_blog_post($user_id, $post_id){
        $conn = connect();
        $sql = "INSERT INTO saved_items (post_id, user_id)
        VALUES($post_id, $user_id)";
        $conn->query($sql);
        close_connection();
    }
    
   
    
    function get_title($post_id){
        $conn = connect();
        $sql = "SELECT title FROM posts_table WHERE post_id=$post_id";
        $title = $conn->query($sql);
        close_connection();
        echo json_encode($title->fetch()[0]);
    }
    function get_genre($post_id){
        $conn = connect();
        $sql = "SELECT genre FROM `posts_table` WHERE post_id=$post_id";
        $genre = $conn->query($sql);
        close_connection();
        echo json_encode($genre->fetch()[0]);
    }
    function get_date_posted($post_id){
        $conn = connect();
        $sql = "SELECT date_posted FROM `posts_table` WHERE post_id=$post_id";
        $date_posted = $conn->query($sql);
        close_connection();
        echo json_encode($date_posted->fetch()[0]);
    }
    function get_time_posted($post_id){
        $conn = connect();
        $sql = "SELECT time_posted FROM `posts_table` WHERE post_id=$post_id";
        $time_posted = $conn->query($sql);
        close_connection();
        echo json_encode($time_posted->fetch()[0]);
    }
    function get_time_read($post_id){
        $conn = connect();
        $sql = "SELECT time_read FROM `posts_table` WHERE post_id=$post_id";
        $time_read = $conn->query($sql);
        close_connection();
        echo json_encode($time_read->fetch()[0]);
    }
    function get_post_image_name($post_id){
        $conn = connect();
        $sql = "SELECT post_image FROM `posts_table` WHERE post_id=$post_id";
        $post_image = $conn->query($sql);
        $html_path = "../uploads/". $post_image->fetch()[0];
        close_connection();
        echo json_encode($html_path);
    }
    /*
    *Function that retrieves a blog post associated with a user_id and post_id.
    *Returns a .json.
    */
    
    function get_blog_post($post_id){
        $conn = connect();
        $sql = "SELECT * FROM `posts_table` WHERE post_id=$post_id";
        $user_blog = $conn->query($sql);
        close_connection();
        echo json_encode($user_blog->fetch());
    }
    function get_blog_post_test(){
        //match user name
        $conn = connect();
        $sql = "SELECT content FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $user_blog = $conn->query($sql);
        //pull last three. Look up filtering. Can filter out last three.
        close_connection();
        return json_encode($user_blog);
    }
    function get_allow_comments($post_id){
        $conn = connect();
        $sql = "SELECT allow_comments FROM `posts_table` WHERE post_id=$post_id";
        $allowcomments = $conn->query($sql);
        close_connection();
        echo json_encode($allowcomments->fetch()[0]);
    }
    function change_allow_comments($post_id){
        $conn = connect();
        $allowed_comments = "SELECT allow_comments FROM posts_table WHERE post_id=$post_id";
        $allowed_comments = $conn->query($allowed_comments)->fetch()[0];
        echo $allowed_comments;
        if($allowed_comments==1){
            $sql = "UPDATE posts_table  SET allow_comments = 0 WHERE post_id = $post_id";
        }else{
            $sql = "UPDATE posts_table SET allow_comments = 1 WHERE post_id = $post_id";
        }
        $conn->query($sql);
        //echo "\n Now comments are: " .$allowed_comments=$conn->query("SELECT allow_comments FROM posts_table WHERE post_id=$post_id")->fetch()[0];
        close_connection();
    }
    function delete_post($post_id){
        $conn = connect();
        $sql = "DELETE FROM `posts` WHERE `post_id` = $post_id";
        $conn->exec($sql);
        close_connection();
    }
    
    function get_tag_count($post_id){
        $conn = connect();
        $sql = "SELECT `count` FROM tags WHERE post_id = $post_id";
        $count = $conn->query($sql);
        close_connection();
        echo json_encode($count->fetch()[0]);
    }
    function set_tag($post_id, $user_id){
        $conn = connect();
        $tag = $_POST['tag'];
        //echo $tag;
        // $sql = $conn->prepare("SELECT * FROM tags WHERE post_id = '$post_id' AND content = '$tag'");
        // $sql->execute();
        // $fetch = $sql->fetch(PDO::FETCH_ASSOC);
        $sql = "SELECT * FROM tags WHERE post_id = '$post_id' AND content = '$tag'";
        $result=$conn->query($sql);
        if($result->fetchColumn() > 0){
            $update = "UPDATE tags SET count = count + 1 WHERE post_id = '$post_id' AND content = '$tag'";
            $conn->query($update);
        }else{
            $insert ="INSERT INTO tags (count, content, post_id, user_id) VALUES(1, '$tag', '$post_id', '$user_id')";
            $conn->query($insert);
        }
        echo json_encode($_POST);      
        close_connection();
    }
    function get_tags($post_id){ //requires array
        $conn = connect();
        foreach($post_id as $value){
            $sql = "SELECT * FROM tags WHERE post_id = $post_id";
            $tags = $conn->query($sql);
            echo json_encode($tags->fetchAll());
        }
        close_connection();
    }
    function set_comment($user_id, $post_id){
        //open connection
        $conn = connect();
        $feedback_content = $_POST['feedback'];
        //prepared statement
        $sql = "INSERT INTO feedback (user_id, content, post_id)
        VALUES(:user_id, :content, :post_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $feedback_content);
        $stmt->bindParam(':post_id', $post_id);
        //fill in variables
       // $feedback_content = $_POST['feedback'];
        //execute statement
        $stmt->execute();
        close_connection();
    }
    /*
     * Function that returns a bool if a comment should be flagged for moderation.
     */
    function flag_comment($feedback_id){ 
        $conn = connect();
        $sql = "SELECT flag_count FROM feedback WHERE `feedback_id` = $feedback_id";
        $flags = $conn->query($sql);
        $sql = "SELECT views FROM feedback WHERE `feedback_id` = $feedback_id";
        $views = $conn->query($sql);
        $disable = ($views/$flags >= 0.5 ? true : false);
        close_connection();
        return $disable;
    }
    /**
     * Function to increase the flag_count on a comment.
     */
    function increase_flag_count($feedback_id){
        $conn = connect();
        $sql = "UPDATE feedback SET flag_count = flag_count + 1 WHERE feedback_id = $feedback_id";
        $conn->exec($sql);
        close_connection();
    }
    function search_user(){
        $conn = connect();
        $search = "SELECT * FROM user WHERE username LIKE :query";
        //$stmt->bindParam(`:user_id`, $user_id);
        $query = $_POST['search'];
        $search_result = $conn->prepare($search);
        $search_result->bindParam(':query', '%$query%');
        $conn->execute();
        $results = $conn->fetchAll();
        echo json_encode($results, JSON_FORCE_OBJECT);        
    }
    /**
     * Function that randomizes a post to be returned as a .json object.
     * This returns the content as a .json object.
     */
    function post_randomizer(){
        $conn = connect();
        $sql = "SELECT COUNT(*) FROM posts_table WHERE post_type = 'Blog'";
        $count = $conn->query($sql);
        $max = $count->fetch();
        $random_num = random_int(1, $max[0]);
        $sql = "SELECT * FROM posts_table WHERE post_id = $random_num";
        $blog = $conn->query($sql); //grabs all content from result
        $random_blog = $blog->fetch();
        close_connection();
        echo json_encode($random_blog);
    }
    function upload_picture(){
        $target_dir = "wandika/uploads/";
        $target_file = $target_dir . basename($_FILES["file-to-upload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["upload"])) {
            $check = getimagesize($_FILES["file-to-upload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }   
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        $file_size_max = 5000000;
        if ($_FILES["fileToUpload"]["size"] > $file_size_max) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                $response = "Sorry, there was an error uploading your file.";
                echo $response;
                $error = 0;
                return $error;
            }
        }
    }
    function time_ago ($post_id) {
        $conn = connect();
        foreach($post_id as $value){
            $sql = "SELECT time_posted FROM posts_table WHERE post_id='$post_id'";
            $database_time = $conn->query($sql);
            $oldTime = $database_time->fetch();
            $compareTime = $oldTime[0];
            $currentTime = date('U');
            $timeCalc = $currentTime - $compareTime;
            if ($timeCalc >= (60*60*24*30*12*2)){
                $timeCalc = intval($timeCalc/60/60/24/30/12) . " years ago";
            }else if ($timeCalc >= (60*60*24*30*12)){
                $timeCalc = intval($timeCalc/60/60/24/30/12) . " year ago";
            }else if ($timeCalc >= (60*60*24*30*2)){
                $timeCalc = intval($timeCalc/60/60/24/30) . " months ago";
            }else if ($timeCalc >= (60*60*24*30)){
                $timeCalc = intval($timeCalc/60/60/24/30) . " month ago";
            }else if ($timeCalc >= (60*60*24*2)){
                $timeCalc = intval($timeCalc/60/60/24) . " days ago";
            }else if ($timeCalc >= (60*60*24)){
                $timeCalc = " Yesterday";
            }else if ($timeCalc >= (60*60*2)){
                $timeCalc = intval($timeCalc/60/60) . " hours ago";
            }else if ($timeCalc >= (60*60)){
                $timeCalc = intval($timeCalc/60/60) . " hour ago";
            }else if ($timeCalc >= 60*2){
                $timeCalc = intval($timeCalc/60) . " minutes ago";
            }else if ($timeCalc >= 60){
                $timeCalc = intval($timeCalc/60) . " minute ago";
            }else if ($timeCalc > 0){
                $timeCalc .= " seconds ago";
            }
            $time_ago = array();
            array_push($time_ago, $timeCalc);
        }
        
        close_connection();
        echo json_encode($time_ago);
        } 
      /* function set_tip($user_id){
        //open connection
        $conn = connect();
        //prepared statement
        $sql = "INSERT INTO `posts_table` (user_id, content, genre, date_posted, post_type)
        VALUES(:user_id, :content, :genre, :date_posted, :post_type)";
        $conn->prepare($sql);
        $conn->bindParam(`:user_id`, $user_id);
        $conn->bindParam(`:content`, $tip_content);
        $conn->bindParam(`:genre`, $genre);
        $conn->bindParam(`:date_posted`, $date_posted);
        $conn->bindParam(`:post_type`, $post_type);
        //fill in variables
        $tip_content = $_POST['content'];
        $genre = $_POST['genre'];
        $date_posted = date('l jS \of F Y h:i:s A');
        $post_type = "Tip";
        
        //execute prepared statement
        $conn->execute();
        close_connection();
    } */
    /* function set_tip_test(){
        //open connection
        $conn = connect();
        //prepared statement
        $sql = "INSERT INTO `posts_table` (content, date_posted, post_type)
        VALUES(:content, :date_posted, :post_type)";
        $conn->prepare($sql);     
        $conn->bindParam(`:content`, $tip_content);
        $conn->bindParam(`:date_posted`, $date_posted);
        $conn->bindParam(`:post_type`, $post_type);
        //fill in variables
        $tip_content = $_POST['content'];
        $date_posted = date('l jS \of F Y h:i:s A');
        $post_type = "Tip";       
        //execute prepared statement
        $conn->execute();
        close_connection();
    } */
  /*   function delete_tip($user_id, $tip_id){
        $conn = connect();
        $postdel = "DELETE FROM ``posts_table`` WHERE `tips_id` = $tip_id AND `user_id` = $user_id";
        $conn->exec($postdel);
        close_connection();
    } */
       /* function increase_recommend_comment($feedback_id){
        $conn = connect();
        $sql = "UPDATE feedback SET recommend_count = recommend_count + 1 WHERE feedback_id = $feedback_id";
        close_connection();
    } */
    /* function promote_comment($feedback_id){
        $conn = connect();
        $recommendations = "SELECT FROM feedback `recommendations` WHERE `feedback_id` = $feedback_id";
        $views = "SELECT FROM feedback `views` WHERE `feedback_id` = $feedback_id";
        $promote = ($views/$flags >= 0.5 ? true : false);
        if($promote){
            $user_id = "SELECT user_id FROM feedback WHERE `feedback_id` = $feedback_id";
            $feedback_content = "SELECT content FROM feedback WHERE `feedback_id` = $feedback_id";
            $date = date('l jS \of F Y h:i:s A');
            $sql = "INSERT INTO `posts_table` (content, post_type, user_id, date_posted) VALUES ($feedback_content, "Tips", $user_id, $date"); 
        }
        close_connection();
        
    } */
?>