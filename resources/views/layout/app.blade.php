<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGAM-SJ</title>
    <link rel="icon" href="{{ asset('IMG_4761.png') }}" type="image/png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <script>
  function toggleMenu(id) {
    const submenu = document.getElementById(id);
    submenu.classList.toggle('hidden');
  }
</script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5e72e4',
                        secondary: '#8392ab',
                        success: '#2dce89',
                        info: '#11cdef',
                        warning: '#FFFFFF', // Color amarillo
                        danger: '#f5365c',
                        light: '#f7fafc',
                        dark: '#344767',
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'wave': 'wave 2s linear infinite'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        wave: {
                            '0%': { transform: 'rotate(0deg)' },
                            '10%': { transform: 'rotate(14deg)' },
                            '20%': { transform: 'rotate(-8deg)' },
                            '30%': { transform: 'rotate(14deg)' },
                            '40%': { transform: 'rotate(-4deg)' },
                            '50%': { transform: 'rotate(10deg)' },
                            '60%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(0deg)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(15deg, #f5f7fa 0%, #e4e7f1 100%);
            min-height: 100vh;
            font-size: 13px; /* Ajusta este valor según lo que necesites */
        }

        .sidebar {
    background: linear-gradient(
        135deg,
        #2d6520 0%,
        #6fae2d 45%,
        #e3f541 130%
    );
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.35);
    width: 260px;
    transition: all 0.3s ease;
}


        .card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .btn {
            transition: all 0.3s ease;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #FFFFFF 0%, #FFFFFF 100%);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #FFFFFF 0%, #FFFFFF 100%);
        }

        .stat-card {
            transition: all 0.4s ease;
        }

        .stat-card:hover {
            transform: scale(1.02);
        }

        .glow {
            box-shadow: 0 0 20px rgba(239, 239, 237, 0.4);
        }

        .active-nav-link {
            position: relative;
            background: rgba(252, 251, 250, 0.1);
            border-radius: 8px;
        }

        .active-nav-link::after {
            content: '';
            position: absolute;
            right: -5px;
            top: 0;
            height: 100%;
            width: 3px;
            background: #24fb44;
            border-radius: 3px;
            animation: pulse-slow 2s infinite;
        }

        .hamburger {
            transition: all 0.3s ease;
        }

        .hamburger:hover {
            transform: rotate(90deg);
        }

        .notification-badge {
            animation: bounce-slow 2s infinite;
        }

        .sidebar-mini {
            width: 80px;
        }

        .sidebar-mini .sidebar-text,
        .sidebar-mini .logo-text {
            display: none;
        }

        .sidebar-mini .active-nav-link::after {
            display: none;
        }

        .content-area {
            transition: all 0.3s ease;
        }

        .content-expanded {
            margin-left: 260px;
        }

        .content-mini {
            margin-left: 80px;
        }

        .menu-link {
            transition: all 0.3s ease;
        }

        .menu-link:hover {
            background: rgba(252, 252, 250, 0.2) !important;
            color: #FFFFFF !important;
        }

        .menu-link:hover i {
            color: #f5f5f3 !important;
        }

        .profile-hover:hover {
            background: rgba(245, 244, 243, 0.2) !important;
        }

        .dropdown-link:hover {
            background: rgba(249, 248, 247, 0.1) !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                height: 100vh;
                z-index: 1000;
            }

            .sidebar-open {
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0 !important;
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
        }
    </style>

     <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
@php
    $rol = optional(Auth::user())->rol_id;
    $esAdmin = $rol == 2;
    $esOperador = $rol == 1;
@endphp

<body class="bg-gray-50">
    <!-- Barra lateral izquierda -->
    <aside class="sidebar fixed h-full z-50">
        <div class="py-6 px-4">
            <!-- Logo y nombre -->
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center space-x-3">
                    <img
                        src="{{ asset('IMG_4761.png') }}"
                        alt="Logo SGAM-SJ"
                        class="w-8 h-8"
                    >
                    <span class="logo-text text-xl font-bold text-white">SGAM-SJ</span>
                </div>
                <button id="toggle-sidebar" class="text-white p-1 rounded-full hover:bg-yellow-400/20 transition">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Menú de navegación vertical -->

            <!-- Perfil de usuario -->
            <div class="mt-8">
            <div class="flex items-center space-x-3 p-2 rounded-lg profile-hover transition cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-orange-400 flex items-center justify-center border-2 border-oange-400">
                    <i class="fas fa-user text-white"></i>
                </div>

                <div class="sidebar-text">
                    {{ optional(Auth::user())->rol_id }}

                    <div class="flex items-center space-x-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <p class="text-xs text-green-400 font-semibold">En línea</p>
                    </div>
                </div>
            </div>
        </div>

                <div class="space-y-2">
    @if($esAdmin || $esOperador)
    <a href="#" class="flex items-center space-x-3 p-3 text-white menu-link active-nav-link">
        <i class="fas fa-home text-lg text-white"></i>
        <span class="sidebar-text font-medium">Dashboard</span>
    </a>
    @endif
    <!-- Solicitudes -->

    <div class="group">
        <a href="#" onclick="toggleMenu('submenu-solicitudes')" class="flex items-center justify-between p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition cursor-pointer">
            <div class="flex items-center space-x-3">
                <i class="fas fa-file-alt text-lg"></i>
                <span class="sidebar-text font-medium">Catálogos</span>
            </div>
            <i class="fas fa-chevron-down"></i>
        </a>
         @if($esAdmin)
        <div id="submenu-solicitudes" class="ml-8 hidden space-y-1 text-sm text-gray-200">
            <a href="/view/catergories" class="flex items-center space-x-2 hover:text-[#124BAB]"><i class="fas fa-plus-circle"></i><span>Categorías</span></a>
            <a href="/medidas/view" class="flex items-center space-x-2 hover:text-[#124BAB]"><i class="fas fa-plus-circle"></i><span>Unidades</span></a>
            <a href="/view/programs" class="flex items-center space-x-2 hover:text-[#124BAB]"><i class="fas fa-plus-circle"></i><span>Programas</span></a>
            <a href="/products/view/createpro" class="flex items-center space-x-2 hover:text-[#124BAB]"><i class="fas fa-plus-circle"></i><span>Productos</span></a>
            <a href="/bajas/products" class="flex items-center space-x-2 hover:text-[#124BAB]"><i class="fas fa-plus-circle"></i><span>Baja Existencia</span></a>
            <a href="/view/roles/see" class="flex items-center space-x-2 hover:text-[#124BAB]"><i class="fas fa-plus-circle"></i><span>Renglones</span></a>
            <a href="/ubicaciones/u/see" class="flex items-center space-x-2 hover:text-[#124BAB]"><i class="fas fa-plus-circle"></i><span>Ubicaciones</span></a>

        </div>
        @endif
    </div>

    <!-- Compras -->
    @if($esAdmin || $esOperador)
    <div class="group">
        <a href="#" onclick="toggleMenu('submenu-requisiciones')" class="flex items-center justify-between p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition cursor-pointer">
            <div class="flex items-center space-x-3">
                <i class="fas fa-tasks text-lg"></i>
                <span class="sidebar-text font-medium">Requisiciones</span>
            </div>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="submenu-requisiciones" class="ml-8 hidden space-y-1 text-sm text-gray-200">
            <a href="/view/see/req" class="flex items-center space-x-2 hover:text-white"><i class="fas fa-plus-circle"></i><span>Nueva Requisicion</span></a>
            @if($esAdmin)
            <a href="/detail/requisiciones/view" class="flex items-center space-x-2 hover:text-white"><i class="fa-solid fa-list"></i></i><span>Detalle Requisiciones</span></a>
             @endif
        </div>
    </div>
    @endif
    <!-- Ventas -->
    @if($esAdmin || $esOperador)
    <div class="group">
        <a href="#" onclick="toggleMenu('submenu-prepedidos')" class="flex items-center justify-between p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition cursor-pointer">
            <div class="flex items-center space-x-3">
                <i class="fas fa-box-open text-lg"></i>
                <span class="sidebar-text font-medium">Proceso</span>
            </div>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="submenu-prepedidos" class="ml-8 hidden space-y-1 text-sm text-gray-200">
            <a href="/lista/view/proceso/requisciones" class="flex items-center space-x-2 hover:text-white"><i class="fas fa-plus-circle"></i><span>Ver Proceso</span></a>
        </div>
    </div>
     @endif
        <!-- Recepciones -->

        <div class="group">
            <a href="#" onclick="toggleMenu('submenu-recepciones')" class="flex items-center justify-between p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition cursor-pointer">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-dolly text-lg"></i>
                    <span class="sidebar-text font-medium">Recepciones</span>
                </div>
                <i class="fas fa-chevron-down"></i>
            </a>
             @if($esAdmin)
            <div id="submenu-recepciones" class="ml-8 hidden space-y-1 text-sm text-gray-200">
                <a href="/view/recepcion" class="flex items-center space-x-2 hover:text-white">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nueva Recepción</span>
                </a>
                <a href="/view/detail/recepciones/vista" class="flex items-center space-x-2 hover:text-white">
                    <i class="fas fa-list"></i>
                    <span>Listar Recepciones</span>
                </a>
            </div>
             @endif
        </div>

        <!-- Despachos -->
        <div class="group">
            <a href="#" onclick="toggleMenu('submenu-despachos')" class="flex items-center justify-between p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition cursor-pointer">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-truck-loading text-lg"></i>
                    <span class="sidebar-text font-medium">Despachos</span>
                </div>
                <i class="fas fa-chevron-down"></i>
            </a>
             @if($esAdmin)
            <div id="submenu-despachos" class="ml-8 hidden space-y-1 text-sm text-gray-200">
                <a href="/view/despacho" class="flex items-center space-x-2 hover:text-white">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nuevo Despacho</span>
                </a>
                <a href="/view/detail/entregas/vista" class="flex items-center space-x-2 hover:text-white">
                    <i class="fas fa-list"></i>
                    <span>Listar Despachos</span>
                </a>
            </div>
            @endif
        </div>



    <!-- Clientes -->
    <div class="group">
        <a href="#" onclick="toggleMenu('submenu-clientes')" class="flex items-center justify-between p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition cursor-pointer">
            <div class="flex items-center space-x-3">
                <i class="fas fa-user-friends text-lg"></i>
                <span class="sidebar-text font-medium">Colaboradores</span>
            </div>
            <i class="fas fa-chevron-down"></i>
        </a>
         @if($esAdmin)
        <div id="submenu-clientes" class="ml-8 hidden space-y-1 text-sm text-gray-200">
            <a href="/see/employes" class="flex items-center space-x-2 hover:text-white"><i class="fas fa-user-plus"></i><span>Nuevo Colaborador</span></a>
        </div>
        @endif
    </div>

    <!-- MENÚ PRESUPUESTO -->
<div class="space-y-1">

    <!-- BOTÓN PRINCIPAL -->
    <button
        class="flex items-center justify-between w-full p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition group"
        onclick="document.getElementById('submenu-cdp').classList.toggle('hidden')"
    >
        <div class="flex items-center space-x-3">
            <i class="fas fa-coins text-lg"></i>
            <span class="sidebar-text font-medium">Presupuesto</span>
        </div>
        <i class="fas fa-chevron-down text-sm transition group-hover:rotate-180"></i>
    </button>
     @if($esAdmin)
    <!-- SUBMENÚ -->
    <div id="submenu-cdp" class="hidden ml-6 space-y-1">

        <!-- CREAR CDP -->
        <a href="/cdp/crear"
           class="flex items-center space-x-3 p-2 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition">
            <i class="fas fa-file-circle-plus text-sm text-yellow-400"></i>
            <span class="sidebar-text">Crear CDP</span>
        </a>

        <!-- LISTAR CDP -->
        <a href="/view/detail/cdp/view"
           class="flex items-center space-x-3 p-2 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition">
            <i class="fas fa-list text-sm text-green-400"></i>
            <span class="sidebar-text">Listar CDP</span>
        </a>

    </div>
    @endif
</div>

     @if($esAdmin)
    <a href="/view/form/suppliers" class="flex items-center space-x-3 p-3 text-gray-300 menu-link hover:bg-yellow-400/20 rounded-lg transition">
        <i class="fas fa-people-carry text-lg"></i>
        <span class="sidebar-text font-medium">Proveedores</span>
    </a>
    @endif
    <!-- Administración -->
    <div class="group">
        <a href="#" onclick="toggleMenu('submenu-administracion')" class="flex items-center justify-between p-3 text-gray-300 hover:bg-yellow-400/20 rounded-lg transition cursor-pointer">
            <div class="flex items-center space-x-3">
                <i class="fas fa-tools text-lg"></i>
                <span class="sidebar-text font-medium">Administración</span>
            </div>
            <i class="fas fa-chevron-down"></i>
        </a>
         @if($esAdmin)
        <div id="submenu-administracion" class="ml-8 hidden space-y-1 text-sm text-gray-200">
            <a href="/view/form/rols" class="flex items-center space-x-2 hover:text-white"><i class="fas fa-user-tag"></i><span>Roles</span></a>
            <a href="/view/form/users" class="flex items-center space-x-2 hover:text-white"><i class="fas fa-users"></i><span>Usuarios</span></a>
            <a href="/view/company" class="flex items-center space-x-2 hover:text-white"><i class="fas fa-briefcase"></i><span>Empresa</span></a>
        </div>
        @endif
    </div>

</div>





            <!-- Separador -->
            <div class="my-8 border-t border-gray-700 opacity-30"></div>


        </div>
    </aside>

    <!-- Overlay para móvil -->
    <div id="mobile-overlay" class="mobile-overlay"></div>

    <!-- Contenido principal -->
    <div class="content-area content-expanded min-h-screen">
        <!-- Barra superior -->
        <header class="bg-white shadow-sm py-4 px-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <button id="mobile-menu-button" class="md:hidden mr-4 text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Sistema de Gestión de Almacén</h1>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <button class="p-2 rounded-full hover:bg-yellow-100 transition">
                            <i class="fas fa-bell text-white"></i>
                        </button>
                        <span class="notification-badge absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                    </div>


                        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button
        type="submit"
        class="flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-900 transition"
    >
        <i class="fas fa-sign-out-alt mr-2"></i>
        Cerrar Sesión
    </button>
</form>

                </div>
            </div>
        </header>

        <!-- Contenido -->
        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-10">
                @yield('contents')<!-- Aca se posiciona todo el contenido a mostrar dentro de este contenedor-->

            </div>





            <!-- Footer -->
            <footer class="mt-10 pt-6 border-t border-gray-200 text-center text-gray-500">
                <p>© 2026 Norio Systems. Todos los derechos reservados.</p>

            </footer>
        </div>
    </div>

    <script>
        // Funcionalidad para alternar la barra lateral
        const toggleSidebar = document.getElementById('toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        const contentArea = document.querySelector('.content-area');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileOverlay = document.getElementById('mobile-overlay');

        let isMini = false;

        toggleSidebar.addEventListener('click', () => {
            isMini = !isMini;

            if (isMini) {
                sidebar.classList.add('sidebar-mini');
                contentArea.classList.remove('content-expanded');
                contentArea.classList.add('content-mini');
            } else {
                sidebar.classList.remove('sidebar-mini');
                contentArea.classList.remove('content-mini');
                contentArea.classList.add('content-expanded');
            }
        });

        // Funcionalidad para menú móvil
        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.add('sidebar-open');
            mobileOverlay.style.display = 'block';
        });

        mobileOverlay.addEventListener('click', () => {
            sidebar.classList.remove('sidebar-open');
            mobileOverlay.style.display = 'none';
        });

        // Animaciones para los botones
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', () => {
                button.classList.add('shadow-lg');
            });

            button.addEventListener('mouseleave', () => {
                button.classList.remove('shadow-lg');
            });
        });

        // Animación para las tarjetas de estadísticas
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('shadow-xl');
            });

            card.addEventListener('mouseleave', () => {
                card.classList.remove('shadow-xl');
            });
        });

        // Animación para botón "Upgrade to Pro"
        const upgradeBtn = document.querySelector('.glow');
        setInterval(() => {
            upgradeBtn.classList.toggle('glow');
            setTimeout(() => upgradeBtn.classList.toggle('glow'), 1000);
        }, 3000);
        function accesoDenegado() {
        Swal.fire({
            icon: 'error',
            title: 'Acceso denegado',
            text: 'No tienes permisos para acceder a este módulo',
            confirmButtonColor: '#facc15'
        });
    }
    </script>
</body>
</html>
