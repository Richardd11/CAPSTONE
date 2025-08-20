### Test-Driven Development (TDD) — Red, Green, Refactor

This project uses PHP 7.4+ and PHPUnit 9.x. Tests live under `tests/Unit` and `tests/Integration`, autoloaded via Composer PSR-4 (`App\` → `src/App`). The test runner is configured in `phpunit.xml` with coverage for `src/`.

#### Quick Commands
- **Install**: `composer install`
- **All tests**: `vendor/bin/phpunit`
- **Unit only**: `vendor/bin/phpunit --testsuite "Unit Tests"`
- **Integration only**: `vendor/bin/phpunit --testsuite "Integration Tests"`
- **Single test/file**: `vendor/bin/phpunit tests/Unit/Auth/AuthServiceTest.php`
- **Single test method**: `vendor/bin/phpunit tests/Unit/Auth/AuthServiceTest.php::it_should_return_success_when_valid_credentials_provided`
- **Coverage (HTML)**: `vendor/bin/phpunit --coverage-html tests/coverage`

### 1) RED — Write a failing test first
- Create or extend a test in `tests/Unit/...` describing the desired behavior using clear names and groups (e.g., `@group auth`).
- Keep the API minimal; describe inputs/outputs and edge cases.
- Run the specific test to see it fail.

Example focus run:
```bash
vendor/bin/phpunit tests/Unit/Auth/AuthServiceTest.php::it_should_return_success_when_valid_credentials_provided --stop-on-failure --testdox
```

### 2) GREEN — Make the test pass with the simplest code
- Implement the minimal code in `src/App/...` to satisfy the failing test only.
- Prefer dependency injection against interfaces (`src/App/Interfaces/...`) to enable mocking.
- Rerun the focused test until it passes, then run the suite.

Example full run:
```bash
vendor/bin/phpunit --testsuite "Unit Tests"
vendor/bin/phpunit --testsuite "Integration Tests"
```

### 3) REFACTOR — Improve design with safety nets
- Consolidate duplication, clarify names, and extract small functions.
- Keep public APIs stable; improve internals first.
- Use coverage to protect behavior while changing structure.

Refactor safety run:
```bash
vendor/bin/phpunit --coverage-text --coverage-html tests/coverage
```

### Working Agreements for this Codebase
- **Structure**: Place business logic in Services (`src/App/Services/...`), persistence in DAO (`src/App/DAO/...`), contracts in Interfaces, and HTTP orchestration in Controllers.
- **Isolation**: Unit tests mock interfaces; integration tests use real services and the configured database where applicable.
- **Naming**: Test names describe outcomes (e.g., `it_should_...`). Group related tests with `@group` annotations (e.g., `auth`, `user`, `dao`, `router`).
- **Assertions**: Assert both success paths and failures (validation, invalid input, not found, unauthorized).

### Everyday TDD Loop (30–120 seconds)
1. Write/extend one failing test (RED).
2. Implement the smallest change to pass (GREEN).
3. Refactor confidently with tests and coverage (REFACTOR).
4. Repeat; grow behavior incrementally.

### Useful Patterns in This Repo
- Run focused tests during RED/GREEN; run suites before commit:
  - `vendor/bin/phpunit tests/Unit/...` then `vendor/bin/phpunit`.
- Use `tests/TestRunner.php` for a curated, TDD-styled flow if preferred:
```bash
php tests/TestRunner.php
```
- Keep fixtures simple; prefer builders/factories for complex data in `tests/`.

### Definition of Done (TDD)
- A failing test was written first and now passes.
- All suites are green locally; no regression to existing behavior.
- Code is refactored to match project architecture and readability standards.
- Coverage generated; risky areas reviewed.

Short, powerful, repeatable. Red → Green → Refactor. Ship with confidence.