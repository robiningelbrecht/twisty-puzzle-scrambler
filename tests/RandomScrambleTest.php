<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RobinIngelbrecht\TwistyPuzzleScrambler\Clock\ClockScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Cube\CubeScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Cube\Size;
use RobinIngelbrecht\TwistyPuzzleScrambler\Megaminx\MegaminxScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Pyraminx\PyraminxScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\RandomScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Skewb\SkewbScramble;
use RobinIngelbrecht\TwistyPuzzleScrambler\Sq1\Sq1Scramble;
use Spatie\Snapshots\MatchesSnapshots;

class RandomScrambleTest extends TestCase
{
    use MatchesSnapshots;

    public function testCubeFactory(): void
    {
        $scramble = RandomScramble::twoByTwo();
        $this->assertEquals(
            $scramble,
            CubeScramble::fromNotation($scramble, Size::fromInt(2)),
        );
        $this->assertCount(9, explode(' ', (string) $scramble));

        $scramble = RandomScramble::threeByThree();
        $this->assertEquals(
            $scramble,
            CubeScramble::fromNotation($scramble, Size::fromInt(3)),
        );
        $this->assertCount(20, explode(' ', (string) $scramble));

        $scramble = RandomScramble::fourByFour();
        $this->assertEquals(
            $scramble,
            CubeScramble::fromNotation($scramble, Size::fromInt(4)),
        );
        $this->assertCount(44, explode(' ', (string) $scramble));

        $scramble = RandomScramble::fiveByFive();
        $this->assertEquals(
            $scramble,
            CubeScramble::fromNotation($scramble, Size::fromInt(5)),
        );
        $this->assertCount(60, explode(' ', (string) $scramble));

        $scramble = RandomScramble::sixBySix();
        $this->assertEquals(
            $scramble,
            CubeScramble::fromNotation($scramble, Size::fromInt(6)),
        );
        $this->assertCount(80, explode(' ', (string) $scramble));

        $scramble = RandomScramble::sevenBySeven();
        $this->assertEquals(
            $scramble,
            CubeScramble::fromNotation($scramble, Size::fromInt(7)),
        );
        $this->assertCount(100, explode(' ', (string) $scramble));
    }

    public function testPyraminxFactory(): void
    {
        $scramble = RandomScramble::pyraminx();
        $this->assertEquals(
            $scramble,
            PyraminxScramble::fromNotation($scramble),
        );
        $scrambleSize = count(explode(' ', (string) $scramble));
        $this->assertTrue($scrambleSize > 8 && $scrambleSize < 13);
    }

    public function testSkewbFactory(): void
    {
        $scramble = RandomScramble::skewb();
        $this->assertEquals(
            $scramble,
            SkewbScramble::fromNotation($scramble),
        );
        $this->assertCount(9, explode(' ', (string) $scramble));
    }

    public function testMegaminxFactory(): void
    {
        $scramble = RandomScramble::megaminx();
        $this->assertEquals(
            $scramble,
            MegaminxScramble::fromNotation($scramble),
        );
        $this->assertCount(77, explode(' ', (string) $scramble));
    }

    public function testClockFactory(): void
    {
        $scramble = RandomScramble::clock();
        $this->assertEquals(
            $scramble,
            ClockScramble::fromNotation($scramble),
        );
        $scrambleSize = count(explode(' ', (string) $scramble));
        $this->assertTrue($scrambleSize > 15 && $scrambleSize < 20);
    }

    public function testSq1Factory(): void
    {
        $scramble = RandomScramble::sq1();
        $this->assertEquals(
            $scramble,
            Sq1Scramble::fromNotation($scramble),
        );
        $this->assertCount(13, explode(' ', (string) $scramble));
    }
}
