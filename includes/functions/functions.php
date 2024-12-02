<?php
    
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

            echo 'Page d\'Accueil Par Défaut';
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

            $url = 'index.php';
            $link='Page d\'accueil';
        }else{
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){
                $url =$_SERVER['HTTP_REFERER'];
                $link='Previous Page';
            }else{
                $url = 'index.php';
                $link =' Page d\'accueil';
            }
        }

        echo $theMsg;
        echo "<div class='alert alert-info'>  Vous serez redirigé vers $link dans $seconds seconds  . </div>";
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
        /**
             **Get All Functions v2.0
            **Function To Get All records from any table of DB .
      */
      function getAllFrom($field , $table , $where = NULL, $and=NULL , $orderfield , $ordering = 'DESC'){
       
        global $con;
        // $sql = $where == NULL ? '' : $where ;

        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering ");

        $getAll->execute();
        $all=$getAll->fetchAll(PDO::FETCH_ASSOC);
        return $all;

      }

      function getAllWithJoin( $field, $table1, $table2, $onCondition, $where =NULL, $and =NULL, $orderField, $ordering = 'DESC') {
        global $con; // Assuming $con is your PDO connection object
    
        // Construct the SQL query
        $sql = "SELECT $field 
                FROM $table1 
                INNER JOIN $table2 
                ON $onCondition 
                $where 
                $and 
                ORDER BY $orderField $ordering";
    
        // Prepare and execute the query
        $getAll = $con->prepare($sql);
        $getAll->execute();
    
        // Fetch and return all results
        return $getAll->fetchAll(PDO::FETCH_ASSOC);
    }
    
      
             /**
      **Get AD Items Functions v1.0
      **Function To Get AD Items from DB .
      */
      function getItems($where, $value, $approve = NULL){

        global $con;

        if($approve === 1){
            $sql='AND Approve=1';

        }else if($approve === 0){
            $sql='AND Approve=0';
        }else{
            $sql=NULL;
        }
        error_log("Executing query: SELECT * FROM items WHERE $where = $value $sql");
        $getCat=$con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC ");
        $getCat->execute(array($value));
        $cats=$getCat->fetchAll();

        return $cats;
        
      }

      
    //check if user is not activated 
    //Function to check  the RegStatus of the User

    function checkUserStatus($user){

        global $con;
        $stmtx = $con -> prepare(" Select 
                                         UserName , RegStatus
                                    FROM 
                                        users
                                    Where 
                                        UserName = ? 
                                    And   
                                        RegStatus = 0 ");

        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();

        return $status;
}
// log user activites 
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
    
