<?php

namespace App\GraphQL\Scalars;

use Carbon\Carbon;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;

class DateTime extends ScalarType
{
    /**
     * Nama scalar untuk didaftarkan
     *
     * @var string
     */
    public static string $name = 'DateTime';

    /**
     * Serialize nilai keluar (misalnya dari DB ke client)
     */
    public function serialize($value)
    {
        return $value instanceof \DateTimeInterface
            ? $value->format('Y-m-d H:i:s')
            : null;
    }

    /**
     * Parse nilai dari client ke server (input)
     */
    public function parseValue($value)
    {
        return Carbon::parse($value);
    }

    /**
     * Parse literal dari query GraphQL
     */
    public function parseLiteral($valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof StringValueNode) {
            return Carbon::parse($valueNode->value);
        }

        return null;
    }
}
