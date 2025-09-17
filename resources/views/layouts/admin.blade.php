<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Studio Booking Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Studio Music Theme Colors
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
                        },
                        success: {
                            500: '#10b981',
                            600: '#059669'
                        },
                        warning: {
                            500: '#f59e0b',
                            600: '#d97706'
                        },
                        danger: {
                            500: '#ef4444',
                            600: '#dc2626'
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        /* Sidebar transition */
        .sidebar-enter {
            transform: translateX(-100%);
        }

        .sidebar-enter-active {
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-studio-50 font-sans">
    <!-- Mobile Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"></div>

    <!-- Sidebar -->
    <nav id="sidebar"
        class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-br from-studio-900 via-studio-800 to-studio-900 text-white z-50 overflow-y-auto scrollbar-thin transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-music text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="text-xl font-bold">Studio Booking</h4>
                    <p class="text-gray-400 text-sm">Admin Panel</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-accent-500 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('admin.studios.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.studios.*') ? 'bg-accent-500 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                <i class="fas fa-building w-5"></i>
                <span class="font-medium">Kelola Studio</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.bookings.*') ? 'bg-accent-500 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                <i class="fas fa-calendar-check w-5"></i>
                <span class="font-medium">Kelola Booking</span>
            </a>

            <a href="{{ route('admin.payments.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.payments.*') ? 'bg-accent-500 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                <i class="fas fa-credit-card w-5"></i>
                <span class="font-medium">Verifikasi Pembayaran</span>
            </a>

            <a href="{{ route('admin.reports.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.reports.*') ? 'bg-accent-500 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span class="font-medium">Laporan</span>
            </a>
        </div>
    </nav>

    <!-- Main Navbar -->
    <nav
        class="fixed left-0 lg:left-64 right-0 top-0 h-20 bg-white/80 backdrop-blur-md border-b border-studio-200 z-30 px-6">
        <div class="flex items-center justify-between h-full">
            <div class="flex items-center space-x-4">
                <!-- Mobile Menu Toggle -->
                <button id="sidebar-toggle"
                    class="lg:hidden p-2 text-studio-600 hover:text-studio-900 hover:bg-studio-100 rounded-lg transition-colors">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <div>
                    <h1 class="text-2xl font-bold text-studio-900">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-sm text-studio-500 hidden sm:block">Kelola sistem booking studio musik Anda</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button
                    class="relative p-2 text-studio-500 hover:text-studio-700 hover:bg-studio-100 rounded-lg transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-danger-500 rounded-full"></span>
                </button>

                <!-- User Dropdown -->
                <div class="relative group">
                    <button
                        class="flex items-center space-x-3 p-2 text-studio-700 hover:bg-studio-100 rounded-lg transition-colors">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-accent-500 to-accent-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <span class="font-medium hidden sm:block">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-studio-200 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="#"
                            class="flex items-center space-x-2 px-4 py-2 text-studio-700 hover:bg-studio-50 transition-colors">
                            <i class="fas fa-user w-4"></i>
                            <span>Profile</span>
                        </a>
                        <hr class="my-2 border-studio-200">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit"
                                class="flex items-center space-x-2 w-full px-4 py-2 text-danger-600 hover:bg-danger-50 transition-colors">
                                <i class="fas fa-sign-out-alt w-4"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="lg:ml-64 mt-16 p-6">
        <!-- Success Alert -->
        @if (session('success'))
            <div
                class="mb-6 bg-success-50 border border-success-200 text-success-800 rounded-xl p-4 flex items-center space-x-3">
                <i class="fas fa-check-circle text-success-500"></i>
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-success-500 hover:text-success-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Error Alert -->
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center space-x-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Mobile Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarBackdrop.classList.toggle('hidden');
        }

        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarBackdrop?.addEventListener('click', toggleSidebar);

        // Close sidebar on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarBackdrop.classList.add('hidden');
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-in-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>
