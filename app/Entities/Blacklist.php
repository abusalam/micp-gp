<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Blacklist extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];


  public function getBlacklistNo()
  {
    return $this->attributes['blacklist_no'] ?? '';
  }
  public function enable()
  {
    /**
     * ENUM 1-enabled, 2-disabled
     */
    $this->attributes['status'] = 'enabled';
    return $this;
  }

  public function disable()
  {
    /**
     * ENUM 1-enabled, 2-disabled
     */
    $this->attributes['status'] = 'disabled';
    return $this;
  }
}
