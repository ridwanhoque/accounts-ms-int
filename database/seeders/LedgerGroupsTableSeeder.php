<?php
namespace Database\Seeders;

use App\Accounts\LedgerGroup;
use Illuminate\Database\Seeder;

class LedgerGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group_names_tally = [
        'BanK Accounts',
        'Bank OCC Atc',
        'Bank 0D At',
        'Branch I Divisions',
        'Capital Account',
        'Cash-In-Hand',
        'Current Assets',
        'Current Liabilities',
        'Deposits (Asset)',
        'Direct Expenses',
        'Direct Incomes',
        'Duties & Taxes',
        'Expenses (Direct)',
        'Expenses (Indirect)',
        'Fixed Assets',
        'Income (Direct)',
        'Income (Indirect)',
        'Indirect Expenses',
        'Indirect Incomes',
        'Investments',
        'Loans & Advances (Asset)',
        'Loans (Liability)',
        'Misc. Expenses (ASSET)',
        'Provisions',
        'Purchase Accounts',
        'Reserves & Surplus',
        'Retained Earnings',
        'Sales Accounts',
        'Secured Loans',
        'Stock-in-Hand',
        'Sundry', 
        'Creditors',
        'Sundry', 
        'Debtors',
        'Suspense At',
        'Unsecured Loans'
        ];
        foreach ($group_names_tally as $group_name) {
            LedgerGroup::create([
                'name' => $group_name
            ]);
        }
    }
}
