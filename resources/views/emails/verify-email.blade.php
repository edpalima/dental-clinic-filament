<!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333333;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #061c34; /* Primary color */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        .header h1 {
            margin: 0;
        }

        .content {
            padding: 30px;
            line-height: 1.8;
            font-size: 16px;
            color: #555555;
        }

        .content p {
            margin-bottom: 20px;
        }

        .content a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #061c34; /* Primary color */
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .content a:hover {
            background-color: #0a2c52; /* Slightly darker shade */
        }

        .footer {
            text-align: center;
            padding: 10px 0;
            font-size: 12px;
            color: #666666;
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Email Verification</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>We’re excited to have you on board! Please click the button below to verify your email address and complete your registration:</p>
            <a href="{{ $link }}">Confirm Email</a>
            <p>If the button doesn’t work, you can copy and paste the following link into your browser:</p>
            <p><a href="{{ $link }}" style="color: #fff; word-break: break-all; font-size: 14px;">{{ $link }}</a></p>
            <p>If you didn’t register for this account, feel free to disregard this email.</p>
            <p>Best regards,</p>
            <p>The Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Almoro Santiago Dental. All rights reserved.
        </div>
    </div>
</body>

</html>
