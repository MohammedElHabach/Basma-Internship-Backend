<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\CategoryClick;
use Carbon\Carbon;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        
        if ($request->hasFile('pic')) {
            $picPath = $request->file('pic')->store('category_images', 'public');
            $category->pic = $picPath;
        }
        
        $category->save();

        return response()->json(['message' => 'Category created successfully']);
    }

    public function trackCategoryVisit(Request $request){
        $categoryId = $request->input('category_id'); 
        
        Category::where('id', $categoryId)->increment('count_visits');

        return response()->json(['message' => 'Category visit tracked successfully']);
    }

    public function displayCategoriesByVisits(Request $request) {
        $period = 24; // Period in hours
        // $period = $request->input('period'); 
        // $validPeriods = ['day', 'week', 'month', 'year'];
    
        // if (!in_array($period, $validPeriods)) {
        //     return response()->json(['error' => 'Invalid period value'], 400);
        // }

        $categories = Category::select('categories.*')
        ->selectRaw("SUM(CASE WHEN updated_at >= NOW() - INTERVAL $period HOUR THEN count_visits ELSE 0 END) AS total_visits_last_24hrs")
        ->orderByDesc('total_visits_last_24hrs')
        ->groupBy('categories.id', 'categories.name', 'categories.count_visits', 'categories.updated_at')
        ->get();

        if ($categories) {
            return response()->json($categories);
        } else {
            return response()->json(['error' => 'not found'], 404);
        }
    }
}
