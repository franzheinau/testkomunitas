<?php

namespace App\Http\Controllers;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    public function toggle(Post $post)
    {
        $userId = Auth::id();
        $existing = $post->likes()->where('user_id',$userId)->first();
        if($existing){
            $existing->delete();
            $status = 'unliked';
        } else {
            $post->likes()->create(['user_id'=>$userId]);
            $status = 'liked';
        }
        if(request()->wantsJson()){
            return response()->json(['status'=>$status, 'likesCount' => $post->likes()->count()]);
        }
        return back();
    }
}
