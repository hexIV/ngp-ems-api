<?php

include 'db.php';
include 'crud.php';

class Cities extends DB implements Crud {
  private $dbTable = 'cities';

  public function add($city = []) {
    if (empty($city['title'])) {
      throw new Exception('Title is required');
    }

    $cityId = parent::insert("INSERT INTO $this->dbTable (title) VALUES (?)", [
      ['type' => 's', 'value' => $city['title']]
    ]);

    return $this->get($cityId);
  }

  public function edit($city = []) {
    if (empty($city['id'])) {
      throw new Exception('ID is required');
    }

    $result = parent::update("UPDATE $this->dbTable SET title = ? WHERE id = ?", [
      ['type' => 's', 'value' => $city['title']],
      ['type' => 'i', 'value' => $city['id']]
    ]);

    if ($result) {
      return $this->get($city['id']);
    }
  }

  public function get($id = null) {
    if (empty($id)) {
      $cities = parent::select("SELECT * FROM $this->dbTable WHERE rec_status = 1 ORDER BY id DESC");
      return $cities;
    } else {
      $city = parent::select("SELECT * FROM $this->dbTable WHERE id = ? AND rec_status = 1 ORDER BY id DESC", [
        ['type' => 'i', 'value' => $id]
      ]);

      return $city[0];
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