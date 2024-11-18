<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit;
}

include "koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Update Mahasiswa</title>
    <style>
        .container {
            width: 60%;
            margin: 0 auto;
            padding: 2em;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 1em;
        }

        .form-group {
            margin-bottom: 1em;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5em;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.5em 1em;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            color: red;
            text-align: center;
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    include "koneksi.php";

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (isset($_GET['nrp'])) {
        $nrp = input($_GET["nrp"]);

        $sql = "SELECT * FROM mahasiswa WHERE nrp='$nrp'";
        $hasil = mysqli_query($kon, $sql);
        $data = mysqli_fetch_assoc($hasil);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nrp = htmlspecialchars($_POST["nrp"]);
        $nama = input($_POST["nama"]);
        $kampus = input($_POST["kampus"]);
        $jurusan = input($_POST["jurusan"]);
        $no_hp = input($_POST["no_hp"]);
        $alamat = input($_POST["alamat"]);
        $lagufav = input($_POST["lagufav"]);
        
        // File upload handling
        $target_dir = "uploads/";
        $uploadOk = 1;
        $filemp3 = $data['filemp3']; 

        if (!empty($_FILES["filemp3"]["name"])) {
            $target_file = $target_dir . basename($_FILES["filemp3"]["name"]);
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if ($fileType != "mp3") {
                echo "<div class='alert'>Hanya support file MP3.</div>";
                $uploadOk = 0;
            } else {
                if (!empty($filemp3) && file_exists($filemp3)) {
                    unlink($filemp3);
                }

                if (move_uploaded_file($_FILES["filemp3"]["tmp_name"], $target_file)) {
                    $filemp3 = $target_file; 
                } else {
                    echo "<div class='alert'>Error mengupload file.</div>";
                    $uploadOk = 0;
                }
            }
        }

        if ($uploadOk == 1) {
            $sql = "UPDATE mahasiswa SET
                    nama='$nama',
                    kampus='$kampus',
                    jurusan='$jurusan',
                    no_hp='$no_hp',
                    alamat='$alamat',
                    lagufav='$lagufav',
                    filemp3='$filemp3'
                    WHERE nrp='$nrp'";

            $hasil = mysqli_query($kon, $sql);

            if ($hasil) {
                header("Location: index.php");
            } else {
                echo "<div class='alert'>Data gagal disimpan.</div>";
            }
        }
    }
    ?>
    
    <h2>Update Data Mahasiswa</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?nrp=" . $data['nrp']); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>NRP:</label>
            <input type="text" name="nrp" value="<?php echo $data['nrp']; ?>" readonly />
        </div>
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?php echo $data['nama']; ?>" placeholder="Masukan Nama" required />
        </div>
        <div class="form-group">
            <label>Kampus:</label>
            <input type="text" name="kampus" value="<?php echo $data['kampus']; ?>" placeholder="Masukan Nama Kampus" required />
        </div>
        <div class="form-group">
            <label>Jurusan:</label>
            <input type="text" name="jurusan" value="<?php echo $data['jurusan']; ?>" placeholder="Masukan Jurusan" required />
        </div>
        <div class="form-group">
            <label>No HP:</label>
            <input type="text" name="no_hp" value="<?php echo $data['no_hp']; ?>" placeholder="Masukan No HP" required />
        </div>
        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" rows="5" placeholder="Masukan Alamat" required><?php echo $data['alamat']; ?></textarea>
        </div>
        <div class="form-group">
            <label>Lagu Favorit (Judul):</label>
            <input type="text" name="lagufav" value="<?php echo $data['lagufav']; ?>" placeholder="Masukan Judul Lagu" required />
        </div>
        <div class="form-group">
            <label>File mp3:</label>
            <input type="file" name="filemp3" accept=".mp3" />
            <small>Current file: <?php echo basename($data['filemp3']); ?></small>
        </div>

        <button type="submit" name="submit">Submit</button>
    </form>
</div>
</body>
</html>
