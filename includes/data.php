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
    'Assertiveness - Presence, Confidence and Embracing Objections',
    'Product 1 - From Product Expert to Trusted Partner',
    'Product 2 - From Product Expert to Trusted Partner',
    'Assertive Customer-centric Engagement',
];

const TEAMS = ['Diabetes Team', 'Dermatology Team', 'Immunology Team', 'Agency Team'];

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
 * Per-course activity/assessment lists, from the client's updated
 * courses_assessments.csv. Unlike the first version of this file (where
 * every course shared the same 39 items), each course now has its own
 * genuinely distinct list. The CSV's own course headers and some activity
 * item text used real product names ("Erleada", "Imbruvica") — all of
 * those have been replaced with the generic "Product 1" / "Product 2"
 * naming (in the order they appeared in the CSV) throughout, at every
 * level, not just the course title.
 */
const COURSE_ACTIVITIES = [
    'Assertiveness - Presence, Confidence and Embracing Objections' => [
        '1 of 3: Assertive greetings',
        '1 of 3: The Objection Opportunity',
        '2 of 3: Assertive language',
        '2 of 3: The Objection Opportunity',
        '3 of 3:  Assertive product discussion',
        '3 of 3: The Objection Opportunity',
        'AFTER: Self assessment - Mastering challenging conversations',
        'Announcements',
        'BEFORE: Self assessment - Mastering challenging conversations',
        'Communication - How You Say It',
        'Course Evaluation',
        'Insight into Assertiveness',
        'The Assertive Medical Representative - Adding Value.',
        'The Power of Presence',
    ],
    'Product 1 - From Product Expert to Trusted Partner' => [
        'AFTER: Self assessment: From Product Expert to Trusted Partner',
        'Announcements',
        'Assertive Product Discussions',
        'BEFORE: Self assessment: From Product Expert to Trusted Partner',
        'Clinical Study Excellence - Product 1',
        'Course Evaluation',
        'Product 1 - applied product knowledge',
        'Product 1 - Overcoming Competitor Objections',
        'Simplify and communicate complex clinical concepts',
        'The Right Patient',
    ],
    'Product 2 - From Product Expert to Trusted Partner' => [
        'AFTER: Self assessment: From Product Expert to Trusted Partner',
        'Announcements',
        'Assertive Product Discussions',
        'BEFORE: Self assessment: From Product Expert to Trusted Partner',
        'Clinical study excellence - Product 2',
        'Course Evaluation',
        'Product 2 - applied product knowledge',
        'Product 2 - Overcoming Competitor Objections',
        'Simplify and communicate complex clinical concepts',
        'The Right Patient',
    ],
    'Assertive Customer-centric Engagement' => [
        'Assessment: Assertive Customer-centric Engagement',
        'HCP Emotional Decision Drivers',
        'Healthcare Practitioner Decision Drivers',
        'Listen with Intent',
        'Observation during HCP Engagement',
        'Psychological factors driving decisions',
        'Strategic Questioning',
        'Understanding Customer Personalities',
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
 *   name, team, course, month_index (0-10, Aug25-Jun26),
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
    foreach (NAMES as $i => $name) {
        $name_team[$name] = TEAMS[$i % count(TEAMS)];
    }

    $records = [];
    foreach (NAMES as $name) {
        $num_courses = 2 + mt_rand(0, 2); // 2-4 courses (out of 4 total)
        $shuffled = COURSES;
        shuffle($shuffled);
        $selected = array_slice($shuffled, 0, $num_courses);

        foreach ($selected as $course) {
            $records[] = [
                'name'            => $name,
                'team'            => $name_team[$name],
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

// Workshop Evaluation and the Facilitator Report share the same
// response scale in the client's forms.
const WORKSHOP_SCALE = ['Definitely agree', 'Moderately agree', 'Neither', 'Moderately disagree', 'Definitely disagree'];

/**
 * Which courses have an associated workshop. Dummy assumption for
 * testing purposes (real logic will come from actual scheduling data
 * later): the first 2 of the 4 courses have one, the other 2 don't, so
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
 * [course => [question => answer, ...], ...].
 */
function get_facilitator_reports(): array
{
    static $reports = null;
    if ($reports !== null) {
        return $reports;
    }

    mt_srand(125);

    $reports = [];
    foreach (get_courses_with_workshop() as $course) {
        $answers = [];
        foreach (FACILITATOR_REPORT_QUESTIONS as $question) {
            $answers[$question] = weighted_pick(WORKSHOP_SCALE, [0.5, 0.3, 0.12, 0.06, 0.02]);
        }
        $reports[$course] = $answers;
    }

    return $reports;
}
