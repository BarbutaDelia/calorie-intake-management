<?php
function runQuery($query, $conn)
{
    $s = oci_parse($conn, $query);
    if (!$s) {
        $m = oci_error($conn);
        trigger_error('Could not parse statement: '. $m['message'], E_USER_ERROR);
    }
    $r = oci_execute($s);
    if (!$r) {
        $m = oci_error($s);
        trigger_error('Could not execute statement: '. $m['message'], E_USER_ERROR);
    }
    return $s;
}
function runQueryInsert($query, $conn){
    $s = oci_parse($conn, $query);
    var_dump($query);
    $result = oci_execute($s);
    return $result;
}