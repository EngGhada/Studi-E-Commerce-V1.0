<?php

  // Category Page
  // ==============

    ob_start();  //Output Buffering Start
    session_start();

    if (isset($_SESSION['UserName'])) {

    $pageTitle='Categories';

    include 'init.php';

    $do = isset($_GET['do'])? $_GET['do'] :'Manage';
    if($do =='Manage') {

       $sort='asc';
       $sort_array=array('asc','desc');
       if(isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array)){
        $sort = $_GET['sort'];
       }
       $stmt2 =$con->prepare("SELECT * FROM categories WHERE Parent=0 ORDER BY Ordering $sort");
       $stmt2->execute();

      // Fetch the Data 
       $cats=$stmt2->fetchAll();
        
       if(!empty($cats)){
    
       ?>
        <h1 class='text-center'>Gérer les Catégories</h1>
       <div class='container categories'>
             <div class="card">
               <div class="card-header">
                <i class='fa fa-edit fa-sm'></i>  Gérer les Catégories
                 <div class='option float-end'>
                   
                    <i class="fa fa-sort fa-sm"></i> Trier : [
                    <a  class='<?php if($sort=='asc'){echo 'active';}?>' href="?sort=asc">Croissant</a> |
                    <a  class='<?php if($sort=='desc'){echo 'active';}?>'href="?sort=desc">Décroissant </a>]
                    
                    <i class="fa fa-eye fa-sm"></i> Affichage : [
                    <span class='active' data-view=full> Complet </span>|
                    <span data-view=classic> Classique </span>]
                 </div>
               </div>
               <div class="card-body">
                
                 <?php
                    foreach ($cats as $cat) {
                        echo "<div class='cat'>";
                        echo "<div class='hidden-buttons'>";

                            echo "<a href='categories.php?do=Edit&catid=".$cat['ID']."' class='btn btn-xs btn-primary'><i class='fa fa-edit fa-xs'></i> Modifier</a>";
                            echo "<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-xs btn-danger '><i class='fa fa-close fa-xs'></i> Supprimer</a>";
                        
                            echo "</div>";
                        echo '<h3>'. $cat['Name'].'</h3>';
                        echo "<div class='full-view'>";

                            echo '<p>';if($cat['Description']==''){echo ' Cette Catégorie n\'a pas de Description ';}else{ echo $cat['Description']; } echo '</p>';
                            if($cat['Visibility']==1){ echo '<span class="visibility"><i class="fa fa-eye fa-xs"></i> Masqué </span>';}
                            if($cat['Allow_Comment']==1){echo '<span class="commenting"><i class="fa fa-close fa-xs"></i> Les Commentaires sont Désactivés </span>';}
                            if($cat['Allow_Ads']==1){echo '<span class="advertising"> <i class="fa fa-close fa-xs"></i> Les Annonces sont Désactivées </span>' ;}
                        
                                //Get Child categories 
                       
                                $childCats=getAllFrom("*","categories","WHERE Parent= {$cat['ID']}","","ID","ASC");
                                if(!empty($childCats))
                                {
                                    echo '<h4 class="child-head">Sous-catégorie</h4>';
                                    echo '<ul class="list-unstyled child-cats">';
                                    foreach ($childCats as $c) {
                                    echo'<li class="child-link">
                                            <a href="categories.php?do=Edit&catid='.$c['ID'].'">' .$c['Name'].'</a>
                                            <a href="categories.php?do=Delete&catid='.$c['ID'].'" class="show-delete confirm">Supprimer</a>
                                        </li>';
                                }
                                echo '</ul>';
                               }   
                        echo "</div>";
                    
                        echo '</div>';

                    echo '<hr>';

                        
                    }
                 ?>
               </div>
             </div> 
            <a class=" add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Ajouter une Nouvelle Catégorie</a> 
        </div>
        <?php }else{ 
                        echo '<div class="container">';
                         echo '<div class="nice-message">Il n\'y a aucune catégorie à afficher</div>';
                         echo '<a class="add-category btn btn-sm btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Ajouter une Nouvelle Catégorie</a>';
                        echo '</div>';
                        } ?>
  <?php
    }elseif($do =='Add'){ ?>

    <h1 class='text-center'>Ajouter une Nouvelle Catégorie</h1>

    <div class='container edit-category'>

        <form action='?do=Insert'  method="POST">
        
            <div class='row  form-control-lg'>
                
                        <!-- Start Name Field -->
            
                    <label  class='col-sm-2 col-form-label'>Nom</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='name' class='form-control' id='name' autocomplete='off' required='required' placeholder ="Nom de la Catégorie"/>
                    </div>
                </div>
                    <!-- End  Name Field -->
                    <!-- Start Description Field -->
                <div class='row form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Description</label>
                    <div class='col-sm-10 col-md-6'>
                        <input  class='form-control' type='text' name='description' id='description' placeholder ="Description de la Catégorie" />
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Ordering Field -->
                <div class='row  form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Ordre</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='ordering' class='form-control'  id='ordering'   placeholder ="Numéro pour Organiser les Catégories"/>
                    </div>
                </div>
                <!-- End Ordering Field -->
                <!-- Start Category Type -->
                 <div class='row  form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Parent ?</label>
                    <div class='col-sm-10 col-md-6'>
                        <select name="parent">
                            <option value="0">None</option>
                           <?php
                                $allCats=getAllFrom("*" , "categories" , " WHERE Parent = 0", "", "ID" , 'ASC');
                                foreach ($allCats as $cat) {

                                    echo "<option value=' ". $cat['ID'] . "'> ".$cat['Name']." </option>" ;

                                }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Type -->
                <!-- Start Visibility Field -->
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>Visible</label>
                    <div class='col-sm-10 col-md-6'>
                    <div>
                        <input id='vis-yes' type='radio' name='visibility' value='0'   checked />
                        <label for="vis-yes">Oui</label>
                    </div>
                    <div>
                        <input id='vis-no' type='radio' name='visibility' value='1'  />
                        <label for="vis-no">Non</label>
                    </div>
                    </div>
                </div>
                <!-- End Visibility Field -->
                <!-- Start Commenting Field -->
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>Autoriser les Commentaires</label>
                    <div class='col-sm-10 col-md-6'>
                    <div>
                        <input id='com-yes' type='radio' name='commenting' value='0'  checked />
                        <label for="com-yes">Oui</label>
                    </div>
                    <div>
                        <input id='com-no' type='radio' name='commenting' value='1'/>
                        <label for="com-no">Non</label>
                    </div>
                    </div>
                </div>
                <!-- End Commenting Field -->
                    <!-- Start Allow-Ads Field -->
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'> Autoriser les Annonces</label>
                    <div class='col-sm-10 col-md-6'>
                    <div>
                        <input id='ads-yes' type='radio' name='ads' value='0'  checked />
                        <label for="ads-yes">Oui</label>
                    </div>
                    <div>
                        <input id='ads-no' type='radio' name='ads' value='1'/>
                        <label for="ads-no">Non</label>
                    </div>
                    </div>
                </div>
             <!-- End Allow-Ads Field -->
            <div class='row form-control-lg'>
                <div class='col-sm-10 offset-sm-2'>
                    <input type='submit' value=' + Add New Category' class='btn btn-primary btn-lg'/>
                </div>
            </div>
            <!-- End button -->
    </form>
</div>
     
    <?php
    }elseif($do =='Insert'){
        
    if($_SERVER['REQUEST_METHOD']=='POST'){

        echo"<h1 class='text-center'>Insérer une Nouvelle Catégorie</h1>";
        echo "<div class='container'>";

       //Get variables from Form 

       
       $name     = $_POST['name'];
       $desc     = $_POST['description'];
       $order    = $_POST['ordering'];
       $parent    = $_POST['parent'];
       $visible  = $_POST['visibility'];
       $comment  = $_POST['commenting'];
       $ads      = $_POST['ads'];

       //validate the form
       // check if category exists ib DB .
       
        $check=checkItem("Name","Categories",$name);
        if( $check==1){
            $theMsg= "<div class='alert alert-danger'>Désolé, cette catégorie existe déjà</div>";
            redirectHome($theMsg,'back');

        } else{
         // Insert Category Info in Database .

                $stmt = $con->prepare("Insert  Into categories ( Name , Description ,Parent, Ordering , Visibility, Allow_Comment, Allow_Ads ) VALUES (:zname , :zdesc,:zparent , :zorder , :zvisible ,:zcomment, :zads)");
                $stmt->execute(array(
                            'zname'   => $name,
                            'zdesc'   => $desc ,
                            'zparent' => $parent ,
                            'zorder'   => $order,
                            'zvisible'   => $visible,
                            'zcomment'=>$comment,
                            'zads'    =>$ads
                            ));

                    //echo success message.

                $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() .' Une Nouvelle Catégorie a été Insérée</div>';
                redirectHome($theMsg,'back');
          
            }
    
    }else{
        echo "<div class='container'>";
        $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page</div>";
        redirectHome($theMsg,'back');
        echo "</div>";
    }
   echo '</div>';


    }elseif($do =='Edit'){

        //check if Get Request catid Is Numeric & Get its Integer value.  
        $catid=isset($_GET['catid']) && is_numeric($_GET['catid'])?intval($_GET['catid']):0;
         
        // Select All Data Depend On This ID
        $stmt = $con -> prepare(" Select * FROM categories Where ID = ?");
        //execute Query
        $stmt->execute(array($catid));
        //Fetch The DaTa
        $cat= $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
       // If there's such ID show the form
        if($count > 0) { ?>

            <h1 class='text-center'>Modifier les Catégories</h1>
            <div class='container edit-category'>
            <form action='?do=Update' method="POST">
             
          
        <input type='hidden' name='catid' class='form-control' id='catid' value="<?php echo $catid; ?>" />
        
        <div class='row  form-control-lg '>
            
                    <!-- Start Name Field -->
        
                <label  class='col-sm-2 col-form-label'>Nom</label>
                <div class='col-sm-10 col-md-6'>
                    <input type='text' name='name' class='form-control' id='name' required='required' placeholder ="Nom de la Catégorie" value="<?php echo $cat['Name'];?>"/>
                </div>
            </div>
                <!-- End  Name Field -->
                <!-- Start Description Field -->
            <div class='row form-control-lg'>
                <label  class='col-sm-2 col-form-label'>Description</label>
                <div class='col-sm-10 col-md-6'>
                    <input type='text' class='form-control'  name='description' id='description' placeholder ="Description de la Catégorie" value="<?php echo $cat['Description']; ?>" />
                </div>
            </div>
            <!-- End Description Field -->
            <!-- Start Ordering Field -->
            <div class='row  form-control-lg'>
                <label  class='col-sm-2 col-form-label'>Ordre</label>
                <div class='col-sm-10 col-md-6'>
                    <input type='text' name='ordering' class='form-control'  id='ordering'   placeholder ="Numéro pour Organiser les Catégories" value="<?php echo $cat['Ordering'];?>"  />
                </div>
            </div>
            <!-- End Ordering Field -->
              <!-- Start Category Type -->
              <div class='row  form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Parent ?</label>
                    <div class='col-sm-10 col-md-6'>
                        <select name="parent">
                            <option value="0">None</option>
                           <?php
                                $allCats=getAllFrom("*" , "categories" , " WHERE Parent = 0", "", "ID" , 'ASC');
                                foreach ($allCats as $c) {

                                    echo "<option value=' ". $c['ID'] . "'";
                                    if($c['ID'] == $cat['Parent'])
                                        {
                                            echo "selected";
                                        }
                                    echo ">" .$c['Name']. " </option>" ;

                                }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Type -->
            <!-- Start Visibility Field -->
            <div class='row form-control-lg'>
                <label class='col-sm-2 col-form-label'>Visible</label>
                <div class='col-sm-10 col-md-6'>
                <div>
                    <input id='vis-yes' type='radio' name='visibility' value="0"  <?php if ($cat['Visibility']==0){echo 'checked';}?>  />
                    <label for="vis-yes">Oui</label>
                </div>
                <div>
                    <input id='vis-no' type='radio' name='visibility' value="1" <?php if ($cat['Visibility']==1){echo 'checked';}?>  />
                    <label for="vis-no">Non</label>
                </div>
                </div>
            </div>
            <!-- End Visibility Field -->
            <!-- Start Commenting Field -->
            <div class='row form-control-lg'>
                <label class='col-sm-2 col-form-label'>Autoriser les Commentaires</label>
                <div class='col-sm-10 col-md-6'>
                <div>
                    <input id='com-yes' type='radio' name='commenting' value="0"  <?php if ($cat['Allow_Comment']==0){echo 'checked';}?>  />
                    <label for="com-yes">Oui</label>
                </div>
                <div>
                    <input id='com-no' type='radio' name='commenting' value="1" <?php if ($cat['Allow_Comment']==1)  {echo 'checked';}?> />
                    <label for="com-no">Non</label>
                </div>
                </div>
            </div>
            <!-- End Commenting Field -->
                <!-- Start Allow-Ads Field -->
            <div class='row form-control-lg'>
                <label class='col-sm-2 col-form-label'> Autoriser les Annonces</label>
                <div class='col-sm-10 col-md-6'>
                <div>
                    <input id='ads-yes' type='radio' name='ads' value="0" <?php if ($cat['Allow_Ads']==0){echo 'checked';}?>/>
                    <label for="ads-yes">Oui</label>
                </div>
                <div>
                    <input id='ads-no' type='radio' name='ads' value="1" <?php if ($cat['Allow_Ads']==1){echo 'checked';}?>/>
                    <label for="ads-no">Non</label>
                </div>
                </div>
            </div>
         <!-- End Allow-Ads Field -->
        <div class='row form-control-lg'>
            <div class='col-sm-10 offset-sm-2'>
                <input type='submit' value='Enregistrer' class='btn btn-primary btn-lg'/>
            </div>
        </div>
        <!-- End button -->
</form>

   
            </div>
        
                 <?php 
                     // If there's No such ID show error message.
                    } else{

                        echo "<div class='container'>";
                        $theMsg= "<div class='alert alert-danger'>Cet ID n'existe pas</div>";
                        redirectHome($theMsg);
                        echo "</div>";

                }
   
    }elseif($do =='Update'){

         
        echo"<h1 class='text-center'> Mettre à Jour la Catégorie</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD']=='POST'){

        
           //Get variables from Form 

           $id          = $_POST['catid'];
           $name        = $_POST['name'];
           $desc        = $_POST['description'];
           $order       = $_POST['ordering'];
           $parent      = $_POST['parent'];
           $visible     = $_POST['visibility'];
           $comment     = $_POST['commenting'];
           $ads         = $_POST['ads'];



          
             // update the database with the Info.
            $stmt = $con->prepare(" UPDATE 
                                            categories 
                                    SET 
                                         Name = ? ,
                                        Description = ?,
                                        Parent=?,
                                        Ordering = ?, 
                                        Visibility = ?,
                                        Allow_Comment=?,
                                        Allow_Ads=? 
                                    WHERE 
                                        ID = ? ");
            $stmt->execute(array($name, $desc,$parent ,$order,$visible,$comment,$ads,$id ));
            //echo success message.
            $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() . ' Enregistrement Mis à Jour</div>';
            redirectHome($theMsg,'back', 2);

           
            
        }else{

            $theMsg = "<div class='alert alert-danger'>Vous ne pouvez pas naviguer directement sur cette page</div>";
            redirectHome($theMsg);
        }
       echo '</div>';

    }elseif($do =='Delete'){

         //Delete Category page
         echo"<h1 class='text-center'>Supprimer la Catégorie</h1>";
         echo "<div class='container'>";
 
 
         $catid=isset($_GET['catid']) && is_numeric($_GET['catid'])?intval($_GET['catid']):0;
     
         // $stmt = $con -> prepare(" Select * FROM users Where UserID = ? LIMIT 1 ");
 
         // $stmt->execute(array($userid));
        
         // $count = $stmt->rowCount();
 
         $check=checkItem('ID','categories',$catid);
 
         if($check > 0) { 
 
             $stmt = $con -> prepare(" Delete FROM categories Where ID = :zid ");
             $stmt->bindParam(":zid",$catid);
             $stmt->execute();
             //echo success message.
 
           $theMsg='<div class="alert alert-success">'. $stmt->rowCount() . ' Enregistrement a été supprimé</div>';
           redirectHome($theMsg,'back');
 
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
?>