<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin · Lex Factum</title>
<link rel="stylesheet" href="<?= base_url('styles.css') ?>">
<style>
.admin{min-height:100vh;background:#080a0c;color:#eee;padding:24px}.admin-wrap{max-width:1400px;margin:auto}.admin-top{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;border:1px solid rgba(218,190,142,.18);border-radius:18px;background:rgba(20,23,26,.76);backdrop-filter:blur(20px)}.admin-brand{color:#e0bd7c;font-size:11px;letter-spacing:.18em}.admin-top a{color:#bfc1bf;text-decoration:none;font-size:12px}.admin-hero{padding:55px 0 32px}.admin-hero small{color:#c8a66e;letter-spacing:.2em}.admin-hero h1{font:500 clamp(42px,6vw,68px)/1 Georgia,serif;margin:10px 0}.admin-hero p{color:#8f9698;max-width:720px;line-height:1.7}.admin-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:15px}.admin-card,.admin-module{border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.035);border-radius:20px;padding:24px;backdrop-filter:blur(16px)}.admin-card span{font-size:10px;letter-spacing:.15em;color:#777f82}.admin-card strong{display:block;font:500 34px Georgia,serif;margin-top:20px}.modules{display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-top:18px}.admin-module h3{font:500 25px Georgia,serif;margin:0 0 9px}.admin-module p{color:#8c9395;line-height:1.65;font-size:13px;min-height:44px}.module-link{display:inline-block;margin-top:14px;padding:10px 13px;border:1px solid rgba(212,177,113,.3);border-radius:10px;color:#d2ae70;text-decoration:none;font-size:11px;letter-spacing:.12em}.module-link:hover{background:rgba(212,177,113,.08)}.module-note{display:inline-block;margin-top:14px;color:#656d70;font-size:11px;letter-spacing:.1em}@media(max-width:850px){.admin-grid,.modules{grid-template-columns:repeat(2,1fr)}}@media(max-width:520px){.admin{padding:14px}.admin-grid,.modules{grid-template-columns:1fr}}
</style></head>
<body><main class="admin"><div class="admin-wrap">
<nav class="admin-top"><span class="admin-brand">LEX FACTUM · ADMIN CONSOLE</span><span><?= esc(session()->get('user_name')) ?> · <a href="<?= site_url('logout') ?>">Sign out</a></span></nav>
<section class="admin-hero"><small>OPERATIONS</small><h1>Command centre.</h1><p>Manage the firm website, clients and professional billing from one place.</p></section>
<section class="admin-grid">
<div class="admin-card"><span>CLIENTS</span><strong><?= number_format((int)($clientCount ?? 0)) ?></strong></div>
<div class="admin-card"><span>ACTIVE CASES</span><strong><?= number_format((int)($activeCaseCount ?? 0)) ?></strong></div>
<div class="admin-card"><span>UPCOMING HEARINGS</span><strong>—</strong></div>
<div class="admin-card"><span>PENDING INVOICES</span><strong><?= number_format((int)($pendingInvoiceCount ?? 0)) ?></strong></div>
</section>
<section class="modules">
<div class="admin-module"><h3>CMS</h3><p>Practice areas, website pages, legal updates and BNS articles.</p><a class="module-link" href="<?= site_url('admin/cms') ?>">OPEN CMS →</a></div>
<div class="admin-module"><h3>CRM</h3><p>Client records, cases, notes, documents and activity history.</p><a class="module-link" href="<?= site_url('admin/crm') ?>">OPEN CRM →</a></div>
<div class="admin-module"><h3>Invoicing</h3><p>Create professional invoices, record payments, track outstanding balances and print/download bills.</p><a class="module-link" href="<?= site_url('admin/invoices') ?>">OPEN INVOICING →</a></div>
</section>
</div></main></body></html>
