<?php

include 'db.php';
include 'crud.php';

class Categories extends DB implements Crud {
  private $dbTable = 'categories';

  public function add($category = []) {

  }

  public function update($id, $category = []) {

  }

  public function get($id = null) {
    if (empty($id)) {
      $categories = parent::select("SELECT * FROM $this->dbTable WHERE rec_status = 1 ORDER BY id DESC");
      return $categories;
    } else {
      $category = parent::select("SELECT * FROM $this->dbTable WHERE id = ? AND rec_status = 1 ORDER BY id DESC", [
        ['type' => 'i', 'value' => $id]
      ]);

      return $category[0];
    }
  }

  public function delete($id = null) {
    
  }
}