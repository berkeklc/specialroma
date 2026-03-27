<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\QrMenu\App\Models\MenuTable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

final class GenerateTableQrCode
{
    public function execute(MenuTable $table): string
    {
        $restaurant = $table->restaurant;
        $url = route('qr-menu.public', [
            'restaurant' => $restaurant->slug,
            'table' => $table->id,
        ]);

        $filename = 'qr-codes/table-' . $table->id . '-' . now()->timestamp . '.svg';

        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(10)
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($url);

        Storage::disk('public')->put($filename, $qrCode);

        $table->update([
            'qr_code_url' => $url,
            'qr_code_path' => Storage::disk('public')->url($filename),
        ]);

        return Storage::disk('public')->url($filename);
    }
}
