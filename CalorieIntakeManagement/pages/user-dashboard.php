<?php include('../templates/header.php'); ?>
<?php
require_once "../config.php";
require_once "../functions.php";

$session_id = $_SESSION["id"];
$current_date = "'". strtoupper(date('d-M-y')) . "'"; //data pe care o folosesc la select-uri
$display_date = date('d-m-y'); //data pe care o afisez pe site
$current_date_for_recipe = strtoupper(date('d-M-y')); //data de care am nevoie ca se verific daca e exisat vreo reteta personalizata azi.

$query = 'SELECT a.ingredients_id, i.name, a.weight, (a.weight * i.kcal) /100 as kcal, a."date"
FROM a_i a, ingredients i
WHERE i.id= a.ingredients_id and a.accounts_id = ' . $session_id . ' and TO_CHAR(a."date") = ' . $current_date ;

$query2 =
    'SELECT a.sports_id, s.name, a.duration, a.duration * s.kcal as kcal, a."date"
FROM a_s a, sports s
WHERE s.id = a.sports_id and a.accounts_id = '.$session_id.' and TO_CHAR(a."date") = ' . $current_date ;
;

//calorii consumate
$query3 = 'SELECT sum(a.weight * i.kcal) / 100 as calorii_consumate
FROM a_i a, ingredients i
WHERE i.id = a.ingredients_id and a.accounts_id = '.  $session_id . ' and TO_CHAR(a."date") = ' . $current_date;

//calorii arse
$query4 = 'SELECT sum(a.duration * s.kcal) as calorii_arse
FROM a_s a, sports s
WHERE s.id = a.sports_id and a.accounts_id = '.  $session_id . ' and TO_CHAR(a."date") = ' . $current_date;

//ingrediente
$query5 = "SELECT id, name from ingredients ";

//sporturi
$query6 = "SELECT id, name from sports";

//reteta personalizata
$query7 = "SELECT name, duration, difficulty, kcal FROM recipes WHERE accounts_id = $session_id";

//ingredientele din reteta
$query8 = 'SELECT i.name, i_r.weight, i_r.kcal
FROM recipes r, i_r, ingredients i 
WHERE r.id = i_r.recipes_id and i_r.ingredients_id = i.id and r.accounts_id ='. $session_id.'  and TO_CHAR(recipe_date) = ' . $current_date ;

//data retetei
$query9 = "SELECT recipe_date FROM recipes WHERE accounts_id = $session_id";



$s = runQuery($query, $conn);
$s2 = runQuery($query2, $conn);
$s3 = runQuery($query3, $conn);
$s4 = runQuery($query4, $conn);
$s5 = runQuery($query5, $conn);
$s6 = runQuery($query6, $conn);
$s7 = runQuery($query7, $conn);
$s8 = runQuery($query8, $conn);
$s9 = runQuery($query9, $conn);



$row3 = oci_fetch_assoc($s3);
$row4 = oci_fetch_assoc($s4);
$row7 = oci_fetch_assoc($s7);
$row9 = oci_fetch_assoc($s9);


$calorii_ramase = 2000 - $row3["CALORII_CONSUMATE"] + $row4["CALORII_ARSE"];
oci_close($conn);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <!--text info e clasa pt culoarea albastru-->
                    <h5 class="card-title text-info">Calorii consumate</h5>
                    <h6 class="card-subtitle mb-2 text-muted"> <?php echo $display_date; ?></h6>
                    <p class="card-text">Ati consumat <?php echo $row3["CALORII_CONSUMATE"]; ?> calorii.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-info">Calorii ramase de consumat</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $display_date; ?></h6>
                    <p class="card-text">Mai aveti <?php echo $calorii_ramase; ?> de calorii de consumat.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-info">Calorii arse</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $display_date; ?></h6>
                    <p class="card-text">Ati ars <?php echo $row4["CALORII_ARSE"]; ?> de calorii.</p>
                </div>
            </div>
        </div>

    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <!-- cap de lista -->
                <div class="card-header text-info">
                    Alimente consumate
                </div>
                <!-- formular pentru a adauga alimente consumate -->
                <div class="card-body">
                    <form action="/bd-proiect/actions/insert_ingredients.php" method="post">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <select class="form-select" name="ingredients_id"aria-label="Default select example">
                                        <option selected>Selectati ingredientul</option>
                                        <?php while (($row5 = oci_fetch_array($s5)) != false){ ?>
                                        <option value="<?php echo $row5["ID"];?>"><?php echo $row5["NAME"];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" name="weight" min="100" step="100" class="form-control"
                                           placeholder="Gramaj(g)">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <button type="submit"class="btn btn-info">Adaugati</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- elementele listei -->
                <table class="table table-striped table-hover">
                    <tbody>
                        <?php while (($row = oci_fetch_array($s)) != false){ ?>
                            <tr>
                                <th scope="row"><?php echo $row["NAME"];?></th>
                                <td class="text-end"><?php echo $row["WEIGHT"];?>g</td>
                                <td class="text-end"><?php echo $row["KCAL"];?>kcal</td>
                                <td class="text-end">
                                    <a href="/bd-proiect/actions/update_ingredients.php?iid=<?php echo $row["INGREDIENTS_ID"] ?>" class="btn btn-sm btn-info">Modifica</a>
                                    <a href="/bd-proiect/actions/delete_ingredients.php?iid=<?php echo $row["INGREDIENTS_ID"] ?>" class="btn btn-sm btn-danger ml-3">Sterge</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <!-- cap de lista -->
                <div class="card-header text-info">
                    Sporturi practicate
                </div>
                <!-- formular pentru a adauga sporturi-->
                <div class="card-body">
                    <form action="/bd-proiect/actions/insert_sports.php" method="post">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <select class="form-select" name="sports_id" aria-label="Default select example">
                                        <option selected>Selectati sportul</option>
                                        <?php while (($row6 = oci_fetch_array($s6)) != false){ ?>
                                            <option value="<?php echo $row6["ID"];?>"><?php echo $row6["NAME"];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input min="1" name="duration" type="number" step="1" class="form-control" placeholder="minute">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info">Adaugati</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- elementele listei -->
                <table class="table table-striped table-hover">
                    <tbody>
                    <?php while (($row2 = oci_fetch_array($s2)) != false) { ?>
                        <tr>
                            <th scope="row"><?php echo $row2["NAME"]; ?></th>
                            <td class="text-end"><?php echo $row2["DURATION"]; ?>min</td>
                            <td class="text-end"><?php echo $row2["KCAL"]; ?>kcal</td>
                            <td class="text-end">
                                <a href="/bd-proiect/actions/update_sports.php?sid=<?php echo $row2["SPORTS_ID"] ?>" class="btn btn-sm btn-info">Modifica</a>
                                <a href="/bd-proiect/actions/delete_sports.php?sid=<?php echo $row2["SPORTS_ID"] ?>" class="btn btn-sm btn-danger ml-3">Sterge</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php if($row9 && $row9["RECIPE_DATE"] === $current_date_for_recipe) { ;?>
    <div class="row mt-3">
        <div class="col">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="card-body">
                            <h4 class="card-title text-info">Reteta personalizata - <?php echo $row7["NAME"]; ?></h4>
                            <p class="card-text">Durata: <?php echo $row7["DURATION"]; ?> min<br />Dificultate: <?php echo $row7["DIFFICULTY"]; ?><br />Calorii: <?php echo $row7["KCAL"]; ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-striped table-hover m-0">
                            <tbody>
                            <?php while (($row8 = oci_fetch_array($s8)) != false){ ?>
                                <tr>
                                    <th scope="row"><?php echo $row8["NAME"];?></th>
                                    <td><?php echo $row8["WEIGHT"];?></td>
                                    <td><?php echo $row8["KCAL"];?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
</div>
<?php include('../templates/footer.php'); ?>