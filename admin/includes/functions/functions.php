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

            $url = 'index.php';
            $link='Home Page';
        }else{
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){
                $url =$_SERVER['HTTP_REFERER'];
                $link='Previous Page';
            }else{
                $url = 'index.php';
                $link ='Home Page';
            }
        }

        echo $theMsg;
        echo "<div class='alert alert-info'> You will be directed to the $link After $seconds seconds . </div>";
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