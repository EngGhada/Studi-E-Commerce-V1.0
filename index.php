<?php 
session_start();
$pageTitle='Page d\'accueil';
include 'init.php';

?>
    <div class='container'>
       <h1 class='text-center'>Afficher les Articles</h1>
        <div  class='row form-control-lg'>  
        <?php 
               //   Show  all items          
           $allItems=getAllWithJoin("items.Item_ID, items.Name, items.Description, items.Price, items.Image, items.Add_Date","items","categories","items.Cat_ID=categories.ID","WHERE items.Approve = 1", "And categories.Visibility = 0 ","Item_ID");
            foreach ( $allItems as $item ) {
              
                echo "<div class='col-sm-6 col-md-3'>";
                echo "<div class='card item-box' >";
                    echo "<span class='price-tag'>$ ".$item['Price']."</span>";
                    if(!empty($item['Image'])){
                        echo "<img  class='card-img-top img-fluid' src='admin/uploads/images/".$item['Image']."' alt='image not found' />";
                     }else{
                        echo "<img  class='card-img-top img-fluid' src='admin/uploads/default.jpg' alt='image not found' />";
                     }
                    echo "<div class='caption'>";
                        echo "<h5 class='card-title'><a href='items.php?itemid=".$item['Item_ID']."'>".$item['Name']."</a></h3>";
                        echo "<p class='card-text text-truncate'>".$item['Description']."</p>"; ?>
                        <button class="btn btn-sm btn-secondary add-to-cart" style="float: left;" data-item-id="<?php echo $item['Item_ID']; ?>">Ajouter au panier</button>
                       <?PHP echo "<div class='date'>".$item['Add_Date']."</div>";                  
                    echo '</div>';
                  echo '</div>';
                echo '</div>';
            }
         ?>
      </div>
    </div>
<?php
    include $tpl."footer.php";
    ob_end_flush();
?>


