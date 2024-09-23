<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Success</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            background-color: #ffffff;
            margin: 50px auto;
            padding: 40px;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .container:hover {
            transform: scale(1.02);
        }
        h1 {
            color: #28a745;
            font-size: 28px;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
            margin-top: 10px;
        }
        .button {
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #218838; /* Darker shade on hover */
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Success!</h1>
        <p>{{ $message }}</p>
    </div>
</body>
</html>
