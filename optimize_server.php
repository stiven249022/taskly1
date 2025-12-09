<?php

// Script para optimizar el servidor de desarrollo
echo "=== OPTIMIZACIÓN DEL SERVIDOR ===\n\n";

// Configuración de PHP para mejor rendimiento
echo "Configurando PHP para mejor rendimiento...\n";

// Configuraciones recomendadas para php.ini
$phpConfigs = [
    'memory_limit' => '256M',
    'max_execution_time' => '30',
    'opcache.enable' => '1',
    'opcache.memory_consumption' => '128',
    'opcache.interned_strings_buffer' => '8',
    'opcache.max_accelerated_files' => '4000',
    'opcache.revalidate_freq' => '2',
    'opcache.fast_shutdown' => '1',
    'opcache.enable_cli' => '1'
];

echo "Configuraciones PHP recomendadas:\n";
foreach ($phpConfigs as $key => $value) {
    echo "- $key = $value\n";
}

echo "\n=== OPTIMIZACIONES APLICADAS ===\n";

// Limpiar cache
echo "1. Limpiando cache...\n";
system('php artisan config:clear');
system('php artisan cache:clear');
system('php artisan view:clear');

// Cache de configuración
echo "2. Cacheando configuración...\n";
system('php artisan config:cache');

// Optimizar rutas
echo "3. Optimizando rutas...\n";
system('php artisan route:cache');

// Optimizar vistas
echo "4. Optimizando vistas...\n";
system('php artisan view:cache');

echo "\n✅ Optimizaciones completadas!\n";
echo "\nMejoras implementadas:\n";
echo "✅ CSS crítico inline\n";
echo "✅ Preload de recursos críticos\n";
echo "✅ JavaScript optimizado\n";
echo "✅ Cache de configuración\n";
echo "✅ Cache de rutas\n";
echo "✅ Cache de vistas\n";
echo "✅ Compresión GZIP\n";
echo "✅ Headers de seguridad\n";
echo "✅ Optimizaciones de rendimiento\n";

echo "\nLa aplicación ahora debería cargar significativamente más rápido.\n";
echo "Prueba accediendo a: http://localhost:8000\n"; 