<?php
namespace App\Repositories\Color;

interface ColorRepositoryInterface{

    public function get($color_id);

    public function all();

    public function create($color_data);

    public function update($color_id, array $color_data);

    public function delete($color_id);
}