<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('user_id', auth()->user()->id)->get();
        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $category = Category::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('category.index')->with('success', 'category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //nampilin satu2
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {
            // dd($category);
            return view('category.edit', compact('category'));
        } else {
            // abort(403);
            // abort(403, 'Not authorized');
            return redirect()->route('category.index')->with('danger', 'You are not authorized to edit this category!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Practical
        // $category->title = $request->title;
        // $category->save();

        // Eloquent Way - Readable
        $category->update([
            'title' => ucfirst($request->title),
        ]);
        return redirect()->route('category.index')->with('success', 'category updated successfully!');
    }

    public function complete(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {
            $category->update([
                'is_complete' => true,
            ]);
            return redirect()->route('category.index')->with('success', 'category completed successfully!');
        } else {
            return redirect()->route('category.index')->with('danger', 'You are not authorized to complete this category!');
        }
    }

    public function uncomplete(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {

            $category->update([
                'is_complete' => false,
            ]);
            return redirect()->route('category.index')->with('success', 'category uncompleted successfully!');
        } else {
            return redirect()->route('category.index')->with('danger', 'You are not authorized to uncomplete this category!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {
            $category->delete();
            return redirect()->route('category.index')->with('success', 'category deleted successfully!');
        } else {
            return redirect()->route('category.index')->with('danger', 'You are not authorized to delete this category!');
        }
    }

    public function destroyCompleted()
    {
        // get all categorys for current user where is_completed is true
        $categorysCompleted = Category::where('user_id', auth()->user()->id)
            ->where('is_complete', true)
            ->get();
        foreach ($categorysCompleted as $category) {
            $category->delete();
        }
        // dd($categorysCompleted);
        return redirect()->route('category.index')->with('success', 'All completed categorys deleted successfully!');
    }
}
