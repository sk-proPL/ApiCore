# Local Component Development Guide

This document describes how to locally develop and test custom Laravel components within a Docker-based environment.

---

## 🐳 Local Testing in Docker

To test components locally using `docker-compose`, add the following line under the `volumes` section inside the **build** part of your service definition:

```yaml
./Component:/var/www/Component
```

**Example:**

```yaml
working_dir: /var/www/html
user: backend
restart: unless-stopped
volumes:
  - ./backend:/var/www/html
  - ./Component:/var/www/Component
```

---

## ⚙️ Add Component as a Dependency

Navigate to your `backend` directory and open the `composer.json` file.  
Add your new component as a dependency in the `require` section:

```json
"require": {
  "YourComponent/example": "dev-main"
}
```

Then, define the repository source just below it:

```json
"repositories": [
  {
    "type": "path",
    "url": "../Component/Example"
  }
]
```

---

## 📁 Component Structure

Create your component inside the `Components/` directory.  
For example:

```
Components/
└── Example/
    ├── app/
        ├── Http/
            ├── Controllers/
            ├── Requests/
        ├── Dtos
        ├── Exceptions
        ├── Mappers
        ├── AppServiceProvider.php
    ├── database/
        ├── migrations/
        ├── seeders/
        ├── factories/
    ├── config/
    ├── routes/
    └── composer.json
```

> The `config/` directory is optional — create it only if your component defines new configuration files.

---

## 🧩 Component `composer.json` Configuration

Each component must include its own `composer.json` file.  
Example:

```json
{
  "name": "YourComponent/example",
  "description": "Example component",
  "type": "library",
  "require": {
    "php": ">=8.0",
    "laravel/framework": "^12.0"
  },
  "autoload": {
    "psr-4": {
      "YourComponent\\Component\\Example\\": "app/",
      "YourComponent\\Component\\Example\\Migrations\\": "database/migrations/"
    }
  },
  "extra": {
    "laravel": {
      "providers": [
        "YourComponent\\Component\\Example\\AppServiceProvider"
      ]
    }
  }
}
```

---

## 🧱 AppServiceProvider Setup

Inside the `app/` directory, you must include an `AppServiceProvider.php` file.  
It should define at least two methods:

```php
public function register(): void
public function boot(): void
```

Use these methods to register your services, migrations, and routes.

---

## 🪄 Example AppServiceProvider Configuration

```php
public function register(): void
{
    //register all configs
    foreach (glob(__DIR__.'/../config/*.php') as $configFile) {
        $name = basename($configFile, '.php');
        $this->mergeConfigFrom($configFile, $name);
    }

    //register CommandHandler
    $this->app->singleton(ExampleCommandHandler::class, function ($app) {
        return new ExampleCommandHandler();
    });

}

public function boot(): void
{
    //register example command and handler
    Bus::map([
        ExampleCommand::class => ExampleCommandHandler::class,
    ]);

    //register example event and listener
    Event::listen(ExampleEvent::class, ExampleEventListener::class);

    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    $this->loadModuleRoutes();
    $this->publishesConfig();
}

protected function loadModuleRoutes(): void
{
    $routesPath = __DIR__.'/../routes/api.php';
    if (file_exists($routesPath)) {
        Route::prefix('api')
            ->middleware('api')
            ->group($routesPath);
    }
}

protected function publishesConfig(): void
{
    if ($this->app->runningInConsole()) {
        $configs = [];
        foreach (glob(__DIR__.'/../config/*.php') as $configFile) {
            $configs[$configFile] = config_path(basename($configFile));
        }
        $this->publishes($configs, 'config');
    }
}
```

---

## 🧰 Customize for Your Project

Adjust the configuration and file structure to match your project’s requirements.  
Ensure your namespaces, dependencies, and service provider registration align with your component’s `composer.json`.
