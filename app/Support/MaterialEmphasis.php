<?php

namespace App\Support;

class MaterialEmphasis
{
    /**
     * @param  list<string>  $phrases
     */
    public static function html(string $value, array $phrases = []): string
    {
        $raw = trim($value);
        if ($raw === '') {
            return '';
        }

        if (mb_strlen($raw) <= 48 && ! preg_match('/[.!?…।]/u', rtrim($raw, ':'))) {
            return '<strong>'.e($raw).'</strong>';
        }

        $marks = [];
        $names = collect($phrases)
            ->map(fn ($phrase) => trim((string) $phrase))
            ->filter(fn ($phrase) => $phrase !== '' && $phrase !== '—')
            ->sortByDesc(fn ($phrase) => mb_strlen($phrase))
            ->values()
            ->all();

        foreach ($names as $phrase) {
            if (preg_match_all('/'.preg_quote($phrase, '/').'/iu', $raw, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as $match) {
                    self::addMark($marks, (int) $match[1], (int) $match[1] + strlen($match[0]));
                }
            }
        }

        if (preg_match_all('/[“"\']([^”"\']{2,80})[”"\']/u', $raw, $quotes, PREG_OFFSET_CAPTURE)) {
            foreach ($quotes[0] as $match) {
                self::addMark($marks, (int) $match[1], (int) $match[1] + strlen($match[0]));
            }
        }

        if (preg_match('/^([^:]{2,48}):/u', $raw, $label, PREG_OFFSET_CAPTURE)) {
            self::addMark($marks, 0, strlen($label[1][0]) + 1);
        }

        if (preg_match_all('/\b[A-Z][A-Za-z0-9’\']+(?:\s+(?:[A-Z][A-Za-z0-9’\']+|\/|&)){1,6}\b/u', $raw, $titles, PREG_OFFSET_CAPTURE)) {
            foreach ($titles[0] as $match) {
                self::addMark($marks, (int) $match[1], (int) $match[1] + strlen($match[0]));
            }
        }

        foreach (self::sentences($raw) as [$start, $end]) {
            $hasMark = false;
            foreach ($marks as $mark) {
                if ($mark[0] >= $start && $mark[1] <= $end) {
                    $hasMark = true;
                    break;
                }
            }
            if ($hasMark) {
                continue;
            }
            $span = self::firstImportant(substr($raw, $start, $end - $start));
            if ($span) {
                self::addMark($marks, $start + $span[0], $start + $span[1]);
            }
        }

        usort($marks, fn ($a, $b) => $a[0] <=> $b[0]);
        $html = '';
        $cursor = 0;
        foreach ($marks as [$from, $to]) {
            if ($from < $cursor) {
                continue;
            }
            $html .= e(substr($raw, $cursor, $from - $cursor));
            $html .= '<strong>'.e(substr($raw, $from, $to - $from)).'</strong>';
            $cursor = $to;
        }
        $html .= e(substr($raw, $cursor));

        return $html;
    }

    /**
     * @param  list<array{0: int, 1: int}>  $marks
     */
    private static function addMark(array &$marks, int $start, int $end): void
    {
        if ($start < 0 || $end <= $start) {
            return;
        }
        foreach ($marks as $mark) {
            if ($start < $mark[1] && $end > $mark[0]) {
                return;
            }
        }
        $marks[] = [$start, $end];
    }

    /**
     * @return list<array{0: int, 1: int}>
     */
    private static function sentences(string $value): array
    {
        if (! preg_match_all('/[^.!?…।]+(?:[.!?…।]+|$)/u', $value, $matches, PREG_OFFSET_CAPTURE)) {
            return [[0, strlen($value)]];
        }

        $ranges = [];
        foreach ($matches[0] as $match) {
            $text = $match[0];
            $lead = strlen($text) - strlen(ltrim($text));
            $start = (int) $match[1] + $lead;
            $end = (int) $match[1] + strlen($text);
            if ($end > $start) {
                $ranges[] = [$start, $end];
            }
        }

        return $ranges !== [] ? $ranges : [[0, strlen($value)]];
    }

    /**
     * @return array{0: int, 1: int}|null
     */
    private static function firstImportant(string $sentence): ?array
    {
        $skip = '/^(a|an|the|and|or|of|to|in|on|for|with|from|by|as|at|is|are|be|this|that|into|over|its|their|can|will|should|every|each|one|our|we|create|launch|offer|introduce|build|start|develop|establish|use|add|help|make|keep|take|show|share)$/i';
        if (! preg_match_all('/\S+/u', $sentence, $matches, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        $start = -1;
        $end = -1;
        $taken = 0;
        foreach ($matches[0] as $match) {
            $word = preg_replace('/^[“"\'(\[]+|[”"\')\].,;:!?।]+$/u', '', $match[0]) ?? '';
            if ($word === '') {
                continue;
            }
            if ($taken === 0 && preg_match($skip, $word)) {
                continue;
            }
            if ($start < 0) {
                $start = (int) $match[1];
            }
            $end = (int) $match[1] + strlen($match[0]);
            $taken++;
            if ($taken >= 2) {
                break;
            }
        }

        return $start >= 0 ? [$start, $end] : null;
    }
}
