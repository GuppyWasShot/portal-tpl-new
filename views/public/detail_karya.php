<?php
/**
 * views/public/detail_karya.php
 * 
 * File ini menggabungkan:
 * - Struktur HTML dari: front/detail-karya.html
 * - Data dari Controller: KaryaController->showDetail()
 * 
 * Variable yang tersedia dari Controller:
 * - $karya: array data karya
 * - $links: array links
 * - $files: array files
 * - $snapshots: array snapshots
 * - $documents: array documents
 * - $userRating: array|null rating user
 * - $page_title: string judul halaman
 */

// Include header (menggunakan layout dari front/index.html header)
include __DIR__ . '/../layouts/header_public.php';
?>

<!-- Main Content -->
<main class="main-content">
    <div class="content-grid">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Project Image - Ambil dari $karya['snapshot_url'] -->
            <div class="project-image">
                <?php if (!empty($karya['snapshot_url'])): ?>
                    <img src="/uploads/<?php echo htmlspecialchars($karya['snapshot_url']); ?>" 
                         alt="<?php echo htmlspecialchars($karya['judul']); ?>">
                <?php else: ?>
                    <!-- Placeholder jika tidak ada snapshot -->
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #a8b5f0 0%, #c8b5f0 100%); display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-size: 48px;">📷</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Title - Dari $karya['judul'] -->
            <h1 class="project-title"><?php echo htmlspecialchars($karya['judul']); ?></h1>

            <!-- Tags/Categories - Parse dari $karya['kategori'] dan $karya['warna'] -->
            <div class="project-tags">
                <?php 
                if ($karya['kategori']): 
                    $kategori_arr = explode(', ', $karya['kategori']);
                    $warna_arr = explode(',', $karya['warna']);
                    
                    foreach($kategori_arr as $idx => $kat): 
                        $warna = $warna_arr[$idx] ?? '#2d1b69';
                ?>
                    <span class="tag" style="background-color: <?php echo $warna; ?>20; color: <?php echo $warna; ?>">
                        <?php echo htmlspecialchars($kat); ?>
                    </span>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>

            <!-- Description Section -->
            <h2 class="section-title">Deskripsi Karya</h2>
            <p class="description-text">
                <?php echo nl2br(htmlspecialchars($karya['deskripsi'])); ?>
            </p>

            <!-- Action Buttons - Dari $links -->
            <div class="action-buttons">
                <?php foreach ($links as $link): ?>
                    <a href="<?php echo htmlspecialchars($link['url']); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn btn-outline">
                        <?php echo htmlspecialchars($link['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Additional Snapshots Gallery -->
            <?php if (count($snapshots) > 1): ?>
            <h2 class="section-title">Galeri Foto</h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px;">
                <?php foreach ($snapshots as $snapshot): ?>
                    <div style="position: relative; height: 150px; border-radius: 10px; overflow: hidden;">
                        <img src="/uploads/<?php echo htmlspecialchars($snapshot['file_path']); ?>" 
                             alt="Snapshot"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Documents Section -->
            <?php if (!empty($documents)): ?>
            <h2 class="section-title">File Pendukung</h2>
            <div style="margin-bottom: 30px;">
                <?php foreach ($documents as $doc): ?>
                    <a href="/uploads/<?php echo htmlspecialchars($doc['file_path']); ?>" 
                       download
                       style="display: block; padding: 15px; background: #f9f9f9; border-radius: 10px; margin-bottom: 10px; text-decoration: none; color: #2d1b69;">
                        <strong>📄 <?php echo htmlspecialchars($doc['label']); ?></strong><br>
                        <small style="color: #666;"><?php echo htmlspecialchars($doc['nama_file']); ?> • <?php echo round($doc['file_size']/1024, 1); ?> KB</small>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Detail Karya Card -->
            <div class="info-card">
                <h3>Detail Karya</h3>
                
                <!-- Tahun -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#2d1b69" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="info-content">
                        <h4>Tahun</h4>
                        <p><?php echo date('Y', strtotime($karya['tanggal_selesai'])); ?></p>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#2d1b69" stroke-width="2">
                            <path d="M20 7h-9"/>
                            <path d="M14 17H5"/>
                            <circle cx="17" cy="17" r="3"/>
                            <circle cx="7" cy="7" r="3"/>
                        </svg>
                    </div>
                    <div class="info-content">
                        <h4>Kategori</h4>
                        <p><?php echo htmlspecialchars($karya['kategori']); ?></p>
                    </div>
                </div>

                <!-- Pembuat -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#2d1b69" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div class="info-content">
                        <h4>Pembuat</h4>
                        <p><?php echo htmlspecialchars($karya['pembuat']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Rating Card -->
            <div class="info-card">
                <h3>Penilaian Rata-Rata</h3>
                
                <!-- Average Rating Display -->
                <?php if ($karya['avg_rating']): ?>
                <div class="stars">
                    <?php 
                    $avg = round($karya['avg_rating']);
                    for ($i = 1; $i <= 5; $i++): 
                    ?>
                        <span class="star <?php echo $i <= $avg ? '' : 'empty'; ?>">★</span>
                    <?php endfor; ?>
                </div>
                <p style="text-align: center; color: #666; margin-top: 10px;">
                    <?php echo number_format($karya['avg_rating'], 1); ?> dari 5 
                    (<?php echo $karya['total_rating']; ?> penilaian)
                </p>
                <?php else: ?>
                <p style="text-align: center; color: #999; padding: 20px 0;">
                    Belum ada penilaian
                </p>
                <?php endif; ?>

                <!-- User Rating Form -->
                <div class="rating-form">
                    <?php if ($userRating): ?>
                        <!-- User sudah rating -->
                        <div style="background: #e8f5e9; padding: 15px; border-radius: 10px; text-align: center;">
                            <p style="color: #2e7d32; margin: 0;">
                                ✅ Anda sudah memberikan rating: 
                                <strong><?php echo $userRating['skor']; ?> bintang</strong>
                            </p>
                        </div>
                    <?php else: ?>
                        <!-- Form rating -->
                        <h4>Beri Penilaian</h4>
                        <form method="POST" action="/submit-rating" id="ratingForm">
                            <input type="hidden" name="id_project" value="<?php echo $karya['id_project']; ?>">
                            <input type="hidden" name="skor" id="skorInput" required>
                            
                            <div class="star-input" id="starRating">
                                <span data-value="1">☆</span>
                                <span data-value="2">☆</span>
                                <span data-value="3">☆</span>
                                <span data-value="4">☆</span>
                                <span data-value="5">☆</span>
                            </div>
                            
                            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                                Kirim Penilaian
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- JavaScript untuk star rating (dari front/detail-karya.html) -->
<script>
    const stars = document.querySelectorAll('.star-input span');
    const skorInput = document.getElementById('skorInput');
    const submitBtn = document.getElementById('submitBtn');

    if (stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-value'));
                skorInput.value = rating;
                submitBtn.disabled = false;
                
                // Update star colors
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                        s.textContent = '★';
                    } else {
                        s.classList.remove('active');
                        s.textContent = '☆';
                    }
                });
            });
        });
    }
</script>

<?php
// Include footer (dari front/index.html footer)
include __DIR__ . '/../layouts/footer_public.php';
?>