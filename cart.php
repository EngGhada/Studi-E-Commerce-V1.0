<?php

ob_start();  //Output Buffering Start
session_start();
$pageTitle='cart';
$userId=$_SESSION['uid']?? 0;

include 'init.php';


 $stmt = $con->prepare("SELECT SUM(quantity) AS cartCount FROM cart WHERE user_id = ?");
 $stmt->execute([$userId]);
 $Count = $stmt->rowCount();


    if(!empty($_SESSION['cartCount'] ) && $Count > 0) {

echo '<div class="container mt-5 " id=tableContainer>
  <h2 class="text-center mb-4">Votre Panier</h2>
  <table class="table table-bordered table-hover tableCart ">
       <thead class="thead-dark">
          <tr>
               <th>L\'article</th>
                <th>Prix</th>
               <th>Quantité</th>
                <th>Total</th>
               <th>Control</th>
            </tr>
        </thead>
        <tbody id="cart-items">
            <!-- Rows will be dynamically added here -->
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center">
        <h4>Total: <span id="cart-total">$0</span></h4>
        <button class="btn btn-primary" id="checkout-btn"> Passer la Commande</button>
    </div>
   </div>';

}else {

    echo '<div style="text-align: center; margin-top: 50px;">';
    echo'<div class="alert alert-info  comment_msg"> Votre panier est vide.</div>';
   echo' <a href="/Ghada_Apps/E-Commerce/index.php" class="btn btn-primary" style="margin-top: 20px;">Continuer vos achats</a>';
    echo '</div>';
}
 include $tpl."footer.php";
 ob_end_flush();
?>

