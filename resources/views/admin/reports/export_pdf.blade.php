<!DOCTYPE html>
<html>

<head>
    <title>Tasks Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Tasks Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Project</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Tag</th>
                <th>Assigned To</th>
                <th>Created At</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $t)
            <tr>
                <td>{{ $t->task_id }}</td>
                <td>{{ $t->title }}</td>
                <td>{{ $t->project_name ?? '-' }}</td>
                <td>{{ $t->status_name ?? '-' }}</td>
                <td>{{ $t->priority_name ?? '-' }}</td>
                <td>{{ $t->tag_name ?? '-' }}</td>
                <td>{{ $t->assigned_user_name ?? '-' }}</td>
                <td>{{ $t->created_at }}</td>
                <td>{{ $t->due_date ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>