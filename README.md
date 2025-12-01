# Expense Prediction System

A smart web-based expense tracking and prediction system that helps users manage their finances, track spending patterns, and get personalized financial tips.

---

## Installation & Setup

### 1. Clone the Repository

First, clone the project to your local machine using Git:

```bash
git clone https://github.com/bishalranjit0606/ExpensePredictionSystem.git
cd ExpensePredictionSystem
```

---

### 2. Choose Your Setup Method

You can run this project using either **Docker (Recommended)** or **XAMPP (Manual Setup)**.

---

## Option A: Run with Docker (Easiest Way) 

You can run this project easily using Docker without setting up XAMPP manually.

### Prerequisites

- Make sure you have **Docker Desktop** installed and running.
- Download Docker Desktop: [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)

### Steps

1. **Open your terminal or command prompt.**

2. **Navigate to the project directory:**
   ```bash
   cd ExpensePredictionSystem
   ```

3. **Run the following command to start the app:**
   ```bash
   docker-compose up -d --build
   ```

4. **Open your browser and go to:**
   - **Application**: [http://localhost:8080](http://localhost:8080)
   - **phpMyAdmin**: [http://localhost:8081](http://localhost:8081)
     - Server: `db`
     - Username: `root`
     - Password: `rootpassword`

**That's it!** The database and everything else will be set up automatically. ✅

### Default Login Credentials

Use the following credentials to log in to the application:

- **User Account**:
  - Username: `tester`
  - Password: `password`

- **Admin Account**:
  - Username: `admin`
  - Password: `admin`

### Stopping the App

To stop the project, run:

```bash
docker-compose down
```

To stop and remove all data (fresh start):

```bash
docker-compose down -v
```

---

## Option B: Manual Setup with XAMPP

If you prefer to run the project manually using XAMPP, follow these steps:

### 1. Move Project Files

- Copy the `ExpensePredictionSystem` folder.
- Paste it into your XAMPP `htdocs` directory:
  - **Mac**: `/Applications/XAMPP/xamppfiles/htdocs/`
  - **Windows**: `C:\xampp\htdocs\`

### 2. Configure Database

1. Start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Create a new database named `expense_db`.
4. Click on the database name, then go to the **Import** tab.
5. Choose the file `database/expense_db.sql` from the project folder.
6. Click **Go** to import the tables.

### 3. Configure Database Connection

Ensure your `includes/config.php` file is set to use the correct port (default is `3306`). If you changed your MySQL port in XAMPP, update it in this file.

### 4. Run the Website

Open your browser and visit:

```
http://localhost/ExpensePredictionSystem
```

---

## About

This project is a web-based **Expense Prediction System** designed to help users track their expenses, analyze spending patterns, and receive personalized financial tips. The system uses historical expense data to predict future spending and provides actionable insights to help users manage their finances better.

### Key Features:

- 📊 **Expense Tracking**: Record and categorize daily expenses
- 📈 **Spending Analysis**: Visualize spending patterns with charts
- 🔮 **Expense Prediction**: Predict future expenses based on historical data
- 💡 **Financial Tips**: Get personalized money-saving tips
- 👥 **User & Admin Panels**: Separate interfaces for users and administrators
- 🔐 **Secure Authentication**: Password-protected user accounts

---

## Technologies Used

- **Backend**: PHP 8.2
- **Database**: MySQL 8.0
- **Frontend**: HTML, CSS, JavaScript
- **Containerization**: Docker & Docker Compose
- **Web Server**: Apache

---

## Project Structure

```
ExpensePredictionSystem/
├── admin/              # Admin panel files
├── user/               # User panel files
├── includes/           # Shared configuration and utilities
├── database/           # Database SQL file
├── Dockerfile          # Docker configuration (multi-stage)
├── docker-compose.yml  # Docker Compose orchestration
└── index.php           # Main entry point
```

---

## Support

For issues or questions, please open an issue on the [GitHub repository](https://github.com/bishalranjit0606/ExpensePredictionSystem/issues).

---


