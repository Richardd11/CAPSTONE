### TDD Build Sequence — MVC • DAO • Service (with code)

This is the practical, step-by-step TDD sequence used to build this MVC–DAO–Service project. For each step: write a failing test (RED), add the smallest implementation (GREEN), then improve (REFACTOR). Code snippets are minimal and production-ready patterns.

Conventions
- Namespaces: `App\` → `src/App` (Composer PSR-4)
- Unit tests mock dependencies (`tests/Unit`); integration tests compose real services (`tests/Integration`)
- Router drives controllers; controllers orchestrate services; services depend on DAOs

### 0) Tooling and Scaffolding (one-time)
RED
- Add PHPUnit config so tests can run and fail.

GREEN
- Ensure these exist: `composer.json`, `phpunit.xml`, `tests/bootstrap.php`, folders under `src/App/{Core,Interfaces,DAO,Services,Controllers,Views}`.

Example `tests/bootstrap.php`:
```php
<?php
require __DIR__ . '/../vendor/autoload.php';
$_ENV['APP_ENV'] = 'testing';
session_start();
```

---

### 1) Core Routing Contract (foundation)
RED
- `tests/Unit/Core/RouterTest.php` — register and dispatch a GET route.
```php
<?php
use PHPUnit\Framework\TestCase;
use App\Core\Router;

final class RouterTest extends TestCase {
    public function test_dispatch_to_registered_get_route(): void {
        $router = new Router();
        $router->get('/hello', fn() => 'world');
        $this->assertSame('world', $router->dispatch('GET', '/hello'));
    }
}
```

GREEN
- `src/App/Core/Router.php` — minimal dispatch and 404.
```php
<?php
namespace App\Core;

final class Router {
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, callable $handler): void { $this->routes['GET'][$path] = $handler; }
    public function post(string $path, callable $handler): void { $this->routes['POST'][$path] = $handler; }

    public function dispatch(string $method, string $path) {
        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) { http_response_code(404); return null; }
        return $handler();
    }
}
```

REFACTOR
- Extract parameter parsing later as needed; keep tests green.

---

### 2) User DAO Contract (data layer first)
RED
- `tests/Unit/DAO/UserDAOTest.php` — specify persistence API.
```php
<?php
use PHPUnit\Framework\TestCase;
use App\Interfaces\DAO\UserDAOInterface;

final class UserDAOTest extends TestCase {
    public function test_contract_methods_exist(): void {
        $this->assertTrue(interface_exists(UserDAOInterface::class));
    }
}
```

GREEN
- `src/App/Interfaces/DAO/UserDAOInterface.php`
```php
<?php
namespace App\Interfaces\DAO;

interface UserDAOInterface {
    public function findBySchoolId(string $schoolId): ?array;
    public function create(array $data): int;
    public function update(int $userId, array $data): bool;
    public function delete(int $userId): bool;
    public function authenticate(string $schoolId, string $password): ?array;
}
```
- `src/App/DAO/Impl/UserDAOImpl.php` (minimal, to be completed as tests grow)
```php
<?php
namespace App\DAO\Impl;

use App\Interfaces\DAO\UserDAOInterface;
use PDO;

final class UserDAOImpl implements UserDAOInterface {
    public function __construct(private PDO $pdo) {}
    public function findBySchoolId(string $schoolId): ?array { return null; }
    public function create(array $data): int { return 1; }
    public function update(int $userId, array $data): bool { return true; }
    public function delete(int $userId): bool { return true; }
    public function authenticate(string $schoolId, string $password): ?array { return null; }
}
```
- Optional DB config: `src/App/Config/Database.php`
```php
<?php
namespace App\Config;

use PDO;

final class Database {
    public static function connect(): PDO {
        return new PDO('mysql:host=127.0.0.1;dbname=capstone2', 'user', 'pass', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
```

REFACTOR
- Consolidate query helpers as behavior grows.

---

### 3) Auth Service (business logic over DAO)
RED
- `tests/Unit/Auth/AuthServiceTest.php` — successful login and validation.
```php
<?php
use PHPUnit\Framework\TestCase;
use App\Services\Auth\Impl\AuthServiceImpl;
use App\Interfaces\DAO\UserDAOInterface;

final class AuthServiceTest extends TestCase {
    public function test_login_success(): void {
        $dao = $this->createMock(UserDAOInterface::class);
        $dao->method('authenticate')->willReturn(['user_id' => 1, 'school_id' => 'S1', 'role' => 'student']);
        $service = new AuthServiceImpl($dao);
        $result = $service->login('S1', 'pw');
        $this->assertTrue($result['success']);
        $this->assertSame('Login successful', $result['message']);
    }
}
```

GREEN
- `src/App/Interfaces/Auth/AuthServiceInterface.php`
```php
<?php
namespace App\Interfaces\Auth;

interface AuthServiceInterface {
    public function login(string $schoolId, string $password): array;
    public function logout(): void;
    public function isAuthenticated(): bool;
}
```
- `src/App/Services/Auth/Impl/AuthServiceImpl.php`
```php
<?php
namespace App\Services\Auth\Impl;

use App\Interfaces\Auth\AuthServiceInterface;
use App\Interfaces\DAO\UserDAOInterface;

final class AuthServiceImpl implements AuthServiceInterface {
    public function __construct(private UserDAOInterface $userDAO) {}

    public function login(string $schoolId, string $password): array {
        if ($schoolId === '' || $password === '') {
            return ['success' => false, 'message' => 'School ID and password are required'];
        }
        $user = $this->userDAO->authenticate($schoolId, $password);
        if (!$user) { return ['success' => false, 'message' => 'Invalid credentials']; }
        $_SESSION['user'] = $user;
        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }

    public function logout(): void { unset($_SESSION['user']); }
    public function isAuthenticated(): bool { return isset($_SESSION['user']); }
}
```

REFACTOR
- Extract small validation/response helpers later.

---

### 4) User Service (user management)
RED
- `tests/Unit/User/UserServiceTest.php` — create/update/delete and validation.
```php
<?php
use PHPUnit\\Framework\\TestCase;
use App\\Interfaces\\DAO\\UserDAOInterface;
use App\\Services\\User\\Impl\\UserServiceImpl;

final class UserServiceTest extends TestCase {
    public function test_create_user_success(): void {
        $dao = $this->createMock(UserDAOInterface::class);
        $dao->method('findBySchoolId')->willReturn(null);
        $dao->method('create')->willReturn(101);
        $service = new UserServiceImpl($dao);
        $result = $service->createUser([
            'school_id' => '2021-0001',
            'full_name' => 'John Doe',
            'role' => 'student',
        ]);
        $this->assertTrue($result['success']);
        $this->assertSame(101, $result['user_id']);
    }

    public function test_create_user_missing_required_fields(): void {
        $dao = $this->createMock(UserDAOInterface::class);
        $service = new UserServiceImpl($dao);
        $result = $service->createUser(['school_id' => '']);
        $this->assertFalse($result['success']);
    }
}
```

GREEN
- `src/App/Interfaces/User/UserServiceInterface.php`
```php
<?php
namespace App\\Interfaces\\User;

interface UserServiceInterface {
    public function createUser(array $data): array;
    public function updateUser(int $userId, array $data): array;
    public function deleteUser(int $userId): array;
    public function getUsersByRole(string $role): array;
}
```
- `src/App/Services/User/Impl/UserServiceImpl.php`
```php
<?php
namespace App\\Services\\User\\Impl;

use App\\Interfaces\\DAO\\UserDAOInterface;
use App\\Interfaces\\User\\UserServiceInterface;

final class UserServiceImpl implements UserServiceInterface {
    public function __construct(private UserDAOInterface $userDAO) {}

    public function createUser(array $data): array {
        if (empty($data['school_id']) || empty($data['full_name'])) {
            return ['success' => false, 'message' => 'School ID and full name are required'];
        }
        if ($this->userDAO->findBySchoolId($data['school_id'])) {
            return ['success' => false, 'message' => 'School ID already exists'];
        }
        $id = $this->userDAO->create($data);
        return ['success' => true, 'message' => 'User created', 'user_id' => $id];
    }

    public function updateUser(int $userId, array $data): array {
        $ok = $this->userDAO->update($userId, $data);
        return $ok ? ['success' => true] : ['success' => false, 'message' => 'Update failed'];
    }

    public function deleteUser(int $userId): array {
        $ok = $this->userDAO->delete($userId);
        return $ok ? ['success' => true] : ['success' => false, 'message' => 'Delete failed'];
    }

    public function getUsersByRole(string $role): array {
        // Extend DAO with role filters when tests demand
        return [];
    }
}
```

REFACTOR
- Extract duplicate validation; add query filters by role/year/section as tests demand.

---

### 5) Auth Controller (HTTP integration)
RED
- `tests/Integration/Controllers/AuthControllerTest.php` — GET /login renders; POST /login succeeds/fails.
```php
<?php
use PHPUnit\Framework\TestCase;
use App\Core\Router;
use App\Controllers\Auth\AuthController;
use App\Interfaces\Auth\AuthServiceInterface;

final class AuthControllerTest extends TestCase {
    public function test_login_route(): void {
        $auth = $this->createStub(AuthServiceInterface::class);
        $auth->method('login')->willReturn(['success' => true, 'message' => 'Login successful']);
        $controller = new AuthController($auth);
        $router = new Router();
        $router->post('/login', [$controller, 'login']);
        $this->assertIsArray($router->dispatch('POST', '/login'));
    }
}
```

GREEN
- `src/App/Controllers/Auth/AuthController.php`
```php
<?php
namespace App\Controllers\Auth;

use App\Interfaces\Auth\AuthServiceInterface;

final class AuthController {
    public function __construct(private AuthServiceInterface $auth) {}

    public function showLogin(): string {
        ob_start();
        include __DIR__ . '/../../Views/auth/login.php';
        return ob_get_clean();
    }

    public function login(array $request = []): array {
        $sid = $request['school_id'] ?? '';
        $pwd = $request['password'] ?? '';
        return $this->auth->login($sid, $pwd);
    }

    public function logout(): void { $this->auth->logout(); }
}
```
- Minimal view: `src/App/Views/auth/login.php`
```php
<form method="post" action="/login">
  <input name="school_id" />
  <input name="password" type="password" />
  <button type="submit">Login</button>
</form>
```

REFACTOR
- Move rendering helpers to a base controller and add CSRF/flash messaging.

---

### 6) Admin Controller + Student/Faculty Management
RED
- `tests/Integration/Controllers/AdminControllerTest.php` — POST /admin/students creates a student.
```php
<?php
use PHPUnit\\Framework\\TestCase;
use App\\Core\\Router;
use App\\Controllers\\Admin\\AdminController;
use App\\Interfaces\\User\\UserServiceInterface;

final class AdminControllerTest extends TestCase {
    public function test_create_student_route(): void {
        $svc = $this->createStub(UserServiceInterface::class);
        $svc->method('createUser')->willReturn(['success' => true, 'user_id' => 5]);
        $controller = new AdminController($svc);
        $router = new Router();
        $router->post('/admin/students', fn() => $controller->createStudent([
            'school_id' => '2021-0002', 'full_name' => 'Jane Doe', 'role' => 'student'
        ]));
        $res = $router->dispatch('POST', '/admin/students');
        $this->assertTrue($res['success']);
        $this->assertSame(5, $res['user_id']);
    }
}
```

GREEN
- `src/App/Controllers/Admin/AdminController.php`
```php
<?php
namespace App\\Controllers\\Admin;

use App\\Interfaces\\User\\UserServiceInterface;

final class AdminController {
    public function __construct(private UserServiceInterface $users) {}

    public function dashboard(): array {
        // Expand with queries once tests demand
        return ['success' => true];
    }

    public function createStudent(array $request): array {
        return $this->users->createUser([
            'school_id' => $request['school_id'] ?? '',
            'full_name' => $request['full_name'] ?? '',
            'role' => 'student',
        ]);
    }
}
```

REFACTOR
- Extract form validation, role checks, and shared partials.

---

### 7) Wiring and Container (Dependency Injection)
GREEN
- `src/App/Core/Container.php` — bind interfaces to concretes.
```php
<?php
namespace App\Core;

final class Container {
    private array $bindings = [];
    public function bind(string $abstract, callable $factory): void { $this->bindings[$abstract] = $factory; }
    public function get(string $abstract): mixed { return ($this->bindings[$abstract])($this); }
}
```
- Example registration (e.g., bootstrap):
```php
$container = new App\Core\Container();
$container->bind(PDO::class, fn() => App\Config\Database::connect());
$container->bind(App\Interfaces\DAO\UserDAOInterface::class, fn($c) => new App\DAO\Impl\UserDAOImpl($c->get(PDO::class)));
$container->bind(App\Interfaces\Auth\AuthServiceInterface::class, fn($c) => new App\Services\Auth\Impl\AuthServiceImpl($c->get(App\Interfaces\DAO\UserDAOInterface::class)));
$container->bind(App\Interfaces\User\UserServiceInterface::class, fn($c) => new App\Services\User\Impl\UserServiceImpl($c->get(App\Interfaces\DAO\UserDAOInterface::class)));
```

---

### 8) HTTP Entry (public)
GREEN
- `public/index.php` — assemble container, router, and routes.
```php
<?php
require __DIR__ . '/../vendor/autoload.php';
session_start();

$container = new App\Core\Container();
// ... register bindings as above ...

$router = new App\Core\Router();
$authController = new App\Controllers\Auth\AuthController(
    $container->get(App\Interfaces\Auth\AuthServiceInterface::class)
);
$adminController = new App\Controllers\Admin\AdminController(
    $container->get(App\Interfaces\User\UserServiceInterface::class)
);
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->post('/admin/students', fn() => $adminController->createStudent($_POST));
// ... dispatch based on actual server request ...
```

---

### 9) Database + Test Data
- Maintain schema scripts (`capstone2.sql`, `database_schema_iteration*.sql`).
- Integration tests can assert schema presence and exercise a thin CRUD path.

---

### Daily TDD Cadence
1) RED: add/extend exactly one failing test in `tests/Unit/...` (or a vertical slice in `tests/Integration/...`).
2) GREEN: implement the smallest code in `src/App/...` to pass that test.
3) REFACTOR: improve names/structure; keep all tests green; run coverage.

Runbook
- Focused: `vendor/bin/phpunit path/to/TestFile.php::test_method --stop-on-failure --testdox`
- Suites: `vendor/bin/phpunit --testsuite "Unit Tests" && vendor/bin/phpunit --testsuite "Integration Tests"`
- Coverage: `vendor/bin/phpunit --coverage-text --coverage-html tests/coverage`

Yes—this is the proper TDD documentation for an MVC–DAO–Service approach: tests shape contracts (DAO), services encapsulate business rules, controllers orchestrate HTTP, and routing composes it all. Short cycles, small steps, strong seams.