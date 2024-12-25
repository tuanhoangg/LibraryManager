<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Minh Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .email-header {
            background: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header img {
            max-width: 150px;
            height: auto;
        }

        .email-content {
            padding: 20px;
            text-align: center;
        }

        .email-content h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .email-content p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .email-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
        }

        .email-footer {
            background: #f2f2f2;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-content">
            <h1>Xác Minh Địa Chỉ Email Của Bạn</h1>
            <p>Chúng tôi đã nhận được yêu cầu xác minh địa chỉ email của bạn. Để hoàn tất quá trình, vui lòng nhấp vào
                nút dưới đây:</p>
            <a href="{{ $url }}" class="email-button">Xác Minh Email</a>
            <p>Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.</p>
        </div>
        <div class="email-footer">
            <p>© 2024 Quản lý thư viện.</p>
        </div>
    </div>
</body>

</html>
