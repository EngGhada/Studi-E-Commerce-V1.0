<?php 

// Manage Member pages
// You can Edit| Add| Delete Members from here .

session_start();
$pageTitle='Les Membres';

if (isset($_SESSION['UserName'])) {

   include 'init.php';

   $do = isset($_GET['do'])? $_GET['do'] :'Manage';

   if($do =='Manage') { //Manage Members Page 

        $query='';
        if(isset($_GET['page'])&&$_GET['page']=='Pending'){

            $query ='And RegStatus=0';
        }

        //select all users except ADMIN

        $stmt = $con -> prepare(" Select * FROM users Where GroupID !=1  $query  ORDER BY UserID DESC");
        $stmt->execute();
        // Assign To Variable
        $rows=$stmt->fetchAll();
        if(!empty($rows)){
    ?>
      <h1 class='text-center'>Gérer les Membres</h1>
      <div class='container'>
           <div class="table-responsive ">
                <table class='table text-center table-bordered manageTable'>
                    <tr >
                        
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Nom d'Utilisateur</td>
                            <td>E-mail</td>
                            <td>Nom complet</td>
                            <td>Date d'Inscription</td>
                            <td>Contrôle</td>
                    
                    
                    </tr>
                    <?php
                                foreach($rows as $row){
                                    echo '<tr>';
                                        echo '<td>'.$row['UserID'].'</td>';
                                        echo "<td>";
                                        if(empty($row['Avatar'])){
                                            echo "<img src='../layout/images/avatar.png' alt='' />";
                                        }else{
                                                echo "<img src='uploads/avatars/".$row['Avatar']."' alt=''/>";
                                            }
                                        echo "</td>";
                                        echo '<td>'.$row['UserName'].'</td>';
                                        echo '<td>'.$row['Email'].'</td>';
                                        echo '<td>'.$row['FullName'].'</td>';
                                        echo '<td>'.$row['Date'].'</td>';
                                        echo "<td>
                                             
                                            <a href='members.php?do=Edit&userid=". $row['UserID']."' class='btn btn-xs btn-success'><i class='fa fa-edit'></i>Modifier</a>
                                            <a href='members.php?do=Delete&userid=". $row['UserID']."' class='btn btn-xs btn-danger confirm'><i class='fa fa-close'></i>Supprimer</a>";

                                        if($row['RegStatus']==0){    
                                            echo "<a href='members.php?do=Activate&userid=". $row['UserID']."'class='btn btn-xs btn-info activate'><i class='fa fa-check'></i>Activer</a>";
                                        }

                                        
                                         echo "</td>";
                                    echo '</tr>';
                                }
                        ?>
                    
                </table>
           </div> 
        <a href="members.php?do=Add" class="btn btn-primary addmember"><i class="fa fa-plus"></i> un Nouveau Membre</a>
    </div>
         
      <?php }else{ 
                        echo '<div class="container">';
                         echo '<div class="nice-message">Il n\'y a aucun membre à afficher</div>';
                         echo'<a href="members.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Nouveau Membre</a>';
                        echo '</div>';
                        } ?>
  <?php 
     
     }elseif($do =='Add') {?>

    <h1 class='text-center'>Ajouter un Nouveau Membre</h1>
    <div class='container'>
    

        <form action='?do=Insert'  method="POST" enctype="multipart/form-data">
          
                <div class='row  form-control-lg'>
                
                    <label  class='col-sm-2 col-form-label'>Nom d'Utilisateur</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='username' class='form-control' id='username' autocomplete='off' required='required' placeholder =" Nom d'utilisateur pour se connecter au formulaire."/>
                    </div>
                </div>
                <div class='row form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Mot de Passe</label>
                    <div class='col-sm-10 col-md-6'>
                        
                        <input  class='form-control' type='password' name='password' id='password' required='required'  placeholder =" Le mot de passe doit être difficile et complexe." />
                    </div>
                </div>
                <div class='row  form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>E-mail</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='email' name='email' class='form-control'  id='email' required='required'  placeholder =" L'e-mail doit être valide"/>
                    </div>
                </div>
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>Nom Complet </label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='full' class='form-control'  id='FullName' required='required' placeholder ="Le nom complet apparaît sur votre profil"/>
                    </div>
                </div>
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>Avatar</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='file' name='Avatar' class='form-control'/>
                    </div>
                </div>
                <div class='row form-control-lg'>
                    <div class='col-sm-10 offset-sm-2'>
                        <input type='submit' value='Ajouter un Nouveau Membre' class='btn btn-primary btn-lg'/>
                    </div>
                </div>
                <!-- End button -->
        </form>
    </div>
    <?php
    }elseif($do=='Insert'){

    //echo $_POST['username']. $_POST['full']. $_POST['password']. $_POST['email'];
    
        //Insert Member page.

    if($_SERVER['REQUEST_METHOD']=='POST'){

        echo"<h1 class='text-center'>Insérer un Nouveau Membre</h1>";
        echo "<div class='container'>";

        //Upload variables.

        
        $avatarName =$_FILES['Avatar']['name'];
        $avatarType =$_FILES['Avatar']['type'] ;
        $avatarTmp  = $_FILES['Avatar']['tmp_name'];
        $avatarSize = $_FILES['Avatar']['size'];
        
        // List Of Allowed Typed Extensions to Upload .
        $avatarAllowedExtensions=array("jpeg","jpg","png","gif");


       // Get the extension of your image
        $avatarNameParts = explode('.', $avatarName);
        $avatarExtension = strtolower(end($avatarNameParts));  
        
        

       //Get variables from Form 

       $user  = $_POST['username'];
       $pass  = $_POST['password'];
       $email = $_POST['email'];
       $name  = $_POST['full'];
 
       $hashPass=password_hash($_POST['password'] , PASSWORD_BCRYPT);

       //validate the form
       $formErrors=array();

       if(strlen($user)<4){

        $formErrors[]="Le nom d'utilisateur ne peut pas contenir moins de <strong>4 caractères .</strong>";

       }
       if(strlen($user)>20){

        $formErrors[]="Le nom d'utilisateur ne peut pas contenir plus de <strong> 20 caractères</strong>";
            
       }

       if(empty($user)){
        $formErrors[]="Le nom d'utilisateur ne peut pas être  <strong>Vide</strong>";
       }

       if(empty($pass)){
        $formErrors[]="Password can't be <strong>Empty</strong>";
       }

       if(empty($email)){
        $formErrors[]="L'e-mail ne peut pas être <strong>Vide</strong>";
       }

       if(empty($name)){
        $formErrors[]="Le nom complet ne peut pas être <strong>Vide</strong>";
       }

       if(!empty($avatarName) && ! in_array( $avatarExtension,$avatarAllowedExtensions)){
        $formErrors[]="Cette extension n'est pas <strong>Autorisée</strong>";
       }

       if(empty($avatarName)){
        $formErrors[]="Avatar Is <strong>Required</strong>";
       }

       if($avatarSize > 2097152){
        $formErrors[]="L'avatar ne peut pas dépasser <strong>2MB</strong>";
       }
       // Loop Into Error array And Echo it 
       foreach ($formErrors as $error) {
     
        echo "<div class='alert alert-danger'>". $error."</div>" ;
        
       }
       // check that there is no error and  proceed the Update Process.
       if(empty($formErrors)){

        $avatar= rand(0,100000) .'_'.$avatarName;
        $avatarUrl = "uploads/avatars/" . $avatar;
       
        
        if (move_uploaded_file($avatarTmp , $avatarUrl)) {
            echo " Fichier téléchargé avec succès.";
        } else {
            echo "Erreur lors du téléchargement du fichier.";
            print_r($_FILES['Avatar']);
        }
            
      
    
        $check=checkItem("UserName","users",$user);
        if( $check==1){
            $theMsg= "<div class='alert alert-danger'> Désolé, cet utilisateur  déjà existe </div>";
           // redirectHome($theMsg,'back');

        } else{
         //update the database with the Info.

                $stmt = $con->prepare("Insert  Into users ( UserName , Password , Email , FullName, RegStatus, Date ,Avatar) VALUES (:zuser , :zpass , :zmail , :zfull ,1, now(),:zavatar)");
                $stmt->execute(array(
                            'zuser'    => $user,
                            'zpass'    => $hashPass,
                            'zmail'    => $email,
                            'zfull'    => $name,
                            'zavatar'  => $avatar
                            ));

                    //echo success message.

                $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() .'  Enregistrement a été inséré</div>';
                redirectHome($theMsg,'back');
          
            }
        }
    }else{
        echo "<div class='container'>";
        $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page</div>";
        redirectHome($theMsg);
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

                    <h1 class='text-center'>Modifier Le Membre </h1>
                    <div class='container edit-members'>
                            <form  action='?do=Update'  method="POST" enctype="multipart/form-data">
                            <div  class='row  form-control-lg'>
                                
                                    <div class='col-md-3 image-avatar'>
                                        <?php if(empty($row['Avatar'])){

                                            echo " <img  class=' img-thumbnail img-fluid  mx-auto  d-block avatar-Edit-img ' src='../layout/images/avatar.png' alt='Current Avatar' />";
                                        }else{
                                         echo "<img  class=' img-thumbnail img-fluid  mx-auto  d-block avatar-Edit-img '  src='uploads/avatars/".$row['Avatar']."' alt='Current Avatar' />";
                                        }
                                         ?>
                                         <span style="font-size:12px;" class="text-avatar">
                                         <?php
                                         if(empty($row['Avatar'])){
                                            echo "<b>Default Avatar</b>";
                                         }else{echo"<b> Mon Avatar :</b>".$row['Avatar'];}
                                         ?>
                                           </span>
                                     </div>
                                    
                                    <div class='col-md-9 edit-member-info'>
                                            <input type='hidden' name='userid' class='form-control' id='userid' value='<?php echo $userid; ?>' />
                                            <ul class="list-unstyled">
                                            <li>
                                                    <i class="fas fa-user  fa-sm"></i>
                                                    <span> Nom d'Utilisateur  </span><input type="text" name="username" class="form-control" id="username"  value="<?php echo $row['UserName'];?>" autocomplete="off" required />
                                            </li>
                                            <li>
                                                    <i class="fas fa-user fa-sm"></i>
                                                    <span> Nom Complet  </span><input type="text" name="full" class="form-control" id="full"  value="<?php echo $row['FullName'];?>" autocomplete="off" required />
                                            </li>
                                            <li>
                                                   <i class="fas fa-lock fa-sm"></i>
                                                    <span>Mot de Passe  </span> 
                                                    <input type='hidden' name='oldpassword'  class='form-control' id='password' value="<?php echo $row['Password'] ; ?>" >
                                                    <input  class='form-control' type='password' name='newpassword' id='newpassword' value="" placeholder ="Laissez vide si vous ne souhaitez pas changer ." />
                                            </li> 
                                            <li>
                                                 <i class="fas fa-envelope fa-sm"></i>
                                                    <span>E-mail  </span> 
                                                    <input type='email' name='email' class='form-control'  value="<?php echo $row['Email'];?>" id='email' required/>
                                            </li>

                                            <li>
                                               
                                                    <i class="fas fa-user-circle fa-sm"></i>
                                                    <span> Nouvel Avatar </span>   
                                                    
                                                        <input type='file' name='Avatar' class='form-control' placeholder="Laissez vide si vous ne souhaitez pas changer l'avatar. "/>
                                                         <!-- <small>Leave blank if you don't want to change the avatar.</small> -->
                                                   
                                            </li>
                                            <li>
                                                  <small style="color:red;text-align:right;"> * Laissez vide si vous ne souhaitez pas changer l'avatar.</small>
                                            </li>
                                            <li>
                                                  <input type='submit' value='Enregistrer' class='btn btn-primary btn-lg '/>
                                            </li>
                                            
                                            </ul> 
                                    </div>
                                
                            </div>
                                                                
                        </form>
                </div>
                 <?php } else{

                        echo "<div class='container'>";
                        $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas</div>";
                        redirectHome($theMsg);
                        echo "</div>";

                }
    }elseif($do=='Update'){

       
        echo"<h1 class='text-center'>Mettre à Jour le Membre</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD']=='POST'){

            //Password Trick
            
            $pass=empty($_POST['newpassword'])?$pass=$_POST['oldpassword']:$pass=password_hash($_POST['newpassword'] , PASSWORD_BCRYPT);
            
          
           //Get variables from Form 

           $id    = $_POST['userid'];
           $user  = $_POST['username'];
           $email = $_POST['email'];
           $name  = $_POST['full'];


        
      
          // Check if a new file has been uploaded
          
                // Process the new avatar upload
                $avatarName = $_FILES['Avatar']['name'];
                $avatarType =$_FILES['Avatar']['type'] ;
                $avatarSize = $_FILES['Avatar']['size'];
                $avatarTmpPath = $_FILES['Avatar']['tmp_name'];

                $securedName= rand(0,100000) .'_'.$avatarName;
                $avatarPath = 'uploads/avatars/' . $securedName;
             
                // List Of Allowed Typed Extensions to Upload .
             $avatarAllowedExtensions=array("jpeg","jpg","png","gif");
   
           
             $avatarNameParts = explode('.', $avatarName);
             $avatarExtension = strtolower(end($avatarNameParts));  

        
           //validate the form
           $formErrors=array();

           if(strlen($user)<4){

            $formErrors[]="<div class='alert alert-danger'>Le nom d'utilisateur ne peut pas contenir moins de <strong> 4 caractères .</strong></div>";

           }
           if(strlen($user)>20){

            $formErrors[]="<div class='alert alert-danger'>Le nom d'utilisateur ne peut pas contenir plus de <strong> 20 caractères</strong></div>";
                
           }

           if(empty($user)){
            $formErrors[]="<div class='alert alert-danger'>Le nom d'utilisateur ne peut pas être  <strong>Vide</strong></div>";
           }

           if(empty($email)){
            $formErrors[]="<div class='alert alert-danger'>L'e-mail ne peut pas être <strong>Vide</strong></div>";
           }
           
           if(empty($name)){
            $formErrors[]="<div class='alert alert-danger'>Le nom complet ne peut pas être <strong>Vide</strong></div>";
           }
           if(!empty($avatarName) && ! in_array( $avatarExtension,$avatarAllowedExtensions)){
            $formErrors[]=" Cette extension n'est pas <strong>Autorisée</strong>";
           }
    
        //    if(empty($avatarName)){
        //     $formErrors[]="Avatar Is <strong>Required</strong>";
        //    }
    
           if($avatarSize > 2097152){
            $formErrors[]="L'avatar ne peut pas dépasser <strong>2MB</strong>";
           }

           // Loop Into Error array And Echo it 
           foreach ($formErrors as $error) {
         
            echo "<div class='alert alert-danger'>". $error."</div>" ;
            
           }
           // check that there is no error and  proceed the Update Process.
           if(empty($formErrors)){

               
           // check that there is no other user with the same username( USERNAME is unique in DB) before insering the updating data to the database.

            $stmt2=$con->prepare('SELECT * FROM users where UserName=? AND UserID !=? ');
            $stmt2->execute(array( $user,$id));
            $count=$stmt2->rowCount();

            if($count==1){

                  // if there is a user with the same username stop updatig and print the MSG.
                $theMsg = "<div class='alert alert-danger'>Désolé, cet utilisateur existe déjà.</div>";
               redirectHome($theMsg,'back');
               
            }else{
                 
                 // Fetch the current avatar from the database
                    $stmt = $con->prepare("SELECT Avatar FROM users WHERE UserID = ?");
                    $stmt->execute(array($id));
                    $countAvatar= $stmt->rowCount();
                    if($countAvatar > 0){
                        $currentAvatar=$stmt->fetchColumn();
                    }

                // Check if a new file has been uploaded
              if (!empty($_FILES['Avatar']['name'])) {

                    // Move the uploaded file to the server
                        if (move_uploaded_file($avatarTmpPath, $avatarPath)) {
                            // File uploaded successfully, use the new file
                            $avatar = $securedName;
                        } else {
                            echo "<div class='alert alert-danger'>Erreur lors du téléchargement du fichier.</div>";
                            $avatar = $currentAvatar; // Keep the old avatar if upload fails
                        }
            
                } else {
                    // No new file uploaded, keep the existing one
                    $avatar = $currentAvatar;
                }
                // update the database with the Info.

                    $stmt = $con->prepare(" UPDATE users SET UserName = ? , Password = ? , Email = ? , FullName = ? , Avatar = ? WHERE UserID = ? ");
                    $stmt->execute(array($user,$pass,$email,$name,$avatar,$id));

                
                }
                //echo success message.
                $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() . ' Enregistrement mis à jour.</div>';
                redirectHome($theMsg);
           }
            

            

        }else{

            $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page</div>";
            redirectHome($theMsg);
        }
       echo '</div>';

    }elseif($do=='Delete'){

        //Delete Member page
        echo"<h1 class='text-center'>Supprimer Le Member</h1>";
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

          $theMsg='<div class="alert alert-success">'. $stmt->rowCount().' Enregistrement a été supprimé.</div>';
          redirectHome($theMsg,'back');

        }else{
            $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas.</div>";
            redirectHome($theMsg);
        }
      echo  '</div>';
    }elseif($do=='Activate'){
        
         //Activate Member page
         echo"<h1 class='text-center'>Activer le Membre</h1>";
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
 
           $theMsg='<div class="alert alert-success">L\'utilisateur a été activé</div>';
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