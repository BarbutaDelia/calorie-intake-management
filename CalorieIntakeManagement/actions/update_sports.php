<?php include('../templates/header.php'); ?>
<?php
require_once "../config.php";
require_once "../functions.php";

$current_date = "'". strtoupper(date('d-M-y')) . "'"; //data pe care o folosesc la select-uri
$session_id = $_SESSION["id"];
$sport_id = $_GET["sid"]; //id_ul sportului pe care l-am selectat pt modificare
$sport_err = $duration_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validare id ingrediente
    if (empty(trim($_POST["sports_id"]))) {
        $sport_err = "Selectati sportul.";
    }
    //validare greutate
    if (empty(trim($_POST["duration"]))) {
        $duration_err = "Introduceti durata.";
    }

    if(!$sport_err && !$duration_err){
        $session_id = $_SESSION["id"];
        $duration = $_POST['duration'];
        $sports_id = $_POST['sports_id']; //id-ul noului ingredient
        $query = 'UPDATE a_s a SET sports_id = ' . $sports_id . ', duration = ' . $duration . '
WHERE a.sports_id = ' . $sport_id . ' and a.accounts_id = ' . $session_id . ' and TO_CHAR(a."date") = ' . $current_date  ;

        $result = runQueryInsert($query, $conn);
        oci_commit($conn);
        oci_close($conn);
        header("location: ../pages/user-dashboard.php");
    }
}

$query = 'SELECT *
FROM a_s a
WHERE a.sports_id = ' . $sport_id . 'and a.accounts_id = ' . $session_id . ' and TO_CHAR(a."date") = ' . $current_date ;
$query5 = "SELECT id, name from sports ";

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
                    Modifica sporturi practicate
                </div>
                <!-- formular pentru a adauga alimente consumate -->
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <select class="form-select" name="sports_id"aria-label="Default select example">
                                        <option selected>Selectati sportul</option>
                                        <?php while (($row5 = oci_fetch_array($s5)) != false){ ?>
                                            <option <?php if($row["SPORTS_ID"] == $row5["ID"]){
                                                echo "selected"; } ?> value="<?php echo $row5["ID"];?>"><?php echo $row5["NAME"];?></option>
                                            }
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input value="<?php echo $row["DURATION"] ?>" min="1" name="duration" type="number" step="1" class="form-control" placeholder="minute">
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
            </div>
        </div>
    </div>
</div>

