---
title: CI/CD Integration Guide
---

# CI/CD Integration Guide

This guide provides examples for integrating Laravel Pint with the ArtisanPack UI code style into various CI/CD platforms.

## GitHub Actions

### Basic Workflow

Create `.github/workflows/code-style.yml`:

```yaml
name: Code Style

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  pint:
    name: Laravel Pint
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          coverage: none

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Run Pint
        run: ./vendor/bin/pint --test
```

### With PHPCS (Recommended)

```yaml
name: Code Style

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  code-style:
    name: Code Style Check
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          tools: cs2pr
          coverage: none

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Run Pint
        run: ./vendor/bin/pint --test

      - name: Run PHPCS
        run: ./vendor/bin/phpcs --standard=ArtisanPackUIStandard --report=checkstyle . | cs2pr
```

### Auto-fix and Commit

```yaml
name: Auto-fix Code Style

on:
  push:
    branches: [develop]

jobs:
  fix:
    name: Auto-fix with Pint
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4
        with:
          token: ${{ secrets.GITHUB_TOKEN }}

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          coverage: none

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Run Pint
        run: ./vendor/bin/pint

      - name: Commit changes
        uses: stefanzweifel/git-auto-commit-action@v5
        with:
          commit_message: "style: auto-fix code style with Pint"
          commit_author: "GitHub Actions <actions@github.com>"
```

## GitLab CI/CD

### Basic Pipeline

Create `.gitlab-ci.yml`:

```yaml
stages:
  - code-quality

variables:
  COMPOSER_CACHE_DIR: "$CI_PROJECT_DIR/.composer-cache"

cache:
  paths:
    - .composer-cache/
    - vendor/

pint:
  stage: code-quality
  image: php:8.2-cli
  before_script:
    - apt-get update && apt-get install -y git unzip
    - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    - composer install --no-interaction --prefer-dist
  script:
    - ./vendor/bin/pint --test
  rules:
    - if: $CI_PIPELINE_SOURCE == "merge_request_event"
    - if: $CI_COMMIT_BRANCH == "main"
    - if: $CI_COMMIT_BRANCH == "develop"
```

### With PHPCS

```yaml
stages:
  - code-quality

code-style:
  stage: code-quality
  image: php:8.2-cli
  before_script:
    - apt-get update && apt-get install -y git unzip
    - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    - composer install --no-interaction --prefer-dist
  script:
    - ./vendor/bin/pint --test
    - ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
  rules:
    - if: $CI_PIPELINE_SOURCE == "merge_request_event"
    - if: $CI_COMMIT_BRANCH == "main"
```

### Merge Request Validation

```yaml
pint:check:
  stage: code-quality
  image: php:8.2-cli
  script:
    - composer install --no-interaction
    - ./vendor/bin/pint --test
  rules:
    - if: $CI_PIPELINE_SOURCE == "merge_request_event"
  allow_failure: false
```

## Bitbucket Pipelines

Create `bitbucket-pipelines.yml`:

```yaml
image: php:8.2

pipelines:
  default:
    - step:
        name: Code Style
        caches:
          - composer
        script:
          - apt-get update && apt-get install -y git unzip
          - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
          - composer install --no-interaction --prefer-dist
          - ./vendor/bin/pint --test

  pull-requests:
    '**':
      - step:
          name: Code Style Check
          caches:
            - composer
          script:
            - apt-get update && apt-get install -y git unzip
            - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
            - composer install --no-interaction --prefer-dist
            - ./vendor/bin/pint --test
            - ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .

definitions:
  caches:
    composer: ~/.composer/cache
```

## CircleCI

Create `.circleci/config.yml`:

```yaml
version: 2.1

jobs:
  code-style:
    docker:
      - image: cimg/php:8.2
    steps:
      - checkout
      - restore_cache:
          keys:
            - composer-v1-{{ checksum "composer.lock" }}
            - composer-v1-
      - run:
          name: Install dependencies
          command: composer install --no-interaction --prefer-dist
      - save_cache:
          key: composer-v1-{{ checksum "composer.lock" }}
          paths:
            - vendor
      - run:
          name: Run Pint
          command: ./vendor/bin/pint --test
      - run:
          name: Run PHPCS
          command: ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .

workflows:
  version: 2
  code-quality:
    jobs:
      - code-style
```

## Azure DevOps

Create `azure-pipelines.yml`:

```yaml
trigger:
  branches:
    include:
      - main
      - develop

pr:
  branches:
    include:
      - main
      - develop

pool:
  vmImage: 'ubuntu-latest'

steps:
  - task: UsePHPVersion@0
    inputs:
      versionSpec: '8.2'

  - script: composer install --no-interaction --prefer-dist
    displayName: 'Install dependencies'

  - script: ./vendor/bin/pint --test
    displayName: 'Run Pint'

  - script: ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
    displayName: 'Run PHPCS'
```

## Jenkins

Create `Jenkinsfile`:

```groovy
pipeline {
    agent {
        docker {
            image 'php:8.2-cli'
        }
    }

    stages {
        stage('Install') {
            steps {
                sh 'apt-get update && apt-get install -y git unzip'
                sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
                sh 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Code Style') {
            parallel {
                stage('Pint') {
                    steps {
                        sh './vendor/bin/pint --test'
                    }
                }
                stage('PHPCS') {
                    steps {
                        sh './vendor/bin/phpcs --standard=ArtisanPackUIStandard .'
                    }
                }
            }
        }
    }

    post {
        always {
            cleanWs()
        }
    }
}
```

## Docker

### Dockerfile for CI

```dockerfile
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts

COPY . .

CMD ["./vendor/bin/pint", "--test"]
```

### Docker Compose for Local Development

```yaml
version: '3.8'

services:
  pint:
    build:
      context: .
      dockerfile: Dockerfile.pint
    volumes:
      - .:/app
    command: ./vendor/bin/pint
```

## Makefile

Create a `Makefile` for common commands:

```makefile
.PHONY: style style-fix style-check

# Run all style checks
style: style-check

# Auto-fix style issues
style-fix:
	./vendor/bin/pint

# Check style without fixing
style-check:
	./vendor/bin/pint --test
	./vendor/bin/phpcs --standard=ArtisanPackUIStandard .

# Check only changed files
style-dirty:
	./vendor/bin/pint --dirty --test
```

## Composer Scripts

Add to `composer.json`:

```json
{
  "scripts": {
    "pint": "./vendor/bin/pint",
    "pint:test": "./vendor/bin/pint --test",
    "phpcs": "./vendor/bin/phpcs --standard=ArtisanPackUIStandard .",
    "style": [
      "@pint:test",
      "@phpcs"
    ],
    "style:fix": "@pint"
  }
}
```

Usage:

```bash
composer style      # Check style
composer style:fix  # Auto-fix style
```

## Best Practices

1. **Fail Fast**: Run Pint early in your pipeline to catch issues quickly
2. **Cache Dependencies**: Cache Composer dependencies to speed up builds
3. **Parallel Execution**: Run Pint and PHPCS in parallel when possible
4. **Only Changed Files**: Use `--dirty` flag for faster local development
5. **Block Merges**: Configure branch protection to require passing style checks
6. **Auto-fix on Feature Branches**: Consider auto-fixing on feature branches only

## Related Documentation

- [Home](Home)
- [Customization Guide](Customization)
- [IDE Integration](Ide-Integration)
- [Rules Mapping](Rules-Mapping)
