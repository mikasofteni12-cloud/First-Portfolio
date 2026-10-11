<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    //change add/delete bookmark
    public function store(Post $post)
    {
        $user = Auth::user();

        // check if already bookmarked or not
        $bookmark = Bookmark::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->first();
        
        if($bookmark){
            // delete if already marked
            $bookmark->delete();
        }else{
            // add if not mark yet
            Bookmark::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        }

        return back();
    }
}
