<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - TaskFlow Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <!-- Navigation -->
    <nav class="nav-glass">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="#" class="nav-brand">
                    <i class="fas fa-tasks"></i>
                    TaskFlow
                </a>
                <div class="nav-links">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#how-it-works" class="nav-link">How It Works</a>
                    <a href="#testimonials" class="nav-link">Reviews</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Revolutionize Your
                    <br>Task Management
                </h1>
                <p class="hero-subtitle">
                    The Ultimate Productivity Solution for Teams and Individuals
                </p>
                <p class="hero-description">
                    Experience seamless task management with advanced role-based access control,
                    Google integration, and powerful analytics. Transform the way your team collaborates
                    and achieves goals with our comprehensive platform.
                </p>

                <div class="auth-section">
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="glass-btn-large primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Login to Dashboard
                        </a>
                        <a href="{{ route('register') }}" class="glass-btn-large success">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </a>

                    </div>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">
                        <i class="fas fa-lock"></i> Secure authentication •
                        <i class="fas fa-rocket"></i> Instant setup •
                        <i class="fas fa-gift"></i> Free to start
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title">Powerful Features</h2>
            <p class="section-subtitle">
                Everything you need to manage tasks efficiently and boost productivity
            </p>

            <div class="features-grid">
                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3 class="feature-title">Advanced Task Management</h3>
                    <p class="feature-description">
                        Create, organize, and track tasks with multiple status levels including pending,
                        in-progress, completed, and on-hold. Set priorities, deadlines, and detailed descriptions
                        to keep your projects on track.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Secure Authentication</h3>
                    <p class="feature-description">
                        Multiple authentication options including traditional login/register and Google OAuth.
                        Secure password encryption, session management, and email verification ensure
                        your data is always protected.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="feature-title">Role-Based Access Control</h3>
                    <p class="feature-description">
                        Comprehensive permission system with Admin and User roles. Admins get full control
                        over user management, task assignment, and system settings, while users have
                        secure access to their assigned tasks.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3 class="feature-title">Complete User Management</h3>
                    <p class="feature-description">
                        Full CRUD operations for user management. Admins can create, read, update,
                        and delete user accounts, manage permissions, view user activity, and
                        maintain team structure efficiently.
                    </p>
                </div>


                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fab fa-google"></i>
                    </div>
                    <h3 class="feature-title">Google Integration</h3>
                    <p class="feature-description">
                        Seamless Google OAuth integration for quick and secure access.
                        Sync with Google Workspace, import contacts, and leverage Google's
                        robust security infrastructure for enhanced protection.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Responsive Design</h3>
                    <p class="feature-description">
                        Fully responsive interface that works perfectly on desktop, tablet,
                        and mobile devices. Access your tasks anywhere, anytime with
                        consistent user experience across all platforms.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 class="feature-title">Project Management</h3>
                    <p class="feature-description">
                        Create and manage multiple projects. Assign tasks to specific projects
                        for better organization and easy tracking of team progress.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h3 class="feature-title">Advanced Task Filtering</h3>
                    <p class="feature-description">
                        Filter tasks by project, status, priority, tag, assigned user, and date.
                        Quickly find the tasks you need and prioritize efficiently.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="feature-title">Report Generation</h3>
                    <p class="feature-description">
                        Generate Excel, PDF, Word, and PowerPoint reports with customizable filters.
                        Export data for presentations, audits, or record-keeping in seconds.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="feature-title">Task Deadline Calendar</h3>
                    <p class="feature-description">
                        View all task deadlines in an interactive calendar. Easily track due dates,
                        plan your schedule, and never miss important deadlines.
                    </p>
                </div>

                <div class="glass-card feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3 class="feature-title">Enhanced Admin Dashboard</h3>
                    <p class="feature-description">
                        Access all critical metrics, tasks, projects, users, and reports
                        from a single comprehensive dashboard for faster decision-making.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">
                Get started in minutes with our simple 4-step process
            </p>

            <div class="steps-container">
                <div class="step-item glass-card">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3 class="step-title">Create Your Account</h3>
                        <p class="step-description">
                            Sign up using email or Google authentication. Choose your role and
                            complete the simple onboarding process. Email verification ensures
                            account security from day one.
                        </p>
                    </div>
                </div>

                <div class="step-item glass-card">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3 class="step-title">Set Up Your Team</h3>
                        <p class="step-description">
                            Invite team members, assign roles, and configure permissions.
                            Create your organizational structure with admins and users.
                            Import existing team data or start fresh.
                        </p>
                    </div>
                </div>

                <div class="step-item glass-card">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3 class="step-title">Create & Assign Tasks</h3>
                        <p class="step-description">
                            Start creating tasks with detailed descriptions, priorities, and deadlines.
                            Assign tasks to team members, set status levels, and organize projects
                            for maximum efficiency.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Users Say</h2>
            <p class="section-subtitle">
                Trusted by thousands of teams worldwide
            </p>

            <div class="testimonials-grid">
                <div class="glass-card testimonial-card">
                    <p class="testimonial-quote">
                        "TaskFlow has completely transformed how our team manages projects.
                        The role-based access and Google integration make everything seamless.
                        Our productivity has increased by 40% since implementation."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">MR</div>
                        <div class="author-info">
                            <h4>Michael Rodriguez</h4>
                            <p>Operations Director, StartupHub</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card testimonial-card">
                    <p class="testimonial-quote">
                        "The Google authentication and mobile responsiveness are game-changers.
                        Our remote team can access tasks from anywhere, and the real-time
                        notifications keep everyone synchronized perfectly."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">EM</div>
                        <div class="author-info">
                            <h4>Emily Martinez</h4>
                            <p>Team Lead, DigitalAgency</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card testimonial-card">
                    <p class="testimonial-quote">
                        "Security was our top concern, and TaskFlow delivered. The role-based
                        access control and secure authentication give us complete confidence
                        in protecting our sensitive project data."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">DK</div>
                        <div class="author-info">
                            <h4>David Kim</h4>
                            <p>IT Security Manager, FinanceFirst</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section" style="padding: 100px 0;">
        <div class="container">
            <h2 class="section-title">Choose Your Plan</h2>
            <p class="section-subtitle">
                Flexible pricing options for teams of all sizes
            </p>

            <div class="pricing-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3rem; margin-top: 4rem;">
                <div class="glass-card" style="padding: 3rem 2rem; text-align: center; position: relative;">
                    <h3 style="font-size: 1.8rem; color: var(--text-primary); margin-bottom: 1rem; font-weight: 600;">Starter</h3>
                    <div style="font-size: 3rem; color: var(--success-color); margin-bottom: 0.5rem; font-weight: 800;">Free</div>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">Perfect for individuals</p>

                    <div style="text-align: left; margin-bottom: 3rem;">
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Up to 5 tasks per month
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Basic task management
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Email notifications
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Mobile access
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="glass-btn-large success" style="width: 100%;">
                        Get Started Free
                    </a>
                </div>

                <div class="glass-card" style="padding: 3rem 2rem; text-align: center; position: relative; border: 2px solid var(--primary-color); transform: scale(1.05);">
                    <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary-color); color: white; padding: 0.5rem 2rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                        MOST POPULAR
                    </div>
                    <h3 style="font-size: 1.8rem; color: var(--text-primary); margin-bottom: 1rem; font-weight: 600;">Professional</h3>
                    <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 0.5rem; font-weight: 800;">$19</div>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">per user/month</p>

                    <div style="text-align: left; margin-bottom: 3rem;">
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Unlimited tasks
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Advanced task management
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Team collaboration
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Real-time notifications
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Analytics dashboard
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Google integration
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="glass-btn-large primary" style="width: 100%;">
                        Start Professional
                    </a>
                </div>

                <div class="glass-card" style="padding: 3rem 2rem; text-align: center; position: relative;">
                    <h3 style="font-size: 1.8rem; color: var(--text-primary); margin-bottom: 1rem; font-weight: 600;">Enterprise</h3>
                    <div style="font-size: 3rem; color: var(--warning-color); margin-bottom: 0.5rem; font-weight: 800;">$49</div>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">per user/month</p>

                    <div style="text-align: left; margin-bottom: 3rem;">
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Everything in Professional
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Advanced user management
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Custom integrations
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Priority support
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Advanced security features
                        </div>
                        <div style="color: var(--text-secondary); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color);"></i>
                            Custom branding
                        </div>
                    </div>

                    <a href="#contact" class="glass-btn-large" style="width: 100%; border-color: var(--warning-color); box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section" style="padding: 100px 0;">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">
                Everything you need to know about TaskFlow
            </p>

            <div class="faq-container" style="max-width: 800px; margin: 4rem auto 0;">
                <div class="glass-card" style="margin-bottom: 2rem; padding: 2rem;">
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem; font-weight: 600;">
                        How secure is my data on TaskFlow?
                    </h4>
                    <p style="color: var(--text-muted); line-height: 1.7;">
                        We implement enterprise-grade security including AES-256 encryption, secure authentication,
                        role-based access control, and regular security audits. Your data is stored in secure data centers
                        with 24/7 monitoring and backup systems.
                    </p>
                </div>

                <div class="glass-card" style="margin-bottom: 2rem; padding: 2rem;">
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem; font-weight: 600;">
                        Can I integrate TaskFlow with other tools?
                    </h4>
                    <p style="color: var(--text-muted); line-height: 1.7;">
                        Yes! TaskFlow offers seamless Google integration, and our Enterprise plan includes custom
                        integrations with popular tools like Slack, Microsoft Teams, Jira, and more. We also provide
                        REST APIs for custom integrations.
                    </p>
                </div>

                <div class="glass-card" style="margin-bottom: 2rem; padding: 2rem;">
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem; font-weight: 600;">
                        What's the difference between Admin and User roles?
                    </h4>
                    <p style="color: var(--text-muted); line-height: 1.7;">
                        Admins have full system access including user management, task creation and assignment,
                        system configuration, and analytics. Users can view and manage their assigned tasks,
                        update task status, and collaborate with team members.
                    </p>
                </div>

                <div class="glass-card" style="margin-bottom: 2rem; padding: 2rem;">
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem; font-weight: 600;">
                        Is there a mobile app available?
                    </h4>
                    <p style="color: var(--text-muted); line-height: 1.7;">
                        TaskFlow is fully responsive and works perfectly on all mobile devices through your web browser.
                        Native mobile apps for iOS and Android are currently in development and will be available soon.
                    </p>
                </div>

                <div class="glass-card" style="margin-bottom: 2rem; padding: 2rem;">
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem; font-weight: 600;">
                        Can I cancel my subscription anytime?
                    </h4>
                    <p style="color: var(--text-muted); line-height: 1.7;">
                        Absolutely! You can cancel your subscription at any time from your account settings.
                        Your account will remain active until the end of your current billing period,
                        and you can export all your data before cancellation.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="glass cta-content">
                <h2 class="cta-title">Ready to Transform Your Productivity?</h2>
                <p class="cta-description">
                    Join thousands of teams already using TaskFlow to streamline their workflow
                    and achieve better results. Start your free trial today and experience the difference.
                </p>
                <div class="auth-buttons">
                    <a href="{{ route('register') }}" class="glass-btn-large primary">
                        <i class="fas fa-rocket"></i>
                        Start Free Trial
                    </a>
                    <a href="#contact" class="glass-btn-large">
                        <i class="fas fa-phone"></i>
                        Schedule Demo
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" style="padding: 100px 0; background: var(--glass-bg); backdrop-filter: blur(15px); border-top: 1px solid var(--glass-border);">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">
                Have questions? We're here to help you succeed
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-top: 4rem;">
                <div class="glass-card" style="padding: 3rem 2rem; text-align: center;">
                    <div class="feature-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.3rem;">Email Support</h4>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                        Get help from our expert support team
                    </p>
                    <a href="mailto:support@taskflow.com" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                        support@taskflow.com
                    </a>
                </div>

                <div class="glass-card" style="padding: 3rem 2rem; text-align: center;">
                    <div class="feature-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.3rem;">Live Chat</h4>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                        Chat with us in real-time during business hours
                    </p>
                    <button class="glass-btn-large primary" style="border: none; cursor: pointer;">
                        Start Chat
                    </button>
                </div>

                <div class="glass-card" style="padding: 3rem 2rem; text-align: center;">
                    <div class="feature-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.3rem;">Phone Support</h4>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                        Speak directly with our support specialists
                    </p>
                    <a href="tel:+1-555-0123" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                        +1 (555) 012-3456
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem; text-align: left;">
                    <div>
                        <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.2rem;">TaskFlow</h5>
                        <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem;">
                            The ultimate task management solution for teams and individuals.
                            Streamline your workflow and boost productivity with our comprehensive platform.
                        </p>
                        <div style="display: flex; gap: 1rem;">
                            <a href="#" style="color: var(--text-muted); font-size: 1.5rem; transition: color 0.3s ease;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" style="color: var(--text-muted); font-size: 1.5rem; transition: color 0.3s ease;">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" style="color: var(--text-muted); font-size: 1.5rem; transition: color 0.3s ease;">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.1rem;">Product</h5>
                        <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                            <a href="#features" style="color: var(--text-muted); text-decoration: none;">Features</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Pricing</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Integrations</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">API</a>
                        </div>
                    </div>

                    <div>
                        <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.1rem;">Company</h5>
                        <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">About Us</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Careers</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Blog</a>
                            <a href="#contact" style="color: var(--text-muted); text-decoration: none;">Contact</a>
                        </div>
                    </div>

                    <div>
                        <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.1rem;">Support</h5>
                        <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Help Center</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Documentation</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Status</a>
                            <a href="#" style="color: var(--text-muted); text-decoration: none;">Community</a>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid var(--glass-border); padding-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted); font-size: 0.9rem;">
                        © 2024 TaskFlow. All rights reserved. |
                        <a href="#" style="color: var(--text-muted); text-decoration: none;">Privacy Policy</a> |
                        <a href="#" style="color: var(--text-muted); text-decoration: none;">Terms of Service</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all glass cards
        document.querySelectorAll('.glass-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>

</html>