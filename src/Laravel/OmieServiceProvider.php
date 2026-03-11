<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Rafapaulino\Omiephpsdk\BillingSteps\Contracts\BillingStepServiceInterface;
use Rafapaulino\Omiephpsdk\BillingSteps\OmieBillingStepService;
use Rafapaulino\Omiephpsdk\CFOP\Contracts\CfopServiceInterface;
use Rafapaulino\Omiephpsdk\CFOP\OmieCfopService;
use Rafapaulino\Omiephpsdk\Categories\Contracts\CategoryServiceInterface;
use Rafapaulino\Omiephpsdk\Categories\OmieCategoryService;
use Rafapaulino\Omiephpsdk\Clients\Contracts\ClientServiceInterface;
use Rafapaulino\Omiephpsdk\Clients\OmieClientService;
use Rafapaulino\Omiephpsdk\CurrentAccount\Contracts\CurrentAccountServiceInterface;
use Rafapaulino\Omiephpsdk\CurrentAccount\OmieCurrentAccountService;
use Rafapaulino\Omiephpsdk\PaymentMethods\Contracts\PaymentMethodServiceInterface;
use Rafapaulino\Omiephpsdk\PaymentMethods\OmiePaymentMethodService;
use Rafapaulino\Omiephpsdk\Products\Contracts\ProductServiceInterface;
use Rafapaulino\Omiephpsdk\Products\OmieProductService;
use Rafapaulino\Omiephpsdk\Sellers\Contracts\SellerServiceInterface;
use Rafapaulino\Omiephpsdk\Sellers\OmieSellerService;
use Rafapaulino\Omiephpsdk\SimpleSale\Contracts\SimpleSaleServiceInterface;
use Rafapaulino\Omiephpsdk\SimpleSale\OmieSimpleSaleService;
use Rafapaulino\Omiephpsdk\Services\Contracts\ServiceServiceInterface;
use Rafapaulino\Omiephpsdk\Services\OmieServiceService;
use Rafapaulino\Omiephpsdk\Users\Contracts\UserServiceInterface;
use Rafapaulino\Omiephpsdk\Users\OmieUserService;

final class OmieServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/omie.php', 'omie');

        $this->registerService(BillingStepServiceInterface::class, OmieBillingStepService::class);
        $this->registerService(CfopServiceInterface::class, OmieCfopService::class);
        $this->registerService(CategoryServiceInterface::class, OmieCategoryService::class);
        $this->registerService(ClientServiceInterface::class, OmieClientService::class);
        $this->registerService(CurrentAccountServiceInterface::class, OmieCurrentAccountService::class);
        $this->registerService(PaymentMethodServiceInterface::class, OmiePaymentMethodService::class);
        $this->registerService(ProductServiceInterface::class, OmieProductService::class);
        $this->registerService(SellerServiceInterface::class, OmieSellerService::class);
        $this->registerService(SimpleSaleServiceInterface::class, OmieSimpleSaleService::class);
        $this->registerService(ServiceServiceInterface::class, OmieServiceService::class);
        $this->registerService(UserServiceInterface::class, OmieUserService::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/omie.php' => config_path('omie.php'),
        ], 'omie-config');
    }

    private function registerService(string $interface, string $concrete): void
    {
        $this->app->singleton($interface, function () use ($concrete) {
            return new $concrete($this->makeHttpClient(), $this->omieConfig());
        });

        $this->app->singleton($concrete, function () use ($interface) {
            return $this->app->make($interface);
        });
    }

    private function makeHttpClient(): ClientInterface
    {
        $config = $this->omieConfig();

        return new Client([
            'base_uri' => rtrim((string) ($config['omie_api'] ?? ''), '/') . '/',
            'timeout' => (int) ($config['timeout'] ?? 30),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function omieConfig(): array
    {
        /** @var mixed $config */
        $config = $this->app['config']->get('omie', []);

        return is_array($config) ? $config : [];
    }
}
