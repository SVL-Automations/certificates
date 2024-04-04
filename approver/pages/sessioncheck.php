<?php

session_start();
ob_start();

include("../../db.php");
include("../../mail/mail.php");

if (!isset($_SESSION['VALID_ECertificate_approver'])) 
{
  header("location:logout.php");
}

?>