<?php

namespace App\Providers;

use App\Interfaces\AdminAuthServiceInterface;
use App\Interfaces\AiDataServiceInterface;
use App\Interfaces\AiServiceInterface;
use App\Interfaces\BrandServiceInterface;
use App\Interfaces\CartServiceInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Interfaces\MemberAuthServiceInterface;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\PaymentServiceInterface;
use App\Interfaces\ProductServiceInterface;
use App\Services\AdminAuthService;
use App\Services\AiDataService;
use App\Services\AiService;
use App\Services\BrandService;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\MemberAuthService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ProductService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MemberAuthServiceInterface::class, MemberAuthService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(BrandServiceInterface::class, BrandService::class);
        $this->app->bind(AdminAuthServiceInterface::class, AdminAuthService::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(AiServiceInterface::class, AiService::class);
        $this->app->bind(AiDataServiceInterface::class, AiDataService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
