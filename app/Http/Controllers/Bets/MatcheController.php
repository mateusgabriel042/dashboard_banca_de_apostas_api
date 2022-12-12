<?php

namespace App\Http\Controllers\Bets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Services\MatcheService;
use App\Models\Matche;

class MatcheController extends Controller
{
    use ApiResponser;

    private $matcheService;
    private $role = 'matche';
    private $endpointName = 'partida(s)';

    public function __construct(){
        $this->matcheService = new MatcheService($this->role, new Matche());
    }

    public function matcheOdds($apieventsSportId, $apieventsLeagueId, $bet365MatcheId){

        try {
            $item = $this->matcheService->getMatche($apieventsSportId, $apieventsLeagueId, $bet365MatcheId);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'item' => $item,
        ],  'partida selecionada com sucesso!');
    }

}
