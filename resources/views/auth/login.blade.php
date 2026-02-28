<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ACTS Church CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 600: '#1e3a7b', 700: '#152c61', 800: '#0e1f47', 900: '#0a1630' },
                        gold: { 400: '#fbbf24', 500: '#d4a017', 600: '#b8860b' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .gradient-navy { background: linear-gradient(135deg, #1e3a7b 0%, #0a1630 100%); }
        .gradient-gold { background: linear-gradient(135deg, #d4a017 0%, #b8860b 100%); }
    </style>
</head>
<body class="gradient-navy min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl gradient-gold mb-4 shadow-lg">
                <i class="fas fa-church text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-wide">ACTS CMS</h1>
            <p class="text-gold-400 text-sm mt-1 tracking-wider uppercase">Church Management System</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-navy-800 mb-1">Welcome Back</h2>
            <p class="text-gray-500 text-sm mb-6">Sign in to your administrator account</p>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 text-sm"
                               placeholder="admin@church.com">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500 text-sm"
                               placeholder="Enter your password">
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-gold-500 mr-2 focus:ring-gold-500">
                        Remember me
                    </label>
                </div>

                <button type="submit"
                        class="w-full gradient-gold text-white py-2.5 px-4 rounded-lg font-semibold text-sm hover:opacity-90 transition-opacity shadow-md">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6">&copy; {{ date('Y') }} ACTS Church CMS. All rights reserved.</p>
    </div>
</body>
</html>
