<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. Pratama Andal Dermaga - Stevedoring & Logistics</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        }
                    }
                }
            }
        }
    </script>

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(8, 47, 73, 0.8), rgba(8, 47, 73, 0.7)), url('https://images.unsplash.com/photo-1586528116311-ad8ed7c80a30?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="glass-nav fixed w-full z-50 transition-all duration-300 shadow-sm" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <div class="flex items-center space-x-3">
                    @if(file_exists(public_path('logo.png')))
                        <img src="{{ asset('logo.png') }}" alt="Logo PAD" class="h-12 w-auto">
                    @else
                        <div class="w-12 h-12 bg-brand-900 rounded-full flex items-center justify-center text-white font-black text-xl shadow-lg"> PAD </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="font-black text-xl md:text-2xl text-brand-950 tracking-tight leading-none uppercase">PT. Pratama</span>
                        <span class="font-bold text-sm md:text-base text-brand-600 tracking-wide leading-none uppercase mt-1">Andal Dermaga</span>
                    </div>
                </div>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-sm font-bold text-gray-700 hover:text-brand-600 transition uppercase tracking-wide">Beranda</a>
                    <a href="#tentang" class="text-sm font-bold text-gray-700 hover:text-brand-600 transition uppercase tracking-wide">Tentang</a>
                    <a href="#layanan" class="text-sm font-bold text-gray-700 hover:text-brand-600 transition uppercase tracking-wide">Layanan</a>
                    <a href="#kontak" class="text-sm font-bold text-gray-700 hover:text-brand-600 transition uppercase tracking-wide">Kontak</a>
                    
                    <div class="h-6 w-px bg-gray-300 mx-2"></div>

                    <a href="{{ route('dashboard.index') }}" class="group relative inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-200 bg-brand-600 border border-transparent rounded-full hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-600 shadow-md hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Portal Internal
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-grow pt-24">
        <!-- Hero Section -->
        <section id="beranda" class="hero-bg relative min-h-[90vh] flex items-center justify-center text-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full text-center">
                <span data-aos="fade-down" data-aos-duration="1000" class="inline-block py-1 px-3 rounded-full bg-brand-600/30 border border-brand-400/50 text-brand-100 text-sm font-bold tracking-widest uppercase mb-6 backdrop-blur-sm">
                    Logistik Terpercaya Sejak 2012
                </span>
                <h1 data-aos="fade-up" data-aos-duration="1200" class="text-5xl md:text-7xl font-black leading-tight mb-6 tracking-tight drop-shadow-2xl">
                    Melayani <span class="text-brand-400">Bongkar Muat</span><br>dengan Standar Ekstra
                </h1>
                <p data-aos="fade-up" data-aos-duration="1400" data-aos-delay="200" class="text-lg md:text-2xl text-gray-200 mb-10 max-w-3xl mx-auto leading-relaxed font-light drop-shadow-md">
                    Spesialis dalam manajemen logistik hewan ternak (PBB) dan kargo berat. Memastikan efisiensi, ketepatan waktu, dan integritas di setiap rantai pasok.
                </p>
                <div data-aos="fade-up" data-aos-duration="1600" data-aos-delay="400" class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="#layanan" class="bg-brand-600 text-white px-8 py-4 rounded-full font-bold shadow-lg hover:bg-brand-500 transition duration-300 transform hover:-translate-y-1 w-full sm:w-auto text-lg border border-brand-500">
                        Jelajahi Layanan
                    </a>
                    <a href="#kontak" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-full font-bold shadow-lg hover:bg-white hover:text-brand-900 transition duration-300 transform hover:-translate-y-1 w-full sm:w-auto text-lg border border-white/30">
                        Hubungi Kami
                    </a>
                </div>
            </div>
            
            <!-- Mouse Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce hidden md:block">
                <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </div>
        </section>

        <!-- Stats Section (Counters) -->
        <section class="bg-brand-950 py-12 border-b border-brand-900 relative z-20 shadow-2xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-brand-800/50">
                    <div data-aos="zoom-in" data-aos-delay="0">
                        <p class="text-4xl md:text-5xl font-black text-brand-400" class="counter">12+</p>
                        <p class="text-xs md:text-sm text-gray-400 mt-2 font-bold uppercase tracking-wider">Tahun Pengalaman</p>
                    </div>
                    <div data-aos="zoom-in" data-aos-delay="100">
                        <p class="text-4xl md:text-5xl font-black text-brand-400">98%</p>
                        <p class="text-xs md:text-sm text-gray-400 mt-2 font-bold uppercase tracking-wider">Tingkat Ketepatan Waktu</p>
                    </div>
                    <div data-aos="zoom-in" data-aos-delay="200">
                        <p class="text-4xl md:text-5xl font-black text-brand-400">45K+</p>
                        <p class="text-xs md:text-sm text-gray-400 mt-2 font-bold uppercase tracking-wider">Ritase Selesai</p>
                    </div>
                    <div data-aos="zoom-in" data-aos-delay="300">
                        <p class="text-4xl md:text-5xl font-black text-brand-400">50+</p>
                        <p class="text-xs md:text-sm text-gray-400 mt-2 font-bold uppercase tracking-wider">Mitra Importir</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="tentang" class="py-24 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="lg:w-1/2" data-aos="fade-right" data-aos-duration="1200">
                        <div class="relative">
                            <div class="absolute inset-0 bg-brand-600 transform translate-x-4 translate-y-4 rounded-2xl"></div>
                            <img src="https://images.unsplash.com/photo-1596733430284-f7437764b1a9?q=80&w=2070&auto=format&fit=crop" alt="Stevedoring sapi" class="relative rounded-2xl shadow-xl z-10 w-full h-auto object-cover border-4 border-white">
                            
                            <!-- Floating Badge -->
                            <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-2xl z-20 animate-pulse border border-gray-100 flex items-center hidden md:flex">
                                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Sertifikasi</p>
                                    <p class="font-black text-gray-900">Operasional Resmi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-1/2" data-aos="fade-left" data-aos-duration="1200">
                        <h4 class="text-brand-600 font-bold tracking-widest uppercase mb-2 text-sm">Tentang Pad</h4>
                        <h2 class="text-4xl md:text-5xl font-black text-brand-950 mb-6 leading-tight">Membangun Konektifitas Rantai Pasok Nusantara</h2>
                        <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                            Bermula dari komitmen kuat terhadap kelancaran arus barang, **PT. Pratama Andal Dermaga** secara spesifik mendedikasikan layanannya di bidang *Stevedoring* (Bongkar Muat) dan manajemen landbound logistik.
                        </p>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Kami sangat diakui dalam penanganan *live cattle* (sapi impor) berkat komitmen kami terhadap Animal Welfare serta sistem *tracking* yang memastikan keakuratan muatan darat.
                        </p>
                        
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-brand-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700 font-medium">Integritas dan transparansi pendataan timbang kendaraan.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-brand-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700 font-medium">Armada logistik mitra yang mumpuni di berbagai rute lokal.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-brand-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700 font-medium">Sistem digital dan pelaporan *real-time*.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="layanan" class="py-24 bg-gray-50 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                    <h4 class="text-brand-600 font-bold tracking-widest uppercase mb-2 text-sm">Layanan Inti</h4>
                    <h2 class="text-4xl font-black text-brand-950 mb-4">Solusi Menyeluruh untuk Portofolio Bisnis Anda</h2>
                    <p class="text-gray-600 text-lg">Dari lepas pantai hingga gudang akhir, kami menangani barang bawaan Anda dengan akurasi persentil tingkat tinggi.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Service 1 -->
                    <div data-aos="fade-up" data-aos-delay="0" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 border border-gray-100 group flex flex-col">
                        <div class="h-56 relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1549487424-698b67f37ccb?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-4 left-6">
                                <span class="bg-brand-600 text-white text-xs font-bold uppercase tracking-widest px-3 py-1 rounded">Unggulan</span>
                            </div>
                        </div>
                        <div class="p-8 flex-grow">
                            <h3 class="text-2xl font-black mb-3 text-brand-950 group-hover:text-brand-600 transition">Bongkar Muat Hewan (Stevedoring)</h3>
                            <p class="text-gray-600 leading-relaxed font-medium">Penanganan sapi hidup yang mematuhi pedoman kesejahteraan hewan yang ketat, mulai dari lambung kapal hingga truk landbound.</p>
                        </div>
                    </div>
                    
                    <!-- Service 2 -->
                    <div data-aos="fade-up" data-aos-delay="150" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 border border-gray-100 group flex flex-col">
                        <div class="h-56 relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <div class="p-8 flex-grow">
                            <h3 class="text-2xl font-black mb-3 text-brand-950 group-hover:text-brand-600 transition">Distribusi Reguler (Trucking)</h3>
                            <p class="text-gray-600 leading-relaxed font-medium">Penyewaan truk dan logistik berbasis darat memetakan rute strategis untuk pengiriman yang lebih cepat dan aman dari pelabuhan.</p>
                        </div>
                    </div>

                    <!-- Service 3 -->
                    <div data-aos="fade-up" data-aos-delay="300" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 border border-gray-100 group flex flex-col">
                        <div class="h-56 relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1587293852726-59118eace1a0?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <div class="p-8 flex-grow">
                            <h3 class="text-2xl font-black mb-3 text-brand-950 group-hover:text-brand-600 transition">Sistem Pencatatan Timbang</h3>
                            <p class="text-gray-600 leading-relaxed font-medium">Validasi bobot dan jumlah truk (Headcount, Gross, Tare, Net) menggunakan teknologi komputasi presisi dan otomatisasi Surat Jalan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="kontak" class="relative bg-brand-900 py-24 overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-aos="zoom-in">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Siap Mengoptimalkan Operasional Logistik Anda?</h2>
                <p class="text-xl text-brand-200 mb-10 font-light">
                    Mari bermitra bersama kami dan rasakan transparansi total sistem logistik PT Pratama Andal Dermaga.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="mailto:info@padlogistics.co.id" class="inline-flex items-center justify-center bg-white text-brand-900 font-bold px-8 py-4 rounded-full shadow-xl hover:bg-gray-100 transition transform hover:scale-105 border border-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Hubungi via Email
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-brand-950 text-gray-400 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-6 cursor-pointer">
                        <div class="w-10 h-10 bg-brand-600 rounded flex items-center justify-center text-white font-black text-sm">PAD</div>
                        <span class="font-black text-xl text-white tracking-tight">PT. Pratama Andal Dermaga</span>
                    </div>
                    <p class="text-gray-500 max-w-sm mb-6 leading-relaxed">Penyedia jasa operasi Pelabuhan dan Logistik Darat premium spesialis impor komoditas perternakan dengan tracking sistem tercanggih di Indonesia.</p>
                </div>
                
                <div>
                    <h3 class="text-white font-bold uppercase tracking-widest mb-6">Tautan Langsung</h3>
                    <ul class="space-y-4">
                        <li><a href="#beranda" class="hover:text-brand-400 transition">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-brand-400 transition">Tentang Perusahaan</a></li>
                        <li><a href="#layanan" class="hover:text-brand-400 transition">Solusi Layanan</a></li>
                        <li><a href="{{ route('dashboard.index') }}" class="hover:text-brand-400 transition">Login Karyawan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold uppercase tracking-widest mb-6">Hubungi Kami</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm">Kawasan Pelabuhan Utama, Indonesia</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-sm">+62 800 123 4567</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center text-sm">
                <p>&copy; 2026 PT. Pratama Andal Dermaga. Dikembangkan secara mandiri.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
        
        // Change navbar appearance on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-md');
                nav.classList.replace('py-4', 'py-0');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.replace('py-0', 'py-4');
            }
        });
    </script>
</body>
</html>
