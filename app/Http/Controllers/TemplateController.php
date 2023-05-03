<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function store(Request $request)
    {
        $template = new Template();
        $template->name = $request->input('name');
        $comments = $request->input('comments');
        $template->comments = json_encode($comments); // encode the comments array as JSON
        $template->save();

        return response()->json($template);
    }

    public function index()
{
    $templates = Template::all();

    return response()->json($templates);
}

}
