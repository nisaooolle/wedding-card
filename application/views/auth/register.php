<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>RegistrationForm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- sweetalert2 -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
    <meta name="robots" content="noindex, follow">
    <script
        nonce="52a23caf-9164-4906-8eef-4074a1e45b9a">(function (w, d) { !function (dp, dq, dr, ds) { dp[dr] = dp[dr] || {}; dp[dr].executed = []; dp.zaraz = { deferred: [], listeners: [] }; dp.zaraz.q = []; dp.zaraz._f = function (dt) { return async function () { var du = Array.prototype.slice.call(arguments); dp.zaraz.q.push({ m: dt, a: du }) } }; for (const dv of ["track", "set", "debug"]) dp.zaraz[dv] = dp.zaraz._f(dv); dp.zaraz.init = () => { var dw = dq.getElementsByTagName(ds)[0], dx = dq.createElement(ds), dy = dq.getElementsByTagName("title")[0]; dy && (dp[dr].t = dq.getElementsByTagName("title")[0].text); dp[dr].x = Math.random(); dp[dr].w = dp.screen.width; dp[dr].h = dp.screen.height; dp[dr].j = dp.innerHeight; dp[dr].e = dp.innerWidth; dp[dr].l = dp.location.href; dp[dr].r = dq.referrer; dp[dr].k = dp.screen.colorDepth; dp[dr].n = dq.characterSet; dp[dr].o = (new Date).getTimezoneOffset(); if (dp.dataLayer) for (const dC of Object.entries(Object.entries(dataLayer).reduce(((dD, dE) => ({ ...dD[1], ...dE[1] })), {}))) zaraz.set(dC[0], dC[1], { scope: "page" }); dp[dr].q = []; for (; dp.zaraz.q.length;) { const dF = dp.zaraz.q.shift(); dp[dr].q.push(dF) } dx.defer = !0; for (const dG of [localStorage, sessionStorage]) Object.keys(dG || {}).filter((dI => dI.startsWith("_zaraz_"))).forEach((dH => { try { dp[dr]["z_" + dH.slice(7)] = JSON.parse(dG.getItem(dH)) } catch { dp[dr]["z_" + dH.slice(7)] = dG.getItem(dH) } })); dx.referrerPolicy = "origin"; dx.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(dp[dr]))); dw.parentNode.insertBefore(dx, dw) };["complete", "interactive"].includes(dq.readyState) ? zaraz.init() : dp.addEventListener("DOMContentLoaded", zaraz.init) }(w, d, "zarazData", "script"); })(window, document);</script>
    <style>
        @font-face {
            font-family: elmessiri-semibold;
            src: url(../fonts/el_messiri/ElMessiri-SemiBold.ttf)
        }

        @font-face {
            font-family: montserrat-regular;
            src: url(../fonts/montserrat/Montserrat-Regular.ttf)
        }

        @font-face {
            font-family: montserrat-semibold;
            src: url(../fonts/montserrat/Montserrat-SemiBold.ttf)
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box
        }

        body {
            font-family: montserrat-regular;
            color: #999;
            font-size: 12px;
            margin: 0
        }

        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        ul {
            margin: 0
        }

        img {
            max-width: 100%
        }

        ul {
            padding-left: 0;
            margin-bottom: 0
        }

        a {
            text-decoration: none;
            color: #ff9a9c;
            transition: all .3s ease
        }

        a:hover {
            text-decoration: none;
            color: #F1B4BB
        }

        :focus {
            outline: none
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: url(../images/bg-registration-form-4.jpg) no-repeat;
            background-size: cover
        }

        .inner {
            max-width: 850px;
            margin: auto;
            background: #fff;
            display: flex;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, .2);
            -webkit-box-shadow: 0 0 10px 0 rgba(0, 0, 0, .2);
            -moz-box-shadow: 0 0 10px 0 rgba(0, 0, 0, .2);
            -ms-box-shadow: 0 0 10px 0 rgba(0, 0, 0, .2);
            -o-box-shadow: 0 0 10px 0 rgba(0, 0, 0, .2)
        }

        .image-holder {
            width: 50%;
            padding-right: 15px
        }

        form {
            width: 50%;
            padding-top: 77px;
            padding-right: 60px;
            padding-left: 15px
        }

        h3 {
            font-size: 35px;
            font-family: elmessiri-semibold;
            text-align: center;
            margin-bottom: 27px;
            color: #ff9a9c
        }

        .form-holder {
            padding-left: 24px;
            position: relative;
        }

        .form-holder:before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #ff9a9c;
            position: absolute;
            left: 1px;
            top: 50%;
            transform: translateY(-50%)
        }

        .form-holder.active:before {
            border: 2px solid transparent;
            background: #ff9a9c
        }

        .form-control {
            display: block;
            width: 100%;
            border-radius: 23.5px;
            height: 47px;
            padding: 0 24px;
            color: gray;
            font-size: 13px;
            border: none;
            background: #f7f7f7;
            margin-bottom: 25px
        }

        .form-control::-webkit-input-placeholder {
            font-size: 13px;
            color: gray;
            text-transform: uppercase;
            font-family: montserrat-regular
        }

        .form-control::-moz-placeholder {
            font-size: 13px;
            color: gray;
            text-transform: uppercase;
            font-family: montserrat-regular
        }

        .form-control:-ms-input-placeholder {
            font-size: 13px;
            color: gray;
            text-transform: uppercase;
            font-family: montserrat-regular
        }

        .form-control:-moz-placeholder {
            font-size: 13px;
            color: gray;
            text-transform: uppercase;
            font-family: montserrat-regular
        }

        @-webkit-keyframes hvr-wobble-horizontal {
            16.65% {
                -webkit-transform: translateX(8px);
                transform: translateX(8px)
            }

            33.3% {
                -webkit-transform: translateX(-6px);
                transform: translateX(-6px)
            }

            49.95% {
                -webkit-transform: translateX(4px);
                transform: translateX(4px)
            }

            66.6% {
                -webkit-transform: translateX(-2px);
                transform: translateX(-2px)
            }

            83.25% {
                -webkit-transform: translateX(1px);
                transform: translateX(1px)
            }

            100% {
                -webkit-transform: translateX(0);
                transform: translateX(0)
            }
        }

        @keyframes hvr-wobble-horizontal {
            16.65% {
                -webkit-transform: translateX(8px);
                transform: translateX(8px)
            }

            33.3% {
                -webkit-transform: translateX(-6px);
                transform: translateX(-6px)
            }

            49.95% {
                -webkit-transform: translateX(4px);
                transform: translateX(4px)
            }

            66.6% {
                -webkit-transform: translateX(-2px);
                transform: translateX(-2px)
            }

            83.25% {
                -webkit-transform: translateX(1px);
                transform: translateX(1px)
            }

            100% {
                -webkit-transform: translateX(0);
                transform: translateX(0)
            }
        }

        button {
            letter-spacing: 2px;
            border: none;
            width: 133px;
            height: 47px;
            margin-right: 19px;
            border-radius: 23.5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: #ff9a9c;
            font-size: 15px;
            color: #fff;
            text-transform: uppercase;
            font-family: montserrat-semibold;
            -webkit-transform: perspective(1px) translateZ(0);
            transform: perspective(1px) translateZ(0);
            box-shadow: 0 0 1px transparent
        }

        button:hover {
            -webkit-animation-name: hvr-wobble-horizontal;
            animation-name: hvr-wobble-horizontal;
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-timing-function: ease-in-out;
            animation-timing-function: ease-in-out;
            -webkit-animation-iteration-count: 1;
            animation-iteration-count: 1
        }

        .checkbox {
            position: relative;
            padding-left: 19px;
            margin-bottom: 37px;
            margin-left: 26px
        }

        .checkbox label {
            cursor: pointer;
            color: #999
        }

        .checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer
        }

        .checkbox input:checked~.checkmark:after {
            display: block
        }

        .checkmark {
            position: absolute;
            top: 2px;
            left: 0;
            height: 10px;
            width: 10px;
            border-radius: 50%;
            border: 1px solid #e7e7e7
        }

        .checkmark:after {
            content: "";
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #ff9a9c;
            position: absolute;
            display: none
        }

        .form-login {
            display: flex;
            align-items: center;
            margin-left: 23px
        }

        @media(max-width:767px) {
            .inner {
                display: block
            }

            .image-holder {
                width: 100%;
                padding-right: 0
            }

            form {
                width: 100%;
                padding: 0 15px 70px
            }

            .wrapper {
                background: 0 0
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="inner">
            <div class="image-holder">
                <img src="https://colorlib.com/etc/regform/colorlib-regform-20/images/registration-form-4.jpg" alt>
            </div>
            <form action="<?php echo base_url('Auth/aksi_register') ?>" method="post">
                <h3>Sign Up</h3>
                <div class="form-holder active">
                    <input type="text" placeholder="username" class="form-control" name="username" required
                        value="<?php echo set_value('username') ?>">
                    <?= form_error('username', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="form-holder">
                    <input type="text" placeholder="e-mail" class="form-control" required name="email"
                        value="<?php echo set_value('email'); ?>">
                    <?= form_error('email', '<smal class="text-danger pl-3">', '</smal>'); ?>
                </div>
                <div class="form-holder">
                    <input type="password" placeholder="Password" class="form-control" style="font-size: 15px;" required
                        name="password" value="<?php echo set_value('password'); ?>">
                    <?= form_error('password', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" class="form-control form-control-lg bg-light fs-6" name="role"
                        placeholder="Role" value="user">
                </div>
                <p style="font-size:13px">* Password Harus Minimal 8 Karakter Ada Huruf Besar & Kecil</p>
                <br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="show-password" checked>Tampilkan
                        Password
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="form-login">
                    <button type="submit">Sign up</button>
                    <p>already have an account? <a href="/wedding-card/auth">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <!-- script hide dan show password -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var passwordField = document.getElementById('password');
            var showPasswordCheckbox = document.getElementById('show-password');

            showPasswordCheckbox.addEventListener('change', function () {
                if (showPasswordCheckbox.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });
        });
    </script>

    <!-- sweet alert register jika error -->
    <?php if ($this->session->flashdata('error_message')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $this->session->flashdata('error_message') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika email sudah ada -->
    <?php if ($this->session->flashdata('error_email')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $this->session->flashdata('error_email') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika username sudah ada -->
    <?php if ($this->session->flashdata('error_username')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $this->session->flashdata('error_username') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>
    <!-- end sweet alert jika error -->

    <!-- sweet alert jika berhasil register -->
    <?php if ($this->session->flashdata('Berhasil_register_user')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Horee...',
                text: '<?= $this->session->flashdata('Berhasil_register_user') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script> $(function () {
            $('.form-holder').delegate("input", "focus", function () {
                $('.form-holder').removeClass("active");
                $(this).parent().addClass("active");
            });
        });</script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317"
        integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA=="
        data-cf-beacon='{"rayId":"84292004aaf22212","version":"2023.10.0","token":"cd0b4b3a733644fc843ef0b185f98241"}'
        crossorigin="anonymous"></script>
</body>

</html>