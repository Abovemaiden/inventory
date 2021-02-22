<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:employees|max:255',
            'email' => 'required|unique:employees|email',
            'phone' => 'required|unique:employees',
            'address' => 'required',
            'salary' => 'required',
        ]);

        if ($request->photo) {
            $employee_image_path = public_path() . '\backend\employee';

            if (!File::exists($employee_image_path)) {
                Log::info('Creating directory:', $employee_image_path);
                File::makeDirectory($employee_image_path, 0777, true, true);
            }

            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $timestamp = time() . '.' . $ext;
            $img = Image::make($request->photo)->resize(300, 200);
            $upload_path = 'backend/employee/';
            $image_url = $upload_path . $timestamp;
            $img->save($image_url);

            $employees = $this->insertEmployee($request, $image_url);
            return response()->json($employees);
        } else {
            $employees = $this->insertEmployee($request, null);
            return response()->json($employees);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Helper when inserting an employee
     */
    private function insertEmployee(Request $request, $image_url = null)
    {
        $employee = new Employee();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->salary = $request->salary;
        $employee->address = $request->address;
        $employee->number_id = $request->number_id;
        $employee->joining_date = $request->joining_date;

        if ($image_url != null) {
            $employee->photo = $image_url;
            $employee->save();
            return $employee;
        } else {
            $employee->save();
            return $employee;
        }
    }
}
