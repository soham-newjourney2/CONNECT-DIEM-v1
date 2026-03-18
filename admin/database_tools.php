<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Ensure admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../admin_login.php');
    exit;
}

$success_msg = '';
$error_msg = '';

// Backup database
if(isset($_POST['backup_db'])){
    $backup_file = 'backup_connect_' . date('Y-m-d_H-i-s') . '.sql';
    $command = "mysqldump --user={$db_user} --password={$db_pass} --host={$db_host} {$db_name} > " . $backup_file;
    exec($command, $output, $return_var);
    if($return_var === 0){
        header("Content-Disposition: attachment; filename=$backup_file");
        readfile($backup_file);
        unlink($backup_file);
        exit;
    } else {
        $error_msg = "Backup failed!";
    }
}

// Restore database
if(isset($_POST['restore_db']) && isset($_FILES['sql_file'])){
    $file_tmp = $_FILES['sql_file']['tmp_name'];
    if($file_tmp){
        $command = "mysql --user={$db_user} --password={$db_pass} --host={$db_host} {$db_name} < " . $file_tmp;
        exec($command, $output, $return_var);
        if($return_var === 0){
            $success_msg = "Database restored successfully!";
        } else {
            $error_msg = "Database restore failed!";
        }
    } else {
        $error_msg = "Please select a SQL file!";
    }
}

// Table maintenance
if(isset($_POST['table_action'])){
    $table = $_POST['table'];
    $action = $_POST['table_action'];
    if($action === 'optimize'){
        $result = $conn->query("OPTIMIZE TABLE `$table`");
        $success_msg = "Table `$table` optimized successfully!";
    } elseif($action === 'repair'){
        $result = $conn->query("REPAIR TABLE `$table`");
        $success_msg = "Table `$table` repaired successfully!";
    }
}

// Fetch tables
$tables_result = $conn->query("SHOW TABLE STATUS");
$tables = [];
while($row = $tables_result->fetch_assoc()){
    $tables[] = $row;
}
?>

<main class="dashboard-main">
    <h2>Database Tools</h2>

    <?php if($success_msg): ?>
        <p class="success"><?= htmlspecialchars($success_msg) ?></p>
    <?php endif; ?>
    <?php if($error_msg): ?>
        <p class="error"><?= htmlspecialchars($error_msg) ?></p>
    <?php endif; ?>

    <!-- Backup -->
    <section class="db-backup">
        <h3>Backup Database</h3>
        <form method="POST">
            <button type="submit" name="backup_db" class="btn btn-primary">Download Backup</button>
        </form>
    </section>

    <!-- Restore -->
    <section class="db-restore">
        <h3>Restore Database</h3>
        <form method="POST" enctype="multipart/form-data" onsubmit="return confirm('Restoring will overwrite existing data. Continue?');">
            <input type="file" name="sql_file" accept=".sql" required>
            <button type="submit" name="restore_db" class="btn btn-warning">Restore</button>
        </form>
    </section>

    <!-- Table Maintenance -->
    <section class="db-maintenance">
        <h3>Table Maintenance</h3>
        <table>
            <tr>
                <th>Table</th>
                <th>Rows</th>
                <th>Size (KB)</th>
                <th>Actions</th>
            </tr>
            <?php foreach($tables as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['Name']) ?></td>
                <td><?= number_format($t['Rows']) ?></td>
                <td><?= number_format($t['Data_length']/1024,2) ?></td>
                <td>
                    <form method="POST" style="display:flex; gap:5px;">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($t['Name']) ?>">
                        <button type="submit" name="table_action" value="optimize" class="btn btn-secondary">Optimize</button>
                        <button type="submit" name="table_action" value="repair" class="btn btn-secondary">Repair</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
