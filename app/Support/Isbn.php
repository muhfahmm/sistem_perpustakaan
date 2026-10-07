<?php

namespace App\Support;

class Isbn
{
    public static function normalize(?string $isbn): ?string
    {
        if ($isbn === null || trim($isbn) === '') {
            return null;
        }

        return strtoupper(preg_replace('/[\s-]/', '', trim($isbn)));
    }

    public static function isValid(?string $isbn): bool
    {
        $isbn = self::normalize($isbn);
        if ($isbn === null) {
            return false;
        }

        if (preg_match('/^\d{9}[\dX]$/', $isbn)) {
            $sum = 0;
            for ($index = 0; $index < 10; $index++) {
                $digit = $index === 9 && $isbn[$index] === 'X' ? 10 : (int) $isbn[$index];
                $sum += $digit * (10 - $index);
            }

            return $sum % 11 === 0;
        }

        if (preg_match('/^97[89]\d{10}$/', $isbn)) {
            $sum = 0;
            for ($index = 0; $index < 13; $index++) {
                $sum += (int) $isbn[$index] * ($index % 2 === 0 ? 1 : 3);
            }

            return $sum % 10 === 0;
        }

        return false;
    }
}
