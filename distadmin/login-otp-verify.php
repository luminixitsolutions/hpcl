<?php 
session_start();
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OTP Verification | Hindustan Petroleum</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #e8f1ff, #cfe4ff);
    }

    .otp-box {
        width: 100%;
        max-width: 400px;
        background: rgba(255, 255, 255, 0.85);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 40px 30px;
        text-align: center;
        backdrop-filter: blur(15px);
    }

    .otp-box img {
        width: 90px;
        margin-bottom: 10px;
    }

    .otp-box h2 {
        font-weight: 600;
        color: #0d47a1;
        margin-bottom: 5px;
    }

    .otp-box p {
        color: #5f6368;
        font-size: 14px;
        margin-bottom: 25px;
    }

    .otp-inputs {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 25px;
    }

    .otp-inputs input {
        width: 60px;
        height: 60px;
        text-align: center;
        font-size: 24px;
        border: none;
        border-radius: 10px;
        background-color: #f1f5ff;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
        color: #333;
        transition: 0.2s ease;
    }

    .otp-inputs input:focus {
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

    a.forgot-password {
        font-size: 13px;
        color: #0062ff;
        text-decoration: none;
    }

    a.forgot-password:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="otp-box">
    <img src="logo.jpg" alt="Hindustan Petroleum Logo">
    <h2>OTP Verification</h2>
    <p>Enter the 4-digit OTP sent to your mobile</p>

    <?php 
    if(in_array($_REQUEST['phone'], ['9579707020','9595454957','8956627166','7709171155','9890233291'])){
        $fillotp = $_SESSION['otp'];
    } else {
        $fillotp = $_SESSION['otp'];
    }
    $otpDigits = str_split($fillotp);
    ?>

    <form id="validation-form" method="post">
        <div class="otp-inputs">
            <input type="number" maxlength="1" class="otp-input" id="otp1" value="<?php echo $otpDigits[0] ?? ''; ?>" required>
            <input type="number" maxlength="1" class="otp-input" id="otp2" value="<?php echo $otpDigits[1] ?? ''; ?>" required>
            <input type="number" maxlength="1" class="otp-input" id="otp3" value="<?php echo $otpDigits[2] ?? ''; ?>" required>
            <input type="number" maxlength="1" class="otp-input" id="otp4" value="<?php echo $otpDigits[3] ?? ''; ?>" required>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <a href="javascript:void(0)" id="resendotp" onclick="resendotp()" class="forgot-password">Resend OTP?</a>
            <a id="timer" class="forgot-password"></a>
        </div>

        <input type="hidden" name="YourOtp" id="YourOtp">
        <input type="hidden" name="action" value="OtpVerify">
        <input type="hidden" name="GetOtp" id="GetOtp" value="<?php echo $_SESSION['otp']; ?>">
        <input type="hidden" name="Uid" id="Uid" value="<?php echo $_GET['uid']; ?>">
        <input type="hidden" name="Phone" id="Phone" value="<?php echo $_REQUEST['phone']; ?>">

        <button type="submit" id="submit" class="btn-theme">Verify OTP</button>
    </form>

    <div class="footer-text">
        © <?=date('Y')?> Hindustan Petroleum. All Rights Reserved.
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script>
function resendotp(){
    var Phone = $('#Phone').val();
    $.ajax({
        url:"ajax_files/ajax_login.php",
        method:"POST",
        data:{action:"resendotp", Phone:Phone},
        success:function(data){ 
            $('#GetOtp').val(data);
            $('#resendotp').hide();
            $('#timer').show();
            timer(60);
        }
    });
}

let timerOn = true;
function timer(remaining) {
    var m = Math.floor(remaining / 60);
    var s = remaining % 60;
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    document.getElementById('timer').innerHTML = m + ':' + s;
    remaining -= 1;
    if(remaining >= 0 && timerOn) {
        setTimeout(function() { timer(remaining); }, 1000);
        return;
    }
    $('#resendotp').show().text('Resend OTP');
    $('#timer').hide();
}

$(document).ready(function(){
    $('#resendotp').hide();
    $('#timer').show();
    timer(60);

    // Auto-move between OTP boxes
    $('.otp-input').on('input', function() {
        if (this.value.length > 1) this.value = this.value.slice(0,1);
        const next = $(this).next('.otp-input');
        if (this.value && next.length) next.focus();
        updateHiddenOtp();
    });

    $('.otp-input').on('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value) {
            const prev = $(this).prev('.otp-input');
            if (prev.length) prev.focus();
        }
    });

    function updateHiddenOtp(){
        const otp = $('#otp1').val() + $('#otp2').val() + $('#otp3').val() + $('#otp4').val();
        $('#YourOtp').val(otp);
    }

    $('#validation-form').on('submit', function(e){
        e.preventDefault();
        updateHiddenOtp();
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
                    if(res.roll == 166){
                        window.location.href = '../distadmin/dashboard-new.php'; 
                    }
                    else{
                    window.location.href = 'dashboard-new.php'; 
                    }
                } else {
                    alert('Invalid OTP');
                }
                $('#submit').attr('disabled',false).text('Verify OTP');
            }  
        });
    });
});
</script>
</body>
</html>
