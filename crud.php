<?php

interface Crud {
  public function get($id = null);

  public function add($category = []);

  public function update($id, $category = []);

  public function delete($id = null);
}