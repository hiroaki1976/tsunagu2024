<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    // ログイン画面呼び出し
    public function showLoginPage(): View
    {
        return view('admin.auth.login');
    }

    // ログイン実行
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard')->with([
                'login_msg' => 'ログインしました。',
            ]);
        }

        return back()->withErrors([
            'login' => ['ログインに失敗しました'],
        ]);
    }

    // ログアウト実行
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            // 管理者用のログアウト処理
            Auth::guard('admin')->logout();
            return to_route('admin.login'); // 管理者用ログインページへリダイレクト
        } else {
            // 一般ユーザー用のログアウト処理
            Auth::guard('web')->logout();
            return to_route('login'); // 一般ユーザー用ログインページへリダイレクト
        }
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}