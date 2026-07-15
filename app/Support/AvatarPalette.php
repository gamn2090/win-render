<?php

namespace App\Support;

/**
 * Deterministic (not re-randomized on every request) initials + brand-color
 * pairing for profile avatar fallbacks, so the same person always gets the
 * same color instead of it flickering between page loads.
 */
class AvatarPalette
{
    private const COLORS = [
        ['bg' => '#6432C8', 'fg' => '#FFFFFF'], // win-purple
        ['bg' => '#FB962F', 'fg' => '#FFFFFF'], // win-orange
        ['bg' => '#F85705', 'fg' => '#FFFFFF'], // win-red
        ['bg' => '#5A7EFF', 'fg' => '#FFFFFF'], // win-blue
        ['bg' => '#EABDA8', 'fg' => '#231F20'], // win-peach
        ['bg' => '#D5C6E7', 'fg' => '#231F20'], // win-lavender
    ];

    /**
     * @return array{0: string, 1: string} [background, foreground]
     */
    public static function colorFor(string $seed): array
    {
        $index = abs(crc32($seed)) % count(self::COLORS);
        $pair = self::COLORS[$index];

        return [$pair['bg'], $pair['fg']];
    }

    public static function initials(string $name): string
    {
        $parts = array_values(array_filter(explode(' ', trim($name))));
        $letters = array_map(
            fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)),
            array_slice($parts, 0, 2)
        );

        return $letters ? implode('', $letters) : '?';
    }

    /**
     * Computes the right initials for any messageable model (User/Vendor)
     * using the same branching `<x-avatar>` already relies on, so callers
     * that only need the text (e.g. a chat bubble avatar) don't have to
     * duplicate the couple-vs-vendor logic themselves.
     */
    public static function initialsFor(object $model): string
    {
        if ($model instanceof \App\Models\User) {
            return self::coupleInitials($model->first_name ?? '', $model->fiance_first_name ?? '');
        }

        return self::initials(trim(($model->first_name ?? '') . ' ' . ($model->last_name ?? '')));
    }

    /**
     * Couples show as "first initial & fiancé's first initial", e.g.
     * "Andrea" + "Gustavo" => "A&G". Falls back to a single initial when
     * there's no fiancé name on file yet.
     */
    public static function coupleInitials(string $firstName, string $fianceFirstName): string
    {
        $first = trim($firstName);
        $fiance = trim($fianceFirstName);

        if ($first === '' && $fiance === '') {
            return '?';
        }

        if ($fiance === '') {
            return mb_strtoupper(mb_substr($first, 0, 1));
        }

        if ($first === '') {
            return mb_strtoupper(mb_substr($fiance, 0, 1));
        }

        return mb_strtoupper(mb_substr($first, 0, 1)) . '&' . mb_strtoupper(mb_substr($fiance, 0, 1));
    }
}
