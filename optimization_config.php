<?php

// Configuración de optimización para Taskly
// Ejecuta este archivo para aplicar optimizaciones

echo "=== OPTIMIZACIÓN DE RENDIMIENTO ===\n\n";

// 1. Limpiar cache
echo "1. Limpiando cache...\n";
system('php artisan config:clear');
system('php artisan cache:clear');
system('php artisan view:clear');
system('php artisan route:clear');

// 2. Optimizar autoloader
echo "2. Optimizando autoloader...\n";
system('composer install --optimize-autoloader --no-dev');

// 3. Cache de configuración
echo "3. Cacheando configuración...\n";
system('php artisan config:cache');
system('php artisan route:cache');
system('php artisan view:cache');

// 4. Optimizar base de datos
echo "4. Optimizando base de datos...\n";
system('php artisan migrate --force');

echo "\n✅ Optimizaciones aplicadas exitosamente!\n";
echo "\nMejoras implementadas:\n";
echo "- Cache de configuración activado\n";
echo "- Autoloader optimizado\n";
echo "- Rutas cacheadas\n";
echo "- Vistas cacheadas\n";
echo "- Base de datos optimizada\n";
echo "\nLa aplicación ahora debería cargar más rápido.\n"; 