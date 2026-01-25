<?php
header('Content-Type: text/plain');
include("db.php");
include_once("functions.php");

$qkey = getGetVar("qkey");
$insqry = sprintf("SELECT COUNT(qkey) FROM qso WHERE qkey='%s'", $qkey);

if ($res = $db->query($insqry)) {
    $row = $res->fetch_row();

    // Note: these return the opposite of what you might expect
    // because this is fed into jQuery Validate which is expecting
    // "true" if the form can proceed and "false" if it cannot
    if($row[0] == 1){
        print("false");
    } else {
        print("true");
    }
} else {
    print("true");
}

$db->close();
?>

