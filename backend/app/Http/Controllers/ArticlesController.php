<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $source = $request->input('source');
        $date = $request->input('date');

        $query = Article::query();
        if ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                ->orWhere('content','like', "%{$keyword}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($source) {
            $query->where('source', $source);
        }

        if ($date) {
            $query->WhereDate('published_at', $date);
        }

        $articles = $query->paginate();

        return response()->json([
            'status' => 'success',
            'message' => count($articles) > 0 ? 'Articles fetched' : 'No article found',
            'articles' => $articles,
        ]);
    }
}
