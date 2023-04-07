<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Essence;

class EssenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Essence::query();

        if ($request->filled('categories')) {
            $categories = explode(',', $request->input('categories'));
            $query->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('categories.id', $categories);
            });
        }

        $essences = $query->paginate(10);

        return view('essences.index', compact('essences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('essences.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $essence = new Essence();
        $essence->fill($request->only(['title', 'description']));
        $essence->save();

        $essence->categories()->attach($request->input('categories'));
        return redirect()->route('essences.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Essence $essence)
    {
        $essence->load('categories');
        return view('essences.show', compact('essence'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Essence $essence)
    {
        $categories = Category::all();
        $essence->load('categories');
        return view('essences.edit', compact('essence', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Essence $essence)
    {
        $essence->fill($request->only(['title', 'description']));
        $essence->save();

        $essence->categories()->sync($request->input('categories'));

        return redirect()->route('essences.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Essence $essence)
    {
        $essence->delete();
        return redirect()->route('essences.index');
    }
}
