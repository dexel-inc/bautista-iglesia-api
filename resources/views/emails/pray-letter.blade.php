<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #4a4a4a;
            background-color: #f5f1e8;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fefefe;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(139, 69, 19, 0.15);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #d2691e 0%, #cd853f 50%, #daa520 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
        }
        .church-image {
            text-align: center;
            margin-bottom: 30px;
        }
        .church-image img {
            max-width: 200px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.2);
        }
        .subject {
            color: #8b4513;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
        }
        .message {
            background-color: #faf8f3;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #d2691e;
            margin: 20px 0;
            line-height: 1.8;
        }
        .footer {
            background-color: #8b4513;
            color: #f5f1e8;
            padding: 25px 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer-info {
            margin-bottom: 15px;
        }
        .footer-info p {
            margin: 5px 0;
        }
        .footer-divider {
            border-top: 1px solid #a0522d;
            margin: 15px 0;
            padding-top: 15px;
        }
        .footer-note {
            color: #deb887;
            font-style: italic;
        }
        .contact-info {
            display: inline-block;
            margin: 0 15px;
        }
        .contact-info a {
            color: #f4a460;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
            color: #daa520;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Iglesia Bautista Fundamental</h1>
        </div>

        <div class="content">
            <div class="church-image">
                <img src="https://admin.ibfcasagrande.com/logo.png" alt="Iglesia Bautista Fundamental">
            </div>

            <div class="subject">{{ $subject }}</div>

            @if($description)
                <div class="message">
                    {!! nl2br(e($description)) !!}
                </div>
            @endif
        </div>

        <div class="footer">
            <div class="footer-note">
                <p>{{ $subject }} ha sido enviado a todos los misioneros.</p>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-info">
                <p><strong>Desarrollado por:</strong></p>
                <div class="contact-info">
                    <a href="https://www.dexel-inc.com/" target="_blank">Dexel Inc.</a>
                </div>
                <div class="contact-info">
                    <a href="mailto:dexelinfo@gmail.com">dexelinfo@gmail.com</a>
                </div>
                <div class="contact-info">
                    <a href="tel:+573135632235">+57 3135632235</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
