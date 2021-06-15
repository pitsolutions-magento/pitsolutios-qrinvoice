<?php declare(strict_types=1);

namespace PitsQrCode\Sprain\SwissQrBill\Constraint;

use PitsQrCode\kmukku\phpIso11649\phpIso11649;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidCreditorReferenceValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidCreditorReference) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ValidCreditorReference');
        }

        if (null === $value || '' === $value) {
            return;
        }

        $referenceGenerator = new phpIso11649();

        if (false === $referenceGenerator->validateRfReference($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}