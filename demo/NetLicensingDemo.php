<?php

/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

use cli\Table;
use NetLicensing\Context;
use NetLicensing\Country;
use NetLicensing\License;
use NetLicensing\Licensee;
use NetLicensing\LicenseeService;
use NetLicensing\LicenseService;
use NetLicensing\LicenseTemplate;
use NetLicensing\LicenseTemplateService;
use NetLicensing\PaymentMethod;
use NetLicensing\PaymentMethodService;
use NetLicensing\Product;
use NetLicensing\ProductDiscount;
use NetLicensing\ProductModule;
use NetLicensing\ProductModuleService;
use NetLicensing\ProductService;
use NetLicensing\Token;
use NetLicensing\TokenService;
use NetLicensing\Transaction;
use NetLicensing\TransactionService;
use NetLicensing\UtilityService;
use NetLicensing\ValidationParameters;
use function cli\err;
use function cli\line;

require_once '../vendor/autoload.php';


class NetLicensingDemo
{
    const BASE_URL = 'https://go.netlicensing.io/core/v2/rest';
    const SECURITY_MODE = \NetLicensing\Constants::BASIC_AUTHENTICATION;
    const USERNAME = 'demo';
    const PASSWORD = 'demo';

    private \Faker\Generator $faker;

    private ?Context $context;
    private ?Product $product;
    private ?ProductModule $productModule;
    private ?LicenseTemplate $licenseeTemplate;
    private ?Licensee $licensee;
    private ?License $license;
    private ?Transaction $transaction;
    private ?Token $token;

    protected array $statuses = [];

    public static function run(): NetLicensingDemo
    {
        return new NetLicensingDemo();
    }


    public function __construct()
    {
        $this->faker = Faker\Factory::create();

        $this->setUpContext();

        //UtilityService
        line('---------------------------- UtilityService ----------------------------');
        $this->listLicenseTypes();
        $this->listLicenseModels();
        $this->listCountries();

        //ProductService
        line('---------------------------- ProductService ----------------------------');
        $this->createProduct();
        $this->getProduct();
        $this->updateProduct();
        $this->listProduct();

        //ProductService
        line('---------------------------- ProductModuleService ----------------------------');
        $this->createProductModule();
        $this->getProductModule();
        $this->updateProductModule();
        $this->listProductModule();

        //LicenseTemplateService
        line('---------------------------- LicenseTemplateService ----------------------------');
        $this->createLicenseTemplate();
        $this->getLicenseTemplate();
        $this->updateLicenseTemplate();
        $this->listLicenseTemplate();

        //LicenseeService
        line('---------------------------- LicenseeService ----------------------------');
        $this->createLicensee();
        $this->getLicensee();
        $this->updateLicensee();
        $this->listLicensee();

        //LicenseService
        line('---------------------------- LicenseService ----------------------------');
        $this->createLicense();
        $this->getLicense();
        $this->updateLicense();
        $this->listLicense();

        //LicenseeService
        line('---------------------------- VALIDATE ----------------------------');
        $this->validate();

        //LicenseeService
        line('---------------------------- TRANSFER ----------------------------');
        $this->transfer();

        //TransactionService
        line('---------------------------- TransactionService ----------------------------');
        $this->createTransaction();
        $this->getTransaction();
        $this->updateTransaction();
        $this->listTransaction();

        //TokenService
        line('---------------------------- TokenService ----------------------------');
        $this->createToken();
        $this->getToken();
        $this->listToken();

        //PaymentMethodService
        line('---------------------------- PaymentMethodService ----------------------------');
        $this->getPaymentMethod();
        $this->listPaymentMethod();

        //Cleanup
        line('---------------------------- Cleanup ----------------------------');
        $this->deleteToken();
        $this->deleteLicense();
        $this->deleteLicensee();
        $this->deleteLicenseTemplate();
        $this->deleteProductModule();
        $this->deleteProduct();

        //Cleanup
        line('---------------------------- Statuses ----------------------------');
        $this->statuses();
    }

    /**
     * Determines the vendor on whose behalf the call is performed
     *
     * @return Context
     */
    public function setUpContext(): Context
    {
        return $this->context = (new Context())
            ->setBaseUrl(self::BASE_URL)
            ->setSecurityMode(self::SECURITY_MODE)
            ->setUsername(self::USERNAME)
            ->setPassword(self::PASSWORD);
    }

    public function listLicenseTypes()
    {
        try {
            $licenseTypes = UtilityService::listLicenseTypes($this->context);

            $headers = ['License Types'];

            $rows = [];

            foreach ($licenseTypes as $licenseType) {
                $rows[] = [$licenseType['name']];
            }

            $this->success('UtilityService::listLicenseTypes');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('UtilityService::listLicenseTypes', $exception);
        }
    }

    public function listLicenseModels()
    {
        try {

            $licensingModels = UtilityService::listLicensingModels($this->context);

            $headers = ['Licensing Models'];

            $rows = [];

            foreach ($licensingModels as $licensingModel) {
                $rows[] = [$licensingModel['name']];
            }

            $this->success('UtilityService::listLicensingModels');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('UtilityService::listLicensingModels', $exception);
        }
    }

    public function listCountries()
    {
        try {

            $countries = UtilityService::listCountries($this->context);

            $headers = ['Code', 'Name', 'Vat Percent', 'Is Eu'];

            $rows = [];
            /** @var  $country Country */
            foreach ($countries as $country) {
                $rows[] = [
                    $country->getCode(),
                    $country->getName(),
                    $country->getVatPercent(),
                    $country->getIsEu(),
                ];
            }

            $this->success('UtilityService::listCountries');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('UtilityService::listCountries', $exception);
        }
    }


    public function createProduct()
    {
        try {
            $product = new Product();

            $product->setNumber($this->faker->bothify('P-########'));
            $product->setName($this->faker->sentence(6, true));
            $product->setActive(true);
            $product->setVersion($this->faker->randomFloat(2));
            $product->setLicenseeAutoCreate($this->faker->boolean(70));

            $discount = new ProductDiscount();
            $discount->setTotalPrice($this->faker->randomFloat(2, 10, 50));
            $discount->setCurrency('EUR');
            $discount->setAmountPercent($this->faker->numberBetween(0, 90));

            $product->addDiscount($discount);

            $this->product = ProductService::create($this->context, $product);

            //output
            $headers = ['Number', 'Name', 'Active', 'Licensee Auto Create', 'Discounts'];
            $rows[] = [
                $this->product->getNumber(),
                $this->product->getName(),
                ($this->product->getActive()) ? 'true' : 'false',
                ($this->product->getLicenseeAutoCreate()) ? 'true' : 'false',
                $this->discountsOutput($this->product->getProductDiscounts())
            ];

            $this->success('ProductService::create');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductService::create', $exception);
        }
    }

    public function getProduct()
    {
        try {

            if (!$this->product) throw new Exception('Product not found');

            $this->product = ProductService::get($this->context, $this->product->getNumber());

            //output
            $headers = ['Number', 'Name', 'Active', 'Licensee Auto Create', 'Discounts'];

            $rows[] = [
                $this->product->getNumber(),
                $this->product->getName(),
                ($this->product->getActive()) ? 'true' : 'false',
                ($this->product->getLicenseeAutoCreate()) ? 'true' : 'false',
                $this->discountsOutput($this->product->getProductDiscounts())
            ];

            $this->success('ProductService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductService::get', $exception);
        }
    }

    public function listProduct()
    {
        try {

            $products = ProductService::getList($this->context);

            $headers = ['Number', 'Name', 'Active', 'Licensee Auto Create', 'Discounts'];

            $rows = [];

            /** @var  $product Product */
            foreach ($products as $product) {

                $rows[] = [
                    $product->getNumber(),
                    $product->getName(),
                    ($product->getActive()) ? 'true' : 'false',
                    ($product->getLicenseeAutoCreate()) ? 'true' : 'false',
                    $this->discountsOutput($product->getProductDiscounts())
                ];
            }

            $this->success('ProductService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductService::getList', $exception);
        }
    }

    public function updateProduct()
    {
        try {

            if (!$this->product) throw new Exception('Product not found');

            $number = $this->product->getNumber();

            $this->product->setNumber($this->faker->bothify('P-########'));
            $this->product->setName($this->faker->sentence(6, true));
            $this->product->setVersion($this->faker->randomFloat(2));
            $this->product->setLicenseeAutoCreate($this->faker->boolean(70));

            $this->product = ProductService::update($this->context, $number, $this->product);

            //output
            $headers = ['Number', 'Name', 'Active', 'Licensee Auto Create', 'Discounts'];
            $rows[] = [
                $this->product->getNumber(),
                $this->product->getName(),
                ($this->product->getActive()) ? 'true' : 'false',
                ($this->product->getLicenseeAutoCreate()) ? 'true' : 'false',
                $this->discountsOutput($this->product->getProductDiscounts())
            ];

            $this->success('ProductService::update');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductService::update', $exception);
        }
    }

    public function deleteProduct()
    {
        try {

            if (!$this->product) throw new Exception('Product not found');

            ProductService::delete($this->context, $this->product->getNumber());

            //output
            $this->success('ProductService::delete');

        } catch (Exception $exception) {
            //output
            $this->error('ProductService::delete', $exception);
        }
    }

    public function createProductModule()
    {
        try {

            if (!$this->product) throw new Exception('Product not found');

            $productModule = new ProductModule();
            $productModule->setNumber($this->faker->bothify('PM-########'));
            $productModule->setName($this->faker->sentence(6, true));
            $productModule->setActive(true);
            $productModule->setLicensingModel('Subscription');

            $this->productModule = ProductModuleService::create($this->context, $this->product->getNumber(), $productModule);

            //output
            $headers = ['Number', 'Name', 'Active', 'LicensingModel'];
            $rows[] = [
                $this->productModule->getNumber(),
                $this->productModule->getName(),
                ($this->productModule->getActive()) ? 'true' : 'false',
                $this->productModule->getLicensingModel()
            ];

            $this->success('ProductModuleService::create');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductModuleService::create', $exception);
        }
    }

    public function getProductModule()
    {
        try {

            if (!$this->productModule) throw new Exception('ProductModule not found');

            $this->productModule = ProductModuleService::get($this->context, $this->productModule->getNumber());

            //output
            $headers = ['Number', 'Name', 'Active', 'LicensingModel'];

            $rows[] = [
                $this->productModule->getNumber(),
                $this->productModule->getName(),
                ($this->productModule->getActive()) ? 'true' : 'false',
                $this->productModule->getLicensingModel()
            ];

            $this->success('ProductModuleService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductModuleService::get', $exception);
        }
    }

    public function listProductModule()
    {
        try {

            $productModules = ProductModuleService::getList($this->context);

            $headers = ['Number', 'Name', 'Active', 'LicensingModel'];

            $rows = [];

            /** @var  $productModule ProductModule */
            foreach ($productModules as $productModule) {

                $rows[] = [
                    $productModule->getNumber(),
                    $productModule->getName(),
                    ($productModule->getActive()) ? 'true' : 'false',
                    $productModule->getLicensingModel()
                ];
            }

            $this->success('ProductModuleService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductModuleService::getList', $exception);
        }
    }

    public function updateProductModule()
    {
        try {
            if (!$this->productModule) throw new Exception('ProductModule not found');

            $number = $this->productModule->getNumber();

            $this->productModule->setName($this->faker->sentence(6, true));

            $this->productModule = ProductModuleService::update($this->context, $number, $this->productModule);

            //output
            $headers = ['Number', 'Name', 'Active', 'LicensingModel'];
            $rows[] = [
                $this->productModule->getNumber(),
                $this->productModule->getName(),
                ($this->productModule->getActive()) ? 'true' : 'false',
                $this->productModule->getLicensingModel()
            ];

            $this->success('ProductModuleService::update');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('ProductModuleService::update', $exception);
        }
    }

    public function deleteProductModule()
    {
        try {

            if (!$this->productModule) throw new Exception('ProductModule not found');

            ProductModuleService::delete($this->context, $this->productModule->getNumber());

            //output
            $this->success('ProductModuleService::delete');

        } catch (Exception $exception) {
            //output
            $this->error('ProductModuleService::delete', $exception);
        }
    }

    public function createLicenseTemplate()
    {
        try {

            $licenseTemplate = new LicenseTemplate();
            $licenseTemplate->setNumber($this->faker->bothify('LT-########'));
            $licenseTemplate->setName($this->faker->sentence(6, true));
            $licenseTemplate->setActive(true);
            $licenseTemplate->setLicenseType('TIMEVOLUME');
            $licenseTemplate->setTimeVolume(30);
            $licenseTemplate->setPrice($this->faker->randomFloat(2, 0, 100));
            $licenseTemplate->setCurrency('EUR');

            $this->licenseeTemplate = LicenseTemplateService::create($this->context, $this->productModule->getNumber(), $licenseTemplate);

            //output
            $headers = ['Number', 'Name', 'Active', 'LicenseType', 'Price', 'Currency'];
            $rows[] = [
                $this->licenseeTemplate->getNumber(),
                $this->licenseeTemplate->getName(),
                ($this->licenseeTemplate->getActive()) ? 'true' : 'false',
                $this->licenseeTemplate->getLicenseType(),
                $this->licenseeTemplate->getPrice(),
                $this->licenseeTemplate->getCurrency(),
            ];

            $this->success('LicenseTemplateService::create');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseTemplateService::create', $exception);
        }
    }

    public function getLicenseTemplate()
    {
        try {

            if (!$this->licenseeTemplate) throw new Exception('LicenseeTemplate not found');

            $this->licenseeTemplate = LicenseTemplateService::get($this->context, $this->licenseeTemplate->getNumber());

            //output
            $headers = ['Number', 'Name', 'Active', 'LicenseType', 'Price', 'Currency'];
            $rows[] = [
                $this->licenseeTemplate->getNumber(),
                $this->licenseeTemplate->getName(),
                ($this->licenseeTemplate->getActive()) ? 'true' : 'false',
                $this->licenseeTemplate->getLicenseType(),
                $this->licenseeTemplate->getPrice(),
                $this->licenseeTemplate->getCurrency(),
            ];

            $this->success('LicenseTemplateService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseTemplateService::get', $exception);
        }
    }

    public function listLicenseTemplate()
    {
        try {

            $licenseTemplates = LicenseTemplateService::getList($this->context);

            $headers = ['Number', 'Name', 'Active', 'LicenseType', 'Price', 'Currency'];

            $rows = [];

            /** @var  $licenseTemplate LicenseTemplate */
            foreach ($licenseTemplates as $licenseTemplate) {

                $rows[] = [
                    $licenseTemplate->getNumber(),
                    $licenseTemplate->getName(),
                    ($licenseTemplate->getActive()) ? 'true' : 'false',
                    $licenseTemplate->getLicenseType(),
                    $licenseTemplate->getPrice(),
                    $licenseTemplate->getCurrency(),
                ];
            }

            $this->success('LicenseTemplateService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseTemplateService::getList', $exception);
        }
    }

    public function updateLicenseTemplate()
    {
        try {

            if (!$this->licenseeTemplate) throw new Exception('LicenseeTemplate not found');

            $this->licenseeTemplate->setName($this->faker->sentence(6, true));
            $this->licenseeTemplate->setPrice($this->faker->randomFloat(2, 0, 100));

            $this->licenseeTemplate = LicenseTemplateService::update($this->context, $this->licenseeTemplate->getNumber(), $this->licenseeTemplate);

            //output
            $headers = ['Number', 'Name', 'Active', 'LicenseType', 'Price', 'Currency'];
            $rows[] = [
                $this->licenseeTemplate->getNumber(),
                $this->licenseeTemplate->getName(),
                ($this->licenseeTemplate->getActive()) ? 'true' : 'false',
                $this->licenseeTemplate->getLicenseType(),
                $this->licenseeTemplate->getPrice(),
                $this->licenseeTemplate->getCurrency(),
            ];

            $this->success('LicenseTemplateService::update');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseTemplateService::update', $exception);
        }
    }

    public function deleteLicenseTemplate()
    {
        try {

            if (!$this->licenseeTemplate) throw new Exception('LicenseeTemplate not found');

            LicenseTemplateService::delete($this->context, $this->licenseeTemplate->getNumber());

            //output
            $this->success('LicenseTemplateService::delete');

        } catch (Exception $exception) {
            //output
            $this->error('LicenseTemplateService::delete', $exception);
        }
    }

    public function createLicensee()
    {
        try {
            $licensee = new Licensee();
            $licensee->setNumber($this->faker->bothify('L-########'));
            $licensee->setName($this->faker->sentence(6, true));
            $licensee->setActive(true);

            $this->licensee = LicenseeService::create($this->context, $this->product->getNumber(), $licensee);

            //output
            $headers = ['Number', 'Name', 'Active'];
            $rows[] = [
                $this->licensee->getNumber(),
                $this->licensee->getName(),
                ($this->licensee->getActive()) ? 'true' : 'false',
            ];

            $this->success('LicenseeService::create');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseeService::create', $exception);
        }
    }

    public function getLicensee()
    {
        try {

            if (!$this->licensee) throw new Exception('Licensee not found');

            $this->licensee = LicenseeService::get($this->context, $this->licensee->getNumber());

            //output
            $headers = ['Number', 'Name', 'Active'];
            $rows[] = [
                $this->licensee->getNumber(),
                $this->licensee->getName(),
                ($this->licensee->getActive()) ? 'true' : 'false',
            ];

            $this->success('LicenseeService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseeService::get', $exception);
        }
    }

    public function listLicensee()
    {
        try {

            $licensees = LicenseeService::getList($this->context);

            $headers = ['Number', 'Name', 'Active'];

            $rows = [];

            /** @var  $licensee Licensee */
            foreach ($licensees as $licensee) {

                $rows[] = [
                    $licensee->getNumber(),
                    $licensee->getName(),
                    ($licensee->getActive()) ? 'true' : 'false',
                ];
            }

            $this->success('LicenseeService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseeService::getList', $exception);
        }
    }

    public function updateLicensee()
    {
        try {

            if (!$this->licensee) throw new Exception('Licensee not found');

            $this->licensee->setName($this->faker->sentence(6, true));

            $this->licensee = LicenseeService::update($this->context, $this->licensee->getNumber(), $this->licensee);

            //output
            $headers = ['Number', 'Name', 'Active'];
            $rows[] = [
                $this->licensee->getNumber(),
                $this->licensee->getName(),
                ($this->licensee->getActive()) ? 'true' : 'false',
            ];

            $this->success('LicenseeService::update');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseeService::update', $exception);
        }
    }

    public function deleteLicensee()
    {
        try {

            if (!$this->licensee) throw new Exception('Licensee not found');

            LicenseeService::delete($this->context, $this->licensee->getNumber());

            //output
            $this->success('LicenseeService::delete');

        } catch (Exception $exception) {
            //output
            $this->error('LicenseeService::delete', $exception);
        }
    }

    public function validate()
    {
        try {
            $validationParameters = new ValidationParameters();
            $validationParameters->setLicenseeName($this->faker->uuid);
            $validationParameters->setProductNumber($this->product->getNumber());
            $validationParameters->setLicenseeName($this->licensee->getName());

            $validationResult = LicenseeService::validate($this->context, $this->licensee->getNumber(), $validationParameters);

            $validation = $validationResult->getProductModuleValidation($this->productModule->getNumber());

            //output
            $headers = ['Product Module Number', 'Product Module Name', 'Licensing Model', 'Valid'];
            $rows[] = [
                $validation['productModuleNumber'],
                $validation['productModuleName'],
                $validation['licensingModel'],
                ($validation['valid']) ? 'true' : 'false',
            ];

            $this->success('LicenseeService::validate');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseeService::validate', $exception);
        }
    }

    public function transfer()
    {
        try {

            $licensee = new Licensee();
            $licensee->setNumber($this->faker->bothify('L-########'));
            $licensee->setName($this->faker->sentence(6, true));
            $licensee->setActive(true);

            $licensee = LicenseeService::create($this->context, $this->product->getNumber(), $licensee);

            $this->licensee->setMarkedForTransfer(true);

            $this->licensee = LicenseeService::update($this->context, $this->licensee->getNumber(), $this->licensee);

            LicenseeService::transfer($this->context, $licensee->getNumber(), $this->licensee->getNumber());

            $this->licensee = $licensee;

            //output
            $headers = ['Number', 'Name', 'Active'];
            $rows[] = [
                $this->licensee->getNumber(),
                $this->licensee->getName(),
                ($this->licensee->getActive()) ? 'true' : 'false',
            ];

            $this->success('LicenseeService::transfer');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseeService::transfer', $exception);
        }
    }

    public function createLicense()
    {
        try {
            $license = new License();
            $license->setNumber($this->faker->bothify('LC-########'));
            $license->setName($this->faker->sentence(6, true));
            $license->setStartDate('now');
            $license->setActive(true);

            $this->license = LicenseService::create($this->context, $this->licensee->getNumber(), $this->licenseeTemplate->getNumber(), null, $license);

            //output
            $headers = ['Number', 'Name'];
            $rows[] = [
                $this->license->getNumber(),
                $this->license->getName(),
            ];

            $this->success('LicenseService::create');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseService::create', $exception);
        }
    }

    public function getLicense()
    {
        try {

            if (!$this->license) throw new Exception('License not found');

            $this->license = LicenseService::get($this->context, $this->license->getNumber());

            //output
            $headers = ['Number', 'Name'];
            $rows[] = [
                $this->license->getNumber(),
                $this->license->getName(),
            ];

            $this->success('LicenseService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseService::get', $exception);
        }
    }

    public function listLicense()
    {
        try {

            $licenses = LicenseService::getList($this->context);

            $headers = ['Number', 'Name'];

            $rows = [];

            /** @var  $license License */
            foreach ($licenses as $license) {

                $rows[] = [
                    $license->getNumber(),
                    $license->getName(),
                ];
            }

            $this->success('LicenseService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseService::getList', $exception);
        }
    }

    public function updateLicense()
    {
        try {

            if (!$this->license) throw new Exception('License not found');

            $this->license->setName($this->faker->sentence(6, true));

            $this->license = LicenseService::update($this->context, $this->license->getNumber(), null, $this->license);

            //output
            $headers = ['Number', 'Name'];
            $rows[] = [
                $this->license->getNumber(),
                $this->license->getName(),
            ];

            $this->success('LicenseService::update');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('LicenseService::update', $exception);
        }
    }

    public function deleteLicense()
    {
        try {

            if (!$this->license) throw new Exception('License not found');

            LicenseService::delete($this->context, $this->license->getNumber());

            //output
            $this->success('LicenseService::delete');

        } catch (Exception $exception) {
            //output
            $this->error('LicenseService::delete', $exception);
        }
    }

    public function createTransaction()
    {
        try {

            $transaction = new Transaction();
            $transaction->setNumber($this->faker->bothify('TR-########'));
            $transaction->setStatus('PENDING');
            $transaction->setSource('SHOP');

            $this->transaction = TransactionService::create($this->context, $transaction);

            //output
            $headers = ['Number', 'Status'];
            $rows[] = [
                $this->transaction->getNumber(),
                $this->transaction->getStatus(),
            ];

            $this->success('TransactionService::create');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('TransactionService::create', $exception);
        }
    }

    public function getTransaction()
    {
        try {

            if (!$this->transaction) throw new Exception('Transaction not found');

            $this->transaction = NetLicensing\TransactionService::get($this->context, $this->transaction->getNumber());

            //output
            $headers = ['Number', 'Status'];
            $rows[] = [
                $this->transaction->getNumber(),
                $this->transaction->getStatus(),
            ];

            $this->success('TransactionService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('TransactionService::get', $exception);
        }
    }

    public function listTransaction()
    {
        try {

            $transactions = TransactionService::getList($this->context);

            $headers = ['Number', 'Status'];

            $rows = [];

            /** @var  $transaction Transaction */
            foreach ($transactions as $transaction) {

                $rows[] = [
                    $transaction->getNumber(),
                    $transaction->getStatus(),
                ];
            }

            $this->success('TransactionService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('TransactionService::getList', $exception);
        }
    }

    public function updateTransaction()
    {
        try {

            if (!$this->transaction) throw new Exception('Transaction not found');

            $this->transaction->setStatus('CLOSED');

            $this->transaction = TransactionService::update($this->context, $this->transaction->getNumber(), $this->transaction);

            //output
            $headers = ['Number', 'Status'];
            $rows[] = [
                $this->transaction->getNumber(),
                $this->transaction->getStatus(),
            ];

            $this->success('TransactionService::update');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('TransactionService::update', $exception);
        }
    }

    public function createToken()
    {
        try {

            $token = new Token();
            $token->setNumber($this->faker->bothify('T-########'));
            $token->setTokenType('SHOP');
            $token->setLicenseeNumber($this->licensee->getNumber());

            $this->token = TokenService::create($this->context, $token);

            //output
            $headers = ['Number', 'Type', 'Expiration Time'];
            $rows[] = [
                $this->token->getNumber(),
                $this->token->getTokenType(),
                $this->token->getExpirationTime(),
            ];

            $this->success('TokenService::create');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('TokenService::create', $exception);
        }
    }

    public function getToken()
    {
        try {

            if (!$this->token) throw new Exception('Token not found');

            $this->token = TokenService::get($this->context, $this->token->getNumber());

            //output
            $headers = ['Number', 'Type', 'Expiration Time'];
            $rows[] = [
                $this->token->getNumber(),
                $this->token->getTokenType(),
                $this->token->getExpirationTime(),
            ];

            $this->success('TokenService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('TokenService::get', $exception);
        }
    }

    public function listToken()
    {
        try {

            $tokens = TokenService::getList($this->context);

            $headers = ['Number', 'Type', 'Expiration Time'];

            $rows = [];

            /** @var  $token Token */
            foreach ($tokens as $token) {

                $rows[] = [
                    $token->getNumber(),
                    $token->getTokenType(),
                    $token->getExpirationTime(),
                ];
            }

            $this->success('TokenService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('TokenService::getList', $exception);
        }
    }

    public function deleteToken()
    {
        try {

            if (!$this->token) throw new Exception('Token not found');

            TokenService::delete($this->context, $this->token->getNumber());

            //output
            $this->success('TokenService::delete');

        } catch (Exception $exception) {
            //output
            $this->error('TokenService::delete', $exception);
        }
    }

    public function getPaymentMethod()
    {
        try {
            $paymentMethod = PaymentMethodService::get($this->context, 'PAYPAL');

            //output
            $headers = ['Number'];
            $rows[] = [
                $paymentMethod->getNumber(),
            ];

            $this->success('PaymentMethodService::get');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('PaymentMethodService::get', $exception);
        }
    }

    public function listPaymentMethod()
    {
        try {
            $paymentMethods = PaymentMethodService::getList($this->context);

            //output
            $headers = ['Number'];

            $rows = [];

            /** @var  $paymentMethod PaymentMethod */
            foreach ($paymentMethods as $paymentMethod) {

                $rows[] = [
                    $paymentMethod->getNumber(),
                ];
            }

            $this->success('PaymentMethodService::getList');
            $this->table($headers, $rows);

        } catch (Exception $exception) {
            //output
            $this->error('PaymentMethodService::getList', $exception);
        }
    }

    private function statuses()
    {

        $headers = ['Operation', 'Status', 'Message'];
        asort($this->statuses);
        $this->table($headers, $this->statuses);
    }

    private function discountsOutput($discounts): string
    {
        $output = '';

        foreach ($discounts as $discount) {
            $output .= $discount . " ";
        }

        return $output;
    }

    private function success($apiMethod)
    {
        line($apiMethod . ' - OK');

        $this->statuses[$apiMethod] = [$apiMethod, 'OK'];
    }

    private function error($apiMethod, Exception $exception)
    {
        err($apiMethod . ' - ERROR');
        line((string)$exception);

        $this->statuses[$apiMethod] = [$apiMethod, 'ERROR', $exception->getMessage()];

        exit();
    }

    private function table(array $headers, array $rows = null)
    {
        $table = new Table($headers, $rows, null);

        $table->display();
    }
}


NetLicensingDemo::run();
