<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    /**
     * 登录
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * 登录动作
     */
    public function store(Request $request)
    {
       $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
       ]);

       $credentials = [
           'email' => $request->email,
           'password' => $request->password
       ];

       if (Auth::attempt($credentials, $request->has('remember'))) {
            return redirect()->route('users.show', [Auth::user()])->with('success', '欢迎回来！');
       }

       return redirect()->back()->with('danger', '很抱歉，您的邮箱和密码不匹配');
    }

    /**
     * 退出登录
     */
    public function destroy()
    {
        Auth::logout();
        return redirect('login')->with('success', '退出成功！');
    }
}
