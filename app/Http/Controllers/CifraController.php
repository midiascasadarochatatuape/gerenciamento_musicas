<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Category;
use App\Models\Cifra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CifraController extends Controller {
    public function index(){
        return view('cifras.index');
    }

    public function show(){
        $cifra = Cifra::find(4);
        return view('cifras.show', compact('cifra'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        Cifra::create($request->only('titulo', 'html'));
        return redirect()->route('cifras.show');
    }

    public function update(Request $request, Cifra $cifra)
    {
        $cifra->update($request->only('html'));
        return back();
    }


}

