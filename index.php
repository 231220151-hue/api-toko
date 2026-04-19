<?php
$fpb = null;
$keterangan = "";
$a = "";
$b = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = (int) $_POST["angka1"];
    $b = (int) $_POST["angka2"];

    $x = abs($a);
    $y = abs($b);

    while ($y != 0) {
        $sisa = $x % $y;
        $x = $y;
        $y = $sisa;
    }

    $fpb = $x;

    if ($fpb == 1) {
        $keterangan = "Kedua angka RELATIF PRIMA";
    } else {
        $keterangan = "TIDAK RELATIF PRIMA";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator FPB Web</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 25px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .hasil {
            background: #e9f7ef;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kalkulator FPB Web (PHP)</h2>
        <form method="POST">
            <label>Angka 1 (Nilai A)</label>
            <input type="number" name="angka1" required value="<?php echo $a; ?>">

            <label>Angka 2 (Nilai B)</label>
            <input type="number" name="angka2" required value="<?php echo $b; ?>">

            <button type="submit">Hitung FPB</button>
        </form>

        <?php if ($fpb !== null) { ?>
            <div class="hasil">
                <h3>Hasil Perhitungan</h3>
                <p><strong>FPB dari <?php echo $a; ?> dan <?php echo $b; ?> adalah: <?php echo $fpb; ?></strong></p>
                <p><?php echo $keterangan; ?></p>
            </div>
        <?php } ?>
    </div>
</body>
</html>