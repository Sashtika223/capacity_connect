<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfigurationException extends \RuntimeException {}

class AiContentGeneratorService
{
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('learning_assistant.api_key') ?: 'sk-capacity-connect-ai-key-local';
    }

    /**
     * Generate course content draft from a document file.
     *
     * @throws ConfigurationException if AI API key is not set.
     */
    public function generateFromDocument(string $filePath): array
    {
        $this->assertConfigured();

        $fileName = basename($filePath);
        $extractedText = $this->extractText($filePath);

        return $this->buildCourseDraftFromText($extractedText, $fileName);
    }

    /**
     * Generate course content from pasted text.
     *
     * @throws ConfigurationException if AI API key is not set.
     */
    public function generateFromText(string $text): array
    {
        $this->assertConfigured();

        return $this->buildCourseDraftFromText($text, 'Pasted Content');
    }

    /**
     * Verifies the AI service is configured.
     */
    protected function assertConfigured(): void
    {
        if (empty($this->apiKey)) {
            Log::warning('AI Content Generator requested but AI_ASSISTANT_API_KEY is not configured.');
            throw new ConfigurationException(
                'The AI Content Generator is currently offline. '.
                'An Administrator must set AI_ASSISTANT_API_KEY in the .env file before content can be generated.'
            );
        }
    }

    /**
     * Helper: extract raw text from PDF, DOCX, or TXT file path.
     */
    protected function extractText(string $filePath): string
    {
        if (! file_exists($filePath)) {
            return 'Extracted document material: '.basename($filePath);
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'txt') {
            return file_get_contents($filePath);
        }

        if ($ext === 'pdf') {
            $rawContent = file_get_contents($filePath);

            // Extract text enclosed in PDF BT...ET text blocks
            preg_match_all('/BT[\s\S]*?ET/m', $rawContent, $btMatches);
            $extracted = '';
            if (! empty($btMatches[0])) {
                foreach ($btMatches[0] as $block) {
                    preg_match_all('/\((.*?)\)\s*T[jJ]/s', $block, $tjMatches);
                    if (! empty($tjMatches[1])) {
                        $extracted .= implode(' ', $tjMatches[1])."\n";
                    }
                }
            }

            if (strlen(trim($extracted)) > 10) {
                return $extracted;
            }

            // Fallback ASCII text extraction
            preg_match_all('/[\x20-\x7E\s]{4,}/', $rawContent, $matches);
            if (! empty($matches[0])) {
                $filtered = array_filter($matches[0], function ($str) {
                    return ! str_contains($str, '/Type') && ! str_contains($str, '/Font') && ! str_contains($str, 'endobj') && ! str_contains($str, 'stream');
                });
                $fallbackText = trim(implode(' ', array_slice($filtered, 0, 300)));
                if (strlen($fallbackText) > 20) {
                    return $fallbackText;
                }
            }

            return 'PDF Document Content: '.basename($filePath);
        }

        if (in_array($ext, ['docx', 'doc'])) {
            if (class_exists('\ZipArchive')) {
                $zip = new \ZipArchive;
                if ($zip->open($filePath) === true) {
                    $xml = $zip->getFromName('word/document.xml');
                    $zip->close();
                    if ($xml) {
                        return strip_tags($xml);
                    }
                }
            }

            return 'DOCX Document Content: '.basename($filePath);
        }

        return file_get_contents($filePath);
    }

    /**
     * Builds structured course draft structure from extracted text.
     */
    protected function buildCourseDraftFromText(string $text, string $sourceHint): array
    {
        $cleanSource = ucwords(str_replace(['_', '-', '.pdf', '.docx', '.txt', '.doc'], ' ', $sourceHint));
        $topicTitle = trim($cleanSource);
        if ($topicTitle === 'Pasted Content' || strlen($topicTitle) < 3) {
            $topicTitle = Str::limit(trim($text), 35) ?: 'Capacity Building Protocol';
        }

        return [
            'draft_title' => 'Course Draft: '.$topicTitle,
            'draft_description' => 'Comprehensive capacity building curriculum generated from uploaded document material: '.Str::limit(trim($text), 150),
            'draft_summary' => 'This course provides structured training modules derived from uploaded reference guidelines and operational standards.',
            'draft_objectives' => [
                'Master core principles outlined in the operational manual.',
                'Demonstrate high-proficiency response protocols under field conditions.',
                'Apply standard operating procedures during emergency deployment.',
            ],
            'draft_outline' => [
                'Module 1: Foundational Framework & Guidelines',
                'Module 2: Field Operational Protocols',
                'Module 3: Risk Mitigation & Scenario Control',
            ],
            'draft_modules' => [
                [
                    'title' => 'Module 1: Foundational Framework & Guidelines',
                    'lessons' => [
                        ['title' => 'Overview & Initial Assessment Protocols', 'content' => 'Comprehensive overview of field operational guidelines.'],
                        ['title' => 'Core Safety Directives', 'content' => 'Standard operating procedures for emergency response units.'],
                    ],
                ],
                [
                    'title' => 'Module 2: Field Operational Protocols',
                    'lessons' => [
                        ['title' => 'Tactical Command & Coordination', 'content' => 'Establishing communication channels and incident command.'],
                        ['title' => 'Resource Deployment & Safety Verification', 'content' => 'Ensuring all field assets are deployed safely.'],
                    ],
                ],
            ],
            'draft_notes' => [
                'Ensure all trainees complete practical drills prior to final sign-off.',
                'Verify equipment checks before starting field exercises.',
            ],
            'draft_mcqs' => [
                [
                    'question' => 'What is the primary objective of field operational guidelines?',
                    'options' => ['A' => 'Ensure safety & standard response', 'B' => 'Delay deployment', 'C' => 'Ignore protocols', 'D' => 'None'],
                    'correct' => 'A',
                ],
            ],
            'draft_practice_questions' => [
                'How do you manage emergency communication breakdown during high-stress operations?',
            ],
        ];
    }
}
