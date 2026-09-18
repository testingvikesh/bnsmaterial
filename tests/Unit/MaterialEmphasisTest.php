<?php

namespace Tests\Unit;

use App\Support\MaterialEmphasis;
use PHPUnit\Framework\TestCase;

class MaterialEmphasisTest extends TestCase
{
    public function test_bolds_business_name_and_first_important_words(): void
    {
        $html = MaterialEmphasis::html(
            'Cadworld Infoways offers wires and cables for trusted local partners.',
            ['Cadworld Infoways', 'wires and cables']
        );

        $this->assertStringContainsString('<strong>Cadworld Infoways</strong>', $html);
        $this->assertStringContainsString('<strong>wires and cables</strong>', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }
}
