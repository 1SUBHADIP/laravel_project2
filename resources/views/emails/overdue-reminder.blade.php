<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Book Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1f6feb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e1e5e9;
        }
        .book-details {
            background-color: white;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #1f6feb;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .fee-info {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e5e9;
            color: #6c757d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #1f6feb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 Library Overdue Notice</h1>
        <p>CCLMS Library Management System</p>
    </div>
    
    <div class="content">
        <h2>Dear {{ $memberName }},</h2>
        
        <p>This is a friendly reminder that you have an overdue book that needs to be returned to the library.</p>
        
        <div class="book-details">
            <h3>📖 Book Details:</h3>
            <p><strong>Title:</strong> {{ $bookTitle }}</p>
            <p><strong>Author:</strong> {{ $author }}</p>
            <p><strong>Loan Date:</strong> {{ $loanDate->format('M d, Y') }}</p>
            <p><strong>Due Date:</strong> {{ $dueDate->format('M d, Y') }}</p>
        </div>
        
        <div class="warning">
            <strong>⚠️ Overdue Information:</strong><br>
            This book is <strong>{{ $daysOverdue }} days overdue</strong>. Please return it as soon as possible to avoid additional late fees.
        </div>
        
        @if($lateFee > 0)
        <div class="fee-info">
            <strong>💰 Late Fee:</strong><br>
            Current late fee: <strong>${{ number_format($lateFee, 2) }}</strong><br>
            <small>Late fees continue to accrue at $1.00 per day until the book is returned.</small>
        </div>
        @endif
        
        <h3>📍 How to Return:</h3>
        <ul>
            <li>Visit the library during operating hours</li>
            <li>Use the book drop box if the library is closed</li>
            <li>Contact us if you need to renew the loan</li>
        </ul>
        
        <h3>📞 Contact Information:</h3>
        <p>
            <strong>Phone:</strong> +91 8167518159<br>
            <strong>Email:</strong> library@cclms.edu<br>
            <strong>Hours:</strong> Monday-Friday 10:00 AM - 5:00 PM
        </p>
        
        <p>If you have already returned this book, please disregard this notice. If you have any questions or concerns, please contact us immediately.</p>
        
        <p>Thank you for your cooperation!</p>
        
        <p>Best regards,<br>
        <strong>CCLMS Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from CCLMS Library Management System.</p>
        <p>Please do not reply to this email. Contact the library directly for assistance.</p>
    </div>
</body>
</html>
