# Testing Documentation

## Overview
This project uses PHPUnit for testing with a comprehensive test suite covering unit tests, integration tests, and MVC tests.

## Test Structure

### Unit Tests (`tests/Unit/`)
- **Admin Controller Tests**: 28 test methods covering admin dashboard, user management, student management, and **faculty management**
- **Auth Service Tests**: Authentication and authorization logic
- **User Service Tests**: User business logic
- **DAO Tests**: Data access layer
- **Core Tests**: Framework components

### Integration Tests (`tests/Integration/`)
- **Admin Controller Integration**: 11 test methods covering real database interactions for both student and **faculty operations**
- **Auth Controller Integration**: Authentication flow testing

### MVC Tests (`tests/mvc/`)
- **Auth Controller MVC**: End-to-end authentication testing
- **Role-based Access**: Admin, faculty, and student role validation

## Test Counts

### Total Test Methods: **38**
- **Unit Tests**: 27 methods
- **Integration Tests**: 11 methods  
- **MVC Tests**: 6 methods

### Recent Additions
- **Faculty Management Tests**: 7 new unit tests + 5 new integration tests
- **Complete CRUD Coverage**: Add, edit, delete operations for both students and faculty

## Running Tests

### Run All Tests
```bash
vendor/bin/phpunit
```

### Run Specific Test Suites
```bash
# Unit tests only
vendor/bin/phpunit tests/Unit/

# Integration tests only  
vendor/bin/phpunit tests/Integration/

# Admin controller tests only
vendor/bin/phpunit tests/Unit/Admin/
```

### Run with Coverage
```bash
vendor/bin/phpunit --coverage-html coverage/
```

## Test Categories

### 1. **Admin Management Tests** (Most Comprehensive)
- **Location**: `tests/Unit/Admin/AdminControllerTest.php`
- **Coverage**: 27 test methods
- **Features**: Dashboard, user management, student management, **faculty management**
- **Mock Strategy**: Complete dependency isolation

### 2. **Integration Tests** (Real Database)
- **Location**: `tests/Integration/Controllers/AdminControllerTest.php`
- **Coverage**: 11 test methods
- **Features**: End-to-end admin operations with real services
- **Database**: Uses actual database connections

### 3. **MVC Tests** (End-to-End)
- **Location**: `tests/mvc/AuthControllerTest.php`
- **Coverage**: 6 test methods
- **Features**: Complete authentication flow testing
- **HTTP**: Simulates real HTTP requests

## Faculty Management Testing

### New Test Coverage
The faculty management functionality includes comprehensive testing:

#### Unit Tests (8 methods)
- ✅ `addFaculty()` - Success and validation scenarios
- ✅ `editFaculty()` - Success and validation scenarios  
- ✅ `deleteFaculty()` - Success and validation scenarios
- ✅ Error handling for missing fields and invalid requests

#### Integration Tests (5 methods)
- ✅ Real database faculty creation
- ✅ Real database faculty updates
- ✅ Real database faculty deletion
- ✅ Authentication failure scenarios
- ✅ End-to-end workflow testing

### Test Scenarios Covered
1. **Successful Operations**
   - Create faculty with valid data
   - Update faculty information
   - Delete faculty members

2. **Validation Testing**
   - Missing required fields
   - Invalid request methods
   - Authentication requirements

3. **Error Handling**
   - Database operation failures
   - Service layer errors
   - Authentication failures

## Best Practices Implemented

### 1. **Test Isolation**
- Each test method is completely independent
- Clean session state between tests
- Mocked dependencies for unit tests

### 2. **Comprehensive Coverage**
- All public methods tested
- All private methods tested via reflection
- Edge cases and error scenarios covered

### 3. **Real-world Testing**
- Integration tests use actual database
- MVC tests simulate real HTTP requests
- Authentication flow fully tested

### 4. **Maintainable Test Code**
- Clear test method naming
- Consistent test structure
- Comprehensive assertions

## Running Faculty Management Tests

### Unit Tests Only
```bash
cd tests/Unit/Admin/
./run_tests.sh
```

### Integration Tests Only
```bash
vendor/bin/phpunit tests/Integration/Controllers/AdminControllerTest.php --testdox
```

### All Admin Tests
```bash
# Unit tests
vendor/bin/phpunit tests/Unit/Admin/

# Integration tests  
vendor/bin/phpunit tests/Integration/Controllers/AdminControllerTest.php

# Both together
vendor/bin/phpunit tests/Unit/Admin/ tests/Integration/Controllers/AdminControllerTest.php
```

## Test Data Management

### Sample Faculty Data
Tests use realistic faculty data:
- School IDs: `FAC001`, `FAC002`, etc.
- Names: `Dr. John Smith`, `Dr. Jane Doe`
- Roles: `faculty`
- Passwords: Configurable or default

### Database State
- Integration tests use real database
- Tests clean up after themselves
- No permanent data changes
- Isolated test environments

## Future Test Enhancements

### Potential Additions
1. **Faculty-Specific Fields**: Position, department, specialization
2. **Subject Assignment Tests**: Faculty-subject relationships
3. **Schedule Management**: Faculty availability testing
4. **Performance Metrics**: Faculty performance tracking
5. **Document Management**: Credentials and certifications

### Test Infrastructure
1. **Database Factories**: Generate test data
2. **Test Data Builders**: Construct complex test objects
3. **Parallel Testing**: Speed up test execution
4. **Continuous Integration**: Automated test running