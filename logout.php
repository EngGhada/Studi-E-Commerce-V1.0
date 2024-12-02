    <?php
     include 'init.php';
      session_start();
      
      if (!empty($_SESSION['User']) && !empty($_SESSION['uid'])) {
      $details = [  'Nom_de_connexion' => $_SESSION['User'],
                    'role'=>'Utlisateur'
                  ];
      logActivity($_SESSION['uid'] ,"Déconnexion", $details);
      
      }else {
        error_log("Session variables not set during logout.");
     }
      session_unset();
      session_destroy();

      header('Location:index.php');
      exit();

    ?>
