
# Dragon MVC Project

This project is built using the Model-View-Controller (MVC) architecture with PHP.

## Table of Contents

- [Overview](#overview)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Project Structure](#project-structure)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Overview

The goal of this project is to develop a scalable and maintainable application using the MVC architecture. It leverages PSR-4 autoloading for proper namespace management and includes UUID generation using the `ramsey/uuid` library.

## Getting Started

### Prerequisites

- **PHP**: Version 7.4 or higher
- **Composer**: Dependency Manager for PHP

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/ayrzDev/Dragon.git
   ```

2. **Install dependencies**

   Run the following command to install the required dependencies via Composer:

   ```bash
   composer install
   ```

3. **Start the application**

   ```bash
   php -S localhost:8000
   ```

## Project Structure

```
Dragon/
├── .vscode/
├── language/
├── public/
├── src/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── uploads/
├── vendor/
├── .htaccess
├── composer.json
├── composer.lock
├── error_log
└── index.php
```

- **src/**: Contains application logic for the controllers, models, and views.
- **public/**: Public directory for entry files like `index.php`.
- **uploads/**: Directory for handling file uploads.
- **vendor/**: Directory for Composer dependencies.
- **composer.json**: Composer configuration file with autoload setup and package requirements.

## Usage

- **Home Page**: Visit `http://localhost:8000` to view the application.

## Contributing

- **Ayrz** - [GitHub Profile](https://github.com/ayrzDev)

## License

This project is licensed under the [MIT License](LICENSE).

---

To regenerate the autoload files after adding new classes, run:

```bash
composer dump-autoload
```

## Contact

For questions or suggestions, you can reach out via [email@example.com](mailto:email@example.com).
