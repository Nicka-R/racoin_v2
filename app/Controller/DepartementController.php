<?php

namespace app\Controller;

use app\Model\Departement;

class DepartementController {

    protected $departments = array();

    public function getAllDepartments() {
        return Departement::orderBy('nom_departement')->get()->toArray();
    }
}