🗂️ TaskFlow Management System
<p align="center"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo"> </p>

TaskFlow Management System is a modern task and project management platform built with Laravel and MySQL, designed for teams to efficiently manage tasks, projects, and collaboration with advanced role-based access control.

⚡ Features
🧑‍💻 Authentication & Roles

✅ Manual login/register + Google OAuth

🔐 Email verification & secure password encryption

👤 Roles: Admin, Project Manager, Project Member, User

🕵️ Admin can switch to any user to manage tasks on their behalf

🛑 Soft-deleted users can log in view-only mode

📁 Projects & Tasks

Create, edit, delete projects

Assign tasks to users or project members

Set priority & status (Pending, In-progress, Completed, On-Hold)

Inline editing of tasks (status, priority, assignee)

Tag tasks for quick classification (Bug, Meeting, Design, Review)

Filter tasks by project, user, status, priority, tag, or date

🗓️ Calendar & Deadlines

Interactive day/week/month view

Task badges with color-coded status dots

Quick deadline tracking

📊 Analytics & Reports

Chart analytics: task status, total tasks per project, tasks per user, monthly completion trends

Generate custom reports in PDF, Excel, Word, PPT

🖌️ UI / UX

Responsive layout for desktop, tablet, mobile

Custom glassmorphism design for a modern interface

🛠️ Tech Stack

Backend: Laravel 12

Frontend: Bootstrap 5.3.2, Font Awesome 6

Database: MySQL

Authentication: Laravel Socialite (Google OAuth)

Styling: Custom CSS (Glassmorphism)

🚀 Getting Started
# Clone repo
git clone https://github.com/Dwip-Pandya/task-management-system.git
cd task-management-system

# Install dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Start the server
php artisan serve


🏆 Highlights

✅ Inline task editing & real-time updates

📊 Analytics dashboards with charts

👤 Role-based access with user switching

📅 Interactive calendar with color-coded task deadlines

<p align="center"> <a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/badge/Laravel-8-red?logo=laravel&logoColor=white" alt="Laravel"></a> <a href="https://getbootstrap.com" target="_blank"><img src="https://img.shields.io/badge/Bootstrap-5.3.2-blue?logo=bootstrap&logoColor=white" alt="Bootstrap"></a> <a href="https://www.mysql.com" target="_blank"><img src="https://img.shields.io/badge/MySQL-8-blue?logo=mysql&logoColor=white" alt="MySQL"></a> </p>

Built with ❤️ by Pandya Dwip
