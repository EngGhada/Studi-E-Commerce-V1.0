
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getTitle()?></title>
    
    <link rel="stylesheet" href="<?php echo $css;?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css;?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $css;?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href="<?php echo $css;?>frontend.css">
    <!-- Bootstrap 5 uses its own icon library called Bootstrap Icons -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> -->
</head>
<body>
  
<div  class="upper-bar">
    <div class="container">

          <?php  
          if (isset($_SESSION['User'])){  
            $stmt = $con -> prepare(" Select Avatar , RegStatus FROM users  Where  UserID = ? ");

                $stmt->execute(array($sessionUserID));
                $profileAvatar=$stmt->fetch();
                $count = $stmt->rowCount();
            ?>
          <image class="img-fluid" style="max-width:100px;max-height:100px; margin-right: 10px;" src="layout/images/eCommerce-logo.jpg" alt="" >
          <div class="btn-group my-info">
            
            <?php 
              if(!empty($profileAvatar['Avatar'])){
                  echo "<img  class = 'img-fluid  img-thumbnail rounded-circle mx-auto d-block' src ='admin/uploads/avatars/".$profileAvatar['Avatar']."' alt='image not found' />";
              }else{
                echo "<img  class = 'img-fluid  img-thumbnail rounded-circle mx-auto d-block' src ='admin/uploads/avatar.png' alt='image not found' />";
              }
            ?>
            <span class="btn  dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <?php echo $sessionUser  ?>       
            </span>
            
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="profile.php">Mon Profil</a></li>
                    <?php
                         if($profileAvatar['RegStatus']==1){
                           echo'<li><a class="dropdown-item" href="newad.php">un Nouvel Article</a></li>';
                         }
                    ?>
                    <li><a class="dropdown-item " href="profile.php#myitems">Mes Articles</a></li>
                    <?php
                     if(isset( $_SESSION['UserName'])){
                      $_SESSION['UserName']=$sessionUser;
                      echo '<li><a class="dropdown-item" href="admin/dashboard.php"> Admin page</a></li>';
                     }
                    ?>
                    <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
                </ul>

        </div>

        <div class="search-container d-flex align-items-center justify-content-between float-end" id="cart-container">

            <form action="search.php" method="GET">
              <input type="text" name="query" placeholder="Rechercher..." class="search-input" value="">
              <button type="submit" class="search-button">
                <i class="fa fa-search"></i> Rechercher</button>
            </form>

            <a href="cart.php" class="ms-3 text-reset text-decoration-none position-relative ">
                <i class="fas fa-shopping-cart fa-2x position-relative"></i> 
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                  <?php
                        $cartCount = $_SESSION['cartCount'] ?? 0; // Default to 0 if no cart count
                        echo $cartCount;
                    ?>
                </span>
              </a>
              
        </div>
       
   <?php
        }else{
    ?>
        <a href="login.php">
           <span class=" login-btn float-end"><i class="fa fa-user"></i> Connexion | Inscription</span>
        </a>

  <?php } ?>

    </div>  

</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
     
     <a class="navbar-brand" href="index.php">Page d'accueil</a>
     <button class="navbar-toggler"
             type="button"
             data-bs-toggle="collapse" 
             data-bs-target="#app-nav"
             aria-controls="navbarSupportedContent" 
             aria-expanded="false"
            aria-label="Toggle navigation">
            
        <span class="navbar-toggler-icon"></span>
     </button>
    
     <div class="collapse navbar-collapse" id="app-nav">
     <ul class="navbar-nav ms-auto mb-2 mb-lg-0 "> <!-- ms-auto == navbar-right ,me-auto==navbar-left-->
       
      <?php
         $cats=getAllFrom("*","categories","WHERE Parent = 0","","ID","ASC");
         foreach ($cats as $cat) {
             echo'<li><a class="nav-link" href="categories.php?pageid='.$cat['ID'].'">' .$cat['Name'].'</li></a><br>';
         }
      ?>
      
      </ul>

    </div>
  </div>
</nav>

