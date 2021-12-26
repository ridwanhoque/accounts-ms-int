<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.payment_methods
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatusesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ColorsTableSeeder::class);
        $this->call(RawMaterialsTableSeeder::class);
        $this->call(SubRawMaterialsTableSeeder::class);
        $this->call(FmKutchasTableSeeder::class);
        $this->call(MachinesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(BatchesTableSeeder::class);
        $this->call(SheetSizesTableSeeder::class);
        $this->call(AccountInformationSeeder::class);
        $this->call(PaymentMethodTableSeeder::class);
        $this->call(PartyTableSeeder::class);
        $this->call(WastageStocksTableSeeder::class);
        $this->call(ConfigMaterialsTableSeeder::class);

        //accounts seeder
        $this->call(OwnerTypesTableSeeder::class);
        $this->call(ChartTypesTableSeeder::class);
        $this->call(ChartOfAccountTableSeeder::class);
        //uncomment DummyChartOfAccountsTableSeeder for more chart of accounts
        $this->call(DummyChartOfAccountsTableSeeder::class);
        $this->call(DummyPartiesTableSeeder::class);
    }
}
