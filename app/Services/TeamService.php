<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class TeamService {


    /**
     * Create a user team (Spatie role)
     *
     * @param $data
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function createTeam($data)
    {
        $team = Role::create(['name' => $data->name, 'guard_name' => 'web']);

        return $team;
    }

    /**
     * Update a user team (Spatie role)
     *
     * @param $data
     *
     * @return \Spatie\Permission\Contracts\Role
     */
    public function updateTeam($data, $teamId)
    {
        $team = Role::findById($teamId);
        $team->name = $data->name;
        $team->save();

        return $team;
    }

    /**
     * Return the team from the database
     *
     * @param $teamId
     *
     * @return \Spatie\Permission\Contracts\Role
     */
    public function getById($teamId)
    {
        return Role::findById($teamId);
    }

    /**
     * Get all teams.
     *
     * @return iterable
     */
    public function getAll()
    {
        return Role::whereNotIn('name', ['Super Admin', 'Admin', 'Individual', 'Organisation', 'Venue'])->get();
    }

    /**
     * Delete the team from the database
     *
     * @param $userId
     */
    public function deleteTeam($teamId)
    {
        $team = Role::findById($teamId);
        $team->delete();
    }

    /**
     * Return all the roles related to administration
     *
     * @return \Spatie\Permission\Models\Role[]
     */
    public function getAllAdminRoles()
    {
        return Role::where('name', 'like', '%Admin%')->get(); // Admin, Super Admin
    }

    /**
     * Return all the roles related to teams
     * (just admins can be assigned to teams)
     *
     * @return \Spatie\Permission\Models\Role[]
     */
    public function getAllTeamRoles()
    {
        return Role::orWhere(function ($query) {
            $query->where('name', 'not like', '%Super admin%')
                ->where('name', 'not like', '%Admin%')
                ->where('name', 'not like', '%Registered%');
        })->get();
    }

    /**
     * Return all the roles Admins and Teams
     *
     * @param null $userId
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllUserRoles($userId = null)
    {
        return Role::all()->pluck('name');
    }




}