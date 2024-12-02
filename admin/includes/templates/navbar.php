<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
   
     <a class="navbar-brand" href="dashboard.php">Page d'accueil</a>
     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
     </button>
    
     <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="categories.php">Catégories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php">Articles</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php">Membres</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php">Commentaires</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="activity_logs.php">LOGS</a>
      </li> 

      </ul>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 bg-dark">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="caret"></span>
          <?php echo $_SESSION['UserName']; ?>
          </a>
          <ul class= "dropdown-menu " aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item "href="../index.php">Visitez Notre Boutique</a></li>
            <li><a class="dropdown-item "href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Modifier le Profil
            </a></li>
            <li><hr class="dropdown-divider d-none d-lg-block"></li>
            <li><a  class="dropdown-item"href="logout.php">Déconnexion</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

