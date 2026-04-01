<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarSwap</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            line-height: 1.6;
        }
        .email-wrapper {
            max-width: 620px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .email-top-bar {
            background-color: #dcb377;
            height: 5px;
        }
        .email-logo {
            background-color: #1a1a2e;
            padding: 20px 30px;
            text-align: center;
        }
        .email-logo span {
            color: #dcb377;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .email-logo span.swap {
            color: #ffffff;
        }
        .email-body {
            padding: 35px 40px;
        }
        /* Styles for inner template HTML */
        .email-body .header {
            background-color: #dcb377 !important;
            color: white !important;
            padding: 20px !important;
            text-align: center !important;
            border-radius: 6px !important;
            margin-bottom: 20px;
        }
        .email-body .content {
            padding: 20px !important;
            background-color: #f9f9f9 !important;
            margin: 20px 0 !important;
            border-radius: 6px !important;
        }
        .email-body ul {
            padding-left: 20px;
            margin: 10px 0;
        }
        .email-body ul li {
            margin-bottom: 8px;
        }
        .email-body a[style*="background-color: #dcb377"] {
            display: inline-block;
            margin-top: 10px;
        }
        .email-footer {
            background-color: #1a1a2e;
            color: #aaaaaa;
            text-align: center;
            padding: 20px 30px;
            font-size: 12px;
        }
        .email-footer a {
            color: #dcb377;
            text-decoration: none;
        }
        .email-divider {
            height: 1px;
            background-color: #eeeeee;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-top-bar"></div>

        <!-- Logo Header -->
        <div class="email-logo">
            <span>CAR<span class="swap">SWAP</span></span>
        </div>

        <!-- Dynamic Content Body -->
        <div class="email-body">
            {!! $body !!}
        </div>

        <div class="email-divider"></div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© {{ date('Y') }} CarSwap. All rights reserved.</p>
            <p style="margin-top: 8px;">
                You received this email because an action was taken on CarSwap.
                If you have questions, <a href="mailto:{{ config('settings.storeEmail', 'support@carswap.com') }}">contact us</a>.
            </p>
        </div>
    </div>
</body>
</html>