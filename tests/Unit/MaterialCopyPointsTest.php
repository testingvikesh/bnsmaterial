<?php

namespace Tests\Unit;

use App\Support\MaterialCopyPoints;
use PHPUnit\Framework\TestCase;

class MaterialCopyPointsTest extends TestCase
{
    public function test_splits_every_sentence_and_labeled_value(): void
    {
        $intro = 'Aditi Bhandari Design is a contemporary fine jewellery studio based in Mumbai. It creates bespoke fine jewellery and design-led collections in silver and titanium, and offers jewellery design consultancy for other brands. The brand specializes in storytelling-led fine jewellery, where designs are rooted in meaning, intention, and refined aesthetics rather than trends. Storytelling First: Every piece begins with a personal, emotional, or conceptual narrative. Design Integrity: Clean lines, balance, proportion, and wearability are non-negotiable. Timelessness Over Trends: Designed for longevity, not seasons. Lightweight Luxury: Jewellery is designed to feel effortless to wear without losing presence. Collaborative Creation: Clients are viewed as partners in the design journey.';

        $points = MaterialCopyPoints::from($intro);

        $this->assertGreaterThanOrEqual(8, count($points));
        $this->assertSame('Aditi Bhandari Design is a contemporary fine jewellery studio based in Mumbai.', $points[0]);
        $this->assertStringContainsString('bespoke fine jewellery', $points[1]);
        $this->assertTrue(collect($points)->contains(fn ($point) => str_starts_with($point, 'Storytelling First:')));
        $this->assertTrue(collect($points)->contains(fn ($point) => str_starts_with($point, 'Lightweight Luxury:')));
    }

    public function test_splits_numbered_product_groups_into_points(): void
    {
        $products = 'For Personal Clients: 1. Bespoke Fine Jewellery: Made-to-order personalized jewellery designed around the client\'s story, occasion, and vision. 2. Design-Led Collections: Silver & Titanium collections inspired by stories, emotions, and nature. For Jewellery Brands: 1. Jewellery Collection Design: End-to-end development from concept and storytelling to technical development. 2. Design Consultancy & Retainerships: Ongoing support for brands to build or strengthen design direction. 3. Design Audits & Brand Alignment: Review collections and identify opportunities to align with brand identity. 4. Material & Trend Guidance: Research and guidance on materials, gemstones, and emerging trends.';

        $points = MaterialCopyPoints::from($products);

        $this->assertGreaterThanOrEqual(6, count($points));
        $this->assertSame('For Personal Clients:', $points[0]);
        $this->assertTrue(collect($points)->contains(fn ($point) => str_contains($point, 'Bespoke Fine Jewellery')));
        $this->assertTrue(collect($points)->contains(fn ($point) => str_contains($point, 'Design Consultancy')));
        $this->assertTrue(collect($points)->contains(fn ($point) => str_starts_with($point, 'For Jewellery Brands:')));
    }

    public function test_keeps_short_single_line_as_one_point(): void
    {
        $this->assertSame(
            ['Gold & Diamond Jewellery Retail Business'],
            MaterialCopyPoints::from('Gold & Diamond Jewellery Retail Business')
        );
    }

    public function test_does_not_split_common_abbreviations(): void
    {
        $points = MaterialCopyPoints::from('Dr. Mehta leads ABC Jewellery. The studio is in Mumbai.');

        $this->assertCount(2, $points);
        $this->assertSame('Dr. Mehta leads ABC Jewellery.', $points[0]);
    }
}
