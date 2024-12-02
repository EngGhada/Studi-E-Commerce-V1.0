<?php 

// You can Edit profile from here .
ob_start();
session_start();
$pageTitle='Modifier le Profil';

if (isset($_SESSION['User'])) {

    include 'init.php';
  
    $do= isset($_GET['do'])? $_GET['do'] :'My-Profile';
    if ($do=='My-Profile'){

        header('Location: profile.php');
        exit();

    }else if($do=='Edit') {
           
            $userid=isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0;
    
            $stmt = $con -> prepare(" Select * FROM users Where UserID = ? LIMIT 1 ");

            $stmt->execute(array($userid));
            $row= $stmt->fetch();
            $count = $stmt->rowCount();

            if($count > 0) { ?>

                <h1 class='text-center'>Modifier le Profil</h1>
                <div class='container edit-members'>
                        <form  action='?do=Update'  method="POST" enctype="multipart/form-data">
                        <div  class='row  form-control-lg'>
                            
                                <div class='col-md-3 image-avatar'>
                                    <?php if(empty($row['Avatar'])){

                                        echo " <img  class=' img-thumbnail img-fluid  mx-auto  d-block avatar-Edit-img ' src='admin/uploads/avatar.png' alt='Current Avatar' />";
                                    }else{
                                     echo "<img  class=' img-thumbnail img-fluid  mx-auto  d-block avatar-Edit-img '  src='admin/uploads/avatars/".$row['Avatar']."' alt='Current Avatar' />";
                                    }
                                     ?>
                                     
                                 </div>
                                
                                <div class='col-md-9 edit-member-info'>
                                        <input type='hidden' name='userid' class='form-control' id='userid' value='<?php echo $userid; ?>' />
                                        <ul class="list-unstyled">
                                        <li>
                                                <i class="fas fa-user  fa-sm"></i>
                                                <span>  Nom d'Utilisateur  </span>   <input type="text" name="username" class="form-control" id="username"  value="<?php echo $row['UserName'];?>" autocomplete="off" required />
                                        </li>
                                        <li>
                                                <i class="fas fa-user fa-sm"></i>
                                                <span> Nom Complet  </span>  <input type="text" name="full" class="form-control" id="full"  value="<?php echo $row['FullName'];?>" autocomplete="off" required />
                                        </li>
                                        <li>
                                               <i class="fas fa-lock fa-sm"></i>
                                                <span>Mot de Passe  </span> 
                                                <input type='hidden' name='oldpassword'  class='form-control' id='password' value="<?php echo $row['Password'] ; ?>" >
                                                <input  class='form-control' type='password' name='newpassword' id='newpassword' value="" placeholder ="Laissez vide si vous ne souhaitez pas changer " />
                                        </li> 
                                        <li>
                                             <i class="fas fa-envelope fa-sm"></i>
                                                <span>E-mail  </span>  
                                                <input type='email' name='email' class='form-control'  value="<?php echo $row['Email'];?>" id='email' required/>
                                        </li>

                                        <li>
                                           
                                                <i class="fas fa-user-circle fa-sm"></i>
                                                <span> Nouvel Avatar  </span> 
                                                
                                                    <input type='file' name='Avatar' class='form-control' placeholder="Laissez vide si vous ne souhaitez pas changer l'avatar "/>
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
                    $theMsg= "<div class='alert alert-danger'>There Is No such ID</div>";
                    redirectHome($theMsg);
                    echo "</div>";

            }
   }elseif($do=='Update'){

       
    echo"<h1 class='text-center'>Mettre à Jour le Profil</h1>";
    echo "<div class='container'>";

    if($_SERVER['REQUEST_METHOD']=='POST'){

        //Password Trick
        
        $pass=empty($_POST['newpassword'])?$pass=$_POST['oldpassword']:$pass = password_hash($_POST['newpassword'] , PASSWORD_BCRYPT);
        
      
       //Get variables from Form 

       $id    = $_POST['userid'];
       $user  = $_POST['username'];
       $email = $_POST['email'];
       $name  = $_POST['full'];


    
  
      // Check if a new file has been uploaded
      
            // Process the new avatar upload
            $avatarName = strip_tags($_FILES['Avatar']['name']);//prevent Cross-Site Scripting (XSS) attacks and other forms of injection attacks.
            $avatarType =$_FILES['Avatar']['type'] ;
            $avatarSize = $_FILES['Avatar']['size'];
            $avatarTmpPath = $_FILES['Avatar']['tmp_name'];

            $securedName= rand(0,100000) .'_'.$avatarName;
            $avatarPath = 'admin/uploads/avatars/' . $securedName;
         
            // List Of Allowed Typed Extensions to Upload .
         $avatarAllowedExtensions=array("jpeg","jpg","png","gif");

       
         $avatarNameParts = explode('.', $avatarName);
         $avatarExtension = strtolower(end($avatarNameParts));  

    
       //validate the form
       $formErrors=array();

       if(strlen($user)<4){

        $formErrors[]="<div class='alert alert-danger'>Le nom d'utilisateur ne peut pas contenir moins de  <strong>4 caractères</strong></div>";

       }
       if(strlen($user)>20){

        $formErrors[]="<div class='alert alert-danger'>Le nom d'utilisateur ne peut pas contenir plus de <strong>20 caractères.</strong></div>";
            
       }

       if(empty($user)){
        $formErrors[]="<div class='alert alert-danger'> Le nom d'utilisateur ne peut pas être <strong>Vide.</strong></div>";
       }

       if(empty($email)){
        $formErrors[]="<div class='alert alert-danger'>L'e-mail ne peut pas être <strong>Vide</strong></div>";
       }
       
       if(empty($name)){
        $formErrors[]="<div class='alert alert-danger'>Le nom complet ne peut pas être <strong>Vide .</strong></div>";
       }
       if(!empty($avatarName) && ! in_array( $avatarExtension,$avatarAllowedExtensions)){
        $formErrors[]="Cette extension n'est pas <strong>Autorisée</strong>";
       }

    //    if(empty($avatarName)){
    //     $formErrors[]="Avatar Is <strong>Required</strong>";
    //    }

       if($avatarSize > 2097152){
        $formErrors[]="Avatar  ne peut pas dépasser <strong>2MB</strong>";
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
            $theMsg = "<div class='alert alert-danger'>Désolé, cet utilisateur déjà existe.</div>";
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

            //echo success message.
                
                $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() . ' Enregistrement mis à jour.</div>';
                if($stmt->rowCount()>0){
                    $details = [
                        'Nom_de_connexion' => mb_convert_encoding($_SESSION['User'], 'UTF-8', 'auto')
                    ];
                    
                    logActivity($_SESSION['uid'], "Modifier Le Profile", $details);

                }
                redirectHome($theMsg,'back', 2);
            }
        
       }
        
    }else{

        $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page.</div>";
         redirectHome($theMsg);
    }
   echo '</div>';
    
  }
  include $tpl."footer.php";

}else{
    header('Location: login.php');
    exit();
}
ob_end_flush();