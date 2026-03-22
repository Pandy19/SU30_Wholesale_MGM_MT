<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MMO Wholesale: Supplier Registration Status</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eeeeee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        .header h1 {
            color: #333333;
            margin: 0;
            font-size: 24px;
        }
        .content {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999999;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php
                $logoUrl = asset('assets/dist/img/MMOLOGO.png');
                // If on localhost, emails might not show image. Using base64 or a public URL is better for production.
            @endphp
            <img src="{{ $logoUrl }}" alt="MMO Wholesale Logo" class="logo">
            <h1>MMO Wholesale</h1>
        </div>
        <div class="content">
            <p>Dear {{ $supplier->company_name }},</p>

            @if($status === 'approved')
                <p>We are pleased to inform you that your registration as a supplier with <strong>MMO Wholesale</strong> has been <strong>Approved</strong>.</p>
                <p>You can now log in to your account and start managing your products and orders.</p>
                <div style="text-align: center;">
                    <a href="{{ url('/login') }}" class="button" style="color: white;">Login to Your Account</a>
                </div>
            @else
                <p>Thank you for your interest in registering as a supplier with <strong>MMO Wholesale</strong>.</p>
                <p>After careful review, we regret to inform you that your application has been <strong>Denied</strong> at this time.</p>
                <p>If you have any questions or would like to provide more information, please feel free to contact our support team.</p>
            @endif

            <p>Best regards,<br>MMO Wholesale Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MMO Wholesale. All rights reserved.
        </div>
    </div>
</body>
</html>
