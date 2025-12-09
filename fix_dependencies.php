<?php

echo "=== REPARACIÓN DE DEPENDENCIAS ===\n\n";

echo "1. Limpiando cache de Composer...\n";
system('composer clear-cache');

echo "2. Eliminando vendor y composer.lock...\n";
if (is_dir('vendor')) {
    system('rmdir /s /q vendor');
}
if (file_exists('composer.lock')) {
    unlink('composer.lock');
}

echo "3. Reinstalando dependencias...\n";
system('composer install');

echo "4. Optimizando autoloader...\n";
system('composer dump-autoload --optimize');

echo "5. Limpiando cache de Laravel...\n";
system('php artisan config:clear');
system('php artisan cache:clear');
system('php artisan view:clear');

echo "6. Cacheando configuración...\n";
system('php artisan config:cache');

echo "\n✅ Dependencias reparadas exitosamente!\n";
echo "Ahora puedes ejecutar: php artisan serve\n"; 