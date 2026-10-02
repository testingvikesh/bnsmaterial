<?php

namespace App\Support;

class MaterialCompanyStructurePart2
{
    /**
     * @param  array<string, string>  $facts
     * @return array<string, mixed>
     */
    public static function for(array $facts = []): array
    {
        $biz = trim((string) ($facts['business_name'] ?? ''));
        $member = trim((string) ($facts['member_name'] ?? ''));

        return [
            'part' => 2,
            'title' => 'Business Structure Master Comparison',
            'subtitle' => 'PVT LTD Session (Part 2) · Points 101–200 for Students & Business Owners',
            'kicker' => 'BNS PVT LTD Session · Part 2',
            'business_name' => $biz !== '' ? $biz : 'Your Business',
            'member_name' => $member,
            'category' => trim((string) ($facts['category'] ?? '')),
            'headers' => ['Proprietorship', 'Partnership', 'LLP', 'Pvt Ltd', 'Public Ltd'],
            'master' => self::numbered(101, self::masterRows()),
            'finance' => self::numbered(151, self::financeRows()),
            'legal' => self::numbered(171, self::legalRows()),
            'management' => self::numbered(181, self::managementRows()),
            'growth' => self::numbered(191, self::growthRows()),
            'decisions' => self::decisions(),
            'formula' => 'Business Idea → Ownership → Liability → Capital → Funding → Accounting → Compliance → Management → Growth → Investor → Corporate Structure → Capital Market',
            'disclaimer' => 'There is no fixed rule that “Pvt Ltd is always better” or “LLP is always better.” The suitable structure depends on the business owners, risk profile, funding plan, scale, ownership requirements and compliance needs.',
        ];
    }

    /**
     * @param  list<list<string>>  $rows
     * @return list<array{no:int,point:string,cells:list<string>}>
     */
    private static function numbered(int $start, array $rows): array
    {
        $out = [];
        foreach ($rows as $i => $row) {
            $out[] = [
                'no' => $start + $i,
                'point' => $row[0],
                'cells' => array_map([self::class, 'cell'], array_slice($row, 1)),
            ];
        }

        return $out;
    }

    private static function cell(string $value): string
    {
        $raw = trim($value);
        if ($raw === '✅') {
            return 'yes';
        }
        if ($raw === '❌') {
            return 'no';
        }

        return $raw;
    }

    /**
     * @return list<list<string>>
     */
    private static function masterRows(): array
    {
        return [
            ['Business Start', 'Easy', 'Easy', 'Formal', 'Formal', 'Most Formal'],
            ['Minimum Owners', '1', '2', '2', 'Generally 2 members', 'Generally 7 members'],
            ['Ownership', 'Proprietor', 'Partners', 'Partners', 'Shareholders', 'Shareholders'],
            ['Separate Legal Entity', '❌', 'Generally ❌', '✅', '✅', '✅'],
            ['Liability', 'Generally Unlimited', 'Generally Unlimited', 'Limited framework', 'Limited', 'Limited'],
            ['Main Document', 'Business registrations', 'Partnership Deed', 'LLP Agreement', 'MOA + AOA', 'MOA + AOA'],
            ['Management', 'Proprietor', 'Partners', 'Partners', 'Directors', 'Board'],
            ['Ownership Proof', 'Proprietorship records', 'Deed', 'LLP Agreement', 'Share certificates/register', 'Shareholding records'],
            ['Profit Sharing', 'Proprietor', 'As per Agreement', 'As per Agreement', 'Shareholding/dividend framework', 'Shareholding/dividend framework'],
            ['Personal Drawing', 'Possible', 'Partner drawings', 'As per Agreement/rules', 'No direct personal drawing', 'No direct personal drawing'],
            ['Salary to Owner', 'Proprietor salary concept differs', 'Partner remuneration rules', 'Partner remuneration rules', 'Director salary possible', 'Director salary possible'],
            ['Dividend', '❌', '❌', '❌', '✅', '✅'],
            ['Share Capital', '❌', '❌', '❌', '✅', '✅'],
            ['Equity Shares', '❌', '❌', '❌', '✅', '✅'],
            ['Investor Entry', 'Limited', 'Agreement-based', 'Possible', 'Strong framework', 'Strong framework'],
            ['VC Funding', 'Difficult', 'Difficult', 'Case-specific', 'Common route', 'Capital-market route'],
            ['Angel Investor', 'Possible but less structured', 'Possible', 'Possible', 'Structured', 'Possible'],
            ['Bank Loan', '✅', '✅', '✅', '✅', '✅'],
            ['Public Fund Raising', '❌', '❌', '❌', '❌ as public offering vehicle', 'Public-market framework'],
            ['IPO', '❌', '❌', '❌', 'Future possibility through applicable conversion/listing route', 'Possible subject to applicable rules'],
            ['Ownership Transfer', 'Difficult', 'Deed-based', 'Agreement-based', 'Share transfer mechanism', 'More formal'],
            ['New Investor Add', 'Personal arrangement', 'New partner', 'Partner admission', 'New shares/transfer', 'Share issuance/transfer'],
            ['Investor Dilution', 'Not applicable as shares', 'Partnership ratio changes', 'Partnership interest changes', 'Possible', 'Possible'],
            ['Valuation', 'Useful', 'Useful', 'Useful', 'Very important', 'Very important'],
            ['Fundraising Documentation', 'Basic', 'Agreement', 'Agreement + records', 'Extensive', 'Extensive'],
            ['Due Diligence', 'Basic/Case-Specific', 'Important', 'Important', 'Important', 'Extensive'],
            ['Board of Directors', '❌', '❌', '❌', '✅', '✅'],
            ['Shareholders Meeting', '❌', '❌', '❌', 'Applicable', 'Applicable'],
            ['Board Meeting', '❌', '❌', '❌', 'Applicable', 'Applicable'],
            ['Corporate Governance', 'Low', 'Low', 'Moderate', 'Higher', 'High'],
            ['ROC Compliance', '❌ as company', '❌', '✅', '✅', '✅'],
            ['MCA Compliance', '❌ as company/LLP', '❌', '✅', '✅', '✅'],
            ['Annual Return', 'Business/tax filings', 'Firm filings as applicable', 'LLP filing', 'Company filing', 'Company filing'],
            ['Financial Statements', 'Accounts', 'Accounts', 'Accounts', 'Financial statements', 'Financial statements'],
            ['Audit', 'Applicable based on law/turnover etc.', 'Applicable conditions', 'Applicable conditions', 'Applicable conditions', 'Applicable conditions'],
            ['Income Tax Return', 'Individual/business framework', 'Firm framework', 'Firm/LLP framework', 'Company framework', 'Company framework'],
            ['Tax Treatment', 'Individual/business rules', 'Firm rules', 'Firm/LLP rules', 'Company rules', 'Company rules'],
            ['Accounting Requirement', 'Basic → formal as scale grows', 'Formal', 'Formal', 'Strong formal system', 'Strong formal system'],
            ['Bookkeeping', 'Important', 'Important', 'Important', 'Essential', 'Essential'],
            ['Invoice System', '✅', '✅', '✅', '✅', '✅'],
            ['Current Account', 'Recommended/needed as applicable', 'Recommended', 'Recommended', 'Essential practical requirement', 'Essential practical requirement'],
            ['GST', 'Applicable if conditions are met', 'Applicable if conditions are met', 'Applicable if conditions are met', 'Applicable if conditions are met', 'Applicable if conditions are met'],
            ['TDS', 'Applicable conditions', 'Applicable conditions', 'Applicable conditions', 'Applicable conditions', 'Applicable conditions'],
            ['Payroll', 'As applicable', 'As applicable', 'As applicable', 'Stronger formal system', 'Stronger formal system'],
            ['Employee Agreements', 'Recommended', 'Recommended', 'Recommended', 'Important', 'Important'],
            ['Vendor Agreement', 'Recommended', 'Recommended', 'Recommended', 'Important', 'Important'],
            ['Customer Agreement', 'Business-dependent', 'Business-dependent', 'Business-dependent', 'Important', 'Important'],
            ['Founder Agreement', 'Not applicable in same form', 'Partnership Deed', 'LLP Agreement', 'Shareholders/Founder arrangements', 'Shareholders arrangements'],
            ['Exit Mechanism', 'Business sale/closure', 'Deed provisions', 'LLP Agreement', 'Share transfer/buyout', 'Share transfer/buyout'],
            ['Succession', 'Proprietor/family/legal planning', 'Agreement', 'Agreement', 'Shareholding/directorship planning', 'Shareholding/directorship planning'],
        ];
    }

    /**
     * @return list<list<string>>
     */
    private static function financeRows(): array
    {
        return [
            ['Revenue Tracking', '✅', '✅', '✅', '✅', '✅'],
            ['Expense Tracking', '✅', '✅', '✅', '✅', '✅'],
            ['Profit & Loss', '✅', '✅', '✅', '✅', '✅'],
            ['Balance Sheet', 'Useful/Applicable', '✅', '✅', '✅', '✅'],
            ['Cash Flow', 'Important', 'Important', 'Important', 'Essential', 'Essential'],
            ['Budget', 'Recommended', 'Recommended', 'Recommended', 'Essential', 'Essential'],
            ['MIS', 'Useful', 'Useful', 'Useful', 'Important', 'Essential'],
            ['Working Capital', 'Important', 'Important', 'Important', 'Essential', 'Essential'],
            ['Break-even', 'Useful', 'Useful', 'Useful', 'Important', 'Important'],
            ['Receivables Tracking', '✅', '✅', '✅', '✅', '✅'],
            ['Payables Tracking', '✅', '✅', '✅', '✅', '✅'],
            ['Asset Register', 'Recommended', 'Recommended', 'Recommended', 'Important', 'Important'],
            ['Depreciation', 'Applicable assets', 'Applicable', 'Applicable', 'Applicable', 'Applicable'],
            ['Business Expense vs Personal Expense', 'Keep separate', 'Keep separate', 'Keep separate', 'Strict separation', 'Strict separation'],
            ['Audit Trail', 'Basic → formal', 'Formal', 'Formal', 'Strong', 'Strong'],
            ['Financial Controls', 'Basic', 'Moderate', 'Moderate', 'Strong', 'Strong'],
            ['Payment Approval', 'Owner', 'Partners', 'Partners', 'Management/Board system', 'Management/Board system'],
            ['Expense Approval Matrix', 'Optional', 'Useful', 'Useful', 'Important', 'Essential'],
            ['Internal Audit', 'Usually not mandatory for every business', 'Case-specific', 'Case-specific', 'Applicable depending on law/scale', 'More formal'],
            ['Financial Reporting', 'Owner-focused', 'Partner-focused', 'Partner-focused', 'Stakeholder-focused', 'Investor/public-stakeholder focused'],
        ];
    }

    /**
     * @return list<list<string>>
     */
    private static function legalRows(): array
    {
        return [
            ['Partnership Deed', '❌', '✅', '❌', '❌', '❌'],
            ['LLP Agreement', '❌', '❌', '✅', '❌', '❌'],
            ['MOA', '❌', '❌', '❌', '✅', '✅'],
            ['AOA', '❌', '❌', '❌', '✅', '✅'],
            ['Shareholders Agreement', '❌', '❌', '❌/case-specific', 'Useful', 'Useful'],
            ['Board Resolutions', '❌', '❌', 'Limited', '✅', '✅'],
            ['Share Certificate', '❌', '❌', '❌', '✅', '✅'],
            ['Statutory Registers', 'Limited', 'Limited', 'Applicable', 'Extensive', 'Extensive'],
            ['Compliance Calendar', 'Basic', 'Moderate', 'Important', 'Essential', 'Essential'],
            ['Legal Due Diligence', 'Basic', 'Useful', 'Important', 'Important', 'Extensive'],
        ];
    }

    /**
     * @return list<list<string>>
     */
    private static function managementRows(): array
    {
        return [
            ['Owner Dependency', 'High', 'Medium', 'Medium', 'Can reduce', 'Can reduce significantly'],
            ['Professional CEO', 'Optional', 'Optional', 'Optional', 'Possible', 'Common'],
            ['CFO', 'Optional', 'Optional', 'Optional', 'Useful/possible', 'Important'],
            ['COO', 'Optional', 'Optional', 'Optional', 'Useful', 'Useful'],
            ['Department Structure', 'Basic', 'Moderate', 'Moderate', 'Formal', 'Highly formal'],
            ['SOP System', 'Recommended', 'Recommended', 'Important', 'Essential', 'Essential'],
            ['KPI System', 'Useful', 'Useful', 'Useful', 'Important', 'Essential'],
            ['MIS Reporting', 'Useful', 'Useful', 'Useful', 'Important', 'Essential'],
            ['Internal Control', 'Basic', 'Moderate', 'Moderate', 'Strong', 'Strong'],
            ['Corporate Governance', 'Limited', 'Limited', 'Moderate', 'Important', 'High'],
        ];
    }

    /**
     * @return list<list<string>>
     */
    private static function growthRows(): array
    {
        return [
            ['Local Business', '✅', '✅', '✅', '✅', '—'],
            ['Multiple Branches', '✅', '✅', '✅', '✅', '✅'],
            ['National Expansion', 'Possible', 'Possible', 'Possible', 'Strong framework', 'Strong'],
            ['International Expansion', 'Possible', 'Possible', 'Possible', 'Strong framework', 'Strong'],
            ['Franchise Model', 'Possible', 'Possible', 'Possible', 'Strong', 'Strong'],
            ['Strategic Investor', 'Limited', 'Possible', 'Possible', 'Strong', 'Strong'],
            ['Institutional Investor', 'Difficult', 'Difficult', 'Case-specific', 'Possible', 'Possible'],
            ['Large Corporate Contracts', 'Possible', 'Possible', 'Possible', 'Strong credibility framework', 'Strong'],
            ['Brand Valuation', 'Possible', 'Possible', 'Possible', 'Strong', 'Strong'],
            ['Business Sale', 'Possible', 'Possible', 'Possible', 'Structured', 'Structured'],
        ];
    }

    /**
     * @return list<array{situation:string,structures:string}>
     */
    private static function decisions(): array
    {
        return [
            ['situation' => 'I am starting a business alone', 'structures' => 'Proprietorship'],
            ['situation' => 'I and my friend are starting a business', 'structures' => 'Partnership / LLP / Pvt Ltd'],
            ['situation' => 'I want lower personal liability exposure', 'structures' => 'LLP / Pvt Ltd / Public Ltd framework'],
            ['situation' => 'I want a simple setup', 'structures' => 'Proprietorship / Partnership'],
            ['situation' => 'I want a professional structure', 'structures' => 'LLP / Pvt Ltd'],
            ['situation' => 'I want to bring investors', 'structures' => 'Pvt Ltd generally relevant framework'],
            ['situation' => 'I want equity shares for funding', 'structures' => 'Pvt Ltd / Public Ltd'],
            ['situation' => 'I want to make the business corporate', 'structures' => 'Pvt Ltd'],
            ['situation' => 'I want to work with larger investors', 'structures' => 'Pvt Ltd / Public Ltd'],
            ['situation' => 'I want to raise capital from the public', 'structures' => 'Public-company / capital-market framework'],
            ['situation' => 'I want to move towards an IPO', 'structures' => 'Applicable public-company/listing framework'],
            ['situation' => 'I want to professionalize a family business', 'structures' => 'LLP / Pvt Ltd — depending on circumstances'],
            ['situation' => 'I want multiple shareholders', 'structures' => 'Company structure'],
            ['situation' => 'I want Board governance', 'structures' => 'Company structure'],
            ['situation' => 'I want a large-scale institutional structure', 'structures' => 'Company structure'],
        ];
    }
}
