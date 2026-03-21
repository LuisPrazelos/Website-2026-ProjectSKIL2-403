<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 20px;
        }
        .details {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .details p {
            margin: 8px 0;
        }
        .remarks {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Opmerkingen ontvangen voor je evenement</h1>

        <p>Hallo {{ $happening->user->first_name }},</p>
        <p>Je hebt opmerkingen ontvangen voor je evenement!</p>

        <h2>Evenement Details</h2>
        <div class="details">
            <p><strong>Datum:</strong> {{ $happening->event_date->format('d-m-Y H:i') }}</p>
            <p><strong>Aantal personen:</strong> {{ $happening->person_count }}</p>
            <p><strong>Evenement ID:</strong> #{{ $happening->id }}</p>
        </div>

        <h2>Opmerkingen</h2>
        <div class="remarks">
            {!! nl2br(e($remarks)) !!}
        </div>

        <p>Dank je wel voor je aanvraag!</p>

        <div class="footer">
            <p>Dit is een automatische email. Antwoord alstublieft niet direct op deze email.</p>
        </div>
    </div>
</body>
</html>
