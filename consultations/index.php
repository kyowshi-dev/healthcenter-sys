<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query("
    SELECT 
        c.record_id,
        c.date_of_consultation,
        c.consultation_time,
        c.name_of_attending_provider,
        p.last_name,
        p.first_name,
        nv.visit_type
    FROM consultation_record c
    JOIN patient p ON c.patient_id = p.patient_id
    LEFT JOIN nature_of_visit nv ON c.visit_type_id = nv.visit_type_id
    ORDER BY c.date_of_consultation DESC, c.consultation_time DESC
");
$consultations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Consultations</title>
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<div style="font-family:Arial;background:#f4f6f8;padding:20px;">

    <h2 style="color:#1e88e5;">Consultation Records</h2> 

    <a href="/bhcis/consultations/create.php" style="padding:8px 14px;background:#1e88e5;color:#fff;text-decoration:none;border-radius:4px;">
        + New Consultation
    </a>

    <table style="width:100%;margin-top:15px;border-collapse:collapse;background:#fff;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
        <thead style="background:#e3f2fd;">
            <tr>
                <th style="padding:10px;text-align:left;">Date</th>
                <th style="padding:10px;text-align:left;">Time</th>
                <th style="padding:10px;text-align:left;">Patient</th>
                <th style="padding:10px;text-align:left;">Visit Type</th>
                <th style="padding:10px;text-align:left;">Provider</th>
                <th style="padding:10px;text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$consultations): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:15px;">No records found</td>
                </tr>
            <?php else: foreach ($consultations as $c): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;"><?= htmlspecialchars($c['date_of_consultation']) ?></td>
                    <td style="padding:10px;"><?= $c['consultation_time'] ?? '—' ?></td>
                    <td style="padding:10px;text-align:left;">
                        <?= htmlspecialchars($c['last_name'] . ', ' . $c['first_name']) ?>
                    </td>
                    <td style="padding:10px;"><?= htmlspecialchars($c['visit_type'] ?? '—') ?></td>
                    <td style="padding:10px;"><?= htmlspecialchars($c['name_of_attending_provider']) ?></td>
                    <td style="padding:10px;text-align:center;">
                        <a href="view.php?id=<?= $c['record_id'] ?>" style="padding:4px 8px;background:#43a047;color:#fff;border-radius:3px;text-decoration:none;">
                            View 
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>

    <p style="margin-top:15px;color:#555;">Total Records: <strong><?= count($consultations) ?></strong></p>

</body>
</html>

