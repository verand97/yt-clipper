<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Send transcript or chapters to Gemini API to detect key moments.
     *
     * @param string $transcript
     * @param array $chapters
     * @param string $videoTitle
     * @param int $durationSeconds
     * @param string|null $customPrompt
     * @param int $minDuration
     * @param int $maxClips
     * @param string|null $userApiKey
     * @return array|null
     */
    public function findKeyMoments(
        string $transcript,
        array $chapters,
        string $videoTitle,
        int $durationSeconds,
        ?string $customPrompt = null,
        int $minDuration = 15,
        int $maxClips = 5,
        ?string $userApiKey = null
    ): ?array {
        $apiKey = $userApiKey ?: config('services.gemini.key', env('GEMINI_API_KEY'));

        if (!$apiKey) {
            Log::warning('Gemini API key is not configured.');
            return null;
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        // Prepare context
        $context = "Video Title: {$videoTitle}\n";
        $context .= "Video Duration: {$durationSeconds} seconds\n";
        
        if (!empty($chapters)) {
            $context .= "\nYouTube native chapters:\n";
            foreach ($chapters as $ch) {
                $start = $this->secondsToTime((int)($ch['start_time'] ?? 0));
                $end = $this->secondsToTime((int)($ch['end_time'] ?? 0));
                $context .= "- [{$start} - {$end}] {$ch['title']}\n";
            }
        }

        if (!empty($transcript)) {
            $context .= "\nVideo Transcript (Timestamped):\n" . substr($transcript, 0, 80000); // limit transcript size to prevent token limits
        }

        $focusInstruction = $customPrompt ? "Focus on identifying segments related to: \"{$customPrompt}\"." : "Identify the most interesting, engaging, funny, or key highlights of the video.";

        $systemInstruction = "You are an AI video editor assistant. Your task is to analyze the video details (transcript and/or chapters) and extract the top key moments.
Rules:
1. Identify up to {$maxClips} key moments.
2. Each moment MUST have a minimum duration of {$minDuration} seconds.
3. Provide start_time and end_time for each moment.
4. Format start_time and end_time as 'HH:MM:SS' (e.g. 00:01:25) or 'MM:SS'.
5. Provide a short, catchy, descriptive title for each moment in Indonesian.
6. Make sure the start_time and end_time are within the video duration of {$durationSeconds} seconds.
7. {$focusInstruction}
8. Ensure the clips do not overlap significantly.";

        $prompt = "Based on the provided video context, output the selected key moments in JSON format.";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $context . "\n\nInstructions:\n" . $systemInstruction . "\n\n" . $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'responseSchema' => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'clips' => [
                                'type' => 'ARRAY',
                                'description' => 'List of identified key moments',
                                'items' => [
                                    'type' => 'OBJECT',
                                    'properties' => [
                                        'start_time' => [
                                            'type' => 'STRING',
                                            'description' => 'Start timestamp of the clip, format: HH:MM:SS or MM:SS'
                                        ],
                                        'end_time' => [
                                            'type' => 'STRING',
                                            'description' => 'End timestamp of the clip, format: HH:MM:SS or MM:SS'
                                        ],
                                        'title' => [
                                            'type' => 'STRING',
                                            'description' => 'A catchy title in Indonesian for this specific clip segment'
                                        ]
                                    ],
                                    'required' => ['start_time', 'end_time', 'title']
                                ]
                            ]
                        ],
                        'required' => ['clips']
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed: ' . $response->body());
                return null;
            }

            $json = $response->json();
            $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            if (empty($text)) {
                Log::error('Gemini API returned empty text response.');
                return null;
            }

            $result = json_decode($text, true);
            if (!isset($result['clips']) || !is_array($result['clips'])) {
                Log::error('Gemini response format is invalid: ' . $text);
                return null;
            }

            return $result['clips'];

        } catch (\Exception $e) {
            Log::error('Error calling Gemini API: ' . $e->getMessage());
            return null;
        }
    }

    private function secondsToTime(int $seconds): string
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
}
