@php
    use App\Models\BorrowHistory;
    use App\Models\Roles;
    use Carbon\Carbon;
@endphp
<table>
    <thead>
        <tr>
            <th style="width: 100px;"><strong>Tên sách</strong></th>
            <th style="width: 100px;"><strong>Mã sách</strong></th>
            <th style="width: 100px;"><strong>Ngày mượn</strong></th>
            <th style="width: 100px;"><strong>Hạn trả</strong></th>
            <th style="width: 100px;"><strong>Ngày trả</strong></th>
            <th style="width: 100px;"><strong>Người mượn</strong></th>
            <th style="width: 100px;"><strong>Trạng thái</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($borrows as $borrow)
            <tr>
                <td>{{ $borrow->book->title }}</td>
                <td class="text-wrap">{{ $borrow->book_code }}</td>
                <td class="text-wrap">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</td>
                <td class="text-wrap">{{ \Carbon\Carbon::parse($borrow->due_date)->format('d/m/Y') }}</td>
                <td class="text-wrap">
                    {{ $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at)->format('d/m/Y') : '' }}</td>
                <td class="text-wrap">{{ $borrow->user?->name }}</td>
                <td class="text-wrap">{{ BorrowHistory::STATUS_LIST[$borrow->status]['label'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<script></script>
