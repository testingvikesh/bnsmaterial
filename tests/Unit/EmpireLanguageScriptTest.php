<?php

namespace Tests\Unit;

use App\Support\MaterialEmpireVision;
use PHPUnit\Framework\TestCase;

class EmpireLanguageScriptTest extends TestCase
{
    public function test_hindi_instruction_uses_devanagari_not_gujarati_as_the_right_style(): void
    {
        $hi = MaterialEmpireVision::languageRewriteInstruction('hi');

        $this->assertStringContainsString('Hindi (Devanagari)', $hi);
        $this->assertStringContainsString('को Mumbai की trusted home-style meal brand बनाना', $hi);
        $this->assertStringContainsString('WRONG (Gujarati)', $hi);
    }

    public function test_rejects_gujarati_script_when_language_is_hindi(): void
    {
        $gujarati = [
            'visions' => [[
                'explanation' => 'Artiben’s Annapurna Hub ને મુંબઈની trusted home-style meal brand બનાવવી.',
                'innovation' => 'Annapurna Daily Meal Club શરૂ કરવો.',
                'example' => 'આસપાસનાં ઘરોને fixed weekly lunch આપવો.',
                'action' => 'ત્રીક meals plans બનાવો clear prices સાથે.',
                'benefit' => 'Regular orders monthly stability વધારશે.',
            ]],
        ];

        $this->assertFalse(MaterialEmpireVision::languagePackScriptIsValid('hi', $gujarati));
        $this->assertTrue(MaterialEmpireVision::languagePackScriptIsValid('gu', $gujarati));
    }

    public function test_accepts_hindi_devanagari_pack(): void
    {
        $hindi = [
            'visions' => [[
                'explanation' => 'Artiben’s Annapurna Hub को Mumbai की trusted home-style meal brand बनाना।',
                'innovation' => 'Annapurna Daily Meal Club शुरू करना।',
                'example' => 'आसपास के घरों को fixed weekly lunch और dinner plans देना।',
                'action' => 'तीन meal plans बनाओ clear prices के साथ।',
                'benefit' => 'Regular orders से monthly stability बढ़ेगी।',
            ]],
        ];

        $this->assertTrue(MaterialEmpireVision::languagePackScriptIsValid('hi', $hindi));
        $this->assertFalse(MaterialEmpireVision::languagePackScriptIsValid('gu', $hindi));
    }
}
