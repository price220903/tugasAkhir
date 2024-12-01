<?php
include '../database.php';
session_start();

date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu ke WIB

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$message = "";
$timeout = 300; // waktu dalam detik (5 menit)

// Logika timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset(); // Hapus semua variabel sesi 
    session_destroy(); // Hancurkan sesi 
    $message = "Sesi telah berakhir karena tidak ada aktivitas selama 5 menit.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = '../login.php';</script>";
    exit;
}
$_SESSION['last_activity'] = time(); // Perbarui waktu aktivitas terakhir

// Logika logout
if (isset($_GET['logout'])) {
    session_unset(); // Hapus semua variabel sesi
    session_destroy(); // Hancurkan sesi
    $message = "Log out berhasil.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = '../login.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $no_telp = $_POST['no_telp'];

    $sql = "INSERT INTO user (nama, email, password, no_telp, role) VALUES ('$nama', '$email', '$password', '$no_telp', 'admin')";

    if ($conn->query($sql) === TRUE) {
        $message = "Tambah Admin berhasil.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'index.php';</script>";
        exit;
    } else {
        $message = "Tambah Admin gagal, coba lagi.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = 'tambahAdmin.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lelang</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="icon" href="../asset/logo.png" type="image/x-icon">
    <script>
        var timeout = 300000; // waktu dalam milidetik (5 menit) 
        var logoutTimer;

        function resetTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(logout, timeout);
        }

        function logout() {
            window.location.href = '?logout=true'; // arahkan ke halaman logout 
        }
        document.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
    </script>
</head>

<body>
    <nav id='menu'>
        <ul>
            <li><a href='index.php' class="active">Sistem Lelang</a></li>
            <li><a href='profil.php?id=<?php echo $_SESSION['id']; ?>'>Profil</a></li>
            <li><a href='tambahAdmin.php'>Tambah Admin</a></li>
            <li><a href='listUser.php'>List Pengguna</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
    <div class="wrapper">
        <div class="card">
            <h1>Tambah Admin</h1>
            <form method="post" action="tambahAdmin.php">
                <label for="email">Email</label>
                <input type="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" name="password" required>
                <label for="nama">Name</label>
                <input type="text" name="nama" required>
                <label for="no_telp">Phone Number</label>
                <input type="number" name="no_telp" required>
                <button type="submit" style="margin-bottom: 20px;">Tambah</button>
            </form>
        </div>
    </div>
</body>

</html>