<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-top: 20px;
        }

        .email-header,
        .email-footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-content {
            margin: 20px 0;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Yêu Cầu Đặt Lại Mật Khẩu</h1>
        </div>
        <div class="email-content">
            <p>Bạn đã yêu cầu đặt lại mật khẩu. Nhấp vào nút dưới đây để đặt lại mật khẩu của bạn:</p>
            <div style="text-align: center; margin: 20px;">
                <a href="{{ $url }}" class="btn btn-primary">Đặt Lại Mật Khẩu</a>
            </div>
            <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
        </div>
        <div class="email-footer">
            <p>Xin cảm ơn!</p>
        </div>
    </div>
</body>

</html>
