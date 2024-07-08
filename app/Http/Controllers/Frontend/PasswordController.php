<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public $folderLink = 'frontend.change-password.';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->folderLink . 'index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $validation = [
            'current_password' => 'required',
            'new_password' => 'required',
            'retype_password' => 'required|same:new_password',
        ];
        $messages = [
            'current_password.required' => 'Please enter Current Password',
            'new_password.required' => 'Please enter New Password',
            'retype_password.required' => 'Please enter Re-type Password'
        ];
        $validator = Validator::make($request->all(), $validation, $messages);
        $validator->after(function ($validator) use ($request) {
            if ($request->has('current_password') && !Hash::check($request->current_password, \Auth::user()->password)) {
                $validator->errors()->add('current_password', 'Current password not valid');
            }
        });
        if ($validator->fails()) {
            $validator->validate();
        }

        $profileData['password'] = bcrypt($request->new_password);
        $id = auth()->user()->id;

        $password = User::where('id',$id)->update($profileData);
        if ($password) {
            $response = ['status' => true, 'message' => 'Updated Successfully', 'redirect' => route('change-password.index')];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }
        return response()->json($response);
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
}
