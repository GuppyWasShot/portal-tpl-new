<?php
/**
 * views/public/galeri.php
 * 
 * Halaman galeri karya dengan filter & search
 * Data dari: KaryaController->showGaleri()
 * 
 * Variables tersedia:
 * - $karya_list: array of karya
 * - $filters: array filter yang sedang aktif
 * - $page_title: string
 */

include __DIR__ . '/../layouts/header_public.php';

// Ambil kategori untuk filter
use PortalTPL\Models\Category;
$categoryModel = new Category();
$categories = $categoryModel->getAllCategories();
?>

<!-- Hero Section -->
<div class="hero" style="padding: 80px 40px 60px; background: linear-gradient(135deg, #e8d9f5 0%, #d4c5eb 100%);">
    <div class="hero-content" style="text-align: center;">
        <h1 style="font-size: 48px; color: #2d1b69; margin-bottom: 20px;">
            Galeri <span style="color: #e91e8c;">Karya Mahasiswa</span>
        </h1>
        <p style="color: #666; font-size: 18px;">
            Jelajahi inovasi dan kreativitas mahasiswa Teknologi Rekayasa Perangkat Lunak
        </p>
    </div>
</div>

<!-- Filter & Search Section -->
<div style="max-width: 1400px; margin: 0 auto; padding: 40px 20px;">
    
    <form method="GET" action="<?php echo base_url('galeri'); ?>" style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin-bottom: 40px;">
        
        <!-- Search Bar -->
        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: 600; color: #2d1b69; margin-bottom: 10px;">
                🔍 Cari Karya
            </label>
            <div style="display: flex; gap: 10px;">
                <input type="text" 
                       name="search" 
                       value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                       placeholder="Cari berdasarkan judul, pembuat, atau deskripsi..."
                       style="flex: 1; padding: 15px 20px; border: 2px solid #e0e0e0; border-radius: 25px; font-size: 15px;">
                <button type="submit" 
                        style="padding: 15px 30px; background: #2d1b69; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                    Cari
                </button>
            </div>
        </div>
        
        <!-- Sort & Category -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            
            <!-- Sort -->
            <div>
                <label style="display: block; font-weight: 600; color: #2d1b69; margin-bottom: 10px;">
                    Urutkan
                </label>
                <select name="sort" 
                        onchange="this.form.submit()"
                        style="width: 100%; padding: 12px 20px; border: 2px solid #e0e0e0; border-radius: 15px; font-size: 14px;">
                    <option value="terbaru" <?php echo ($filters['sort'] ?? '') === 'terbaru' ? 'selected' : ''; ?>>Terbaru</option>
                    <option value="terlama" <?php echo ($filters['sort'] ?? '') === 'terlama' ? 'selected' : ''; ?>>Terlama</option>
                    <option value="judul_asc" <?php echo ($filters['sort'] ?? '') === 'judul_asc' ? 'selected' : ''; ?>>Judul (A-Z)</option>
                    <option value="judul_desc" <?php echo ($filters['sort'] ?? '') === 'judul_desc' ? 'selected' : ''; ?>>Judul (Z-A)</option>
                    <option value="rating" <?php echo ($filters['sort'] ?? '') === 'rating' ? 'selected' : ''; ?>>Rating Tertinggi</option>
                </select>
            </div>
            
            <!-- Category Toggle Button -->
            <div>
                <label style="display: block; font-weight: 600; color: #2d1b69; margin-bottom: 10px;">
                    Filter Kategori
                </label>
                <button type="button" 
                        onclick="document.getElementById('categoryFilter').style.display = document.getElementById('categoryFilter').style.display === 'none' ? 'block' : 'none'"
                        style="width: 100%; padding: 12px 20px; background: white; border: 2px solid #e0e0e0; border-radius: 15px; cursor: pointer; text-align: left; font-size: 14px;">
                    <?php 
                    $selectedCount = is_array($filters['kategori'] ?? []) ? count($filters['kategori']) : 0;
                    echo $selectedCount > 0 ? "$selectedCount kategori dipilih" : "Semua Kategori";
                    ?> ▼
                </button>
            </div>
        </div>
        
        <!-- Category Checkboxes (Hidden by default) -->
        <div id="categoryFilter" style="display: none; padding: 20px; background: #f9f9f9; border-radius: 15px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                <?php foreach ($categories as $cat): ?>
                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; background: white; border-radius: 10px; border: 2px solid transparent; transition: all 0.3s;"
                       onmouseover="this.style.borderColor='<?php echo $cat['warna_hex']; ?>'"
                       onmouseout="this.style.borderColor='transparent'">
                    <input type="checkbox" 
                           name="kategori[]" 
                           value="<?php echo $cat['id_kategori']; ?>"
                           <?php echo in_array($cat['id_kategori'], $filters['kategori'] ?? []) ? 'checked' : ''; ?>
                           onchange="this.form.submit()"
                           style="margin-right: 10px;">
                    <span style="color: <?php echo $cat['warna_hex']; ?>; font-weight: 600;">
                        <?php echo htmlspecialchars($cat['nama_kategori']); ?>
                    </span>
                </label>
                <?php endforeach; ?>
            </div>
            
            <?php if ($selectedCount > 0): ?>
            <div style="margin-top: 15px; text-align: center;">
                <a href="<?php echo base_url('galeri'); ?>" 
                   style="color: #e91e8c; text-decoration: underline; font-size: 14px;">
                    Hapus semua filter
                </a>
            </div>
            <?php endif; ?>
        </div>
        
    </form>
    
    <!-- Results Info -->
    <div style="margin-bottom: 30px; text-align: center;">
        <p style="color: #666; font-size: 16px;">
            Menampilkan <strong style="color: #2d1b69;"><?php echo count($karya_list); ?></strong> karya
            <?php if (!empty($filters['search'])): ?>
                untuk pencarian "<strong><?php echo htmlspecialchars($filters['search']); ?></strong>"
            <?php endif; ?>
        </p>
    </div>
    
    <!-- Karya Grid -->
    <?php if (!empty($karya_list)): ?>
    <div class="projects-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
        
        <?php foreach ($karya_list as $karya): ?>
        <div class="project-card" 
             onclick="window.location='<?php echo base_url('detail?id=' . $karya['id_project']); ?>'"
             style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s;"
             onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.2)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.1)'">
            
            <!-- Thumbnail -->
            <div class="project-image" style="width: 100%; height: 220px; position: relative; overflow: hidden;">
                <?php if (!empty($karya['snapshot_url'])): ?>
                    <img src="<?php echo base_url($karya['snapshot_url']); ?>" 
                         alt="<?php echo htmlspecialchars($karya['judul']); ?>"
                         style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #a8b5f0 0%, #c8b5f0 100%); display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 64px;">📦</span>
                    </div>
                <?php endif; ?>
                
                <!-- Rating Badge -->
                <?php if ($karya['avg_rating']): ?>
                <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); padding: 5px 12px; border-radius: 20px; display: flex; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                    <span style="color: #ffd700; margin-right: 5px;">⭐</span>
                    <span style="font-weight: 600; color: #2d1b69;">
                        <?php echo number_format($karya['avg_rating'], 1); ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Content -->
            <div class="project-content" style="padding: 25px;">
                
                <!-- Categories -->
                <?php if ($karya['kategori']): 
                    $kategori_arr = explode(', ', $karya['kategori']);
                    $warna_arr = explode(',', $karya['warna']);
                ?>
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 15px;">
                    <?php foreach ($kategori_arr as $idx => $kat): 
                        $warna = $warna_arr[$idx] ?? '#2d1b69';
                    ?>
                    <span style="background: <?php echo $warna; ?>20; color: <?php echo $warna; ?>; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        <?php echo htmlspecialchars($kat); ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Title -->
                <h3 class="project-title" style="font-size: 20px; color: #2d1b69; margin-bottom: 12px; font-weight: 700; line-height: 1.3;">
                    <?php echo htmlspecialchars($karya['judul']); ?>
                </h3>
                
                <!-- Description -->
                <p class="project-description" style="color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                    <?php echo htmlspecialchars(substr($karya['deskripsi'], 0, 120)); ?>...
                </p>
                
                <!-- Footer -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #f0f0f0;">
                    <span style="color: #999; font-size: 12px;">
                        <?php echo htmlspecialchars($karya['pembuat']); ?>
                    </span>
                    <span style="color: #e91e8c; font-weight: 600; font-size: 14px;">
                        Lihat Detail →
                    </span>
                </div>
            </div>
            
        </div>
        <?php endforeach; ?>
        
    </div>
    
    <?php else: ?>
    <!-- Empty State -->
    <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
        <div style="font-size: 80px; margin-bottom: 20px;">😕</div>
        <h3 style="font-size: 28px; color: #2d1b69; margin-bottom: 15px;">Tidak ada karya ditemukan</h3>
        <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
            <?php if (!empty($filters['search']) || !empty($filters['kategori'])): ?>
                Coba ubah filter atau kata kunci pencarian Anda
            <?php else: ?>
                Belum ada karya yang dipublikasikan
            <?php endif; ?>
        </p>
        <?php if (!empty($filters['search']) || !empty($filters['kategori'])): ?>
        <a href="<?php echo base_url('galeri'); ?>" 
           style="display: inline-block; padding: 15px 30px; background: #2d1b69; color: white; text-decoration: none; border-radius: 25px; font-weight: 600;">
            Lihat Semua Karya
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
</div>

<?php include __DIR__ . '/../layouts/footer_public.php'; ?>