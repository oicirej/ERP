<?php
require_once "database.php";
global $title;
$title="Opdrachten";
include_once "top.inc.php";

connect();
$result=laadOpdrachten();
toonOpdrachten($result);
disconnect();

include_once "bottom.inc.php";
?>