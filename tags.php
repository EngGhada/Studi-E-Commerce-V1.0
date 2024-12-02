
<?php
ob_start();  //Output Buffering Start
session_start();
$pageTitle='les Catégories';
 
include 'init.php';

?>

    <div class='container'>
       
        <div  class='row justify-content-center form-control-lg'>  
        <?php 
               //   Show  all items approved from admin depending oo its category

               if(isset($_GET['name'])){
                $tag=$_GET['name'];
                $tagName = htmlspecialchars(urldecode($tag), ENT_QUOTES, 'UTF-8');
               echo "<h1 class='text-center'>Afficher les Articles par Étiquette( $tagName )</h1>";
               $tagItems= getAllFrom( "*","items","WHERE Tags LIKE '%$tagName%'","And Approve = 1","Item_ID") ;
                  if(!empty($tagItems)){
                        foreach ( $tagItems as $item ) {
                        echo "<div class='col-sm-6 col-md-3'>";
                        echo "<div class='card item-box' >";
                            echo "<span class='price-tag'>$ ".$item['Price']."</span>";
                            if(!empty($item['Image'])){
                                echo "<img  class='card-img-top img-fluid' src='admin/uploads/images/".$item['Image']."' alt='image not found' />";
                            }else{
                                echo "<img  class='card-img-top img-fluid' src='admin/uploads/default-product.png' alt='image not found' />";
                            }
                        
                            echo "<div class='caption'>";
                                echo "<h5 class='card-title'><a href='items.php?itemid=".$item['Item_ID']."'>".$item['Name']."</a></h3>";
                                echo "<p class='card-text'>".$item['Description']."</p>"; 
                                echo "<div class='date'>".$item['Add_Date']."</div>";                  
                            echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        }

                   }else{
                     echo "<div class='alert alert-danger'> Aucun article à afficher </div>";
                  }
               }else{
                     echo "<div class='alert alert-danger'> vous devez saisir un nom de tag. </div>";
             }
         ?>
      </div>
    </div>
    <?php include $tpl."footer.php"; ?>