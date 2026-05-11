<?php
require 'koneksi.php'; // sudah konek ke admin_antrian

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

    // Cek jadwal & kuota dari admin_antrian
    $stmt_kuota = $pdo->prepare("
        SELECT ss.max_quota, ss.is_open, s.id as service_id
        FROM service_schedules ss
        JOIN services s ON ss.service_id = s.id
        WHERE s.name = ? AND ss.date = CURDATE()
    ");
    $stmt_kuota->execute([$poli]);
    $jadwal = $stmt_kuota->fetch(PDO::FETCH_ASSOC);

    // Cek apakah jadwal ada
    if (!$jadwal) {
        header("Location: index.php?error=Jadwal+poli+belum+dibuka+oleh+admin");
        exit;
    }

    // Cek apakah poli buka
    if ($jadwal['is_open'] == 0) {
        header("Location: index.php?error=Poli+ini+sedang+tutup");
        exit;
    }

    // Cek jumlah antrian hari ini di tabel queues
    $stmt_count = $pdo->prepare("
        SELECT COUNT(*) FROM queues 
        WHERE service_id = ? AND DATE(created_at) = CURDATE()
    ");
    $stmt_count->execute([$jadwal['service_id']]);
    $jumlah = $stmt_count->fetchColumn();

    // Cek apakah sudah melebihi kuota
    if ($jumlah >= $jadwal['max_quota']) {
        header("Location: index.php?error=Kuota+antrian+poli+ini+sudah+penuh");
        exit;
    }

    $nomor        = $jumlah + 1;
    $kode_antrian = $huruf . '-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

    // Simpan ke tabel queues di admin_antrian (queue_number pakai angka saja)
    $stmt = $pdo->prepare("
        INSERT INTO queues (service_id, visitor_name, visitor_phone, queue_number, appointment_date, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, CURDATE(), 'menunggu', NOW(), NOW())
    ");
    $stmt->execute([
        $jadwal['service_id'],
        'Pasien QuickMed',
        $nomor_telepon,
        $nomor  // hanya angka, bukan 'A-001'
    ]);

    header("Location: kartu-antrian.php?telepon=" . urlencode($nomor_telepon) . "&poli=" . urlencode($poli) . "&nomor=" . urlencode($kode_antrian));
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>