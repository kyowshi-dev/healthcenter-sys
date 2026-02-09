<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$id = (int)$_GET['id'];

$pdo->prepare("
UPDATE patient SET status='ARCHIVED' WHERE patient_id=?
")->execute([$id]);

header("Location: index.php");
exit;
