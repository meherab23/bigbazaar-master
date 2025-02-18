<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attribute;

class AttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_attributes = Attribute::all();
        //dd($all_attributes);
        return view('admin.attributes.index', compact('all_attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        //dd(Auth::guard('admin')->user()->id);

        $validated = $request->validate([
            'attribute_name' => 'required',
        ]);

        $attribute_model = new Attribute();
        $attribute_model->attribute_name = $request->input('attribute_name');
        $attribute_model->attribute_value = json_encode($request->input('attribute_values'));
        $attribute_model->created_by = Auth::guard('admin')->user()->id;

        $attribute_model->save();

        // Alert::success('Attribute Created Successfully!', 'success');
        return redirect()->route('attributes.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $all_attributes = Attribute::where('attribute_row_id', $id)->first();
        //dd($all_attributes);
        return view('admin.attributes.edit', compact('all_attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request);

        $attribute_model = Attribute::findOrFail($request->input('attr_id'));

        $attribute_model->attribute_name = $request->input('attribute_name');
        $attribute_model->attribute_value = json_encode($request->input('attribute_values'));
        $attribute_model->created_by = Auth::guard('admin')->user()->id;

        $attribute_model->save();
        return redirect()->route('attributes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
