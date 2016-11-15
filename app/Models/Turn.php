<?php

namespace App\Models;

use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Turn extends Model {

    protected $turn;

    public function generateTurn($params) {

        $this->turn['params'] = $params;

        // We check in session/db the number of turns already done

        // The params will include the screen size to decide the type of grid we'll generate
        $this->_generateGrid();
        $this->_generateWinner();
        $this->_saveTurn();
        $this->_hideWinnerToUser();

        return $this->turn;


    }

    /** GENERATES THE GRID **/
    private function _generateGrid() {

        // TODO: use params(mobile) for creating less intensive grids (up to 32)
        $options = array(2, 4, 8, 16, 32, 64, 128, 256);
        $randIndex = rand(0, count($options) - 1);

        $this->turn['grid'] = $options[$randIndex];

    }

    /** GENERATES THE WINNER FOR THE GRID **/
    private function _generateWinner() {

        if (!isset($this->turn['grid'])) throw new \Exception;

        $this->turn['winner'] = rand(0, $this->turn['grid'] - 1);

    }

    /** SAVE TURN **/
    private function _saveTurn() {

        session_start();
        $_SESSION['turn'] = $this->turn;

    }

    /** HIDE WINNER **/
    private function _hideWinnerToUser() {

        // After having saved the turn, we remove the winner before returning it and rendering to the user
        unset($this->turn['winner']);

    }

}