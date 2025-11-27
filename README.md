# Expense Prediction System

A web-based expense prediction system.

## Quick Start (Docker)

You can run the entire project with a single command using Docker.

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed.

### Run the Application

1. **Clone the repository** (if you haven't already):
   ```bash
   git clone https://github.com/bishalranjit0606/ExpensePredictionSystem.git
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

### Default Credentials

Use the following credentials to log in to the application:

- **User Account**:
  - Username: `tester`
  - Password: `tester123`

- **Admin Account**:
  - Username: `admin`
  - Password: `admin123`

### Stop the Application

```bash
docker-compose down
```


