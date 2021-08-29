<?php
declare(strict_types=1);

namespace Szemul\LoggingErrorHandling\Context;

class ContextEntryFactory
{
    /**
     * @param array<string,mixed> $values
     * @param string[]            $scopes
     *
     * @return ContextEntry[]
     */
    public function createContextEntries(array $values, ?string $errorHandlerType, array $scopes = []): array
    {
        $entries = [];

        foreach ($values as $key => $value) {
            $entries[] = new ContextEntry($key, $value, $errorHandlerType, $scopes);
        }

        return $entries;
    }
}
