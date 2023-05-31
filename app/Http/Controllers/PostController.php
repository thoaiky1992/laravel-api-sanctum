<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function search(Request $request)
    {
        $postQuery = Posts::query();
        $postQuery->whereFullText('title', $request->only('title'));
        $posts = $postQuery->get();
        return $posts;
    }
}
