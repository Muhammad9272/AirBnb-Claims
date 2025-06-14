<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $gs->name }}</title>
    <style>
        /* Email styles */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
        }
        .email-header {
            background: #7c3aed;
            padding: 20px;
            text-align: center;
        }
        .email-logo {
            max-width: 150px;
        }
        .email-content {
            padding: 30px 20px;
        }
        .email-footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            background: #7c3aed;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('assets/images/'.$gs->logo) }}" alt="{{ $gs->name }}" class="email-logo">
        </div>
        
        <div class="email-content">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ $gs->name }}. All rights reserved.</p>
            <p>
                If you have any questions, please contact us at 
                <a href="mailto:{{ $gs->site_email }}">{{ $gs->site_email }}</a>
            </p>
        </div>
    </div>
</body>
</html>
