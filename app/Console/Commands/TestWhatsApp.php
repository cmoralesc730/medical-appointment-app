<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class TestWhatsApp extends Command
{
    protected $signature   = 'whatsapp:test {phone : Número en formato internacional, ej: 5216641234567}';
    protected $description = 'Envía un mensaje de prueba de WhatsApp al número indicado';

    public function handle(WhatsAppService $whatsapp): int
    {
        $phone = $this->argument('phone');

        $this->info("Enviando mensaje de prueba a: {$phone}");
        $this->info('FROM : ' . config('services.twilio.whatsapp_from'));
        $this->info('SID  : ' . config('services.twilio.sid'));

        try {
            $whatsapp->send($phone, "✅ Mensaje de prueba desde Healthify.\n\nSi recibes esto, la integración con WhatsApp funciona correctamente.");
            $this->info('✓ Mensaje enviado correctamente.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('✗ Error al enviar el mensaje:');
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
