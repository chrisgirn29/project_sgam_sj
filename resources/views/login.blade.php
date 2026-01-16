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
          const msg = @json(session('error'));
          if (msg) {
            try {
              Swal.fire({
                icon: 'error',
                title: 'Credenciales incorrectas',
                text: msg,
                confirmButtonColor: '#facc15',
                background: '#1f2937',
                color: '#f3f4f6'
              });
            } catch(err) {
              console.error('Swal error:', err);
            }
          }
        });
      </script>
    @endif

    <!-- MENSAJE DE ÉXITO -->
    @if(session('success'))
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const msg = @json(session('success'));
          if (msg) {
            try {
              Swal.fire({
                title: '¡Bienvenido!',
                text: msg,
                icon: 'success',
                background: '#1f2937',
                color: '#f3f4f6',
                timer: 2000,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
              });
            } catch(err) {
              console.error('Swal error:', err);
            }
          }
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
      const email = document.getElementById('email')?.value.trim() || '';
      const password = document.getElementById('password')?.value.trim() || '';

      if (email === '' || password === '') {
        e.preventDefault();
        try {
          Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor completa todos los campos.',
            confirmButtonColor: '#facc15',
            background: '#1f2937',
            color: '#f3f4f6'
          });
        } catch(err) {
          console.error('Swal error:', err);
        }
        return;
      }

      try {
        Swal.fire({
          title: 'Verificando...',
          text: 'Por favor espera un momento',
          allowOutsideClick: false,
          background: '#1f2937',
          color: '#f3f4f6',
          didOpen: () => {
            try {
              Swal.showLoading();
            } catch(err) {
              console.error('Swal showLoading error:', err);
            }
          }
        });
      } catch(err) {
        console.error('Swal error:', err);
      }
    });


  </script>

</body>
</html>
