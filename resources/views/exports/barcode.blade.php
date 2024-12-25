<!DOCTYPE html>
<html>

<head>
    <title>Barcodes for {{ $book->title }}</title>
    <!-- CSS -->
    <style>
        .barcode {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: 10px;
        }

        .barcode img {
            max-width: 100%;
            /* Ensure image is responsive */
            height: 50px;
            width: 250px;
            /* Maintain aspect ratio */
        }

        .barcode-code {
            margin-top: 5px;
            /* Space between barcode and code */
            font-size: 14px;
            /* Adjust as needed */
            font-weight: bold;
            /* Optional: Make the text bold */
        }
    </style>
</head>

<body>
    <h1>Barcodes sách {{ $book->title }}</h1>
    <br>
    @foreach ($bookItems as $item)
        <div class="barcode">
            <img src="{{ public_path($item->barcode) }}" alt="Barcode for {{ $item->book_code }}">
            <div class="barcode-code">{{ $item->isbn_code . '-' . $item->book_code }}</div>
        </div>
        <br>
    @endforeach
</body>

</html>
