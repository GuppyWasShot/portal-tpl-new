<?php
/**
 * views/public/tentang.php
 * 
 * Halaman Tentang TPL
 */

include __DIR__ . '/../layouts/header_public.php';
?>

<!-- Hero Section -->
<section class="hero" style="padding: 5rem 2rem 4rem;">
    <div style="max-width: 900px; margin: 0 auto; text-align: center;">
        <h1 style="font-size: 3rem; color: #2d1b69; margin-bottom: 1.5rem;">
            Tentang <span style="color: #e91e8c;">TPL</span>
        </h1>
        <p style="font-size: 1.2rem; color: #666; line-height: 1.8;">
            Mari mengenal program studi Teknologi Rekayasa Perangkat Lunak Sekolah Vokasi IPB University
        </p>
    </div>
</section>

<!-- Content Section -->
<section style="background: white; padding: 4rem 2rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        
        <!-- Profil -->
        <div style="background: #f9f9f9; padding: 2rem; border-radius: 1rem; margin-bottom: 3rem;">
            <h2 style="color: #2d1b69; margin-bottom: 1.5rem; font-size: 2rem;">Profil Program Studi</h2>
            <p style="color: #666; line-height: 1.8; margin-bottom: 1rem;">
                Program Studi Teknologi Rekayasa Perangkat Lunak (D4) merupakan salah satu program studi di Sekolah Vokasi IPB University yang fokus pada pengembangan kompetensi mahasiswa di bidang teknologi informasi dan pengembangan perangkat lunak.
            </p>
            <p style="color: #666; line-height: 1.8;">
                Program studi ini dirancang untuk menghasilkan lulusan yang siap kerja dengan kemampuan praktis dalam merancang, mengembangkan, dan mengelola sistem perangkat lunak.
            </p>
        </div>

        <!-- Visi -->
        <div style="margin-bottom: 3rem;">
            <h2 style="color: #2d1b69; margin-bottom: 1.5rem; font-size: 2rem;">Visi</h2>
            <p style="color: #666; line-height: 1.8; padding: 1.5rem; background: #e8f5e9; border-left: 4px solid #059669; border-radius: 0.5rem;">
                Menjadi program studi yang terdepan dan unggul di Indonesia dalam menyiapkan tenaga profesional sebagai Sarjana Terapan bidang Teknologi Rekayasa Perangkat Lunak yang ikut mendukung penerapan teknologi di bidang pertanian, kelautan, dan biosains tropika tahun 2030.
            </p>
        </div>

        <!-- Misi -->
        <div style="margin-bottom: 3rem;">
            <h2 style="color: #2d1b69; margin-bottom: 1.5rem; font-size: 2rem;">Misi</h2>
            <ol style="color: #666; line-height: 1.8; padding-left: 2rem;">
                <li style="margin-bottom: 1rem;">
                    Menyelenggarakan pendidikan vokasi yang berkualitas untuk menyiapkan tenaga yang terampil dan terdidik di bidang Teknologi Rekayasa Perangkat Lunak yang berkontribusi terhadap bidang pertanian dalam arti luas sesuai dengan kebutuhan dunia kerja.
                </li>
                <li style="margin-bottom: 1rem;">
                    Menyelenggarakan penelitian terapan di bidang informatika mengacu pada kebutuhan, ilmu dan teknologi yang terus berkembang serta berkontribusi dalam bidang pertanian, kelautan, dan biosains tropika.
                </li>
                <li style="margin-bottom: 1rem;">
                    Menyelenggarakan pengabdian kepada masyarakat dalam menyebarluaskan hasil pendidikan dan hasil penelitian terapan di bidang informatika.
                </li>
                <li style="margin-bottom: 1rem;">
                    Menjalin kerjasama dengan lembaga pemerintahan dan/atau instansi terkait dengan pencapaian kompetensi mahasiswa, penelitian terapan, pengabdian kepada masyarakat, dan lapangan pekerjaan bagi lulusan.
                </li>
            </ol>
        </div>

        <!-- Capaian Pembelajaran (Simplified) -->
        <div style="margin-bottom: 3rem;">
            <h2 style="color: #2d1b69; margin-bottom: 1.5rem; font-size: 2rem;">Kompetensi Lulusan</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid #e8d9f5;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💻</div>
                    <h3 style="color: #412358; font-size: 1.1rem; margin-bottom: 0.5rem;">Pengembangan Software</h3>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">Mampu merancang dan mengembangkan sistem perangkat lunak yang berkualitas</p>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid #ffd8e5;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                    <h3 style="color: #412358; font-size: 1.1rem; margin-bottom: 0.5rem;">Analisis Data</h3>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">Mampu menganalisis dan memanfaatkan data untuk sistem informasi</p>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid #dbeafe;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎨</div>
                    <h3 style="color: #412358; font-size: 1.1rem; margin-bottom: 0.5rem;">UI/UX Design</h3>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">Mampu menciptakan desain produk digital yang user-friendly</p>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid #d1fae5;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔧</div>
                    <h3 style="color: #412358; font-size: 1.1rem; margin-bottom: 0.5rem;">Project Management</h3>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">Mampu mengelola proses pengembangan produk digital</p>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid #fef3c7;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🌐</div>
                    <h3 style="color: #412358; font-size: 1.1rem; margin-bottom: 0.5rem;">Web & Mobile</h3>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">Mahir dalam pengembangan aplikasi web dan mobile</p>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid #e9d5ff;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🤝</div>
                    <h3 style="color: #412358; font-size: 1.1rem; margin-bottom: 0.5rem;">Kolaborasi</h3>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">Mampu bekerja dalam tim interdisipliner</p>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
            <div style="background: linear-gradient(135deg, #412358 0%, #6b4a8c 100%); color: white; padding: 2rem; border-radius: 1rem; text-align: center;">
                <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">8</h3>
                <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9);">Semester</p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem; color: rgba(255,255,255,0.8);">Lama Studi (4 Tahun)</p>
            </div>
            
            <div style="background: linear-gradient(135deg, #e91e8c 0%, #ff4da6 100%); color: white; padding: 2rem; border-radius: 1rem; text-align: center;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: white;">S.Tr.Kom.</h3>
                <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9);">Gelar</p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem; color: rgba(255,255,255,0.8);">Sarjana Terapan Komputer</p>
            </div>
        </div>

    </div>
</section>

<!-- CTA Section -->
<section style="background: #f9fafb; padding: 4rem 2rem; text-align: center;">
    <div style="max-width: 700px; margin: 0 auto;">
        <h2 style="color: #2d1b69; font-size: 2rem; margin-bottom: 1rem;">
            Tertarik Bergabung?
        </h2>
        <p style="color: #666; font-size: 1.1rem; margin-bottom: 2rem;">
            Kunjungi website resmi Sekolah Vokasi IPB untuk informasi lengkap tentang pendaftaran
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="https://sv.ipb.ac.id" target="_blank" rel="noopener" class="btn btn-primary">
                Website Sekolah Vokasi
            </a>
            <a href="<?php echo base_url('galeri'); ?>" class="btn btn-outline">
                Lihat Karya Mahasiswa
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer_public.php'; ?>