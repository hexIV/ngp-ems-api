<?php

include 'db.php';

class CategoriesTree extends DB {
  private $dbTable = 'categories';

  public function get() {
    // to use cache here
    $categories = parent::select("SELECT * FROM categories WHERE parent_id IS NULL AND rec_status = 1");
    foreach ($categories as &$category) {
      $category['children'] = $this->getSubCategories($category);
    }

    return $categories;
  }

  private function getSubCategories($category) {
    $subCategories = parent::select("SELECT * FROM categories WHERE parent_id = ? AND rec_status = 1", [['type' => 'i', 'value' => $category['id']]]);
    foreach ($subCategories as &$subCategory) {
      $subCategory['children'] = $this->getSubCategories($subCategory);
    }

    return $subCategories;
  }
}