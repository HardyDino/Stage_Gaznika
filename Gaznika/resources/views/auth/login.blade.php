<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GazNika - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #006eff;
            --primary-hover: #029af1;
            --secondary-color: #f59e0b;
            --bg-gradient: linear-gradient(135deg, #0844e9 0%, #03ffb8 100%);
            --dark-color: #1e293b;
        }
        
        body {
            background: var(--bg-gradient);
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        
        .login-container {
            perspective: 1000px;
        }
        
        .login-card {
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-card:hover {
            transform: translateY(-5px) rotateX(5deg);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #cbd5e1;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .input-field:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            background: white;
        }
        
        .input-label {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #64748b;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 5px;
        }
        
        .input-field:focus + .input-label,
        .input-field:not(:placeholder-shown) + .input-label {
            top: -10px;
            left: 10px;
            font-size: 0.75rem;
            color: var(--primary-color);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .login-btn {
            background: var(--primary-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }
        
        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
            }
        }
        
        .gas-icon {
            color: var(--primary-color);
            text-shadow: 0 0 10px rgba(16, 103, 185, 0.5);
        }
        
        .logo-text {
            color: var(--dark-color);
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #64748b;
            margin: 1.5rem 0;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .divider::before {
            margin-right: 1rem;
        }
        
        .divider::after {
            margin-left: 1rem;
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center">
    <!-- Floating background elements -->
    <div class="floating-elements">
        <div class="floating-element" style="width: 150px; height: 150px; top: 20%; left: 10%;"></div>
        <div class="floating-element" style="width: 100px; height: 100px; top: 60%; left: 80%;"></div>
        <div class="floating-element" style="width: 200px; height: 200px; top: 80%; left: 30%;"></div>
        <div class="floating-element" style="width: 80px; height: 80px; top: 30%; left: 70%;"></div>
    </div>
    
    <div class="login-container w-full max-w-md px-4">
        <div class="login-card p-8 animate__animated animate__fadeInUp">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 animate__animated animate__bounceIn animate-delay-1">
                    <i class="fas fa-fire gas-icon text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center justify-center gap-2">
                    <span class="logo-text">GazNika</span>
                </h1>
                <p class="text-gray-600">Accédez à votre espace professionnel</p>
            </div>
            
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>


            <div class="text-center mt-4 text-gray-600 animate__animated animate__fadeIn animate-delay-3">
                <p>© 2025 GazNika - Tous droits réservés</p>
                <p class="text-xs mt-2 text-gray-400">Version 2.1.0</p>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
        });
        
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('animate__animated', 'animate__pulse', 'animate__faster');
                setTimeout(() => {
                    this.parentNode.classList.remove('animate__animated', 'animate__pulse', 'animate__faster');
                }, 1000);
            });
        });
    </script>
</body>
</html>