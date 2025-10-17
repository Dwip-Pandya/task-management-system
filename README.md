# TaskFlow Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3.2-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</p>

## 📋 Overview

TaskFlow is a modern, full-featured task and project management platform built with Laravel and MySQL. Designed to streamline team collaboration and project tracking, TaskFlow provides an intuitive interface for managing tasks, projects, and teams with powerful filtering, calendar views, and comprehensive reporting capabilities.

## ✨ Key Features

### 🎯 Task Management
- **Create, Assign & Track Tasks** - Efficiently manage tasks with detailed information including descriptions, deadlines, priorities, and assignments
- **Inline Editing** - Update task status, priority, and assignee directly from the dashboard without page reloads
- **Advanced Filtering** - Filter tasks by project, status, priority, assigned user, tags, and date ranges
- **Task Tags** - Organize and categorize tasks with custom tags for better organization
- **Threaded Comments** - Collaborate on tasks with nested discussion threads

### 📅 Calendar Module
- **Multiple Views** - Display tasks in day, week, or month calendar views
- **Color-Coded Badges** - Visual identification of task status with color-coded indicators
- **Deadline Tracking** - Quick visual identification of upcoming and overdue tasks
- **Interactive Interface** - Click on tasks to view details and make updates

### 👥 Role-Based Access Control
Four predefined roles with specific permissions:

- **Admin** - Full system control including user management, task oversight, project administration, report generation, and user impersonation
- **Project Manager** - Create and manage projects, assign tasks, monitor team activity, and generate project reports
- **Project Member** - View assigned tasks, update task statuses, participate in discussions, and track personal workload
- **User** - Manage personal tasks, update own task statuses, and participate in task discussions

### 🔐 Advanced User Management
- **Soft Delete Functionality** - Deactivated users can log in with view-only access
- **User Impersonation** - Admins can switch to any user account to perform actions on their behalf
- **Google OAuth Integration** - Seamless login with Google accounts via Laravel Socialite

### 📊 Analytics & Reporting
- **Interactive Dashboards** - Visual analytics with charts showing:
  - Task status distribution
  - Total tasks per project
  - Tasks assigned per user
  - Monthly task completion trends
- **Multi-Format Export** - Generate and export reports in PDF, Excel, Word, and PowerPoint formats
- **Filtered Reports** - Create custom reports based on various filter criteria

### 🎨 Modern UI/UX
- **Glassmorphism Design** - Polished, modern interface with custom glassmorphism CSS
- **Responsive Layout** - Built with Bootstrap 5.3.2 for seamless mobile and desktop experience
- **Font Awesome Icons** - Professional iconography throughout the application

## 🛠️ Technology Stack

### Backend
- **Laravel 11.x** - Modern PHP framework
- **MySQL 8.0+** - Relational database management
- **PHP 8.2+** - Server-side programming language

### Frontend
- **Bootstrap 5.3.2** - Responsive CSS framework
- **Font Awesome 6** - Icon library
- **Custom Glassmorphism CSS** - Modern UI styling
- **Chart Libraries** - Interactive data visualization

### Authentication & Integration
- **Laravel Socialite** - Google OAuth authentication
- **Laravel Breeze/Sanctum** - API authentication (if applicable)

### Additional Packages
- Export libraries for PDF, Excel, Word, and PowerPoint generation
- Calendar rendering libraries
- Chart/graph visualization packages

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for asset compilation)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/task-management-system.git
   cd task-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your database**
   
   Edit the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=taskflow_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Configure Google OAuth (Optional)**
   
   Add your Google OAuth credentials to `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Compile assets**
   ```bash
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   
   Open your browser and navigate to `http://localhost:8000`

## 👤 Default Credentials

After running the seeders, you can log in with these default accounts:

- **Admin Account**
  - Email: `admin@taskflow.com`
  - Password: `password`

- **Project Manager Account**
  - Email: `manager@taskflow.com`
  - Password: `password`

- **User Account**
  - Email: `user@taskflow.com`
  - Password: `password`

> ⚠️ **Important:** Change these default passwords immediately in production environments.

## 📖 Usage

### Creating a Project
1. Log in as Admin or Project Manager
2. Navigate to Projects → Create New Project
3. Fill in project details (name, description, deadline)
4. Assign team members to the project

### Managing Tasks
1. Navigate to the Tasks section
2. Click "Create Task" to add a new task
3. Assign task to a user and set priority
4. Use inline editing to quickly update task status
5. Add tags and comments for better organization

### Using the Calendar
1. Navigate to the Calendar view
2. Switch between day, week, or month views
3. Click on any task to view or edit details
4. Use color codes to identify task status at a glance

### Generating Reports
1. Navigate to Reports section
2. Apply desired filters (project, date range, status, etc.)
3. Preview the report
4. Export in your preferred format (PDF, Excel, Word, PowerPoint)

## 🔒 Security Features

- Password hashing with bcrypt
- CSRF protection on all forms
- SQL injection prevention with Eloquent ORM
- XSS protection with Blade templating
- Role-based middleware for route protection
- Soft delete for data retention and audit trails

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel Framework - For the excellent PHP framework
- Bootstrap Team - For the responsive CSS framework
- Font Awesome - For the comprehensive icon library
- All contributors and supporters of this project

## 📧 Support

For support, email support@taskflow.com or open an issue in the GitHub repository.

## 🗺️ Roadmap

- [ ] Real-time notifications
- [ ] Mobile application (iOS/Android)
- [ ] Kanban board view
- [ ] Time tracking functionality
- [ ] File attachments for tasks
- [ ] Advanced project templates
- [ ] Integration with third-party tools (Slack, Teams, etc.)
- [ ] API documentation for external integrations

---

<p align="center">Made with ❤️ by the TaskFlow Team</p>
