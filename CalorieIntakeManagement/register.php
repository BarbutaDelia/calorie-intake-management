<?php
// Include config file
//TODO: user settings
require_once "config.php";
require_once "functions.php";

// Check if the user is already logged in, if yes then redirect him to dashboard page
if(isset($_SESSION["id"]) && $_SESSION["id"]) {
    header("location: pages/user-dashboard.php");
    exit;
}

// Define variables and initialize with empty values
$last_name = $first_name = "";
$last_name_err = $first_name_err = "";
$email = "";
$email_err = "";
$birth_date = "";
$birth_date_err = "";
$weight = "";
$weight_err = "";
//fac update-ul simultan si in accounts si in user settings
$desired_weight = $desired_time = "";
$desired_weight_err = $desired_time_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validare campuri

    // Validare email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Introduceti un email.";
    } elseif (filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL) === false) {
        $email_err = "Email-ul nu e valid.";
    }
    //validare nume
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Introduceti un nume.";
    }
    //validare prenume
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Introduceti un prenume.";
    }
    //validare data de nastere
    if (!empty(trim($_POST["birth_date"]))) {
        if(date('Y', strtotime($_POST["birth_date"])) < 1900) {
            $birth_date_err = "Data de nastere invalida.";
        }
    }
    //validare greutate
    if (!empty(trim($_POST["weight"]))) {
        if (intval($_POST["weight"] ) < 1)
            $weight_err = "Greutate invalida.";
    }
    //validare greutate dorita
    if (empty(trim($_POST["desired_weight"]))) {
        $desired_weight_err = "Introduceti o greutate dorita.";
    }
    //validare timp
    if (empty(trim($_POST["desired_time"]))) {
        $desired_time_err = "Introduceti timpul.";
    }




    if(!$email_err && !$last_name_err && !$first_name_err && !$birth_date_err && !$weight_err && !$desired_weight_err && !$desired_time_err) {
        $email = trim($_POST["email"]);
        $last_name = trim($_POST["last_name"]);
        $first_name = trim($_POST["first_name"]);
        $birth_date = trim($_POST["birth_date"]);
        $weight = trim($_POST["weight"]);
        $desired_weight = trim($_POST["desired_weight"]);
        $desired_time = trim($_POST["desired_time"]);

        // Vedem daca mai e un alt email identic in baza de date
        $query = "SELECT * FROM accounts WHERE email = '$email'";

        $s = runQuery($query, $conn);
        // output data of each row
        $row = oci_fetch_assoc($s);
        if($row) { //email-ul trebuie sa fie unic, deci daca mai exista unul identic, afisam un mesaj de eroare
            $email_err = "Adresa de email exista deja in baza de date.";
        }
        else{
            //cream o tranzactie: vom face 2 inserturi, 1 in accounts si 1 in user_settings,
            //dar pentru a pastra atomicitatea datelor, vom da commit doar dupa ce se realizeaza amandoua.
            //Astfel ne putem asigura ca fie se realizeaza ambele inserturi, fie nu se realizeaza niciunul.

            $birth_date = strtoupper(date("d-M-y", strtotime($birth_date)));
            $query = "INSERT INTO accounts(last_name, first_name, email, birth_date, weight) 
	values ('$last_name','$first_name','$email','$birth_date', '$weight') RETURNING id INTO :p_val";
//trebuie sa returnez id-ul ca sa il introduc in user settings

            $s = oci_parse($conn, $query);
            oci_bind_by_name($s, ":p_val", $val);
            $result = oci_execute($s, OCI_NO_AUTO_COMMIT);

            $query2 = "INSERT INTO user_settings(desired_weight, desired_time, accounts_id) 
	values ('$desired_weight','$desired_time',' $val')";

            $s2 = oci_parse($conn, $query2);
            $result2 = oci_execute($s2, OCI_NO_AUTO_COMMIT);

            oci_commit($conn);

        }
    }
    // Close statement
    oci_close($conn);

    header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Email*</label>
            <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label>Nume*</label>
            <input type="text" name="last_name"
                   class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $last_name; ?>">
            <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Prenume*</label>
            <input type="text" name="first_name"
                   class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $first_name; ?>">
            <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Data nasterii</label>
            <input type="date" name="birth_date"
                   class="form-control <?php echo (!empty($birth_date_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $birth_date; ?>">
            <span class="invalid-feedback"><?php echo $birth_date_err; ?></span>
        </div>
        <div class="form-group">
            <label>Greutate</label>
            <input type="number" min="1" step="1" name="weight"
                   class="form-control <?php echo (!empty($weight_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $weight; ?>">
            <span class="invalid-feedback"><?php echo $weight_err; ?></span>
        </div>
        <div class="form-group">
            <label>Greutate dorita*</label>
            <input type="number" min="1" step="1" name="desired_weight"
                   class="form-control <?php echo (!empty($desired_weight_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $desired_weight; ?>">
            <span class="invalid-feedback"><?php echo $desired_weight_err; ?></span>
        </div>
        <div class="form-group">
            <label>Timp acordat indeplinirii obiectivului*</label>
            <input type="number" min="1" step="1" name="desired_time"
                   class="form-control <?php echo (!empty($desired_time_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $desired_time; ?>">
            <span class="invalid-feedback"><?php echo $desired_time_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>
