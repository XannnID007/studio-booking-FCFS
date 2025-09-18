<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Booking System - Book Your Music Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        studio: {
                            50: '#fafafa',
                            100: '#f4f4f5',
                            200: '#e4e4e7',
                            300: '#d4d4d8',
                            400: '#a1a1aa',
                            500: '#71717a',
                            600: '#52525b',
                            700: '#3f3f46',
                            800: '#27272a',
                            900: '#18181b'
                        },
                        accent: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12'
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in': 'fadeIn 1s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'pulse-soft': 'pulseSoft 2s infinite',
                        'float': 'float 3s ease-in-out infinite'
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        bounceGentle: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        },
                        pulseSoft: {
                            '0%, 100%': {
                                transform: 'scale(1)'
                            },
                            '50%': {
                                transform: 'scale(1.05)'
                            }
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            }
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-studio-50 font-sans">
    <!-- Navigation Header -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md border-b border-studio-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-music text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-studio-900">Studio Booking</h1>
                        <p class="text-xs text-studio-500 hidden sm:block">Music Studio Rental</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home"
                        class="text-studio-700 hover:text-accent-600 font-medium transition-colors">Home</a>
                    <a href="#studios"
                        class="text-studio-700 hover:text-accent-600 font-medium transition-colors">Studios</a>
                    <a href="#features"
                        class="text-studio-700 hover:text-accent-600 font-medium transition-colors">Features</a>
                    <a href="#contact"
                        class="text-studio-700 hover:text-accent-600 font-medium transition-colors">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-studio-700 hover:text-accent-600 font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-2 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-lg hover:from-accent-600 hover:to-accent-700 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Get Started
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-studio-600" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-studio-200">
            <div class="px-4 py-4 space-y-4">
                <a href="#home" class="block text-studio-700 hover:text-accent-600 font-medium">Home</a>
                <a href="#studios" class="block text-studio-700 hover:text-accent-600 font-medium">Studios</a>
                <a href="#features" class="block text-studio-700 hover:text-accent-600 font-medium">Features</a>
                <a href="#contact" class="block text-studio-700 hover:text-accent-600 font-medium">Contact</a>
                <hr class="border-studio-200">
                <div class="flex space-x-3">
                    <a href="{{ route('login') }}"
                        class="flex-1 text-center px-4 py-2 border border-studio-300 text-studio-700 rounded-lg">Login</a>
                    <a href="{{ route('register') }}"
                        class="flex-1 text-center px-4 py-2 bg-accent-600 text-white rounded-lg">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home"
        class="pt-16 min-h-screen bg-gradient-to-br from-studio-900 via-studio-800 to-studio-900 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-accent-500/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-accent-600/10 rounded-full blur-3xl animate-float"
                style="animation-delay: -1s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-white animate-fade-in-up">
                    <div
                        class="inline-flex items-center px-4 py-2 bg-accent-500/20 rounded-full text-accent-300 text-sm font-medium mb-8 border border-accent-500/30">
                        <i class="fas fa-fire mr-2"></i>
                        #1 Studio Booking Platform
                    </div>

                    <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Book Your Dream
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent-400 to-accent-600">
                            Music Studio
                        </span>
                    </h1>

                    <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                        Temukan studio musik terbaik dengan fasilitas premium. Booking mudah, harga transparan, dan
                        pengalaman bermusik yang tak terlupakan.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-xl hover:from-accent-600 hover:to-accent-700 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-rocket mr-2"></i>
                            Mulai Booking Sekarang
                        </a>
                        <a href="#studios"
                            class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 backdrop-blur-sm transition-all duration-300">
                            <i class="fas fa-play mr-2"></i>
                            Lihat Studio
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 mt-12 pt-12 border-t border-white/10">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-accent-400 mb-1">50+</div>
                            <div class="text-sm text-gray-400">Studios</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-accent-400 mb-1">1000+</div>
                            <div class="text-sm text-gray-400">Happy Customers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-accent-400 mb-1">5000+</div>
                            <div class="text-sm text-gray-400">Bookings</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Visual -->
                <div class="relative animate-fade-in">
                    <div class="relative z-10">
                        <div
                            class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-2xl animate-pulse-soft">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-accent-500/20 rounded-xl p-4 border border-accent-500/30">
                                    <div class="w-8 h-8 bg-accent-500 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fas fa-microphone text-white text-sm"></i>
                                    </div>
                                    <div class="text-white font-medium">Recording</div>
                                    <div class="text-gray-300 text-sm">Professional Setup</div>
                                </div>
                                <div class="bg-blue-500/20 rounded-xl p-4 border border-blue-500/30">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fas fa-guitar text-white text-sm"></i>
                                    </div>
                                    <div class="text-white font-medium">Instruments</div>
                                    <div class="text-gray-300 text-sm">Complete Set</div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-xl p-4 border border-green-500/30">
                                <div class="flex items-center justify-between mb-4">
                                    <h3
                                        class="text-xl font-bold text-studio-900 group-hover:text-accent-600 transition-colors">
                                        Acoustic Studio C</h3>
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="text-studio-600 text-sm">4.7</span>
                                    </div>
                                </div>
                                <div class="flex items-center text-studio-500 text-sm mb-4">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span>Jakarta Pusat</span>
                                </div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-users text-studio-400"></i>
                                        <span class="text-studio-600 text-sm">Capacity: 4 people</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-accent-600">Rp 100K</span>
                                        <span class="text-studio-500 text-sm">/hour</span>
                                    </div>
                                    <button
                                        class="px-6 py-2 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-lg hover:from-accent-600 hover:to-accent-700 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-12">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-xl hover:from-accent-600 hover:to-accent-700 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Semua Studio
                        </a>
                    </div>
                </div>
    </section>

    <!-- How It Works -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-bold text-studio-900 mb-6">
                    Cara
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent-500 to-accent-600">
                        Kerja Sistem
                    </span>
                </h2>
                <p class="text-xl text-studio-600 max-w-3xl mx-auto">
                    Proses booking yang mudah dan cepat, hanya dalam 3 langkah sederhana
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-accent-500 to-accent-600 rounded-full flex items-center justify-center mx-auto shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-search text-white text-2xl"></i>
                        </div>
                        <div
                            class="absolute -top-2 -right-2 w-8 h-8 bg-accent-200 text-accent-800 rounded-full flex items-center justify-center text-sm font-bold">
                            1</div>
                    </div>
                    <h3 class="text-2xl font-bold text-studio-900 mb-4">Pilih Studio</h3>
                    <p class="text-studio-600 leading-relaxed">
                        Browse dan pilih studio music yang sesuai dengan kebutuhan Anda. Filter berdasarkan lokasi,
                        harga, dan fasilitas.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-calendar-check text-white text-2xl"></i>
                        </div>
                        <div
                            class="absolute -top-2 -right-2 w-8 h-8 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-sm font-bold">
                            2</div>
                    </div>
                    <h3 class="text-2xl font-bold text-studio-900 mb-4">Book & Bayar</h3>
                    <p class="text-studio-600 leading-relaxed">
                        Pilih tanggal dan waktu yang tersedia, lakukan pembayaran DP yang aman melalui berbagai metode
                        pembayaran.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto shadow-xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-music text-white text-2xl"></i>
                        </div>
                        <div
                            class="absolute -top-2 -right-2 w-8 h-8 bg-green-200 text-green-800 rounded-full flex items-center justify-center text-sm font-bold">
                            3</div>
                    </div>
                    <h3 class="text-2xl font-bold text-studio-900 mb-4">Enjoy Music!</h3>
                    <p class="text-studio-600 leading-relaxed">
                        Datang ke studio sesuai jadwal booking Anda dan nikmati pengalaman bermusik yang tak terlupakan!
                    </p>
                </div>
            </div>

            <!-- Process Flow Line -->
            <div class="hidden md:block relative mt-16">
                <div
                    class="absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-accent-200 via-blue-200 to-green-200 transform -translate-y-1/2">
                </div>
                <div class="flex justify-between items-center relative z-10">
                    <div class="w-4 h-4 bg-accent-500 rounded-full"></div>
                    <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-studio-900 via-studio-800 to-studio-900 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/3 left-1/3 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/3 right-1/3 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <div
                class="inline-flex items-center px-6 py-3 bg-accent-500/20 rounded-full text-accent-300 text-sm font-medium mb-8 border border-accent-500/30">
                <i class="fas fa-rocket mr-2"></i>
                Siap Memulai Perjalanan Musik Anda?
            </div>

            <h2 class="text-4xl lg:text-6xl font-bold text-white mb-8 leading-tight">
                Jangan Tunggu Lagi!
                <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent-400 to-accent-600">
                    Book Studio Sekarang
                </span>
            </h2>

            <p class="text-xl text-gray-300 mb-12 leading-relaxed max-w-2xl mx-auto">
                Bergabunglah dengan ribuan musisi yang telah mempercayai platform kami untuk kebutuhan studio musik
                mereka.
            </p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center px-10 py-5 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-bold text-lg rounded-xl hover:from-accent-600 hover:to-accent-700 shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-user-plus mr-3"></i>
                    Daftar Gratis Sekarang
                </a>
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-10 py-5 border-2 border-white/30 text-white font-bold text-lg rounded-xl hover:bg-white/10 backdrop-blur-sm transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    Sudah Punya Akun?
                </a>
            </div>

            <div class="grid grid-cols-3 gap-8 mt-16 pt-16 border-t border-white/10">
                <div class="text-center">
                    <div class="text-4xl font-bold text-accent-400 mb-2">24/7</div>
                    <div class="text-gray-400">Customer Support</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-accent-400 mb-2">99.9%</div>
                    <div class="text-gray-400">Uptime Guarantee</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-accent-400 mb-2">100%</div>
                    <div class="text-gray-400">Secure Payment</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-studio-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-music text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Studio Booking</h3>
                            <p class="text-gray-400 text-sm">Music Studio Rental</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6">
                        Platform terpercaya untuk booking studio musik dengan fasilitas terlengkap dan harga terbaik.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-accent-500 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-accent-500 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-accent-500 transition-colors">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-accent-400 transition-colors">Home</a>
                        </li>
                        <li><a href="#studios"
                                class="text-gray-400 hover:text-accent-400 transition-colors">Studios</a></li>
                        <li><a href="#features"
                                class="text-gray-400 hover:text-accent-400 transition-colors">Features</a></li>
                        <li><a href="{{ route('login') }}"
                                class="text-gray-400 hover:text-accent-400 transition-colors">Login</a></li>
                        <li><a href="{{ route('register') }}"
                                class="text-gray-400 hover:text-accent-400 transition-colors">Register</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Services</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-accent-400 transition-colors">Recording
                                Studio</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-accent-400 transition-colors">Rehearsal
                                Room</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-accent-400 transition-colors">Equipment
                                Rental</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-accent-400 transition-colors">Sound
                                Engineering</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Contact Info</h4>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-accent-500"></i>
                            <span class="text-gray-400">Jakarta, Indonesia</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-accent-500"></i>
                            <span class="text-gray-400">+62 812 3456 7890</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-accent-500"></i>
                            <span class="text-gray-400">info@studiobooking.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-white/10 my-12">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © 2024 Studio Booking System. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-accent-400 text-sm transition-colors">Privacy
                        Policy</a>
                    <a href="#" class="text-gray-400 hover:text-accent-400 text-sm transition-colors">Terms of
                        Service</a>
                    <a href="#" class="text-gray-400 hover:text-accent-400 text-sm transition-colors">Cookie
                        Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = '0s';
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe all sections
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
