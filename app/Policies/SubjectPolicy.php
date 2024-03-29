<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Subject $subject)
    {
        if ($user->role === 'student') {
            $subjects = $user->student->group->subjects;
        }
        else if ($user->role === 'teacher') {
            $subjects = $user->subjects;
        }
        else if ($user->role === 'head') {
            $subjects = array();
            $department_groups = $user->department->groups;
            foreach ($department_groups as $group) {
                foreach ($group->subjects as $subj) {
                    $subjects[] = $subj;
                }
            }
        }

        else {
            return false;
        }

        foreach ($subjects as $subj) {
            if ($subj == $subject) {
                return true;
            }
        }
    }

    public function view_create_form(User $user) {
        if ($user->role === 'head') {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Group $group) {
        if ($user->role === 'head') {
            $deparment = $user->department;
            $groups = $deparment;
            foreach ($groups as $g) {
                if ($g == $group) {
                    return true;
                }
            }
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Subject $subject)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Subject $subject)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Subject $subject)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Subject $subject)
    {
        //
    }
}
