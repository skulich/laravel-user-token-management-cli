# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.2.0] - 2026-04-02

- Add Laravel 13 support
- Fix stale token list after deletion in `user:token:delete`
- Add CI matrix for Laravel 12/13 and PHP 8.3/8.4/8.5
- Apply Rector code quality improvements (type declarations, arrow functions, `::class` constants)

## [1.1.1] - 2025-12-26

- Fix `truncate` method not found error when using `laravel/prompts` >= 0.3.9
- Remove unused `Colors` and `DrawsBoxes` traits from commands that don't need them

## [1.1.0] - 2025-12-22

- Add `user:list` command

## [1.0.0] - 2025-12-21

- Initial implementation
