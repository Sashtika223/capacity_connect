<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Services\ConfigurationException;
use App\Services\LearningAssistantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LearningAssistantController extends Controller
{
    public function index()
    {
        return view('dashboards.trainee.assistant.index');
    }

    public function chat(Request $request, LearningAssistantService $assistantService)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        try {
            $response = $assistantService->generateResponse($request->prompt, [
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $response,
            ]);

        } catch (ConfigurationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'not_configured',
                'message' => $e->getMessage(),
            ], 503);

        } catch (\Exception $e) {
            Log::error('Learning Assistant Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'internal_error',
                'message' => 'The assistant encountered an error. Please try again later.',
            ], 500);
        }
    }
}
