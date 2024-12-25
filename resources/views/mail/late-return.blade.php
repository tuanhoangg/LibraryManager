<!-- resources/views/emails/borrowNotification.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Mượn Sách</title>
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
            background-color: #343a40;
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

<body>
    @php
        use Carbon\Carbon;

        $dueDate = Carbon::createFromFormat('d-m-Y', date('d-m-Y', strtotime($data['due_date'])));
        $now = Carbon::now();

        $difference = $now->diffInDays($dueDate, false);
    @endphp

    <div class="email-container">
        <div class="header">
            <h1>Thông Báo Mượn Sách</h1>
        </div>
        <div class="content">
            <p>Kính gửi {{ $data['user']['name'] }},</p>
            <p>Đây là một lời nhắc rằng cuốn sách sau đã quá hạn trả:</p>
            <table class="table">
                <tr>
                    <th>Mã sách:</th>
                    <td>{{ $data['book_code'] }}</td>
                </tr>
                <tr>
                    <th>Tên sách:</th>
                    <td>{{ $data['book']['title'] }}</td>
                </tr>
                <tr>
                    <th>ISBN:</th>
                    <td>{{ $data['isbn_code'] }}</td>
                </tr>
                <tr>
                    <th>Ngày mượn:</th>
                    <td>{{ date('d-m-Y', strtotime($data['borrow_date'])) }}</td>
                </tr>
                <tr>
                    <th>Ngày trả:</th>
                    <td>{{ date('d-m-Y', strtotime($data['due_date'])) }}</td>
                </tr>
                <tr>
                    <th>Số ngày trả muộn:</th>
                    <td>{{ abs($difference) }}</td>
                </tr>
            </table>
            <p>Vui lòng đảm bảo trả sách đúng hạn để tránh bất kỳ phí phạt nào.</p>
            <p>Xin cảm ơn sự hợp tác của bạn.</p>
        </div>
        <div class="footer">
            <p>&copy; 2023 Thư Viện Của Bạn. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>

</html>
