<?php 
session_start();
include_once 'config.php';
echo $_COOKIE["member_login"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Hindustan Petroleum</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Background animation */
    body {
        font-family: 'Poppins', sans-serif;
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        background: linear-gradient(135deg, #e9f2ff, #cfdfff);
    }

    .background {
        position: fixed;
        width: 100%;
        height: 100%;
        overflow: hidden;
        top: 0; left: 0;
        z-index: -1;
    }

    .background span {
        position: absolute;
        width: 120px;
        height: 120px;
        background: rgba(0, 102, 255, 0.08);
        border-radius: 50%;
        animation: float 12s ease-in-out infinite alternate;
    }

    .background span:nth-child(1) { top: 15%; left: 10%; animation-delay: 0s; }
    .background span:nth-child(2) { top: 60%; left: 20%; animation-delay: 2s; }
    .background span:nth-child(3) { top: 30%; right: 15%; animation-delay: 4s; }
    .background span:nth-child(4) { bottom: 10%; right: 10%; animation-delay: 6s; }

    @keyframes float {
        0% { transform: translateY(0px) scale(1); opacity: 0.6; }
        100% { transform: translateY(-40px) scale(1.1); opacity: 0.9; }
    }

    /* Login Box */
    .login-box {
        width: 100%;
        max-width: 400px;
        background: rgba(255, 255, 255, 0.85);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 40px 30px;
        text-align: center;
        backdrop-filter: blur(15px);
        transition: all 0.3s ease;
        animation: fadeIn 1s ease;
    }

    .login-box:hover {
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
    }

    .login-box img {
        width: 90px;
        margin-bottom: 10px;
    }

    .login-box h2 {
        font-weight: 600;
        color: #0d47a1;
    }

    .login-box p {
        color: #5f6368;
        font-size: 14px;
        margin-bottom: 25px;
    }

    .form-control {
        border: none;
        border-radius: 10px;
        padding: 12px 15px;
        background-color: #f1f5ff;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
        color: #333;
    }

    .form-control:focus {
        outline: none;
        background-color: #e8efff;
        box-shadow: 0 0 0 2px #0d6efd30;
    }

    .btn-theme {
        background: linear-gradient(90deg, #0062ff, #2a9df4);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 16px;
        padding: 12px;
        width: 100%;
        transition: 0.3s;
        font-weight: 500;
    }

    .btn-theme:hover {
        background: linear-gradient(90deg, #2a9df4, #0062ff);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 98, 255, 0.3);
    }

    .footer-text {
        margin-top: 25px;
        font-size: 13px;
        color: #7b8ab8;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>

<!-- Floating Animated Background -->
<div class="background">
    <span></span><span></span><span></span><span></span>
</div>

<!-- Toast Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Hello!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Login Box -->
<div class="login-box">
    <img src="logo.jpg" alt="Hindustan Petroleum Logo">
    <h2>Welcome Back 👋</h2>
    <p>Sign in to continue to your dashboard</p>

    <form id="validation-form" method="post">
        <div class="mb-3">
            <input type="number" name="Username" id="Username" class="form-control" placeholder="Enter Mobile Number" required>
        </div>
        <input type="hidden" name="action" value="Login">
        <button type="submit" id="submit" class="btn-theme">Login</button>
    </form>

    <div class="footer-text">
        © <?=date('Y')?> Hindustan Petroleum. All Rights Reserved.
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showToast(message, color='primary') {
    const toastEl = document.getElementById('liveToast');
    const toastBody = document.getElementById('toastMessage');
    toastBody.textContent = message;
    toastEl.className = `toast align-items-center text-white bg-${color} border-0`;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

$(document).ready(function(){
    $('#validation-form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url :"ajax_files/ajax_login.php",  
            method:"POST",  
            data:new FormData(this),  
            contentType:false,  
            processData:false,  
            beforeSend:function(){
                $('#submit').attr('disabled','disabled').text('Please Wait...');
            },
            success:function(data){ 
                res = JSON.parse(data);
                if(res.Status == 1){
                    showToast('OTP sent successfully to your mobile.', 'success');
                    setTimeout(function(){  
                        window.location.href = "login-otp-verify.php?phone="+res.Username+"&uid="+res.uid;
                    }, 1200);
                } else {
                    showToast('Invalid login details.', 'danger');
                }
                $('#submit').attr('disabled',false).text('Login');
            }
        });
    });
});
</script>

</body>
</html>
