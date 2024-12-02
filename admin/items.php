<?php

  // Items Page
  // ==============

    ob_start();  //Output Buffering Start
    session_start();

    if (isset($_SESSION['UserName'])) {

    $pageTitle='Items';

    include 'init.php';

    $do = isset($_GET['do'])? $_GET['do'] :'Manage';
    if($do =='Manage') {
        
        $stmt = $con -> prepare("Select 
                                    items.* ,
                                    categories.Name As Category_Name ,
                                    users.UserName As User_Name
                                FROM 
                                     items
                                INNER JOIN 
                                    categories
                               ON categories.ID= items.Cat_ID 
                               INNER JOIN 
                                    users
                               ON users.UserID = items.Member_ID
                             ORDER BY Item_ID DESC ");
                                    
        $stmt->execute();

        // Assign To Variable
        $items=$stmt->fetchAll();

        if(!empty($items)){
    ?>
      <h1 class='text-center'> Gérer les Articles </h1>
      <div class='containeritm'>
       <div class="table-responsive">
        <table class='table text-center table-bordered manageTable '>
        <tr >
            
                <td>#Article_ID</td>
                <td>Nom</td>
                <td>Prix</td>
                <td>Date d'Ajout</td>
                <td>Pays de Fabrication</td>
                <td>Catégorie</td>
                <td>Ajouté par</td>
                <td>Contrôle</td>
        
           
      </tr>
                <?php
                        foreach($items as $item){
                            echo '<tr>';
                                echo '<td>'.$item['Item_ID'].'</td>';
                                echo '<td>'.$item['Name'].'</td>';
                                echo '<td>'.$item['Price'].'</td>';
                                echo '<td>'.$item['Add_Date'].'</td>';
                                echo '<td>'.$item['Country_Made'].'</td>';
                                echo '<td>'.$item['Category_Name'].'</td>';
                                echo '<td>'.$item['User_Name'].'</td>';
                                echo "<td>
                                   
                                    <a href='items.php?do=Edit&itemid=". $item['Item_ID']."' class='btn btn-xs btn-success'><i class='fa fa-edit'></i> Modifier</a>
                                    <a href='items.php?do=Delete&itemid=". $item['Item_ID']."' class='btn  btn-xs btn-danger confirm'><i class='fa fa-close'></i> Supprimer</a>";
                                    if($item['Approve']==0){    
                                        echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID']."'class='btn btn-xs btn-info activate'><i class='fa fa-check'></i> Approuver</a>";
                                    } 
                                   
                            echo "</td>";
                            echo '</tr>';
                        }
                ?>
           
            
        </table>
       </div> 
      <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> un Nouvel Article</a>
      </div>
      <?php }else{ 
                        echo '<div class="container">';
                            echo '<div class="nice-message">There is No Items To Show</div>';
                            echo'<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>';
                        echo '</div>';
                        } ?>
   <?php }elseif($do =='Add'){?>
        <h1 class='text-center'>Ajouter de Nouveaux Articles</h1>
        <div class='container'>
            <form action='?do=Insert'  method="POST" enctype="multipart/form-data">
                <div class='row  form-control-lg'>
                    
                            <!-- Start Name Field -->
                
                        <label  class='col-sm-2 col-form-label'>Nom</label>
                        <div class='col-sm-10 col-md-6'>
                            <input type='text' name='name' class='form-control' id='name' required='required'  placeholder ="Name Of The Item"/>
                        </div>
                    </div>
                        <!-- End  Name Field -->
                         <!-- Start Image field  -->
                         <div class='row form-control-lg'>
                            <label class='col-sm-2 col-form-label'>Image</label>
                            <div class='col-sm-10 col-md-6'>
                                <input type='file' name='Image' class='form-control'/>
                            </div>
                         </div>
                          <!-- End Image field -->
                        <!-- Start Description Field -->
                    <div class='row form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Description</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  class='form-control' type='text' name='description' id='description' required='required'  placeholder ="Describtion Of The Item" />
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Prix</label>
                        <div class='col-sm-10 col-md-6'>
                            <input type='text' name='price' class='form-control'  id='price' required='required'  placeholder ="Price Of The Item"/>
                        </div>
                    </div>
                    <!-- End Price Field -->
                      <!-- Start Country Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Pays</label>
                        <div class='col-sm-10 col-md-6'>
                            <input type='text' name='country' class='form-control'  id='country' required='required'  placeholder ="Country Of Made "/>
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Statut</label>
                        <div class='col-sm-10 col-md-6'>
                            <select name="status" id="status">
                                <option value="0">...</option>
                                <option value="1">Neuf</option>
                                <option value="2"> Comme Neuf</option>
                                <option value="3">D'occasion</option>
                                <option value="4">Très Ancien</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                       <!-- Start Member Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Membere</label>
                        <div class='col-sm-10 col-md-6'>
                            <select name="member" id="member">
                              <option value="0">...</option>
                                <?php
                                    $stmt=$con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users=$stmt->fetchAll();
                                    foreach ($users as $user)
                                     {
                                      echo  "<option value='".$user['UserID']."'>".$user['UserName']."</option>";
                                     }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Field -->
                       <!-- Start Category Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Catégorie</label>
                        <div class='col-sm-10 col-md-6'>
                            <select name="category" id="category">
                            <option value="0">...</option>
                                <?php
                                    $mainCats= getAllFrom("*" , "categories" , "WHERE Parent=0", "" , "ID") ;
                                    foreach ($mainCats as $cat)
                                     {
                                        echo  "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                        $childCats= getAllFrom("*" , "categories" , "WHERE Parent={$cat['ID']}", "" , "ID") ;
                                        foreach ( $childCats as $child)
                                        {
                                            echo  "<option value='".$child['ID']."'> ---  ".$child['Name']."</option>";
                                        }
                                     }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category Field -->
                    <!-- Start Tag Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Étiquettes</label>
                        <div class='col-sm-10 col-md-6'>
                            <input type='text' name='tags' class='form-control'  id='tags' placeholder ="Veuillez Séparer les Étiquettes par une Virgule (,)"/>
                        </div>
                    </div>
                    <!-- End Tag Field -->
                    <!-- Start Submit Button -->
                <div class='row form-control-lg'>
                    <div class='col-sm-10 offset-sm-2'>
                        <input type='submit' value='Ajouter un Nouvel Article' class='btn btn-primary btn-sm'/>
                    </div>
                </div>
                <!-- End Submit button -->
        </form>
    </div>

    <?php }elseif($do =='Insert'){

            if($_SERVER['REQUEST_METHOD']=='POST'){

                echo"<h1 class='text-center'>Insérer un Nouvel Article</h1>";
                echo "<div class='container'>";
             
                //Upload variables.

        
        $imgName =$_FILES['Image']['name'];
        $imgType =$_FILES['Image']['type'] ;
        $imgTmp  =$_FILES['Image']['tmp_name'];
        $imgSize =$_FILES['Image']['size'];
        
        // List Of Allowed Typed Extensions to Upload .
        $imgAllowedExtensions=array("jpeg","jpg","png","gif");


       // Get the extension of your image
        $imgNameParts = explode('.', $imgName);
        $imgExtension = strtolower(end($imgNameParts));  
        
        

            //Get variables from Form 

            
            $name     = $_POST['name'];
            $desc     = $_POST['description'];
            $price    = $_POST['price'];
            $country  = $_POST['country'];
            $status   = $_POST['status'];
            $member   = $_POST['member'];
            $cat      = $_POST['category'];
            $tags      = $_POST['tags'];
            

            //validate the form
            $formErrors=array();

            if(empty($name)){

                $formErrors[]="Le nom ne peut pas être <strong>Vide</strong>";

            }
            if(empty($desc)){

                $formErrors[]="La description ne peut pas être <strong>Vide</strong>";
                    
            }

            if(empty($price)){
                $formErrors[]="Le prix de l\'article ne doit pas être <strong> vide.</strong>";
            }

            if(empty($country)){
                $formErrors[]="Le pays ne peut pas être <strong>Vide</strong>";
            }

            if($status==0){
                $formErrors[]="Vous devez choisir le <strong>Statut</strong>";
            }
            if($member==0){
                $formErrors[]="Vous devez choisir le <strong>Membre</strong>";
            }
            if($cat==0){
                $formErrors[]="Vous devez choisir la <strong>Catégorie</strong>";
            }
            if(!empty($imgName) && ! in_array( $imgExtension,$imgAllowedExtensions)){
                $formErrors[]="Cette extension n'est pas <strong>Autorisée</strong>";
               }
        
               if(empty($imgName)){
                $formErrors[]="L'image est <strong>Requise</strong>";
               }
        
               if($imgSize > 2097152){
                $formErrors[]="L'image ne peut pas dépasser <strong>2MB</strong>";
               }


            // Loop Into Error array And Echo it 
            foreach ($formErrors as $error) {
            
                echo "<div class='alert alert-danger'>". $error."</div>" ;
                
            }
            // check that there is no error and  proceed the Update Process.
            if(empty($formErrors)){
              
                $img= rand(0,100000) .'_'.$imgName;
                $imgUrl = "uploads/images/" . $img;
               
                
                if (move_uploaded_file($imgTmp , $imgUrl)) {
                    echo "Fichier téléchargé avec succès.";
                } else {
                    echo "Erreur lors du téléchargement du fichier.";
                    print_r($_FILES['Image']);
                }
                   
        
                // update the database with the Info.

                        $stmt= $con->prepare("Insert Into items ( Name ,Image ,Description , Price , Country_Made, Status, Add_Date,Member_ID,Cat_ID ,Tags) VALUES (:zname,:zimg , :zdesc , :zprice , :zcountry ,:zstatus, now(),:zmember,:zcat ,:ztags)");
                        $stmt->execute(array(
                                    'zname'     => $name,
                                    'zimg'      => $img,
                                    'zdesc'     => $desc,
                                    'zprice'    => $price,
                                    'zcountry'  => $country,
                                    'zstatus'   => $status,
                                    'zmember'   => $member,
                                    'zcat'      => $cat,
                                    'ztags'     => $tags
                                    ));

                            //echo success message.

                        $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() .'  Enregistrement a été inséré</div>';
                        redirectHome($theMsg,'back');
                
                    }
                
            }else{
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page</div>";
                redirectHome($theMsg);
                echo "</div>";
            }
            echo '</div>';

    }elseif($do =='Edit'){
        $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
    
        $stmt = $con -> prepare(" Select * FROM items Where Item_ID = ? ");

        $stmt->execute(array($itemid));
        $item= $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0) { ?>
             <h1 class='text-center'>Modifier l'Article</h1>
        <div class='container'>
            <form action='?do=Update'  method="POST" enctype="multipart/form-data">
                <input type='hidden' name='itemid' value='<?php echo $itemid ?>'>
                      <div class='row form-control-lg d-flex justify-content-center align-items-center   '>
                                   
                                    <div class='item-Edit-img'>
                                        <?php if(empty($item['Image'])){

                                            echo " <img  class=' img-thumbnail img-fluid  mx-auto  d-block  ' src='uploads/default-product.png' alt='Current Image' />";
                                        }else{
                                         echo "<img  class=' img-thumbnail img-fluid '  src='uploads/images/".$item['Image']."' alt='Current Image' />";
                                        }
                                         ?>
                                           </span>
                                     </div>
                            </div>
                         <!-- Start Name Field -->
                        <div class='row  form-control-lg'>
                            <label  class='col-sm-2 col-form-label'>Nom</label>
                            <div class='col-sm-10 col-md-6'>
                                <input type='text' name='name' class='form-control' id='name' required='required'  placeholder =" Nom de l'Article" value="<?php echo $item['Name'];?>"/>
                            </div>
                        </div>
                        <!-- End  Name Field -->
                        <!-- Start Image field  -->
                        <div class='row form-control-lg'>
                            <label class='col-sm-2 col-form-label'>Image</label>
                            <div class='col-sm-10 col-md-6'>
                                <input type='file' name='Image' class='form-control'/>
                            </div>
                         </div>
                          <!-- End Image field -->
                        <!-- Start Description Field -->
                    <div class='row form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Description</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  class='form-control' type='text' name='description' id='description' required='required'  placeholder ="Description de l'Article" value="<?php echo $item['Description'];?>" />
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Price</label>
                        <div class='col-sm-10 col-md-6'>
                            <input type='text' name='price' class='form-control'  id='price' required='required'  placeholder ="Prix de l'Article" value="<?php echo $item['Price'];?>"/>
                        </div>
                    </div>
                    <!-- End Price Field -->
                      <!-- Start Country Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Pays</label>
                        <div class='col-sm-10 col-md-6'>
                            <input type='text' name='country' class='form-control'  id='country' required='required'  placeholder ="Pays de Fabrication " value="<?php echo $item['Country_Made'];?>"/>
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Statut</label>
                        <div class='col-sm-10 col-md-6'>
                            <select name="status" id="status">
                                <option value="1" <?php if($item['Status']=="1"){echo 'selected';}?>>Neuf</option>
                                <option value="2" <?php if($item['Status']=="2"){echo 'selected';}?>>Comme Neuf</option>
                                <option value="3" <?php if($item['Status']=="3"){echo 'selected';}?>>D'occasion</option>
                                <option value="4" <?php if($item['Status']=="4"){echo 'selected';}?>>Très Ancien</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                       <!-- Start Member Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Membre</label>
                        <div class='col-sm-10 col-md-6'>
                            <select name="member" id="member">
                                <?php
                                    $stmt=$con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users=$stmt->fetchAll();
                                    foreach ($users as $user)
                                     {
                                      echo  "<option value='".$user['UserID']."'";
                                      if($item['Member_ID']==$user['UserID']) {echo 'selected';}
                                      echo ">" .$user['UserName']. "</option>";

                                     }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Field -->
                       <!-- Start Category Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Catégorie</label>
                        <div class='col-sm-10 col-md-6'>
                            <select name="category" id="category">
                            <option value="0">...</option>
                                <?php
                                    $mainCats= getAllFrom("*" , "categories" , "WHERE Parent=0", "" , "ID") ;
                                    foreach ($mainCats as $cat)
                                     {
                                        echo  "<option value='".$cat['ID']."'";
                                        if ($item['Cat_ID']==$cat['ID']) {echo 'selected';}
                                        echo">".$cat['Name']."</option>";
                                        $childCats= getAllFrom("*" , "categories" , "WHERE Parent={$cat['ID']}", "" , "ID") ;
                                        foreach ( $childCats as $child)
                                        {
                                            echo "<option value='".$child['ID']."'";
                                            if ($item['Cat_ID']==$child['ID']) {echo 'selected';}
                                            echo "> --- ".$child['Name'] ."</option>";
                                        }
                                     }
                                ?>
                            </select>

                          
                        </div>
                    </div>
                    <!-- End Category Field -->
                       <!-- Start Tag Field -->
                    <div class='row  form-control-lg'>
                        <label  class='col-sm-2 col-form-label'>Étiquettes</label>
                        <div class='col-sm-10 col-md-6'>
                            <input type='text' name='tags' class='form-control'  id='tags' placeholder ="Veuillez Séparer les Étiquettes par une Virgule (,)" value="<?php echo $item['Tags'];?>"/>
                        </div>
                    </div>
                    <!-- End Tag Field -->
                     <!-- Start Submit Button -->
                <div class='row form-control-lg'>
                    <div class='col-sm-10 offset-sm-2'>
                        <input type='submit' value='Enregistrer' class='btn btn-primary'/>
                    </div>
                </div>
                <!-- End Submit button -->
        </form><?php
        
        //Manage Members Page 

        //select all comments concerned to its item

        $stmt = $con -> prepare(" Select comments.*  ,users.UserName
                                  FROM  comments 
                                    INNER JOIN 
                                        users
                                   ON
                                       users.UserID=comments.user_id
                                    WHERE item_id=? ");
        $stmt->execute(array($itemid));

        // Assign To Variable
        $rows=$stmt->fetchAll();

        if(!empty($rows)){
    ?>
      <h1 class='text-center'>Gérer  [<?php echo $item['Name'];?>] les Commentaires </h1>
       <div class="table-responsive">
        <table class='table text-center table-bordered manageTable'>
        <tr >
                <td>Commentaire</td> 
                <td>Date d'Ajout</td>
                <td>Nom d'Utilisateur</td>
                <td>Contrôle</td> 
      </tr>
                <?php
                        foreach($rows as $row){
                            echo '<tr>';
                                echo '<td>'.$row['comment'].'</td>';
                                echo '<td>'.$row['comment_date'].'</td>'; 
                                echo '<td>'.$row['UserName'].'</td>';
                                echo "<td>
                                    <a href='comments.php?do=Edit&comid=". $row['c_id']."' class='btn btn-sm btn-success'><i class='fa fa-edit'></i>Modifier</a>
                                    <a href='comments.php?do=Delete&comid=". $row['c_id']."' class='btn btn-sm btn-danger confirm'><i class='fa fa-close'></i>Supprimer</a>";

                                if($row['status']==0){    
                                    echo "<a href='comments.php?do=Approve&comid=". $row['c_id']."'class='btn btn-sm btn-info activate'><i class='fa fa-check'></i>Activater</a>";
                                }   
                            echo "</td>";
                            echo '</tr>';
                        }
                ?>      
        </table>
       </div> 

       <?php } ?>  

    </div>

         <?php } else{

                echo "<div class='container'>";
                $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas</div>";
                redirectHome($theMsg);
                echo "</div>";

        }

    }elseif($do =='Update'){

        echo"<h1 class='text-center'>Mettre à Jour les Articles</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD']=='POST'){

                  
        //Upload variables.

        
        $imgName    =$_FILES['Image']['name'];
        $imgType    =$_FILES['Image']['type'] ;
        $imgTmpPath =$_FILES['Image']['tmp_name'];
        $imgSize    =$_FILES['Image']['size'];
        
        // List Of Allowed Typed Extensions to Upload .
        $imgAllowedExtensions=array("jpeg","jpg","png","gif");

        $securedName= rand(0,100000) .'_'.$imgName;
        $imgPath = 'uploads/images/' . $securedName;

       // Get the extension of your image
        $imgNameParts = explode('.', $imgName);
        $imgExtension = strtolower(end($imgNameParts));  
        
        

           //Get variables from Form 

           $id          = $_POST['itemid'];
           $name        = $_POST['name'];
           $desc        = $_POST['description'];
           $price       = $_POST['price'];
           $country     = $_POST['country'];
           $status      = $_POST['status'];
           $cat         = $_POST['category'];
           $member      = $_POST['member'];
           $tags        = $_POST['tags'];
           
           //validate the form
           $formErrors=array();

           if(empty($name)){

               $formErrors[]="Le nom ne peut pas être <strong>Vide</strong>";

           }
           if(empty($desc)){

               $formErrors[]="La description ne peut pas être <strong>Vide</strong>";
                   
           }

           if(empty($price)){
               $formErrors[]="Le prix de l'article ne doit pas être <strong>Vide</strong>";
           }

           if(empty($country)){
               $formErrors[]="Le pays ne peut pas être <strong>Vide</strong>";
           }

           if($status==0){
               $formErrors[]="Vous devez choisir le <strong>Statut</strong>";
           }
           if($member==0){
               $formErrors[]="Vous devez choisir le  <strong>Membre</strong>";
           }
           if($cat==0){
               $formErrors[]="Vous devez choisir la <strong>Catégorie</strong>";
           }
           if(!empty($imgName) && ! in_array( $imgExtension,$imgAllowedExtensions)){
            $formErrors[]="Cette extension n'est pas <strong>Autorisée</strong>";
           }
    
         
           if($imgSize > 2097152){
            $formErrors[]="L'image ne peut pas dépasser <strong>2MB</strong>";
           }


           // Loop Into Error array And Echo it 
           foreach ($formErrors as $error) {
           
               echo "<div class='alert alert-danger'>". $error."</div>" ;
               
           }
        
           // check that there is no error and  proceed the Update Process.
           if(empty($formErrors)){

               // Fetch the current avatar from the database
               $stmt = $con->prepare("SELECT Image FROM items WHERE Item_ID = ?");
               $stmt->execute(array($id));
               $countImage= $stmt->rowCount();

               if($countImage > 0){
                   $currentImg=$stmt->fetchColumn();
               }
            
              // Check if a new file has been uploaded
              if (!empty($_FILES['Image']['name'])) {

                    // Move the uploaded file to the server
                        if (move_uploaded_file($imgTmpPath, $imgPath)) {
                            // File uploaded successfully, use the new file
                            $img = $securedName;
                        } else {
                            echo "<div class='alert alert-danger'>Erreur lors du téléchargement du fichier.</div>";
                            $img = $currentImg; // Keep the old avatar if upload fails
                        }
            
                } else {
                    // No new file uploaded, keep the existing one
                    $img = $currentImg;
                }

             // update the database with the Info.

            $stmt = $con->prepare(" UPDATE items SET  Name = ?, Image = ? , Description = ?, Price=? , Country_Made=?, Status = ?,Cat_ID = ? ,Member_ID = ?, Tags = ? WHERE Item_ID = ? ");
            $stmt->execute(array($name,$img,$desc,$price,$country,$status,$cat,$member,$tags,$id));

            //echo success message.

          $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() . ' Enregistrement mis à jour</div>';
          redirectHome($theMsg,'back', 4);

           }
            
        }else{

            $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page</div>";
            redirectHome($theMsg);
        }
       echo '</div>';

    }elseif($do =='Delete'){
        //Delete Item 
        echo"<h1 class='text-center'>Supprimer L'Article</h1>";
        echo "<div class='container'>";


        $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
    
        // $stmt = $con -> prepare(" Select * FROM users Where UserID = ? LIMIT 1 ");

        // $stmt->execute(array($userid));
       
        // $count = $stmt->rowCount();

        $check=checkItem('Item_ID','items',$itemid);

        if($check > 0) { 

            $stmt = $con -> prepare(" Delete FROM items Where Item_ID = :zid ");
            $stmt->bindParam(":zid",$itemid);
            $stmt->execute();
            //echo success message.

          $theMsg='<div class="alert alert-success">'. $stmt->rowCount() . ' Enregistrement a été supprimé</div>';
          redirectHome($theMsg,'back');

        }else{
            $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas</div>";
            redirectHome($theMsg);
        }
      echo  '</div>';
    
    }elseif($do =='Approve'){
         
         //Approve Item page
         echo"<h1 class='text-center'>Approuver l'Article</h1>";
         echo "<div class='container'>";
 
 
         $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
     
        
 
         $check=checkItem('Item_ID','items',$itemid);
 
         if($check > 0) { 
 
             $stmt = $con -> prepare(" Update items set Approve=1 Where Item_ID = ? ");
             $stmt->execute(array($itemid));
            
             //echo success message.
 
           $theMsg='<div class="alert alert-success"> L\'article a été approuvé</div>';
           redirectHome($theMsg,'back');
 
         }else{
             $theMsg= "<div class='alert alert-danger'> Cet ID n'existe pas</div>";
             redirectHome($theMsg,'back');
         }
       echo  '</div>';
        
    
    }
    include $tpl."footer.php";

    }else {
    
    header('Location:../login.php'); // Redirect to the login page
    exit();                       // Terminate the script to prevent further execution

    }
    ob_end_flush();
?>        