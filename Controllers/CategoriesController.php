<?php

include 'db.php';
include 'crud.php';

class Categories extends DB implements Crud {
  private $dbTable = 'categories';

  public function add($category = []) {
    if (empty($category['title'])) {
      throw new Exception('Title is required');
    }

    $categoryId = parent::insert("INSERT INTO $this->dbTable (title, parent_id) VALUES (?, ?)", [
      ['type' => 's', 'value' => $category['title']],
      ['type' => 'i', 'value' => $category['parent_id'] ?? null]
    ]);

    $category = parent::select("SELECT * FROM $this->dbTable WHERE id = ? AND rec_status = 1", [
      ['type' => 'i', 'value' => $categoryId]
    ]);

    return $category;
  }

  public function edit($category = []) {
    if (empty($category['id'])) {
      throw new Exception('ID is required');
    }

    $result = parent::update("UPDATE $this->dbTable SET title = ?, parent_id = ? WHERE id = ?", [
      ['type' => 's', 'value' => $category['title']],
      ['type' => 'i', 'value' => $category['parent_id'] ?? null],
      ['type' => 'i', 'value' => $category['id']]
    ]);

    if ($result) {
      $category = parent::select("SELECT * FROM $this->dbTable WHERE id = ? AND rec_status = 1", [
        ['type' => 'i', 'value' => $category['id']]
      ]);
  
      return $category;
    }
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
    if (empty($id)) {
      throw new Exception('ID is required');
    }

    parent::update("UPDATE $this->dbTable SET rec_status = 0 WHERE id = ?", [
      ['type' => 'i', 'value' => $id]
    ]);
  }
}