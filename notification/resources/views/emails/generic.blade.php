<!-- resources/views/emails/generic.blade.php -->
<!-- Last updated: 2025-02-06 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007BFF;
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $subject ?? 'Notification' }}</h1>
        </div>
        <div class="content">
            {!! $body !!}
            <p style="text-align: center;">
                <a href="#" class="btn">Call to Action</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} My Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
