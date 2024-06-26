<?php 

include_once 'shared/database.php';

$database = new Database();

$pdo = $database->getConnection();

$recipe_id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$query = "SELECT
    R.recipe_id,
    R.title AS recipe_title,
    R.instructions,
    R.ingredients,
    R.image_url,
    C.title, 
    U.username AS user_username,
    U.email AS user_email
FROM pizzarecipes R
INNER JOIN categories C ON R.category_id = C.category_id
INNER JOIN Users U ON R.user_id = U.user_id
WHERE R.recipe_id = :recipeId";

$stmt = $pdo->prepare($query);


$stmt->bindParam(':recipeId', $recipe_id, PDO::PARAM_INT);


$stmt->execute();


$recipe_data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include('shared/header.php');?>

<main>
    <div class="previous-page">
        <a class="btn" href="index.php">Back to Recipes</a>
    </div>
<div class="container view-recipe">

    <div class="image-container">   
    <?php if (!empty($recipe_data['image_url'])) { ?>     
        <img src="<?php echo $recipe_data['image_url'] ?>" width="500">
    <?php } ?>
    </div>
    <div class="details-container">
        <?php echo $recipe_data['recipe_title'];?>
        <h1 class="recipe-title"><?php echo $recipe_data['recipe_title']; ?></h1>
        
            
        <div class="detail-item">Pizza reciepe by: <?php echo $recipe_data['user_username']; ?></div>
        <p class="user-info">Email: <?php echo $recipe_data['user_email']; ?></p>
        

        <h2 class="section-title">Instructions</h2>
        <p class="instructions"><?php echo $recipe_data['instructions']; ?></p>

        <h2 class="section-title">Ingredients</h2>
        <p class="ingredients"><?php echo $recipe_data['ingredients']; ?></p>

        <h2 class="section-title">Category</h2>
        <p class="category"><?php echo $recipe_data['title']; ?></p>

        
    </div>
    
</div>

</main>

<?php include('shared/footer.php');?>