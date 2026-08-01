<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var int $usersCount */
/** @var int $companiesCount */
/** @var int $scientistsCount */
/** @var int $problemsCount */
/** @var int $problemsActive */
/** @var int $problemsClosed */
/** @var int $proposalsCount */
/** @var int $proposalsAccepted */

use yii\bootstrap5\Html;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Antigravity Platform Statistics Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: sans-serif; background: #fff; padding: 40px; color: #333; }
        .report-header { border-bottom: 3px solid #4f46e5; padding-bottom: 20px; margin-bottom: 40px; }
        .report-title { font-weight: 800; color: #1e293b; }
        .table { margin-top: 20px; }
        .footer-note { margin-top: 60px; font-size: 0.85rem; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="no-print mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
            <span>💡 Use your browser's Print option (Ctrl+P) to save this page as a PDF report.</span>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-4 rounded-pill">🖨️ Print Report</button>
        </div>

        <div class="report-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="report-title mb-1">Antigravity Platform</h1>
                <h5 class="text-secondary mb-0">Construction Innovation Ecosystem Statistics Report</h5>
            </div>
            <div class="text-end">
                <span class="d-block text-secondary small">Report Date</span>
                <strong class="text-dark"><?= date('d.m.Y H:i') ?></strong>
            </div>
        </div>

        <h3 class="mb-3 text-dark fw-bold">Platform Metrics Summary</h3>
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="width: 70%;">Metric Description</th>
                    <th scope="col" class="text-center" style="width: 30%;">Total Count / Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Registered Accounts (Total Users)</td>
                    <td class="text-center fw-bold"><?= $usersCount ?></td>
                </tr>
                <tr>
                    <td>Construction & Manufacturing Companies</td>
                    <td class="text-center fw-bold text-primary"><?= $companiesCount ?></td>
                </tr>
                <tr>
                    <td>Scientists, Academic Researchers & Experts</td>
                    <td class="text-center fw-bold text-info"><?= $scientistsCount ?></td>
                </tr>
                <tr>
                    <td>Total Industrial Challenges Published</td>
                    <td class="text-center fw-bold"><?= $problemsCount ?></td>
                </tr>
                <tr>
                    <td>Active Challenges (Open for Solutions)</td>
                    <td class="text-center fw-bold text-warning"><?= $problemsActive ?></td>
                </tr>
                <tr>
                    <td>Closed / Solved Challenges</td>
                    <td class="text-center fw-bold text-success"><?= $problemsClosed ?></td>
                </tr>
                <tr>
                    <td>Total Solution Proposals Submitted</td>
                    <td class="text-center fw-bold"><?= $proposalsCount ?></td>
                </tr>
                <tr>
                    <td>Accepted Proposal Contracts</td>
                    <td class="text-center fw-bold text-success"><?= $proposalsAccepted ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer-note d-flex justify-content-between small">
            <span>System Administrator Audit Log verified.</span>
            <span>&copy; <?= date('Y') ?> Antigravity platform. All rights reserved.</span>
        </div>
    </div>

    <script>
        // Auto trigger print when loaded
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
