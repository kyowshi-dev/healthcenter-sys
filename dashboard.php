<?php
require_once __DIR__ . '/config/db.php';

/* ===============================
   DASHBOARD METRICS
=============================== */

// Total patients
$totalPatients = $pdo->query("SELECT COUNT(*) FROM patient")->fetchColumn();

// Total consultations today
$todayConsults = $pdo->query("
    SELECT COUNT(*) FROM consultation_record
    WHERE date_of_consultation = CURDATE()
")->fetchColumn();

// Total consultations (overall)
$totalConsults = $pdo->query("
    SELECT COUNT(*) FROM consultation_record
")->fetchColumn();

// Zones covered
$totalZones = $pdo->query("SELECT COUNT(*) FROM zone")->fetchColumn();
?>

<h4 class="mb-4">Dashboard</h4>

<div class="row g-3">

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Patients</h6>
                <h3 class="fw-bold"><?= $totalPatients ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Consultations Today</h6>
                <h3 class="fw-bold"><?= $todayConsults ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Consultations</h6>
                <h3 class="fw-bold"><?= $totalConsults ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Zones Covered</h6>
                <h3 class="fw-bold"><?= $totalZones ?></h3>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">

<div class="row g-3">

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title">Quick Actions</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="/bhcis/index.php?page=patients">Register / View Patients</a>
                    </li>
                    <li class="list-group-item">
                        <a href="/bhcis/index.php?page=consultations">New Consultation</a>
                    </li>
                    <li class="list-group-item">
                        <a href="/bhcis/index.php?page=prenatal">Prenatal Records</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title">System Notes</h6>
                <p class="text-muted small">
                    This system supports daily Barangay Health Center operations,
                    patient consultations, and DOH-aligned reporting.
                </p>
            </div>
        </div>
    </div>

</div>
