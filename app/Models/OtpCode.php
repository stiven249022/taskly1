<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'type',
        'used',
        'expires_at'
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * Generar un código OTP de 6 dígitos
     */
    public static function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Crear un nuevo código OTP
     */
    public static function createCode(string $email, string $type = 'email_verification', int $expirationMinutes = 10): self
    {
        // Invalidar códigos anteriores para el mismo email y tipo
        self::where('email', $email)
            ->where('type', $type)
            ->where('used', false)
            ->update(['used' => true]);

        return self::create([
            'email' => $email,
            'code' => self::generateCode(),
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes($expirationMinutes)
        ]);
    }

    /**
     * Verificar si el código es válido
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * Marcar el código como usado
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }

    /**
     * Limpiar códigos expirados
     */
    public static function cleanExpiredCodes(): int
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }
}
