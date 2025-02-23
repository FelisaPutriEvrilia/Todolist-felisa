<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['subtask_id']) || !isset($_GET['task_id'])) {
    header("Location: index.php");
    exit();
}

$subtask_id = intval($_GET['subtask_id']);
$task_id = intval($_GET['task_id']);

$stmt = $pdo->prepare("SELECT subtask, deadline FROM subtasks WHERE id = ? AND task_id = ?");
$stmt->execute([$subtask_id, $task_id]);
$subtask = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subtask) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_subtask'], $_POST['deadline'])) {
    $new_subtask = trim($_POST['new_subtask']);
    $new_deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

    $today = date('Y-m-d');

    // **Validasi backend (tolak jika tanggal sebelum hari ini)**
    if ($new_deadline && strtotime($new_deadline) < strtotime($today)) {
        die("❌ ERROR: Tanggal deadline tidak boleh sebelum hari ini!");
    }

    $stmt = $pdo->prepare("UPDATE subtasks SET subtask = ?, deadline = ? WHERE id = ? AND task_id = ?");
    $stmt->execute([$new_subtask, $new_deadline, $subtask_id, $task_id]);

    header("Location: subtasks.php?task_id=" . $task_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Subtugas</title>
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
            max-width: 500px;
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
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
            width: 100%;
            margin-top: 20px;
        }
        input, button {
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
        document.addEventListener("DOMContentLoaded", function() {
            var today = new Date().toISOString().split("T")[0];
            var deadlineInput = document.getElementById("deadline");

            if (deadlineInput) {
                deadlineInput.setAttribute("min", today);
                
                deadlineInput.addEventListener("change", function() {
                    if (this.value < today) {
                        alert("Tanggal tidak boleh sebelum hari ini!");
                        this.value = today;
                    }
                });
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Edit Subtugas</h2>
        <a href="subtasks.php?task_id=<?= $task_id ?>" class="back-button" style="font-size: 18px; text-decoration: none; color: white;">🔙 Kembali</a>

        <form method="POST">
            <input type="text" name="new_subtask" value="<?= htmlspecialchars($subtask['subtask']) ?>" required>
            <input type="date" name="deadline" id="deadline" value="<?= $subtask['deadline'] ?>" required>
            <button type="submit">✅ Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
