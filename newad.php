<?php 

ob_start();  //Output Buffering Start
session_start();
$pageTitle='Créer un Nouvel Article';
 
include 'init.php';
if(isset($_SESSION['User'])){ 
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $formErrors=[];

        $name     = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
        $desc     = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
        $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country  = htmlspecialchars(trim($_POST['country']), ENT_QUOTES, 'UTF-8');
        $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags     = htmlspecialchars(trim($_POST['tags']), ENT_QUOTES, 'UTF-8');
    
          //Upload variables.

          $imgName =strip_tags($_FILES['Image']['name']); //sanitize the file name by stripping out any potential HTML or PHP tags.
          $imgType =$_FILES['Image']['type'] ;
          $imgTmp  =$_FILES['Image']['tmp_name'];
          $imgSize =$_FILES['Image']['size'];
          
          // List Of Allowed Typed Extensions to Upload .
          $imgAllowedExtensions=array("jpeg","jpg","png","gif");
  
  
         // Get the extension of your image
          $imgNameParts = explode('.', $imgName);
          $imgExtension = strtolower(end($imgNameParts));  
          
          
  
        

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            if(strlen($name) < 3){
                $formErrors[]='Le nom de l\'article doit comporter au moins 3 caractères';
            }

            $desc = isset($_POST['description']) ? trim($_POST['description']) : '';
            if(strlen($desc) < 10 ) { 
            
                $formErrors[]='La description de l\'article doit comporter au moins 10 caractères'; 
            }

            $country = isset($_POST['country']) ? trim($_POST['country']) : '';
            if(strlen($country) < 2 ) { 
                $formErrors[]='Le pays de l\'article doit comporter au moins 2 caractères'; 
            }
            if (!preg_match("/^[a-zA-Z\s]+$/", $country)) {
                $formErrors[] = 'Le pays de l\'article doit contenir uniquement des lettres.';
            }

            if(empty($price)){
                $formErrors[]='Le prix de l\'article ne doit pas être vide.';
            }

            if(empty($status)){
                $formErrors[]='Le statut de l\'article ne doit pas être vide.';

            }
            if(empty($category)){
                $formErrors[]='La catégorie de l\'article ne doit pas être vide.';
            }

            if(!empty($imgName) && ! in_array( $imgExtension,$imgAllowedExtensions)){
                $formErrors[]="Cette extension n'est pas <strong>Autorisée</strong>";
               }
        
            //    if(empty($imgName)){
            //     $formErrors[]="Image Is <strong>Required</strong>";
            //    }
        
               if($imgSize > 2097152){
                $formErrors[]=" L'image ne peut pas dépasser <strong>2MB</strong>";
               }


              // check that there is no error and  proceed the Update Process.
              if(empty($formErrors)){

                
        
                if(!empty($imgName)){

                    $img= rand(0,100000) .'_'.$imgName;
                    $imgUrl = "admin/uploads/images/" . $img;
                
                    
                    if (move_uploaded_file($imgTmp , $imgUrl)) {
                        echo " Fichier téléchargé avec succès.";
                    } else {
                        echo "Erreur lors du téléchargement du fichier.";
                        print_r($_FILES['Image']);
                    }
                   
            }else{
                $img="";
            }
                // update the database with the Info.

                        $stmt= $con->prepare("Insert Into items ( Name ,Image ,Description , Price , Country_Made, Status, Add_Date,Member_ID,Cat_ID ,Tags ) VALUES (:zname ,:zimage ,:zdesc , :zprice , :zcountry ,:zstatus, now(),:zmember,:zcat , :ztags)");
                        $stmt->execute(array(
                                    'zname'     => $name,
                                    'zimage'    => $img,
                                    'zdesc'     => $desc,
                                    'zprice'    => $price,
                                    'zcountry'  => $country,
                                    'zstatus'   => $status,
                                    'zmember'   => $_SESSION['uid'],
                                    'zcat'      => $category,
                                    'ztags'     => $tags
                                    ));

                            //echo success message.
                            if($stmt){
                                $successMsg= 'L\'article a été ajouté avec succès';
                                
                                $details = [
                                    'Nom_de_connexion' => mb_convert_encoding($_SESSION['User'], 'UTF-8', 'auto'),
                                    'Le nom d\'article' => mb_convert_encoding($name, 'UTF-8', 'auto'),
                                    'Le categorie Numéro' => mb_convert_encoding($category, 'UTF-8', 'auto')
                                ];
                                
                                logActivity($_SESSION['uid'], "Un nouvel article a été ajouté ", $details);
                            
                            }else{
                                echo 'Un problème est survenu lors de l\'insertion de l\'annonce';
                            }
           }
    }
    ?>

   <h1 class="text-center"><?php echo $pageTitle; ?></h1>
    <div class="create-ad block">
        <div class="container">
            <div class="card ">
                <div class="card-header bg-primary text-white">
                    <?php echo $pageTitle; ?>
               </div>
               <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                                    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>  method="POST" enctype="multipart/form-data">
                                        <div class='row  form-control-lg'>
                                                    <!-- Start Name Field -->
                                                <label  class='col-sm-3 col-form-label'>Nom</label>
                                                <div class='col-sm-10 col-md-9'>
                                                    <input 
                                                     pattern=".{4,}" title="Ce champ nécessite au moins 4 caractères"
                                                    type='text' name='name'
                                                    class='form-control live'
                                                    data-class=".live-title"
                                                    id='name' placeholder ="Nom de l'article" required/>
                                                </div>
                                            </div>
                                                <!-- End  Name Field -->
                                                <!-- Start Description Field -->
                                            <div class='row form-control-lg'>
                                                <label  class='col-sm-3 col-form-label'>Description</label>
                                                <div class='col-sm-10 col-md-9'>
                                                    <input 
                                                     pattern=".{10,}" title="Ce champ nécessite au moins 10 caractères."
                                                     type='text' class='form-control live'
                                                            data-class=".live-desc"  
                                                            name='description' id='description' 
                                                            placeholder ="Description de l'article"  required />
                                                </div>
                                            </div>
                                            <!-- End Description Field -->
                                            <!-- Start Image Item -->
                                            <div class='row form-control-lg'>
                                                <label class='col-sm-3 col-form-label'>Image</label>
                                                <div class='col-sm-10 col-md-9'>
                                                    <input type='file' name='Image' class='form-control'/>
                                                </div>
                                            </div>
                                            <!-- End Image Item -->
                                            <!-- Start Price Field -->
                                            <div class='row  form-control-lg'>
                                                <label  class='col-sm-3 col-form-label'>Prix</label>
                                                <div class='col-sm-10 col-md-9'>
                                                    <input type='text' name='price' 
                                                    class='form-control live'
                                                    data-class=".live-price"
                                                    id='price'  placeholder =" Prix de l'article ." required />
                                                </div>
                                            </div>
                                            <!-- End Price Field -->
                                            <!-- Start Country Field -->
                                            <div class='row  form-control-lg'>

                                                <label  class='col-sm-3 col-form-label'>Pays</label>
                                                <div class='col-sm-10 col-md-9'>
                                                    <input 
                                                    pattern=".{2,}" title="Ce champ nécessite au moins 2 caractères."
                                                    type='text'  class='form-control'
                                                    name='country'  id='country'  placeholder =" Pays de fabrication"  required  />
                                                </div>

                                            </div>
                                            <!-- End Country Field -->
                                            <!-- Start Status Field -->
                                            <div class='row  form-control-lg'>
                                                <label  class='col-sm-3 col-form-label'>Statut</label>
                                                <div class='col-sm-10 col-md-9'>
                                                    <select name="status" id="status" required>
                                                        <option value="">...</option>
                                                        <option value="1">Neuf</option>
                                                        <option value="2"> Comme Neuf</option>
                                                        <option value="3">D'occasion</option>
                                                        <option value="4">Très Ancien</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- End Status Field -->
                                            <!-- Start Category Field -->
                                            <div class='row  form-control-lg'>
                                                <label  class='col-sm-3 col-form-label'>Catégorie</label>
                                                <div class='col-sm-10 col-md-9'>
                                                <select name="category" id="category">
                                                        <option value="0">...</option>
                                                        <?php
                                                        $mainCats= getAllFrom("*" , "categories" , "WHERE Parent=0", "" , "ID") ;
                                                        foreach ($mainCats as $cat)
                                                        {    
                                                                $disabled = $cat['Allow_Ads'] == 1 ? 'disabled' : '';
                                                                echo "<option value='".$cat['ID']."' $disabled>".$cat['Name']."</option>";
                                                            $childCats= getAllFrom("*" , "categories" , "WHERE Parent={$cat['ID']}", "" , "ID") ;
                                                            foreach ( $childCats as $child)
                                                            {
                                                               // Disable if Allow_Ads = 1
                                                                 $disabled = $child['Allow_Ads'] == 1 ? 'disabled' : '';
                                                                 echo "<option value='".$child['ID']."' $disabled>-- ".$child['Name']."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                            <!-- End Category Field -->
                                              <!-- Start Tag Field -->
                                                <div class='row  form-control-lg'>
                                                    <label  class='col-sm-3 col-form-label'>Étiquettes</label>
                                                    <div class='col-sm-10 col-md-9'>
                                                        <input type='text' name='tags' class='form-control'  id='tags' placeholder ="Veuillez Séparer les Étiquettes par une Virgule (,)"/>
                                                    </div>
                                                </div>
                                                <!-- End Tag Field -->
                                                <!-- Start submit button -->
                                                <div class='row form-control-lg'>
                                                    <div class='col-sm-9 offset-sm-3'>
                                                        <input type='submit' value='Ajouter un Nouvel Article' class='btn btn-primary btn-sm'/>
                                                    </div>
                                                </div>
                                                <!-- End Submit button -->
                                    </form>
                        </div>

                        <div class="col-md-4">
                            <div class='card item-box ' >

                                        <span class='price-tag'>
                                            $<span class='live-price'>0</span>
                                        </span>
                                        <img style='border:solid 1px #EEE;' class='card-img-top    image-responsive' src='admin/uploads/default-product.png' alt='image not found' />
                                        <div class='caption'>
                                            <h5 class='live-title'>Titre</h3>
                                            <p class='live-desc'>Description</p>                  
                                        </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start Looping Throuh Error Array -->
                    <?php
                        if(!empty($formErrors)){
                            foreach($formErrors as $error){
                                echo "<div class='alert alert-danger newad-error-msg'>".$error."</div>";
                            }
                        }
                        if(isset($successMsg)){
                            echo '<div class="alert alert-success">'.$successMsg.'</div>'; 
                        }

                    ?>
                     <!--End Looping Throuh Error Array -->
               </div>
            </div>
        </div>
   </div>

   <?php }else{
        header('Location:login.php');
        exit();
    }
?>
<?php include $tpl."footer.php";
    ob_end_flush();
?>

