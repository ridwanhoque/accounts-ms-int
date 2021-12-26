<?php

namespace App\Repositories\Accounts;

use App\ChartOfAccount;
use Illuminate\Support\Facades\Request;

class ChartOfAccountRepository
{

    public function adjust_double_entry($chart_of_account)
    {
        request()->chart_of_account_id;

        $parent_chart_id = request()->parent_id;
        $amount = request()->opening_balance;

        switch ($chart_of_account->owner_type_id) {
            case 2:
                $chart_to_be_updated = 45;
                break;
            case 3:
                $chart_to_be_updated = 45;
                break;
        };
        
        $reverse_chart = ChartOfAccount::find($chart_to_be_updated);
        //update reverse chart balance and opening balance
        $reverse_chart->increment('opening_balance', $amount);
        $reverse_chart->increment('balance', $amount);

        
        if ($reverse_chart->parent_id > 0) {
            $this->update_parent_chart($reverse_chart->parent_id, $amount);
        }
    }

    public function reverse_charts()
    {

        //if bank or cash opening added it will be added with capital
        return [
            '2' => '45', //bank
            '3' => '45', //cash
        ];
    }

    public function update_parent_chart($parent_chart_id, $amount)
    {

        $parent_chart = ChartOfAccount::find($parent_chart_id);
        $parent_chart->increment('balance', $amount);
        $parent_chart->save();

        if ($parent_chart->parent_id > 0) {
            $this->update_parent_chart($parent_chart->parent_id, $amount);
        }

        return true;
    }

}
