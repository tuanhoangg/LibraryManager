<table>
    <thead>
        <tr>
            <th style="width: 300px">Tên người dùng</th>
            <th style="width: 300px">Email</th>
            <th style="width: 300px">Địa chỉ</th>
            <th style="width: 200px">Số điện thoại</th>
            <th style="width: 100px">Role</th>
            <th style="width: 300px">Mã hội viên</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->address }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->role->name }}</td>
                <td>{{ $user->member_code ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
