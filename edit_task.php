<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data tugas berdasarkan ID
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    $task = $stmt->fetch();

    if (!$task) {
        die("Tugas tidak ditemukan!");
    }
}

// Update tugas setelah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $task_id = $_POST['task_id'];
    $task = $_POST['task'];

    $stmt = $pdo->prepare("UPDATE tasks SET task = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$task, $task_id, $user_id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right, #D2B48C, #8B4513);
            color: white;
        }
        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #fff;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, button {
            padding: 15px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 18px;
            outline: none;
        }
        input {
            background: rgba(255, 255, 255, 0.8);
            font-size: 18px;
            text-align: center;
        }
        button {
            background-color: rgb(139, 69, 19);
            color: black;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: rgb(210, 180, 140);
            transform: scale(1.05);
        }
        .back-link {
            display: block;
            margin-top: 15px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }
        .back-link:hover {
            color: rgb(139, 69, 19);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Edit Tugas</h2>
        <form method="POST">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <input type="text" name="task" value="<?= htmlspecialchars($task['task']) ?>" required placeholder="Nama Tugas">
            <button type="submit">✔ Simpan Perubahan</button>
        </form>
        <a href="index.php" class="back-link">⬅ Kembali ke Daftar</a>
    </div>
</body>
</html>
