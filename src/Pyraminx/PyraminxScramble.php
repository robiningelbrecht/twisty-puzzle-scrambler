<?php

namespace RobinIngelbrecht\TwistyPuzzleScrambler\Pyraminx;

use RobinIngelbrecht\TwistyPuzzleScrambler\InvalidScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Scramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Turn\SimpleTurnType;
use RobinIngelbrecht\TwistyPuzzleScrambler\Turn\Turn;

class PyraminxScramble extends Scramble
{
    private const REGEX = "/^(?<move>[UuRrLlBb])?(?<turnType>\\')?$/";

    public static function random(int $scrambleSize): Scramble
    {
        $turns = [];
        $previousMove = null;

        for ($i = 0; $i < $scrambleSize; ++$i) {
            do {
                $newMove = Move::random();
            } while ($previousMove && $previousMove === $newMove);

            $turnType = SimpleTurnType::random();

            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                $newMove->value.$turnType->getModifier(),
                $newMove,
                $turnType,
                1
            );

            $previousMove = $newMove;
        }

        $wideMoves = Move::wideMoves();
        shuffle($wideMoves);
        for ($i = 0; $i < rand(1, 4); ++$i) {
            $move = $wideMoves[$i];
            $turnType = SimpleTurnType::random();
            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                $move->value.$turnType->getModifier(),
                $move,
                $turnType,
                2
            );
        }

        return new self(...$turns);
    }

    public static function fromNotation(string $notation): Scramble
    {
        $turns = [];
        foreach (explode(' ', $notation) as $turn) {
            if (!preg_match(self::REGEX, $turn, $matches)) {
                throw new InvalidScramble(sprintf('Invalid turn "%s"', $turn));
            }

            $move = $matches['move'];

            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                $turn,
                Move::from($move),
                SimpleTurnType::getByTurnByModifier($matches['turnType'] ?? ''),
                $move === strtolower($move) ? 2 : 1,
            );
        }

        return new self(...$turns);
    }
}
