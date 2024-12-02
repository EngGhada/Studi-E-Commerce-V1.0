<?php 
// Start session and output buffering
ob_start();  
session_start();
$pageTitle = 'Connexion';
include 'init.php'; 

// Check if user is logged in via session
if (isset($_SESSION['User'])) {
       
    header('Location:index.php');
     exit;
   
 }elseif (isset($_SESSION['UserName'])) {

    header('Location:admin/dashboard.php');
     exit;
 } 

// Check if the user is remembered by cookies

    if (isset($_COOKIE['remember_token'])) {
    
        $token = $_COOKIE['remember_token'];
        $stmt = $con->prepare("SELECT * FROM users WHERE rememberToken = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $count= $stmt->rowCount();

        if ($count > 0) {
            if (isset($_POST['login'])) {
            if (isset($_POST['remember_me'])){  
           // if (isset($user['UserName']) && isset($user['UserID'])) {
                setcookie('name', $user['UserName'] , time() + (86400 * 90) , "/") ;
                if ($user['GroupID'] == 0) {
                    // Set session variables based on user type
                $_SESSION['User'] =$user['UserName']; ;  // Register session Name
                $_SESSION['uid'] = $user['UserID']; // Register session ID
                header('Location: index.php'); // Redirect for normal users
            } else {
                $_SESSION['User'] =$user['UserName']; ;  // Register session Name
                $_SESSION['uid'] = $user['UserID']; // Register session ID

                $_SESSION['UserName'] = $_SESSION['User']; // Register session Name for Admin
                $_SESSION['ID'] = $_SESSION['uid']; // Register session ID for Admin
                header('Location: admin/dashboard.php'); // Redirect for admin users
            }
            exit();
           // }
        }
    }
  }else{
    setcookie('remember_token', '', time() - 3600, "/");
    setcookie('name','', time() - 3600, "/");
  }
}else{// Handle form submission for login and signup
if ($_SERVER['REQUEST_METHOD'] == 'POST') {     
    if (isset($_POST['login'])) {
       
        $formErrors = [];

        // Sanitize user input
        $user = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
        $pass = htmlspecialchars(trim($_POST['password']), ENT_QUOTES, 'UTF-8');
        $rememberMe = isset($_POST['remember_me']);

        // Retrieve user data from the database
        $stmt = $con->prepare("SELECT UserID, UserName, Password, GroupID FROM users WHERE UserName = ? LIMIT 1");
        $stmt->execute(array($user));
        $get = $stmt->fetch();
        
        if ($get) {
            // Verify the provided password against the stored hash
            if (password_verify($pass, $get['Password'])) {

                if ($rememberMe) {
                    // Generate a unique remember token
                    $token = bin2hex(random_bytes(16));
                    
                    // Save the token in the database
                    $stmt = $con->prepare("UPDATE users SET rememberToken = :token WHERE UserID = :userid");
                    $stmt->execute(['token' => $token, 'userid' => $get['UserID']]);
                    
                    // Set the token in a cookie (expires in 90 days)
                    setcookie('remember_token', $token, time() + (86400 * 90), "/");
                    setcookie('name', $get['UserName'] , time() + (86400 * 90) , "/") ;
                }
                
                

                if ($get['GroupID'] == 0) {
                     // Set session variables based on user type
                   $_SESSION['User'] =$get['UserName']; ;  // Register session Name
                   $_SESSION['uid'] = $get['UserID']; // Register session ID
               
                        $details = [  'Nom_de_connexion' => $_SESSION['User']];
                        logActivity($_SESSION['uid'] ,"Connexion", $details);

                    header('Location: index.php'); // Redirect for normal users
                } else {
                    $_SESSION['User'] =$get['UserName']; ;  // Register session Name
                    $_SESSION['uid'] = $get['UserID']; // Register session ID

                    $_SESSION['UserName'] = $_SESSION['User']; // Register session Name for Admin
                    $_SESSION['ID'] = $_SESSION['uid']; // Register session ID for Admin

                    $details = [  'Nom_de_connexion' => $_SESSION['UserName']];
                    logActivity($_SESSION['ID'] ,"Connexion", $details);

                    header('Location: admin/dashboard.php'); // Redirect for admin users
                }
                exit();
            } else {
                $formErrors[] = " Mot de passe invalide";
            }
        } else {
            $formErrors[] = "Nom d'utilisateur invalide";
        }
    } else if (isset($_POST['signup'])) { // Handle signup
        $formErrors = [];

        // Sanitize and validate signup input
        $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars(trim($_POST['password']), ENT_QUOTES, 'UTF-8');
        $confirmpass = htmlspecialchars(trim($_POST['confirmpass']), ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (strlen($username) < 3) {
            $formErrors[] = 'Le nom d\'utilisateur doit comporter plus de 3 caractères.';
        }
        if (empty($password) || empty($confirmpass) || $password !== $confirmpass) {
            $formErrors[] = 'Le mot de passe et la confirmation ne correspondent pas.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $formErrors[] = 'Cet e-mail n\'est pas valide';
        }

        // If no errors, insert user into the database
        if (empty($formErrors)) {
            $check = checkItem("UserName", "users", $username);
            if ($check == 1) {
                $formErrors[] = "Désolé, cet utilisateur déjà existe.";
            } else {
                $stmt = $con->prepare("INSERT INTO users (UserName, Password, Email, RegStatus, Date) VALUES (:zuser, :zpass, :zmail, 0, now())");
                $stmt->execute(['zuser' => $username, 'zpass' => $hashedPassword, 'zmail' => $email]);
                $successMsg = 'Félicitations, vous êtes maintenant inscrit.';
                $details = [  ' Nom_d\'inscription' => $username];
                    logActivity( $_SESSION['User'] ," Inscription ", $details);

                
            }
        }
    }
}
}
?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Connexion</span> | 
        <span data-class="signup">Inscription</span>
    </h1>

    <!-- Login Form -->
    <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input class="form-control" type="text" name="username" placeholder="Entrez votre nom d'utilisateure"  value="<?php if (isset($_COOKIE['remember_token'])) { echo  $_COOKIE['name'] ;}?>" required />
        <input class="form-control" type="password" name="password" placeholder=" Entrez votre mot de passe" autocomplete="new-password" value="<?php if (isset($_COOKIE['remember_token'])) { echo "Your password is secured ";}?>"  required />
        <label><input type="checkbox" name="remember_me" <?php if (isset($_COOKIE['remember_token'])) { ?> checked  <?php } ?>> Se souvenir de moi</label>
        <input class="btn btn-primary w-100" name="login" type="submit" value="Connexion" />
    </form>

    <!-- Signup Form -->
    <form class="signup" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input class="form-control" type="text" pattern=".{3,}" title="Le nom d'utilisateur doit comporter au moins 3 caractères." name="username" placeholder="Entrez votre nom d'utilisateur" autocomplete="off" required />
        <input class="form-control" type="password" minlength="5" name="password" placeholder="Entrez un mot de passe complexe" autocomplete="new-password" required />
        <input class="form-control" type="password" minlength="5" name="confirmpass" placeholder=" Confirmez votre mot de passe" autocomplete="new-password" required />
        <input class="form-control" type="email" name="email" placeholder="Entrez votre E-mail" required />
        <input class="btn btn-success w-100" name="signup" type="submit" value="Inscription" />
    </form>

    <!-- Display Errors and Success Messages -->
    <div class="the-errors text-center">
        <?php
        if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
                echo '<div class="msg error">' . $error . '</div>';
            }
        }
        if (isset($successMsg)) {
            echo '<div class="msg success">' . $successMsg . '</div>';
        }
        ?>
    </div>
</div>

<?php 
include $tpl . "footer.php"; 
ob_end_flush();
