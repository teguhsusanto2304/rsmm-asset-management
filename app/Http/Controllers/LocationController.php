<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locations = Location::whereNull('parent_id')
            ->with('children')
            ->get();
        
        if ($request->filled('id')) {
            $selectedLocation = Location::where('parent_id', $request->id);
        } else {
            $selectedLocationDir = Location::whereNull('parent_id')->get()->first();        
            $selectedLocation = Location::where('parent_id', $selectedLocationDir?->id);
        }
        
        //dd($selectedDepartment);
        $subUnits = $selectedLocation
            ? $selectedLocation->get() 
            : collect();
        $selectedLocation = $selectedLocation ?? null;


        return view('admin.locations.index', compact(
            'locations',
            'selectedLocation',
            'subUnits'
        ));
    }


    public function create()
    {
        $parents = Location::all();
        $locations = $parents;
        $departments = Department::all();
        $users   = User::all();

        return view('admin.locations.create', compact('locations','parents','users','departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Location::create($request->all());

        return redirect()->route('locations.index')
            ->with('success','Locasi telah dibuat');
    }

    public function edit(Location $location)
    {
        $parents = Location::where('id','!=',$location->id)->get();
        $locations = $parents;
        $departments = Department::all();
        $users   = User::all();


        return view('admin.locations.edit', compact('location','locations','departments','parents','users'));
    }

    public function update(Request $request, Location $location)
    {
        $location->update($request->all());

        return redirect()->route('locations.index')
            ->with('success','Lokasi telah diupdate');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return back()->with('success','Locasi telah dihapus');
    }
}
