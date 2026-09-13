<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class EscalateOverdueEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollments:escalate-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan enrollments past due date, mark as overdue, and dispatch escalation notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdueEnrollments = Enrollment::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('escalated', false)
            ->with(['user', 'course.trainer'])
            ->get();

        $count = 0;
        $adminUsers = User::where('role', 'admin')->get();

        foreach ($overdueEnrollments as $enrollment) {
            $enrollment->update([
                'status' => 'overdue',
                'escalated' => true,
            ]);

            $count++;

            // 1. Notify Trainee
            Notification::create([
                'user_id' => $enrollment->user_id,
                'title' => 'Overdue Deadline Escalation',
                'message' => 'Your target completion deadline for course "'.($enrollment->course->title ?? 'Course').'" has passed. This status has been escalated.',
                'link' => route('trainee.courses.show', $enrollment->course_id),
                'read' => false,
            ]);

            // 2. Notify Trainer if assigned
            if ($enrollment->course && $enrollment->course->trainer_id) {
                Notification::create([
                    'user_id' => $enrollment->course->trainer_id,
                    'title' => 'Trainee Enrollment Overdue Escalation',
                    'message' => 'Trainee '.($enrollment->user->name ?? 'User').' has missed the completion deadline for course "'.$enrollment->course->title.'".',
                    'link' => route('trainer.courses.enrollees', $enrollment->course_id),
                    'read' => false,
                ]);
            }

            // 3. Notify Admins
            foreach ($adminUsers as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Admin Overdue Escalation Alert',
                    'message' => 'Escalation Notice: '.($enrollment->user->name ?? 'Trainee').' is overdue on "'.($enrollment->course->title ?? 'Course').'".',
                    'link' => route('admin.courses.index'),
                    'read' => false,
                ]);
            }
        }

        $this->info("Escalation engine complete: {$count} overdue enrollments processed and escalated.");
    }
}
