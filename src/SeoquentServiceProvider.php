<?php

namespace IbrahimEng12\Seoquent;

use IbrahimEng12\Seoquent\Services\SeoManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SeoquentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/seoquent.php', 'seoquent');

        $this->app->singleton('seoquent', function () {
            return new SeoManager();
        });
    }

    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerBladeDirectives();
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__ . '/../config/seoquent.php' => config_path('seoquent.php'),
            ], 'seoquent-config');

            // Migration
            if (config('seoquent.database.enabled', true)) {
                $migrationFileName = 'create_seo_meta_table.php';
                $migrationStub = __DIR__ . '/../database/migrations/' . $migrationFileName . '.stub';

                if (! $this->migrationExists($migrationFileName)) {
                    $this->publishes([
                        $migrationStub => database_path('migrations/' . date('Y_m_d_His') . '_' . $migrationFileName),
                    ], 'seoquent-migrations');
                }
            }

            // Views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/seoquent'),
            ], 'seoquent-views');
        }
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'seoquent');
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('seoHead', function () {
            return "<?php echo app('seoquent')->renderHead(); ?>";
        });

        Blade::directive('seoJsonLd', function () {
            return "<?php echo app('seoquent')->renderJsonLd(); ?>";
        });
    }

    protected function migrationExists(string $migrationFileName): bool
    {
        $migrationsPath = database_path('migrations');

        if (! is_dir($migrationsPath)) {
            return false;
        }

        $files = scandir($migrationsPath);

        foreach ($files as $file) {
            if (str_contains($file, $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
