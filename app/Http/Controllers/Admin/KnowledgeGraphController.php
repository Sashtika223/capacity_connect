<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KnowledgeGraphController extends Controller
{
    public function index()
    {
        return view('dashboards.admin.knowledge-graph.index');
    }

    /**
     * Build and return the full graph as JSON (nodes + edges).
     * Called via AJAX by the Vis.js frontend.
     */
    public function graphData(Request $request)
    {
        $filter = $request->input('filter', '');

        $nodes = [];
        $edges = [];
        $nodeIds = [];

        // Helper to add a node only once
        $addNode = function ($id, $label, $group, $title = '') use (&$nodes, &$nodeIds) {
            if (! in_array($id, $nodeIds)) {
                $nodes[] = [
                    'id' => $id,
                    'label' => Str::limit($label, 20),
                    'title' => $title ?: $label,
                    'group' => $group,
                ];
                $nodeIds[] = $id;
            }
        };

        // ------- COMPETENCIES (skill nodes) -------
        $competencies = Competency::with(['users', 'courses'])->get();
        foreach ($competencies as $comp) {
            if ($filter && stripos($comp->name, $filter) === false) {
                continue;
            }
            $addNode("comp_{$comp->id}", $comp->name, 'competency', "Competency: {$comp->name}\nType: {$comp->type}");
        }

        // ------- USERS (employees & trainers) -------
        $users = User::with(['competencies', 'enrollments.course', 'certificates', 'trainerProfile', 'traineeProfile', 'trainerCourses'])
            ->whereIn('role', ['trainee', 'trainer'])
            ->get();

        foreach ($users as $user) {
            $profile = $user->role === 'trainee' ? $user->traineeProfile : $user->trainerProfile;
            $dept = $profile->department ?? 'Unknown Department';
            $title = "{$user->name}\nRole: {$user->role}\nDept: {$dept}";

            $addNode("user_{$user->id}", $user->name, $user->role, $title);

            // Employee -> Department
            if ($dept && $dept !== 'Unknown Department') {
                $deptId = 'dept_'.Str::slug($dept);
                $addNode($deptId, $dept, 'department', "Department: {$dept}");
                $edges[] = ['from' => "user_{$user->id}", 'to' => $deptId, 'label' => 'belongs to', 'arrows' => 'to'];
            }

            // Employee -> Competency
            foreach ($user->competencies as $comp) {
                $level = $comp->pivot->current_level ?? 'Beginner';
                $compNodeId = "comp_{$comp->id}";
                if (in_array($compNodeId, $nodeIds)) {
                    $edges[] = ['from' => "user_{$user->id}", 'to' => $compNodeId, 'label' => $level, 'arrows' => 'to'];
                }
            }

            // Employee -> Completed Courses
            foreach ($user->enrollments as $enrollment) {
                if ($enrollment->status === 'completed' && $enrollment->course) {
                    $course = $enrollment->course;
                    $courseNodeId = "course_{$course->id}";
                    $addNode($courseNodeId, $course->title, 'course', "Course: {$course->title}\nDifficulty: {$course->difficulty}");
                    $edges[] = ['from' => "user_{$user->id}", 'to' => $courseNodeId, 'label' => 'completed', 'arrows' => 'to'];
                }
            }

            // Employee -> Certifications
            foreach ($user->certificates as $cert) {
                $certNodeId = "cert_{$cert->id}";
                $addNode($certNodeId, "Cert #{$cert->id}", 'certification', "Certificate #{$cert->id}\nIssued: {$cert->created_at->format('d M Y')}");
                $edges[] = ['from' => "user_{$user->id}", 'to' => $certNodeId, 'label' => 'holds', 'arrows' => 'to'];
            }

            // Trainer -> Courses they teach
            foreach ($user->trainerCourses as $course) {
                $courseNodeId = "course_{$course->id}";
                $addNode($courseNodeId, $course->title, 'course', "Course: {$course->title}\nDifficulty: {$course->difficulty}");
                $edges[] = ['from' => "user_{$user->id}", 'to' => $courseNodeId, 'label' => 'instructs', 'arrows' => 'to'];
            }
        }

        // ------- COURSES -> COMPETENCIES -------
        $courses = Course::with('competencies')->get();
        foreach ($courses as $course) {
            foreach ($course->competencies as $comp) {
                $courseNodeId = "course_{$course->id}";
                $compNodeId = "comp_{$comp->id}";
                if (in_array($courseNodeId, $nodeIds) && in_array($compNodeId, $nodeIds)) {
                    $edges[] = ['from' => $courseNodeId, 'to' => $compNodeId, 'label' => 'teaches', 'arrows' => 'to'];
                }
            }
        }

        return response()->json([
            'nodes' => array_values($nodes),
            'edges' => array_values($edges),
        ]);
    }
}
