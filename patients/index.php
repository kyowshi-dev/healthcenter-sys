<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/auth_check.php";

function computeAge(string $dob): int
{
    return (new DateTime($dob))->diff(new DateTime())->y;
}

$search = trim($_GET["search"] ?? "");
$zone = $_GET["zone"] ?? "";

$zones = $pdo
    ->query("SELECT zone_id, zone_number FROM zone ORDER BY zone_number")
    ->fetchAll();

$sql = "
SELECT p.patient_id, p.first_name, p.last_name, p.middle_name, p.suffix,
       p.date_of_birth, z.zone_number
FROM patient p
LEFT JOIN household h ON p.household_id = h.household_id
LEFT JOIN zone z ON h.zone_id = z.zone_id
WHERE 1=1
";

$params = [];

if ($search !== "") {
    $sql .= " AND (p.first_name LIKE ? OR p.last_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($zone !== "") {
    $sql .= " AND z.zone_id = ?";
    $params[] = $zone;
}

$sql .= " ORDER BY p.last_name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="font-family:Arial;background:#f4f6f8;padding:20px;">
    <div style="max-width:1100px;margin:auto;">

        <h2 style="color:#1e88e5;">Patients</h2>

        <form method="GET" style="display:flex;gap:10px;margin-bottom:15px;">
            <input type="hidden" name="page" value="patients">

            <input name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name" style="padding:8px;width:200px;">

            <select name="zone" style="padding:8px;">
                <option value="">All Zones</option>
                <?php foreach ($zones as $z): ?>
                    <option value="<?= $z["zone_id"] ?>" <?= $zone == $z["zone_id"] ? "selected" : "" ?>>
                        Zone <?= $z["zone_number"] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button style="padding:8px 14px;background:#1e88e5;color:#fff;border:none;border-radius:4px;cursor:pointer;">
                Filter
            </button>

            <a href="?page=patients" style="padding:8px 14px;background:#777;color:#fff;border-radius:4px;text-decoration:none;">
                Reset
            </a>
        </form>

        <a href="/bhcis/patients/create.php" style="display:inline-block;margin-bottom:15px;padding:10px 16px;background:#1e88e5;color:#fff;border-radius:4px;text-decoration:none;">
            + Register Patient
        </a>

        <table style="width:100%;border-collapse:collapse;background:#fff;box-shadow:0 2px 6px rgba(0,0,0,.1)">
            <thead style="background:#e3f2fd;">
                <tr>
                    <th style="padding:10px;text-align:left;">Name</th>
                    <th style="text-align:center">Age</th>
                    <th style="text-align:center">Zone</th>
                    <th style="text-align:center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$patients): ?>
                    <tr>
                        <td colspan="4" style="padding:15px;text-align:center;">No records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($patients as $p): ?>
                        <tr>
                            <td style="padding:10px;border-bottom:1px solid #eee;">
                                <?= htmlspecialchars($p["last_name"] . ", " . $p["first_name"]) ?>
                            </td>
                            <td style="text-align:center;border-bottom:1px solid #eee;">
                                <?= computeAge($p["date_of_birth"]) ?>
                            </td>
                            <td style="text-align:center;border-bottom:1px solid #eee;">
                                <?= $p["zone_number"] ?? "—" ?>
                            </td>
                            <td style="text-align:center;border-bottom:1px solid #eee;">
                                <a href="/bhcis/patients/view.php?id=<?= $p["patient_id"] ?>">View</a> |
                                <a href="/bhcis/patients/edit.php?id=<?= $p["patient_id"] ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>