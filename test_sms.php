<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\ClinicSetting::current();
echo "Provider: $s->sms_provider\n";
echo "URL: $s->sms_api_url\n";
echo "Key: $s->sms_api_key\n";
echo "Sender: $s->sender_id\n\n";

// Send directly
$service = app(App\Services\SmsService::class);
$log = $service->sendRaw('0613976254', 'Test kutoka CLI', null, 'manual', null);
echo "Log ID: $log->id\n";
echo "Status: $log->status\n";
echo "Response: $log->response\n";
