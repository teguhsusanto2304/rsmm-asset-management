<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();
        
        if ($request->filled('id')) {
            $selectedCategory = Category::where('parent_id', $request->id);
        } else {
            $selectedCategoryDir = Category::whereNull('parent_id')->get()->first();        
            $selectedCategory = Category::where('parent_id', $selectedCategoryDir?->id);
        }
        
        //dd($selectedDepartment);
        $subUnits = $selectedCategory
            ? $selectedCategory->get() 
            : collect();
        $selectedCategory = $selectedCategory ?? null;


        return view('admin.categories.index', compact(
            'categories',
            'selectedCategory',
            'subUnits'
        ));
    }


    public function create()
    {
        $parents = Category::all();
        $categories = $parents;

        return view('admin.categories.create', compact('categories','parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')
            ->with('success','Kategory telah dibuat');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id','!=',$category->id)->get();
        $categories = $parents;


        return view('admin.categories.edit', compact('category','categories','parents'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success','Kategory telah diupdate');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success','Kategory telah dihapus');
    }
}
