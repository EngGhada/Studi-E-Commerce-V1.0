<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
   
     <a class="navbar-brand   " href="dashboard.php"><?php echo lang('HOME');?></a>
     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
     </button>
    
     <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('ITEMS');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php"><?php echo lang('MEMBERS');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('STATISTICS');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('LOGS');?></a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 bg-dark">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="caret"></span>
            Ghada
          </a>
          <ul class= "dropdown-menu " aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item "href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Edit profile</a></li>
            <li><a class="dropdown-item "href="#">Settings</a></li>
            <li><hr class="dropdown-divider d-none d-lg-block"></li>
            <li><a  class="dropdown-item"href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
