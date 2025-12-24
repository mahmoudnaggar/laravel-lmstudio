# Publishing to GitHub

This guide will help you publish the Laravel LM Studio package to GitHub.

## Prerequisites

- GitHub account
- Git installed locally
- Package is complete and tested

## Steps

### 1. Initialize Git Repository

```bash
cd packages/laravel-lmstudio
git init
git add .
git commit -m "Initial commit: Laravel LM Studio package"
```

### 2. Create GitHub Repository

1. Go to [GitHub](https://github.com)
2. Click "New repository"
3. Name: `laravel-lmstudio`
4. Description: "Advanced LM Studio integration for Laravel"
5. Public repository
6. Don't initialize with README (we already have one)
7. Click "Create repository"

### 3. Push to GitHub

```bash
git remote add origin https://github.com/YOUR-USERNAME/laravel-lmstudio.git
git branch -M main
git push -u origin main
```

### 4. Add Topics/Tags

On GitHub repository page:
- Click "About" (gear icon)
- Add topics: `laravel`, `lm-studio`, `ai`, `llm`, `php`, `local-ai`, `openai-compatible`

### 5. Enable GitHub Actions

- Go to "Actions" tab
- Enable workflows
- The test workflow will run automatically on push/PR

### 6. Create First Release

1. Go to "Releases" → "Create a new release"
2. Tag version: `v1.0.0`
3. Release title: `v1.0.0 - Initial Release`
4. Description: Copy from CHANGELOG.md
5. Click "Publish release"

### 7. Submit to Packagist

1. Go to [Packagist.org](https://packagist.org)
2. Click "Submit"
3. Enter repository URL: `https://github.com/YOUR-USERNAME/laravel-lmstudio`
4. Click "Check"
5. Click "Submit"

### 8. Set up Auto-Update Hook

On Packagist:
1. Go to your package page
2. Click "Settings"
3. Copy the webhook URL
4. On GitHub: Settings → Webhooks → Add webhook
5. Paste URL, select "Just the push event"
6. Click "Add webhook"

## Post-Publishing Checklist

- [ ] Repository is public
- [ ] README displays correctly
- [ ] License is visible
- [ ] GitHub Actions are passing
- [ ] Package is on Packagist
- [ ] Auto-update webhook is configured
- [ ] Topics/tags are added
- [ ] Release is created

## Updating the Package

```bash
# Make changes
git add .
git commit -m "Description of changes"
git push

# Create new release on GitHub
# Packagist will auto-update via webhook
```

## Versioning

Follow [Semantic Versioning](https://semver.org/):
- **MAJOR** (1.0.0): Breaking changes
- **MINOR** (1.1.0): New features, backward compatible
- **PATCH** (1.0.1): Bug fixes, backward compatible

## Badges

Add these to README.md:

```markdown
[![Latest Version](https://img.shields.io/packagist/v/mahmoudnaggar/laravel-lmstudio.svg)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)
[![Total Downloads](https://img.shields.io/packagist/dt/mahmoudnaggar/laravel-lmstudio.svg)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)
[![License](https://img.shields.io/packagist/l/mahmoudnaggar/laravel-lmstudio.svg)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)
[![Tests](https://github.com/mahmoudnaggar/laravel-lmstudio/actions/workflows/tests.yml/badge.svg)](https://github.com/mahmoudnaggar/laravel-lmstudio/actions/workflows/tests.yml)
```

## Promotion

- Share on Twitter/X with #Laravel hashtag
- Post in Laravel News
- Share in Laravel subreddit
- Post in Laravel Discord/Slack communities
- Write a blog post about it

## Support

- Respond to issues promptly
- Review pull requests
- Update documentation as needed
- Keep dependencies up to date

Good luck with your package! 🚀
