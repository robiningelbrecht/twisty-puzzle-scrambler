<?php

namespace Tests\Cube;

use PHPUnit\Framework\TestCase;
use RobinIngelbrecht\TwistyPuzzleScrambler\Cube\CubeScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Cube\Size;
use RobinIngelbrecht\TwistyPuzzleScrambler\InvalidScramble;
use Spatie\Snapshots\MatchesSnapshots;

class CubeScrambleTest extends TestCase
{
    use MatchesSnapshots;

    private string $snapshotName;

    /**
     * @dataProvider provideNotations()
     */
    public function testFromNotation(int $size, string $scramble): void
    {
        $scramble = CubeScramble::fromNotation($scramble, Size::fromInt($size));

        $this->snapshotName = (new \ReflectionClass($scramble))->getShortName();
        if (method_exists($scramble, 'getSize')) {
            $this->snapshotName .= $scramble->getSize().'x'.$scramble->getSize();
        }

        $this->assertMatchesJsonSnapshot(json_encode(
            $scramble
        ));
        $this->assertMatchesTextSnapshot($scramble->forHumans());
        $this->assertCount(count(explode(' ', (string) $scramble)), $scramble->getTurns());
    }

    /**
     * @dataProvider provideNotations()
     */
    public function testReverse(int $size, string $scramble): void
    {
        $scramble = CubeScramble::fromNotation($scramble, Size::fromInt($size));
        $this->assertEquals($scramble->reverse()->reverse(), $scramble);
    }

    public function testRandom(): void
    {
        $scramble = CubeScramble::random(21, Size::fromInt(7));
        $this->assertCount(21, explode(' ', (string) $scramble));
    }

    public function testItShouldThrowWhenEmptySizeForNotation(): void
    {
        $this->expectException(InvalidScramble::class);
        $this->expectExceptionMessage('Size is required');

        CubeScramble::fromNotation('L2');
    }

    public function testItShouldThrowWhenEmptySizeForRandom(): void
    {
        $this->expectException(InvalidScramble::class);
        $this->expectExceptionMessage('Size is required');

        CubeScramble::random(10);
    }

    public function testItShouldThrowOnInvalidTurn(): void
    {
        $this->expectException(InvalidScramble::class);
        $this->expectExceptionMessage('Invalid turn "V"');

        CubeScramble::fromNotation('V', Size::fromInt(3));
    }

    public function testItShouldThrowOnWhenNoOuterIndicatorButSlices(): void
    {
        $this->expectException(InvalidScramble::class);
        $this->expectExceptionMessage('Invalid turn "3L", cannot specify number of slices if outer block move indicator "w" is not present');

        CubeScramble::fromNotation('3L', Size::fromInt(7));
    }

    public function testItShouldThrowOnWhenSlicesIsTooLarge(): void
    {
        $this->expectException(InvalidScramble::class);
        $this->expectExceptionMessage('Invalid turn "3Lw", slice cannot be greater than 2');

        CubeScramble::fromNotation('3Lw', Size::fromInt(4));
    }

    protected function getSnapshotId(): string
    {
        return (new \ReflectionClass($this))->getShortName().'--'.
            $this->name().'--'.
            $this->snapshotName.'--'.
            $this->snapshotIncrementor;
    }

    public static function provideNotations(): array
    {
        return [
            [2, "F2 U' R U2 R U R' F' R"],
            [3, "R2 D B' D2 L' B' R' B2 U R2 B D2 L2 F' B' D2 R2 D2 F U2 R2"],
            [4, "R' B D2 L B2 R' D2 L' D2 L2 F2 D2 R B2 D' U' F R' B' R2 U' Rw2 F L2 U' Fw2 U2 F Uw2 B' D2 L2 D' Uw2 L B' Rw Fw2 Uw2 Rw B Fw D2 Uw B Fw2 L'"],
            [5, "Lw' F2 D' L Bw2 D' Rw Fw Bw R2 Uw Lw2 Dw' F Fw2 Dw' Lw2 Rw2 Fw2 D R2 D' Bw2 Rw2 Bw' L R2 U Dw' R Bw' B Rw Bw2 F Dw2 L R2 F2 Dw2 U2 Bw' Dw F2 Lw' B R' L' U' F2 Bw R Fw2 F2 D2 B2 R L2 Bw' F"],
            [6, "Rw L2 3Uw' L' F' Uw2 D2 Lw' 3Uw2 U2 F' 3Uw' 3Fw Bw R 3Fw' B' U2 Bw2 D Bw2 3Fw' Fw' L Uw B 3Fw' Uw D 3Rw2 B' Rw2 Uw2 3Rw' Fw' U' B2 Fw2 Dw Uw' Lw2 F2 Rw2 Uw U D' R L2 Fw Uw' 3Fw2 B' Uw' Bw Rw2 3Fw Uw F2 3Fw2 R Dw2 Uw' L' F R' Rw 3Fw2 F2 Bw R' Bw Rw' U Rw' 3Fw2 3Rw2 Fw R2 3Fw 3Uw'"],
            [7, "Lw' 3Rw2 3Fw Fw R Lw' 3Dw' Dw R 3Lw' D 3Dw' Dw2 L Uw2 3Lw 3Fw B' D2 Dw2 Lw B2 Fw2 U' B L' B2 Fw' Lw2 Bw F Rw' Lw U2 Dw' 3Bw B' Uw2 B' L' 3Rw2 U B' F2 R' Rw2 3Uw2 B R 3Dw' 3Uw2 3Lw2 3Fw' F2 Uw 3Uw L' Rw2 3Bw2 B 3Dw2 D' Bw2 3Bw' Uw2 3Bw' B' L 3Dw 3Lw' 3Fw R2 3Bw Fw' U' Uw' 3Uw2 F' Uw' 3Rw2 Rw Uw' Bw2 3Bw' R2 3Rw' 3Fw 3Bw2 3Uw' L Lw2 D2 Uw' U' Fw' F2 Lw2 L Uw Bw'"],
        ];
    }
}
