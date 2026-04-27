<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Exceptions\RateLimitException;
use OpenAI\Factory;

class OpenAiService
{
    public function generatePromptForImage(UploadedFile $image): string
    {
        $imageData = base64_encode(file_get_contents($image->getPathname()));
        $mimeType = $image->getMimeType();

        $client = (new Factory())->withApiKey(config('services.openai.key'))->make();

        try {
            $response = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'You are an expert image analyst and prompt engineer. Analyze the provided image and produce a detailed, structured reconstruction prompt optimized for text-to-image models (e.g., Midjourney, DALL·E, Stable Diffusion).

                                    Your output must cover ALL of the following dimensions:

                                    **Subject & Composition**
                                    - Primary subject(s): what they are, their pose, position, and spatial relationship
                                    - Foreground, midground, and background elements
                                    - Camera angle, framing (close-up / medium / wide), and perspective

                                    **Visual Style**
                                    - Art style (photorealistic, oil painting, anime, concept art, watercolor, etc.)
                                    - Time period or cultural aesthetic if applicable
                                    - Any notable rendering technique or texture

                                    **Lighting & Color**
                                    - Light source direction, type (natural/artificial/volumetric), and intensity
                                    - Dominant color palette (list 3–5 key colors or tones)
                                    - Shadows, highlights, contrast level (soft / dramatic / flat)

                                    **Mood & Atmosphere**
                                    - Emotional tone and narrative feeling of the image
                                    - Weather, time of day, or environmental conditions if present

                                    **Technical Parameters** *(estimate if unclear)*
                                    - Aspect ratio
                                    - Level of detail (hyper-detailed / painterly / minimal)
                                    - Any post-processing style (film grain, lens blur, HDR, etc.)

                                    Format your final output as a single cohesive prompt paragraph, followed by a `--style` tag block with suggested model-specific parameters. Be precise, evocative, and avoid vague filler words like "beautiful" or "stunning." Focus on concrete, descriptive language that conveys the exact visual elements and style of the image.'
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$imageData}"
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        } catch (RateLimitException $e) {
            throw new \RuntimeException('OpenAI rate limit exceeded. Please try again later.', 429, $e);
        } catch (ErrorException $e) {
            throw new \RuntimeException('OpenAI API error: ' . $e->getMessage(), $e->getCode(), $e);
        }

        return $response->choices[0]->message->content;
    }
}
