![LINE Login Create a channel.](https://i.imgur.com/U69W9YR.png)

# Line Login on Laravel
這是一個 Laravel 上使用 SocialiteProviders 的 Line 登入範例，以 [laravel-boilerplate](https://github.com/rappasoft/laravel-boilerplate) 作為延伸。

# 前置步驟
1. 建立一個 Laravel 專案，可以使用 [laravel-boilerplate](https://github.com/rappasoft/laravel-boilerplate) 來建立。
2. 引用 [SocialiteProviders/Line](https://github.com/SocialiteProviders/Line) 來安裝套件。

# 新增 Login with Line 按鈕
需要在 `config/services.php` 的地方去新增設定
```php
'line' => [
    'active' => env('LINE_ACTIVE', false),
    'client_id' => env('LINE_CLIENT_ID'),
    'client_secret' => env('LINE_CLIENT_SECRET'),
    'redirect' => env('LINE_REDIRECT_URI')
],
```

需要在 `social.blade.php` 的地方去新增登入元件
```php
<x-utils.link
    :href="route('frontend.auth.social.login', 'line')"
    class="btn btn-sm btn-outline-info m-1 mt-4"
    icon="fab fa-line"
    :text="__('Login with Line')"
    :hide="!config('services.line.active')" />
```

# 申請 Line Login
1. 你需要透過 [Line Developers](https://developers.line.biz) 來申請 LINE Login，`App types` 的地方需要包含 `Web app`。
2. 申請完畢後，將 `Channel ID` 與 `Channel secret` 回填至 `.env` 當中。
```env
LINE_ACTIVE=false
#LINE_CLIENT_ID=
#LINE_CLIENT_SECRET=
#LINE_REDIRECT_URI="${APP_URL}/login/line/callback"
```

# 參考文件
1. [rappasoft/laravel-boilerplate](https://github.com/rappasoft/laravel-boilerplate)
2. [SocialiteProviders/Line](https://github.com/SocialiteProviders/Line)
