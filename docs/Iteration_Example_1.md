### Iteration 1

#### User Stories
- As a student, I want to log in using my school ID and password so that I can take exams securely.
- As an admin, I want to view the dashboard so that I can manage students and faculty.

#### Pair Programming
On the development session conducted on 2025-08-12, Alex acted as the driver while Sam took the role of navigator. During this session, the driver was responsible for building the authentication flow and routing foundation, following the MVC–DAO–Service architecture with properly structured Core (Router), Interfaces (DAO/Services), Services, Controllers, and Views. The driver also wired dependency injection and basic views for the login page.

The navigator actively reviewed the code in real time, ensuring SOLID and PSR-4 best practices and that security checks (prepared statements, no plaintext passwords in code, session handling) were met. The navigator recommended extracting validation helpers and using `__DIR__`-based view includes. The driver then revised the implementation based on navigator feedback. After successful testing of login success/failure, development continued with the admin endpoints. Once confirmed correct, they proceeded to add dashboard scaffolding. After completing this phase, the roles were switched, and the new driver worked on student CRUD.

#### Test-Driven Development (TDD)
The team applied the TDD approach to ensure that authentication and routing were properly built and tested. Before coding began, Alex and Sam started by writing test cases using PHPUnit. These tests included scenarios such as:
- Router dispatches to registered GET/POST handlers
- AuthService returns success for valid credentials
- AuthService fails for empty/invalid credentials
- AuthController integrates service for POST /login

Since the system logic was not yet implemented, the initial tests failed (RED phase). Acting as driver, Alex coded the minimum logic in `App/Core/Router`, `App/Services/Auth/Impl/AuthServiceImpl`, and `App/Controllers/Auth/AuthController` to make the tests pass. The navigator reviewed the flow, pointed out issues, and suggested improvements. After updates, tests were rerun (GREEN phase). Once functionality was confirmed, they moved to the REFACTOR phase to clean code (extracting response/validation helpers and improving names) without changing behavior.

#### Continuous Integration (CI)
To ensure new changes were integrated and tested, the driver committed each completed feature to GitHub. Before significant modifications, the driver performed unit testing with PHP + PHPUnit to confirm stability. If no issues were found, the code was merged into the main branch. The navigator synchronized their local environment by regularly pulling updates.

Although integration was manual, the team applied CI principles: frequent commits, consistent testing, and proactive conflict resolution. The use of phpunit and testdox output streamlined error detection and improved collaboration, making refactoring safer.

#### Refactoring
After tests for authentication and routing passed, the team performed code refactoring. Sam reviewed and reorganized `AuthServiceImpl`, `Router`, and `AuthController` for better readability and maintainability. Together, they renamed variables for clarity, removed duplication around response arrays, and improved error handling for invalid requests. All tests were rerun after changes to ensure stability.

#### Small Release
After completing the login feature and passing unit tests, the team initiated a small release. They pushed updated `public/index.php`, `src/App/Services/Auth/Impl/AuthServiceImpl.php`, `src/App/Core/Router.php`, `src/App/Controllers/Auth/AuthController.php`, and `src/App/Views/auth/login.php` to the repository. This release ensured that the core features—routing, login view, and authentication—were functional and ready for the next phase. The team validated the release by running the PHPUnit test suite and manually verifying `/login` in a staged environment.