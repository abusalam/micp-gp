<?php

namespace App\Models;

use CodeIgniter\Model;

class BlacklistModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'blacklists';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\Blacklist';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'blacklist_no',
      'reason',
      'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
      'blacklist_no' => 'required|is_unique[blacklists.blacklist_no]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

  public function fake(&$faker)
  {
    return [
      'blacklist_no'    => $faker->ean8,
      'reason'   => $faker->sentence($nbWords = 4, $variableNbWords = true),
      'status'    => $faker->randomElement(['enabled', 'disabled']),
    ];
  }
}
