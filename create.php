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
    <title>Form Pendaftaran Mahasiswa</title>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nrp = input($_POST["nrp"]);
        $nama = input($_POST["nama"]);
        $kampus = input($_POST["kampus"]);
        $jurusan = input($_POST["jurusan"]);
        $no_hp = input($_POST["no_hp"]);
        $alamat = input($_POST["alamat"]);
        $lagufav = input($_POST["lagufav"]);

        $target_dir = "uploads/"; 
        $filemp3 = basename($_FILES["filemp3"]["name"]);
        $target_file = $target_dir . $filemp3;
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($fileType != "mp3") {
            echo "<div class='alert'>Hanya support file MP3.</div>";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["filemp3"]["tmp_name"], $target_file)) {
                $filemp3_path = $target_file;

                $sql = "INSERT INTO mahasiswa (nrp, nama, kampus, jurusan, no_hp, alamat, lagufav, filemp3) 
                        VALUES ('$nrp', '$nama', '$kampus', '$jurusan', '$no_hp', '$alamat', '$lagufav', '$filemp3_path')";

                $hasil = mysqli_query($kon, $sql);

                if ($hasil) {
                    echo "<div class='alert'>Data berhasil disimpan.</div>";
                } else {
                    echo "<div class='alert'>Data Gagal disimpan.</div>";
                }
            } else {
                echo "<div class='alert'>Error mengupload file.</div>";
            }
        }
    }
    ?>
    <h2>Input Data Mahasiswa</h2>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>NRP:</label>
            <input type="text" name="nrp" placeholder="Masukan NRP" required />
        </div>
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" placeholder="Masukan Nama" required />
        </div>
        <div class="form-group">
            <label>Kampus:</label>
            <input type="text" name="kampus" placeholder="Masukan Nama Kampus" required />
        </div>
        <div class="form-group">
            <label>Jurusan:</label>
            <input type="text" name="jurusan" placeholder="Masukan Jurusan" required />
        </div>
        <div class="form-group">
            <label>No HP:</label>
            <input type="text" name="no_hp" placeholder="Masukan No HP" required />
        </div>
        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" rows="5" placeholder="Masukan Alamat" required></textarea>
        </div>
        <div class="form-group">
            <label>Lagu Favorit:</label>
            <input type="text" name="lagufav" placeholder="Masukan Judul Lagu" required />
        </div>
        <div class="form-group">
            <label>File MP3:</label>
            <input type="file" name="filemp3" accept=".mp3" required />
        </div>
        <button type="submit" name="submit">Submit</button>
    </form>
</div>
</body>
</html>
