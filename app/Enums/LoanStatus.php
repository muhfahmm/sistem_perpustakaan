<?php

namespace App\Enums;

enum LoanStatus: string
{
    case PENDING = 'pending';
    case BORROWED = 'borrowed';
    case RETURNED = 'returned';
    case OVERDUE = 'overdue';
    case REJECTED = 'rejected';
    case LOST = 'lost';

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PENDING => [self::BORROWED, self::REJECTED],
            self::BORROWED => [self::RETURNED, self::OVERDUE, self::LOST],
            self::OVERDUE => [self::RETURNED, self::LOST],
            self::RETURNED, self::REJECTED, self::LOST => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::RETURNED, self::REJECTED, self::LOST], true);
    }

    public function isActive(): bool
    {
        return in_array($this, [self::PENDING, self::BORROWED, self::OVERDUE], true);
    }
}
