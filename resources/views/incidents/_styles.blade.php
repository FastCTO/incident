<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        color: #111827;
        margin: 0;
        padding: 30px;
    }

    .wrap {
        max-width: 1100px;
        margin: 0 auto;
    }

    .top-nav {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .top-nav-left,
    .top-nav-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .top-nav a {
        color: #1d4ed8;
        font-weight: bold;
        text-decoration: none;
    }

    .top-nav a:hover {
        text-decoration: underline;
    }

    .nav-button-link {
        background: transparent;
        border: 0;
        padding: 0;
        color: #1d4ed8;
        font-weight: bold;
        cursor: pointer;
        font-size: 16px;
        font-family: Arial, sans-serif;
    }

    .nav-button-link:hover {
        text-decoration: underline;
    }

    .logout-button {
        background: #e5e7eb;
        color: #111827;
        border: 0;
        border-radius: 6px;
        padding: 8px 12px;
        font-weight: bold;
        cursor: pointer;
    }

    .logout-button:hover {
        background: #d1d5db;
    }

    .card {
        background: #ffffff;
        border-radius: 10px;
        padding: 24px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }

    .header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    h1 {
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 30px;
    }

    h2 {
        margin-top: 0;
    }

    p {
        color: #4b5563;
    }

    .logo {
        max-height: 70px;
        width: auto;
    }

    .btn {
        display: inline-block;
        background: #1d4ed8;
        color: white;
        padding: 10px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        border: 0;
        cursor: pointer;
        font-size: 15px;
    }

    .btn:hover {
        background: #1e40af;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #111827;
        margin-left: 8px;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .success {
        background: #dcfce7;
        color: #166534;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
        padding: 12px;
    }

    td {
        border-bottom: 1px solid #e5e7eb;
        padding: 12px;
    }

    a {
        color: #1d4ed8;
    }

    .sort-link {
        color: #374151;
        text-decoration: none;
        font-weight: bold;
    }

    .sort-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .empty {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 6px;
    }

    input,
    select,
    textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 15px;
    }

    textarea {
        min-height: 110px;
    }

    .field {
        margin-bottom: 18px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .label {
        font-size: 13px;
        color: #6b7280;
        font-weight: bold;
        text-transform: uppercase;
    }

    .value {
        margin-top: 4px;
    }

    .box {
        background: #f9fafb;
        border-radius: 8px;
        padding: 14px;
        white-space: pre-wrap;
    }

    .error {
        color: #b91c1c;
        font-size: 14px;
        margin-top: 6px;
    }

    .notice {
        background: #eff6ff;
        color: #1e3a8a;
        border-radius: 8px;
        padding: 14px;
        margin-top: 16px;
    }

    @media (max-width: 850px) {
        body {
            padding: 16px;
        }

        .top-nav,
        .header-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .top-nav-left,
        .top-nav-right {
            flex-wrap: wrap;
        }

        .grid,
        .detail-grid,
        .details {
            grid-template-columns: 1fr;
        }
    }
</style>
