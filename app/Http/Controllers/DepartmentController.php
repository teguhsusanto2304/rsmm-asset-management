<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;

use function PHPUnit\Framework\isNull;

class DepartmentController extends Controller
{
    public function index(Request $request)
{
    $departments = Department::whereNull('parent_id')
        ->with('children')
        ->get();
    
    if ($request->filled('id')) {
        $selectedDepartment = Department::where('parent_id', $request->id);
    } else {
        $selectedDepartmentDir = Department::whereNull('parent_id')->first();
        // Safely handle empty root departments
        $selectedDepartment = $selectedDepartmentDir
            ? Department::where('parent_id', $selectedDepartmentDir->id)
            : Department::query()->whereRaw('1=0');
    }
    
    //dd($selectedDepartment);
    $subUnits = $selectedDepartment
        ? $selectedDepartment->get() 
        : collect();
    $selectedDepartment = $selectedDepartment ?? null;


    return view('admin.departments.index', compact(
        'departments',
        'selectedDepartment',
        'subUnits'
    ));
}


    public function create()
    {
        $parents = Department::all();
        $users   = User::all();

        return view('admin.departments.create', compact('parents','users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required',
            'user_id'    => 'required',
        ], [
            'user_id.required'    => 'Departemen/Unit harus memiliki penanggung jawab.',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')
            ->with('success','Department created');
    }

    public function edit(Department $department)
    {
        $parents = Department::where('id','!=',$department->id)->get();
        $users   = User::all();

        return view('admin.departments.edit', compact('department','parents','users'));
    }

    public function update(Request $request, Department $department)
    {
        $department->update($request->all());

        return redirect()->route('departments.index')
            ->with('success','Department updated');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return back()->with('success','Department deleted');
    }
}

