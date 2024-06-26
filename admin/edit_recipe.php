<?php 
include_once '../Shared/database.php';
include_once 'shared/image_handler.php';
include_once 'shared/recipeHandler.php';
include_once 'shared/categoryHandler.php';
include_once 'shared/userHandler.php';


if(empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: login.php');
}
$conn = new Database();
$recipeHandler = new RecipeHandler($conn);
$imageHandler = new ImageHandler();
$categoryHandler = new CategoryHandler($conn);
$userHandler = new UserHandler($conn);

$users = $userHandler->getUsers();

$categories = $categoryHandler->getCategories();

$recipe_id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';


$data = $recipeHandler->getSingleRecipe($recipe_id);
$error_message = (isset($_GET['error'])) ? $_GET['error'] : null;
$title = (isset($_GET['error'])) ? $_GET['title'] : $data['title'];
$instructions = (isset($_GET['error'])) ? $_GET['instructions'] : $data['instructions'];
$ingredients = (isset($_GET['error'])) ? $_GET['ingredients'] : $data['ingredients'];
$category_id = (isset($_GET['error'])) ? $_GET['category_id'] : $data['category_id'];
$user_id = (isset($_GET['error'])) ? $_GET['user_id'] : $data['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_url = $data['image_url'];

    if (isset($_FILES['uploadFile']) && $_FILES['uploadFile']['error'] !== UPLOAD_ERR_NO_FILE && !isset($_POST['delete_image'])) {
        try {
            $returned_value = $imageHandler->upload_image($_FILES['uploadFile']);
            if ($returned_value) {
                $image_url = $returned_value;
            }
        } catch (Exception $e) {
            // Handle the exception here
            echo 'Error: ' . $e->getMessage();
        }
    }
    else if(isset($_POST['delete_image'])) {
        $image_path = "../" . $image_url;
        if (file_exists($image_path)) {
            unlink($image_path);
            echo "Image deleted successfully!";
        } else {
            echo "Image not found.";
        }

        $image_url = null;
        header("Location: recipes.php");
        exit;
    }	    
    // Extract data from the form
    $recipe_id = $_GET['id'];
    $recipeHandler->title = htmlspecialchars($_POST['title']);
    $recipeHandler->ingredients = htmlspecialchars($_POST['ingredients']);
    $recipeHandler->instructions = $_POST['instructions'];
    $recipeHandler->user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $recipeHandler->category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);

    if ($recipeHandler->title && $recipeHandler->category_id !== false && $recipeHandler->user_id !== false) {

        $recipeHandler->image_url = (isset($_FILES['uploadFile']) && $_FILES['uploadFile']['error'] !== UPLOAD_ERR_NO_FILE) ? $imageHandler->upload_image($_FILES['uploadFile']) : null;

        $requestStatus = $recipeHandler->editRecipe($recipe_id);

        if ($requestStatus === true) {
            header("Location: recipes.php");
            exit();
        }
    } else {
        $error_message = "Please enter all required fields";
        header("Location: edit_recipe.php?id=". $recipe_id . "&error=" . urlencode($error_message) . "&title=" . urlencode($_POST['title']) . "&instructions=" . urlencode($_POST['instructions']) . "&ingredients=" . urlencode($_POST['ingredients']) . "&category_id=" . urlencode($_POST['category_id']) . "&user_id=" . urlencode($_POST['user_id']));
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <link rel="stylesheet" href="css/admin-panel.css">
    <title>Admin Panel</title>
</head>
<body>
<?php include('shared/sidebar.php');?>
<script>
    tinymce.init({
        selector: 'textarea#instructions',
        plugins: 'advlist autolink lists link image charmap print preview anchor',
        toolbar_mode: 'floating',
        setup: function (editor) {
            editor.on('init', function () {
                // Get the existing instructions from PHP
                var existingInstructions = <?php echo json_encode($data['instructions']); ?>;
                // Set the existing instructions into the editor
                editor.setContent(existingInstructions);
            });
        }
    });
</script>

    <div class="add-recipe-container">
        <?php if (isset($_GET['error'])) {?>
            <div class='col-md-9 ml-sm-auto col-lg-10 px-md-4'>
                <div class='error-message'><?php echo $error_message; ?></div>
            </div>
        <?php } ?>
        <h2 class="add-recipe-title">Edit Pizza Recipe</h2>
        <form class="recipe-form" action="edit_recipe.php?id=<?php echo $recipe_id?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" class="form-input" value="<?php echo $title; ?>">
        </div>
        <div class="form-group">
            <label for="category_id">Select Category</label>
            <select id="category_id" name="category_id" value="<?php echo $category_id?>">
            <?php foreach ($categories as $categoryId => $categoryName) : ?>
                <option value="<?php echo $categoryId; ?>" <?php if($category_id == $categoryId) { echo "Selected"; } ?>><?php echo $categoryName; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="user_id">Select User:</label>
            <select id="user_id" name="user_id" value="<?php echo $user_id; ?>">
                <?php foreach ($users as $userId => $username) : ?>
                    <option value="<?php echo $userId; ?>" <?php if($user_id == $userId) { echo "Selected"; } ?>><?php echo $username; ?></option>
                    <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" class="form-input" required><?php echo $ingredients?></textarea>
        </div>
        <div class="form-group">
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" value="<?php echo $instructions?>"></textarea>
        </div>
        <?php if (!empty($image_url)) {?>
            <div class="form-group">
            <label for="image_url">Uploaded Image:</label>
            <span class="modify-Image">
            <input type="checkbox" id="delete_image" name="delete_image" value="delete">
            <span class="modify-image-text">Remove Image</span>
            <span class="image-container"><img src="../<?php echo $image_url?>" width="100"></span></span>
        </div>
        <?php }?>
        <div class="form-group">
            <label for="image_url">Upload Image:</label>
            <input type="file" id="image_url" name="uploadFile" class="form-input">
        </div>
            
        <script>
            document.addEventListener('DOMContentLoaded', ()=>{
                document.getElementById('image_url').addEventListener('change', function() {
                    const fileInput = document.getElementById('image_url');
                    const filePath = fileInput.value;
                    const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

                    if (!allowedExtensions.exec(filePath)) {
                        alert('Please upload files having extensions .jpg/.jpeg/.png/.gif only.');
                        fileInput.value = '';
                        throw new Error('Incorrect file format');
                    }
                });
            });
        </script>
            <input type="submit" value="Save" class="submit-button">
        </form>
    </div>

<?php include('shared/footer.php');?>