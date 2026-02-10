<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$jawaban = "";

if (isset($_POST['tanya'])) {
    $pertanyaan = strtolower($_POST['pertanyaan']);

    // 1. Deteksi jenis olahraga
    if (strpos($pertanyaan, 'badminton') !== false) {
        $jenis = 'Badminton';
    } elseif (strpos($pertanyaan, 'futsal') !== false) {
        $jenis = 'Futsal';
    } elseif (strpos($pertanyaan, 'tennis') !== false) {
        $jenis = 'Tennis';
    } else {
        $jawaban = "Maaf, saya tidak menemukan jenis olahraga yang dimaksud.";
    }

    // 2. Deteksi tanggal
    if (strpos($pertanyaan, 'hari ini') !== false) {
        $tanggal = date('Y-m-d');
    } elseif (strpos($pertanyaan, 'besok lusa') !== false) {
        $tanggal = date('Y-m-d', strtotime('+2 day'));
    } elseif (strpos($pertanyaan, 'besok') !== false) {
        $tanggal = date('Y-m-d', strtotime('+1 day'));
    } elseif (preg_match('/tanggal\\s*(\\d{1,2})/', $pertanyaan, $match)) {
        $hari = $match[1];
        $bulan = date('m');
        $tahun = date('Y');
        $tanggal = $tahun . '-' . $bulan . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
    } else {
        $tanggal = date('Y-m-d'); // default
    }

    // 3. Deteksi jam
    preg_match('/jam\\s*(\\d{1,2})/', $pertanyaan, $matchJam);
    if (isset($matchJam[1])) {
        $jam_mulai_tanya = str_pad($matchJam[1], 2, '0', STR_PAD_LEFT) . ":00:00";
        $jam_selesai_tanya = date('H:i:s', strtotime($jam_mulai_tanya . ' +1 hour'));
    } else {
        $jawaban = "Maaf, saya tidak menemukan jam yang dimaksud.";
    }

    // 4. Proses cek database
    if ($jawaban == "") {

        $qCourt = mysqli_query($conn, "SELECT * FROM courts WHERE jenis_olahraga='$jenis'");

        if (mysqli_num_rows($qCourt) == 0) {
            $jawaban = "Maaf, tidak ditemukan lapangan untuk olahraga $jenis.";
        } else {

            $jawaban = "Jadwal $jenis tanggal " . date('d-m-Y', strtotime($tanggal)) . " jam " . substr($jam_mulai_tanya,0,5) . ":<br>";

            while ($court = mysqli_fetch_assoc($qCourt)) {

                $court_id = $court['id'];
                $nama = $court['nama_lapangan'];

                // cek bentrok jam
                $cek = "SELECT * FROM bookings 
                        WHERE court_id='$court_id' 
                        AND tanggal='$tanggal'
                        AND jam_mulai < '$jam_selesai_tanya'
                        AND jam_selesai > '$jam_mulai_tanya'";

                $res = mysqli_query($conn, $cek);

                if (mysqli_num_rows($res) > 0) {
                    $b = mysqli_fetch_assoc($res);
                    $jawaban .= "- $nama: TERISI (".$b['jam_mulai']." - ".$b['jam_selesai'].")<br>";
                } else {
                    $jawaban .= "- $nama: MASIH KOSONG<br>";
                }
            }
        }
    }
}
?>

<h2>Chatbot BookNPlay</h2>

<form method="post">
  <input type="text" name="pertanyaan" placeholder="Contoh: jadwal badminton besok jam 15" style="width:380px" required>
  <button name="tanya">Tanya</button>
</form>

<?php if ($jawaban != "") { ?>
  <p><strong>Jawaban Chatbot:</strong><br><?php echo $jawaban; ?></p>
<?php } ?>

<br>
<a href="dashboard.php">Kembali ke Dashboard</a>
