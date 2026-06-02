<section class="hero">
    {{-- 1. Container Background Slider (Kosongkan, akan diisi JS dari Database) --}}
    <div class="hero-bg-slider" id="heroBgSlider">
        {{-- Fallback jika JS gagal load --}}
        <div class="hero-bg-slide active" style="background-image: url('{{ asset('assets/landing/images/Background_1.jpg') }}')"></div>
    </div>
    
    <div class="hero-bg-overlay"></div>
    <div class="hero-particles" id="particles"></div>
    
    {{-- 2. Konten Hero--}}
    <div class="hero-content">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            <span>Portal Resmi Digital</span>
        </div>
        <div class="hero-logo-container">
            <img src="{{ asset('assets/landing/images/PKK-Logo.png') }}" alt="PKK Logo" class="hero-logo">
        </div>
        <h1>Selamat Datang di Portal<br><span class="highlight">PKK Kabupaten Toba</span></h1>
        <p class="hero-subtitle">Melayani masyarakat Kabupaten Toba melalui transformasi digital untuk pemberdayaan keluarga dan kesejahteraan masyarakat yang lebih baik.</p>
        <a onclick="document.getElementById('quickAccess').scrollIntoView({behavior:'smooth'})" class="hero-cta">
            Jelajahi Layanan
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17l9.2-9.2M17 17V7.8H7.8"/></svg>
        </a>
    </div>
    
    {{-- 3. Indicators/Dots (Akan diisi JS sesuai jumlah slide) --}}
    <div class="hero-slider-indicators" id="sliderIndicators"></div>
</section>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    try {
        // Fetch data dari API yang kita buat sebelumnya
        const response = await fetch('/api/v1/hero-slider');
        const result = await response.json();
        
        const sliderContainer = document.getElementById('heroBgSlider');
        const indicatorsContainer = document.getElementById('sliderIndicators');
        
        if (result.success && result.data && result.data.length > 0) {
            const slidesData = result.data;
            const settings = result.settings || {};
            
            // Reset container
            sliderContainer.innerHTML = '';
            indicatorsContainer.innerHTML = '';
            
            // 1. Generate Slides dari Database
            slidesData.forEach((slide, index) => {
                const slideDiv = document.createElement('div');
                slideDiv.className = `hero-bg-slide ${index === 0 ? 'active' : ''}`;
                slideDiv.style.backgroundImage = `url('${slide.image_url}')`;
                slideDiv.dataset.duration = slide.display_duration * 1000; // Simpan durasi (ms)
                sliderContainer.appendChild(slideDiv);
                
                // 2. Generate Dots
                const dotDiv = document.createElement('div');
                dotDiv.className = `slider-dot ${index === 0 ? 'active' : ''}`;
                dotDiv.dataset.index = index;
                dotDiv.addEventListener('click', () => window.goToSlide(index));
                indicatorsContainer.appendChild(dotDiv);
            });
            
            // 3. Initialize Logic Slider
            initHeroSliderLogic(slidesData, settings);
            
        } else {
            // Jika kosong/error, pakai gambar statis default Anda
            console.log('Slider kosong, menggunakan fallback statis.');
        }
    } catch (error) {
        console.error('Error loading hero slider:', error);
    }
    
    // --- Logic Slider Internal ---
    function initHeroSliderLogic(slides, settings) {
        let currentSlide = 0;
        const slideElements = document.querySelectorAll('.hero-bg-slide');
        const dotElements = document.querySelectorAll('.slider-dot');
        let slideTimer = null;
        
        window.goToSlide = function(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            
            // Pindah class active
            slideElements[currentSlide].classList.remove('active');
            slideElements[index].classList.add('active');
            
            dotElements[currentSlide].classList.remove('active');
            dotElements[index].classList.add('active');
            
            currentSlide = index;
            restartTimer();
        };
        
        function startTimer() {
            if (slideTimer) clearTimeout(slideTimer);
            // Ambil durasi dari slide aktif (sesuai settingan admin)
            const duration = parseInt(slideElements[currentSlide].dataset.duration) || 5000;
            slideTimer = setTimeout(() => window.goToSlide(currentSlide + 1), duration);
        }
        
        function restartTimer() {
            startTimer();
        }
        
        function pauseTimer() {
            if (slideTimer) clearTimeout(slideTimer);
        }
        
        // Cek pengaturan auto_play dari admin
        if (settings.auto_play !== false && slides.length > 1) {
            startTimer();
            
            // Pause saat mouse hover
            const heroSection = document.querySelector('.hero');
            if (heroSection) {
                heroSection.addEventListener('mouseenter', pauseTimer);
                heroSection.addEventListener('mouseleave', startTimer);
            }
        }
    }
});
</script>