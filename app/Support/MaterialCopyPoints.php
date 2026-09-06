<?php

namespace App\Support;

class MaterialCopyPoints
{
    /**
     * Split business introduction / product copy into one-sentence classroom points.
     *
     * @return list<string>
     */
    public static function from(mixed $value): array
    {
        if (is_array($value)) {
            $out = [];
            foreach ($value as $item) {
                foreach (self::from($item) as $point) {
                    $out[] = $point;
                }
            }

            return self::unique($out);
        }

        $text = self::normalize((string) $value);
        if ($text === '' || $text === '—') {
            return [];
        }

        $points = [];
        foreach (self::blocks($text) as $block) {
            foreach (self::expand($block) as $point) {
                $point = self::clean($point);
                if ($point !== '') {
                    $points[] = $point;
                }
            }
        }

        return self::unique($points);
    }

    private static function normalize(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/<br\s*\/?>/iu', "\n", $text) ?? $text;
        $text = preg_replace('/<\/p>\s*<p[^>]*>/iu', "\n", $text) ?? $text;
        $text = preg_replace('/<\/?(div|li|ul|ol|h[1-6])[^>]*>/iu', "\n", $text) ?? $text;
        $text = strip_tags($text);
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = str_replace(["\xC2\xA0", '•', '●', '▪', '➤'], "\n", $text);
        $text = preg_replace('/[ \t]+/u', ' ', $text) ?? $text;
        $text = preg_replace('/\n[ \t]+/u', "\n", $text) ?? $text;
        $text = preg_replace('/\n{2,}/u', "\n", $text) ?? $text;

        return trim($text);
    }

    /**
     * @return list<string>
     */
    private static function blocks(string $text): array
    {
        $parts = preg_split('/\n+/u', $text) ?: [];
        $parts = array_values(array_filter(array_map('trim', $parts)));
        if ($parts === []) {
            $parts = [$text];
        }

        if (count($parts) === 1) {
            $numbered = self::splitNumbered($parts[0]);
            if (count($numbered) > 1) {
                $parts = $numbered;
            }
        }

        return self::splitSections($parts);
    }

    /**
     * @param  list<string>  $parts
     * @return list<string>
     */
    private static function splitSections(array $parts): array
    {
        $out = [];
        foreach ($parts as $part) {
            $bits = preg_split('/(?=(?:^|\s)For\s+[A-Z][^:]{1,48}:)/u', $part) ?: [$part];
            foreach ($bits as $bit) {
                $bit = trim($bit);
                if ($bit !== '') {
                    $out[] = $bit;
                }
            }
        }

        return $out !== [] ? $out : $parts;
    }

    /**
     * @return list<string>
     */
    private static function expand(string $block): array
    {
        $block = trim($block);
        if ($block === '') {
            return [];
        }

        if (self::isHeading($block)) {
            return [$block];
        }

        $numbered = self::splitNumbered($block);
        if (count($numbered) > 1) {
            $out = [];
            foreach ($numbered as $part) {
                foreach (self::expand($part) as $point) {
                    $out[] = $point;
                }
            }

            return $out;
        }

        $sentences = self::sentences($block);
        if (count($sentences) > 1) {
            return $sentences;
        }

        $labeled = self::splitLabeled($block);
        if (count($labeled) > 1) {
            return $labeled;
        }

        return [$block];
    }

    /**
     * @return list<string>
     */
    private static function sentences(string $text): array
    {
        $protected = preg_replace(
            '/\b(Mr|Mrs|Ms|Dr|Prof|Sr|Jr|St|No|vs|etc|Inc|Ltd|Pvt|Co|e\.g|i\.e)\./iu',
            '$1<prd>',
            $text
        ) ?? $text;

        $parts = preg_split('/(?<=[.!?…।])\s+(?=\S)/u', $protected) ?: [];
        if (count($parts) <= 1) {
            $parts = preg_split('/(?<=[.!?…।])(?=[A-Z“"\'])/u', $protected) ?: [$protected];
        }

        $out = [];
        foreach ($parts as $part) {
            $part = trim(str_replace('<prd>', '.', (string) $part));
            if ($part !== '') {
                $out[] = $part;
            }
        }

        return $out !== [] ? $out : [str_replace('<prd>', '.', $text)];
    }

    /**
     * @return list<string>
     */
    private static function splitNumbered(string $text): array
    {
        if (! preg_match('/(?:^|\s)\d+[.)]\s+\S/u', $text)) {
            return [$text];
        }

        $parts = preg_split('/(?=(?:^|\s)\d+[.)]\s+\S)/u', $text) ?: [];
        $out = [];
        foreach ($parts as $part) {
            $part = trim(preg_replace('/^\d+[.)]\s+/u', '', trim($part)) ?? '');
            if ($part !== '') {
                $out[] = $part;
            }
        }

        return count($out) > 1 ? $out : [$text];
    }

    /**
     * Split only complete labels such as "Storytelling First:" or "For Personal Clients:".
     * A label must start at the beginning, after a sentence, or after a lowercase word.
     *
     * @return list<string>
     */
    private static function splitLabeled(string $text): array
    {
        $label = '(?:For\s+[A-Z][^:]{1,48}|(?:[A-Z][A-Za-z0-9’\'\/+-]+(?:\s+(?:&|and|\/|[A-Z][A-Za-z0-9’\'\/+-]+)){0,6})):';
        if (! preg_match_all('/(?:^|(?<=[.!?…।]\s)|(?<=[a-z0-9,;]\s))('.$label.')/u', $text, $matches, PREG_OFFSET_CAPTURE)) {
            return [$text];
        }

        $starts = [];
        foreach ($matches[1] as $match) {
            $starts[] = (int) $match[1];
        }
        $starts = array_values(array_unique($starts));
        if ($starts === [] || (count($starts) === 1 && $starts[0] === 0)) {
            return [$text];
        }

        $out = [];
        $cursor = 0;
        foreach ($starts as $start) {
            if ($start > $cursor) {
                $before = trim(substr($text, $cursor, $start - $cursor));
                if ($before !== '') {
                    $out[] = $before;
                }
            }
            $cursor = $start;
        }
        $tail = trim(substr($text, $cursor));
        if ($tail !== '') {
            $out[] = $tail;
        }

        return count($out) > 1 ? $out : [$text];
    }

    private static function isHeading(string $value): bool
    {
        $trim = rtrim($value);

        return mb_strlen($trim) <= 56 && str_ends_with($trim, ':') && ! preg_match('/[.!?…।]/u', $trim);
    }

    private static function clean(string $value): string
    {
        $value = trim(preg_replace('/^\s*(?:[-*•●]|\d+[.)])\s+/u', '', $value) ?? $value);
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }

    /**
     * @param  list<string>  $points
     * @return list<string>
     */
    private static function unique(array $points): array
    {
        $seen = [];
        $out = [];
        foreach ($points as $point) {
            $key = mb_strtolower($point);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[] = $point;
        }

        return $out;
    }
}
