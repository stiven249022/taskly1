<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTaskCompletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Registrar la finalización de la tarea
            Log::info("Tarea completada: {$this->task->title} por usuario ID: {$this->task->user_id}");
            
            // Aquí podrías agregar lógica adicional como:
            // - Enviar notificaciones
            // - Actualizar estadísticas
            // - Generar reportes
            // - Enviar emails de confirmación
            
            $this->task->update([
                'completed_at' => now()
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error procesando finalización de tarea: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job de procesamiento de tarea falló: " . $exception->getMessage());
    }
} 