<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Test</title>
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../public/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @php($j = 5)
    <div class="card m-4 col-sm-2">
        <div class="card-header">{{$j}} Multiplication Table</div>
        <div class="card-body">
            <table>
                @foreach (range(1, 10) as $i)
                    <tr>
                        <td>{{$i}} * {{$j}}</td><td>= {{ $i * $j }}</td>
                @endforeach
            </table>
        </div>
    </div>
</body>

</html>