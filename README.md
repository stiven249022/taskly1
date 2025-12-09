# Taskly - Gestión de Tareas y Proyectos

Taskly es una aplicación web moderna para la gestión de tareas, proyectos, cursos y recordatorios. Diseñada para estudiantes y profesores, ofrece una interfaz intuitiva y funcionalidades esenciales para organizar el trabajo académico.

## Características Principales

### 📋 Gestión de Tareas
- Crear, editar y eliminar tareas
- Asignar prioridades y fechas límite
- Marcar tareas como completadas
- Filtrar por curso y estado
- Seguimiento del progreso

### 📊 Gestión de Proyectos
- Crear y gestionar proyectos
- Asignar tareas a proyectos
- Seguimiento del progreso
- Organización por categorías

### 🎓 Gestión de Cursos
- Crear y organizar cursos
- Asociar tareas con cursos específicos
- Seguimiento académico

### 🔔 Recordatorios
- Crear recordatorios personalizados
- Notificaciones programadas
- Organización por fechas

### 📅 Calendario
- Vista de calendario integrada
- Visualización de tareas y eventos
- Gestión de fechas importantes

## Tecnologías Utilizadas

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Blade Templates, Tailwind CSS
- **Base de Datos**: MySQL/PostgreSQL
- **Autenticación**: Laravel Breeze
- **JavaScript**: Alpine.js

## Instalación

### Requisitos Previos
- PHP 8.2 o superior
- Composer
- Node.js y npm
- Base de datos MySQL o PostgreSQL

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd taskly
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Node.js**
   ```bash
   npm install
   ```

4. **Configurar el archivo de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar la base de datos**
   Editar el archivo `.env` con las credenciales de tu base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=taskly
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

6. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

7. **Ejecutar seeders (opcional)**
   ```bash
   php artisan db:seed
   ```

8. **Compilar assets**
   ```bash
   npm run build
   ```

9. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

## Usuarios de Prueba

Después de ejecutar los seeders, puedes usar estos usuarios de prueba:

- **Email**: usuario@taskly.com | **Contraseña**: usuario123
- **Email**: maria@taskly.com | **Contraseña**: maria123
- **Email**: juan@taskly.com | **Contraseña**: juan123

## Estructura del Proyecto

```
taskly/
├── app/
│   ├── Http/Controllers/     # Controladores de la aplicación
│   ├── Models/              # Modelos Eloquent
│   ├── Policies/            # Políticas de autorización
│   └── Jobs/                # Trabajos en segundo plano
├── database/
│   ├── migrations/          # Migraciones de base de datos
│   └── seeders/             # Seeders para datos de prueba
├── resources/
│   └── views/               # Vistas Blade
│       ├── tasks/           # Vistas de tareas
│       ├── projects/        # Vistas de proyectos
│       ├── courses/         # Vistas de cursos
│       ├── reminders/       # Vistas de recordatorios
│       └── layouts/         # Layouts principales
└── routes/
    └── web.php              # Rutas web
```

## Funcionalidades por Usuario

### Estudiantes
- Crear y gestionar tareas personales
- Organizar proyectos académicos
- Gestionar cursos y materias
- Configurar recordatorios
- Visualizar calendario personal

### Profesores
- Todas las funcionalidades de estudiantes
- Gestión avanzada de proyectos
- Organización de cursos
- Seguimiento de progreso

## Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Soporte

Si tienes alguna pregunta o necesitas ayuda, por favor abre un issue en el repositorio.
