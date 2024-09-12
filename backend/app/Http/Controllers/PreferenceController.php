<?php

namespace App\Http\Controllers;

use App\Repository\ArticleRepository;
use App\Repository\PreferenceRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class PreferenceController extends Controller
{   
    protected $peferenceRepository;
    protected $articleRepository;

    public function __construct(
        PreferenceRepository $peferenceRepository,
        ArticleRepository $articleRepository)
    {
        $this->middleware('auth.jwt');
        $this->peferenceRepository = $peferenceRepository;
        $this->articleRepository = $articleRepository;
    }

    public function getPreference()
    {
        $user = JWTAuth::parseToken()->authenticate(); 
        $preferences = $this->peferenceRepository->getPreference($user); 

        if (!$preferences) {
            return response()->json([
                'status' => 'error',
                'message' => 'No preferences found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'ok',
            'preferences' => $preferences,
        ]);
    }

    
    public function updatePreference(Request $request)
    {
        $validated = $request->validate([
            'sources' => 'nullable|array',
            'categories' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        $preference = $this->peferenceRepository->updateOrCreate(
            $user,
            [
                'sources' => $validated['sources'] ?? [],
                'categories' => $validated['categories'] ?? [],
                'authors' => $validated['authors'] ?? [],
            ]
        );

        return response()->json([
            'status'=> 'success',
            'message' => 'Preferences updated successfully', 
            'preference' => $preference],
        );
    }

    public function getPreferenceOptions()
    {
        return $this->articleRepository->getOptions();
    }
}
