# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 3.x     | :white_check_mark: |
| < 3.0   | :x:                |

## Reporting a Vulnerability

We take the security of Fixed Array Functions seriously. If you discover a security vulnerability, please follow these steps:

### 1. **Do Not** Open a Public Issue

Security vulnerabilities should not be disclosed publicly until a fix is available. Please do not open a GitHub issue for security concerns.

### 2. Report Privately

Send an email to **[oliver.earl@petrobolos.com](mailto:oliver.earl@petrobolos.com)** with:

- A description of the vulnerability
- Steps to reproduce the issue
- Potential impact assessment
- Any suggested fixes (optional)

### 3. Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Varies by severity, but we aim to release patches ASAP

### 4. Disclosure Policy

Once a fix is available:

1. We'll release a patch version
2. Update the CHANGELOG with security advisory details
3. Credit the reporter (unless anonymity is requested)
4. Coordinate public disclosure timing

## Security Best Practices

When using this package:

- Always use the latest stable version
- Keep PHP and Laravel up to date
- Review dependency security advisories via `composer audit`
- Use proper input validation when populating fixed arrays from user input

## Out of Scope

The following are generally not considered security vulnerabilities:

- Performance issues that don't lead to DoS
- Issues requiring physical access to the server
- Social engineering attacks
- Issues in unsupported versions

## Questions?

For general security questions (not vulnerabilities), feel free to open a public GitHub issue or discussion.

Thank you for helping keep Fixed Array Functions secure! 🔒

