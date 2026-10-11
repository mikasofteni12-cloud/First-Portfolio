<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    // index
    public function index(Request $request)
    {
        $user = Auth::user();

        $targetRole = ($user->role === 'seeker') ? 'owner' : 'seeker';

        // basic relation user, category
        $query = Post::with(['user', 'category'])
                 ->whereHas('user', function($q) use ($targetRole){
                    $q->where('role', $targetRole);
                 });
        // filter
        if ($request->filled('category_id')){
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('location')){
            $query->where('location', $request->location);
        }
        // get post
        $posts    = $query->latest()->get();
        // get my post list
        $my_posts = Post::where('user_id', Auth::id())->latest()->get(); 
        // get bookmarked post
        $bookmarkedPosts = $user ? $user->bookmarkedPosts()
                ->whereHas('user', function ($q) use ($targetRole) {
                $q->where('role', $targetRole);
                })->latest()->get() : collect();
        // get category to display 
        $categories = Category::all();
        $locations  = \App\Models\Post::$locations;

        return view('users.home', compact('posts', 'my_posts', 'bookmarkedPosts', 'categories', 'locations'));
    }

    // create post
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    // to store for DB
    public function store(Request $request)
    {
        $user = Auth::user();
        // validation on role 
        if ($user->role === 'owner') {
            $request->validate([
                'title'        => 'required|max:100',
                'company_name' => 'required|max:250',
                'location'     => ['required', Rule::in(Post::$locations)],
                'wage'         => 'required|max:250',
                'phone'        => 'required|max:50',
                'email'        => 'required|email|max:250',
                'description'  => 'required|string',
                'work_style'   => 'required',
                'employment_type'=> 'required',
                'category_id'  => 'required|exists:categories,id',
            ]);
        } else {
            $request->validate([
                'title'        => 'required|max:100',
                'desired_job'  => 'nullable|max:250',
                'description'  => 'required|string',
                'work_style'   => 'required',
                'employment_type'=> 'required',
                'location'     => ['required', Rule::in(Post::$locations)],
                'category_id'  => 'required|exists:categories,id',          
                ]);
        }

        // store with relation(posts())
        $request->user()->posts()->create([
            'user_id'          =>Auth::id(),
            'title'            =>$request->title,
            'description'      =>$request->description,
            'company_name'     =>$request->company_name,
            'location'         =>$request->location,
            'wage'             =>$request->wage,
            'phone'            =>$request->phone,
            'email'            =>$request->email,
            'desired_job'      =>$request->desired_job,
            'work_style'       =>$request->work_style,
            'employment_type'  =>$request->employment_type,
            'category_id'      =>$request->category_id,

        ]);

        return redirect()->route('home')->with('success', 'Post created successfully.');
        
    }

    // show details
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // update, delete
    // show edit screen
    // public function edit(Post $post)
    // {
    //     // only can edit the owner of the post
    //     if($post->user_id !== Auth::id())
    //         {
    //             return redirect()->route('posts.index');
    //         }
    // }
    public function edit($id)
    {
        // can reach only for the owner of the post
        $post = Post::findOrFail($id);
        // identify the owner if not redirect to index
        if ($post->user_id != Auth::id()){
            return redirect()->route('posts.index');
        }
        $categories = Category::all();

        // if the owner of the post to conncect edit.blade
        return view('posts.edit', compact('post', 'categories'));
    }

    // update DB from changed form
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'title'        => 'required|max:100',
            'description'  => 'required',
            'company_name' => 'nullable|string|max:250',
            'location'     => 'nullable|string|max:250',
            'wage'         => 'nullable|string|max:250',
            'phone'        => 'nullable|string|max:250',
            'email'        => 'nullable|email|max:250',
            'desired_job'  => 'nullable|string|max:250',
            'category_id'  => 'required|exists:categories,id',
            'work_style'   => 'required|in:On-side,Remote,Hybrid',
            'employment_type' => 'required|in:Full-time,Part-time',
        ]);

        // get post from ID
        $post = Post::findOrFail($id);

        // Only can edit for the owner of the post / abort_if :本人確認 403: 403 forbidden
        abort_if($post->user_id != Auth::id(), 403, 'Unauthorized action.');

        // update data for both seeker and owner
        $post->title       = $request->title;
        $post->description = $request->description;
        $post->category_id = $request->category_id;
        $post->work_style  = $request->work_style;
        $post->employment_type = $request->employment_type;
        $post->location     = $request->location;

        // update for owner
        if(Auth::user()->role === 'owner'){
            $post->company_name = $request->company_name;        
            $post->wage         = $request->wage;
            $post->phone        = $request->phone;
            $post->email        = $request->email;
        }else{
            // for seeker
            $post->desired_job  = $request->desired_job;
        }
        // save
        $post->save();
        // redirect to show.blade
        return redirect()->route('posts.show', $id)->with('success', 'Post updated Successfully.' );
    }


    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Only can edit for the owner of the post
        abort_if($post->user_id != Auth::id(), 403, 'Unauthorized action.');

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully. ');
    }
}
