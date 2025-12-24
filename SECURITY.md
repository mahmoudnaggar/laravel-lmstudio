# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

Instead, please report them via email to: **mahmoudelnagarx1@gmail.com**

You should receive a response within 48 hours. If for some reason you do not, please follow up via email to ensure we received your original message.

Please include the following information:

- Type of issue (e.g. buffer overflow, SQL injection, cross-site scripting, etc.)
- Full paths of source file(s) related to the manifestation of the issue
- The location of the affected source code (tag/branch/commit or direct URL)
- Any special configuration required to reproduce the issue
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit it

## Preferred Languages

We prefer all communications to be in English.

## Disclosure Policy

- We will confirm receipt of your vulnerability report
- We will investigate and validate the issue
- We will work on a fix and release timeline
- We will notify you when the fix is released
- We will credit you in the release notes (if desired)

## Comments on this Policy

If you have suggestions on how this process could be improved, please submit a pull request.

## Security Best Practices

When using this package:

1. **Keep LM Studio local** - Don't expose LM Studio server to the internet
2. **Use environment variables** - Store configuration in `.env` file
3. **Validate input** - Always validate and sanitize user input before sending to LM Studio
4. **Rate limiting** - Implement rate limiting for API endpoints
5. **Monitor usage** - Keep track of token usage and API calls
6. **Update regularly** - Keep the package and dependencies up to date
7. **Secure your Laravel app** - Follow Laravel security best practices

## Known Security Considerations

- LM Studio runs locally and should not be exposed to the internet
- This package does not send any data to external servers
- All communication is between your Laravel app and local LM Studio instance
- No API keys or sensitive data are transmitted over the internet

Thank you for helping keep Laravel LM Studio and its users safe!
