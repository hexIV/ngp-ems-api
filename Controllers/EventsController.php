<?php

include 'db.php';
include 'crud.php';

class Events extends DB implements Crud {
  private $dbTable = 'events';

  public function add($event = []) {
    if (empty($event['title'])) {
      throw new Exception('Title is required');
    }

    if (empty($event['city_id'])) {
      throw new Exception('City is required');
    }

    if (empty($event['starts_on'])) {
      throw new Exception('Start Date is required');
    }

    if (empty($event['ends_on'])) {
      throw new Exception('End Date is required');
    }

    $eventId = parent::insert("INSERT INTO $this->dbTable (title, city_id, starts_on, ends_on) VALUES (?, ?, ?, ?)", [
      ['type' => 's', 'value' => $event['title']],
      ['type' => 'i', 'value' => $event['city_id']],
      ['type' => 's', 'value' => $event['starts_on']],
      ['type' => 's', 'value' => $event['ends_on']],
    ]);

    foreach ($event['categories'] as $categoryId) {
      parent::insert("INSERT INTO events_categories (event_id, category_id) VALUES (?, ?)", [
        ['type' => 'i', 'value' => $eventId],
        ['type' => 'i', 'value' => $categoryId],
      ]);
    }

    return $this->get($eventId);
  }

  public function edit($event = []) {
    if (empty($event['id'])) {
      throw new Exception('ID is required');
    }

    $result = parent::update("UPDATE $this->dbTable SET title = ?, city_id = ?, starts_on = ?, ends_on = ? WHERE id = ?", [
      ['type' => 's', 'value' => $event['title']],
      ['type' => 'i', 'value' => $event['city_id']],
      ['type' => 's', 'value' => $event['starts_on']],
      ['type' => 's', 'value' => $event['ends_on']],
      ['type' => 'i', 'value' => $event['id']],
    ]);

    parent::deleteRecords("DELETE FROM events_categories WHERE event_id = ?", [['type' => 'i', 'value' => $event['id']]]);

    foreach ($event['categories'] as $categoryId) {
      parent::insert("INSERT INTO events_categories (event_id, category_id) VALUES (?, ?)", [
        ['type' => 'i', 'value' => $event['id']],
        ['type' => 'i', 'value' => $categoryId],
      ]);
    }

    if ($result) {
      return $this->get($event['id']);
    }
  }

  public function get($id = null) {
    if (empty($id)) {
      $events = parent::select("SELECT * FROM $this->dbTable WHERE rec_status = 1 ORDER BY id DESC");
      return $events;
    } else {
      $event = parent::select("SELECT * FROM $this->dbTable WHERE id = ? AND rec_status = 1 ORDER BY id DESC", [
        ['type' => 'i', 'value' => $id]
      ]);
      $event[0]['categories'] = array_column(parent::select("SELECT category_id FROM events_categories WHERE rec_status = 1 AND event_id = ?", [
        ['type' => 'i', 'value' => $id]
      ]), 'category_id');

      return $event[0];
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