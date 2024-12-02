<?php 

ob_start();  //Output Buffering Start
session_start();
$pageTitle='Profile';
 
include 'init.php';

if(isset($_SESSION['User'])){
    $getUser = $con -> prepare(" Select * FROM  users Where UserName = ? ");   
    $getUser->execute(array($sessionUser));
    $info = $getUser -> fetch();
?>
<h1 class="text-center">Mon Profil</h1>
<div class="information block">
   <div class="container">
      <div class="card ">
        <div class="card-header bg-primary text-white">
            Mes Informations
        </div>
        <div class="card-body">
          <ul class="list-unstyled">
                <li> 
                    <i class="fa fa-unlock-alt fa-fw"></i>
                    <span> Nom de Connexion </span> : <?php echo $info['UserName'] ;?><br>
                </li>
                <li>
                    <i class="fa fa-envelope fa-fw"></i>
                    <span> E-mail     </span> : <?php echo $info['Email'] ;?><br>
                </li>
                <li>
                    <i class="fas fa-user fa-fw"></i>
                    <span> Nom Complet   </span> : <?php echo $info['FullName'] ;?><br>
                </li>
                <li>
                    <i class="fas fa-calendar fa-fw"></i>
                    <span> Date d'Inscription </span> : <?php echo $info['Date'] ;?><br>
                </li>
           </ul>
           <a href='editprofile.php?do=Edit&userid=<?php echo $sessionUserID ; ?>'  class='btn btn-sm btn-success' ><i class='fa fa-edit'></i>Modifier les Informations
           </a>
        </div>
      </div>
   </div>
</div>

<div  id = "myitems" class="my-ads block">
   <div class="container">
      <div class="card ">
        <div class="card-header bg-primary text-white">
             Mes Articles
        </div>
        <div class="card-body">
                <?php
                    
                    $myItems=getAllFrom("*","items","WHERE Member_ID={$info['UserID']}","","Item_ID");
                    if(!empty($myItems)){
                    
                        echo" <div  class='row  form-control-lg'>";  

                        foreach ($myItems as $item ) {
                        echo "<div class='col-sm-6 col-md-3'>";
                        echo "<div class='card item-box  cardProfile'>";
                           
                            echo "<span class='price-tag'> $".$item['Price']."</span>";
                            echo "<img  class=' card-img-top image-responsive' src='admin/uploads/default.jpg' alt='image not found' />";
                            echo "<div class='caption'>";
                                echo "<h5><a href='items.php?itemid=".$item['Item_ID']."'>".$item['Name']."</a></h3>";
                                echo "<p class='card-text text-truncate'>".$item['Description']."</p>"; 
                                if($item['Approve']==0)
                                {
                                    echo '<span class="approve-status">En attente d\'approbation</span>';  
                                }else{
                                    echo "<button class='add-to-cart btn btn-sm btn-secondary' style='float: left;' data-item-id=".$item['Item_ID']."'> Ajouter au panier </button>";
                                }
                                echo "<div class='date'>".$item['Add_Date']."</div>";                   
                            echo '</div>';
                          
                        echo '</div>';
                        echo '</div>';
                      }
                    }else{
                        echo " Désolé, aucune annonce à afficher.";
                        if($info['RegStatus'] == 1){
                       echo "<a href='newad.php'>un Nouvel Annonce</a>";
                      }
                    }
                ?>
            </div>
        </div>
      </div>
   </div>
</div>

<div class="my-comments block">
   <div class="container">
      <div class="card ">
        <div class="card-header bg-primary text-white">
            Mes Derniers Commentaires
        </div>
        <div class="card-body">
          <?php
                //select all comments concerned to its item

                $myComments=getAllFrom("*","comments","WHERE user_id={$info['UserID']}","","c_id");
                if(!empty($myComments)){
                   foreach($myComments as $Comment) {
                    echo "<p>". $Comment['comment'] ."</p>";
                   }
                }else{
                        echo 'Il n\'y a aucun commentaire à afficher';
                }
            ?>
        </div>
      </div>
   </div>
</div>
<?php

    }else{
        header('Location:login.php');
        exit();
    }

?>
<?php include $tpl."footer.php";
 ob_end_flush();
?>

