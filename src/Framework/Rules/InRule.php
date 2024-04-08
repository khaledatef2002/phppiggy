<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class InRule implements RuleInterface
{
    public function validate(array $formData, string $field, array $params): bool
    {
        if (empty($params))
            throw new InvalidArgumentException("InRule must have at least 1 parameter");

        return in_array($formData[$field], $params);
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "{$data[$field]} isn't a valid value";
    }
}
