<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => 'Log In'])
    @include('layouts.shared/head-css')
    @vite(['resources/scss/icons.scss'])
</head>

<body class="loading auth-fluid-pages pb-0">

    <div class="auth-fluid">


        <!-- Auth fluid right content -->
        <div class="auth-fluid-right ">
            <div class="auth-user-testimonial" style="margin: 10vh;">
                <h2 class="mb-3 text-white text-center"> Important Instructions</h2>
                <p class="lead" >
                        <li style="font-size: 20px;">Password should be at least 8 characters long, including one lowercase letter, one uppercase letter, one number, and one special character from @$!%*?&</li>

                        <li style="font-size: 20px;"><span> DO NOT</span> provide your username and password anywhere other than in this page</li>

                        <li style="font-size: 20px;"> Your username and password are highly confidential. <span>NEVER</span> part with them. IFCI will never ask for this information</li>

                        <li style="font-size: 20px;"><span>ALWAYS</span> visit the portal directly instead of clicking on the links provided in emails or third party websites</li>

                        <li style="font-size: 20px;"><span>NEVER</span> respond to any popup,email, SMS or phone call, no matter how appealing or official looking, seeking your personal information such as username, password(s), mobile number, etc. Such communications are sent or created by fraudsters to trick you into parting with your credentials</li>

                </p>
                <h5 class="text-white">
                    <!-- -  ESG-PRAKRIT Admin User -->
                </h5>
            </div> <!-- end auth-user-testimonial-->
        </div>
        <!-- end Auth fluid right content -->

        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="align-items-center d-flex h-100">
                <div class="card-body">

                    <!-- Logo -->
                    <div class="auth-brand text-center text-lg-start">
                        <div class="auth-brand">
                            <a href="" class="logo logo-dark text-center">
                                <span class="logo-lg">
                                    <img src="/images/logo/home-logo2.png" alt="" height="60">
                                </span>
                            </a>

                            <a href="" class="logo logo-light text-center">
                                <span class="logo-lg">
                                    <img src="/images/logo/home-logo2.png" alt="" height="60">
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- title-->
                    <h4 class="mt-0">Bank Sign In</h4>
                    <p class="text-muted mb-4">Enter your Username and password to access account.</p>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            <br>
                        @endif
                        @if (session('success'))
                            <div class=" alert alert-success">{{ session('success') }}
                            </div>
                            <br>
                        @endif

                        @if (sizeof($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                    <!-- form -->
                     <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                            @csrf
                            <input class="form-control" type="hidden" name="user_type" id="user_type" readonly value="bank">
                        <div class="mb-3">
                            <label for="emailaddress" class="form-label">Username</label>
                              <input id="identity" type="text"
                class="form-control @if ($errors->has('unique_login_id')) {{ $errors->has('unique_login_id') ? ' is-invalid' : '' }} @elseif($errors->has('email')) {{ $errors->has('email') ? ' is-invalid' : '' }} @endif" name="identity" value="{{ old('identity') }}"
                placeholder="" required autofocus>

                        </div>
                        <div class="mb-3">
                            <a href="" class="text-muted float-end"><small>Forgot password?</small></a>
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">

                     <input id="password" type="password"
                class="form-control @error('password') is-invalid @enderror" name="password"
                placeholder="" required autocomplete="off">







                                <div class="input-group-text" data-password="true">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                            </div>
                        </div>
                        <div class="text-center d-grid">
                            <button class="btn btn-primary" type="submit">Log In </button>
                        </div>


                    </form>
                    <!-- end form-->

                    <!-- Footer-->
                    <footer class="footer footer-alt">
                        <p class="text-muted">Don't have an account? <a href="" class="text-muted ms-1"><b>Sign Up</b></a></p>
                    </footer>

                </div> <!-- end .card-body -->
            </div> <!-- end .align-items-center.d-flex.h-100-->
        </div>
        <!-- end auth-fluid-form-box-->

    </div>
    <!-- end auth-fluid-->

    @vite('resources/js/pages/auth.js')

      <script src="{{ asset('asset/js/landing/crypto-js.min.js') }}"></script>
    <script src="{{ asset('asset/js/landing/aes.min.js') }}"></script>
    <script>
        var a = sessionStorage.getItem('my_session');
        var assignment = document.getElementById("user_type").value = a;

    </script>

    <script>
        document.querySelector('#loginForm').addEventListener('submit', (e) => {
            e.preventDefault();
            var id = document.getElementById("identity");
            var pwd = document.getElementById("password");

            var key = CryptoJS.enc.Hex.parse("0123456789abcdef0123456789abcdef");
            var iv = CryptoJS.enc.Hex.parse("abcdef9876543210abcdef9876543210");


            var encId = CryptoJS.AES.encrypt(id.value, key, {
                iv,
                padding: CryptoJS.pad.ZeroPadding,
            });
            var encPwd = CryptoJS.AES.encrypt(pwd.value, key, {
                iv,
                padding: CryptoJS.pad.ZeroPadding,
            });


            id.value = encId.toString();
            pwd.value = encPwd.toString();
            var loginForm = document.getElementById("loginForm");
            loginForm.submit();
        });
    </script>
</body>

</html>
