<?php

namespace Tests\Unit;

use App\Support\Isbn;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IsbnTest extends TestCase
{
    #[DataProvider('validIsbns')]
    public function test_it_accepts_valid_isbn_10_and_isbn_13(string $isbn): void
    {
        $this->assertTrue(Isbn::isValid($isbn));
    }

    public static function validIsbns(): array
    {
        return [
            'ISBN-10' => ['0-306-40615-2'],
            'ISBN-10 with X check digit' => ['0-8044-2957-X'],
            'ISBN-13' => ['978-0-306-40615-7'],
        ];
    }

    #[DataProvider('invalidIsbns')]
    public function test_it_rejects_invalid_or_incomplete_isbns(string $isbn): void
    {
        $this->assertFalse(Isbn::isValid($isbn));
    }

    public static function invalidIsbns(): array
    {
        return [
            'short value' => ['123456'],
            'bad ISBN-10 check digit' => ['0306406153'],
            'bad ISBN-13 check digit' => ['9780306406158'],
        ];
    }
}
