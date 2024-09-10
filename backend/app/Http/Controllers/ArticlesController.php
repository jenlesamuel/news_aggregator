<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Repository\ArticleRepository;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{   
    
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->middleware('auth.jwt');
        $this->articleRepository = $articleRepository;
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
}
