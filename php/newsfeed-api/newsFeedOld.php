<?php
    require 'database.php';
    require 'validity.php';
    

   
    // the response will be a JSON object
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // pull the input, which should be in the form of a JSON object
    //$json_params = file_get_contents('php://input');
    //$conn = null;
    // if(!isset($_POST['functionname'])){
    //      $aResult['error'] = 'No function name!'; 
    //     }

    // if(!isset($_POST['arguments'])){ 
    //     $aResult['error'] = 'No function arguments!';
    // }
    //$decoded_params = json_decode($json_params, TRUE);
    //$action = $decoded_params['action'];
   // $json['action'] = $action;
    // if( !isset($aResult['error']) ) {
        //$function_name = 'blog-post';
   
    //echo json_encode($_POST);
    

    

        if(isset($_POST['action']) /*&& !empty($_POST['action'])*/) {
            $action = $_POST['action'];
                switch($action) {
                    case 'blog-post': set_blog_post_test(); break;
                    case 'tip-post': echo "set tag post"; set_tip_test(); break;
                    default: echo "No such function";
                }
        
        }
            
     

    //}

    //echo json_encode($aResult);
    /**
     * Function that accepts a written post from an html form and associates it with the specified user_id.
     * The function will take the necessary parts from the post fields and input them into the sql database. Uses PDO and
     * prepared statements.
     * @Param: $user_id: the user_id associated with the post. This is a unique key.
     *         
     */
    function set_blog_post($user_id){
        //connect to database
        $conn = connect();
        
        //prepare statement
        $sql = "INSERT INTO `posts_table` (user_id, title, content, genre, date_posted, time_read, post_image, post_type, allow_comments)
        VALUES(:user_id, :title, :post, :genre, :date_posted, :time_read, :post_image, :post_type, :allow_comments)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id); //From session ID
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $written_post); //from text box
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':date_posted', $date); //dont worry about this. From php date function
        $stmt->bindParam(':time_read', $time_to_read);
        $stmt->bindParam(':post_image', $post_image);
        $stmt->bindParam(':post_type', $type_of_post);
        $stmt->bindParam(':allow_comments', $allow_comments);
        
        //collect data
        $user = $conn->query("SELECT user_id FROM user WHERE user_id==$user_id");
        $written_post = $_POST['blog-post'];
        $blog_title = $_POST['News Feed'];
        $genre = $_POST['genre'];
        
        //insert date into date field using php DATE
        $date = date('l jS \of F Y h:i:s A');
        
        //retrieve the estimated time to read from time_to_read form and insert into time_to_read column.
        //javascript needs to return a string.
        $time_to_read = $_POST['timeToRead'];

        $type_of_post = "Blog";
        
        $allow_comments = ((isset($comments) && $comments=='Allow') ? true : false);
        //insert into table posts            
        $stmt->execute();
        $last_id = $conn->lastInsertId();
        echo "New post created successfully. Last inserted ID is: " . $last_id;
        close_connection();
        return json_encode($last_id);
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
        $last_post = get_last_post($last_id, $conn);
        //echo "The last post was". json_encode($last_post);
        close_connection();
        echo json_encode($last_post);
    }

    function get_last_post($last_id, $conn){
        //$conn = connect_test();
        $sql = "SELECT content FROM posts_table WHERE post_id = $last_id";
        $last_post_content = $conn->query($sql);
        $ret_content = $last_post_content->fetch();
        return $ret_content;
    }

    // function save_blog_post($user_id){
    //     $conn = connect();
    //     $sql = "INSERT INTO saved_items (saved_items_id, blogs_id, user_id, content, genre, date_posted, post_type)
    //     VALUES(:user_id, :content, :genre, :date_posted, :post_type)";
    // }
    function set_image($user_id){
        $conn = connect();
        $sql = "INSERT INTO post_table (user_id, post_image) VALUES(:user_id, :post_image)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam();
    }
    
    function get_title($user_id, $post_id){
        $conn = connect();
        $sql = "SELECT title FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $title = $conn->query($sql);
        close_connection();
        return json_encode($title);
    }

    function get_genre($user_id, $post_id){
        $conn = connect();
        $sql = "SELECT genre FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $genre = $conn->query($sql);
        close_connection();
        return json_encode($genre);
    }

    function get_date_posted($user_id, $post_id){
        $conn = connect();
        $sql = "SELECT date_posted FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $date_posted = $conn->query($sql);
        close_connection();
        return json_encode($date_posted);
    }

    function get_time_posted($user_id, $post_id){
        $conn = connect();
        $sql = "SELECT time_posted FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $time_posted = $conn->query($sql);
        close_connection();
        return json_encode($time_posted);
    }

    function get_time_read($user_id, $post_id){
        $conn = connect();
        $sql = "SELECT time_read FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $time_read = $conn->query($sql);
        close_connection();
        return json_encode($time_read);
    }

    function get_post_image($user_id, $post_id){
        $conn = connect();
        $sql = "SELECT post_image FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $post_image = $conn->query($sql);
        close_connection();
        return json_encode($post_image);
    }
    /*
    *Function that retrieves a blog post associated with a user_id and post_id.
    *Returns a .json.
    */
    function get_blog_post($user_id, $post_id){
        //match user name
        $conn = connect();
        $sql = "SELECT content FROM `posts_table` WHERE user_id=$user_id AND post_id=$post_id";
        $user_blog = $conn->query($sql);
        close_connection();
        return json_encode($user_blog);
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

    function allow_comments($post_id){
        $conn = connect();
        $sql = "SELECT allow_comments FROM `posts_table` WHERE post_id=$post_id";
        $allowcomments = $conn->query($sql);
        close_connection();
        return json_encode($allowcomments);
    }

    function delete_post($user_id, $post_id){
        $conn = connect();
        $sql = "DELETE FROM `posts` WHERE `post_id` = $post_id AND `user_id` = $user_id";
        $conn->exec($sql);
        close_connection();
    }

    function set_tip($user_id){
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
    }

    function set_tip_test(){
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
    }


    function delete_tip($user_id, $tip_id){
        $conn = connect();
        $postdel = "DELETE FROM ``posts_table`` WHERE `tips_id` = $tip_id AND `user_id` = $user_id";
        $conn->exec($postdel);
        close_connection();
    }

    /* function set_tag($content_id){
        $conn = connect();
        $tag = $_POST['tag']
        switch($tag){
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;
            case "":
                $sql = "UPDATE tags SET count = count + 1 WHERE content = ''";
                break;    
                
        }
        $tag_sql = "SELECT * from tags WHERE post_id = $content_id";
        $return_tags = $conn->query($tag_sql);
        close_connection();
        return json_encode($return_tags);
    } */
    
    function get_tag_count($content_id){
        $conn = connect();
        $sql = "SELECT `count` FROM tags WHERE `post_id` = $content_id";
        return $sql;
    }

    function set_comment($user_id, $post_id){
        //open connection
        $conn = connect();
        //prepared statement
        $sql = "INSERT INTO feedback (user_id, content, post_id)
        VALUES(:user_id, :content, :genre, :date_posted, :post_type)";
        $conn->prepare($sql);
        $stmt->bindParam(`:user_id`, $user_id);
        $stmt->bindParam(`:content`, $feedback_content);
        $stmt->bindParam(`:post_id`, $post_id);
        //fill in variables
        $feedback_content = $_POST['feedback'];
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

    function search_user($query){
        $conn = connect();
        $search = "SELECT * FROM user WHERE username LIKE :query";
        
        //$stmt->bindParam(`:user_id`, $user_id);
        
        $search_result = $conn->prepare($search);
        $search_result->bindParam(':query', $query);
        $conn->execute();
        $results = $conn->fetchAll();
        return json_encode($results, JSON_FORCE_OBJECT);

        
    }

    /**
     * Function that randomizes a post to be returned as a .json object.
     * This returns the content as a .json object.
     */
    function post_randomizer(){
        $conn = connect();
        $sql = "SELECT * FROM post_table WHERE post_type = 'Blog'";
        $conn->query($sql);
        $blogs = $conn->fetchAll(PDO::FETCH_COLUMN, 4); //grabs all content from result
        close_connection();
        shuffle($blogs);
        $count = count($blogs);
        $random_num = random_int(0, $count-1);
        $blog_to_return = $blogs[$random_num];
        return json_encode($blog_to_return);
    }
    /*$_POST["blog-post"]="stuti";
    print_r($_POST);
    set_blog_post_test();*/

?>
