<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #343a40;
            padding: 1em;
            color: white;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 2em auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 2em;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h4 {
            text-align: center;
            font-size: 1.8em;
            margin-top: 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1em;
            font-size: 0.95em;
            color: #555;
        }

        th, td {
            padding: 0.75em;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: normal;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .table-danger {
            background-color: #f8d7da;
        }

        .btn {
            padding: 0.5em 1em;
            border: none;
            color: white;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9em;
            margin: 0.2em;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 1em;
            margin: 1em 0;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            text-align: center;
        }

        .logout-btn {
            display: block;
            text-align: center;
            margin-top: 2em;
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 0.85em;
            }
            .btn {
                font-size: 0.8em;
                padding: 0.4em 0.8em;
            }
        }
    </style>
</head>
<title>Daftar Mahasiswa</title>
<body>

    <div class="navbar">
        DATA MAHASISWA
    </div>

    <div class="container">
        <br>
        <h4>DAFTAR MAHASISWA</h4>

        <?php
            include "koneksi.php";

            if (isset($_GET['nrp'])) {
                $nrp = htmlspecialchars($_GET["nrp"]);
                $sql = "DELETE FROM mahasiswa WHERE nrp='$nrp'";
                $hasil = mysqli_query($kon, $sql);

                if ($hasil) {
                    header("Location:index.php");
                } else {
                    echo "<div class='alert alert-danger'> Data gagal dihapus.</div>";
                }
            }
        ?>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NRP</th>
                    <th>Nama</th>
                    <th>Kampus</th>
                    <th>Jurusan</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Lagu Favorit</th>
                    <?php if ($_SESSION["role"] == "admin") : ?>
                        <th colspan="3">Aksi</th>
                    <?php endif; ?>
                    <?php if ($_SESSION["role"] == "user") : ?>
                        <th colspan="1">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    include "koneksi.php";
                    $sql = "SELECT * FROM mahasiswa ORDER BY nrp DESC";
                    $hasil = mysqli_query($kon, $sql);
                    $no = 0;

                    while ($data = mysqli_fetch_array($hasil)) {
                        $no++;
                ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data["nrp"]; ?></td>
                        <td><?php echo $data["nama"]; ?></td>
                        <td><?php echo $data["kampus"]; ?></td> 
                        <td><?php echo $data["jurusan"]; ?></td>
                        <td><?php echo $data["no_hp"]; ?></td>
                        <td><?php echo $data["alamat"]; ?></td>     
                        <td><?php echo $data["lagufav"]; ?></td>
                        <?php if ($_SESSION["role"] == "admin") : ?>
                            <td>
                                <a href="update.php?nrp=<?php echo $data['nrp']; ?>" class="btn btn-warning" role="button">Update</a>
                                <a href="delete.php?nrp=<?php echo $data['nrp']; ?>" class="btn btn-danger" role="button">Delete</a>
                            </td>
                        <?php endif; ?>
                            <td>
                                <a href="<?php echo $data['filemp3']; ?>" download="<?php echo $data['filemp3']; ?>" class="btn btn-primary" role="button">Download</a>
                            </td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>

        <?php if ($_SESSION["role"] == "admin") : ?>
            <a href="create.php" class="btn btn-primary">Tambah Data</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
