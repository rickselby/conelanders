<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\CategoriesUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\CategoryRequest;
use App\Models\Races\RacesCategory;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:races-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('races.category.index')
            ->with('categories', RacesCategory::orderBy('name')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('races.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = RacesCategory::create($request->only('name'));
        \Event::fire(new CategoriesUpdated());
        \Notification::add('success', 'Category "'.$category->name.'" created');
        return \Redirect::route('races.category.show', $category);
    }

    /**
     * Display the specified resource.
     *
     * @param  RacesCategory $category
     * @return \Illuminate\Http\Response
     */
    public function show(RacesCategory $category)
    {
        return view('races.category.show')
            ->with('category', $category)
            ->with('championships', $category->championships()->with('events')->get()->sortBy('ends'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  RacesCategory $category
     * @return \Illuminate\Http\Response
     */
    public function edit(RacesCategory $category)
    {
        return view('races.category.edit')
            ->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryRequest $request
     * @param  RacesCategory $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, RacesCategory $category)
    {
        $category->fill($request->only('name'));
        $category->save();
        \Event::fire(new CategoriesUpdated());
        \Notification::add('success', 'Category "'.$category->name.'" updated');
        return \Redirect::route('races.category.show', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RacesCategory $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(RacesCategory $category)
    {
        if ($category->championships->count()) {
            \Notification::add('error', 'Category "'.$category->name.'" cannot be deleted - there are championships assigned to it');
            return \Redirect::route('races.category.show', $category);
        } else {
            $category->delete();
            \Event::fire(new CategoriesUpdated());
            \Notification::add('success', 'Category "'.$category->name.'" deleted');
            return \Redirect::route('races.category.index');
        }
    }
}
