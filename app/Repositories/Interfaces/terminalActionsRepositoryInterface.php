<?php

namespace App\Repositories\Interfaces;

interface terminalActionsRepositoryInterface
{
    public function index();
    public function dataTable();
    public function store(array $attributes);
    public function show($id);
    public function update(array $attributes, array $updateFlag);
    public function delete($id);
}
