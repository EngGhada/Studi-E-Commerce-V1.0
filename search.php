<?php 
ob_start();  //Output Buffering Start
session_start();

$pageTitle='les Résultats de la Recherche';
include 'init.php';

// Search functionality
if (isset($_GET['query'])) {

    $searchTerm =htmlspecialchars(trim( ($_GET['query'])));
    
    if (!empty($searchTerm)) {
    $sql = "Select items.Item_ID As ITemID, items.Name AS ItemName,
            categories.ID  As Category_ID,
            categories.Name As Category_Name ,
            categories.Description As category_Desc
           FROM 
                  items
           INNER JOIN 
                 categories
          ON categories.ID= items.Cat_ID 

          where   items.Name LIKE CONCAT('%', :searchTerm , '%')
           OR     categories.Name LIKE CONCAT('%', :searchTerm , '%')
           OR    categories.Description LIKE CONCAT('%', :searchTerm , '%');";

    $stmt = $con->prepare($sql);
    $stmt->execute(['searchTerm' => $searchTerm]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count=$stmt->rowCount();

    
   if($count>0){
    echo "<div class='container'>";
    echo "<h1 class='text-center'>les Résultats de la Recherche</h1>";
    echo"   <div  class='row  form-control-lg'>"  ;
    
    foreach ($results as $result) {
             $id=$result['ITemID'];
             $allItems=getAllFrom("*", "items", "WHERE Item_ID = {$id}", "And Approve = 1", "Item_ID");
        
         
                //   Show  all items 
            
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
                         echo "<p class='card-text text-truncate'>".$item['Description']."</p>"; 
                         echo "<button class='add-to-cart btn btn-sm btn-secondary' style='float: left;' data-item-id=".$item['Item_ID']."'>Ajouter au panier</button>";
                         echo "<div class='date'>".$item['Add_Date']."</div>";                  
                     echo '</div>';
                   echo '</div>';
                 echo '</div>';
             }
        
    
    
        }
        echo'</div>';
        echo'</div>';
    } else {
      echo  '<div class="alert alert-info  comment_msg"  >  Cet article n\'existe pas.</div>';
    }
}else{
    echo '<div class="alert alert-info  comment_msg"  > Le champ de recherche est vide , Veuillez entrer le nom de l\'article que vous souhaitez rechercher.</div>';
}   
}

 include $tpl."footer.php";
 ob_end_flush();
 ?>