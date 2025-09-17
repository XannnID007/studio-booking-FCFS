<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Studio Booking System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                    },
                    keyframes: {
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

<body
    class="min-h-screen bg-gradient-to-br from-studio-900 via-studio-800 to-studio-900 font-sans flex items-center justify-center p-4">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-20 w-32 h-32 bg-accent-500/10 rounded-full animate-float"></div>
        <div class="absolute top-40 right-32 w-24 h-24 bg-accent-400/10 rounded-full animate-float"
            style="animation-delay: -2s;"></div>
        <div class="absolute bottom-32 left-40 w-20 h-20 bg-accent-600/10 rounded-full animate-float"
            style="animation-delay: -4s;"></div>

        <div class="absolute top-1/4 right-1/4 text-accent-500/20 animate-pulse-slow">
            <i class="fas fa-music text-4xl"></i>
        </div>
        <div class="absolute bottom-1/4 left-1/4 text-accent-400/20 animate-pulse-slow" style="animation-delay: -1s;">
            <i class="fas fa-guitar text-3xl"></i>
        </div>
    </div>

    <div class="relative w-full max-w-sm">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-studio-900 to-studio-800 px-6 py-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-accent-500 rounded-2xl mb-3 shadow-lg">
                    <i class="fas fa-music text-white text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Studio Booking</h1>
                <p class="text-studio-300 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <div class="px-6 py-6">
                @if ($errors->any())
                    <div
                        class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 flex items-center space-x-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span class="text-sm">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-studio-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-studio-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                autofocus placeholder="Masukkan email Anda"
                                class="w-full pl-12 pr-4 py-3 bg-studio-50 border border-studio-200 rounded-xl text-studio-900 placeholder-studio-500 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-studio-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-studio-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                placeholder="Masukkan password Anda"
                                class="w-full pl-12 pr-12 py-3 bg-studio-50 border border-studio-200 rounded-xl text-studio-900 placeholder-studio-500 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent transition-all duration-200">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-studio-400 hover:text-studio-600 transition-colors">
                                <i id="toggleIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-accent-500 bg-studio-50 border-studio-300 rounded focus:ring-accent-500 focus:ring-2">
                            <span class="ml-2 text-sm text-studio-600">Ingat saya</span>
                        </label>
                        <a href="#"
                            class="text-sm text-accent-600 hover:text-accent-700 font-medium transition-colors">
                            Lupa password?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-accent-600 hover:to-accent-700 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </button>
                </form>

                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-studio-200"></div>
                    <span class="px-4 text-sm text-studio-500 bg-white">atau</span>
                    <div class="flex-1 border-t border-studio-200"></div>
                </div>

                <div class="text-center">
                    <p class="text-studio-600 text-sm">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}"
                            class="font-semibold text-accent-600 hover:text-accent-700 transition-colors">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <p class="text-studio-300 text-sm">
                © 2024 Studio Booking System. Built for music creators.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form validation feedback
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>
