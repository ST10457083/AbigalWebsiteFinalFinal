<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin - Abigail Beauty Bar') ?></title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Admin specific styles */
        body {
            background: var(--ivory);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .admin-wrapper {
            width: 100%;
            max-width: 420px;
        }
        .admin-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .admin-box h2 {
            text-align: center;
            margin-bottom: 10px;
            color: var(--plum);
        }
        .admin-box .subtitle {
            text-align: center;
            color: var(--ink);
            opacity: 0.6;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }
        .admin-box .form-group {
            margin-bottom: 20px;
        }
        .admin-box label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--plum);
            font-size: 0.9rem;
        }
        .admin-box input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .admin-box input:focus {
            outline: 2px solid var(--rose);
            outline-offset: 1px;
        }
        .admin-box .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--plum);
            color: var(--ivory);
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .admin-box .btn-login:hover {
            background: var(--rose);
        }
        .admin-box .form-note {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
            font-size: 0.8rem;
            opacity: 0.7;
        }
        .admin-box .form-note strong {
            color: #6B2323;
        }
        .admin-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8rem;
            opacity: 0.4;
        }
        .admin-footer a {
            color: var(--plum);
            text-decoration: none;
        }
        .admin-footer a:hover {
            opacity: 0.8;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .alert-error {
            background: #F4DEDE;
            color: #6B2323;
            border-left: 4px solid #6B2323;
        }
        .alert-success {
            background: #E4EEE1;
            color: #2E4A2A;
            border-left: 4px solid #2E4A2A;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-box">