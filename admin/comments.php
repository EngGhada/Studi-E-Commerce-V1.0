<?php 

// Manage Comments pages
// You can Edit | Delete | Approve Comments from here .

ob_start();  //Output Buffering Start
session_start();
$pageTitle='Les Commentaires';

if (isset($_SESSION['UserName'])) {

   include 'init.php';

    $do = isset($_GET['do'])? $_GET['do'] :'Manage';

    if($do =='Manage') { //Manage Comments Page 

        //select the comments and all related data 

        $stmt = $con -> prepare(" Select comments.* ,items.Name ,users.UserName
                                  FROM  comments 
                                   INNER JOIN 
                                       items
                                   ON 
                                        items.Item_ID=comments.item_id
                                    INNER JOIN 
                                        users
                                   ON
                                       users.UserID=comments.user_id
                                    ORDER BY c_id DESC");
        $stmt->execute();

        // Assign To Variable
        $comments=$stmt->fetchAll();
       if(!empty($comments)){
    ?>
    
      <h1 class='text-center'> Gérer les Commentaires </h1>
      <div class='container'>
       <div class="table-responsive">
        <table class='table text-center table-bordered manageTable'>
        <tr >
            
                <td>#ID</td>
                <td>Commentaire</td> 
                <td>Date d'Ajout</td>
                <td>Nom de l'Article</td>
                <td>Nom d'Utilisateur</td>
                <td>Contrôle</td>       
      </tr>
                <?php
                        foreach($comments as $comment){
                            echo '<tr>';
                                echo '<td>'.$comment['c_id'].'</td>';
                                echo '<td>'. htmlspecialchars(truncateText($comment['comment'], 50)).'</td>';
                                echo '<td>'.$comment['comment_date'].'</td>';
                                echo '<td>'.$comment['Name'].'</td>';
                                echo '<td>'.$comment['UserName'].'</td>';
                                echo "<td>
                                    <a href='comments.php?do=Edit&comid=".$comment['c_id']."' class='btn btn-sm btn-success'><i class='fa fa-edit'></i>Modifier</a>
                                    <a href='comments.php?do=Delete&comid=".$comment['c_id']."' class='btn btn-sm btn-danger confirm'><i class='fa fa-close'></i>Supprimer</a>";

                                if($comment['status']==0){    
                                    echo "<a href='comments.php?do=Approve&comid=".$comment['c_id']."'class='btn btn-sm btn-info activate'><i class='fa fa-check'></i>Activer</a>";
                                }   
                            echo "</td>";
                            echo '</tr>';
                        }
                ?>
        </table>
       </div> 
      </div>
                    <?php }else{ 
                        echo '<div class="container">';
                         echo '<div class="nice-message">Il n\'y a aucun commentaire à afficher/div>';
                        echo '</div>';
                        } ?>
    <?php

    }elseif($do =='Edit') {

      $comid=isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']):0;
    
                $stmt = $con -> prepare(" Select * FROM comments Where c_id =? LIMIT 1 ");

                $stmt->execute(array($comid));
                $row= $stmt->fetch();
                $count = $stmt->rowCount();

                if($count > 0) { ?>

                    <h1 class='text-center'>Modifier les Commentaires</h1>
                    <div class='container'>
                        <form  action='?do=Update'  method="POST">

                            <input type='hidden' name='comid' class='form-control' id='comid' value="<?php echo $comid; ?>" />    
                        
                            <div class=" row form-control-lg">
                            <label class="col-sm-2   col-form-label">Commentaire</label>
                            <div class="col-sm-10 col-md-6">
                               <textarea class='form-control'  name="comment" id="comment"><?php echo $row['comment']; ?></textarea>
                            </div>
                        </div>
                         
                        <div class='row form-control-lg'>
                            <div class='col-sm-10 offset-sm-2'>
                                <input type='submit' value='Enregistrer' class='btn btn-primary btn-lg '/>
                            </div>
                        </div> 
                        <!-- End button -->
                    </form>
                </div>
                 <?php } else{
                        echo "<div class='container'>";
                        $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas</div>";
                        redirectHome($theMsg);
                        echo "</div>";
                }
    }elseif($do=='Update'){

        echo"<h1 class='text-center'> Mettre à Jour le Commentaire</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD']=='POST'){
            
           //Get variables from Form 

           $comid    = $_POST['comid'];
           $comment  = $_POST['comment'];
          

             // update the database with the Info.

            $stmt = $con->prepare(" UPDATE comments SET comment = ?   WHERE c_id = ? ");

            $stmt->execute(array($comment,$comid));

            //echo success message.

          $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() . ' Enregistrement mis à jour . </div>';
          redirectHome($theMsg,'back', 2);

           
            
        }else{

            $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page</div>";
            redirectHome($theMsg, 2);
        }
       echo '</div>';

    }elseif($do=='Delete'){

        //Delete comment page
        echo"<h1 class='text-center'>Supprimer le Commentaire</h1>";
        echo "<div class='container'>";

        $comid=isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']):0;
    
        $check=checkItem('c_id','comments',$comid);

        if($check > 0) { 

            $stmt = $con -> prepare(" Delete FROM comments Where c_id = :zcomid ");
            $stmt->bindParam(":zcomid",$comid);
            $stmt->execute();
            //echo success message.

          $theMsg='<div class="alert alert-success">' .$stmt->rowCount(). '  Enregistrement a été supprimé </div>';
          redirectHome($theMsg,'back');

        }else{
            $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas</div>";
            redirectHome($theMsg);
        }
      echo  '</div>';
    }elseif($do=='Approve'){
        
         //Activate Member page
         echo"<h1 class='text-center'>Approuver le Commentaire</h1>";
         echo "<div class='container'>";
 
         $comid=isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']):0;
         $check=checkItem('c_id','comments',$comid);
 
         if($check > 0) { 
 
             $stmt = $con -> prepare(" Update comments set status = 1 Where c_id = ? ");
             $stmt->execute(array($comid));
            
             //echo success message.
 
           $theMsg='<div class="alert alert-success">'.$stmt->rowCount().' Le commentaire a été approuvé </div>';
           redirectHome($theMsg);
 
         }else{

             $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas</div>";
             redirectHome($theMsg);
         }
       echo  '</div>';
        }
 
      include $tpl."footer.php";

 }else {
    header('Location:../login.php'); // Redirect to the login page
    exit();                       // Terminate the script to prevent further execution
 }
 ob_end_flush();