<!doctype html>
<html><body style="margin:0;background:#111;padding:30px;font-family:Arial,sans-serif;color:#222">
<div style="max-width:620px;margin:auto;background:#fff;border:1px solid #ddd;padding:34px">
<h2 style="margin-top:0">Lex Factum &amp; Partners</h2>
<p>Dear <?= esc($name) ?>,</p>
<p>Your client portal account has been created. You can use the following credentials to sign in:</p>
<table cellpadding="8" cellspacing="0" style="border-collapse:collapse;width:100%">
<tr><td style="border:1px solid #ddd"><strong>Login</strong></td><td style="border:1px solid #ddd"><?= esc($email) ?></td></tr>
<tr><td style="border:1px solid #ddd"><strong>Temporary Password</strong></td><td style="border:1px solid #ddd;font-family:monospace"><?= esc($password) ?></td></tr>
</table>
<p style="margin:24px 0"><a href="<?= esc($loginUrl) ?>" style="background:#b89b5e;color:#fff;text-decoration:none;padding:12px 18px;border-radius:6px">Login to Client Portal</a></p>
<p style="font-size:13px;color:#666">Please change your password after signing in. This email contains your temporary password; keep it confidential.</p>
<p>Regards,<br>Lex Factum &amp; Partners</p>
</div></body></html>
