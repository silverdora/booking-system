# Credentials for testing existing users
#### Salon owner:
* login: kirak@gmail.com
* password: 00000000

#### Customer:
* login: annamaria@gmail.com
* password: 00000000

#### Specialist:
* login: renatagibson@gmil.com
* password: 00000000

#### Receptionist:
* login: katem@gmail.com
* password: 00000000

# GitHub repository: 
https://github.com/silverdora/booking-system

# Docker template for PHP projects 
This is a copy of instructions for Docker setup which we used in classes and which was reused for this project.

It contains:
* NGINX webserver
* PHP FastCGI Process Manager with PDO MySQL support
* MariaDB (GPL MySQL fork)
* PHPMyAdmin
* Composer
* Composer package [nikic/fast-route](https://github.com/nikic/FastRoute) for routing

## Setup

1. Install Docker Desktop on Windows or Mac, or Docker Engine on Linux.
1. Clone the project

## Usage

In a terminal, from the cloned project folder, run:
```bash
docker compose up
```

### Composer Autoload

This template is configured to use Composer for PSR-4 autoloading:

- Namespace `App\\` is mapped to `app/src/`.

To install dependencies and generate the autoloader, run:

```bash
docker compose run --rm php composer install
```

If you add new classes or change namespaces, regenerate the autoloader:

```bash
docker compose run --rm php composer dump-autoload
```

Example usage is wired in `app/public/index.php` and a sample class exists at `app/src/hello.php`.

### NGINX

NGINX will now serve files in the app/public folder.

### PHPMyAdmin

PHPMyAdmin provides basic database administration. It is accessible at [localhost:8080](localhost:8080).

Credentials are defined in `docker-compose.yml`. They are: root/secret123


### Stopping the docker container

If you want to stop the containers, press Ctrl+C. 

Or run:
```bash
docker compose down
```


# Compliance with WCAG and GDPR
## Web Content Accessibility Guidelines (WCAG)
This project follows the basic principles of the Web Content Accessibility Guidelines (WCAG) to ensure that the application is accessible and usable for a wide range of users.

### 1. Semantic HTML structure
Semantic HTML elements are used consistently throughout the application to provide a clear document structure for screen readers and assistive technologies.
Examples:
* `<nav>` is used for the main navigation bar (see partials/header.php)
* `<main>` is used to wrap the main page content (see partials/header.php and partials/footer.php)
* Headings follow a logical hierarchy using `<h1>`–`<h4>` depending on page context (e.g. `app/src/Views/salons/index.php`, `app/src/Views/salons/salon.php`, `app/src/Views/appointments/index.php`)
* `<footer>` is used for global footer content (see `app/src/Views/partials/footer.php`)

### 2. Properly labeled form inputs
All form fields are explicitly associated with `<label>` elements using the for attribute.
This ensures compatibility with screen readers and improves usability.
Examples:
* Login form: `app/src/Views/authentication/login.php`
* Registration form: `app/src/Views/authentication/register.php`
* Edit profile form: `app/src/Views/partials/user_form.php`
* Booking forms: `app/src/Views/appointments/choose_service.php`, `app/src/Views/appointments/choose_date.php`

Example pattern used:<br>
`<label for="email" class="form-label">Email</label>` <br>
`<input id="email" name="email" type="email" class="form-control" required>`

### 3. No empty links or buttons

All links (`<a>`) and buttons (`<button>`) contain descriptive text that clearly explains their purpose.

Examples:
* Navigation links: “Salons”, “My profile”, “Appointments”, “Login”, “Register”
* Action buttons: “Book an appointment”, “Edit profile”, “Save changes”, “Cancel/Delete”
* There are no empty or icon-only buttons without accessible text.

### 4. Text resizing and responsive layouts
The layout uses Bootstrap 5 with flexible units and responsive grid classes (`container`, `row`, `col-*`, `d-flex`).
Text resizing (browser zoom) does not break the layout.

Examples:
* Responsive cards and lists in `app/src/Views/salons/index.php` and `app/src/Views/appointments/index.php`
* Mobile-friendly navigation with collapsible navbar (see `app/src/Views/partials/header.php`)

### 5. Contrast and readable text
The application uses a dark theme with sufficient contrast between text and background.
Examples:
* Primary text uses text-light
* Secondary text uses opacity-75 instead of low-contrast defaults
* Buttons use Bootstrap contrast-safe classes (`btn-primary`, `btn-outline-light`, `btn-danger`)
* Custom CSS avoids inline styles and follows accessibility-friendly color contrast rules (see `app/public/assets/css/main.css`)

## General Data Protection Regulation (GDPR)
This application complies with the GDPR requirements applicable in the Netherlands, focusing on lawful data processing, data minimization, and secure handling of personal data.
### 1. Personal data collected
The application processes only the personal data that is strictly necessary for its functionality:
* First name
* Last name
* Email address
* Phone number
* Appointment data (date, time, service)

This data is stored in the database for legitimate purposes such as user authentication and appointment management.

### 2. Lawful basis and purpose limitation
Personal data is collected only when users:
* Register an account
* Book or manage appointments

The data is used exclusively for:
* Account identification
* Communication related to appointments
* Managing salon services and bookings

No data is collected for marketing or unrelated purposes.

### 3. Session handling and authentication
User authentication is handled using server-side PHP sessions.
Example:
* Session usage in authentication logic (see `app/src/Views/partials/header.php` and authentication-related classes)
* Sessions store only the minimum required data (such as user ID and role) and are used solely for access control and authorization.

### 4. Data minimization and access control
* Users can view and edit their own personal data via the profile page (see `app/src/Views/users/profile/show.php` and `app/src/Views/users/profile/edit.php`)
* Role-based access control ensures that users can only access data relevant to their role (customer vs salon owner)
* Appointment data visibility is restricted based on user permissions

### 5. User control over personal data
Users are able to:
* View their stored personal data (“My profile”)
* Update their personal information
* Cancel appointments (where permitted)
* This aligns with GDPR principles of transparency, data accuracy, and user rights.