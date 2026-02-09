<?php
session_start();
require_once __DIR__ . '/../config/db.php';

function computeAge(string $dob): int
{
    return (new DateTime($dob))->diff(new DateTime())->y;
}

$patient_id = (int)($_GET['id'] ?? 0);
if (!$patient_id) {
    die("Patient ID required.");
}

/* ===============================
   CORE PATIENT
=============================== */
$stmt = $pdo->prepare("
    SELECT p.*, h.family_id, h.household_id, h.household_contact,
           z.zone_number,
           CONCAT(hw.first_name,' ',hw.last_name) AS bhw
    FROM patient p
    LEFT JOIN household h ON p.household_id = h.household_id
    LEFT JOIN zone z ON h.zone_id = z.zone_id
    LEFT JOIN health_worker hw ON z.assigned_worker_id = hw.worker_id
    WHERE p.patient_id = ?
");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();

if (!$patient) {
    die("Patient not found.");
}

/* ===============================
   DUPLICATE CHECK (SOFT)
=============================== */
$dupes = $pdo->prepare("
    SELECT patient_id, first_name, last_name, date_of_birth
    FROM patient
    WHERE last_name = ?
    AND date_of_birth = ?
    AND patient_id != ?
");
$dupes->execute([
    $patient['last_name'],
    $patient['date_of_birth'],
    $patient_id
]);
$duplicates = $dupes->fetchAll();

/* ===============================
   FAMILY MEMBERS
=============================== */
$family = $pdo->prepare("
    SELECT patient_id, first_name, last_name, date_of_birth
    FROM patient
    WHERE household_id = ?
    AND patient_id != ?
");
$family->execute([
    $patient['household_id'],
    $patient_id
]);
$family_members = $family->fetchAll();
?>

<div style="font-family:Arial;background:#f4f6f8;padding:20px;">
    <div style="max-width:900px;margin:auto;">

        <h2 style="color:#1e88e5;">Patient Record</h2>

        <?php if ($duplicates): ?>
            <div style="background:#fff3cd;padding:12px;border-left:5px solid #ff9800;margin-bottom:15px;">
                <strong>⚠ Possible Duplicate Record Detected</strong>
                <ul>
                    <?php foreach ($duplicates as $d): ?>
                        <li><?= htmlspecialchars($d['last_name']) ?>, <?= htmlspecialchars($d['first_name']) ?> (<?= $d['date_of_birth'] ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
            <h3>Patient Information</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($patient['last_name']) ?>, <?= htmlspecialchars($patient['first_name']) ?> <?= htmlspecialchars($patient['middle_name']) ?> <?= htmlspecialchars($patient['suffix']) ?></p>
            <p><strong>Age:</strong> <?= computeAge($patient['date_of_birth']) ?></p>
            <p><strong>DOB:</strong> <?= $patient['date_of_birth'] ?></p>
            <p><strong>Enrollment ID:</strong> <?= htmlspecialchars($patient['patient_enrollment_id']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($patient['residential_address']) ?></p>

            <a href="edit.php?id=<?= $patient_id ?>">✏ Edit</a> |
            <a href="archive.php?id=<?= $patient_id ?>" onclick="return confirm('Archive this patient?')" style="color:red;">🗄 Archive</a>
        </div>

        <br>

        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
            <h3>Household</h3>
            <p><strong>Family ID:</strong> <?= htmlspecialchars($patient['family_id'] ?? 'N/A') ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($patient['household_contact'] ?? 'N/A') ?></p>
            <p><strong>Zone:</strong> <?= htmlspecialchars($patient['zone_number'] ?? 'N/A') ?></p>
            <p><strong>BHW:</strong> <?= htmlspecialchars($patient['bhw'] ?? 'N/A') ?></p>
        </div>

        <br>

        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
            <h3>Family Members</h3>
            <?php if ($family_members): ?>
                <ul>
                    <?php foreach ($family_members as $f): ?>
                        <li>
                            <a href="view.php?id=<?= $f['patient_id'] ?>">
                                <?= htmlspecialchars($f['last_name']) ?>, <?= htmlspecialchars($f['first_name']) ?>
                                (<?= computeAge($f['date_of_birth']) ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No other family members recorded.</p>
            <?php endif; ?>
        </div>

    </div>
</div>