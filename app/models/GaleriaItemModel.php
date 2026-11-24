<?php
declare(strict_types=1);

namespace App\Models;

use Core\BaseModel;

class GaleriaItemModel extends BaseModel
{
    protected string $table = 'SIG_GaleriaItems';
    protected string $primaryKey = 'galeria_item_id';

    protected array $fillable = ["galeria_id", "item_id"];
    protected array $required = ["galeria_id", "item_id"];
    protected array $types = ['galeria_id' => 'int', 'item_id' => 'int'];
}
