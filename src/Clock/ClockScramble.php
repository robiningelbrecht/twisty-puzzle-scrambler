<?php

namespace RobinIngelbrecht\TwistyPuzzleScrambler\Clock;

use RobinIngelbrecht\TwistyPuzzleScrambler\InvalidScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Scramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Turn\Turn;

class ClockScramble extends Scramble
{
    private const REGEX_FULL_NOTATION = "/^UR\d[+-] DR\d[+-] DL\d[+-] UL\d[+-] U\d[+-] R\d[+-] D\d[+-] L\d[+-] ALL\d[+-] y2 U\d[+-] R\d[+-] D\d[+-] L\d[+-] ALL\d[+-]( UR| DR| DL| UL){1,4}$/";
    private const REGEX_TURN = "/^(?<move>[a-zA-Z]{1,3})(?<turnType>[\d][+-]|[\d])?$/";

    public static function random(int $scrambleSize): Scramble
    {
        $moves = [
            Move::UR, Move::DR, Move::DL, Move::UL, Move::U, Move::R, Move::D,
            Move::L, Move::ALL, Move::y, Move::U, Move::R, Move::D, Move::L, Move::ALL,
        ];

        $turns = [];
        foreach ($moves as $move) {
            $turnType = Move::y === $move ? TurnType::DOUBLE : TurnType::random();
            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                $move->value.$turnType->value,
                $move,
                $turnType,
                1,
                new ForHumans(),
            );
        }

        $endMoves = Move::endMoves();
        shuffle($endMoves);
        for ($i = 0; $i < rand(1, 4); ++$i) {
            $move = $endMoves[$i];
            $turnType = TurnType::NONE;
            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                $move->value.$turnType->getModifier(),
                $move,
                $turnType,
                1,
                new ForHumans(),
            );
        }

        return new self(...$turns);
    }

    public static function fromNotation(string $notation): Scramble
    {
        $turns = [];
        if (!preg_match(self::REGEX_FULL_NOTATION, $notation, $matches)) {
            throw new InvalidScramble(sprintf('Invalid notation "%s"', $notation));
        }

        foreach (explode(' ', $notation) as $turn) {
            if (!preg_match(self::REGEX_TURN, $turn, $matches)) {
                throw new InvalidScramble(sprintf('Invalid turn "%s"', $turn));
            }

            $move = $matches['move'];

            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                $turn,
                Move::from($move),
                TurnType::getByTurnByModifier($matches['turnType'] ?? ''),
                1,
                new ForHumans(),
            );
        }

        return new self(...$turns);
    }
}