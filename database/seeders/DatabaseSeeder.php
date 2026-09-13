<?php

namespace Database\Seeders;

use App\Models\AiContentDraft;
use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\AssessmentOption;
use App\Models\AssessmentQuestion;
use App\Models\AuditLog;
use App\Models\Badge;
use App\Models\Certificate;
use App\Models\Competency;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\LearningResource;
use App\Models\Lesson;
use App\Models\PracticalAssessment;
use App\Models\PracticalNode;
use App\Models\PracticalOption;
use App\Models\TraineeProfile;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Admin User
        $admin = User::updateOrCreate(
            ['role' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => 'admin.capacity.connect.lms@gmail.com',
                'password' => Hash::make('Admin@26075'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 2. Core Trainer User & Profile
        $trainer = User::firstOrCreate(
            ['email' => 'trainer@capacityconnect.gov'],
            [
                'name' => 'Dr. Rajesh Sharma',
                'password' => Hash::make('password123'),
                'role' => 'trainer',
                'status' => 'active',
            ]
        );

        TrainerProfile::updateOrCreate(
            ['user_id' => $trainer->id],
            [
                'qualification' => 'Ph.D. in Hydro-meteorology, Certified Disaster Mitigation Specialist',
                'experience' => '15 Years',
                'expertise' => 'Cyclone Forecasting, Flood Modeling, Emergency Logistics',
                'department' => 'Disaster Management',
                'bio' => 'Senior Specialist leading national capacity building in hydrometeorological disasters.',
                'subjects' => 'Cyclone Forecasting, Disaster Response',
            ]
        );

        // 3. Core Trainee User & Profile
        $trainee = User::firstOrCreate(
            ['email' => 'trainee@capacityconnect.gov'],
            [
                'name' => 'Anita Desai',
                'password' => Hash::make('password123'),
                'role' => 'trainee',
                'status' => 'active',
                'points' => 350,
                'level' => 3,
                'learning_streak' => 5,
            ]
        );

        TraineeProfile::updateOrCreate(
            ['user_id' => $trainee->id],
            [
                'phone' => '+91 9876543210',
                'qualifications' => 'B.E. Civil Engineering, Emergency First Responder',
                'work_experience' => '5 Years',
                'designation' => 'District Rescue Coordinator',
                'department' => 'Field Operations',
                'interests' => 'Flood Mitigation, Coastal Surge Management',
                'skills' => 'Search and Rescue, GIS Mapping, Emergency Command',
            ]
        );

        // 4. Competencies
        $compFlood = Competency::firstOrCreate(['name' => 'Flood Risk Management'], ['description' => 'Proficiency in flood hazard modeling, inundation mapping, and shelter management.', 'type' => 'technical']);
        $compCyclone = Competency::firstOrCreate(['name' => 'Cyclone Operational Response'], ['description' => 'Expertise in evacuation protocols, coastal warning systems, and relief logistics.', 'type' => 'core']);
        $compLogistics = Competency::firstOrCreate(['name' => 'Emergency Resource Logistics'], ['description' => 'Resource deployment, emergency fuel rationing, and medical supply chain containment.', 'type' => 'technical']);

        $trainee->competencies()->syncWithoutDetaching([
            $compFlood->id => ['current_level' => 2, 'required_level' => 4],
            $compCyclone->id => ['current_level' => 3, 'required_level' => 4],
            $compLogistics->id => ['current_level' => 1, 'required_level' => 3],
        ]);

        // 5. Course Categories
        $catDisaster = CourseCategory::firstOrCreate(['name' => 'Disaster Management'], ['slug' => 'disaster-management', 'description' => 'Emergency preparedness and rapid response training.']);
        $catLogistics = CourseCategory::firstOrCreate(['name' => 'Logistics & Supplies'], ['slug' => 'logistics-supplies', 'description' => 'Relief chain and inventory distribution.']);

        // 6. Course, Modules & Lessons
        $course1 = Course::firstOrCreate(
            ['title' => 'Category 4 Super Cyclone Response & Relief Operations'],
            [
                'course_code' => 'CC-CRS-101',
                'description' => 'Comprehensive operational course covering coastal warning systems, rapid evacuation protocols, shelter safety, and post-storm damage assessment.',
                'category_id' => $catDisaster->id,
                'trainer_id' => $trainer->id,
                'difficulty' => 'intermediate',
                'duration' => 6,
                'publish_status' => 'published',
                'status' => 'approved',
            ]
        );

        $course1->competencies()->syncWithoutDetaching([$compCyclone->id => ['level' => 4]]);

        $module1 = CourseModule::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'Module 1: Storm Warning & Rapid Evacuation Protocols'],
            ['order' => 1]
        );

        $lesson1 = Lesson::firstOrCreate(
            ['course_module_id' => $module1->id, 'title' => 'Lesson 1.1: Interpreting Meteorological Radar Alerts'],
            ['content' => 'Overview of radar reflectivity, Doppler velocity imagery, and coastal storm surge tracking.', 'order' => 1]
        );

        LearningResource::firstOrCreate(
            ['lesson_id' => $lesson1->id, 'title' => 'Coastal Evacuation Manual 2026.pdf'],
            ['file_path' => 'resources/coastal_evacuation_manual.pdf', 'type' => 'pdf']
        );

        // 7. MCQ Assessment (15 Questions, 15-Minute Timer)
        $assessment = Assessment::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'Super Cyclone Response Certification Assessment'],
            [
                'subject' => 'Cyclone Emergency Management',
                'instructions' => 'Complete all 15 multiple-choice questions within the 15-minute countdown timer. Passing score is 70%.',
                'duration' => 15, // 15 Minutes
                'passing_score' => 70,
                'status' => 'published',
                'negative_marking' => false,
                'max_attempts' => 5,
                'randomize_questions' => false,
            ]
        );

        $questionsDataSeeder = [
            ['q' => 'What is the primary objective of a Category 4 Cyclone evacuation warning?', 'opts' => [['Immediate movement of vulnerable population to designated cyclone shelters', true], ['Storing extra emergency rations at home', false], ['Securing residential property windows', false], ['Awaiting social media updates', false]]],
            ['q' => 'Which meteorological sensor system provides real-time Doppler velocity tracking for tropical cyclones?', 'opts' => [['S-Band Doppler Weather Radar (DWR)', true], ['Barometric Pressure Gauge', false], ['Anemometer Mast', false], ['Hydrological Flow Meter', false]]],
            ['q' => 'What is the standard time limit for this emergency operational assessment?', 'opts' => [['15 Minutes', true], ['30 Minutes', false], ['45 Minutes', false], ['60 Minutes', false]]],
            ['q' => 'How far ahead of cyclone landfall should mandatory coastal evacuation orders be communicated?', 'opts' => [['24 to 36 Hours prior to landfall', true], ['2 to 4 Hours prior to landfall', false], ['72 Hours prior to landfall', false], ['1 Hour prior to landfall', false]]],
            ['q' => 'Which satellite frequency band is recommended for tactical backup communications during terrestrial network collapse?', 'opts' => [['C-Band and Ku-Band Emergency Satellite Terminals', true], ['FM Commercial Radio Frequency', false], ['Cellular 4G LTE Towers', false], ['Bluetooth Local Mesh', false]]],
            ['q' => 'What is the minimum recommended clean water allocation per person per day in emergency shelters?', 'opts' => [['15 Liters per day', true], ['2 Liters per day', false], ['50 Liters per day', false], ['1 Liter per day', false]]],
            ['q' => 'In the Incident Command System (ICS), who holds overall responsibility for incident safety and tactical deployment?', 'opts' => [['Incident Commander (IC)', true], ['Public Information Officer', false], ['Logistics Section Chief', false], ['Planning Section Chief', false]]],
            ['q' => 'What is the primary purpose of GIS spatial mapping during flood relief operations?', 'opts' => [['Identifying inundated zones and routing rescue boats to high-ground shelters', true], ['Calculating administrative budget expenses', false], ['Printing physical paper maps', false], ['Monitoring weather radar frequencies', false]]],
            ['q' => 'Which triage color code indicates immediate life-threatening injuries requiring priority emergency transport?', 'opts' => [['Red (Immediate Priority)', true], ['Yellow (Delayed Priority)', false], ['Green (Minimal Priority)', false], ['Black (Deceased / Expectant)', false]]],
            ['q' => 'What is the standard CPR compression-to-ventilation ratio for adult cardiac arrest victims?', 'opts' => [['30 Chest Compressions to 2 Rescue Breaths', true], ['15 Chest Compressions to 1 Rescue Breath', false], ['50 Chest Compressions to 5 Rescue Breaths', false], ['10 Chest Compressions to 2 Rescue Breaths', false]]],
            ['q' => 'Which document verifies trainer qualification and digital profile tier badges?', 'opts' => [['Digital Twin Trainer Profile Certificate', true], ['Temporary Attendance Sheet', false], ['Self-Assessment Questionnaire', false], ['Unverified Registration Form', false]]],
            ['q' => 'What is the minimum passing score percentage for the 15-minute emergency assessment?', 'opts' => [['70% Passing Grade', true], ['50% Passing Grade', false], ['80% Passing Grade', false], ['60% Passing Grade', false]]],
            ['q' => 'During storm surge conditions, what is the safest minimum elevation for emergency relief staging areas?', 'opts' => [['10 Meters above mean sea level', true], ['1 Meter above mean sea level', false], ['Mean Sea Level', false], ['2 Meters below sea level', false]]],
            ['q' => 'Which emergency broadcast frequency channel is designated for national disaster advisories?', 'opts' => [['Emergency Alert System (EAS) & All-Hazards Warning Network', true], ['Commercial Radio Music Channel', false], ['Local High-Frequency Band', false], ['Private Mesh Network', false]]],
            ['q' => 'What action is taken automatically when the 15-minute assessment timer reaches 00:00?', 'opts' => [['Automatic form submission and instant score calculation', true], ['Assessment reset and restart', false], ['Extra 10 minutes granted automatically', false], ['Cancellation of assessment attempt', false]]],
        ];

        foreach ($questionsDataSeeder as $sItem) {
            $sQ = AssessmentQuestion::firstOrCreate(
                ['assessment_id' => $assessment->id, 'question_text' => $sItem['q']],
                ['marks' => 10]
            );
            foreach ($sItem['opts'] as $sOpt) {
                AssessmentOption::firstOrCreate(
                    ['assessment_question_id' => $sQ->id, 'option_text' => $sOpt[0]],
                    ['is_correct' => $sOpt[1]]
                );
            }
        }

        // 8. Practical Assessment Drill
        $drill = PracticalAssessment::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'Emergency Shelter Resource Allocation Simulation'],
            [
                'description' => 'Interactive scenario-based simulation for emergency resource allocation.',
                'created_by' => $trainer->id,
                'status' => 'published',
            ]
        );

        $rootNode = PracticalNode::firstOrCreate(
            ['practical_assessment_id' => $drill->id, 'title' => 'Initial Incident Report: River Delta Flooding'],
            [
                'situation_text' => 'A sudden breach in the northern dike threatens 5,000 residents in Delta Sector B.',
                'is_start' => true,
            ]
        );

        $targetNode = PracticalNode::firstOrCreate(
            ['practical_assessment_id' => $drill->id, 'title' => 'Scenario Complete: Evacuation Success'],
            [
                'situation_text' => 'Sector B evacuated safely with zero casualties recorded.',
                'is_start' => false,
            ]
        );

        PracticalOption::firstOrCreate(
            ['practical_node_id' => $rootNode->id, 'option_text' => 'Deploy high-capacity water pumps immediately'],
            [
                'next_node_id' => $targetNode->id,
                'consequence_text' => 'Water levels stabilized rapidly.',
                'score_delta' => 25,
                'feedback' => 'Correct choice. Pumps stabilize water rise while evacuation proceeds.',
            ]
        );

        // 9. Enrollment, Progress & Certificate
        Enrollment::firstOrCreate(
            ['user_id' => $trainee->id, 'course_id' => $course1->id],
            [
                'status' => 'completed',
                'progress' => 100,
                'due_date' => now()->addDays(30),
            ]
        );

        Certificate::firstOrCreate(
            ['certificate_id' => 'CC-CERT-2026-8841'],
            [
                'user_id' => $trainee->id,
                'course_id' => $course1->id,
                'certificate_name' => 'Category 4 Super Cyclone Response Certificate',
                'issuing_organization' => 'National Disaster Capacity Building Authority',
                'score' => 95,
                'cert_status' => 'valid',
                'issue_date' => now()->subDays(10),
                'expiry_date' => now()->addYears(2),
            ]
        );

        // 10. Notifications, Badges & AI Drafts
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\CertificateIssuedNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $trainee->id,
            'data' => json_encode([
                'title' => 'Certificate Issued',
                'message' => 'Your official certificate for "Category 4 Super Cyclone Response" is now available for download.',
                'link' => route('trainee.certificates'),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $badge = Badge::firstOrCreate(
            ['slug' => 'disaster-hero'],
            [
                'name' => 'Disaster Preparedness Specialist',
                'description' => 'Completed Category 4 Super Cyclone Response course with high assessment score.',
                'icon' => 'bi-shield-check',
                'points_required' => 200,
            ]
        );

        $trainee->badges()->syncWithoutDetaching([$badge->id => ['awarded_at' => now()]]);

        AiContentDraft::firstOrCreate(
            ['draft_title' => 'AI Draft: Flood Preparedness Protocols'],
            [
                'user_id' => $admin->id,
                'source_file_name' => 'flood_protocols.pdf',
                'source_file_path' => 'ai_drafts/flood_protocols.pdf',
                'draft_difficulty' => 'intermediate',
                'draft_description' => 'Draft course overview generated for flood preparedness.',
                'draft_mcqs' => [['question' => 'How often should dikes be inspected during monsoon?', 'options' => ['Daily', 'Monthly', 'Yearly'], 'answer' => 'Daily']],
                'status' => 'under_review',
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'National Disaster Preparedness & Cyclone Readiness Advisory 2026'],
            [
                'content' => 'All district personnel are mandated to complete the Super Cyclone Emergency Assessment within 14 business days. Interactive decision tree simulations have been updated.',
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'created_by' => $admin->id,
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'Q3 Workforce Competency Risk & Practical Drill Schedule'],
            [
                'content' => 'Department heads and regional supervisors are advised that the Q3 practical response drills for coastal surge defense will begin on the 1st of next month.',
                'is_published' => true,
                'published_at' => now()->subDay(),
                'created_by' => $admin->id,
            ]
        );

        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'DATABASE_SEEDED',
            'entity_type' => 'System',
            'entity_id' => 1,
            'new_values' => ['message' => 'System initialized database with demo accounts and enterprise entities.'],
        ]);

        $this->command->info('Database successfully seeded with realistic enterprise data!');
    }
}
