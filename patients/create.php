<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$zones = $pdo->query("
    SELECT z.zone_id, z.zone_number,
           CONCAT(hw.first_name,' ',hw.last_name) AS worker
    FROM zone z
    LEFT JOIN health_worker hw ON z.assigned_worker_id = hw.worker_id
    ORDER BY z.zone_number
")->fetchAll();
?>

<div style="font-family:Arial;background:#f4f6f8;padding:20px;min-height:100vh;">
    <div style="max-width:800px;margin:auto;background:#fff;padding:25px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">

        <h2 style="color:#1e88e5;margin-top:0;">Patient Enrolment Record</h2>

        <form method="POST" action="store.php">
            <style>
                .step input, .step select, .step textarea {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 10px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    box-sizing: border-box; /* Important for padding */
                }
                .btn {
                    padding: 10px 15px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    text-decoration: none;
                }
                .btn-primary { background: #1e88e5; color: #fff; }
                .btn-secondary { background: #777; color: #fff; }
            </style>

            <div class="step">
                <h3>Patient Identification</h3>
                <input name="last_name" placeholder="Last Name" required>
                <input name="first_name" placeholder="First Name" required>
                <input name="middle_name" placeholder="Middle Name">
                <input name="suffix" placeholder="Suffix">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" required>
            </div>

            <div class="step" style="display:none">
                <h3>Address & Household</h3>
                <textarea name="residential_address" placeholder="Complete Address" rows="3" required></textarea>
                
                <select name="zone_id" required>
                    <option value="">Select Zone</option>
                    <?php foreach ($zones as $z): ?>
                        <option value="<?= $z['zone_id'] ?>">
                            Zone <?= $z['zone_number'] ?> — <?= htmlspecialchars($z['worker']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input name="household_contact" placeholder="Household Contact">
            </div>

            <div class="step" style="display:none">
                <h3>Enrollment</h3>
                <input name="patient_enrollment_id" placeholder="Enrollment ID (optional)">
                <p style="color:#777;font-size:13px;margin-top:-5px;">Leave blank to auto-generate.</p>
            </div>

            <br>
            <div style="display:flex;justify-content:space-between;">
                <button type="button" class="btn btn-secondary" onclick="prev()">Back</button>
                <div>
                    <button type="button" class="btn btn-primary" onclick="next()">Next</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary" style="display:none;background:#2e7d32;">Save Patient</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let current = 0;
    const steps = document.querySelectorAll('.step');
    const submitBtn = document.getElementById('submitBtn');
    const nextBtn = document.querySelector('button[onclick="next()"]');
    const prevBtn = document.querySelector('button[onclick="prev()"]');

    function showStep(i) {
        steps.forEach((s, idx) => s.style.display = idx === i ? 'block' : 'none');
        submitBtn.style.display = i === steps.length - 1 ? 'inline' : 'none';
        nextBtn.style.display = i === steps.length - 1 ? 'none' : 'inline';
        prevBtn.style.display = i === 0 ? 'none' : 'inline';
    }

    function next() {
        if (current < steps.length - 1) current++;
        showStep(current);
    }

    function prev() {
        if (current > 0) current--;
        showStep(current);
    }
    
    // Initial display
    showStep(current);
</script>

Would you like me to add validation to ensure the form steps cannot be bypassed without filling in required fields?