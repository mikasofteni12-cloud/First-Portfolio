<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        

        // change to target to get the data, depends on role
        //  if Owner; bookedmarked from seekers posts/ Seeker; bookmarked from owners posts
        $targetRole = ($user->role === 'seeker') ? 'owner' : 'seeker';

        $query = Post::whereHas('user', function($q) use($targetRole){
            $q->where('role', $targetRole);
        });

        // if filterd categories
        if($request->filled('category_id')){
            $query->where('category_id', $request->category_id);
        }

        // if filterd locations
        if($request->filled('location')){
            $query->where('location', $request->location);
        }

        // get index of posts
        $posts = $query->latest()->get();
        
        // get categories, locations
        $categories = Category::all();
        $locations  =\App\Models\Post::$locations;

        // display own posts 
        $my_posts = Post::where('user_id', $user->id)->latest()->get();
        
        // get posts the logged-in user bookmarked
        $bookmarkedPosts = $user ? $user->bookmarkedPosts()
                                        ->whereHas('user', function ($q) use ($targetRole){
                                            $q->where('role', $targetRole);
                                        })
                                        ->latest()->get() : collect();
        // display to home
        return view('users.home', compact('posts', 'my_posts', 'bookmarkedPosts', 'categories', 'locations'));
    }

}


// if($user->role === 'owner'){
        //     // if login as owner, only can see the posts from seeker
        //     $posts = Post::whereHas('user', function ($q){
        //         $q->where('role', 'seeker');
        //     });
        // }else{
        //     // if login as seeker, only can see the posts from owners
        //     $posts = Post::whereHas('user', function ($q){
        //         $q->where('role', 'owner');
        //     });
        // }
