<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        /*   QrCode Print Styles */
        @media print {
            .print-container {
                page-break-after: always;
            }
        }
        .print-container {
            display: flex;
            align-items: end;
            flex-wrap: wrap;
        }
        .firstRow {
            width: 100%;
        }
        .companyName {
			font-size: 30px;
			width: 160px;
			padding: 5px;
			text-align: center;
			margin: 0 auto;
        }

        .companyName span{
            font-weight: bold;
        }

        .qr-code-text {
            padding: 10px;
            font-weight: bold;
        }
    </style>
    <title>Document</title>
</head>
<body>

    @foreach($postData as $value)
        <div class="print-container">
            <div class="firstRow">
                <div class="companyName">
                    <span>
                    @foreach($settings as $set)
                        {{$set['company_name']}}
                    @endforeach
                    </span>
                </div>
            </div>
            <div class="two">

            </div>
            <div class="qr-code-image">
            {!! QrCode::size(100)->encoding('UTF-8')->generate(
              "رمز الصنف: " . $value[0] . "\n".
            "اسم الصنف: " . $value[1] . "\n".
            "المسلسل: " . $value[2] . "\n".
            "العيار: " . $value[3] . "\n".
            "الوزن: " . $value[4] . "\n"
             ) !!}
            </div>
            <div class="qr-code-text">
                Wt: {{$value[4]}} <br />
                Kt:  {{$value[3]}} <br />
                Name: {{$value[1]}} <br />
                ID: {{$value[0]}} <br />
            </div>
		<div class="firstRow">
            <div class="serial">
                    <span>
                        {{$value[2]}}
                    </span>
            </div>
        </div>
        </div>
        <div class="two">

        </div>
    @endforeach

</body>
</html>