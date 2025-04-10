# Docker Client for PHP - Project Guide

## Project Overview

This project is a PHP client library for the Docker API, designed to provide a clean and intuitive interface for interacting with Docker resources. The library follows Domain-Driven Design (DDD) principles and uses a repository pattern to abstract the Docker API interactions.

## Architecture

The project follows a layered architecture:

1. **Domain Layer** (`src/Domain/`)
   - Contains the core business logic and entities
   - Defines interfaces for repositories
   - Includes value objects and domain services

2. **Repository Layer** (`src/Repositories/`)
   - Implements the repository interfaces
   - Handles communication with the Docker API
   - Transforms API responses into domain objects

3. **Exception Layer** (`src/Exceptions/`)
   - Contains custom exceptions for the library
   - Provides detailed error information

## Design Principles

1. **Domain-Driven Design (DDD)**
   - Rich domain model with encapsulated business logic
   - Value objects for immutable concepts
   - Aggregates for transactional boundaries

2. **Repository Pattern**
   - Abstracts data access
   - Provides a consistent interface for domain objects
   - Isolates Docker API implementation details

3. **SOLID Principles**
   - Single Responsibility Principle: Each class has one reason to change
   - Open/Closed Principle: Open for extension, closed for modification
   - Liskov Substitution Principle: Subtypes must be substitutable for their base types
   - Interface Segregation Principle: Clients should not depend on interfaces they don't use
   - Dependency Inversion Principle: High-level modules should not depend on low-level modules

4. **Clean Code**
   - Meaningful names
   - Small, focused functions
   - Clear and concise documentation
   - Consistent formatting

## Development Workflow

1. **Feature Development**
   - Create a feature branch from `main`
   - Implement the feature with tests
   - Run all tests and linters
   - Create a pull request

2. **Code Review**
   - All code changes must be reviewed
   - Address review comments
   - Ensure all tests pass

3. **Continuous Integration**
   - Automated tests on pull requests
   - Code quality checks
   - Coverage reports

## Testing Strategy

1. **Unit Tests**
   - Test individual components in isolation
   - Mock dependencies
   - Focus on behavior, not implementation

2. **Feature Tests**
   - Test complete features
   - May use real Docker API (in integration tests)
   - Verify end-to-end functionality

3. **Code Coverage**
   - Aim for high test coverage
   - Focus on critical paths
   - Use coverage reports to identify gaps

## Code Style

The project follows PSR-12 coding standards with some additional rules:

- Use strict types
- Use constructor property promotion
- Use typed properties
- Use return type declarations
- Use union types and nullable types where appropriate

## Documentation

- PHPDoc comments for all classes, methods, and properties
- README.md for project overview
- Examples for common use cases
- API documentation for public interfaces

## Release Process

1. **Versioning**
   - Follow Semantic Versioning (SemVer)
   - Major version: Incompatible API changes
   - Minor version: Add functionality in a backward-compatible manner
   - Patch version: Backward-compatible bug fixes

2. **Changelog**
   - Keep a detailed changelog
   - Document breaking changes
   - Include migration guides when necessary

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linters
5. Submit a pull request

## Getting Help

- Check the documentation
- Open an issue for bugs
- Use discussions for questions
- Contact the maintainers for critical issues
