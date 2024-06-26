<?php

class RecipeHandler {
    private $database;
    public $title;
    public $ingredients;
    public $image_url;
    public $instructions;
    public $category_id;
    public $user_id;
    public $created_at;



    public function __construct($conn) {
        $this->database = $conn;
    }
    public function getConnection() {
        return $this->database->getConnection();
    }

    public function addRecipe() {
        $pdo = $this->database->getConnection();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $sql = "INSERT INTO pizzarecipes (title, ingredients, image_url, instructions, category_id, user_id, created_at) VALUES (:title, :ingredients, :image_url, :instructions, :category_id, :user_id, NOW())";
    
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':title', $this->title);
                $stmt->bindParam(':ingredients', $this->ingredients);
                $stmt->bindParam(':image_url', $this->image_url);
                $stmt->bindParam(':instructions', $this->instructions);
                $stmt->bindParam(':category_id', $this->category_id);
                $stmt->bindParam(':user_id', $this->user_id);
    
                $stmt->execute();
    
                echo "Recipe added successfully!";
                return true;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
    
    public function editRecipe($recipe_id) {
        $pdo = $this->database->getConnection();
        if ($recipe_id === false) {
            throw new Exception('Invalid recipe ID, Please try again');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $sql = "UPDATE pizzarecipes SET 
                    title = :title,
                    ingredients = :ingredients,
                    instructions = :instructions,
                    category_id = :category_id,
                    user_id = :user_id,
                    image_url = :image_url
                    WHERE recipe_id = :recipe_id";
    
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':title', $this->title);
                $stmt->bindParam(':ingredients', $this->ingredients);
                $stmt->bindParam(':image_url', $this->image_url);
                $stmt->bindParam(':instructions', $this->instructions);
                $stmt->bindParam(':category_id', $this->category_id);
                $stmt->bindParam(':user_id', $this->user_id);
                $stmt->bindParam(':recipe_id', $recipe_id);
    
                $stmt->execute();
    
                echo "Recipe updated successfully!";
                return true;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
    

    public function getAllRecipes($user_id) {
        $pdo = $this->database->getConnection();
        if ($user_id === false) {
            throw new Exception('Invalid User ID, Please contact admin');
        }
        $query = "SELECT * FROM pizzarecipes WHERE user_id = :userId";

        $stmt1 = $pdo->prepare($query);

        $stmt1->bindParam(':userId', $user_id, PDO::PARAM_INT);

        $stmt1->execute();

        $recipe = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        return $recipe;
    }

    public function getSingleRecipe($recipe_id) {
        $pdo = $this->database->getConnection(); 
        if ($recipe_id === false) {
            throw new Exception('Invalid recipe ID, Please try again');
        }
        try {
            $query = "SELECT * FROM pizzarecipes WHERE recipe_id = :recipeId";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':recipeId', $recipe_id, PDO::PARAM_INT);
            $stmt->execute();
            $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
            return $recipe;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Handle the error appropriately
        }
    }
    
}

?>