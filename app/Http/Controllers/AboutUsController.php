<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    //
    public function index(){
        $weburl = [
    		'google' 	=> 'https://www.google.com',
    		'facebook'	=> 'https://www.facebook.com',
    		'youtube'	=> 'https://www.youtube.com'
    	];
    	$desc = 'Please visit the following url';
    	// return view('about_us', [
        //     'weburl' => $weburl,
        //     'desc'	 => $description	
        // ]);

	    return view('about-us', compact('weburl', 'desc'));

    }

}
