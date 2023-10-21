<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .separator {
            display: flex;
            align-items: center;
            text-align: center;

        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #000;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }
        .separator span {
            border: 1px solid;
            padding: 0 10px;
        }
        body {
            font-size: 12px;
        }
        .print-container {
            max-width: 57mm;
            direction: rtl;
        }
        .header {
            display: flex;
        }
        .content {
            padding: 0 10px;
        }
        .right-col, .left-col {
            width: 50%;
        }
    </style>
    <title>Document</title>
</head>
<body>

    <div class="print-container">
        <div class="header">
            <div class="right-col">&nbsp;</div>
            <div class="left-col">
                <p><span>رقم الحركة: </span>{{$transferItem->id}}</p>
                <p><span>التاريخ: </span><span>{{$date}}</span></p>
                <p><span>الوقت: </span><span>{{$time}}</span></p>
            </div>
        </div>
        <div class="separator"><span>تحويل</span></div>
        <div class="content">
            <p><span>من القسم :</span> <span>{{$transferItem->fromDepartment->name}} </span></p>
            <p><span> إلي القسم : </span><span>{{$transferItem->toDepartment->name}}</span></p>
            <p><span>العيار : </span><span>{{$transferItem->actual_shares}}</span></p>
            <p><span>الوزن : </span><span>{{$transferItem->weight_to_transfer}}</span></p>
            <p>
                <span>الصنف : </span>
                <span>{{$transferItem->item->code}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span>{{$transferItem->item->name}}</span>
            </p>
        </div>
    </div>

</body>
</html>
