<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    /**
     * 只让未登录用户访问登录/注册页面
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }


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
            if (Auth::user()->activated) {
                return redirect()->intended(route('users.show', [Auth::user()]))->with('success', '欢迎回来！');
            }

            Auth::logout();
            return redirect('/')->with('warning', '您的帐号未激活，请检查邮箱中的注册邮件进行激活。');
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
