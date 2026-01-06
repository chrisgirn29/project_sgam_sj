<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SGAM-SJ | Iniciar Sesión</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background: linear-gradient(135deg, #2d6520 0%, #e3f541 100%);
      background-attachment: fixed;
      font-family: 'Poppins', sans-serif;
      color: #E5E7EB;
    }

    .glass {
      background: rgba(17, 25, 40, 0.75);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .logo {
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen">

  <div class="glass rounded-3xl shadow-2xl p-10 w-full max-w-md text-center">

    <!-- LOGO -->
    <div class="flex justify-center mb-6">
      <img src="{{ asset('IMG_4761.png') }}"
           alt="Logo ExploraGT"
           class="logo w-32 h-32 ">
    </div>

    <h2 class="text-3xl font-bold mb-6 text-white">
      <span class="text-yellow-400">Bienvenido a SGAM-SJ</span>
    </h2>
    <p class="text-gray-300 mb-6 text-sm">
      Sistema de Gestión de Almacén Municipal - San Jerónimo
    </p>

    <!-- MENSAJE DE ERROR -->
    @if(session('error'))
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          Swal.fire({
            icon: 'error',
            title: 'Credenciales incorrectas',
            text: '{{ session('error') }}',
            confirmButtonColor: '#facc15',
            background: '#1f2937',
            color: '#f3f4f6'
          });
        });
      </script>
    @endif

    <!-- MENSAJE DE ÉXITO -->
    @if(session('success'))
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          Swal.fire({
            title: '¡Bienvenido!',
            text: '{{ session('success') }}',
            icon: 'success',
            background: '#1f2937',
            color: '#f3f4f6',
            timer: 2000,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
          });
        });
      </script>
    @endif

    <!-- FORMULARIO -->
     <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-6 text-left">
      @csrf

      <div>
        <label for="email" class="block text-gray-200 font-semibold mb-1">
          Correo electrónico
        </label>
        <input type="email" id="email" name="email" required
               class="w-full p-3 rounded-lg bg-gray-800/50 border-none focus:ring-2 focus:ring-yellow-400 text-gray-100 placeholder-gray-400"
               placeholder="ejemplo@exploragt.com">
      </div>

      <!-- CONTRASEÑA CON OJO -->
      <div>
        <label for="password" class="block text-gray-200 font-semibold mb-1">
          Contraseña
        </label>

        <div class="relative">
          <input type="password" id="password" name="password" required
                 class="w-full p-3 pr-12 rounded-lg bg-gray-800/50 border-none focus:ring-2 focus:ring-yellow-400 text-gray-100 placeholder-gray-400"
                 placeholder="••••••••">

          <button type="button"
                  id="togglePassword"
                  class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-yellow-400 focus:outline-none">

            <!-- Ojo cerrado -->
            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10
                       0-1.657.402-3.217 1.125-4.575M6.343 6.343A9.956 9.956 0 0112 5
                       c5.523 0 10 4.477 10 10 0 1.657-.402 3.217-1.125 4.575
                       M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>

            <!-- Ojo abierto -->
            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 hidden" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12s3.6-7 9-7 9 7 9 7-3.6 7-9 7-9-7-9-7z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <button type="submit"
              class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 rounded-lg shadow-lg transition duration-300">
        Conectarse
      </button>
    </form>

    <p class="mt-6 text-sm text-gray-300">
      ¿No tienes cuenta?
      <a href="#" class="text-yellow-400 hover:underline">Contáctanos</a>
    </p>

  </div>

  <!-- SCRIPTS -->
  <script>
    // Validación y SweetAlert al enviar
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();

      if (email === '' || password === '') {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Campos vacíos',
          text: 'Por favor completa todos los campos.',
          confirmButtonColor: '#facc15',
          background: '#1f2937',
          color: '#f3f4f6'
        });
        return;
      }

      Swal.fire({
        title: 'Verificando...',
        text: 'Por favor espera un momento',
        allowOutsideClick: false,
        background: '#1f2937',
        color: '#f3f4f6',
        didOpen: () => Swal.showLoading()
      });
    });

    // Mostrar / ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    togglePassword.addEventListener('click', () => {
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      eyeOpen.classList.toggle('hidden', !isPassword);
      eyeClosed.classList.toggle('hidden', isPassword);
    });
  </script>

</body>
</html>
