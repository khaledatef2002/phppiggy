<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class MatchRule implements RuleInterface
{
    public function validate(array $formData, string $field, array $params): bool
    {
        if (empty($params))
            throw new InvalidArgumentException("Must have 1 parameter to match it!");
        $toBeMatched = $formData[$field];
        $matcher = $formData[$params[0]];

        return $toBeMatched == $matcher;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Doesn't match {$data[$params[0]]}";
    }
}
