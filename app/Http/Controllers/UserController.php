<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('superadmin');
        return view('dashboard.superAdmin.user', [
            'users' => User::where('id', '!=', auth()->user()->id)->get()
        ]);
    }

    public function userTable()
    {
        return DataTables::of(User::where('id', '!=', auth()->user()->id)->orderBy('id')->get())
            ->addIndexColumn()
            ->addColumn('name', function(User $user){
                return $user->name;
            })
            ->addColumn('username', function(User $user){
                return $user->username;
            })
            ->addColumn('action', function(User $row){
                $btn = '<button type="button" class="btn btn-primary btn-sm aksi" data-toggle="modal" data-bs-target="#modalEdit" data-id="'.$row->id.'" data-name="'.$row->name.'" data-username="'.$row->username.'"><ion-icon name="create-outline"></ion-icon> Edit</button>';
                $btn .= '<button type="button" class="btn btn-danger btn-sm ms-2 aksi deleteUser" data-id="'.$row->id.'"><ion-icon name="trash-outline"></ion-icon> Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create(Request $request)
    {
        $this->authorize('superadmin');
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username'
        ]);

        $password = '';
        if ($request->password == null) {
            $password = $request->username;
        }
        else {
            $password = $request->password;
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($password),
            'is_reviewer' => true
        ]);

        // return redirect()->back()->with('success', 'User Successfully Created');
    }

    public function update(Request $request)
    {
        $this->authorize('superadmin');
        $user = User::find($request->user_id);
        $rules = ([
            'name' => 'required'
        ]);

        if ($user->username != $request->username) {
            $rules['username'] = 'required|unique:users,username';
        }

        $validatedData = $request->validate($rules);
        if ($request->password == null) {
            $validatedData['password'] = $user->password;
        }
        else {
            $validatedData['password'] = bcrypt($request->password);
        }

        User::where('id', $request->user_id)->update($validatedData);

        return redirect()->back()->with('success', 'User Successfully Updated');
    }

    public function delete(Request $request)
    {
        $this->authorize('superadmin');
        $user = User::with(['project_user' => function($query){
            $query->where('user_role', 'admin');
        }])->where('id', $request->id)->first();

        if ($user->project_user->count() > 0) {
            $string = 'User cannot be deleted because user is an admin in a project';
            return json_encode(['error' => $string]);
        }
        else {
            return $user->delete();
            // return json_encode(['success' => 'User Successfully Deleted']);
        }
    }
}
