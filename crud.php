<?php

interface Crud {
  public function get($id = null);

  public function add($category = []);

  public function edit($category = []);

  public function delete($id = null);
}