<?php
session_start();
require_once "../config.php";
require_once "../functions.php";

$ingredient_err = $weight_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validare id ingrediente
    if (empty(trim($_POST["ingredients_id"]))) {
        $ingredient_err = "Selectati ingredientul.";
    }
    //validare greutate
    if (empty(trim($_POST["weight"]))) {
        $weight_err = "Introduceti cantitatea.";
    }

    if(!$ingredient_err && !$weight_err){
        $session_id = $_SESSION["id"];
        $weight = $_POST['weight'];
        $ingredients_id = $_POST['ingredients_id'];
        $query = "INSERT INTO a_i(accounts_id, ingredients_id, weight) 
	values ('$session_id','$ingredients_id','$weight')";
        $result = runQueryInsert($query, $conn);
        oci_commit($conn);
        oci_close($conn);
    }
}
header("location: ../pages/user-dashboard.php");
?>