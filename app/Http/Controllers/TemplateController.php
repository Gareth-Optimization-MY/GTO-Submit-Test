<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;

class TemplateController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $templates = Template::latest()->paginate(5);
        return view('admin.templates.index', compact('templates'));
    }
    public function getTemplate(Request $request)
    {
        $template= Template::find($request->template_id);

        return $template;
    }

}
