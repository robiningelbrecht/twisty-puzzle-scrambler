<?php

namespace RobinIngelbrecht\TwistyPuzzleScrambler\Turn;

use RobinIngelbrecht\TwistyPuzzleScrambler\InvalidScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Turn\TurnType as TurnTypeBase;

enum SimpleTurnType: string implements TurnTypeBase
{
    case CLOCKWISE = 'clockwise';
    case COUNTER_CLOCKWISE = 'counterClockwise';

    public function getOpposite(): self
    {
        return match ($this) {
            self::CLOCKWISE => self::COUNTER_CLOCKWISE,
            self::COUNTER_CLOCKWISE => self::CLOCKWISE,
        };
    }

    public function getModifier(): string
    {
        return match ($this) {
            self::CLOCKWISE => '',
            self::COUNTER_CLOCKWISE => "'",
        };
    }

    public function forHumans(): ?string
    {
        return match ($this) {
            self::CLOCKWISE => 'clockwise',
            self::COUNTER_CLOCKWISE => 'counterclockwise',
        };
    }

    public static function random(): self
    {
        $turnTypes = self::cases();

        return $turnTypes[array_rand($turnTypes)];
    }

    public static function getByTurnByModifier(string $modifier): self
    {
        return match ($modifier) {
            '' => self::CLOCKWISE,
            "'" => self::COUNTER_CLOCKWISE,
            default => throw new InvalidScramble(sprintf('Invalid turnAbbreviation "%s"', $modifier)),
        };
    }
}
