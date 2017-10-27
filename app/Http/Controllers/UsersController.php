<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
    /**
     * 权限控制
     */
    public function __construct()
    {
        $this->middleware('auth', [            
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
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
        $statuses = $user->statuses()
                         ->orderBy('created_at', 'desc')
                         ->paginate(30);
        return view('users.show', compact('user', 'statuses'));
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
       
        $this->sendEmailConfirmationTo($user);
        return redirect('/')->with('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
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

    /**
     * 发送邮件
     */
    private function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'postmaster@iokvip.com';
        $name = 'Sunrise';
        $to = $user->email;
        $subject = '感谢注册Sample应用，请确认您的邮箱！';

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    /**
     * 激活邮箱
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        return redirect()->route('users.show', [$user])->with('success', '恭喜您，邮箱激活成功！');
    }


}
