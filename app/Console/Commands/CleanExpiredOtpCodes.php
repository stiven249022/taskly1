<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OtpCode;

class CleanExpiredOtpCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar códigos OTP expirados de la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = OtpCode::cleanExpiredCodes();
        
        $this->info("Se eliminaron {$deletedCount} códigos OTP expirados.");
        
        return Command::SUCCESS;
    }
}
