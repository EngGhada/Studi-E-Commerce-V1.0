<?php 

session_start();
$noNavbar ='';
$pageTitle='Login';

if (isset($_SESSION['UserName'])) {

    header('Location:dashboard.php');
}  
include 'init.php';

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
{

    $username=$_POST['user'];
    $password=$_POST['password'];

   
    $hashedpass=sha1($password);

   
    $stmt = $con -> prepare(" Select 
                                 UserID, UserName , Password
                              FROM 
                                   users
                             Where 
                                  UserName = ? 
                             And   
                                  Password = ?
                             AND   
                                GroupID=1 
                                LIMIT 1 ");
   
    $stmt->execute(array($username , $hashedpass));
    $row= $stmt->fetch();
    $count = $stmt->rowCount();

    if($count > 0)
    {
       
        $_SESSION['UserName'] = $username; //Register session Name 
        $_SESSION['ID'] = $row['UserID']; //Register session ID

       header('Location: dashboard.php'); //redirect to dashboard
       exit(); 
    }
}
?>

<form  class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post">
    <h4 class="text-center">Admin login</h4>
    <input class="form-control " type="text" name="user" placeholder="username" autocomplete="off"/>
    <input class="form-control " type="password" name="password" placeholder="password" autocomplete="new-password"/>
    <input class="btn btn-primary w-100" type="submit" name="submit" value="login"/>
</form>


<?php include $tpl."footer.php"; ?> 


