<?php
//echo phpinfo();
/* Database credentials.  */
define('DB_USERNAME', 'bd034');
define('DB_PASSWORD', 'Dyf0a4f06');
/*
$fp = fsockopen('bd-dc.cs.tuiasi.ro', 1539, $errno, $errstr, 20);
if (!$fp) {
    echo "$errstr ($errno)<br>";
} else {
    echo 'success';
} */
/* Attempt to connect to Oracle database */
$db = '(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = bd-dc.cs.tuiasi.ro)(PORT = 1539)))
(CONNECT_DATA = (SERVICE_NAME = orcl)))';
$conn = oci_connect(DB_USERNAME, DB_PASSWORD, $db);

if (!$conn){
    $m = oci_error();
    trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
}

/*$query = "select * from employees";

$s = oci_parse($conn, $query);
if (!$s) {
    $m = oci_error($c);
    trigger_error('Could not parse statement: '. $m['message'], E_USER_ERROR);
}
$r = oci_execute($s);
if (!$r) {
    $m = oci_error($s);
    trigger_error('Could not execute statement: '. $m['message'], E_USER_ERROR);
}

$ncols = oci_num_fields($s);

for ($i = 1; $i <= $ncols; ++$i) {
    $colname = oci_field_name($s, $i);
    echo htmlspecialchars($colname,ENT_QUOTES|ENT_SUBSTITUTE)."<br />";
}

while (($row = oci_fetch_array($s, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "<td>";
        echo $item!==null?htmlspecialchars($item, ENT_QUOTES|ENT_SUBSTITUTE):"&nbsp;";
        echo "</td>\n";
    }
    echo "</tr>\n";
}*/
?>
