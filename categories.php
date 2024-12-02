
<?php
ob_start();  //Output Buffering Start
session_start();
$pageTitle='Les Catégories';
 

include 'init.php';

?>

<div class='container'>
    <h1 class='text-center'>Afficher la catégorie </h1>
    <!-- <div class='row justify-content-center form-control-lg g-3'> -->
    <div class='row form-control-lg '>
        <?php 
            if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
                $categoryId = intval($_GET['pageid']);

                  //  Show  all items approved from admin depending on its category and visibility status
                  
                  $allItems=getAllWithJoin("items.Item_ID, items.Name, items.Description, items.Price, items.Image, items.Add_Date","items","categories","items.Cat_ID=categories.ID","WHERE items.Cat_ID = {$categoryId} And items.Approve = 1", "And categories.Visibility = 0 ","Item_ID");
                if (!empty($allItems)) {
                    foreach ($allItems as $item) {
                        echo "<div class='col-sm-6 col-md-4 col-lg-3'>";
                        echo "<div class='card item-box mb-4'>";
                        echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
                        if (!empty($item['Image'])) {
                            echo "<img class='card-img-top img-fluid product-image' src='admin/uploads/images/" . $item['Image'] . "' alt='Product Image' />";
                        } else {
                            echo "<img class='card-img-top img-fluid product-image' src='admin/uploads/default-product.png' alt='Product Image' />";
                        }
                        echo "<div class='caption p-3'>";
                        echo "<h5 class='card-title'><a href='items.php?itemid=" . $item['Item_ID'] . "'>" . $item['Name'] . "</a></h5>";
                        echo "<p class='card-text text-truncate'>" . $item['Description'] . "</p>"; 
                        echo "<button class='add-to-cart btn btn-sm btn-secondary' style='float: left;' data-item-id=".$item['Item_ID']."'>Ajouter au panier</button>";
                        echo "<div class='date text-muted'>" . $item['Add_Date'] . "</div>";                  
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Aucun article à afficher.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Vous devez ajouter un ID de page valide.</div>";
            }
        ?>
    </div>
</div>

    <?php include $tpl."footer.php"; ?>
