# Tawazun – Mental Health Support System

A web-based platform that provides secure and confidential psychological support for university students. It connects students with psychologists through a structured digital environment, reducing barriers of time, location, and stigma.

> Graduation project – Software Engineering, Al-Azhar University (2025/2026)

## User Roles

**Student**
- Create an account and log in
- Submit psychological concerns
- Track the status of submitted requests
- View therapeutic tasks assigned by the psychologist
- Read mental health guidance and awareness content

**Psychologist**
- View and review student requests
- Assign therapeutic tasks
- Provide psychological guidance
- Update request status

**Administrator**
- Manage student and psychologist accounts
- Monitor system requests and activity
- Manage awareness and guidance content

## Built With

- Laravel (PHP) – MVC architecture
- MySQL
- HTML, CSS, JavaScript

## Getting Started

1. Clone the repository:
   ```bash
   git clone https://github.com/AlaaObaid2004/tawazun_final.git
   cd tawazun_final
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Create the environment file and generate the app key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Set your MySQL database details in `.env`, then run:
   ```bash
   php artisan migrate
   php artisan serve
   ```


[اكتب هون شو اشتغلت إنت: مثلاً UI design, واجهات الطالب, صفحات...]
