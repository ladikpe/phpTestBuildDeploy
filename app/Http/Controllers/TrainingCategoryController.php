<?php

namespace App\Http\Controllers;
use App\Http\Requests\TrainingCategoryRequest;
use App\TrainingCategory;
use Illuminate\Http\Request;

class TrainingCategoryController extends Controller
{
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingCategoryRequest $request)
    {
        try {
            $category = TrainingCategory::UpdateOrCreate(['id' => $request['category_id']],[
                'name'  => $request['name'],
                'description' => $request['description']
             ]);
            return response()->json(['data' => $category, 'status' => true, 'message' => 'Request successful.'], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'status' => false], 500);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = TrainingCategory::find($id);
            return response()->json(['data' => $category, 'status' => true, 'message' => 'Category retrieved successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'status' => false], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = TrainingCategory::find($id);
            $category->delete();
            return response()->json(['data' => $category, 'status' => true, 'message' => 'Category retrieved successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'status' => false], 500);
        }
    }
}
