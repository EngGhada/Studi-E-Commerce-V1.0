<?php 

// Manage Member pages
// You can Edit| Add| Delete Members from here .

session_start();
$pageTitle='Members';

if (isset($_SESSION['UserName'])) {

   include 'init.php';

    $do = isset($_GET['do'])? $_GET['do'] :'Manage';

    if($do =='Manage') { //Manage Members Page 

        $query='';
        if(isset($_GET['page'])&&$_GET['page']=='Pending'){

            $query ='And RegStatus=0';
        }

        //select all users except ADMIN

        $stmt = $con -> prepare(" Select * FROM users Where GroupID !=1  $query");
        $stmt->execute();
        // Assign To Variable
        $rows=$stmt->fetchAll();
    
    
    
    
    ?>
      <h1 class='text-center'>Manage Members </h1>
      <div class='container'>
       <div class="table-responsive">
        <table class='table text-center table-bordered manageTable'>
        <tr >
            
                <td>#ID</td>
                <td>UserName</td>
                <td>Email</td>
                <td>Full Name</td>
                <td>Registered Date</td>
                <td>Control</td>
        
           
      </tr>
                <?php
                        foreach($rows as $row){
                            echo '<tr>';
                                echo '<td>'.$row['UserID'].'</td>';
                                echo '<td>'.$row['UserName'].'</td>';
                                echo '<td>'.$row['Email'].'</td>';
                                echo '<td>'.$row['FullName'].'</td>';
                                echo '<td>'.$row['Date'].'</td>';
                                echo "<td>
                                    <a href='members.php?do=Edit&userid=". $row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                    <a href='members.php?do=Delete&userid=". $row['UserID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";

                                if($row['RegStatus']==0){    
                                    echo "<a href='members.php?do=Activate&userid=". $row['UserID']."'class='btn btn-info activate'><i class='fa fa-close'></i>Activate</a>";
                                }

                              
                            echo "</td>";
                            echo '</tr>';
                        }
                ?>
           
            
        </table>
       </div> 
      <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
      </div>
         


  <?php }elseif($do =='Add') {?>

    <h1 class='text-center'>Add New Member</h1>

    <div class='container'>

        <form action='?do=Insert'  method="POST">
          
            <div class='row  form-control-lg'>
                
                    <label  class='col-sm-2 col-form-label'>Username</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='username' class='form-control' id='username' autocomplete='off' required='required' placeholder ="User name to login into form"/>
                    </div>
                </div>
                <div class='row form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Password</label>
                    <div class='col-sm-10 col-md-6'>
                        
                        <input  class='form-control' type='password' name='password' id='password' required='required'  placeholder ="Password must be hard & complexe" />
                    </div>
                </div>
                <div class='row  form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>E-mail</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='email' name='email' class='form-control'  id='email' required='required'  placeholder ="Email Must Be Valid"/>
                    </div>
                </div>
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>FullName</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='full' class='form-control'  id='FullName' required='required' placeholder ="Full Name appears on your profile name"/>
                    </div>
                </div>
                <div class='row form-control-lg'>
                    <div class='col-sm-10 offset-sm-2'>
                        <input type='submit' value='Add New Member' class='btn btn-primary btn-lg'/>
                    </div>
                </div>
                <!-- End button -->
        </form>
    </div>
    <?php
    }elseif($do=='Insert'){

    //echo $_POST['username']. $_POST['full']. $_POST['password']. $_POST['email'];

  

    if($_SERVER['REQUEST_METHOD']=='POST'){

        echo"<h1 class='text-center'>Insert New Member</h1>";
        echo "<div class='container'>";

       //Get variables from Form 

       
       $user  = $_POST['username'];
       $pass  = $_POST['password'];
       $email = $_POST['email'];
       $name  = $_POST['full'];
 
       $hashPass=sha1($_POST['password']);

       //validate the form
       $formErrors=array();

       if(strlen($user)<4){

        $formErrors[]="User name can't be less than <strong>4 characters</strong>";

       }
       if(strlen($user)>20){

        $formErrors[]="User name can't be more than<strong> 20 characters</strong>";
            
       }

       if(empty($user)){
        $formErrors[]="User name can't be <strong>Empty</strong>";
       }

       if(empty($pass)){
        $formErrors[]="Password can't be <strong>Empty</strong>";
       }

       if(empty($email)){
        $formErrors[]="Email can't be<strong>Empty</strong>";
       }

       if(empty($name)){
        $formErrors[]="Full Name can't be<strong>Empty</strong>";
       }

       // Loop Into Error array And Echo it 
       foreach ($formErrors as $error) {
     
        echo "<div class='alert alert-danger'>". $error."</div>" ;
        
       }
       // check that there is no error and  proceed the Update Process.
       if(empty($formErrors)){

       
        $check=checkItem("UserName","users",$user);
        if( $check==1){
            $theMsg= "<div class='alert alert-danger'>Sorry This user is exist</div>";
            redirectHome($theMsg,'back');

        } else{
         // update the database with the Info.

                $stmt = $con->prepare("Insert  Into users ( UserName , Password , Email , FullName, RegStatus, Date ) VALUES (:zuser , :zpass , :zmail , :zfull ,1, now())");
                $stmt->execute(array(
                            'zuser'  => $user,
                            'zpass'  => $hashPass,
                            'zmail'  => $email,
                            'zfull'  => $name
                            ));

                    //echo success message.

                $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() .'  Record Inserted</div>';
                redirectHome($theMsg,'back');
          
            }
        }
    }else{
        echo "<div class='container'>";
        $theMsg = "<div class='alert alert-danger'>You can't Browse this page directly</div>";
        redirectHome($theMsg,'back');
        echo "</div>";
    }
   echo '</div>';

    

    }elseif($do =='Edit') {

      $userid=isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0;
    
                $stmt = $con -> prepare(" Select * FROM users Where UserID = ? LIMIT 1 ");

                $stmt->execute(array($userid));
                $row= $stmt->fetch();
                $count = $stmt->rowCount();

                if($count > 0) { ?>

                    <h1 class='text-center'>Edit Members</h1>
                    <div class='container'>
                        <form  action='?do=Update'  method="POST">
                        
                            
                        <div  class='row form-control-lg'>

                            <div class='col-sm-10 col-md-6'>
                            <input type='hidden' name='userid' class='form-control' id='userid' value="<?php echo $userid; ?>" />
                        </div>
                             
                        <div class=" row form-control-lg">
                            <label class="col-sm-2   col-form-label">Username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" id="username"  value="<?php echo $row['UserName'];?>" autocomplete="off" required />
                            </div>
                        </div>
                                
                        <div class='row form-control-lg'>
                            <label  class='col-sm-2 col-form-label'>Password</label>
                            <div class='col-sm-10 col-md-6'>
                                <input type='hidden' name='oldpassword'  class='form-control' id='password' value="<?php echo $row['Password'] ; ?>" >
                                <input  class='form-control' type='password' name='newpassword' id='newpassword' value="" placeholder ="Leave Blank if you don't want to change " />
                            </div>
                        </div>
                        <div class='row form-control-lg'>
                            <label  class='col-sm-2  col-form-label'>E-mail</label>
                            <div class='col-sm-10 col-md-6'>
                                <input type='email' name='email' class='form-control'  value="<?php echo $row['Email'];?>" id='email' required/>
                            </div>
                        </div>

                        <div class='row form-control-lg'>
                            <label class='col-sm-2  col-form-label'>FullName</label>
                            <div class='col-sm-10 col-md-6'>
                                <input type='text' name='full' class='form-control'  value="<?php echo $row['FullName'];?>" id='FullName' required/>
                            </div>
                        </div>
                        <div class='row form-control-lg'>
                            <div class='col-sm-10 offset-sm-2'>
                                <input type='submit' value='Save' class='btn btn-primary btn-lg '/>
                            </div>
                        </div> 
                        <!-- End button -->
                    </form>
                </div>
                 <?php } else{

                        echo "<div class='container'>";
                        $theMsg= "<div class='alert alert-danger'>There Is No such ID</div>";
                        redirectHome($theMsg);
                        echo "</div>";

                }
    }elseif($do=='Update'){

       
        echo"<h1 class='text-center'>Update Members</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD']=='POST'){

            //Password Trick
            
            $pass=empty($_POST['newpassword'])?$pass=$_POST['oldpassword']:$pass=sha1($_POST['newpassword']);

           //Get variables from Form 

           $id    = $_POST['userid'];
           $user  = $_POST['username'];
           $email = $_POST['email'];
           $name  = $_POST['full'];

           //validate the form
           $formErrors=array();

           if(strlen($user)<4){

            $formErrors[]="<div class='alert alert-danger'>User name can't be less than <strong>4 characters</strong></div>";

           }
           if(strlen($user)>20){

            $formErrors[]="<div class='alert alert-danger'>User name can't be more than<strong> 20 characters</strong></div>";
                
           }

           if(empty($user)){
            $formErrors[]="<div class='alert alert-danger'>User name can't be <strong>Empty</strong></div>";
           }

           if(empty($email)){
            $formErrors[]="<div class='alert alert-danger'>Email can't be<strong>Empty</strong></div>";
           }
           
           if(empty($name)){
            $formErrors[]="<div class='alert alert-danger'>Full Name can't be<strong>Empty</strong></div>";
           }

           // Loop Into Error array And Echo it 
           foreach ($formErrors as $error) {
         
            echo  $error ;
            
           }
           // check that there is no error and  proceed the Update Process.
           if(empty($formErrors)){

             // update the database with the Info.

            $stmt = $con->prepare(" UPDATE users SET UserName = ? , Password = ? , Email = ? , FullName = ?  WHERE UserID = ? ");

            $stmt->execute(array($user,$pass,$email,$name,$id));

            //echo success message.

          $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() . ' Record Updated</div>';
          redirectHome($theMsg,'back', 6);

           }
            
        }else{

            $theMsg = "<div class='alert alert-danger'>You Can't Browse  this page directly</div>";
            redirectHome($theMsg);
        }
       echo '</div>';

    }elseif($do=='Delete'){

        //Delete Member page
        echo"<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";


        $userid=isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0;
    
        // $stmt = $con -> prepare(" Select * FROM users Where UserID = ? LIMIT 1 ");

        // $stmt->execute(array($userid));
       
        // $count = $stmt->rowCount();

        $check=checkItem('UserID','users',$userid);

        if($check > 0) { 

            $stmt = $con -> prepare(" Delete FROM users Where UserID = :zuser ");
            $stmt->bindParam(":zuser",$userid);
            $stmt->execute();
            //echo success message.

          $theMsg='<div class="alert alert-success">'. $stmt->rowCount() . ' Record Deleted</div>';
          redirectHome($theMsg);

        }else{
            $theMsg= "<div class='alert alert-danger'>This Id isn't exist</div>";
            redirectHome($theMsg);
        }
      echo  '</div>';
    }elseif($do=='Activate'){
        
         //Activate Member page
         echo"<h1 class='text-center'>Activate Member</h1>";
         echo "<div class='container'>";
 
 
         $userid=isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0;
     
         // $stmt = $con -> prepare(" Select * FROM users Where UserID = ? LIMIT 1 ");
 
         // $stmt->execute(array($userid));
        
         // $count = $stmt->rowCount();
 
         $check=checkItem('UserID','users',$userid);
 
         if($check > 0) { 
 
             $stmt = $con -> prepare(" Update users set RegStatus=1 Where UserID = ? ");
             $stmt->execute(array($userid));
            
             //echo success message.
 
           $theMsg='<div class="alert alert-success">The User has been activated</div>';
           redirectHome($theMsg);
 
         }else{
             $theMsg= "<div class='alert alert-danger'>This Id isn't exist</div>";
             redirectHome($theMsg);
         }
       echo  '</div>';
        }
    
    
   
      include $tpl."footer.php";

 }else {
   
    header('Location:index.php'); // Redirect to the login page
    exit();                       // Terminate the script to prevent further execution

 }