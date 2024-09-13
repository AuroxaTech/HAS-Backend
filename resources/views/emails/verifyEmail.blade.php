<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
        }
        .email-container {
            background-color: #ffffff;
            margin: 0 auto;
            max-width: 600px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #226DFF;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }
        .email-body p {
            margin: 0 0 15px;
        }
        .verify-button {
            display: block;
            background-color: #226DFF;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin: 20px auto 0;
            width: fit-content;
            text-align: center;
        }
        .email-body .post-button-text {
            
            padding-top: 20px;
            color: #555555;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #888888;
        }
        .email-footer p {
            margin: 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100%;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="email-header">
            <h1>Verify Your Email Address</h1>
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <p>Hello, {{ $user->fullname }}</p>
            <p>Please click the button below to verify your email address:</p>

            <!-- Centered Verify Button -->
            <a href="{{ $link }}" class="verify-button">Verify Email</a>

            <!-- Text below the button with padding -->
            <div class="post-button-text">
                <p>If you did not create an account, no further action is required.</p>
            </div>
        </div>

        <!-- Email Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
