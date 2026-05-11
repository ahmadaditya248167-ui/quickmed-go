<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_telepon = $_POST['nomor_telepon'];
    $poli          = $_POST['poli'];

    $poliKode = [
        'Poli Umum'            => 'A',
        'Poli Anak'            => 'B',
        'Poli Gigi'            => 'C',
        'Poli Kandungan'       => 'D',
        'Poli Kulit & Kelamin' => 'E',
    ];

    $huruf = $poliKode[$poli] ?? 'X';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM antrian WHERE poli = ? AND DATE(waktu) = CURDATE()");
    $stmt->execute([$poli]);
    $jumlah = $stmt->fetchColumn();
    $nomor  = $jumlah + 1;

    $kode_antrian = $huruf . '-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare("INSERT INTO antrian (nomor_telepon, poli, kode_antrian) VALUES (?, ?, ?)");
    $stmt->execute([$nomor_telepon, $poli, $kode_antrian]);

    header("Location: kartu-antrian.php?telepon=" . urlencode($nomor_telepon) . "&poli=" . urlencode($poli) . "&nomor=" . urlencode($kode_antrian));
    exit;
} else {
    header("Location: index.html");
    exit;
}
?>