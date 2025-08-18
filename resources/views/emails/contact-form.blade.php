<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #9333ea, #ec4899, #ef4444);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .field {
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
            display: block;
        }
        .value {
            background: #f9fafb;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #9333ea;
        }
        .message-box {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            margin-top: 10px;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎵 New Contact Form Submission</h1>
            <p>Someone reached out through your LoveSong contact form</p>
        </div>
        
        <div class="content">
            <div class="field">
                <span class="label">👤 Name:</span>
                <div class="value">{{ $contactData['name'] }}</div>
            </div>
            
            <div class="field">
                <span class="label">📧 Email:</span>
                <div class="value">{{ $contactData['email'] }}</div>
            </div>
            
            <div class="field">
                <span class="label">📋 Subject:</span>
                <div class="value">{{ ucfirst(str_replace('_', ' ', $contactData['subject'])) }}</div>
            </div>
            
            <div class="field">
                <span class="label">💌 Message:</span>
                <div class="message-box">
                    {!! nl2br(e($contactData['message'])) !!}
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>This message was sent via the LoveSong contact form at {{ now()->format('F j, Y \a\t g:i A T') }}</p>
            <p>You can reply directly to this email to respond to {{ $contactData['name'] }}</p>
        </div>
    </div>
</body>
</html>
