<?php
    
    /**
         **Get All Functions v2.0
        **Function To Get All records from any table of DB .
    */
    function getAllFrom($field , $table , $where = NULL, $and=NULL , $orderfield , $ordering = 'DESC'){
    
        global $con;
        // $sql = $where == NULL ? '' : $where ;

        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering ");
        $getAll->execute();
        $all=$getAll->fetchAll();
        return $all;

    }

    /** Title Function v0.1
     ** Title Function that echo the page title  in case the page has
    **  the variable $pageTitle and echo Default Title for other pages
    **/
    
    function getTitle(){

        global $pageTitle;

        if(isset($pageTitle))
        {
            echo $pageTitle;

        }else{

            echo 'Default';
        }
    };
    /**
     ** Home Redirect Functions v0.2
     ** [This Function Accept Parameters]
     ** $theMsg = Echo The MSG
     ** $seconds  = seconds before redirecting
     */
    function redirectHome($theMsg, $url=null , $seconds = 3){

        if($url===null){

            $url ='dashboard.php';
            $link='Page d\'accueil';
        }else{
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){
                $url =$_SERVER['HTTP_REFERER'];
                $link='Page Précédente';
            }else{
                $url = 'dashboard.php';
                $link ='Page d\'accueil';
            }
        }

        echo $theMsg;
        echo "<div class='alert alert-info'> Vous serez redirigé vers $link dans $seconds seconds  . </div>";
        header("refresh: $seconds ; url=$url");
        exit();

    }
    /**
     * Ckeck Items function v0.1
     * Function to check item in database [Function Accept parameters]
     * $select =The Item to select Example [user,item,category]
     * $from   =The table to select from Example [users,items,categories]
     * $value  =The value of Select Example [Osama,Box,Electronics]
     * 
     */

     function checkItem($select,$from,$value){
        global $con;
        $statement =$con->prepare("SELECT $select FROM $from WHERE $select= ?  ");
        $statement->execute(array($value));
        $count =$statement->rowCount();
        return $count;
     }


     /** Count Number Of Items Function V1.0 
      ** Function To Count Number Of Items Rows
      ** $item =the item to count
      ** $table=the table to choose from
     */

     function countItems($item , $table){

        global $con;
        $stmt2=$con->prepare("SELECT count($item) From $table");
        $stmt2->execute();
        return $stmt2->fetchColumn();   

     }


     /**
      **Get Latest Records Functions v1.0
      **Function To Get Latest Items from DB [users,items,comments].
      **$select= Field to select.
      **$table =The table to select from Example [users,items,comments].
      **$order=The DESC Ordering .
      **$limit =The number of records to select .
      **
      */
      function getLatest($select,$table,$order,$limit=5){

        global $con;
        $getstmt=$con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");
        $getstmt->execute();
        $rows=$getstmt->fetchAll();
        return $rows;

      }

      // Function to insert log activity into MongoDB collection "Activities_USER_Log".
      
      function logActivity($userID, $action, $details = []) {
        global $db;
        
        $collection = $db->Activities_USER_Log;
        $log = [
            'user_id' => $userID,
            'action' => $action,
            'details' => $details,
            'timestamp' => new MongoDB\BSON\UTCDateTime()
        ];
        $collection->insertOne($log);
    }

    // function to truncate text
    function truncateText($text, $maxLength = 50) {
        return strlen($text) > $maxLength ? substr($text, 0, $maxLength) . '...' : $text;
    }