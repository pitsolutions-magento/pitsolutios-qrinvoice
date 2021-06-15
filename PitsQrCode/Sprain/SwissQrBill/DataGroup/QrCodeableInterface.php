<?php declare(strict_types=1);

namespace PitsQrCode\Sprain\SwissQrBill\DataGroup;

interface QrCodeableInterface
{
    public function getQrCodeData(): array;
}
