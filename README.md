# ExpertRep Standalone Demo — local PHP setup

## What this is

This is a **login-free, single-client copy** of the ExpertRep dashboard,
always showing "Demo Client A"'s data. It's a separate, simplified fork of
the main **MJH Dashboards** project — used as a sales/demo tool to show
prospective clients what the platform looks like, without needing to
create an account for them or walk them through a login screen.

**This is not the product.** The actual multi-tenant, login-gated
platform (with the admin CMS, multiple companies, user accounts, etc.)
lives in the separate `mjh-dashboards` project. Changes made here don't
affect that project, and vice versa — they're two independent codebases
that happened to start from the same source.

## What's different from the main project

- No login, no session, no `auth.php` — every page is open
- No admin, no CMS, no company picker — always Demo Client A
- `includes/demo-context.php` replaces `auth.php`: it just hardcodes the
  company name, logo, and theme colors that auth.php would normally
  resolve from a session
- ExpertRep branding (logo, wordmark, colors) is fixed — there's no
  concept of a different company's theme here, since there's only ever
  one company

Everything else — the 8 dashboard pages, the filters, the charts, the
dummy data in `includes/data.php` — is identical to the main project.

## Requirements

- PHP 8+ (check with `php -v` in Terminal)
- If you don't have PHP installed on Mac: `brew install php`

## Running it locally

1. Open this folder in VS Code.
2. Open a terminal (`` Ctrl+` ``).
3. Run:

   ```
   php -S localhost:8000 -t public
   ```

4. Open `http://localhost:8000` in your browser — no login required, it
   goes straight to the dashboard.

## Keeping this in sync with the main project

Since this is a fork, any dashboard changes made to the main
`mjh-dashboards` project (new pages, chart tweaks, data changes) won't
automatically appear here — they'd need to be manually re-applied to this
copy if you want this sales demo to reflect the latest version. There's
no automated sync between the two right now.
