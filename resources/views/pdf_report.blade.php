<!DOCTYPE html>
<html>

<head>
    <title>Inventory Sales Report</title>
    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
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
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="container d-flex flex-row align-items-center justify-content-between">
            <img class="img-logo" src="{{ $m9_logo }}" alt="Merchant9 Logo">
            <h1>Inventory Sales Report</h1>
        </div>

        <!-- Inventory Data Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><strong>No.</strong></strong></th>
                        <th><strong>Design Code</strong></th>
                        <th><strong>Vendor Code</strong></th>
                        <th><strong>Total Items</strong></th>
                        <th><strong>Items Sold</strong></th>
                        <th><strong>Items Available</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventoryData as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td> <!-- Incrementing the row number -->
                        <td>{{ $data['InternalCode'] }}</td> <!-- Display InternalCode -->
                        <td>{{ $data['VendorCode'] }}</td> <!-- Display VendorCode -->
                        <td>{{ $data['TotalItems'] }}</td> <!-- Display Total Items -->
                        <td>{{ $data['SoldItems'] }}</td> <!-- Display Sold Items -->
                        <td>{{ $data['AvailableItems'] }}</td> <!-- Display Available Items -->
                    </tr>
                    @endforeach
                </tbody>
        </table>
        </table>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js v5.3.2 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
