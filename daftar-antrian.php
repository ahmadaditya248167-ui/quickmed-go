<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Nomor Antrian – QuickMed Go</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="daftar-antrian.css"/>
</head>
<body>

<?php
require 'koneksi.php';

$poliList = [
  ['nama' => 'Poli Umum',            'huruf' => 'A', 'icon' => '🩺', 'id' => 'nomorA'],
  ['nama' => 'Poli Anak',            'huruf' => 'B', 'icon' => '🧸', 'id' => 'nomorB'],
  ['nama' => 'Poli Gigi',            'huruf' => 'C', 'icon' => '🦷', 'id' => 'nomorC'],
  ['nama' => 'Poli Kandungan',       'huruf' => 'D', 'icon' => '🤰', 'id' => 'nomorD'],
  ['nama' => 'Poli Kulit & Kelamin', 'huruf' => 'E', 'icon' => '🔬', 'id' => 'nomorE'],
];

$nomorAntrian = [];
foreach ($poliList as $p) {
    $stmt = $pdo->prepare("SELECT kode_antrian FROM antrian WHERE poli = ? AND DATE(waktu) = CURDATE() ORDER BY id DESC LIMIT 1");
    $stmt->execute([$p['nama']]);
    $hasil = $stmt->fetchColumn();
    $nomorAntrian[$p['huruf']] = $hasil ?: ($p['huruf'] . '-000');
}
?>

<!-- SIDEBAR -->
<aside class="sidebar">
  <!-- 1. LOGO POJOK KIRI ATAS - diganti -->
  <div class="sidebar-logo">
    <img src="9121dd35-7bd9-4e90-8689-2466e4dd6735-removebg-preview.png"
         alt="Logo Klinik QuickMed"
         style="height: 60px; width: auto; object-fit: contain;" />
  </div>

  <nav class="sidebar-nav">
    <a href="index.html" class="nav-item">
      <i class="fa-solid fa-users"></i> Antrian
    </a>
    <a href="daftar-antrian.php" class="nav-item active">
      <i class="fa-solid fa-bars"></i> Daftar Antrian
    </a>
    <a href="kartu-antrian.php" class="nav-item">
      <i class="fa-solid fa-id-card-clip"></i> Kartu Antrian
    </a>
  </nav>

  <div class="sidebar-info">
    <div class="info-title">KLINIK QUICKMED</div>
    <div class="info-row">
      <span class="info-label">Alamat</span>
      <span class="info-value">Jalan Cik Di Tiro 30, Kel. Terban, Kec. Gondokusuman, Kota Yogyakarta, Prop. Daerah Istimewa Yogyakarta</span>
    </div>
    <div class="info-row">
      <span class="info-label">Telepon</span>
      <span class="info-value">(0274) 514014, 514845, 563333 (hunting)</span>
    </div>
    <div class="info-row">
      <span class="info-label">Faksimile</span>
      <span class="info-value">(0274) 564583</span>
    </div>
    <div class="info-row">
      <span class="info-label">Email</span>
      <span class="info-value"><a href="mailto:admin@quickmed.or.id">admin@quickmed.or.id</a></span>
    </div>
  </div>
</aside>

<!-- MAIN -->
<div class="main-content">
  <header class="top-header">
    <div class="hex-overlay"></div>
    <div class="header-logo">
      <!-- 2. LOGO TENGAH ATAS - diganti -->
      <div class="header-icon" style="background:transparent; box-shadow:none;">
        <img src="logo (1).png"
             alt="Logo QuickMed Go"
             style="height: 56px; width: auto; object-fit: contain;" />
      </div>
      <span class="header-title">QuickMed Go</span>
    </div>
  </header>

  <div class="hero-area">
    <div class="page-title-wrap">
      <h1 class="page-title">Daftar Nomor Antrian</h1>
    </div>

    <div class="cards-grid">

      <?php foreach ($poliList as $p): ?>
      <div class="queue-card">
        <!-- 3. LOGO POJOK KIRI ATAS TIAP KARTU - diganti -->
        <div class="card-logo">
          <img src="9121dd35-7bd9-4e90-8689-2466e4dd6735-removebg-preview.png"
               alt="Logo"
               style="height: 36px; width: auto; object-fit: contain;" />
        </div>
        <div class="card-label">Nomor Antrian</div>
        <div class="card-number"><?= $nomorAntrian[$p['huruf']] ?></div>
        <div class="card-divider">
          <svg viewBox="0 0 200 24" preserveAspectRatio="none">
            <path d="M0,12 C40,0 80,24 120,12 C160,0 180,20 200,12 L200,24 L0,24 Z" fill="#b8e8f5"/>
          </svg>
        </div>
        <div class="card-poli">
          <span class="card-poli-icon"><?= $p['icon'] ?></span> <?= $p['nama'] ?>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</div>

</body>
</html>