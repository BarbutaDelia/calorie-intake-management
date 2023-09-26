<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to dashboard page
//if(isset($_SESSION["id"]) && $_SESSION["id"]) {
//    header("location: pages/user-dashboard.php");
//    exit;
//}

// Include config file + file with functions
require_once "config.php";
require_once "functions.php";

// Define variables and initialize with empty values
$email = "";
$email_err =  $login_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = $_POST["email"];
    }

    // Validate credentials
    if (empty($email_err)) {
        // Prepare a select statement
        $query = "SELECT id FROM accounts WHERE email = '$email'";
        $s = runQuery($query, $conn);

        $row = oci_fetch_assoc($s);
        if($row) {
            // Store data in session variables
            $_SESSION["id"] = $row["ID"];
            // Redirect user to dashboard page
            header("location: pages/user-dashboard.php");
        }
        else{
            $login_err = "Email-ul nu exista in baza de date";
        }
    } else {
        $login_err = "Invalid email.";
    }
}
// Close connection
    oci_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>

    <?php
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </form>
</div>
</body>
</html>