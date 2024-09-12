<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Repository\ArticleRepository;
use App\Repository\PreferenceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticlesController extends Controller
{   
    
    protected $articleRepository;
    protected $preferenceRepository;

    public function __construct(
        ArticleRepository $articleRepository,
        PreferenceRepository $preferenceRepository)
    {
        $this->middleware('auth.jwt');
        $this->articleRepository = $articleRepository;
        $this->preferenceRepository = $preferenceRepository;
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $source = $request->input('source');
        $date = $request->input('date');
        $currentPage = $request->input('page');

        $articles = $this->articleRepository->getArticles(
            $keyword, $category, $source, $date, intval($currentPage));

        return response()->json([
            'status' => 'success',
            'message' => 'ok',
            'articles' => $articles,
        ]);
    }

    public function getUserNewsfeed(Request $request):JsonResponse
    {  
        $currentPage = $request->input('page');

        $user = JWTAuth::parseToken()->authenticate();

        $preference = $this->preferenceRepository->getPreference($user);

        $articles = $this->articleRepository->getUserArticles($preference, $currentPage);

        return response()->json([
            'status' => 'success',
            'message' => 'ok',
            'articles' => $articles,
        ]);
    }
}
