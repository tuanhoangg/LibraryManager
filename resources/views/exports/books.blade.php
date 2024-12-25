<table>
    <thead>
        <tr>
            <th style="width: 100px">Tên sách</th>
            <th style="width: 100px">ISBN Code</th>
            <th style="width: 100px">Mã sách</th>
            <th style="width: 100px">Tác giả</th>
            <th style="width: 100px">Giá tiền</th>
            <th style="width: 100px">Phiên bản</th>
            <th style="width: 100px">Ngôn ngữ</th>
            <th>Barcode</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($books as $book)
            @foreach ($book->book_item as $item)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->isbn_code }}</td>
                    <td>{{ $item->book_code }}</td>
                    <td>{{ $book->author->name }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') . ' ₫' }}</td>
                    <td>{{ $item->edition }}</td>
                    <td>{{ $item->language->language_name }}</td>
                    <td style="height: 50px">
                        @if ($item->barcode)
                            <img src="{{ public_path($item->barcode) }}" width="15" height="30">
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
