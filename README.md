# Activity Tracker

PHP activity tracking application with admin dashboard and user session management.

## Requirements

- Make utility
- npm
- Docker & Docker Compose

## Quick Start

1. Clone repository and navigate to directory:
```bash
git clone https://github.com/DimasPng/tracker.git
cd tracker
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Initialize application:
```bash
make init
```

4. Open in browser: **http://localhost:8080**

## Project Structure

```
public/index.php   # Application entry point

src/
├── Controller/    # Request handlers
├── Service/       # Business logic
├── Repository/    # Data access layer
├── DTO/           # Data transfer objects
├── Middleware/    # Request middleware
├── Core/          # Application core
└── Factory/       # Object factories

routes/
└── web.php        # Application routes

resources/views/   # Templates
database/          # Migrations & seeds
```

## Main Components

- **Routes**: `routes/web.php`
- **Controllers**: `src/Controller/`
- **Services**: `src/Service/`
- **Repositories**: `src/Repository/`

## Technology Stack

- **Backend**: PHP 8.3
- **Database**: MySQL 8.3
- **Frontend**: HTML, CSS (Tailwind), JavaScript
- **Infrastructure**: Docker, Docker Compose
- **Build Tools**: Make, npm
