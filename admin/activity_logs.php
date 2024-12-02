<?php 

// Manage Comments pages
// You can Edit | Delete | Approve Comments from here .

ob_start();  //Output Buffering Start
session_start();
$pageTitle='Logs';

if (isset($_SESSION['UserName'])) {

   include 'init.php';

   if (!isset($collection)) {
    die("Error: \$collection is not initialized.");
}

    $do = isset($_GET['do'])? $_GET['do'] :'view_Log';
    $collection = $db->Activities_USER_Log;
    $logs = $collection->find([], ['sort' => ['timestamp' => -1]]); // Sort by latest


    if($do =='view_Log') {
        
        if ($do == 'view_Log') {
            // Pagination setup
            $limit = 10; // Number of logs per page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page or default to 1
            $offset = ($page - 1) * $limit; // Calculate the skip value
        
            // Fetch logs with pagination
            $collection = $db->Activities_USER_Log;
            $logs = $collection->find([], ['limit' => $limit, 'skip' => $offset, 'sort' => ['timestamp' => -1]]);
        
            // Get the total number of logs for calculating total pages
            $totalLogs = $collection->count();
            $totalPages = ceil($totalLogs / $limit);
        }
        

    }if ($do == 'delete') {
        // Validate the ID
        $id = isset($_GET['id']) ? $_GET['id'] : null;
    
        if ($id) {
            try {
                // Convert ID to MongoDB ObjectID
                $logId = new MongoDB\BSON\ObjectId($id);
    
                // Delete the log from the database
                $deleteResult = $collection->deleteOne(['_id' => $logId]);
    
                if ($deleteResult->getDeletedCount() > 0) {
                    echo "<div class='alert alert-success' id='message-box'>Journal supprimé avec succès.</div>";
                } else {
                    echo "<div class='alert alert-danger' id='message-box'>Journal introuvable ou impossible à supprimer.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>ID de journal invalide.</div>";
            }
        } else {
                echo "<div class='alert alert-danger'>Aucun ID de journal fourni.</div>";
        }
    }
    
    ?>

<h1 class='text-center'> Journal des activités</h1>
      <div class='container'>
           <div class="table-responsive ">
                <table class='table text-center table-bordered manageTable'>
            <tr>
                <td>ID</td>
                <td>Action</td>
                <td>les Détailes</td>
                <td>La Date</td>
                <td>Contrôle</td>
            </tr>     
            <?php
        
            // Display Logs
            foreach ($logs as $log) {
                echo "<tr>";
                echo "<td>{$log['user_id']}</td>";
                echo "<td>{$log['action']}</td>";

                // Format Details as JSON
                $details = !empty($log['details']) ? json_encode($log['details'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : 'None';
                echo "<td><pre>{$details}</pre></td>";

                // Format Timestamp
                $timestamp = $log['timestamp']->toDateTime()->format('Y-m-d H:i:s');
                echo "<td>{$timestamp}</td>";

                  // Add control buttons
                echo "<td>
                  <a href='?do=delete&id={$log['_id']}' class='btn btn-danger btn-sm confirm'>Supprimer</a>
                </td>";

                echo "</tr>";
            }
    
       echo" </table>"; ?>

       <div class="pagination">
            <?php if ($page > 1): ?>
                        <a href="?do=view_Log&page=<?= $page - 1 ?>" class="btn btn-sm btn-primary">Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?do=view_Log&page=<?= $i ?>" class="btn btn-sm <?= ($i == $page) ? 'btn-secondary' : 'btn-primary' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?do=view_Log&page=<?= $page + 1 ?>" class="btn btn-sm btn-primary">Suivant</a>
                <?php endif; ?>
                <?php
                if ($page < 1) {
                    header("Location: ?do=view_Log&page=1");
                    exit;
                }if ($page > $totalPages) {
                    header("Location: ?do=view_Log&page=$totalPages");
                    exit;
                } ?>
                
                
      </div>

<?php
       
        include $tpl."footer.php";

    }else {
    header('Location:../login.php'); // Redirect to the login page
    exit();                       // Terminate the script to prevent further execution
 }
 ob_end_flush();
 ?>