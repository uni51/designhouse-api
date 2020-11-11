<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ITeam;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    protected $teams;

    public function __construct(ITeam $teams)
    {
        $this->teams = $teams;
    }


    /**
     * Get list of all teams (eg for Search)
     */
    public function index(Request $request)
    {

    }

    /**
     * Save team to database
     */
    public function store(Request $request)
    {

    }

    /**
     * Update team information
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Get the teams that the current user belongs to
     */
    public function fetchUserTeams()
    {

    }

    /**
     * Get team by slug for Public view
     */
    public function findBySlug($slug)
    {

    }

    /**
     * Destroy (delete) a team
     */
    public function destroy($id)
    {

    }
}
