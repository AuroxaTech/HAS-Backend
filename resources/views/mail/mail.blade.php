
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property App</title>
</head>
<div>
    <div style="background-color:#f4f4f4;width:800px !important;margin: auto auto auto auto; height: 300px;">
        
        <div style="padding: 15px 30px 30px 30px;">
            <h1 style="color:#363636">Welcome to  (Project Name), Click continue to reset your account password.</h1>
            <p style="margin-bottom: 10px;color:#363636">We received a request to register with this email address.  simply click the link below. It will take you to a web page where you can reset passwordyour account.</p>
            <a href="{{ url(config('app.url').route('password.reset', ['token' => $mailData['token'], 'email' => $mailData['email']], false)) }}" style="padding: 10px 20px 10px 20px; background-color:#294e73;color: white;text-decoration: auto;">Reset Password</a>
        </div>
    </div>
</body>
</html>
