<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ResultSoccer;
use App\Models\Bet;
use App\Models\BetPurchase;
use App\Services\CheckResults\AbstractCheckResultsService;

class CheckResults extends Command
{
    protected $signature = 'check-results {sport_id}';

    protected $description = 'Check betting results';

    public function handle()
    {
        $betPurchases = BetPurchase::with('bets')->where('is_active', '=', 1)->get();
        foreach ($betPurchases as $key => $betPurchase) {
            foreach ($betPurchase['bets'] as $key => $bet) {
                if($bet->is_active && $bet->apievents_sport_id == $this->argument('sport_id')){
                    $abstractCheckResultsService = new AbstractCheckResultsService($bet);
                    $bet->win = $abstractCheckResultsService->checkResult();
                    $bet->is_active = 0;
                    $bet->save();
                }
            }
            //verificando se todas as apostas foram win
            echo $this->checkWinBetPurchase($betPurchase);
        }
    }

    public function checkWinBetPurchase($betPurchase){
        $countBets = $betPurchase['bets']->count();
        $countBetNotActive = 0;
        $countWins = 0;
        $countLoss = 0;
        foreach ($betPurchase['bets'] as $key => $bet) {
            if($bet->is_active == 0)
                $countBetNotActive += 1;
            if($bet->win)
                $countWins += 1;
            else
                $countLoss += 1;
        }

        //verificando se tem apostas ativas
        if($countBets != $countBetNotActive){
            return false;
        }

        if($countBets == $countBetNotActive && $countLoss > 0){
            $betPurchase->is_active = 0;
            $betPurchase->win = 0;
            $betPurchase->save();
        }


        if($countBets == $countBetNotActive && $countBets == $countWins){
            $betPurchase->is_active = 0;
            $betPurchase->win = 1;
            $betPurchase->save();
        }
        return true;
    }
}
