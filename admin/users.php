<?php
include_once '../shared/database.php';

if(empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: login.php');
}
$database = new Database();

$pdo = $database->getConnection();

$results = $pdo->query('SELECT * FROM users');

?>

<?php include('shared/sidebar.php');?>

<div class="content">
    
    <main>
    <div class="list-container">
        <h2 class="user-list-title">User List</h2>
        <button class="add-new-user-btn edit-button"><a href="add_new_user.php">Add New</a></button>
        <ul class="user-list">
            <li class="user-item">
                <span class="user-name">Username</span>
                <span class="user-name">Email</span>
                <span class="user-name">is_admin</span>
                <span class="user-name">Actions</span>
            </li>
        <?php
        while ($post = $results->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <li class="user-item">
                <span class="user-name"><?php echo $post['username']?></span>
                <span class="user-email"><?php echo $post['email']?></span>
                <span class="user-is_admin"><?php echo $post['is_admin']?></span>
                <div class="user-actions">
                <button class="edit-button"><a href="edit_users.php?id=<?php echo $post['user_id']?>">Edit</a></button>
                <form method="post" action="delete_users.php">
                    <input type="hidden" name="user_id" value="<?php echo $post['user_id']?>">
                    <input type="submit" class="delete-button" name="delete_user" value="Delete User" onclick="return confirm('Delete this User?');">
                </form>
                </div>
            </li>
		<?php } ?>
        </ul>
    </div>
    </main>
</div>

        
<?php include('shared/footer.php');?>