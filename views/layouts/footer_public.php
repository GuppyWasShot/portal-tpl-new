</main>
    <!-- Main Content End -->

    <!-- Footer -->
    <footer id="kontak">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <div style="background: #412358; width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <img src="<?php echo asset('img/logo_tpl.png'); ?>" 
                             alt="Logo TPL" 
                             style="width: 70%; height: 70%; object-fit: contain; filter: brightness(0) invert(1);">
                    </div>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Tautan</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="https://ipb.ac.id" target="_blank" rel="noopener">IPB University</a></li>
                    <li><a href="https://sv.ipb.ac.id" target="_blank" rel="noopener">Sekolah Vokasi IPB</a></li>
                    <li><a href="<?php echo base_url('tentang'); ?>">Tentang TPL</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Alamat & Kontak</h3>
                <p>
                    Jl. Kumbang No.14, Kelurahan Babakan,<br>
                    Kecamatan Bogor Tengah, Kota Bogor,<br>
                    Jawa Barat 16128
                </p>
                <p style="margin-top: 1rem;">
                    <strong>Email:</strong> tpl@apps.ipb.ac.id<br>
                    <strong>Telp:</strong> (0251) 8378277
                </p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> - Program Studi Teknologi Rekayasa Perangkat Lunak</div>
            <div class="social-icons">
                <a href="https://instagram.com/tpl_ipb" target="_blank" rel="noopener" aria-label="Instagram">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <a href="https://www.linkedin.com/school/sekolah-vokasi-ipb" target="_blank" rel="noopener" aria-label="LinkedIn">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" style="display: none;" aria-label="Back to top">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
    
    <style>
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: #412358;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .back-to-top:hover {
            background: #e91e8c;
            transform: translateY(-5px);
        }
        
        .back-to-top svg {
            width: 24px;
            height: 24px;
        }
        
        @media (max-width: 768px) {
            .back-to-top {
                bottom: 1rem;
                right: 1rem;
                width: 40px;
                height: 40px;
            }
        }
    </style>
    
    <!-- JavaScript -->
    <script>
        // Back to top button
        const backToTopBtn = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Auto hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Close mobile menu on scroll
        window.addEventListener('scroll', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu && mobileMenu.classList.contains('open')) {
                mobileMenu.classList.remove('open');
                mobileMenu.classList.add('closed');
            }
        });
    </script>
    
</body>
</html>