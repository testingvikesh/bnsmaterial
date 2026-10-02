<?php

namespace App\Support;

class MaterialCompanyStructure
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
            'title' => 'Proprietorship vs Partnership vs LLP vs Private Limited vs Public Limited',
            'subtitle' => 'Complete Comparison for Students & Business Owners',
            'kicker' => 'BNS PVT LTD Session',
            'business_name' => $biz !== '' ? $biz : 'Your Business',
            'member_name' => $member,
            'category' => trim((string) ($facts['category'] ?? '')),
            'headers' => ['Proprietorship', 'Partnership', 'LLP', 'Private Limited', 'Public Limited'],
            'points' => self::points(),
            'entities' => self::entities(),
            'expenses' => self::expenses(),
            'owners' => self::owners(),
            'funding' => self::funding(),
            'stages' => self::stages(),
            'questions' => self::questions(),
            'formula' => 'Business Idea → Right Structure → Proper Accounts → Legal Compliance → Professional Management → Funding → Growth → Expansion → Business Empire',
            'disclaimer' => 'Final structure selection should be made after taking advice from the relevant CA/CS/lawyer based on the business\'s specific facts and current law.',
            'expense_note' => 'A bill alone does not automatically make an expense deductible. Business purpose, documentation, accounting treatment and applicable tax rules must be considered.',
            'stage_note' => 'This is not a compulsory sequence. A businessman should select a structure based on factors such as: Number of Owners + Liability + Capital Requirement + Investment Plans + Compliance Capacity + Growth Plans + Governance + Long-Term Vision.',
        ];
    }

    /**
     * @return list<array{no:int,point:string,cells:list<string>}>
     */
    private static function points(): array
    {
        $rows = [
            ['Basic Meaning', 'Business owned by one person', 'Business owned by 2 or more partners', 'Partnership with limited liability framework', 'Separate company owned through shares', 'Company with access to public capital markets, subject to applicable rules'],
            ['Owners', '1', '2 or more', '2 or more', 'Shareholders', 'Shareholders'],
            ['Main Decision Maker', 'Proprietor', 'Partners', 'Partners', 'Board/Directors', 'Board/Directors'],
            ['Ownership Proof', 'Business ownership', 'Partnership Deed', 'LLP Agreement', 'Shareholding', 'Shareholding'],
            ['Separate Legal Entity', 'Generally No', 'Generally No', 'Yes', 'Yes', 'Yes'],
            ['Liability', 'Generally Unlimited', 'Generally Unlimited, subject to law', 'Generally Limited', 'Generally Limited', 'Generally Limited'],
            ['Personal Asset Protection', 'Limited', 'Limited', 'Generally stronger', 'Generally stronger', 'Generally stronger'],
            ['Formation', 'Simple', 'Relatively simple', 'Formal registration', 'Formal incorporation', 'Formal incorporation + additional requirements'],
            ['Main Legal Document', 'Business/registration records', 'Partnership Deed', 'LLP Agreement', 'MOA & AOA', 'MOA, AOA & applicable requirements'],
            ['Number of Owners', '1', '2+', '2+', '1+', '7+ under applicable company law'],
            ['Profit', 'Owner\'s profit', 'Shared as per agreement', 'Shared as per LLP Agreement', 'Company profit', 'Company profit'],
            ['Drawings', 'Generally possible', 'Possible', 'As permitted by agreement', 'Not personal drawings', 'Not personal drawings'],
            ['Salary', 'Owner withdrawal is not normally salary', 'Possible subject to rules', 'Possible subject to rules', 'Director/employee remuneration possible', 'Director/employee remuneration possible'],
            ['Dividend', 'No', 'No', 'No', 'Yes, subject to applicable rules', 'Yes, subject to applicable rules'],
            ['Outside Investment', 'Difficult', 'Limited', 'Possible', 'Suitable for equity investment', 'Suitable for public capital markets, subject to requirements'],
            ['Angel Investment', 'Usually difficult', 'Usually difficult', 'Possible', 'Commonly suitable', 'Possible through applicable framework'],
            ['Venture Capital', 'Difficult', 'Difficult', 'Case-specific', 'Suitable', 'Possible, subject to applicable framework'],
            ['Bank Loan', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes'],
            ['Equity Shares', 'No', 'No', 'No company shares in the normal sense', 'Yes', 'Yes'],
            ['Share Transfer', 'Not applicable', 'Agreement-based', 'Agreement-based', 'Subject to company rules', 'Generally more transferable, subject to rules'],
            ['Continuity', 'Dependent on owner', 'Depends on partners/agreement', 'Better continuity', 'Perpetual succession', 'Perpetual succession'],
            ['Management', 'Simple', 'Partner-based', 'Partner-based', 'Board/professional management', 'Formal board management'],
            ['Compliance', 'Relatively lower', 'Moderate', 'Higher', 'Higher', 'Extensive'],
            ['Audit & Filing', 'Depends on applicable rules', 'Depends on applicable rules', 'Statutory requirements apply', 'Statutory requirements apply', 'Extensive statutory/regulatory requirements'],
            ['Accounting', 'Basic to Moderate', 'Moderate', 'Formal', 'Formal', 'Highly Formal'],
            ['Tax Treatment', 'Owner-level taxation', 'Firm-level taxation under applicable rules', 'LLP taxation', 'Company taxation', 'Company taxation'],
            ['Business Expenses', 'Allowed subject to applicable rules', 'Allowed subject to applicable rules', 'Allowed subject to applicable rules', 'Allowed subject to applicable rules', 'Allowed subject to applicable rules'],
            ['Personal Expenses', 'Not business expenses', 'Not business expenses', 'Not business expenses', 'Not company expenses', 'Not company expenses'],
            ['Asset Ownership', 'Owner/Business', 'Firm', 'LLP', 'Company', 'Company'],
            ['Asset Register', 'Recommended', 'Recommended', 'Important', 'Important', 'Essential'],
            ['Depreciation', 'Applicable assets/rules', 'Applicable assets/rules', 'Applicable', 'Applicable', 'Applicable'],
            ['Business Bank Account', 'Recommended', 'Current Account', 'Current Account', 'Current Account', 'Current Account'],
            ['Agreement Importance', 'Low', 'Very High', 'Very High', 'Very High', 'Very High'],
            ['Dispute Management', 'Owner decides', 'Partnership Agreement', 'LLP Agreement', 'MOA/AOA + governance framework', 'Extensive governance framework'],
            ['Exit', 'Owner-dependent', 'Agreement-based', 'Agreement-based', 'Share transfer/buyout mechanisms', 'Share transfer mechanisms'],
            ['Succession', 'More dependent on owner', 'Agreement-based', 'Better structured', 'Strong continuity', 'Strong continuity'],
            ['Professional Management', 'Limited', 'Possible', 'Possible', 'Highly suitable', 'Highly suitable'],
            ['MIS & KPI', 'Recommended', 'Recommended', 'Important', 'Very Important', 'Essential'],
            ['Internal Controls', 'Basic', 'Moderate', 'Important', 'Strong', 'Very Strong'],
            ['Corporate Governance', 'Limited', 'Limited', 'Moderate', 'Important', 'Extensive'],
            ['Investor Reporting', 'Usually not required', 'Limited', 'Possible', 'Important', 'Extensive'],
            ['Due Diligence', 'Basic', 'Moderate', 'Important', 'Very Important', 'Extensive'],
            ['Transparency', 'Relatively lower', 'Moderate', 'Higher', 'Higher', 'Very High'],
            ['Scalability', 'Small/Medium Business', 'Small/Medium Business', 'Medium/Growth Business', 'Growth/Scalable Business', 'Large Enterprises'],
            ['Suitable For', 'Individual entrepreneurs', 'Partner businesses', 'Partner-led growth businesses', 'Startups & scalable businesses', 'Large enterprises'],
            ['Brand Building', 'Possible', 'Possible', 'Strong', 'Strong', 'Strong'],
            ['Franchise Expansion', 'Possible', 'Possible', 'Possible', 'Suitable', 'Suitable'],
            ['Multiple Investors', 'Difficult', 'Difficult', 'Possible', 'Suitable', 'Suitable'],
            ['IPO Journey', 'Not directly suitable', 'Not directly suitable', 'Restructuring may be required', 'Possible route toward IPO, subject to eligibility', 'Public-company/listing framework'],
            ['Main Focus', 'Start & Operate', 'Operate with Partners', 'Protect & Grow', 'Scale & Raise Capital', 'Large-Scale Expansion & Public Capital'],
        ];

        $out = [];
        foreach ($rows as $i => $row) {
            $out[] = [
                'no' => $i + 1,
                'point' => $row[0],
                'cells' => array_slice($row, 1),
            ];
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function entities(): array
    {
        return [
            [
                'no' => '01',
                'code' => 'proprietorship',
                'title' => 'Proprietorship',
                'meaning' => 'A business owned and controlled by one individual.',
                'example' => 'Rahul Traders is owned and operated by Rahul.',
                'features' => [
                    'One owner',
                    'Simple management',
                    'Easy decision-making',
                    'Lower compliance compared with companies',
                    'Owner directly controls the business',
                    'Liability is generally unlimited',
                    'Suitable for many small businesses',
                ],
                'examples' => [
                    'Retail shop',
                    'Consultant',
                    'Freelancer',
                    'Small trading business',
                    'Local service business',
                ],
                'documents' => [],
                'suitable' => [],
            ],
            [
                'no' => '02',
                'code' => 'partnership',
                'title' => 'Partnership',
                'meaning' => 'A business operated by two or more partners under a partnership arrangement.',
                'example' => 'Rahul and Amit start RA Traders. Rahul invests ₹10 lakh and Amit invests ₹5 lakh. Their profit-sharing arrangement is recorded in the Partnership Deed.',
                'features' => [
                    'Two or more partners',
                    'Partnership Deed is important',
                    'Profit-sharing arrangement',
                    'Shared decision-making',
                    'Partner roles should be clearly defined',
                    'Dispute and exit clauses are important',
                ],
                'examples' => [],
                'documents' => [
                    'Partnership Deed',
                    'PAN',
                    'Bank account',
                    'Accounting records',
                    'Applicable registrations',
                ],
                'suitable' => [],
            ],
            [
                'no' => '03',
                'code' => 'llp',
                'title' => 'LLP — Limited Liability Partnership',
                'meaning' => 'An LLP combines elements of a partnership structure with limited liability and separate legal entity status.',
                'example' => 'Rahul and Amit create RA Consulting LLP. They remain partners, but the LLP is a separate legal entity.',
                'features' => [
                    'Two or more partners',
                    'Separate legal entity',
                    'Limited liability framework',
                    'LLP Agreement',
                    'Flexible partner arrangements',
                    'Better structure for professional/growth-oriented partnerships',
                    'Formal compliance requirements',
                ],
                'examples' => [],
                'documents' => [],
                'suitable' => [
                    'Professional firms',
                    'Consultants',
                    'Family businesses',
                    'Service businesses',
                    'Partner-led businesses',
                ],
            ],
            [
                'no' => '04',
                'code' => 'pvt',
                'title' => 'Private Limited Company',
                'meaning' => 'A Private Limited Company is a separate legal entity whose ownership is represented through shares.',
                'example' => 'ABC Education Private Limited. Founder A → 60% shares. Founder B → 40% shares. The company operates separately from its shareholders.',
                'features' => [
                    'Separate legal entity',
                    'Limited liability framework',
                    'Shareholding structure',
                    'Directors/Board',
                    'Formal accounting',
                    'Statutory compliance',
                    'Suitable for scaling',
                    'Suitable for equity investment',
                ],
                'examples' => [],
                'documents' => [],
                'suitable' => [
                    'Startups',
                    'Technology businesses',
                    'Education businesses',
                    'Manufacturing',
                    'Scalable businesses',
                    'Businesses seeking investors',
                ],
            ],
            [
                'no' => '05',
                'code' => 'public',
                'title' => 'Public Limited Company',
                'meaning' => 'A Public Limited Company is a company structured to meet the requirements applicable to public companies and, where eligible, access public capital markets.',
                'example' => 'A large company grows significantly, meets applicable regulatory requirements and may eventually pursue a stock-market listing.',
                'features' => [
                    'Shareholders',
                    'Board of Directors',
                    'Strong corporate governance',
                    'Extensive compliance',
                    'Greater transparency',
                    'Investor reporting',
                    'Public-market framework where applicable',
                ],
                'examples' => [],
                'documents' => [],
                'suitable' => [
                    'Large enterprises',
                    'Businesses requiring substantial capital',
                    'Businesses considering public-market funding',
                    'Large-scale expansion',
                ],
            ],
        ];
    }

    /**
     * @return array{headers:list<string>,rows:list<array{item:string,cells:list<string>}>}
     */
    private static function expenses(): array
    {
        $yes = ['yes', 'yes', 'yes', 'yes', 'yes'];
        $no = ['no', 'no', 'no', 'no', 'no'];

        return [
            'headers' => ['Expense', 'Proprietorship', 'Partnership', 'LLP', 'Pvt Ltd', 'Public Ltd'],
            'rows' => [
                ['item' => 'Office Rent', 'cells' => $yes],
                ['item' => 'Employee Salary', 'cells' => $yes],
                ['item' => 'Advertising', 'cells' => $yes],
                ['item' => 'Website', 'cells' => $yes],
                ['item' => 'Software', 'cells' => $yes],
                ['item' => 'AI Tools', 'cells' => $yes],
                ['item' => 'Internet', 'cells' => $yes],
                ['item' => 'Professional Fees', 'cells' => $yes],
                ['item' => 'Business Travel', 'cells' => $yes],
                ['item' => 'Business Hotel', 'cells' => $yes],
                ['item' => 'Computer/Laptop', 'cells' => $yes],
                ['item' => 'Machinery', 'cells' => $yes],
                ['item' => 'Personal Shopping', 'cells' => $no],
                ['item' => 'Family Personal Expenses', 'cells' => $no],
            ],
        ];
    }

    /**
     * @return array{headers:list<string>,rows:list<array{point:string,cells:list<string>}>}
     */
    private static function owners(): array
    {
        return [
            'headers' => ['Point', 'Proprietor', 'Partner', 'LLP Partner', 'Pvt Ltd Shareholder', 'Public Ltd Shareholder'],
            'rows' => [
                ['point' => 'Ownership', 'cells' => ['Direct', 'Partnership interest', 'LLP interest', 'Shares', 'Shares']],
                ['point' => 'Control', 'cells' => ['Direct', 'Shared', 'Shared', 'Through voting/shareholding & governance', 'Through voting/shareholding & governance']],
                ['point' => 'Profit', 'cells' => ['Owner\'s business profit', 'Share as agreed', 'Share as agreed', 'Company profit; dividends subject to rules', 'Company profit; dividends subject to rules']],
                ['point' => 'Capital', 'cells' => ['Owner capital', 'Partner capital', 'Partner contribution', 'Share capital', 'Share capital']],
                ['point' => 'Exit', 'cells' => ['Business transfer/closure etc.', 'Agreement-based', 'Agreement-based', 'Share transfer/buyout', 'Share transfer subject to rules']],
                ['point' => 'Liability', 'cells' => ['Generally unlimited', 'Generally unlimited, subject to law', 'Generally limited', 'Generally limited', 'Generally limited']],
            ],
        ];
    }

    /**
     * @return array{headers:list<string>,rows:list<array{source:string,cells:list<string>}>}
     */
    private static function funding(): array
    {
        return [
            'headers' => ['Funding Source', 'Proprietorship', 'Partnership', 'LLP', 'Pvt Ltd', 'Public Ltd'],
            'rows' => [
                ['source' => 'Owner Capital', 'cells' => ['yes', '—', '—', '—', '—']],
                ['source' => 'Partner Capital', 'cells' => ['—', 'yes', 'yes', '—', '—']],
                ['source' => 'Bank Loan', 'cells' => ['yes', 'yes', 'yes', 'yes', 'yes']],
                ['source' => 'Angel Investment', 'cells' => ['Difficult', 'Difficult', 'Possible', 'Suitable', 'Possible']],
                ['source' => 'Venture Capital', 'cells' => ['Difficult', 'Difficult', 'Case-specific', 'Suitable', 'Possible']],
                ['source' => 'Equity Investment', 'cells' => ['no', 'no', 'Different framework', 'yes', 'yes']],
                ['source' => 'Public Capital', 'cells' => ['no', 'no', 'no', 'No as a private company', 'Applicable public-market framework']],
                ['source' => 'IPO', 'cells' => ['no', 'no', 'Not directly', 'Potential route, subject to eligibility', 'Applicable framework']],
            ],
        ];
    }

    /**
     * @return list<array{no:int,situation:string,structure:string}>
     */
    private static function stages(): array
    {
        return [
            ['no' => 1, 'situation' => 'One person starts business', 'structure' => 'Proprietorship'],
            ['no' => 2, 'situation' => 'Two or more people join', 'structure' => 'Partnership'],
            ['no' => 3, 'situation' => 'Partners want a separate legal entity and limited-liability framework', 'structure' => 'LLP'],
            ['no' => 4, 'situation' => 'Business wants equity investment and scalable corporate structure', 'structure' => 'Private Limited'],
            ['no' => 5, 'situation' => 'Large company seeks public capital markets', 'structure' => 'Public Limited / IPO Journey'],
        ];
    }

    /**
     * @return list<string>
     */
    private static function questions(): array
    {
        return [
            'How many owners will the business have?',
            'How much capital is required?',
            'What level of business risk exists?',
            'Do we need limited liability?',
            'Will we need outside investors?',
            'Do we want to issue equity?',
            'How much compliance can we manage?',
            'Do we need professional management?',
            'What is our 5-year growth plan?',
            'Do we have a long-term public-market/IPO vision?',
        ];
    }
}
