# Bootstrap Fix Report: Courier Management System

The bootstrap process has been completely overhauled to ensure the application is robust and can function independently of Composer while still supporting it if present.

## Root Cause Analysis
The application was crashing because it had a hard dependency on `vendor/autoload.php`, which was missing from the environment. Additionally, the `.env` loading relied on an external library (`vlucas/phpdotenv`) that was not yet installed or available.

## Modifications and Fixes

### 1. Robust Autoloading
I implemented a custom **PSR-4 compatible autoloader** in a new `Core\Bootstrap` class. This autoloader handles the `App\`, `Core\`, and `Config\` namespaces automatically by mapping them to their respective directories. It also includes a "soft check" for Composer's autoloader, allowing the system to use Composer packages if they are available without crashing if they are not.

### 2. Independent .env Loading
A native `.env` file parser was added to the `Bootstrap` class. This removes the dependency on `phpdotenv` for basic environment configuration, allowing the application to load database credentials and application settings directly from the `.env` file using standard PHP functions.

### 3. Centralized Bootstrap Flow
The `public/index.php` file was refactored to use the new `Core\Bootstrap::init()` method. This centralizes the following operations:
- Registering the PSR-4 autoloader.
- Loading environment variables.
- Starting and managing secure sessions (including periodic regeneration).

## Final Bootstrap Flow
1. **Entry Point**: `public/index.php` defines the `ROOT_PATH`.
2. **Bootstrap Loading**: `core/Bootstrap.php` is required manually.
3. **Initialization**: `Core\Bootstrap::init()` is called, which:
   - Registers `spl_autoload_register` for namespace resolution.
   - Parses `.env` and populates `$_ENV`, `$_SERVER`, and `putenv()`.
   - Starts the session and handles security checks.
4. **Routing**: The router is loaded from `routes/web.php` and dispatches the request based on the URL and HTTP method.

## Verification Results
| Check | Status | Result |
| :--- | :--- | :--- |
| **Autoloader** | PASS | Classes in `App\` and `Core\` namespaces resolve correctly. |
| **.env Loading** | PASS | Database credentials successfully loaded into `$_ENV`. |
| **DB Connection** | PASS | `Core\Database` successfully connects using loaded credentials. |
| **Session** | PASS | Sessions are initiated and managed securely. |
| **Routing** | PASS | Router successfully loads and maps controllers. |

---
*The foundation is now stable and ready for further development.*
