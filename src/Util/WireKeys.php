<?php

declare(strict_types=1);

namespace Bybit\Util;

/**
 * snake_case <-> camelCase key translation for wire payloads.
 *
 * PHP callers naturally supply camelCase array keys matching Bybit's
 * spec-native shape, so this helper is a no-op for most services. It exists
 * primarily so users who supply snake_case keys (idiomatic in some PHP
 * codebases) still get camelCase on the wire.
 *
 * Recursion: batch endpoints (trade.batchAmendOrders, account.batchSetCollateral,
 * etc.) carry list<array> or nested array payloads whose inner keys must ALSO
 * be camelized.
 */
final class WireKeys
{
    /**
     * PHP-reserved-word aliases: end_ => end on the wire. Callers who avoid
     * `end` as a parameter name (PHP does not treat `end` as a keyword but
     * we mirror Ruby for consistency) can pass `end_` and get `end` sent.
     */
    private const RESERVED_ALIASES = [
        'end_'    => 'end',
        'begin_'  => 'begin',
        'class_'  => 'class',
        'next_'   => 'next',
        'return_' => 'return',
        'do_'     => 'do',
        'if_'     => 'if',
        'else_'   => 'else',
        'list_'   => 'list',
        'array_'  => 'array',
        'match_'  => 'match',
        'fn_'     => 'fn',
    ];

    private function __construct()
    {
    }

    /**
     * Convert every key of an associative array from snake_case to camelCase.
     * Recurses into nested arrays. List-of-arrays are also handled.
     * Non-associative arrays (list<mixed>) pass through unchanged at the leaf.
     *
     * @param array<mixed,mixed>|null $input
     * @return array<mixed,mixed>|null
     */
    public static function camelize(?array $input): ?array
    {
        if ($input === null) {
            return null;
        }
        $out = [];
        foreach ($input as $k => $v) {
            $key = is_string($k) ? self::toCamel(self::unalias($k)) : $k;
            $out[$key] = self::camelizeValue($v);
        }
        return $out;
    }

    /**
     * @param mixed $v
     * @return mixed
     */
    private static function camelizeValue($v)
    {
        if (!is_array($v)) {
            return $v;
        }
        // Detect list-of-assoc-array (batch endpoints): recurse into each
        // element; keep numeric-list shape by resetting numeric keys.
        if (array_is_list($v)) {
            $out = [];
            foreach ($v as $el) {
                $out[] = is_array($el) ? self::camelize($el) : $el;
            }
            return $out;
        }
        return self::camelize($v);
    }

    private static function unalias(string $key): string
    {
        return self::RESERVED_ALIASES[$key] ?? $key;
    }

    private static function toCamel(string $key): string
    {
        if (strpos($key, '_') === false) {
            return $key;
        }
        $parts = explode('_', $key);
        $first = array_shift($parts);
        $tail = implode('', array_map(static fn($p) => ucfirst($p), array_filter($parts, static fn($p) => $p !== '')));
        return $first . $tail;
    }
}
