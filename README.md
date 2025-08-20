# Examination System with AI Essay Checker

A comprehensive web-based examination system built with PHP using MVC-DAO-Service architecture with interfaces. The system currently focuses on authentication with support for three user roles (Admin, Faculty, Student).

## 🚀 Features

### Authentication Features
- **Secure Login**: Role-based authentication system
- **Session Management**: Secure session handling
- **Role Support**: Admin, Faculty, and Student roles
- **Dependency Injection**: Interface-based architecture

## 🛠 Technical Features

- **MVC-DAO-Service Architecture**: Interface-based architecture with dependency injection
- **Role-Based Access Control**: Secure access based on user roles
- **Session Management**: Secure session handling
- **Database Integration**: MySQL database with proper relationships
- **Responsive Design**: Bootstrap-based responsive UI
- **Real-time Timer**: JavaScript-based exam timer
- **Dependency Injection**: Interface-based service resolution

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for autoloading)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd examination-system
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Database Setup
1. Create a MySQL database named `capstone2`
2. Import the database schema:
```bash
mysql -u root -p capstone2 < capstone2.sql
```

### 4. Configuration
1. Update database credentials in `src/App/Config/Database.php`:
```php
private $host = '127.0.0.1';
private $database = 'capstone2';
private $username = 'your_username';
private $password = 'your_password';
```

### 5. Web Server Configuration
Configure your web server to point to the `public/` directory as the document root.

#### Apache (.htaccess already included)
The `.htaccess` file in the `public/` directory handles URL rewriting.

#### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/examination-system/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 👥 Default Users

The system comes with pre-configured users for testing:

### Admin
- **School ID**: ADMIN001
- **Password**: password123
- **Role**: Admin

### Faculty
- **School ID**: FAC001
- **Password**: password123
- **Role**: Faculty

### Students
- **School ID**: 2020-001, 2020-002, 2021-001, 2021-002
- **Password**: password123
- **Role**: Student

## 🏗 System Architecture

### Directory Structure
```
examination-system/
├── public/                 # Web root directory
│   ├── index.php          # Main entry point
│   └── .htaccess          # URL rewriting
├── src/                   # Application source code
│   └── App/
│       ├── Config/        # Configuration files
│       ├── Interfaces/    # Service and DAO interfaces
│       ├── DAO/           # Data Access Objects
│       │   └── Impl/      # DAO implementations
│       ├── Controllers/   # Controllers organized by feature
│       │   └── Auth/      # Authentication controllers
│       ├── Services/      # Business logic services
│       │   └── Impl/      # Service implementations
│       ├── Core/          # Core framework classes
│       └── Views/         # View templates
├── vendor/                # Composer dependencies
├── tests/                 # Unit tests
├── capstone2.sql          # Database schema
└── composer.json          # Composer configuration
```

### Interface-Based Architecture

#### Interfaces
- `UserDAOInterface.php` - User data access contract
- `AuthServiceInterface.php` - Authentication service contract

#### DAO Layer (Implementations)
- `UserDAOImpl.php` - User database operations implementation

#### Services Layer (Implementations)
- `AuthServiceImpl.php` - Authentication business logic implementation

#### Controllers (Request Handling)
- `Auth/AuthController.php` - Login/logout handling with dependency injection

#### Views (Presentation Layer)
- `auth/login.php` - Login interface with Bootstrap styling

#### Dependency Injection
- `Core/Container.php` - Service container for dependency resolution

## 🔐 Security Features

- **Session-based Authentication**: Secure session management
- **Role-based Access Control**: Proper authorization checks
- **Input Validation**: Sanitized user inputs
- **SQL Injection Prevention**: Prepared statements
- **CSRF Protection**: Form token validation (recommended enhancement)

## 🔧 Interface-Based Architecture

The system uses interfaces and dependency injection for better testability and maintainability:

### Interface Contracts
```php
interface UserDAOInterface {
    public function findBySchoolId(string $school_id): ?array;
    public function authenticate(string $school_id, string $password): ?array;
}

interface AuthServiceInterface {
    public function login(string $school_id, string $password): array;
    public function logout(): array;
    public function isAuthenticated(): bool;
}
```

### Dependency Injection
```php
class Container {
    public function registerServices() {
        $this->services[UserDAOInterface::class] = function() {
            return new UserDAOImpl();
        };
        
        $this->services[AuthServiceInterface::class] = function() {
            $userDAO = $this->get(UserDAOInterface::class);
            return new AuthServiceImpl($userDAO);
        };
    }
}
```

## 📊 Database Schema

The system uses a well-structured MySQL database with the following main tables:

- `users` - User accounts and roles
- `subjects` - Course subjects
- `subject_assignments` - Faculty-subject assignments
- `exams` - Exam definitions
- `questions` - Exam questions with multiple types
- `exam_attempts` - Student exam attempts
- `student_answers` - Individual student answers

## 🧪 Testing

Run the database test to verify connectivity:
```bash
php test_db.php
```

## 🚀 Usage

1. **Access the System**: Navigate to your web server URL
2. **Login**: Use the default credentials provided above
3. **Role-based Access**: Each role will see their specific dashboard
4. **Create Exams** (Faculty): Create exams with various question types
5. **Take Exams** (Students): Take exams with real-time timer
6. **View Results**: Check scores and performance analytics

## 🔧 Customization

### Adding New Question Types
1. Update the `questions` table schema
2. Modify `Question.php` model
3. Update exam creation forms
4. Extend the grading logic in `ExamService.php`

### Integrating Real AI Services
Replace the mock AI implementation in `ExamService.php` with actual AI service calls:
```php
// Example OpenAI integration
private function gradeEssayWithAI($studentAnswer, $referenceAnswer)
{
    $client = new OpenAI\Client('your-api-key');
    $response = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Grade this essay...'],
            ['role' => 'user', 'content' => $studentAnswer]
        ]
    ]);
    return $response->choices[0]->message->content;
}
```

## 📝 License

This project is licensed under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📞 Support

For support and questions, please open an issue in the repository.

---

**Note**: This is a comprehensive examination system with AI essay checking capabilities. The AI implementation is currently a mock version - for production use, integrate with actual AI services like OpenAI, GPT, or similar APIs.