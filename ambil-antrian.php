<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_telepon = $_POST['nomor_telepon'];
    $poli          = $_POST['poli'];

    // Mapping poli ke huruf kode
    $poliKode = [
        'Poli Umum'            => 'A',
        'Poli Anak'            => 'B',
        'Poli Gigi'            => 'C',
        'Poli Kandungan'       => 'D',
        'Poli Kulit & Kelamin' => 'E',
    ];

    $huruf = $poliKode[$poli] ?? 'X';

    // Hitung jumlah antrian poli ini hari ini
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM antrian WHERE poli = ? AND DATE(waktu) = CURDATE()");
    $stmt->execute([$poli]);
    $jumlah = $stmt->fetchColumn();
    $nomor  = $jumlah + 1;

    // Format kode antrian, contoh: A-001
    $kode_antrian = $huruf . '-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO antrian (nomor_telepon, poli, kode_antrian) VALUES (?, ?, ?)");
    $stmt->execute([$nomor_telepon, $poli, $kode_antrian]);

    // Redirect ke kartu antrian dengan data
    header("Location: kartu-antrian.php?telepon=" . urlencode($nomor_telepon) . "&poli=" . urlencode($poli) . "&nomor=" . urlencode($kode_antrian));
    exit;
} else {
    header("Location: index.html");
    exit;
}
?>