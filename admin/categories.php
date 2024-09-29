<?php

  // Category Page
  // ==============

    ob_start();  //Output Buffering Start
    session_start();

    if (isset($_SESSION['UserName'])) {

    $pageTitle='Categories';

    include 'init.php';

    $do = isset($_GET['do'])? $_GET['do'] :'Manage';
    if($do =='Manage') {
       $sort='ASC';
       $sort_array=array('ASC','DESC');
       if(isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array)){
        $sort = $_GET['sort'];
       }
       $stmt2 =$con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
       $stmt2->execute();
       $cats=$stmt2->fetchAll();?>
        <h1 class='text-center'>Manage Categories</h1>
       <div class='container categories'>
             <div class="card">
               <div class="card-header">Manage categories
                 <div class='ordering float-end'>
                    Ordering :
                    <a  class='<?php if($sort=='ASC'){echo 'active';}?>' href="?sort=ASC">ASC</a> |
                    <a  class='<?php if($sort=='DESC'){echo 'active';}?>'href="?sort=DESC"> DESC </a>
                 </div>
               </div>
               <div class="card-body">
                 <?php
                    foreach ($cats as $cat) {
                        echo "<div class='cat'>";
                        echo "<div class='hidden-buttons'>";
                            echo "<a href='#' class='btn btn-xs btn-primary '><i class='fa fa-edit'></i>Edit</a>";
                            echo "<a href='#' class='btn btn-xs btn-danger '><i class='fa fa-close'></i>Delete</a>";
                        echo "</div>";
                        echo '<h3>'. $cat['Name'].'</h3>';
                        echo '<p>';if($cat['Description']==''){echo ' This Category has no description ';}else{ echo $cat['Description']; } echo '</p>';
                        if($cat['Visibility']==1){ echo '<span class="visibility">Hidden</span>';}
                        if($cat['Allow_Comment']==1){echo '<span class="commenting"> Comments Is Disabled </span>';}
                        if($cat['Allow_Ads']==1){echo '<span class="advertising"> Ads Is Disabled </span>' ;}
                        echo '</div>';
                        echo '<hr>';
                    }
                 ?>
               </div>
             </div>  
        </div>
  <?php
    }elseif($do =='Add'){ ?>

    <h1 class='text-center'>Add New Category</h1>

    <div class='container'>

        <form action='?do=Insert'  method="POST">
        
            <div class='row  form-control-lg'>
                
                        <!-- Start Name Field -->
            
                    <label  class='col-sm-2 col-form-label'>Name</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='name' class='form-control' id='name' autocomplete='off' required='required' placeholder ="Name Of The Category"/>
                    </div>
                </div>
                    <!-- End  Name Field -->
                    <!-- Start Description Field -->
                <div class='row form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Description</label>
                    <div class='col-sm-10 col-md-6'>
                        <input  class='form-control' type='text' name='description' id='description' placeholder ="Describe The Category" />
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Ordering Field -->
                <div class='row  form-control-lg'>
                    <label  class='col-sm-2 col-form-label'>Ordering</label>
                    <div class='col-sm-10 col-md-6'>
                        <input type='text' name='ordering' class='form-control'  id='ordering'   placeholder ="Number To Arrange The Categories"/>
                    </div>
                </div>
                <!-- End Ordering Field -->
                <!-- Start Visibility Field -->
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>Visible</label>
                    <div class='col-sm-10 col-md-6'>
                    <div>
                        <input id='vis-yes' type='radio' name='visibility' value='0'   checked />
                        <label for="vis-yes">Yes</label>
                    </div>
                    <div>
                        <input id='vis-no' type='radio' name='visibility' value='1'  />
                        <label for="vis-no">No</label>
                    </div>
                    </div>
                </div>
                <!-- End Visibility Field -->
                <!-- Start Commenting Field -->
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>Allow Commenting</label>
                    <div class='col-sm-10 col-md-6'>
                    <div>
                        <input id='com-yes' type='radio' name='commenting' value='0'  checked />
                        <label for="com-yes">Yes</label>
                    </div>
                    <div>
                        <input id='com-no' type='radio' name='commenting' value='1'/>
                        <label for="com-no">No</label>
                    </div>
                    </div>
                </div>
                <!-- End Commenting Field -->
                    <!-- Start Allow-Ads Field -->
                <div class='row form-control-lg'>
                    <label class='col-sm-2 col-form-label'>Allow Ads</label>
                    <div class='col-sm-10 col-md-6'>
                    <div>
                        <input id='ads-yes' type='radio' name='ads' value='0'  checked />
                        <label for="ads-yes">Yes</label>
                    </div>
                    <div>
                        <input id='ads-no' type='radio' name='ads' value='1'/>
                        <label for="ads-no">No</label>
                    </div>
                    </div>
                </div>
             <!-- End Allow-Ads Field -->
            <div class='row form-control-lg'>
                <div class='col-sm-10 offset-sm-2'>
                    <input type='submit' value='Add New Category' class='btn btn-primary btn-lg'/>
                </div>
            </div>
            <!-- End button -->
    </form>
</div>
     
    <?php
    }elseif($do =='Insert'){
        
    if($_SERVER['REQUEST_METHOD']=='POST'){

        echo"<h1 class='text-center'>Insert New Category</h1>";
        echo "<div class='container'>";

       //Get variables from Form 

       
       $name     = $_POST['name'];
       $desc     = $_POST['description'];
       $order    = $_POST['ordering'];
       $visible  = $_POST['visibility'];
       $comment  = $_POST['commenting'];
       $ads      = $_POST['ads'];

       //validate the form
       // check if category exists ib DB .
       
        $check=checkItem("Name","Categories",$name);
        if( $check==1){
            $theMsg= "<div class='alert alert-danger'>Sorry This Category is exist</div>";
            redirectHome($theMsg,'back');

        } else{
         // Insert Category Info in Database .

                $stmt = $con->prepare("Insert  Into categories ( Name , Description , Ordering , Visibility, Allow_Comment, Allow_Ads ) VALUES (:zname , :zdesc , :zorder , :zvisible ,:zcomment, :zads)");
                $stmt->execute(array(
                            'zname'   => $name,
                            'zdesc'   => $desc ,
                            'zorder'   => $order,
                            'zvisible'   => $visible,
                            'zcomment'=>$comment,
                            'zads'    =>$ads
                            ));

                    //echo success message.

                $theMsg= '<div class="alert alert-success">'. $stmt->rowCount() .' New  Catgory Inserted</div>';
                redirectHome($theMsg,'back');
          
            }
    
    }else{
        echo "<div class='container'>";
        $theMsg = "<div class='alert alert-danger'>You can't Browse this page directly</div>";
        redirectHome($theMsg,'back');
        echo "</div>";
    }
   echo '</div>';


    }elseif($do =='Edit'){

    }elseif($do =='Update'){

    }elseif($do =='Delete'){

    }
    include $tpl."footer.php";

    }else {
    
    header('Location:index.php'); // Redirect to the login page
    exit();                       // Terminate the script to prevent further execution

    }
    ob_end_flush();
?>