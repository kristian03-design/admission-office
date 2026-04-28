<?php

namespace App\Console\Commands;

use App\Models\Program;

class SeedProgramDetails
{
    public static function run()
    {
        $data = [
            1 => [
                'careers' => ['Software Engineer', 'Web Developer', 'Network Administrator', 'Database Manager', 'Cybersecurity Analyst', 'IT Project Manager'],
                'core' => [['name' => 'Computer Programming', 'icon' => 'code'], ['name' => 'Web Development', 'icon' => 'globe'], ['name' => 'Database Systems', 'icon' => 'database'], ['name' => 'Networking', 'icon' => 'network'], ['name' => 'Information Security', 'icon' => 'lock'], ['name' => 'Mobile App Dev', 'icon' => 'smartphone']],
                'desc' => 'The Bachelor of Science in Information Technology (BSIT) program prepares students to design, implement, and manage complex information systems. Our curriculum focuses on current industry trends and emerging technologies.'
            ],
            2 => [
                'careers' => ['Marketing Manager', 'Digital Marketing Specialist', 'Social Media Manager', 'Market Research Analyst', 'Brand Manager', 'Public Relations Manager'],
                'core' => [['name' => 'Consumer Behavior', 'icon' => 'users'], ['name' => 'Branding', 'icon' => 'star'], ['name' => 'Digital Marketing', 'icon' => 'smartphone'], ['name' => 'Sales Management', 'icon' => 'trending-up'], ['name' => 'Public Relations', 'icon' => 'megaphone'], ['name' => 'Market Research', 'icon' => 'search']],
                'desc' => 'Prepare for the dynamic world of business with a focus on market trends, consumer psychology, and digital strategy.'
            ],
            3 => [
                'careers' => ['Financial Analyst', 'Financial Planner', 'Budget Analyst', 'Treasury Manager', 'Investment Analyst', 'Credit Analyst'],
                'core' => [['name' => 'Corporate Finance', 'icon' => 'building'], ['name' => 'Investment Analysis', 'icon' => 'pie-chart'], ['name' => 'Risk Management', 'icon' => 'shield'], ['name' => 'Banking', 'icon' => 'landmark'], ['name' => 'Financial Planning', 'icon' => 'calendar'], ['name' => 'Portfolio Management', 'icon' => 'briefcase']],
                'desc' => 'Master the art of wealth management and financial decision-making for corporations and individuals.'
            ],
            4 => [
                'careers' => ['Human Resource Manager', 'Employee Relations Specialist', 'Recruitment & Talent Acquisition', 'Training and Development Officer', 'Compensation and Benefits Analyst', 'HR Consultant'],
                'core' => [['name' => 'Recruitment & Selection', 'icon' => 'user-plus'], ['name' => 'Labor Relations', 'icon' => 'scale'], ['name' => 'Compensation Management', 'icon' => 'wallet'], ['name' => 'Strategic HR', 'icon' => 'trophy'], ['name' => 'Organizational Development', 'icon' => 'network'], ['name' => 'Performance Management', 'icon' => 'bar-chart']],
                'desc' => 'The BS Business Administration Major in Human Resource Management is designed to produce globally competitive professionals equipped with technical expertise and professional integrity. Our curriculum is constantly reviewed and updated to meet the evolving demands of the industry.'
            ],
            5 => [
                'careers' => ['Small Business Owner', 'Business Development Manager', 'Startup Founder', 'Project Manager', 'Operations Manager', 'Corporate Innovation Consultant'],
                'core' => [['name' => 'Venture Capital', 'icon' => 'rocket'], ['name' => 'Business Planning', 'icon' => 'map'], ['name' => 'Innovation', 'icon' => 'lightbulb'], ['name' => 'Small Business Operations', 'icon' => 'home'], ['name' => 'E-commerce', 'icon' => 'shopping-cart'], ['name' => 'Strategic Management', 'icon' => 'target']],
                'desc' => 'Build your own future by learning how to identify opportunities and turn them into successful business ventures.'
            ],
            6 => [
                'careers' => ['Economist', 'Economic Consultant', 'Data Analyst', 'Financial Risk Analyst', 'Policy Analyst', 'Market Researcher'],
                'core' => [['name' => 'Microeconomics', 'icon' => 'microscope'], ['name' => 'Macroeconomics', 'icon' => 'globe'], ['name' => 'Econometrics', 'icon' => 'line-chart'], ['name' => 'Public Policy', 'icon' => 'landmark'], ['name' => 'International Trade', 'icon' => 'truck'], ['name' => 'Development Economics', 'icon' => 'trending-up']],
                'desc' => 'Analyze the complexities of resource allocation and global market trends to influence policy and business growth.'
            ],
            7 => [
                'careers' => ['Internal Auditor', 'Compliance Officer', 'Risk Manager', 'IT Auditor', 'Quality Assurance Auditor', 'Internal Control Specialist'],
                'core' => [['name' => 'Audit Principles', 'icon' => 'clipboard-check'], ['name' => 'Risk Assessment', 'icon' => 'shield-alert'], ['name' => 'Fraud Examination', 'icon' => 'search'], ['name' => 'Corporate Governance', 'icon' => 'building'], ['name' => 'Compliance', 'icon' => 'check-circle'], ['name' => 'Internal Controls', 'icon' => 'settings']],
                'desc' => 'Ensure organizational integrity and efficiency through rigorous auditing standards and risk management strategies.'
            ],
            8 => [
                'careers' => ['AIS Analyst', 'Accounting Systems Consultant', 'IT Auditor', 'Software Implementation Specialist', 'Financial Systems Analyst'],
                'core' => [['name' => 'Enterprise Resource Planning', 'icon' => 'database'], ['name' => 'Database Management', 'icon' => 'server'], ['name' => 'AIS Security', 'icon' => 'lock'], ['name' => 'Systems Analysis', 'icon' => 'monitor'], ['name' => 'Financial Modeling', 'icon' => 'bar-chart'], ['name' => 'Business Intelligence', 'icon' => 'eye']],
                'desc' => 'The bridge between accounting and IT, preparing you to manage modern financial information systems.'
            ],
            9 => [
                'careers' => ['Certified Public Accountant (CPA)', 'Staff Accountant', 'Tax Consultant', 'Forensic Accountant', 'Auditor', 'Controller'],
                'core' => [['name' => 'Financial Accounting', 'icon' => 'file-text'], ['name' => 'Cost Accounting', 'icon' => 'calculator'], ['name' => 'Auditing & Assurance', 'icon' => 'shield-check'], ['name' => 'Taxation', 'icon' => 'receipt'], ['name' => 'Business Law', 'icon' => 'scale'], ['name' => 'Management Science', 'icon' => 'brain']],
                'desc' => 'A rigorous program designed to produce ethical and highly skilled CPAs ready for global professional practice.'
            ],
            10 => [
                'careers' => ['Management Accountant', 'Cost Accountant', 'Budget Director', 'FP&A Manager', 'Accounting Manager'],
                'core' => [['name' => 'Cost Management', 'icon' => 'dollar-sign'], ['name' => 'Performance Evaluation', 'icon' => 'bar-chart'], ['name' => 'Strategic Cost', 'icon' => 'target'], ['name' => 'Budgeting', 'icon' => 'calendar'], ['name' => 'Financial Analysis', 'icon' => 'trending-up'], ['name' => 'Controllership', 'icon' => 'command']],
                'desc' => 'Focus on internal decision support, cost efficiency, and strategic financial planning for organizations.'
            ],
            11 => [
                'careers' => ['Teacher/Professor', 'Archivist', 'Museum Curator', 'Historical Consultant', 'Researcher', 'Journalist'],
                'core' => [['name' => 'World History', 'icon' => 'globe'], ['name' => 'Local History', 'icon' => 'map-pin'], ['name' => 'Archival Studies', 'icon' => 'archive'], ['name' => 'Research Methods', 'icon' => 'search'], ['name' => 'Historiography', 'icon' => 'book'], ['name' => 'Heritage Management', 'icon' => 'landmark']],
                'desc' => 'Explore the past to understand the present, developing critical analytical and research skills for various sectors.'
            ],
            12 => [
                'careers' => ['Actuary', 'Statistician', 'Data Scientist', 'Operations Research Analyst', 'Cryptographer', 'Quantitative Analyst'],
                'core' => [['name' => 'Calculus', 'icon' => 'divide'], ['name' => 'Linear Algebra', 'icon' => 'grid'], ['name' => 'Probability & Statistics', 'icon' => 'bar-chart'], ['name' => 'Mathematical Modeling', 'icon' => 'activity'], ['name' => 'Discrete Math', 'icon' => 'box'], ['name' => 'Abstract Algebra', 'icon' => 'puzzle-piece']],
                'desc' => 'Master quantitative analysis and abstract reasoning to solve complex problems in technology, finance, and science.'
            ],
            13 => [
                'careers' => ['Elementary School Teacher', 'Curriculum Developer', 'Educational Consultant', 'School Administrator'],
                'core' => [['name' => 'Child Development', 'icon' => 'baby'], ['name' => 'Teaching Methods', 'icon' => 'presentation'], ['name' => 'Literacy & Numeracy', 'icon' => 'book-a'], ['name' => 'Classroom Management', 'icon' => 'users'], ['name' => 'Special Education', 'icon' => 'heart'], ['name' => 'Educational Tech', 'icon' => 'monitor']],
                'desc' => 'Shape the future by molding young minds through innovative teaching and holistic developmental approaches.'
            ],
            14 => [
                'careers' => ['Secondary School English Teacher', 'ESL Instructor', 'Content Writer/Editor', 'Corporate Trainer', 'Communication Specialist'],
                'core' => [['name' => 'Linguistics', 'icon' => 'languages'], ['name' => 'Literature', 'icon' => 'book-open'], ['name' => 'Creative Writing', 'icon' => 'pen-tool'], ['name' => 'Speech & Oral Comm', 'icon' => 'mic'], ['name' => 'English Grammar', 'icon' => 'spell-check'], ['name' => 'Curriculum Design', 'icon' => 'layout']],
                'desc' => 'Excel in communication and pedagogy, specializing in English language education for secondary levels.'
            ],
            15 => [
                'careers' => ['Hotel General Manager', 'Restaurant Manager', 'Front Office Manager', 'Event Planner', 'Food and Beverage Manager'],
                'core' => [['name' => 'Front Office Ops', 'icon' => 'door-open'], ['name' => 'Food & Beverage', 'icon' => 'coffee'], ['name' => 'Event Management', 'icon' => 'calendar-heart'], ['name' => 'Tourism Marketing', 'icon' => 'plane'], ['name' => 'Customer Service', 'icon' => 'smile'], ['name' => 'Quality Control', 'icon' => 'check-badge']],
                'desc' => 'Step into the global world of hospitality with practical training in hotel operations, culinary arts, and event management.'
            ],
            16 => [
                'careers' => ['Travel Agency Manager', 'Tour Guide', 'Flight Attendant', 'Tour Operator', 'Destination Marketing Manager', 'Cruise Ship Operations', 'Eco-tourism Specialist'],
                'core' => [['name' => 'Travel Management', 'icon' => 'suitcase'], ['name' => 'Tour Guiding', 'icon' => 'map-pin'], ['name' => 'Destination Marketing', 'icon' => 'globe'], ['name' => 'Airline Ops', 'icon' => 'plane'], ['name' => 'Sustainable Tourism', 'icon' => 'leaf'], ['name' => 'Leisure & Recreation', 'icon' => 'sun']],
                'desc' => 'Lead the travel industry by promoting sustainable tourism and managing world-class travel experiences.'
            ]
        ];

        foreach ($data as $id => $info) {
            $p = Program::find($id);
            if ($p) {
                $p->career_opportunities = $info['careers'];
                $p->core_areas = $info['core'];
                $p->description = $info['desc'];
                $p->save();
            }
        }
    }
}
