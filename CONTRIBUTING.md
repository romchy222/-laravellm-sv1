# Contributing to Laravel LMS

First off, thank you for considering contributing to Laravel LMS! 🎉

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Commit Messages](#commit-messages)
- [Pull Request Process](#pull-request-process)
- [Feature Requests](#feature-requests)
- [Bug Reports](#bug-reports)

## Code of Conduct

This project adheres to a Code of Conduct. By participating, you are expected to uphold this code:

- Be respectful and inclusive
- Welcome newcomers and help them learn
- Focus on what is best for the community
- Show empathy towards other community members

## How Can I Contribute?

### Types of Contributions

1. **Bug Fixes** - Found a bug? Fix it!
2. **New Features** - Add new functionality
3. **Documentation** - Improve or translate docs
4. **Tests** - Add test coverage
5. **Code Quality** - Refactor and optimize
6. **Design** - UI/UX improvements

### Good First Issues

Look for issues labeled `good first issue` to get started!

## Development Setup

1. Fork the repository
2. Clone your fork:
   ```bash
   git clone https://github.com/YOUR_USERNAME/-laravellm-sv1.git
   cd -laravellm-sv1
   ```

3. Add upstream remote:
   ```bash
   git remote add upstream https://github.com/romchy222/-laravellm-sv1.git
   ```

4. Create a branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

5. Follow the [SETUP.md](SETUP.md) guide for installation

## Coding Standards

### PHP Code Style

We follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.

**Format your code:**
```bash
./vendor/bin/pint
```

### Laravel Best Practices

- Use Eloquent ORM over raw queries
- Use Form Requests for validation
- Use Resource Controllers
- Keep controllers thin, models fat
- Use Service classes for business logic
- Use Repository pattern when needed
- Use Events and Listeners for decoupling

### JavaScript/Vue Style

- Use ES6+ syntax
- Use async/await over promises
- Follow Vue.js style guide
- Use Composition API for new components

### Database

- Always use migrations
- Never edit existing migrations that have been deployed
- Use factories for testing
- Use seeders for sample data
- Add indexes for foreign keys

### Security

- Validate all inputs
- Use Laravel's built-in security features
- Sanitize outputs
- Use CSRF protection
- Follow OWASP guidelines
- Never commit sensitive data

## Commit Messages

Follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
type(scope): subject

body

footer
```

### Types

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### Examples

```bash
feat(courses): add video player support
fix(auth): resolve login redirect issue
docs(api): update API documentation
test(lessons): add quiz submission tests
```

## Pull Request Process

### Before Submitting

1. **Test your changes**
   ```bash
   php artisan test
   ```

2. **Check code style**
   ```bash
   ./vendor/bin/pint
   ```

3. **Update documentation** if needed

4. **Add/update tests** for new features

5. **Update CHANGELOG.md** (if applicable)

### Creating a Pull Request

1. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

2. **Create PR** on GitHub

3. **Fill out the PR template** completely:
   - Description of changes
   - Related issue number
   - Screenshots (if UI changes)
   - Testing done
   - Checklist completion

4. **Respond to review** comments promptly

### PR Title Format

```
[Type] Brief description of changes
```

Examples:
- `[Feature] Add course marketplace functionality`
- `[Fix] Resolve enrollment payment issue`
- `[Docs] Update API documentation`

### Review Process

- Maintainers will review within 1-3 days
- At least one approval required
- All checks must pass
- No merge conflicts

## Feature Requests

### Before Requesting

- Check if feature already exists
- Search existing issues/PRs
- Consider if it fits the project scope

### Creating a Feature Request

1. Open an issue with label `enhancement`
2. Use the feature request template
3. Provide:
   - Clear description
   - Use case/motivation
   - Proposed solution
   - Alternative solutions considered
   - Additional context

## Bug Reports

### Before Reporting

- Search existing issues
- Try latest version
- Check if it's already fixed

### Creating a Bug Report

1. Open an issue with label `bug`
2. Use the bug report template
3. Provide:
   - Bug description
   - Steps to reproduce
   - Expected behavior
   - Actual behavior
   - Screenshots/logs
   - Environment details:
     - PHP version
     - Laravel version
     - MySQL version
     - OS

### Security Vulnerabilities

**DO NOT** open public issues for security vulnerabilities.

Email security issues to: [your-security-email]

## Development Workflow

### Branch Strategy

- `main` - Production-ready code
- `develop` - Integration branch
- `feature/*` - New features
- `fix/*` - Bug fixes
- `hotfix/*` - Critical fixes

### Keeping Your Fork Updated

```bash
git fetch upstream
git checkout main
git merge upstream/main
git push origin main
```

## Testing

### Running Tests

```bash
# All tests
php artisan test

# Specific test
php artisan test --filter=CourseTest

# With coverage
php artisan test --coverage
```

### Writing Tests

- Feature tests for API endpoints
- Unit tests for services/helpers
- Minimum 80% coverage for new code
- Use factories and seeders
- Mock external services

### Test Structure

```php
public function test_user_can_enroll_in_course()
{
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()->create();
    
    // Act
    $response = $this->actingAs($user)
        ->postJson("/api/courses/{$course->id}/enroll");
    
    // Assert
    $response->assertStatus(201);
    $this->assertDatabaseHas('enrollments', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
}
```

## Documentation

### Types of Documentation

1. **Code Comments** - For complex logic
2. **PHPDoc** - For all public methods
3. **README** - Project overview
4. **API Docs** - Endpoint documentation
5. **Guides** - Setup and usage guides

### Writing Good Documentation

- Clear and concise
- Include examples
- Keep up to date
- Use proper formatting
- Add screenshots for UI

## Community

### Getting Help

- GitHub Discussions
- Stack Overflow (tag: `laravel-lms`)
- Discord/Slack (if available)

### Contributing to Discussions

- Be helpful and respectful
- Share knowledge
- Ask questions
- Provide feedback

## Recognition

Contributors will be:
- Listed in CONTRIBUTORS.md
- Mentioned in release notes
- Given credit in commits

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

## Quick Checklist

Before submitting a PR, ensure:

- [ ] Code follows style guidelines
- [ ] Tests pass locally
- [ ] New tests added for new features
- [ ] Documentation updated
- [ ] Commit messages are clear
- [ ] PR description is complete
- [ ] No merge conflicts
- [ ] Code is reviewed by yourself first

## Thank You! 🙏

Your contributions make this project better for everyone!

For questions about contributing, open a discussion on GitHub.

---

**Happy Coding! 💻**
