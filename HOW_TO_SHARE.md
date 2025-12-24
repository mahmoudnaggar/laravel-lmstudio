# 📦 How to Publish to Packagist (5 Minutes)

## Step 1: Go to Packagist
Visit: https://packagist.org/

## Step 2: Sign In
- Click "Sign In" (top right)
- Use your GitHub account

## Step 3: Submit Package
- Click "Submit" (top menu)
- Enter: `https://github.com/mahmoudnaggar/laravel-lmstudio`
- Click "Check"
- Click "Submit"

## Step 4: Set Up Auto-Update (Optional)
1. On Packagist, go to your package settings
2. Copy the webhook URL
3. On GitHub: Settings → Webhooks → Add webhook
4. Paste the URL
5. Select "Just the push event"
6. Click "Add webhook"

## Done! 🎉

Your friend can now install with:
```bash
composer require mahmoudnaggar/laravel-lmstudio
```

No need for `dev-main` or anything else!

---

## After Publishing

Update your README badges:
```markdown
[![Latest Version](https://img.shields.io/packagist/v/mahmoudnaggar/laravel-lmstudio.svg)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)
[![Total Downloads](https://img.shields.io/packagist/dt/mahmoudnaggar/laravel-lmstudio.svg)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)
```

---

## Create a Release (Recommended)

On GitHub:
1. Go to "Releases" → "Create a new release"
2. Tag: `v1.0.0`
3. Title: `v1.0.0 - Initial Release`
4. Description: Copy from CHANGELOG.md
5. Click "Publish release"

This makes it more professional and trustworthy!
