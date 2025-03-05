<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $articles = Article::where('user_id', $user->id)->get();
            return response()->json(['status' => true, 'articles' => $articles]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to retrieve articles', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }

        try {
            $article = Article::create([
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json(['status' => true, 'article' => $article], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create article', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = Auth::user();
            $article = Article::where('user_id', $user->id)->find($id);

            if (!$article) {
                return response()->json(['status' => false, 'message' => 'Article not found'], 404);
            }

            return response()->json(['status' => true, 'article' => $article]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to retrieve article', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $article = Article::where('user_id', $user->id)->find($id);

            if (!$article) {
                return response()->json(['status' => false, 'message' => 'Article not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
            }

            $article->update($request->only(['title', 'content']));

            return response()->json(['status' => true, 'article' => $article]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to update article', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $article = Article::where('user_id', $user->id)->find($id);

            if (!$article) {
                return response()->json(['status' => false, 'message' => 'Article not found'], 404);
            }

            $article->delete();

            return response()->json(['status' => true, 'message' => 'Article deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete article', 'error' => $e->getMessage()], 500);
        }
    }
}
