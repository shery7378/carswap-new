<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired</title>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <style>
        :root {
            color-scheme: light;
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --accent: #111827;
            --border: #e5e7eb;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(180deg, #eef2f7 0%, #f8fafc 100%);
            color: var(--text);
        }

        .card {
            width: min(92vw, 520px);
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
            padding: 40px 32px;
            text-align: center;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 2rem;
        }

        p {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.6;
        }

        a.button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 12px;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Session expired</h1>
        <p>Your admin session has timed out. Please sign in again to continue.</p>
        <a class="button" href="{{ route('login') }}">Click here to login again</a>
    </main>
    <script>
        setTimeout(function () {
            window.location.replace(@json(route('login')));
        }, 3000);
    </script>
</body>
</html>
