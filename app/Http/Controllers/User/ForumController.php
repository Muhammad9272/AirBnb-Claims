<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class ForumController extends Controller
{   
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    public function Forum()
    {   
        // $banner=Banner::active()->where('for_section','section_career_forum')->first();
        // return view('user.forum',compact('banner'));
    }
    public function gameBased($value='')
    {
        return view('user.gamebased.index');
    }
}
