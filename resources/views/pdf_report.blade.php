<!DOCTYPE html>
<html>

<head>
    <title>Inventory Report</title>
    <!-- Bootstrap CSS v5.2.1 -->

    <style>
        @page {
            margin: 5 5 5 5; /* top, right, bottom, left */
        }

        *{
            font-size: 0.9em;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
            border-bottom: 1px solid rgba(182, 177, 177, 0.568);
        }

        img{
            height:75px;
            widht:auto;
        }

        .header-details{
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
            width: 100%;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="header">
        <div class="container d-flex flex-column align-items-center">
            <img class="img-logo" src="{{$m9_logo }}" alt="Merchant9 Logo">
            <div class="header-details">
                <h1 style="position: relative; left:-5px;">Sales Report</h1>
                <h4 style="position: relative; left:5px;">Sale period: <strong>{{ $report_generated_at_start }}</strong> to
                    <strong>{{ $report_generated_at_end }}</strong>
                </h4>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Category Code</th>
                    <th>Items Sold</th>
                    <th>Total Sales Amount</th>
                </tr>
            </thead>
            @foreach ($inventoryData as $description => $data)
                <tbody>
                    <tr>
                        <td>{{ $description }}</td>
                        <td>{{ $data['category_code'] }}</td>
                        <td>
                            @foreach ($data['store_counts'] as $storecode => $count)
                            <div class="container p-0">
                                {{ $storecode }}: {{ $count }}
                            </div>
                            @endforeach
                            <div class="container p-0">
                                total: {{ $data['items_sold_count']}}
                            </div>
                        </td>
                        <td>{{ $data['sales_amount'] }}</td>
                    </tr>
                    {{-- <tr>
                    <th colspan="5" class="text-center">Items Sold Per Store</th>
                </tr>
                <tr>
                    <th>Item No.</th>
                    <th>Inventory Code</th>
                    <th colspan="3">Store Code</th>
                </tr> --}}
                </tbody>
                {{-- <tbody>
                    @foreach ($data['inventory_codes'] as $index => $code)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $code }}</td>
                        </tr>
                    @endforeach
                </tbody> --}}
            @endforeach
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
