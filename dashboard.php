<?php
// Example: Fetch counts from your database
$patients = $conn->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$consults = $conn->query("SELECT COUNT(*) FROM consultations WHERE DATE(consultation_date) = CURDATE()")->fetchColumn();
$prenatal = $conn->query("SELECT COUNT(*) FROM prenatal_records")->fetchColumn();
$immunization = $conn->query("SELECT COUNT(*) FROM immunization_records")->fetchColumn();
?>

<h2 style="margin-bottom:15px;">Dashboard Overview</h2>

<div style="display:flex; gap:15px; flex-wrap:wrap;">
<?php
$cards = [
    ['Patients', $patients, '#1e88e5'],
    ['Consultations Today', $consults, '#43a047'],
    ['Prenatal Records', $prenatal, '#fb8c00'],
    ['Immunizations', $immunization, '#8e24aa']
];

foreach ($cards as [$label, $count, $color]):
?>
<div style="
    flex:1;
    min-width:200px;
    background:white;
    padding:15px;
    border-left:6px solid <?= $color ?>;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
">
    <h3 style="margin:0; color:#555;"><?= $label ?></h3>
    <p style="font-size:26px; margin:5px 0 0;"><strong><?= $count ?></strong></p>
</div>
<?php endforeach; ?>
</div>
