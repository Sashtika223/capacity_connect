<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\AiContentDraft;
use App\Services\AiContentGeneratorService;
use App\Services\ConfigurationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiStudioController extends Controller
{
    public function index()
    {
        $drafts = AiContentDraft::where('user_id', auth()->id())->latest()->get();

        return view('dashboards.trainer.ai-studio.index', compact('drafts'));
    }

    public function generate(Request $request, AiContentGeneratorService $aiService)
    {
        $request->validate([
            'document' => 'nullable|file|mimes:pdf,txt,docx,doc|extensions:pdf,txt,docx,doc|max:51200',
            'pasted_content' => 'nullable|string|max:50000',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $file = $request->file('document');
        if ($file && ! $file->isValid()) {
            $errCode = $file->getError();
            if ($errCode === UPLOAD_ERR_INI_SIZE || $errCode === UPLOAD_ERR_FORM_SIZE) {
                return redirect()->back()->with('error', 'The uploaded file exceeds the server upload limit. Please select a smaller file or split the document.');
            }

            return redirect()->back()->with('error', 'File upload failed (Error Code: '.$errCode.'). Please try again.');
        }

        if (! $request->hasFile('document') && empty($request->pasted_content)) {
            return redirect()->back()->with('error', 'Please select a file or paste text content before clicking Generate Course Draft.');
        }

        try {
            $filePath = null;
            $fileName = 'Pasted Content';
            $pastedContent = $request->pasted_content;

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filePath = $file->store('ai_uploads', 'public');
                $fileName = $file->getClientOriginalName();
                $fullPath = storage_path('app/public/'.$filePath);
                if (! file_exists($fullPath)) {
                    $fullPath = storage_path('app/'.$filePath);
                }
                $generatedData = $aiService->generateFromDocument($fullPath);
            } else {
                $generatedData = $aiService->generateFromText($pastedContent);
            }

            $draft = AiContentDraft::create([
                'user_id' => auth()->id(),
                'source_file_name' => $fileName,
                'source_file_path' => $filePath,
                'source_pasted_content' => $pastedContent,
                'status' => 'drafted',
                'draft_difficulty' => $request->difficulty,
                'draft_title' => $generatedData['draft_title'] ?? null,
                'draft_description' => $generatedData['draft_description'] ?? null,
                'draft_outline' => $generatedData['draft_outline'] ?? [],
                'draft_summary' => $generatedData['draft_summary'] ?? null,
                'draft_objectives' => $generatedData['draft_objectives'] ?? [],
                'draft_modules' => $generatedData['draft_modules'] ?? [],
                'draft_notes' => $generatedData['draft_notes'] ?? [],
                'draft_mcqs' => $generatedData['draft_mcqs'] ?? [],
                'draft_practice_questions' => $generatedData['draft_practice_questions'] ?? [],
            ]);

            return redirect()->route('trainer.ai-studio.draft', $draft->id)
                ->with('success', 'Draft generated. Please review and edit before approving.');

        } catch (ConfigurationException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('AI Generator Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to process content. Please try again.');
        }
    }

    public function showDraft(AiContentDraft $draft)
    {
        if ($draft->user_id !== auth()->id()) {
            abort(403);
        }

        return view('dashboards.trainer.ai-studio.review', compact('draft'));
    }

    public function approveDraft(Request $request, AiContentDraft $draft)
    {
        if ($draft->user_id !== auth()->id()) {
            abort(403);
        }

        $action = $request->input('action', 'approve');

        // Always persist edits
        $draft->update([
            'draft_title' => $request->input('draft_title'),
            'draft_description' => $request->input('draft_description'),
            'draft_difficulty' => $request->input('draft_difficulty'),
            'draft_summary' => $request->input('draft_summary'),
            'draft_objectives' => array_filter($request->input('draft_objectives', [])),
            'draft_outline' => array_filter($request->input('draft_outline', [])),
            'draft_notes' => array_filter($request->input('draft_notes', [])),
        ]);

        if ($action === 'approve') {
            $draft->update(['status' => 'approved']);

            return redirect()->route('trainer.ai-studio.index')
                ->with('success', 'Draft approved! Content is ready to be published as a course.');
        }

        return redirect()->back()->with('success', 'Edits saved. Continue reviewing before approving.');
    }
}
