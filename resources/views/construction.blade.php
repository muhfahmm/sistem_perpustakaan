<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpusku | Sedang Dalam Pembangunan</title>
    <style>
        :root {
            --ink: #173042;
            --muted: #61727c;
            --paper: #f7f4ed;
            --accent: #e2794f;
            --line: #d8d5ca;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background: var(--paper);
            font-family: Georgia, 'Times New Roman', serif;
        }

        main {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px;
            background:
                linear-gradient(135deg, transparent 0 48%, rgba(226, 121, 79, .08) 48% 49%, transparent 49%),
                radial-gradient(circle at 12% 18%, rgba(23, 48, 66, .08) 0 1px, transparent 1px);
            background-size: auto, 18px 18px;
        }

        .content {
            width: min(760px, 100%);
            padding: clamp(28px, 7vw, 72px);
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .mark {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--accent);
            font: 700 .78rem/1.2 Arial, sans-serif;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .mark::before {
            content: '';
            width: 34px;
            height: 2px;
            background: var(--accent);
        }

        h1 {
            max-width: 620px;
            margin: 28px 0 20px;
            font-size: clamp(2.8rem, 8vw, 6.5rem);
            font-weight: 400;
            line-height: .95;
            letter-spacing: 0;
        }

        p {
            max-width: 520px;
            margin: 0;
            color: var(--muted);
            font: 1.05rem/1.7 Arial, sans-serif;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 54px;
            padding-top: 18px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font: .75rem/1.4 Arial, sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        @media (max-width: 520px) {
            main { padding: 20px; }
            .content { padding: 36px 0; }
            .footer { display: block; }
            .footer span { display: block; margin-top: 8px; }
        }
    </style>
</head>
<body>
    <main>
        <section class="content" aria-labelledby="page-title">
            <div class="mark">Perpusku</div>
            <h1 id="page-title">Sedang dalam pembangunan.</h1>
            <p>Kami sedang menyiapkan ruang perpustakaan digital yang lebih rapi untuk menemukan, meminjam, dan mengelola buku.</p>
            <div class="footer">
                <span>Perpustakaan digital</span>
                <span>Segera hadir</span>
            </div>
        </section>
    </main>
</body>
</html>
