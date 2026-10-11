<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'company_name',
        'location',
        'wage',
        'phone',
        'email',
        'desired_job',
        'category_id',
        'work_style',
        'employment_type',
    ];

    // relation with Models\User.php , Bookmark.php
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
    // check whether logedin user already marked or not
    //  ?User: Not get error(nullable) even the user logedin yet
    // : bool ; true ir false
    public function isBookmarkedBy(?User $user): bool
    {
        //  user is null(not logedin yet)
        if(!$user){
            return false;
        }
        // get user data from bookmark table , logedin userID is exist :true, not:false 
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static $locations = [
        'Vancouver, BC',
        'Burnaby, BC',
        'Richmond, BC',
        'Surrey, BC',
        'Victoria, BC',
        'Toronto, ON',
        'Mississauga, ON',
        'Calgary, AB',
        'Edmonton, AB',
        'Montreal, QC',
        'Quebec, QC',
        'Other',
    ]; 
}
