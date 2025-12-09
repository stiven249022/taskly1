<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Mostrar página de configuración del sistema
     */
    public function index()
    {
        $settings = $this->getSystemSettings();
        return view('admin.settings', compact('settings'));
    }

    /**
     * Actualizar configuración general
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string|max:255',
            'system_url' => 'required|url|max:255',
            'timezone' => 'required|string|max:100',
            'default_language' => 'required|in:es,en',
            'auto_registration' => 'boolean',
            'email_verification' => 'boolean',
            'maintenance_mode' => 'boolean',
            'max_users' => 'nullable|integer|min:1',
            'max_tasks_per_user' => 'nullable|integer|min:1',
            'max_projects_per_user' => 'nullable|integer|min:1',
        ]);

        $this->saveSystemSettings($validated);

        return response()->json([
            'success' => true,
            'message' => 'Configuración general actualizada exitosamente'
        ]);
    }

    /**
     * Actualizar configuración de seguridad
     */
    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6|max:32',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_symbols' => 'boolean',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:1|max:60',
            'two_factor_enabled' => 'boolean',
            'ip_whitelist' => 'nullable|string|max:1000',
        ]);

        $this->saveSystemSettings($validated);

        return response()->json([
            'success' => true,
            'message' => 'Configuración de seguridad actualizada exitosamente'
        ]);
    }

    /**
     * Actualizar configuración de notificaciones
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_enabled' => 'boolean',
            'email_from_address' => 'nullable|email|max:255',
            'email_from_name' => 'nullable|string|max:255',
            'push_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'notification_frequency' => 'required|in:realtime,hourly,daily,weekly',
            'task_reminder_days' => 'required|integer|min:0|max:30',
            'project_reminder_days' => 'required|integer|min:0|max:30',
        ]);

        $this->saveSystemSettings($validated);

        return response()->json([
            'success' => true,
            'message' => 'Configuración de notificaciones actualizada exitosamente'
        ]);
    }

    /**
     * Obtener información de la base de datos
     */
    public function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $databaseName = $connection->getDatabaseName();
            
            // Obtener tamaño de la base de datos
            $sizeQuery = "SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
                FROM information_schema.tables 
                WHERE table_schema = ?";
            
            $size = DB::selectOne($sizeQuery, [$databaseName]);
            
            // Obtener número de tablas
            $tables = DB::select("SHOW TABLES");
            $tableCount = count($tables);
            
            // Obtener información de las tablas principales
            $mainTables = ['users', 'tasks', 'projects', 'courses', 'reminders', 'notifications'];
            $tableInfo = [];
            
            foreach ($mainTables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $tableInfo[$table] = $count;
                } catch (\Exception $e) {
                    $tableInfo[$table] = 0;
                }
            }
            
            return response()->json([
                'success' => true,
                'database' => [
                    'name' => $databaseName,
                    'size_mb' => $size->size_mb ?? 0,
                    'table_count' => $tableCount,
                    'tables' => $tableInfo,
                    'connection' => config('database.default'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información de la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Realizar respaldo de la base de datos
     */
    public function backupDatabase(Request $request)
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Crear directorio si no existe
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            
            // Ejecutar mysqldump (requiere que esté instalado)
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            if (!$database || !$username || $password === null || !$host) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuración de base de datos incompleta para realizar respaldo.'
                ], 422);
            }

            $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');
            $cmd = sprintf(
                '%s --host=%s --port=%d --user=%s --password=%s --routines --events --single-transaction --quick --lock-tables=false %s > %s 2>&1',
                escapeshellarg($mysqldump),
                escapeshellarg($host),
                $port,
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($path)
            );

            exec($cmd, $output, $returnVar);
            
            if ($returnVar === 0 && file_exists($path)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Respaldo creado exitosamente',
                    'filename' => $filename,
                    'size' => filesize($path)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el respaldo. Verifica que mysqldump esté instalado y accesible en el PATH o configura MYSQLDUMP_PATH en .env.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el respaldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar caché del sistema
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Caché limpiado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar el caché: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener configuraciones del sistema
     */
    private function getSystemSettings()
    {
        $defaults = $this->getDefaultSettings();
        
        // Usar tabla de configuraciones si existe, sino usar valores por defecto
        try {
            if (DB::getSchemaBuilder()->hasTable('system_settings')) {
                $dbSettings = DB::table('system_settings')->pluck('value', 'key')->toArray();
                
                // Convertir valores booleanos
                foreach ($dbSettings as $key => $value) {
                    if (in_array($key, ['auto_registration', 'email_verification', 'maintenance_mode', 
                                         'password_require_uppercase', 'password_require_lowercase', 
                                         'password_require_numbers', 'password_require_symbols', 
                                         'two_factor_enabled', 'email_enabled', 'push_enabled', 'sms_enabled'])) {
                        $dbSettings[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    } elseif (in_array($key, ['max_users', 'max_tasks_per_user', 'max_projects_per_user', 
                                               'password_min_length', 'session_timeout', 'max_login_attempts', 
                                               'lockout_duration', 'task_reminder_days', 'project_reminder_days'])) {
                        $dbSettings[$key] = (int) $value;
                    }
                }
                
                return array_merge($defaults, $dbSettings);
            }
        } catch (\Exception $e) {
            // Si la tabla no existe, usar valores por defecto
        }
        
        return $defaults;
    }

    /**
     * Guardar configuraciones del sistema
     */
    private function saveSystemSettings(array $settings)
    {
        // Crear tabla si no existe
        if (!DB::getSchemaBuilder()->hasTable('system_settings')) {
            DB::statement('CREATE TABLE IF NOT EXISTS system_settings (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `key` VARCHAR(255) NOT NULL UNIQUE,
                value TEXT,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
        }
        
        foreach ($settings as $key => $value) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : $value, 'updated_at' => now()]
            );
        }
    }

    /**
     * Obtener valores por defecto
     */
    private function getDefaultSettings()
    {
        return [
            'system_name' => config('app.name', 'Taskly'),
            'system_url' => config('app.url', 'http://127.0.0.1:8000'),
            'timezone' => config('app.timezone', 'America/Mexico_City'),
            'default_language' => config('app.locale', 'es'),
            'auto_registration' => true,
            'email_verification' => true,
            'maintenance_mode' => false,
            'max_users' => 1000,
            'max_tasks_per_user' => 100,
            'max_projects_per_user' => 50,
            'password_min_length' => 8,
            'password_require_uppercase' => false,
            'password_require_lowercase' => false,
            'password_require_numbers' => false,
            'password_require_symbols' => false,
            'session_timeout' => 120,
            'max_login_attempts' => 5,
            'lockout_duration' => 15,
            'two_factor_enabled' => false,
            'ip_whitelist' => '',
            'email_enabled' => true,
            'email_from_address' => config('mail.from.address', 'noreply@taskly.com'),
            'email_from_name' => config('mail.from.name', 'Taskly'),
            'push_enabled' => false,
            'sms_enabled' => false,
            'notification_frequency' => 'realtime',
            'task_reminder_days' => 1,
            'project_reminder_days' => 3,
        ];
    }
}

