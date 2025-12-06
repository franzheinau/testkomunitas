<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    public function store(Request $request, Post $post)
    {
        $request->validate(['body'=>'required|string|max:2000','parent_id'=>'nullable|exists:comments,id']);
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->input('body'),
            'parent_id' => $request->input('parent_id'),
        ]);
        if($request->wantsJson()){
            return response()->json(['comment'=>$comment->load('user')], 201);
        }
        return back()->with('success','Komentar terkirim.');
    }

    public function destroy(Comment $comment)
    {
        // hanya owner atau admin boleh hapus
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success','Komentar dihapus.');
    }
}
