<?php

/**
 * TDD Test Runner - Demonstrates Red-Green-Refactor Cycle
 * 
 * This script shows how to run tests following TDD principles:
 * 1. RED: Write a failing test first
 * 2. GREEN: Write minimal code to make the test pass
 * 3. REFACTOR: Clean up the code while keeping tests green
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\TestCase;
use PHPUnit\TextUI\TestRunner;

class TDDTestRunner
{
    private $testResults = [];
    private $totalTests = 0;
    private $passedTests = 0;
    private $failedTests = 0;

    public function runAllTests()
    {
        echo "🧪 TDD Test Suite - Red-Green-Refactor Cycle\n";
        echo "==============================================\n\n";

        // Run Unit Tests
        $this->runUnitTests();
        
        // Run Integration Tests
        $this->runIntegrationTests();
        
        // Run DAO Tests
        $this->runDAOTests();
        
        // Display Summary
        $this->displaySummary();
    }

    private function runUnitTests()
    {
        echo "📋 UNIT TESTS (Red-Green-Refactor)\n";
        echo "-----------------------------------\n";

        // AuthService Tests
        $this->runTestSuite('Tests\Unit\Auth\AuthServiceTest', 'AuthService');
        
        // UserService Tests
        $this->runTestSuite('Tests\Unit\User\UserServiceTest', 'UserService');
        
        // Router Tests
        $this->runTestSuite('Tests\Unit\Core\RouterTest', 'Router');
        
        echo "\n";
    }

    private function runIntegrationTests()
    {
        echo "🔗 INTEGRATION TESTS (Red-Green-Refactor)\n";
        echo "------------------------------------------\n";

        // AuthController Tests
        $this->runTestSuite('Tests\Integration\Controllers\AuthControllerTest', 'AuthController');
        
        // AdminController Tests
        $this->runTestSuite('Tests\Integration\Controllers\AdminControllerTest', 'AdminController');
        
        echo "\n";
    }

    private function runDAOTests()
    {
        echo "🗄️  DAO TESTS (Red-Green-Refactor)\n";
        echo "-----------------------------------\n";

        // UserDAO Tests
        $this->runTestSuite('Tests\Unit\DAO\UserDAOTest', 'UserDAO');
        
        echo "\n";
    }

    private function runTestSuite($testClass, $componentName)
    {
        echo "Testing {$componentName}...\n";
        
        try {
            $suite = new TestSuite($testClass);
            $runner = new TestRunner();
            $result = $runner->run($suite);
            
            $this->totalTests += $result->count();
            $this->passedTests += $result->count() - $result->failureCount() - $result->errorCount();
            $this->failedTests += $result->failureCount() + $result->errorCount();
            
            if ($result->wasSuccessful()) {
                echo "✅ {$componentName} tests PASSED\n";
            } else {
                echo "❌ {$componentName} tests FAILED\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Error running {$componentName} tests: " . $e->getMessage() . "\n";
            $this->failedTests++;
        }
    }

    private function displaySummary()
    {
        echo "📊 TDD TEST SUMMARY\n";
        echo "===================\n";
        echo "Total Tests: {$this->totalTests}\n";
        echo "Passed: {$this->passedTests}\n";
        echo "Failed: {$this->failedTests}\n";
        echo "Success Rate: " . round(($this->passedTests / $this->totalTests) * 100, 2) . "%\n\n";

        if ($this->failedTests === 0) {
            echo "🎉 ALL TESTS PASSED! TDD Cycle Complete!\n";
            echo "✅ RED: Tests written first\n";
            echo "✅ GREEN: Minimal implementation to pass\n";
            echo "✅ REFACTOR: Clean, maintainable code\n";
        } else {
            echo "⚠️  Some tests failed. Review and fix before continuing.\n";
        }
    }

    public function demonstrateTDDCycle()
    {
        echo "🔄 TDD CYCLE DEMONSTRATION\n";
        echo "==========================\n\n";

        echo "1️⃣  RED PHASE: Write failing test first\n";
        echo "   - Write test that describes desired behavior\n";
        echo "   - Test should fail (Red)\n";
        echo "   - This ensures we're testing the right thing\n\n";

        echo "2️⃣  GREEN PHASE: Write minimal code to pass\n";
        echo "   - Write the simplest code that makes test pass\n";
        echo "   - Don't worry about elegance yet\n";
        echo "   - Just make it work (Green)\n\n";

        echo "3️⃣  REFACTOR PHASE: Clean up the code\n";
        echo "   - Improve code quality and readability\n";
        echo "   - Remove duplication\n";
        echo "   - Keep tests green while refactoring\n\n";

        echo "4️⃣  REPEAT: Continue the cycle\n";
        echo "   - Add more tests for new features\n";
        echo "   - Refactor as needed\n";
        echo "   - Maintain high test coverage\n\n";
    }
}

// Run the TDD Test Suite
if (php_sapi_name() === 'cli') {
    $runner = new TDDTestRunner();
    $runner->demonstrateTDDCycle();
    echo "\n";
    $runner->runAllTests();
} else {
    echo "This script should be run from the command line.\n";
    echo "Usage: php tests/TestRunner.php\n";
}