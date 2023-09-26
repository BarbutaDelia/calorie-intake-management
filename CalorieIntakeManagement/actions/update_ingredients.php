<?php include('../templates/header.php'); ?>
<?php
require_once "../config.php";
require_once "../functions.php";

$current_date = "'". strtoupper(date('d-M-y')) . "'";
$session_id = $_SESSION["id"];
$ingredient_id = $_GET["iid"]; //id_ul incredientului pe care l-am selectat pt modificare
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
        $weight = $_POST['weight'];
        $ingredients_id = $_POST['ingredients_id']; //id-ul noului ingredient
        $query = 'UPDATE a_i a SET ingredients_id = ' . $ingredients_id . ', weight = ' . $weight . '
WHERE a.ingredients_id = ' . $ingredient_id . ' and a.accounts_id = ' . $session_id . ' and TO_CHAR(a."date") = ' . $current_date  ;

        $result = runQueryInsert($query, $conn);
        oci_commit($conn);
        oci_close($conn);
        header("location: ../pages/user-dashboard.php");
    }
}

$query = 'SELECT *
FROM a_i a
WHERE a.ingredients_id = ' . $ingredient_id . 'and a.accounts_id = ' . $session_id . ' and TO_CHAR(a."date") = ' . $current_date ;
$query5 = "SELECT id, name from ingredients ";

$s = runQuery($query, $conn);
$s5 = runQuery($query5, $conn);

$row = oci_fetch_assoc($s);


?>
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <!-- cap de lista -->
                <div class="card-header text-info">
                    Modifica alimente consumate
                </div>
                <!-- formular pentru a adauga alimente consumate -->
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <select class="form-select" name="ingredients_id"aria-label="Default select example">
                                        <option selected>Selectati ingredientul</option>
                                        <?php while (($row5 = oci_fetch_array($s5)) != false){ ?>
                                            <option <?php if($row["INGREDIENTS_ID"] == $row5["ID"]){
                                                echo "selected"; } ?> value="<?php echo $row5["ID"];?>"><?php echo $row5["NAME"];?></option>
                                        }
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input value="<?php echo $row["WEIGHT"] ?>" type="number" name="weight" min="100" step="100" class="form-control"
                                           placeholder="Gramaj(g)">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <button type="submit"class="btn btn-info">Modifica</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- elementele listei -->
            </div>
        </div>
    </div>
</div>
