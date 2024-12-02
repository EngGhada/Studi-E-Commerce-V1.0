<?php 
ob_start();  //Output Buffering Start
session_start();

$pageTitle='Afficher les Articles';
include 'init.php';

    $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
    
    $stmt = $con -> prepare(" Select 
                                    items.* ,
                                    categories.Name As Category_Name ,
                                    categories.Allow_Comment,
                                    users.UserName As User_Name
                                    
                                FROM 
                                     items
                                INNER JOIN 
                                    categories
                               ON categories.ID= items.Cat_ID 
                               INNER JOIN 
                                    users
                               ON users.UserID = items.Member_ID
                               WHERE 
                                   items.Item_ID = ?
                               AND  Approve = 1  ");

    $stmt->execute(array($itemid));
    
    $count=$stmt->rowCount();
    if($count>0){
        $item= $stmt->fetch();
?>
<h1 class="text-center"><?php echo $item['Name']; ?></h1>

<div class='container'>
    <div  class='row  form-control-lg'>

        <div class='col-md-3'>
        <?php 
            if(!empty($item['Image'])){
                echo "<img  class=' img-thumbnail img-fluid  mx-auto  d-block '  src='admin/uploads/images/".$item['Image']."' alt='image not found' />";
                }else{
                echo "<img  class=' img-thumbnail img-fluid  mx-auto  d-block '  src ='admin/uploads/default-product.png' alt='image not found' />";
            }
        ?>
          
        </div>
        
        <div class='col-md-9 item-info '>
           
                <h2><?php echo $item['Name']?></h2>
                <p class='card-text'><?php echo $item['Description']?></p> 
                <ul class="list-unstyled ">
                  <li>
                        <i class="fas fa-calendar fa-fw"></i>
                        <span> Date d'Ajout </span> : <?php echo $item['Add_Date']?>
                   </li> 
                   <li>
                        <i class="fas fa-coins"></i>
                        <span> Prix </span> <?php echo '  : $'. $item['Price']?>
                  </li>

                   <li>
                      <i class="fas fa-building fa-fw"></i>
                      <span> Fabriqué en </span> : <?php echo $item['Country_Made']?>
                   </li>
                  
                   <li>
                       <i class="fas fa-tags fa-fw"></i>
                       <span> Catégorie </span><a href="categories.php?pageid=<?php echo $item['Cat_ID']?>"> : <?php echo $item['Category_Name']?></a>
                  </li>
                   <li>
                        <i class="fas fa-user fa-fw"></i>
                        <span> Ajouté par</span><a href="#"> : <?php echo $item['User_Name']?></a>
                    </li>
                    <li class="tags-items">
                        <i class="fas fa-tags fa-fw"></i>
                        <span > Étiquettes </span> : 
                        <?php
                            $allTags= explode(",",$item['Tags']);
                            foreach ($allTags as $tag) {
                                $tag=str_replace(' ','',$tag);
                                $lowertag=strtolower($tag);
                                if(!empty($tag)){
                                  echo "<a href='tags.php?name={$lowertag}'>".$tag."</a>";
                                }
                            }
                        ?>
                    </li>
                </ul> 
        </div>
    </div> 
    
    <hr class="custom-hr">
   <?php
    if (isset($_SESSION['User'])){
        if($item['Allow_Comment']==0){
         ?>
    <!-- Start Add Comment -->
        <div class="row">
            <div class="col-md-3 offset-md-3">
               <div class='add-comment'>
                  <h4>Ajoutez Votre Commentaire</h4>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?itemid='.$item['Item_ID']?>" method="POST">
                        <textarea name='comment' required></textarea>
                        <input class="btn btn-primary"type="submit" value="Ajouter un Commentaire.">
                    </form>
                            <?php

                            if($_SERVER['REQUEST_METHOD']=='POST'){

                                    $comment=$_POST['comment'];
                                    $userid=$_SESSION['uid'];
                                    $itemid=$item['Item_ID'];

                            
                            if(!empty($comment)){

                                $stmt=$con->prepare("Insert Into comments (comment,status,comment_date,item_id,user_id) VALUES ( :zcomment , 0 , NOW() , :zitemid , :zuserid )");
                                $stmt->execute(array(
                                                    'zcomment'=>$comment,
                                                    'zuserid'=>$userid,
                                                    'zitemid'=>$itemid
                                                )); 
                                            $count= $stmt->rowCount();

                                                if($count>0){

                                                    $details = [
                                                        'Nom_de_connexion' => mb_convert_encoding($_SESSION['User'], 'UTF-8', 'auto'),
                                                        'un Nouveau Commentaire' => mb_convert_encoding($comment, 'UTF-8', 'auto'),
                                                        'L\'article ID' => mb_convert_encoding($itemid, 'UTF-8', 'auto'),
                                                        'Le Nom de L\'article' =>$item['Name']
                                                        
                                                    ];
                                                    
                                                    logActivity($_SESSION['uid'], "Ajouter un Nouveau Commentaire", $details);

                                                    $successMsg='<div class="alert alert-success  comment_msg" id="success-message ">Commentaire ajouté avec succès.</div>';  
                                                    echo $successMsg ;

                                                } 
                                                
                                              
                                 }else{
                                    echo '<div class="alert alert-danger  comment_msg"  > Veuillez d\'abord entrer votre commentaire. .</div>';
                                 }
                            }
                        ?>
                </div>
            </div>
        </div>
    <!-- End Add Comment -->
     <?php 
        }else{
            echo "<div class='alert alert-warning'>Les commentaires ne sont pas actifs pour cet article.</div>";
        }
   }else{
     echo "<div class='login-signup'>
                <a class='selected' href='login.php' data-class='login'>Connexion ou </a>
                      
                <a  href='login.php' data-class='signup'>Inscription</a>
                   Pour Ajouter un Commentaire.
            </div>";
    }
     ?>
    <hr class="custom-hr">
    <?php
                $stmt = $con -> prepare(" Select comments.*  , users.UserName As Member , users.Avatar As AvatarOfComment
                FROM  comments 
                        
                INNER JOIN 
                            users
                        ON
                            users.UserID=comments.user_id
                        WHERE 
                            item_id = ?
                        AND 
                            status = 1

                        ORDER BY c_id DESC");

                 $stmt->execute(array($item['Item_ID']));

                // Assign To Variable
                $comments = $stmt->fetchAll();
    ?>
    
              <?php  foreach($comments as $comment) { ?>
                 <div class="comment-box">
                    <div class="row">
                            <div class="col-sm-2 text-center"> 
                            <?php 
                                if(!empty($comment['AvatarOfComment'])){
                                    echo "<img  class='img-fluid  img-thumbnail rounded-circle mx-auto d-block '  src='admin/uploads/avatars/".$comment['AvatarOfComment']."' alt='image not found' />";
                                    }else{
                                    echo "<img  class=' img-fluid  img-thumbnail rounded-circle mx-auto d-block'  src ='layout/images/avatar.png' alt='image not found' />";
                                }
                                echo $comment['Member'];
                             ?>   
                                </div>
                                <div class="col-sm-10">
                                    <p class="lead"><?php echo $comment['comment']?></p>
                                </div>
                        </div>
                        <hr class="custom-hr">
                 </div>
                    
              <?php } ?>
 </div>
<?php }else{
       echo '<div class="alert alert-warning alert-dismissible fade show" id="warning-message comment_msg">Cet ID n\'existe pas ou l\'article n\'est pas encore activé.</div>';
    }    
?>
<?php include $tpl."footer.php";
    ob_end_flush();
?>

