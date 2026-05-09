# Security Hardening Checklist

## Priority 0: Secrets and Production Flags

- Set production env:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `SESSION_ENCRYPT=true`
  - `SESSION_SECURE_COOKIE=true`
  - `CORS_ALLOWED_ORIGINS=https://admission-office-kristian-hernandez.vercel.app`
- Frontend-safe keys: `VITE_SUPABASE_URL`, `VITE_SUPABASE_ANON_KEY` only. These must rely on Supabase RLS.
- Server-only secrets: `APP_KEY`, `DB_PASSWORD`, `SUPABASE_SERVICE_ROLE_KEY`, `SUPABASE_S3_SECRET`, SMTP password, JWT secrets, Vercel tokens. Never prefix these with `VITE_`.
- If any secret was committed or pasted into Vercel logs, rotate it immediately in Supabase/Vercel/Mail provider, update Vercel env vars, then redeploy.

Secret scan commands:

```bash
git ls-files .env
git log --all -- .env .env.production .env.local
git grep -n -I -E "SUPABASE|SERVICE_ROLE|DB_PASSWORD|APP_KEY|JWT|SECRET|MAIL_PASSWORD|postgres://|mysql://|sk_"
npx gitleaks detect --source . --redact
```

If history contains a real leaked secret, rotate first, then remove history:

```bash
git filter-repo --path .env --path .env.production --invert-paths
git push --force-with-lease
```

## Priority 1: Database and Admin Access

- Admin APIs now require both `auth:sanctum` and `admin`.
- The `users.is_admin` column is added. Set only real administrators to `true`.
- Public application submission now validates allowed fields only and ignores user-supplied `status`, `admin_notes`, and `reference_number`.
- Public document uploads now require a one-time application upload token plus strict file validation.

Create a least-privilege PostgreSQL role for Laravel:

```sql
create role admission_app login password 'REPLACE_WITH_STRONG_PASSWORD';
grant usage on schema public to admission_app;
grant select, insert, update, delete on all tables in schema public to admission_app;
grant usage, select, update on all sequences in schema public to admission_app;
alter default privileges in schema public grant select, insert, update, delete on tables to admission_app;
alter default privileges in schema public grant usage, select, update on sequences to admission_app;
```

## Priority 2: Supabase RLS and Storage

Enable RLS on sensitive tables:

```sql
alter table applications enable row level security;
alter table users enable row level security;
alter table system_settings enable row level security;
```

Example admin-only policy if using Supabase Auth profiles:

```sql
create table if not exists profiles (
  id uuid primary key references auth.users(id),
  role text not null default 'user'
);

create policy "admins can read applications"
on applications for select
using (exists (select 1 from profiles p where p.id = auth.uid() and p.role = 'admin'));

create policy "admins can manage applications"
on applications for all
using (exists (select 1 from profiles p where p.id = auth.uid() and p.role = 'admin'))
with check (exists (select 1 from profiles p where p.id = auth.uid() and p.role = 'admin'));
```

Private storage bucket policy example:

```sql
insert into storage.buckets (id, name, public)
values ('application-documents', 'application-documents', false)
on conflict (id) do update set public = false;

create policy "admins can read application documents"
on storage.objects for select
using (
  bucket_id = 'application-documents'
  and exists (select 1 from profiles p where p.id = auth.uid() and p.role = 'admin')
);
```

Use signed URLs for private documents. Do not use public buckets for IDs, report cards, birth certificates, TORs, or diplomas.

## Priority 3: Vercel and Headers

The repo includes secure headers in `vercel.json` and Laravel middleware:

- `Content-Security-Policy`
- `X-Frame-Options: DENY`
- `X-Content-Type-Options: nosniff`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy`
- `Strict-Transport-Security`

Vercel env vars to configure:

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://admission-office-kristian-hernandez.vercel.app
CORS_ALLOWED_ORIGINS=https://admission-office-kristian-hernandez.vercel.app
SANCTUM_STATEFUL_DOMAINS=admission-office-kristian-hernandez.vercel.app
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
```

## Testing Checklist

- Visit `/admin/dashboard` while logged out: redirects to `/admin/login`.
- Create or use a non-admin account: login is rejected with 403 or an admin access error.
- Submit application: succeeds and returns a reference number.
- Upload `.php`, `.exe`, or oversized file: rejected with 422.
- Upload `jpg/png/webp/pdf` with returned upload token: succeeds.
- Call `GET /api/admin/dashboard` without token: returns 401.
- Call `GET /api/admin/dashboard` with non-admin token: returns 403.
- Trigger invalid route/error in production: no stack trace.
- Check headers:

```bash
curl -I https://admission-office-kristian-hernandez.vercel.app/
```

- Run tests and migrations:

```bash
php artisan migrate
php artisan test
npm run build
```
