# PHP & MySQL Blog Project

ApexPlanet Software internship, Web Development (PHP & MySQL).

## Task 1: Development Environment Setup
- Local server: XAMPP (Apache + MySQL)
- Editor: Visual Studio Code
- Version control: Git and GitHub

## How to run
1. Copy this folder into `C:\xampp\htdocs\`
2. Start Apache and MySQL from the XAMPP Control Panel
3. Open http://localhost/blog in your browser

## Task 2: Basic CRUD Application
- MySQL database `blog` with `posts` and `users` tables
- Full CRUD (create, read, update, delete) for posts
- User registration and login with hashed passwords
- Session-based authentication; only logged-in users can add/edit/delete

## Task 3: Advanced Features Implementation
- Search posts by title or content
- Pagination (5 posts per page)
- Improved UI with custom CSS styling

## Task 4: Security Enhancements
- All database queries use prepared statements (PDO/MySQLi) to prevent SQL injection
- Server-side validation: username format/length, password length and confirmation, post title/content length
- Client-side validation via HTML5 attributes (required, minlength, maxlength, pattern)
- User roles (admin, editor) added to the users table
- Role-based access control: only admins can delete posts; editors can create/edit but not delete