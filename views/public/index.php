<?php
/**
 * views/public/index.php
 * 
 * Homepage Portal TPL
 * Data dari: KaryaController->showHome()
 * 
 * Variables tersedia:
 * - $karya_featured: array (6 karya terbaru)
 */

include __DIR__ . '/../layouts/header_public.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>
            Galeri Mahasiswa <em>TPL</em><br>
            <span class="highlight">Sekolah Vokasi<br>IPB University</span>
        </h1>
        <p>Ruang kreatif yang menghadirkan inovasi digital untuk masyarakat.</p>
        <div class="hero-buttons">
            <a href="<?php echo base_url('galeri'); ?>" class="btn btn-primary">
                Jelajahi Karya
            </a>
            <a href="<?php echo base_url('tentang'); ?>" class="btn btn-outline">
                Tentang TPL
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section style="background: white; padding: 3rem 2rem; text-align: center;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <p style="font-size: 1.1rem; color: #666; margin-bottom: 3rem;">
            Temukan <strong style="color: #412358;">100+ proyek mahasiswa</strong> dan karya akademis yang luar biasa.
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <!-- Website -->
            <div style="padding: 2rem; border-radius: 1rem; background: #f9f9f9;">
                <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: #e8d9f5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" fill="none" stroke="#412358" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <line x1="8" y1="21" x2="16" y2="21"/>
                        <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <h3 style="color: #412358; margin-bottom: 0.5rem;">Website & Portal</h3>
                <p style="color: #666; font-size: 0.9rem;">Proyek berbasis web dan sistem informasi interaktif</p>
            </div>
            
            <!-- Mobile -->
            <div style="padding: 2rem; border-radius: 1rem; background: #f9f9f9;">
                <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: #ffd8e5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" fill="none" stroke="#e91e8c" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="5" y="2" width="14" height="20" rx="2"/>
                        <line x1="12" y1="18" x2="12" y2="18"/>
                    </svg>
                </div>
                <h3 style="color: #412358; margin-bottom: 0.5rem;">Aplikasi Mobile</h3>
                <p style="color: #666; font-size: 0.9rem;">Inovasi aplikasi untuk Android dan iOS</p>
            </div>
            
            <!-- Documents -->
            <div style="padding: 2rem; border-radius: 1rem; background: #f9f9f9;">
                <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: #dbeafe; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <h3 style="color: #412358; margin-bottom: 0.5rem;">Karya Ilmiah</h3>
                <p style="color: #666; font-size: 0.9rem;">Jurnal dan laporan penelitian akademis</p>
            </div>
            
            <!-- Other -->
            <div style="padding: 2rem; border-radius: 1rem; background: #f9f9f9;">
                <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: #d1fae5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M12 14c-4 0-8 2-8 6v2h16v-2c0-4-4-6-8-6z"/>
                    </svg>
                </div>
                <h3 style="color: #412358; margin-bottom: 0.5rem;">Kolaborasi & IoT</h3>
                <p style="color: #666; font-size: 0.9rem;">Hardware, IoT, dan multimedia</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects -->
<?php if (!empty($karya_featured)): ?>
<section style="background: #f9fafb; padding: 4rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; color: #412358; margin-bottom: 1rem;">Karya Terbaru</h2>
            <p style="color: #666; font-size: 1.1rem;">Inovasi terbaru dari mahasiswa TPL</p>
        </div>
        
        <div class="projects-grid">
            <?php foreach ($karya_featured as $karya): ?>
            <div class="project-card" 
                 onclick="window.location='<?php echo base_url('detail?id=' . $karya['id_project']); ?>'"
                 style="cursor: pointer;">
                
                <!-- Thumbnail -->
                <div class="project-image">
                    <?php if (!empty($karya['snapshot_url'])): ?>
                        <img src="<?php echo base_url($karya['snapshot_url']); ?>" 
                             alt="<?php echo htmlspecialchars($karya['judul']); ?>">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #a8b5f0 0%, #c8b5f0 100%); display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 64px;">📦</span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Rating Badge -->
                    <?php if ($karya['avg_rating']): ?>
                    <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); padding: 5px 12px; border-radius: 20px; display: flex; align-items: center;">
                        <span style="color: #ffd700; margin-right: 5px;">⭐</span>
                        <span style="font-weight: 600; color: #2d1b69;">
                            <?php echo number_format($karya['avg_rating'], 1); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="project-content">
                    <!-- Categories -->
                    <?php if ($karya['kategori']): 
                        $kategori_arr = explode(', ', $karya['kategori']);
                        $warna_arr = explode(',', $karya['warna']);
                    ?>
                    <div class="project-tags">
                        <?php foreach ($kategori_arr as $idx => $kat): 
                            $warna = $warna_arr[$idx] ?? '#412358';
                        ?>
                        <span class="tag" style="background: <?php echo $warna; ?>20; color: <?php echo $warna; ?>;">
                            <?php echo htmlspecialchars($kat); ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <h3 class="project-title"><?php echo htmlspecialchars($karya['judul']); ?></h3>
                    <p class="project-description">
                        <?php echo htmlspecialchars(substr($karya['deskripsi'], 0, 120)); ?>...
                    </p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #f0f0f0;">
                        <span style="color: #999; font-size: 0.85rem;">
                            <?php echo htmlspecialchars($karya['pembuat']); ?>
                        </span>
                        <span style="color: #e91e8c; font-weight: 600; font-size: 0.9rem;">
                            Lihat Detail →
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-center; margin-top: 3rem;">
            <a href="<?php echo base_url('galeri'); ?>" class="btn btn-primary">
                Lihat Semua Karya
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section style="background: linear-gradient(135deg, #412358 0%, #6b4a8c 100%); color: white; padding: 4rem 2rem; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: white;">
            Mahasiswa TPL? <span style="color: #ffd8e5;">Publikasikan Karyamu!</span>
        </h2>
        <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9); margin-bottom: 2rem;">
            Hubungi admin untuk mempublikasikan karya Anda di portal ini
        </p>
        <a href="mailto:tpl@apps.ipb.ac.id" class="btn btn-secondary">
            Hubungi Admin
        </a>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer_public.php'; ?>