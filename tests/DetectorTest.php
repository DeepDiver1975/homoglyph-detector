<?php

use Homoglyph\Detector;
use PHPUnit\Framework\TestCase;

class DetectorTest extends TestCase
{
    /**
     * @dataProvider provider
     * @param string[] $targetWords
     */
    public function test(int $expectedCount, string $inputText, array $targetWords): void
    {
        $d = new Detector();
        $matches = $d->search($inputText, $targetWords);
        self::assertCount($expectedCount, $matches);
    }

    /**
     * @return Generator & iterable<string, array>
     */
    public function provider(): Generator
    {
        yield 'simple' => [
            1,
            '𝐍𝐞𝐭𝐉𝐨𝐛𝟏.𝐜𝐨𝐦',
            ['netjob1.com']
        ];

        yield 'multiple banned words' => [
            2,
            '𝐍𝐞𝐭𝐉𝐨𝐛𝟏.𝐜𝐨𝐦 and abc',
            ['netjob1.com', 'abc']
        ];

        yield 'soft hyphens' => [
            1,
            'no NOT join 𝐍­𝐞­𝐭­𝐉­𝐨­𝐛­𝟏­.𝐜­𝐨­𝐦',
            ['netjob1.com']
        ];

        yield 'multiple occurrences' => [
            2,
            'do NOT join netjob1.com - netjob1.com is a bad site',
            ['netjob1.com']
        ];

        yield 'no banned word contained' => [
            0,
            'lorem ipsum',
            ['google.com']
        ];
    }
}
