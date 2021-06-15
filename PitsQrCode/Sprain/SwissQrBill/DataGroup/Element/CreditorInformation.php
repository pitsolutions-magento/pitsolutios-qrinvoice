<?php declare(strict_types=1);

namespace PitsQrCode\Sprain\SwissQrBill\DataGroup\Element;

use PitsQrCode\Sprain\SwissQrBill\DataGroup\QrCodeableInterface;
use PitsQrCode\Sprain\SwissQrBill\Validator\SelfValidatableInterface;
use PitsQrCode\Sprain\SwissQrBill\Validator\SelfValidatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class CreditorInformation implements QrCodeableInterface, SelfValidatableInterface
{
    use SelfValidatableTrait;

    /**
     * IBAN or QR-IBAN of the creditor
     *
     * @var string
     */
    private $iban;

    public static function create(string $iban): self
    {
        $creditorInformation = new self();
        $creditorInformation->iban = preg_replace('/\s+/', '', $iban);

        return $creditorInformation;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function getFormattedIban(): ?string
    {
        if (null === $this->iban) {
            return null;
        }

        return trim(chunk_split($this->iban, 4, ' '));
    }

    public function containsQrIban(): bool
    {
        $qrIid = substr($this->iban, 4, 5);

        if ($this->isValid() && (int) $qrIid >= 30000 && (int) $qrIid <= 31999) {
            return true;
        }

        return false;
    }

    public function getQrCodeData(): array
    {
        return [
            $this->getIban()
        ];
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        // Only IBANs with CH or LI country code
        $metadata->addPropertyConstraints('iban', [
            new Assert\NotBlank(),
            new Assert\Iban(),
            new Assert\Regex([
                'pattern' => '/^(CH|LI)/',
                'match' => true
            ])
        ]);
    }
}
