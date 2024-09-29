<?php
ob_start();  //Output Buffering Start
session_start();

if (isset($_SESSION['UserName'])) {

   $pageTitle='Dashboard';

   include 'init.php';

   /** start Dashboard page */
   ?>

   <div class="Container home-stats text-center ">
      <h1>Dashboard</h1>
      <div class="row">
         <div class="col-md-3">
            <div class="stat st-members">
               Total Members
               <span><a href='members.php'><?php echo countItems('UserID' ,'users' );?></a></span>
            </div>
         </div>
         <div class="col-md-3">
            <div class="stat st-pending">
               Pending Members
               <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus','users',0)?></a></span>
            </div>
         </div>
         <div class="col-md-3">
            <div class="stat st-items">
               Total Items
               <span>1500</span>
            </div>
         </div>
         <div class="col-md-3">
            <div class="stat st-comments">
               Total Comments
               <span>3500</span>
            </div>
         </div>
      </div>
   </div>
   <div class="Container latest">
      <div class="row">
         <div class="col-sm-6">
            <div class="card ">
               <?php $theLatestUser=5 ;?>
                  <div class="card-header">
                  <i class="fa fa-users"></i> Latest <?php echo $theLatestUser; ?> Registered Users
                  </div>
                  <div class="card-body">
                  <ul class="list-unstyled latest-users">
                  <?php
                     $theLatest=getLatest("*","users","UserID",$theLatestUser);
                     foreach ($theLatest as $user) 
                     {
                        echo '<li>';
                        echo   $user['UserName'];
                        echo    '<a href="members.php?do=Edit&userid='.$user['UserID'].'">';
                        echo     '<span class="btn btn-success float-end">';
                        echo     '<i class="fa fa-edit"></i>Edit ';
                        if($user['RegStatus']==0){    
                           echo "<a href='members.php?do=Activate&userid=". $user['UserID']."'class='btn btn-info float-end activate'><i class='fa fa-close'></i>Activate</a>";
                       }
                        echo     '</span>';
                        echo     '</a>';
                        echo     '</li>';

                     }
                  ?>
                  </ul>
                      </div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="card ">
                  <div class="card-header">
                  <i class="fa fa-tag"></i> Latest Items
                  </div>
                  <div class="card-body">Test </div>
            </div>
         </div>
      </div>
   </div>

   



   <?php
   include $tpl."footer.php";

 }else {
   
    header('Location:index.php'); // Redirect to the login page
    exit();                       // Terminate the script to prevent further execution

 }

 ob_end_flush();

?>
