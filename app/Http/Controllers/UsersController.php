<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    /**
     * 权限控制
     */
    public function __construct()
    {
        $this->middleware('auth', [            
            'except' => ['show', 'create', 'store', 'index']
        ]);

        // 只让未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 用户列表
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * 注册action
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 用户详情
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 保存用户
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6|max:16',
            'password_confirmation' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        
        Auth::login($user);
        return redirect()->route('users.show', [$user])->with('success', '欢迎，您将在这里开启一段新的旅程～');
    }

    /**
     * 编辑用户
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * 更新用户资料
     */
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'password' => 'nullable|confirmed|min:6|max:16'
        ]);

        $this->authorize('update', $user);
        
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = $request->password;
        }
        $user->update($data);

        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

    /**
     * 删除用户
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy, $user');
        $user->delete();
        return redirect()->back()->with('success', '删除成功！');
    }

}
