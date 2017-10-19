<?php

namespace App\Http\Controllers;

use Mail;

class MailController extends Controller
{
    public function mail()
    {
//        Mail::raw('测试邮件', function ($message) {
//            $message->from('postmaster@iokvip.com', '慕课网');
//            $message->subject('邮件主题');
//            $message->to('oksvip@qq.com');
//        });
//
//        Mail::send('mail', ['name' => 'wt'], function($message){
//            $message->to('oksvip@qq.com');
//        });
    }
}