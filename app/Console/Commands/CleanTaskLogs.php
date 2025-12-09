<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanTaskLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean-task-logs {--days=30 : Número de días para mantener los logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia los logs de tareas más antiguos que el número de días especificado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $deletedCount = DB::table('task_logs')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("Se eliminaron {$deletedCount} registros de logs de tareas más antiguos que {$days} días.");
        
        if ($deletedCount > 0) {
            $this->info('Limpieza de logs completada exitosamente.');
        } else {
            $this->info('No se encontraron logs para eliminar.');
        }

        return Command::SUCCESS;
    }
} 