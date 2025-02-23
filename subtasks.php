<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Cek apakah task_id ada di URL
if (!isset($_GET['task_id'])) {
    header("Location: index.php");
    exit();
}

$task_id = $_GET['task_id'];

// Ambil nama tugas utama
$stmt = $pdo->prepare("SELECT task FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$task_id, $user_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header("Location: index.php");
    exit();
}

// Tambah subtugas ke database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subtask'])) {
    $subtask = trim($_POST['subtask']);
    $priority = isset($_POST['priority']) ? intval($_POST['priority']) : 2;
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

    if (!empty($subtask)) {
        $stmt = $pdo->prepare("INSERT INTO subtasks (task_id, subtask, priority, deadline, status) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$task_id, $subtask, $priority, $deadline]);
        header("Location: subtasks.php?task_id=" . $task_id);
        exit();
    }
}

// Hapus subtugas dari database
if (isset($_GET['delete_subtask'])) {
    $subtask_id = intval($_GET['delete_subtask']);
    $stmt = $pdo->prepare("DELETE FROM subtasks WHERE id = ? AND task_id = ?");
    $stmt->execute([$subtask_id, $task_id]);
    header("Location: subtasks.php?task_id=" . $task_id);
    exit();
}

// Update status subtask
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subtask_id'], $_POST['status'])) {
    $subtask_id = intval($_POST['subtask_id']);
    $status = intval($_POST['status']); // 1 = selesai

    $stmt = $pdo->prepare("UPDATE subtasks SET status = ? WHERE id = ? AND task_id = ?");
    $stmt->execute([$status, $subtask_id, $task_id]);
    exit();
}

// Ambil semua subtugas
$stmt = $pdo->prepare("SELECT * FROM subtasks WHERE task_id = ? ORDER BY priority ASC, deadline IS NULL, deadline ASC");
$stmt->execute([$task_id]);
$subtasks = $stmt->fetchAll();

// Konversi angka prioritas ke teks
function getPriorityText($priority) {
    switch ($priority) {
        case 1:
            return "🔥 Tinggi";
        case 2:
            return "⚖️ Sedang";
        case 3:
            return "🟢 Rendah";
        default:
            return "❓ Tidak Diketahui";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Subtasks</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #D2B48C, #8B4513);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px;
        }
        .container {
            width: 80%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 18px; }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th { background: #8B4513; color: white; font-size: 20px; }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
            width: 100%;
            margin-top: 20px;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 18px;
        }
        button {
            background-color: #8B4513;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #5A2E0A;
        }
    </style>
    <script>
        function toggleStatus(checkbox, subtaskId) {
            if (checkbox.checked) {
                fetch("subtasks.php?task_id=<?= $task_id ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "subtask_id=" + subtaskId + "&status=1"
                }).then(() => {
                    checkbox.disabled = true;
                    document.getElementById('edit-' + subtaskId).style.display = 'none';
                });
            }
        }

        function deleteSubtask(subtaskId) {
            if (confirm("Apakah Anda yakin ingin menghapus subtugas ini?")) {
                window.location.href = "subtasks.php?task_id=<?= $task_id ?>&delete_subtask=" + subtaskId;
            }
        }

        window.onload = function() {
            var today = new Date().toISOString().split("T")[0];
            document.getElementById("deadline").setAttribute("min", today);
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Subtasks: <?= htmlspecialchars($task['task']) ?></h2>
        <a href="index.php" class="back-button" style="font-size: 18px; text-decoration: none; color: white;">🔙 Kembali</a>

        <!-- Form Tambah Subtugas -->
        <form method="POST">
            <input type="text" name="subtask" placeholder="Tambahkan subtugas baru" required>
            <input type="date" name="deadline" id="deadline">
            <select name="priority">
                <option value="1">🔥 Tinggi</option>
                <option value="2" selected>⚖️ Sedang</option>
                <option value="3">🟢 Rendah</option>
            </select>
            <button type="submit">➕ Tambah Subtugas</button>
        </form>

        <!-- Tabel Subtugas -->
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Subtugas</th>
                    <th>Prioritas</th>
                    <th>Tenggat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subtasks as $subtask): ?>
                    <tr>
                        <td>
                            <input type="checkbox" <?= $subtask['status'] ? 'checked disabled' : '' ?> onclick="toggleStatus(this, <?= $subtask['id'] ?>)">
                        </td>
                        <td><?= htmlspecialchars($subtask['subtask']) ?></td>
                        <td><?= getPriorityText($subtask['priority']) ?></td>
                        <td><?= $subtask['deadline'] ? date('d-m-Y', strtotime($subtask['deadline'])) : '⏳ Tidak Ada' ?></td>
                        <td>
                            <?php if ($subtask['status'] == 0): ?>
                                <a id="edit-<?= $subtask['id'] ?>" href="edit_subtask.php?subtask_id=<?= $subtask['id'] ?>&task_id=<?= $task_id ?>"><button>✏️ Edit</button></a>
                            <?php endif; ?>
                            <button onclick="deleteSubtask(<?= $subtask['id'] ?>)">🗑️ Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
