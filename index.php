<?php include 'includes/header.php'; ?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-text">
                <h1>Welcome to Connect</h1>
                <p>Your gateway to seamless learning, collaboration, and campus updates at your Institute.</p>
                <a href="student_login.php" class="btn btn-primary">Get Started</a>
            </div>
            <div class="hero-image">
                <!-- Icon-based hero illustration -->
                <i class="hero-icon fa-solid fa-network-wired"></i>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Our Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fa-solid fa-graduation-cap fa-3x feature-icon"></i>
                    <h3>Student Dashboard</h3>
                    <p>Track attendance, marks, and access resources all in one place.</p>
                </div>
                <div class="feature-card">
                    <i class="fa-solid fa-chalkboard-teacher fa-3x feature-icon"></i>
                    <h3>Faculty Dashboard</h3>
                    <p>Manage courses, monitor performance, and interact with students.</p>
                </div>
                <div class="feature-card">
                    <i class="fa-solid fa-users fa-3x feature-icon"></i>
                    <h3>Collaboration</h3>
                    <p>Engage in discussions, post queries, and share learning materials.</p>
                </div>
                <div class="feature-card">
                    <i class="fa-solid fa-calendar-check fa-3x feature-icon"></i>
                    <h3>Events & Notifications</h3>
                    <p>Stay updated on campus events, workshops, and important announcements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlights Section -->
    <section class="highlights">
        <div class="container highlights-container">
            <div class="highlight-card">
                <i class="fa-solid fa-lock fa-2x"></i>
                <h4>Secure Login</h4>
                <p>Role-based access ensures data privacy and security for all users.</p>
            </div>
            <div class="highlight-card">
                <i class="fa-solid fa-mobile-screen-button fa-2x"></i>
                <h4>Fully Responsive</h4>
                <p>Accessible on any device — desktop, tablet, or mobile.</p>
            </div>
            <div class="highlight-card">
                <i class="fa-solid fa-moon-stars fa-2x"></i>
                <h4>Dark/Light Mode</h4>
                <p>Switch between themes for comfortable viewing anytime.</p>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="cta">
        <div class="container cta-container">
            <h2>Ready to Connect?</h2>
            <p>Sign up or log in to start your journey with Connect today!</p>
            <a href="student_login.php" class="btn btn-primary">Student Login</a>
            <a href="faculty_login.php" class="btn btn-secondary">Faculty Login</a>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>


