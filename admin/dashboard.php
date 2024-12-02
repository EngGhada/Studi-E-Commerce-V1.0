<?php
ob_start();  //Output Buffering Start
session_start();

if (isset($_SESSION['UserName'])) {

   $pageTitle='Dashboard';

   include 'init.php';

   /** start Dashboard page */

   $numUsers=6 ; //Number of users
   $theLatestUsers=getLatest("*","users","UserID",$numUsers); //  Latest Users array

   $numItems=6 ; //Number of Items
   $theLatestItems=getLatest("*","items","Item_ID",$numItems); // Latest Items array

   $numComments=6;
   $theLatestComments=getLatest("*","comments","c_id",$numComments); // Latest Comments array

   ?>
  <div class="body-container">
   <div class="Container home-stats text-center ">
      <h1>Dashboard</h1>
      <div class="row">
         <div class="col-md-3">
            <div class="stat st-members">
               
               <i class="fa fa-users"></i>
               
               <div class="info">
                   Nombre Total de Membres
                  <span><a href='members.php'><?php echo countItems('UserID' ,'users' );?></a></span>
               </div>
            </div>
         </div>
         <div class="col-md-3">
               <div class="stat st-pending">
                  <i class="fa fa-user-plus"></i>
                  <div class="info">
                     Membres en Attente
                     <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus','users',0)?></a></span>
                  </div>
               </div>
         </div>
         <div class="col-md-3">
            <div class="stat st-items">
               <i class="fa fa-tag"></i>
                  <div class="info">
                      Nombre Total d'Articles
                     <span><a href='items.php'><?php echo countItems('Item_ID' ,'items' );?></a></span>
                  </div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="stat st-comments">
               <i class="fa fa-comment"></i>
               <div class="info">
                   Nombre Total de Commentaires
                  <span><a href='comments.php'><?php echo countItems('c_id' ,'comments' );?></a></span>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="Container latest">
      <div class="row">
         <div class="col-sm-6">
            <div class="card ">
            
                  <div class="card-header">
                    <i class="fa fa-users"></i> Derniers <?php  if(!empty($theLatestUsers)) {echo $numUsers;} ?> Utilisateurs Enregistrés
                    <span class="toggle-info float-end">
                        <i class="fa fa-plus fa-lg"></i>
                    </span>
                 </div>
                  <div class="card-body">
                  <ul class="list-unstyled latest-users">
                  <?php
                   
                    if(!empty($theLatestUsers))
                     {
                         foreach ($theLatestUsers as $user) 
                           {
                              echo '<li>';
                              echo   $user['UserName'];
                              echo    '<a href="members.php?do=Edit&userid='.$user['UserID'].'">';
                              echo     '<span class="btn btn-sm btn-success float-end">';
                              echo     '<i class="fa fa-edit"></i>Modifier ';
                              if($user['RegStatus']==0){    
                                 echo "<a href='members.php?do=Activate&userid=". $user['UserID']."'class='btn btn-sm btn-info float-end activate'><i class='fa fa-check'></i> Activer </a>";
                           }
                              echo     '</span>';
                              echo     '</a>';
                              echo     '</li>';
                           }
                     }else{
                        echo '<li>Aucun enregistrement à afficher</li>';
                     }
                  ?>
                  </ul>
                      </div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="card ">
               
                  <div class="card-header">
                     <i class="fa fa-tag"></i> Derniers <?php if(!empty($theLatestItems)) { echo $numItems;} ?> Articles
                     <span class="toggle-info float-end">
                           <i class="fa fa-plus fa-lg"></i>
                     </span>
                   </div>
                  <div class="card-body">
                  <ul class="list-unstyled latest-users">
                  <?php
                   
                    if(!empty($theLatestItems))
                    {
                     foreach ($theLatestItems as $item) 
                     {
                        echo '<li>';
                        echo   $item['Name'];
                        echo    '<a href="items.php?do=Edit&itemid='.$item['Item_ID'].'">';
                        echo     '<span class="btn btn-sm btn-success float-end">';
                        echo     '<i class="fa fa-edit"></i>Modifier ';
                        if($item['Approve']==0){    
                           echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID']."'class='btn btn-sm btn-info float-end activate'><i class='fa fa-check'></i> Approuver </a>";
                       }
                        echo     '</span>';
                        echo     '</a>';
                        echo     '</li>';
                     }
                  }else{
                     echo '<li>Aucun article à afficher</li>';
                  }
                  ?>
                  </ul>
                 </div>
            </div>
         </div>
      </div>
      <!-- Start Latest Comments -->
      <div class="row">
         <div class="col-sm-6">
            <div class="card ">

                  <div class="card-header">
                    <i class="fas fa-comments"></i> Derniers <?php  if(!empty($theLatestComments)) { echo $numComments;} ?> Commentaires
                    <span class="toggle-info float-end">
                        <i class="fas fa-plus fa-lg"></i>
                    </span>
                 </div>
                  <div class="card-body">
                  <?php
                     $stmt=$con->prepare("SELECT comments.* , users.UserName As Member 
                                          FROM 
                                                   comments
                                          INNER JOIN
                                                    users 
                                          ON 
                                                   comments.user_id = users.UserID
                                         ORDER BY  c_id DESC
                                         LIMIT $numComments");
                                          
                     $stmt->execute();
                     $comments = $stmt->fetchAll();
                     if(!empty($comments))
                     {
                     foreach ($comments as $comment) 
                        {
                           echo '<div class="comment-box">';
                                 echo '<span class="member-n">
                                  <a href="members.php?do=Edit&userid= '. $comment['user_id'].'">
                                 '.$comment['Member'].'</a></span>';

                                 echo '<p     class="member-c">'.$comment['comment'].'</p>';
                           echo '</div>';   
                        }
                    }else{
                     echo '<div class="container">';
                        echo '<div class="nice-message">Aucun enregistrement à afficher</div>';
                     echo '</div>';
                    }
                  ?>
                      </div>
            </div>
         </div>
   </div>
</div>
   <?php
   
   include $tpl."footer.php";

 }else {
   
    header('Location:../login.php'); // Redirect to the login page
    exit();                       // Terminate the script to prevent further execution

 }

 ob_end_flush();

?>
