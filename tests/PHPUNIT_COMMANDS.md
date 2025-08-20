# 🧪 PHPUnit Commands for TDD Tests

## 🚀 Quick Start Commands

### **Run All Tests**
```bash
vendor/bin/phpunit tests
```

### **Run Specific Test Suites**
```bash
# Unit Tests (37 tests)
vendor/bin/phpunit --testsuite "Unit Tests"

# Integration Tests (14 tests)
vendor/bin/phpunit --testsuite "Integration Tests"

# DAO Tests (12 tests)
vendor/bin/phpunit --testsuite "DAO Tests"
```

### **Run by Test Groups**
```bash
# Authentication Tests (12 tests)
vendor/bin/phpunit --group auth

# Login Tests (6 tests)
vendor/bin/phpunit --group login

# User Management Tests (21 tests)
vendor/bin/phpunit --group user

# Database Operations Tests (12 tests)
vendor/bin/phpunit --group dao

# Routing Tests (10 tests)
vendor/bin/phpunit --group router
```

### **Run Specific Test Files**
```bash
# AuthService Tests
vendor/bin/phpunit tests/Unit/Auth/AuthServiceTest.php

# UserService Tests
vendor/bin/phpunit tests/Unit/User/UserServiceTest.php

# Router Tests
vendor/bin/phpunit tests/Unit/Core/RouterTest.php

# UserDAO Tests
vendor/bin/phpunit tests/Unit/DAO/UserDAOTest.php

# AuthController Tests
vendor/bin/phpunit tests/Integration/Controllers/AuthControllerTest.php

# AdminController Tests
vendor/bin/phpunit tests/Integration/Controllers/AdminControllerTest.php
```

## 📊 Coverage Commands

### **Generate HTML Coverage Report**
```bash
vendor/bin/phpunit --coverage-html tests/coverage
```

### **Generate Text Coverage Report**
```bash
vendor/bin/phpunit --coverage-text
```

### **Generate Coverage with Specific Tests**
```bash
vendor/bin/phpunit --coverage-html tests/coverage --group auth
vendor/bin/phpunit --coverage-text --group user
```

## 🔍 Detailed Output Commands

### **Verbose Output**
```bash
vendor/bin/phpunit --verbose tests
```

### **Stop on First Failure**
```bash
vendor/bin/phpunit --stop-on-failure tests
```

### **Show Test Names**
```bash
vendor/bin/phpunit --testdox tests
```

### **Generate JUnit XML Report**
```bash
vendor/bin/phpunit --log-junit tests/junit.xml tests
```

## 🎯 TDD-Specific Commands

### **Run Only Failing Tests (Red Phase)**
```bash
vendor/bin/phpunit --stop-on-failure --group auth
```

### **Run All Tests (Green Phase)**
```bash
vendor/bin/phpunit tests
```

### **Run with Coverage (Refactor Phase)**
```bash
vendor/bin/phpunit --coverage-html tests/coverage --coverage-text tests
```

## 📋 Test Categories Breakdown

### **Authentication Tests (12 tests)**
```bash
vendor/bin/phpunit --group auth
```
- Login with valid credentials
- Login with invalid credentials  
- Login with empty credentials
- Logout functionality
- Current user retrieval
- Session management

### **User Management Tests (21 tests)**
```bash
vendor/bin/phpunit --group user
```
- Create user with valid data
- Create user with existing school ID
- Create user with missing fields
- Update user successfully
- Update nonexistent user
- Delete user successfully
- Delete nonexistent user
- Get all users
- Get users by role
- Get students by year/section

### **Database Operations Tests (12 tests)**
```bash
vendor/bin/phpunit --group dao
```
- Find user by school ID
- Find user by ID
- Create user
- Update user
- Delete user
- Authenticate user
- Get all users
- Get users by role
- Get students by year/section

### **Routing Tests (10 tests)**
```bash
vendor/bin/phpunit --group router
```
- Register GET routes
- Register POST routes
- Dispatch to correct routes
- Handle 404 errors
- Handle root path
- Handle subdirectory paths
- Handle parameterized routes
- Handle query parameters
- Handle different HTTP methods

## 🎉 Expected Output Examples

### **All Tests Passing**
```
PHPUnit 9.5.28 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.0
Configuration: /workspace/phpunit.xml

...........................................................

Time: 00:00.234, Memory: 18.50 MB

OK (51 tests, 102 assertions)
```

### **With Coverage**
```
Code Coverage Report:
  2023-08-07 21:30:00

 Summary:
  Classes: 100.00% (8/8)
  Methods: 100.00% (32/32)
  Lines:   100.00% (156/156)
```

### **Verbose Output**
```
PHPUnit 9.5.28 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.0
Configuration: /workspace/phpunit.xml

Tests\Unit\Auth\AuthServiceTest
 ✓ It should return success when valid credentials provided
 ✓ It should return failure when invalid credentials provided
 ✓ It should return failure when empty credentials provided
 ✓ It should destroy session on logout
 ✓ It should return current user when session exists
 ✓ It should return null when no session exists

Time: 00:00.234, Memory: 18.50 MB

OK (6 tests, 18 assertions)
```

## 🚀 Advanced Commands

### **Run Tests in Parallel (if available)**
```bash
vendor/bin/phpunit --process-isolation tests
```

### **Generate Coverage for Specific Files**
```bash
vendor/bin/phpunit --coverage-html tests/coverage --filter AuthService
```

### **Run Tests with Custom Configuration**
```bash
vendor/bin/phpunit --configuration custom-phpunit.xml tests
```

### **Generate Test Results in Different Formats**
```bash
# JUnit XML
vendor/bin/phpunit --log-junit tests/junit.xml tests

# TestDox
vendor/bin/phpunit --testdox tests

# Coverage Clover XML
vendor/bin/phpunit --coverage-clover tests/coverage.xml tests
```

## 🎯 TDD Workflow Commands

### **1. Red Phase (Write Failing Test)**
```bash
# Run specific test to see it fail
vendor/bin/phpunit tests/Unit/Auth/AuthServiceTest.php::it_should_return_success_when_valid_credentials_provided
```

### **2. Green Phase (Make Test Pass)**
```bash
# Run all tests to see them pass
vendor/bin/phpunit tests
```

### **3. Refactor Phase (Clean Up)**
```bash
# Run with coverage to ensure refactoring didn't break anything
vendor/bin/phpunit --coverage-html tests/coverage tests
```

## 📈 Monitoring Commands

### **Check Test Performance**
```bash
vendor/bin/phpunit --verbose --debug tests
```

### **Generate Test Statistics**
```bash
vendor/bin/phpunit --log-junit tests/stats.xml tests
```

### **Continuous Integration Ready**
```bash
# For CI/CD pipelines
vendor/bin/phpunit --coverage-clover coverage.xml --log-junit junit.xml tests
```

## 🎉 Success Indicators

When all tests pass, you should see:
- ✅ **51 tests, 102 assertions**
- ✅ **100% code coverage**
- ✅ **No failures or errors**
- ✅ **All TDD cycle phases complete**

This indicates a successful TDD implementation following the Red-Green-Refactor cycle!