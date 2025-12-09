<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Taskly - Organizador estudiantil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        
        .typewriter::after {
            content: '|';
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
        }
        
        .wave-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        
        .wave-shape svg {
            position: relative;
            display: block;
            width: calc(120% + 1.3px);
            height: 100px;
        }
        
        .gradient-text {
            background: linear-gradient(45deg, #ffffffff, #ffffffff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .gradient-text2 {
            background: linear-gradient(45deg, #4f46e5ff, #4f46e5ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .stats-card {
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            background: linear-gradient(135deg, #ffffffff, #ffffffff);
            color: white;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-600 to-cyan-500 text-white overflow-hidden">
        <div class="wave-shape text-white">
           
        </div>
        
        <div class="container mx-auto px-6 py-24 md:py-50 relative z-5">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-12 md:mb-0 md:pr-10">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-20">
                        Organiza tu <span class="gradient-text">vida estudiantil</span> con Taskly
                    </h1>
                    
                    <div class="text-xl md:text-2xl font-medium mb-8 h-10">
                        <span id="typewriter" class="typewriter"></span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-4 rounded-full font-semibold text-lg shadow-lg transition-all transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-rocket mr-2"></i> Comenzar ahora
                        </a>
                        <a href="#features" class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-4 rounded-full font-semibold text-lg transition-all">
                            <i class="fas fa-book-open mr-2"></i> Conoce más
                        </a>
                    </div>
                    
                    
                </div>
                
                <div class="md:w-1/2 flex justify-center">
                    <div class="relative max-w-md">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7" alt="Estudiante usando Taskly" class="rounded-xl shadow-2xl transform rotate-1">
                       
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
 <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stats-card bg-gray-50 p-6 rounded-xl text-center border border-gray-200">
                    <h3 class="text-indigo-600 text-3xl font-bold mb-2">95%</h3>
                    <p class="text-gray-600">Estudiantes mejor organizados</p>
                </div>
                <div class="stats-card bg-gray-50 p-6 rounded-xl text-center border border-gray-200">
                    <h3 class="text-indigo-600 text-3xl font-bold mb-2">87%</h3>
                    <p class="text-gray-600">Mejor rendimiento académico</p>
                </div>
                <div class="stats-card bg-gray-50 p-6 rounded-xl text-center border border-gray-200">
                    <h3 class="text-indigo-600 text-3xl font-bold mb-2">10+</h3>
                    <p class="text-gray-600">Horas ahorradas</p>
                </div>
                <div class="stats-card bg-gray-50 p-6 rounded-xl text-center border border-gray-200">
                    <h3 class="text-indigo-600 text-3xl font-bold mb-2">4/5</h3>
                    <p class="text-gray-600">Calificación promedio</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Diseñado para estudiantes</span></h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Taskly te ayuda a manejar tus proyectos universitarios, trabajos en grupo y recordatorios importantes</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6 text-indigo-600 text-2xl">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Gestión de tareas</h3>
                    <p class="text-gray-600">Organiza todas tus asignaciones por materia, prioridad y fecha de entrega. Marca tus progresos y nunca pierdas un plazo.</p>
                </div>
                
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mb-6 text-cyan-600 text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Trabajo en equipo</h3>
                    <p class="text-gray-600">Coordina trabajos grupales fácilmente. Asigna tareas, comparte archivos y comunícate con tus compañeros en un solo lugar.</p>
                </div>
                
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 text-purple-600 text-2xl">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Recordatorios inteligentes</h3>
                    <p class="text-gray-600">Recibe alertas personalizadas vía email o app para tus entregas importantes. Configura múltiples recordatorios para cada tarea.</p>
                </div>
                
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 text-green-600 text-2xl">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Planificador semanal</h3>
                    <p class="text-gray-600">Visualiza toda tu semana académica en una vista simple. Equilibra estudio, trabajos y tiempo libre eficientemente.</p>
                </div>
                
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 text-blue-600 text-2xl">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Archivos integrados</h3>
                    <p class="text-gray-600">Adjunta documentos importantes directamente a tus tareas y proyectos. Soporta PDFs, Word, PowerPoint y más.</p>
                </div>
                
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6 text-amber-600 text-2xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Seguimiento académico</h3>
                    <p class="text-gray-600">Analiza tu desempeño con gráficos y estadísticas. Identifica tus materias más demandantes y mejora tu productividad.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section id="testimonios" class="py-16 bg-gradient-to-br from-indigo-50 to-cyan-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Lo que dicen los estudiantes</span></h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Taskly ha ayudado a miles de estudiantes a mejorar su productividad</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="text-amber-400 text-xl">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Como estudiante de ingeniería con múltiples proyectos, Taskly me ha salvado la vida. Nunca más olvidé una entrega y mis notas han mejorado considerablemente."</p>
                    <div class="flex items-center">
                        <img src="{{ asset('img/so.jpg')}}" alt="" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Oliver Medrano</h4>
                            <p class="text-gray-600">Estudiante</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="text-amber-400 text-xl">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"La función de trabajo en equipo es increíble. Ahora coordinar proyectos grupales es mucho más fácil y eficiente."</p>
                    <div class="flex items-center">
                        <img src="{{ asset('img/as.jpg')}}" alt="" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Alex Lopez</h4>
                            <p class="text-gray-600">Estudiante de Matematicas</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="text-amber-400 text-xl">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Los recordatorios y el planificador semanal me han ayudado a mantener un mejor equilibrio entre estudio y vida personal."</p>
                    <div class="flex items-center">
                    <img src="{{ asset('img/th.jpg')}}" alt="" class="w-12 h-12 rounded-full mr-4">    
                        <div>
                            <h4 class="font-bold text-gray-900">Aaron Valentinez</h4>
                            <p class="text-gray-600">Medicina</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Conoce al equipo</span> detrás de Taskly</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Estudiantes como tú, creando soluciones para estudiantes</p>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-40 h-40 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-cyan-100 flex items-center justify-center text-indigo-600 text-5xl font-bold overflow-hidden">
                        <div>CC</div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Cristian Castro</h3>
                    <p class="text-indigo2">Frontend Developer</p>
                    <div class="flex justify-center space-x-3 mt-4">
                        <a href="https://github.com/CRINGMAN12" target="_blank" class="text-gray-500 hover:text-indigo-600"><i class="fab fa-github"></i></a>
                       
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="w-40 h-40 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-cyan-100 flex items-center justify-center text-indigo-600 text-5xl font-bold overflow-hidden">
                        <div>AR</div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Alejandro Rodriguez</h3>
                    <p class="text-indigo2">Backend Developer</p>
                    <div class="flex justify-center space-x-3 mt-4">
                        <a href="https://github.com/Alejo-Rh" target="_blank" class="text-gray-500 hover:text-indigo-600"><i class="fab fa-github"></i></a>
                       
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="w-40 h-40 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-cyan-100 flex items-center justify-center text-indigo-600 text-5xl font-bold overflow-hidden">
                        <div>SH</div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Sebastian Higuita</h3>
                    <p class="text-indigo2">UI/UX Designer</p>
                    <div class="flex justify-center space-x-3 mt-4">
                        <a href="https://github.com/JuanHiguita14" target="_blank" class="text-gray-500 hover:text-indigo-600"><i class="fab fa-github"></i></a>
                       
                    </div>
                    
                </div>
                
                <div class="text-center">
                    <div class="w-40 h-40 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-cyan-100 flex items-center justify-center text-indigo-600 text-5xl font-bold overflow-hidden">
                        <div>SE</div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Stiven Echeverry</h3>
                    <p class="text-indigo2">Product Manager</p>
                      <div class="flex justify-center space-x-3 mt-4">
                        <a href="https://github.com/stiven249022" target="_blank" class="text-gray-500 hover:text-indigo-600"><i class="fab fa-github"></i></a>
                       
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-16 bg-gradient-to-br from-indigo-600 to-cyan-500 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">¿Listo para transformar tu vida académica?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Regístrate ahora y lleva tu organización estudiantil al siguiente nivel</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-4 rounded-full font-semibold text-lg shadow-lg transition-all transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i> Crear cuenta
                </a>
              <a href="https://www.youtube.com/watch?v=oYobLFYuTXs&list=RDoYobLFYuTXs&start_radio=1" 
                 target="_blank"
                      class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-4 rounded-full font-semibold text-lg transition-all flex items-center justify-center">
                         <i class="fas fa-play-circle mr-2"></i> Ver demo
                </a>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-tasks mr-2"></i> Taskly
                    </h3>
                    <p class="text-gray-400">La herramienta definitiva para la organización estudiantil. Diseñada por estudiantes, para estudiantes.</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Enlaces</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Inicio</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Características</a></li>
                        <li><a href="#testimonios" class="text-gray-400 hover:text-white transition">Testimonios</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">Iniciar sesión</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contáctanos</h4>
                    <div class="flex space-x-4 mb-4">
                        <a href="https://www.facebook.com/profile.php?id=61584356486393" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-indigo-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/TasklyAyuda" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-indigo-600 transition">
                            <i class="fab fa-x"></i>
                        </a>
                        <a href="https://www.instagram.com/taskly2025/" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-indigo-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
    
                    </div>
                    <p class="text-gray-400">tasklyayuda@gmail.com</p>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>© {{ date('Y') }} Taskly. Todos los derechos reservados.</p>
                <p class="mt-2">Desarrollado con ❤️ por Cristian Castro Alcaraz, Alejandro Rodriguez Hincapie, Juan Sebastian Higuita Correa y Stiven Echeverry</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Typewriter effect
        const phrases = [
            "Organiza tus tareas",
            "Coordina trabajos grupales",
            "Mejora tu productividad",
            "Alcanza tus metas académicas"
        ];
        
        let currentPhrase = 0;
        let currentChar = 0;
        let isDeleting = false;
        let typewriterElement = document.getElementById('typewriter');
        
        function typeWriter() {
            const currentText = phrases[currentPhrase];
            
            if (isDeleting) {
                typewriterElement.textContent = currentText.substring(0, currentChar - 1);
                currentChar--;
            } else {
                typewriterElement.textContent = currentText.substring(0, currentChar + 1);
                currentChar++;
            }
            
            if (!isDeleting && currentChar === currentText.length) {
                isDeleting = true;
                setTimeout(typeWriter, 2000);
            } else if (isDeleting && currentChar === 0) {
                isDeleting = false;
                currentPhrase = (currentPhrase + 1) % phrases.length;
                setTimeout(typeWriter, 500);
            } else {
                setTimeout(typeWriter, isDeleting ? 50 : 100);
            }
        }
        
        // Start typewriter effect
        typeWriter();
        
        // Counter animation
        const counters = document.querySelectorAll('.counter');
        const speed = 200;
        
        counters.forEach(counter => {
            const target = +counter.innerText;
            const increment = target / speed;
            
            let count = 0;
            const updateCount = () => {
                if (count < target) {
                    count += increment;
                    counter.innerText = Math.ceil(count);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            
            updateCount();
        });
    </script>
</body>
</html>
