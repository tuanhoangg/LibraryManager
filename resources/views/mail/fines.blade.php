<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Nộp Phạt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        .header {
            background-color: #dc3545;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border-radius: 0 0 0.375rem 0.375rem;
        }

        .content {
            padding: 20px;
        }

        .content p {
            margin: 0 0 10px;
        }

        .table th,
        .table td {
            border-top: none;
        }
    </style>
</head>
@php
    use App\Models\Fines;
    use App\Models\Roles;
    use App\Models\BorrowHistory;
@endphp

<body>
    <div class="email-container">
        <div class="header">
            <h1>Thông Báo Nộp Phạt</h1>
        </div>
        <div class="content">
            <p>Kính gửi {{ $data['user']['name'] }},</p>
            <p>Bạn đã vi phạm quy định của thư viện với thông tin cụ thể như sau:</p>
            <table class="table">
                <tr>
                    <th>Mã sách:</th>
                    <td>{{ $data['borrow']['book_code'] }}</td>
                </tr>
                <tr>
                    <th>Tên sách:</th>
                    <td>{{ $data['borrow']['book']['title'] }}</td>
                </tr>
                <tr>
                    <th>ISBN:</th>
                    <td>{{ $data['borrow']['isbn_code'] }}</td>
                </tr>
                <tr>
                    <th>Ngày mượn:</th>
                    <td>{{ date('d-m-Y', strtotime($data['borrow']['borrow_date'])) }}</td>
                </tr>
                <tr>
                    <th>Lý do phạt:</th>
                    <td>{{ Fines::STATUS_LIST[$data['fine_type']]['label'] }}</td>
                </tr>
                <tr>
                    <th>Số tiền phạt:</th>
                    <td>{{ number_format($data['amount'], 0, ',', '.') }} VNĐ</td>
                </tr>
            </table>
            <p>Vui lòng thanh toán số tiền phạt tại quầy thư viện trong thời gian sớm nhất để tránh ảnh hưởng đến quyền
                lợi của bạn.</p>
            <p>Xin cảm ơn sự hợp tác của bạn!</p>
        </div>
        <div class="footer">
            <p>&copy; 2023 Thư Viện. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>

</html>
