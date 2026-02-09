<?php
    session_start();
    require_once __DIR__ . '/../config/db.php';

    $patient_id = (int)$_POST['patient_id'];

    try {
    $pdo->beginTransaction();

    /* UPDATE PATIENT */
    $pdo->prepare("
    UPDATE patient SET
    first_name=?, last_name=?, middle_name=?, suffix=?,
    residential_address=?, date_of_birth=?
    WHERE patient_id=?
    ")->execute([
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['middle_name'],
    $_POST['suffix'],
    $_POST['residential_address'],
    $_POST['date_of_birth'],
    $patient_id
    ]);

    /* UPDATE HOUSEHOLD */
    $pdo->prepare("
    UPDATE household SET zone_id=?, household_contact=?
    WHERE household_id = (
        SELECT household_id FROM patient WHERE patient_id=?
    )
    ")->execute([
    $_POST['zone_id'],
    $_POST['household_contact'],
    $patient_id
    ]);

    $pdo->commit();
    header("Location: view.php?id=".$patient_id);
    exit;

    } catch(Throwable $e){
    $pdo->rollBack();
    die("Update failed.");
    }
