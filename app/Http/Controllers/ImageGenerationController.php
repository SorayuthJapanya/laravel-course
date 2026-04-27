<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePromptRequest;
use App\Services\OpenAiService;
use Illuminate\Http\Request;

class ImageGenerationController extends Controller
{
    public function __construct(private OpenAiService $openAiService)
    {

    }

    public function index()
    {

    }

    public function store(GeneratePromptRequest $request)
    {
        $user = $request->user();
        $image = $request->file('image');

        $orginalFilename = $image->getClientOriginalName();
        $sanitizedFilename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $orginalFilename);
        $extension = $image->getClientOriginalExtension();
        $safeFilename = pathinfo($sanitizedFilename, PATHINFO_FILENAME);
        $finalFilename = $safeFilename . '_' . time() . '.' . $extension;

        $imagePath = $image->storeAs('uploads/images', $finalFilename, 'public');

        try {
            $generatePrompt = $this->openAiService->generatePromptForImage($image);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }

        $imageGeneration = $user->imageGenerations()->create([
            'image_path' => $imagePath,
            'generate_prompt' => $generatePrompt,
            'origin_filename' => $orginalFilename,
            'file_size' => $image->getSize(),
            'mime_type' => $image->getMimeType(),
        ]);

        return response()->json([
            'id' => $imageGeneration->id,
            'image_url' => asset('storage/' . $imageGeneration->image_path),
            'generate_prompt' => $imageGeneration->generate_prompt,
            'origin_filename' => $imageGeneration->origin_filename,
            'file_size' => $imageGeneration->file_size,
            'mime_type' => $imageGeneration->mime_type,
            'created_at' => $imageGeneration->created_at,
        ], 201);
    }
}
