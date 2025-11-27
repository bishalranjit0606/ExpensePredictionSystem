# Expense Prediction System

A web-based expense prediction system.

## Quick Start (Docker)

You can run the entire project with a single command using Docker.

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed.

### Run the Application

1. **Clone the repository** (if you haven't already):
   ```bash
   git clone <repository-url>
   cd ExpensePredictionSystem
   ```

2. **Start the application**:
   ```bash
   docker-compose up -d --build
   ```

3. **Access the application**:
   - Web App: [http://localhost:8080](http://localhost:8080)
   - phpMyAdmin: [http://localhost:8081](http://localhost:8081)

### Database Access

You can access the database in two ways:

1.  **Via phpMyAdmin (Recommended)**:
    - Open [http://localhost:8081](http://localhost:8081)
    - Server: `db`
    - Username: `root`
    - Password: `rootpassword`

2.  **Via Local Client (e.g., MySQL Workbench)**:
    - Host: `127.0.0.1`
    - Port: `3307`
    - Username: `root`
    - Password: `rootpassword`

### Stop the Application

```bash
docker-compose down
```

## Troubleshooting

**"Mounts denied" on macOS:**
If you see a "mounts denied" error, it means your project is in a directory not shared with Docker (like `/Applications/XAMPP`).
- **Solution 1:** Move the project to your User directory (e.g., `~/Documents/ExpensePredictionSystem`).
- **Solution 2:** Add the current path to Docker File Sharing resources in Docker Desktop settings.
