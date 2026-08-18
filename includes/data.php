<?php
/**
 * data.php
 *
 * DUMMY DATA LAYER — this file simulates what will eventually be a set of
 * MySQL queries. get_records() is the single function the rest of the app
 * calls to get the raw dataset. When the real database is ready, this
 * function's internals get replaced with a PDO query, but its return shape
 * (array of associative arrays with the same keys) should stay the same so
 * nothing else in the app needs to change.
 */

const COURSES = [
    'Anatomy and Physiology of the Human Body',
    'Disease Management',
    'Diabetes Fundamentals',
    'Pharmacovigilance and Reporting Adverse Events',
    'Travel Safely',
    'Interpreting and Presenting Clinical Trials',
    'Constructive Conversations - Giving and Receiving Feedback',
    'Negotiation Principles and Practices',
    'Selling - Balancing Science and Art',
    'Assertive Customer-centric Engagement in Healthcare Product Promotion and Sales',
    'From Sales Manager to Sales Team Leader',
];

const TEAMS = ['Diabetes Team', 'Dermatology Team', 'Immunology Team', 'Agency Team'];

/**
 * Current role, pulled (in the real Moodle integration) from the user
 * profile field of the same name. Used to drive the Role filter.
 */
const ROLES = [
    'Medical Representative',
    'Sales Manager',
    'Brand Manager',
    'Medical Science Liaison',
    'Other',
];

const NAMES = [
    'Bongani Zulu', 'Pieter van der Merwe', 'Sipho Dlamini', 'Thandi Nkosi', 'Annelie Botha',
    'Chantal September', 'Fatima Hendricks', 'Kobus Pretorius', 'Marlize du Plessis', 'Matthew Hudson',
    'Nomsa Mthembu', 'Priya Pillay', 'Rachel Ralphs', 'Tyler Maren', 'Wayne Carolus',
    'Yusuf Davids', 'Jolene Kirsten', 'Given Sithole', 'Werner Botha', 'Amahle Ngcobo',
    'Divan Steyn', 'Palesa Mahlangu', 'Ryan Abrahams', 'Zanele Khumalo',
];

const MONTHS = ['AUG', 'SEP', 'OCT', 'NOV', 'DEC', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN'];
const MONTH_YEARS = ['2025', '2025', '2025', '2025', '2025', '2026', '2026', '2026', '2026', '2026', '2026'];

/**
 * Per-course activity/assessment lists. Placeholder content matching the
 * new 11-course sample list (client's real Moodle activity names will
 * replace these once available) — Announcements and Course Evaluation are
 * deliberately left out of every course's list per the client's request;
 * Course Evaluation is still captured separately via the standardized
 * form on the Evaluation page, it's just not listed as an "activity" here.
 */
const COURSE_ACTIVITIES = [
    'Anatomy and Physiology of the Human Body' => [
        'BEFORE: Self assessment - Anatomy and Physiology',
        'Introduction to Human Body Systems',
        'The Cardiovascular System',
        'The Nervous System',
        'The Endocrine System',
        'Applying Anatomy Knowledge to Product Conversations',
        'AFTER: Self assessment - Anatomy and Physiology',
    ],
    'Disease Management' => [
        'BEFORE: Self assessment - Disease Management',
        'Understanding Disease Progression',
        'Treatment Pathways and Guidelines',
        'Managing Chronic Conditions',
        'Case Study: Disease Management in Practice',
        'AFTER: Self assessment - Disease Management',
    ],
    'Diabetes Fundamentals' => [
        'BEFORE: Self assessment - Diabetes Fundamentals',
        'Types and Causes of Diabetes',
        'Diagnosis and Monitoring',
        'Treatment Options and Adherence',
        'Diabetes and Lifestyle Management',
        'AFTER: Self assessment - Diabetes Fundamentals',
    ],
    'Pharmacovigilance and Reporting Adverse Events' => [
        'BEFORE: Self assessment - Pharmacovigilance',
        'Principles of Pharmacovigilance',
        'Identifying Adverse Events',
        'Reporting Procedures and Timelines',
        'Case Study: Adverse Event Reporting',
        'AFTER: Self assessment - Pharmacovigilance',
    ],
    'Travel Safely' => [
        'Travel Risk Awareness',
        'Health and Safety While Traveling',
        'Emergency Procedures on the Road',
        'Assessment: Travel Safely',
    ],
    'Interpreting and Presenting Clinical Trials' => [
        'BEFORE: Self assessment - Clinical Trials',
        'Understanding Trial Design and Methodology',
        'Interpreting Statistical Significance',
        'Presenting Clinical Data to HCPs',
        'Handling Questions on Clinical Evidence',
        'AFTER: Self assessment - Clinical Trials',
    ],
    'Constructive Conversations - Giving and Receiving Feedback' => [
        'BEFORE: Self assessment - Constructive Conversations',
        'Principles of Constructive Feedback',
        'Giving Feedback with Confidence',
        'Receiving and Acting on Feedback',
        'Practicing Difficult Conversations',
        'AFTER: Self assessment - Constructive Conversations',
    ],
    'Negotiation Principles and Practices' => [
        'BEFORE: Self assessment - Negotiation Principles',
        'Foundations of Negotiation',
        'Preparing for a Negotiation',
        'Negotiation Tactics and Techniques',
        'Reaching Win-Win Outcomes',
        'AFTER: Self assessment - Negotiation Principles',
    ],
    'Selling - Balancing Science and Art' => [
        'BEFORE: Self assessment - Balancing Science and Art',
        'The Science of Selling',
        'The Art of Building Rapport',
        'Combining Data with Storytelling',
        'Practical Application in the Field',
        'AFTER: Self assessment - Balancing Science and Art',
    ],
    'Assertive Customer-centric Engagement in Healthcare Product Promotion and Sales' => [
        'BEFORE: Self assessment - Assertive Customer-centric Engagement',
        'Understanding Customer Personalities',
        'Healthcare Practitioner Decision Drivers',
        'Strategic Questioning',
        'Assertive Product Discussions',
        'AFTER: Self assessment - Assertive Customer-centric Engagement',
    ],
    'From Sales Manager to Sales Team Leader' => [
        'BEFORE: Self assessment - Sales Team Leadership',
        'Transitioning from Manager to Leader',
        'Coaching and Developing Your Team',
        'Leading Through Change',
        'Building a High-Performing Sales Culture',
        'AFTER: Self assessment - Sales Team Leadership',
    ],
];

function get_course_activities(): array
{
    return COURSE_ACTIVITIES;
}

/**
 * Weighted random pick, e.g. weighted_pick(['Excellent','Good'], [0.7, 0.3])
 */
function weighted_pick(array $items, array $weights)
{
    $total = array_sum($weights);
    $r = mt_rand() / mt_getrandmax() * $total;
    $acc = 0;
    foreach ($items as $i => $item) {
        $acc += $weights[$i];
        if ($r <= $acc) {
            return $item;
        }
    }
    return end($items);
}

/**
 * Generates (and caches for the request) the dummy dataset.
 * Each record = one student's completion of one course.
 *
 * Fields:
 *   name, team, role, course, month_index (0-10, Aug25-Jun26),
 *   grade (0-100), activity_passed (bool),
 *   knowledge (Excellent|Good|Fair|Poor),
 *   confidence (Excellent|Good|Fair|Poor|Not Relevant),
 *   badge (bool)
 */
function get_records(): array
{
    static $records = null;
    if ($records !== null) {
        return $records;
    }

    mt_srand(42); // fixed seed so dummy data is stable across reloads

    $name_team = [];
    $name_role = [];
    foreach (NAMES as $i => $name) {
        $name_team[$name] = TEAMS[$i % count(TEAMS)];
        $name_role[$name] = ROLES[$i % count(ROLES)];
    }

    $records = [];
    foreach (NAMES as $name) {
        $num_courses = 2 + mt_rand(0, 2); // 2-4 courses (out of 11 total)
        $shuffled = COURSES;
        shuffle($shuffled);
        $selected = array_slice($shuffled, 0, $num_courses);

        foreach ($selected as $course) {
            $records[] = [
                'name'            => $name,
                'team'            => $name_team[$name],
                'role'            => $name_role[$name],
                'course'          => $course,
                'month_index'     => mt_rand(0, 10),
                'grade'           => mt_rand(72, 99),
                'activity_passed' => (mt_rand(1, 100) <= 92),
                'knowledge'       => weighted_pick(
                    ['Excellent', 'Good', 'Fair', 'Poor'],
                    [0.5, 0.3, 0.15, 0.05]
                ),
                'confidence'      => weighted_pick(
                    ['Excellent', 'Good', 'Fair', 'Poor', 'Not Relevant'],
                    [0.35, 0.3, 0.15, 0.1, 0.1]
                ),
                'badge'           => (mt_rand(1, 100) <= 35),
            ];
        }
    }

    return $records;
}

/**
 * One row per (student, course, activity) — an activity-level score for
 * every course a student took, using THAT COURSE'S OWN activity list
 * (see COURSE_ACTIVITIES above). Reuses get_records() as its base so
 * the same students/courses/teams/dates line up with the rest of the
 * dummy data, then fans each of those rows out across that course's
 * activities.
 */
function get_assessment_records(): array
{
    static $assessment_records = null;
    if ($assessment_records !== null) {
        return $assessment_records;
    }

    $base_records = get_records();
    $course_activities = get_course_activities();

    mt_srand(99); // different seed than get_records(), but still fixed/stable across reloads

    $assessment_records = [];
    foreach ($base_records as $r) {
        $activities = $course_activities[$r['course']] ?? [];
        foreach ($activities as $topic) {
            // Mostly high scores (80-100), occasional weaker topic (40-79),
            // matching the general pattern in the client's Excel demo.
            $score = (mt_rand(1, 100) <= 80)
                ? mt_rand(80, 100)
                : mt_rand(40, 79);

            $assessment_records[] = [
                'name'        => $r['name'],
                'team'        => $r['team'],
                'role'        => $r['role'],
                'course'      => $r['course'],
                'month_index' => $r['month_index'],
                'topic'       => $topic,
                'score'       => $score,
            ];
        }
    }

    return $assessment_records;
}

/**
 * Generic confidence statements used on the Confidence Insights page —
 * unlike Performance Detail's activities, these are NOT per-course; the
 * same 8 statements apply regardless of which course a record belongs to.
 */
const CONFIDENCE_STATEMENTS = [
    'Confidence in tailoring approach to HCPs & owning their concerns & needs',
    'Confidence in ability to listen actively & identify customer needs',
    'Confidence in using visual & behavioural cues to understand customer mindset',
    'Confidence in managing difficult or challenging conversations',
    'Confidence in asking powerful & relevant questions during customer meetings',
    'Confidence in ability to communicate clearly & effectively with HCP',
    'Confidence in ability to add value to practice',
    'Confidence in starting and leading conversations with HCPs',
];

/**
 * One row per (student, course, statement) — a Before/After confidence
 * score pair. Reuses get_records() as its base so the same students/
 * courses/teams/dates line up with the rest of the dummy data. "After"
 * is generally higher than "Before", matching the improvement narrative
 * from the client's Excel demo.
 */
function get_confidence_records(): array
{
    static $confidence_records = null;
    if ($confidence_records !== null) {
        return $confidence_records;
    }

    $base_records = get_records();

    mt_srand(77); // different seed than the other generators, still fixed/stable

    $confidence_records = [];
    foreach ($base_records as $r) {
        foreach (CONFIDENCE_STATEMENTS as $statement) {
            $before = mt_rand(55, 82);
            $after = min(100, $before + mt_rand(8, 28));

            $confidence_records[] = [
                'name'        => $r['name'],
                'team'        => $r['team'],
                'role'        => $r['role'],
                'course'      => $r['course'],
                'month_index' => $r['month_index'],
                'statement'   => $statement,
                'before'      => $before,
                'after'       => $after,
            ];
        }
    }

    return $confidence_records;
}

/**
 * One row per (student, course) — a single overall self-assessment
 * Before/After score pair (unlike Confidence Insights, there's no list
 * of statements here — just one score per course completion). Reuses
 * get_records() as its base so students/courses/teams/dates line up.
 */
function get_self_assessment_records(): array
{
    static $self_assessment_records = null;
    if ($self_assessment_records !== null) {
        return $self_assessment_records;
    }

    $base_records = get_records();

    mt_srand(55); // different seed than the other generators, still fixed/stable

    $self_assessment_records = [];
    foreach ($base_records as $r) {
        $before = mt_rand(60, 85);
        $after = min(100, $before + mt_rand(8, 25));

        $self_assessment_records[] = [
            'name'        => $r['name'],
            'team'        => $r['team'],
            'role'        => $r['role'],
            'course'      => $r['course'],
            'month_index' => $r['month_index'],
            'before'      => $before,
            'after'       => $after,
        ];
    }

    return $self_assessment_records;
}

/* ============ EVALUATION PAGE ============ */

/**
 * Course Evaluation: the now-standardized form every student fills in
 * after completing ANY course. Quantitative questions only — the
 * open-ended free-text fields from the real form aren't modeled here.
 */
const COURSE_EVAL_QUESTIONS = [
    'I have significantly increased my knowledge and understanding of the topics covered in this course.',
    'I am confident in my ability to apply the knowledge gained in this course to my role.',
    'This course combined clear content, interactive activities, and user-friendly design for a rewarding learning experience.',
    'I recommend this course to other Medical Representatives.',
];
const COURSE_EVAL_SCALE = ['Definitely Agree', 'Mostly Agree', 'Neither', 'Mostly Disagree', 'Definitely Disagree'];

/**
 * Workshop Evaluation: filled in by PARTICIPANTS, only for courses that
 * actually have an associated workshop (not every course does).
 */
const WORKSHOP_EVAL_QUESTIONS = [
    'The workshop content was relevant to my role and responsibilities and delivered at the appropriate level for my knowledge and experience.',
    'The session was effectively facilitated, with clear explanations, engaging delivery, and opportunities for active participation and discussion.',
    'The facilitator demonstrated expert-level knowledge on the topic.',
    'The workshop increased my confidence to apply the knowledge and skills learned immediately in my work.',
    'The workshop met my expectations and added value to my professional development.',
];

/**
 * Facilitator Workshop Report: filled in ONCE per workshop by the
 * facilitator (not once per participant), so there's a single answer per
 * question per course — not a distribution, which is why this is
 * rendered as a simple answer list rather than a donut chart.
 */
const FACILITATOR_REPORT_QUESTIONS = [
    'Prepared and structured the session with clear objectives and outcome-based design.',
    'Created a learner-centered, inclusive, psychologically safe environment that encouraged participation, critical thinking, and reflection.',
    'Demonstrated expert-level knowledge, professionalism, and adherence to ethical, promotional, and compliance standards.',
    'Integrated real-world examples, case studies, and practical exercises to support immediate application and actionable skill development.',
    'Monitored understanding, provided constructive feedback, and adapted delivery as needed to support participant learning outcomes.',
];

/**
 * Free-text section of the Facilitator Workshop Report (added per
 * client request) — unlike FACILITATOR_REPORT_QUESTIONS above, these are
 * open-ended narrative fields rather than a rating on WORKSHOP_SCALE.
 */
const FACILITATOR_REPORT_TEXT_FIELDS = [
    'What went well',
    'What can be improved',
    'Recommendations and next steps',
];

// Workshop Evaluation and the Facilitator Report share the same
// response scale in the client's forms.
const WORKSHOP_SCALE = ['Definitely agree', 'Moderately agree', 'Neither', 'Moderately disagree', 'Definitely disagree'];

/**
 * Which courses have an associated workshop. Dummy assumption for
 * testing purposes (real logic will come from actual scheduling data
 * later): the first 2 of the 11 courses have one, the rest don't, so
 * both the "has a workshop" and "no workshop" states are visible.
 */
function get_courses_with_workshop(): array
{
    return array_slice(COURSES, 0, 2);
}

function course_has_workshop(string $course): bool
{
    return in_array($course, get_courses_with_workshop(), true);
}

/**
 * One row per (student, course, question) — Course Evaluation is filled
 * in for every course completion, so this covers all of get_records().
 */
function get_course_evaluation_records(): array
{
    static $records = null;
    if ($records !== null) {
        return $records;
    }

    $base_records = get_records();
    mt_srand(123);

    $records = [];
    foreach ($base_records as $r) {
        foreach (COURSE_EVAL_QUESTIONS as $question) {
            $response = weighted_pick(COURSE_EVAL_SCALE, [0.45, 0.32, 0.13, 0.07, 0.03]);
            $records[] = [
                'name'        => $r['name'],
                'team'        => $r['team'],
                'role'        => $r['role'],
                'course'      => $r['course'],
                'month_index' => $r['month_index'],
                'question'    => $question,
                'response'    => $response,
            ];
        }
    }

    return $records;
}

/**
 * One row per (student, course, question) — but ONLY for students whose
 * course has a workshop (see course_has_workshop() above).
 */
function get_workshop_evaluation_records(): array
{
    static $records = null;
    if ($records !== null) {
        return $records;
    }

    $base_records = get_records();
    mt_srand(124);

    $records = [];
    foreach ($base_records as $r) {
        if (!course_has_workshop($r['course'])) {
            continue;
        }
        foreach (WORKSHOP_EVAL_QUESTIONS as $question) {
            $response = weighted_pick(WORKSHOP_SCALE, [0.4, 0.35, 0.14, 0.07, 0.04]);
            $records[] = [
                'name'        => $r['name'],
                'team'        => $r['team'],
                'role'        => $r['role'],
                'course'      => $r['course'],
                'month_index' => $r['month_index'],
                'question'    => $question,
                'response'    => $response,
            ];
        }
    }

    return $records;
}

/**
 * ONE report per workshop-having course (not per student). Returns
 * [course => ['ratings' => [question => answer, ...], 'text' => [field => answer, ...]], ...].
 * 'ratings' feeds the existing pill-style question list; 'text' is the
 * new free-text section (What went well / What can be improved /
 * Recommendations and next steps).
 */
function get_facilitator_reports(): array
{
    static $reports = null;
    if ($reports !== null) {
        return $reports;
    }

    mt_srand(125);

    // Small pool of canned narrative answers, cycled per course so the
    // demo shows variety rather than identical text everywhere.
    $went_well_pool = [
        'Participants engaged actively and asked thoughtful questions throughout the session.',
        'Strong participation in the practical exercises, with several participants sharing real examples from the field.',
        'The group grasped the core concepts quickly, which allowed more time for role-play practice.',
    ];
    $improve_pool = [
        'More time could be allocated for practical role-play exercises.',
        'A few participants would have benefited from a shorter theory section and more hands-on time.',
        'Room setup made small-group work a bit cramped — worth revisiting the venue layout next time.',
    ];
    $next_steps_pool = [
        'Schedule a refresher session in 3 months and pair participants with a mentor for ongoing practice.',
        'Share a short follow-up video recapping the key techniques as a refresher before the next cycle.',
        'Introduce a peer role-play buddy system so participants can keep practicing between formal sessions.',
    ];

    $reports = [];
    $i = 0;
    foreach (get_courses_with_workshop() as $course) {
        $ratings = [];
        foreach (FACILITATOR_REPORT_QUESTIONS as $question) {
            $ratings[$question] = weighted_pick(WORKSHOP_SCALE, [0.5, 0.3, 0.12, 0.06, 0.02]);
        }

        $text = [
            'What went well'                => $went_well_pool[$i % count($went_well_pool)],
            'What can be improved'          => $improve_pool[$i % count($improve_pool)],
            'Recommendations and next steps' => $next_steps_pool[$i % count($next_steps_pool)],
        ];

        $reports[$course] = [
            'ratings' => $ratings,
            'text'    => $text,
        ];
        $i++;
    }

    return $reports;
}
